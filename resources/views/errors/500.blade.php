@extends('layouts.app')

@section('content')
    <div class="container d-flex flex-column align-items-center" style="min-height: 100vh; background-color: #f8f9fa; padding: 50px;">
        <div class="text-center bg-white rounded shadow p-5" style="max-width: 400px;">
            <h1 class="display-4 text-danger">Error 500</h1>
            <p class="lead">Ha ocurrido un error en el servidor. Por favor, inténtalo más tarde.</p>
            <img src="https://media.giphy.com/media/3o6ZsY90A3YayO3b1S/giphy.gif" alt="Error" class="img-fluid mb-3" style="max-width: 150px;">
            <a href="{{ url('/') }}" class="btn btn-primary btn-lg">Volver al inicio</a>
        </div>
    </div>
@endsection
