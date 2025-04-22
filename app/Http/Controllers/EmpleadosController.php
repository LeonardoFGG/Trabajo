<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleados;
use Illuminate\Support\Facades\Hash;
use App\Models\Departamento;
use App\Models\Cargos;
use App\Models\Rubro;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Supervisor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


class EmpleadosController extends Controller
{
    public function index()
    {
        $empleados = Empleados::with('departamento')
            ->latest()
            ->paginate(30);
        return view('Empleados.indexEmpleados', compact('empleados'));
    }

    public function create()
    {
        $empleado = new Empleados();
        $departamentos = Departamento::all();
        $supervisores = Supervisor::all();
        $empleadosSupervisores = Empleados::where('es_supervisor', true)->get(); // Empleados marcados como supervisores
        $supervisoresSuperiores = Supervisor::where('es_supervisor_superior', true)->get();
        $cargos = Cargos::all();
        $rubros = Rubro::all();




        return view('Empleados.createEmpleados', compact('empleado', 'departamentos', 'cargos', 'rubros', 'supervisores', 'empleadosSupervisores', 'supervisoresSuperiores'));
    }

    public function store(Request $request)
    {
        // Validación de datos
        $validated = $request->validate([
            'nombre1' => 'required|string|max:255',
            'apellido1' => 'required|string|max:255',
            'nombre2' => 'nullable|string|max:255',
            'apellido2' => 'nullable|string|max:255',
            'cedula' => 'required|string|max:10|unique:empleados',
            'fecha_nacimiento' => 'required|date',
            'telefono' => 'nullable|string|max:15',
            'celular' => 'required|string|max:15',
            'correo_institucional' => 'required|string|email|max:255|unique:empleados',
            'departamento_id' => 'required|exists:departamentos,id',
            'curriculum' => 'nullable|file|mimes:pdf,doc,docx,jpg,png',
            'contrato' => 'nullable|file|mimes:pdf,jpg,png,doc,docx',
            'contrato_confidencialidad' => 'nullable|file|mimes:pdf,jpg,png,doc,docx',
            'contrato_consentimiento' => 'nullable|file|mimes:pdf,jpg,png,doc,docx',
            'fecha_ingreso' => 'required|date',
            'cargo_id' => 'required|exists:cargos,id',
            'es_supervisor' => 'nullable|boolean',
            'supervisor_id' => 'nullable|exists:supervisores,id', // Para asociar con supervisor superior
            'jornada_laboral' => 'required|string|max:255',
            'fecha_contratacion' => 'required|date',
            'fecha_conclusion_contrato' => 'nullable|date',
            'terminacion_contrato' => 'nullable|string|max:255',
            'fecha_recontratacion' => 'nullable|date',
            'rubros' => 'nullable|array',
            'rubros.*' => 'exists:rubros,id',
            'monto_rubro' => 'nullable|array',
            'monto_rubro.*' => 'numeric',
        ]);

        // Crear empleado
        $empleados = new Empleados($validated);

        // Crear el usuario asociado al empleado
        $user = User::create([
            'name' => $validated['nombre1'] . ' ' . $validated['apellido1'],
            'email' => $validated['correo_institucional'],
            'password' => Hash::make($validated['cedula']),
            'role' => 'empleado',
        ]);
        $empleados->user_id = $user->id;

        // Subir archivos y guardarlos en el directorio público
        if ($request->hasFile('curriculum')) {
            $file = $request->file('curriculum');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('curriculums'), $filename);
            $empleados->curriculum = 'curriculums/' . $filename;
        }

