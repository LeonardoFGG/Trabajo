<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    private function userHasAccess()
    {
        // Verifica si el usuario es admin o tiene ID 8
        return Auth::user()->id == 8 || Auth::user()->role === 'admin';
    }

    public function index()
    {
        if (!$this->userHasAccess()) {
            return redirect()->back()->withErrors('No tienes permiso para ver esta página.');
        }

        $imagenes = Image::all();
        return view('Imagen.index', compact('imagenes'));
    }

    public function create()
    {
        if (!$this->userHasAccess()) {
            return redirect()->back()->withErrors('No tienes permiso para acceder a esta página.');
        }

        return view('imagen.create');
    }

    public function upload(Request $request)
    {
        if (!$this->userHasAccess()) {
            return redirect()->back()->withErrors('No tienes permiso para subir imágenes.');
        }

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
        $request->file('image')->move(public_path('uploads'), $imageName);

        Image::create([
            'file_path' => $imageName,
            'empleado_id' => auth()->user()->id,
        ]);

        return redirect()->back()->with('success', 'Imagen subida con éxito');
    }

    public function destroy($id)
    {
        $imagen = Image::find($id);

        if (!$imagen) {
            return redirect()->back()->withErrors('La imagen no existe.');
        }

        // Verifica que el usuario tenga permiso para eliminar
        if (auth()->user()->id != $imagen->empleado_id && Auth::user()->role !== 'admin') {
            return redirect()->back()->withErrors('No tienes permiso para eliminar esta imagen.');
        }

        unlink(public_path('uploads/' . $imagen->file_path));
        $imagen->delete();

        return redirect()->back()->with('success', 'Imagen eliminada con éxito');
    }
}
