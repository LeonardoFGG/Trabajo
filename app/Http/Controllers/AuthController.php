<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class AuthController extends Controller
{
	/**
	 * Función que muestra la vista de logados o la vista con el formulario de Login
	 */
	public function index()
	{
		// Comprobamos si el usuario ya está logado
		if (Auth::check()) {

			// Si está logado le mostramos la vista de logados
			return view('logados');
		}

		// Si no está logado le mostramos la vista con el formulario de login
		return view('login');
	}

	/**
	 * Función que se encarga de recibir los datos del formulario de login, comprobar que el usuario existe y
	 * en caso correcto logar al usuario
	 */
	public function login(Request $request)

	{
		// Validamos los datos del formulario
		$request->validate([
			'email' => 'required|email',
			'password' => 'required',
		]);

		// Comprobamos si los datos son correctos y logamos al usuario
		if (Auth::attempt($request->only('email', 'password'))) {
			return redirect('/logados')->withSuccess('Has iniciado sesión correctamente');
		}

		// Si los datos no son correctos volvemos al formulario de login con un mensaje de error
		return back()->withInput()->with('error', 'Usuario o contraseña incorrectos');
	}
	
	/**
	 * Función que muestra la vista de logados si el usuario está logado y si no le devuelve al formulario de login
	 * con un mensaje de error
	 */
	public function logados()
	{
		if (Auth::check()) {
			return view('logados');
		}

		return redirect("/")->withSuccess('No tienes acceso, por favor inicia sesión');
	}

	public function home()
	{
		// Asegúrate de que el usuario esté autenticado
		if (Auth::check()) {
			return view('home'); // Asegúrate de tener la vista 'home.blade.php'
		}

		return redirect('/')->with('error', 'No tienes acceso, por favor inicia sesión.');
	}
}