        if ($request->hasFile('contrato')) {
            $file = $request->file('contrato');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('contratos_empleados'), $filename);
            $empleados->contrato = 'contratos_empleados/' . $filename;
        }

        if ($request->hasFile('contrato_confidencialidad')) {
            $file = $request->file('contrato_confidencialidad');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('contratos_confidencialidad'), $filename);
            $empleados->contrato_confidencialidad = 'contratos_confidencialidad/' . $filename;
        }

        if ($request->hasFile('contrato_consentimiento')) {
            $file = $request->file('contrato_consentimiento');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('contratos_consentimiento'), $filename);
            $empleados->contrato_consentimiento = 'contratos_consentimiento/' . $filename;
        }

        // Guardar el empleado en la base de datos
        $empleados->save();

        // Si el empleado no es supervisor, asociamos al supervisor del departamento
        if (!$empleados->es_supervisor) {
            // Obtener el supervisor del departamento
            $departamento = Departamento::find($empleados->departamento_id);
            if ($departamento && $departamento->supervisor_id) {
                $empleados->supervisor_id = $departamento->supervisor_id; // Asignamos el supervisor del departamento
                $empleados->save();
            }
        }

        // Si el empleado es supervisor, registrar en la tabla 'supervisores'
        if ($empleados->es_supervisor) {
            $supervisor = new Supervisor([
                'empleado_id' => $empleados->id,
                'nombre_supervisor' => $empleados->nombre1 . ' ' . $empleados->apellido1,
                'departamento_id' => $empleados->departamento_id,
            ]);
            $supervisor->save();

            // Si se ha seleccionado un supervisor superior, asociamos a este supervisor
            if ($request->has('supervisor_id') && $request->input('supervisor_id') != '') {
                $supervisorSuperior = Supervisor::find($request->input('supervisor_id'));
                if ($supervisorSuperior) {
                    // Guardamos el supervisor superior en la tabla 'empleados'
                    $empleados->supervisor_id = $supervisorSuperior->empleado_id; // Relación en empleados
                    $empleados->save();

                    // Guardamos el supervisor superior en la tabla 'supervisores'
                    $supervisor->supervisor_id = $supervisorSuperior->id; // Relación recursiva en supervisores
                    $supervisor->save();
                }
            }

            // Si el supervisor es supervisor superior, asignar automáticamente el supervisor superior
            if ($request->has('es_supervisor_superior') && $request->input('es_supervisor_superior') == true) {
                $existeSupervisorSuperior = Supervisor::where('es_supervisor_superior', true)
                    ->where('departamento_id', $empleados->departamento_id) // Asegurarse de que sea del mismo departamento
                    ->first();
                if ($existeSupervisorSuperior) {
                    // Si ya existe un supervisor superior, asignamos ese supervisor superior automáticamente
                    $empleados->supervisor_id = $existeSupervisorSuperior->empleado_id; // Asignar al empleado el supervisor superior
                    $empleados->save();
                }

                // Marcar al supervisor como supervisor superior
                $supervisor->es_supervisor_superior = true;
                $supervisor->save();
            }

            // Asignar el supervisor automáticamente al departamento
            Departamento::where('id', $empleados->departamento_id)->update([
                'supervisor_id' => $supervisor->id,
            ]);
        }

        // Asignar rubros con sus montos si se han proporcionado
        if ($request->filled('rubros') && $request->filled('monto_rubro')) {
            $rubros = $request->input('rubros');
            $montos = $request->input('monto_rubro');

            // Sincronizar los rubros con sus montos en la tabla pivote
            $empleados->rubros()->sync(
                collect($rubros)->mapWithKeys(function ($rubroId) use ($montos) {
                    return [$rubroId => ['monto' => $montos[$rubroId] ?? 0]]; // Valor predeterminado si no está presente
                })->toArray()
            );
        }

        // Redirigir con mensaje de éxito
        return redirect()->route('empleados.indexEmpleados')->with('success', 'Empleado creado con éxito.');
    }



    public function getSupervisoresPorDepartamento($departamento_id)
    {
        // Obtener los supervisores que pertenecen al departamento seleccionado
        $supervisores = Empleados::where('departamento_id', $departamento_id)
            ->where('es_supervisor', true)
            ->get();

        return response()->json($supervisores);
    }

    public function getSupervisores($departamento_id)
    {
        $supervisores = Supervisor::where('departamento_id', $departamento_id)->get();
        return response()->json(['supervisores' => $supervisores]);
    }

    public function show($id)
    {
        $empleados = Empleados::find($id);
        $empleados = Empleados::with('departamento')->findOrFail($id);
        $empleados = Empleados::with('rubros')->findOrFail($id);
        $empleados = Empleados::with('cargo')->findOrFail($id);
        $empleados = Empleados::with('supervisor')->findOrFail($id);

        // Calcular los totales de ingresos y egresos
        $totalIngreso = 0;
        $totalEgreso = 0;

        foreach ($empleados->rubros as $rubro) {
            if ($rubro->tipo_rubro == 'ingreso') {
                $totalIngreso += $rubro->pivot->monto;  // Acceder al monto a través del campo 'pivot'
            } elseif ($rubro->tipo_rubro == 'egreso') {
                $totalEgreso += $rubro->pivot->monto;
            }
        }

        return view('Empleados.show',  compact('empleados', 'totalIngreso', 'totalEgreso'));
    }

    public function edit($id)
    {
        $empleados = Empleados::find($id);
        $departamentos = Departamento::all();
        $supervisores = Supervisor::all();
        $cargos = Cargos::all();
        $rubros = Rubro::all();
        return view('Empleados.editEmpleados', compact('empleados', 'departamentos', 'supervisores', 'cargos', 'rubros'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nombre1' => 'required|string|max:255',
            'apellido1' => 'required|string|max:255',
            'nombre2' => 'nullable|string|max:255',
            'apellido2' => 'nullable|string|max:255',
            'cedula' => [
                'required',
                'string',
                'max:10',
                Rule::unique('empleados')->ignore($id),
            ],
            'fecha_nacimiento' => 'required|date',
            'telefono' => 'nullable|string|max:15',
            'celular' => 'required|string|max:15',
            'correo_institucional' => 'required|string|email|max:255|unique:empleados,correo_institucional,' . $id,
            'departamento_id' => 'required|exists:departamentos,id',
            'curriculum' => 'nullable|file|mimes:pdf,doc,docx,jpg,png',
            'contrato' => 'nullable|file|mimes:pdf,jpg,png,doc,docx',
            'contrato_confidencialidad' => 'nullable|file|mimes:pdf,jpg,png,doc,docx',
            'contrato_consentimiento' => 'nullable|file|mimes:pdf,jpg,png,doc,docx',
            'fecha_ingreso' => 'required|date',
            'cargo_id' => 'required|exists:cargos,id',
            'es_supervisor' => 'nullable|boolean',
            'supervisor_id' => 'nullable|exists:supervisores,id',
            'jornada_laboral' => 'required|string|max:255',
            'fecha_contratacion' => 'required|date',
            'fecha_conclusion_contrato' => 'nullable|date',
            'terminacion_contrato' => 'nullable|string|max:255',
            'fecha_recontratacion' => 'nullable|date',
            'rubros' => 'nullable|array',
            'rubros.*' => 'exists:rubros,id',
            'monto_rubro' => 'nullable|array',
            'monto_rubro.*' => 'numeric',
        ]);

        $empleados = Empleados::findOrFail($id);
        $empleados->update($validated);



        // Subir nuevos archivos y eliminar los antiguos si existen
        if ($request->hasFile('curriculum')) {
            // Verificar si la carpeta existe, si no, crearla
            $folderPath = public_path('curriculums');
            if (!File::exists($folderPath)) {
                // Crear la carpeta si no existe
                File::makeDirectory($folderPath, 0755, true);
            }

            // Verificar si ya existe un archivo antiguo y eliminarlo si es necesario
            if ($empleados->curriculum && file_exists(public_path($empleados->curriculum))) {
                unlink(public_path($empleados->curriculum)); // Eliminar archivo antiguo
            }

            // Subir el nuevo archivo
            $file = $request->file('curriculum');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('curriculums'), $filename);

            // Guardar la ruta relativa en la base de datos
            $empleados->curriculum = 'curriculums/' . $filename;
        }

        if ($request->hasFile('contrato')) {
            // Verificar si la carpeta existe, si no, crearla
            $folderPath = public_path('contratos_empleados');
            if (!File::exists($folderPath)) {
                // Crear la carpeta si no existe
                File::makeDirectory($folderPath, 0755, true);
            }

            // Verificar si ya existe un archivo antiguo y eliminarlo si es necesario
            if ($empleados->contrato && file_exists(public_path($empleados->contrato))) {
                unlink(public_path($empleados->contrato)); // Eliminar archivo antiguo
            }

            // Subir el nuevo archivo
            $file = $request->file('contrato');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('contratos_empleados'), $filename);

            // Guardar la ruta relativa en la base de datos
            $empleados->contrato = 'contratos_empleados/' . $filename;
        }

        if ($request->hasFile('contrato_confidencialidad')) {
            // Verificar si la carpeta existe, si no, crearla
            $folderPath = public_path('contratos_confidencialidad');
            if (!File::exists($folderPath)) {
                // Crear la carpeta si no existe
                File::makeDirectory($folderPath, 0755, true);
            }

            // Verificar si ya existe un archivo antiguo y eliminarlo si es necesario
            if ($empleados->contrato_confidencialidad && file_exists(public_path($empleados->contrato_confidencialidad))) {
                unlink(public_path($empleados->contrato_confidencialidad)); // Eliminar archivo antiguo
            }

            // Subir el nuevo archivo
            $file = $request->file('contrato_confidencialidad');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('contratos_confidencialidad'), $filename);

            // Guardar la ruta relativa en la base de datos
            $empleados->contrato_confidencialidad = 'contratos_confidencialidad/' . $filename;
        }


        if ($request->hasFile('contrato_consentimiento')) {
            // Verificar si la carpeta existe, si no, crearla
            $folderPath = public_path('contratos_consentimiento');
            if (!File::exists($folderPath)) {
                // Crear la carpeta si no existe
                File::makeDirectory($folderPath, 0755, true);
            }

            // Verificar si ya existe un archivo antiguo y eliminarlo si es necesario
            if ($empleados->contrato_consentimiento && file_exists(public_path($empleados->contrato_consentimiento))) {
                unlink(public_path($empleados->contrato_consentimiento)); // Eliminar archivo antiguo
            }

            // Subir el nuevo archivo
            $file = $request->file('contrato_consentimiento');
            $filename = time() . '_' . $file->getClientOriginalName();

            // Mover el archivo a la carpeta 'contratos_consentimiento' dentro de public
            $file->move(public_path('contratos_consentimiento'), $filename);

            // Guardar la ruta relativa en la base de datos (esto estará en 'contratos_consentimiento/...')
            $empleados->contrato_consentimiento = 'contratos_consentimiento/' . $filename;
        }

        if (!$empleados->es_supervisor) {
            // Obtener el supervisor del departamento
            $departamento = Departamento::find($empleados->departamento_id);
            if ($departamento && $departamento->supervisor_id) {
                $empleados->supervisor_id = $departamento->supervisor_id;
                $empleados->save();
            }
        }

        if ($empleados->es_supervisor) {
            $supervisor = Supervisor::updateOrCreate(
                ['empleado_id' => $empleados->id],
                [
                    'nombre_supervisor' => $empleados->nombre1 . ' ' . $empleados->apellido1,
                    'departamento_id' => $empleados->departamento_id,
                ]
            );

            // Si se ha seleccionado un supervisor superior, asociamos a este supervisor
            if ($request->has('supervisor_id') && $request->input('supervisor_id') != '') {
                $supervisorSuperior = Supervisor::find($request->input('supervisor_id'));
                if ($supervisorSuperior) {
                    $empleados->supervisor_id = $supervisorSuperior->empleado_id;
                    $empleados->save();

                    $supervisor->supervisor_id = $supervisorSuperior->id;
                    $supervisor->save();
                }
            }

            // Si el supervisor es supervisor superior, asignar automáticamente el supervisor superior
            if ($request->has('es_supervisor_superior') && $request->input('es_supervisor_superior') == true) {
                $existeSupervisorSuperior = Supervisor::where('es_supervisor_superior', true)
                    ->where('departamento_id', $empleados->departamento_id)
                    ->first();
                if ($existeSupervisorSuperior) {
                    $empleados->supervisor_id = $existeSupervisorSuperior->empleado_id;
                    $empleados->save();
                }

                $supervisor->es_supervisor_superior = true;
                $supervisor->save();
            }

            Departamento::where('id', $empleados->departamento_id)->update([
                'supervisor_id' => $supervisor->id,
            ]);
        }

        if ($request->filled('rubros') && $request->filled('monto_rubro')) {
            $rubros = $request->input('rubros');
            $montos = $request->input('monto_rubro');

            $empleados->rubros()->sync(
                collect($rubros)->mapWithKeys(function ($rubroId) use ($montos) {
                    return [$rubroId => ['monto' => $montos[$rubroId] ?? 0]];
                })->toArray()
            );
        }


        // Guardar el registro de empleado
        $empleados->save();


        return redirect()->route('empleados.indexEmpleados')->with('success', 'Empleado actualizado con éxito.');
    }



    public function getEmployeeDetails($id)
    {
        $empleado = Empleados::with('departamento', 'cargo', 'supervisor')->findOrFail($id);

        return response()->json([
            'departamento' => $empleado->departamento->nombre ?? 'N/A',
            'cargo' => $empleado->cargo->nombre_cargo ?? 'N/A',
            'supervisor' => $empleado->supervisor->nombre_supervisor ?? 'N/A',
        ]);
    }

    public function toggleEstado($id)
    {
        $empleado = Empleados::findOrFail($id);
        $empleado->estado = !$empleado->estado; // Cambia de 1 a 0 o viceversa
        $empleado->save();

        return redirect()->back()->with('success', 'Estado actualizado correctamente.');
    }




    public function destroy($id)
    {
        $empleados = Empleados::findOrFail($id);

        // Eliminar los archivos asociados antes de eliminar el empleado
        if ($empleados->curriculum) {
            Storage::disk('public')->delete($empleados->curriculum);
        }

        if ($empleados->contrato) {
            Storage::disk('public')->delete($empleados->contrato);
        }

        if ($empleados->contrato_confidencialidad) {
            Storage::disk('public')->delete($empleados->contrato_confidencialidad);
        }

        if ($empleados->contrato_consentimiento) {
            Storage::disk('public')->delete($empleados->contrato_consentimiento);
        }

        $empleados->delete();

        return redirect()->route('empleados.indexEmpleados')->with('success', 'Empleado eliminado con éxito.');
    }
}
