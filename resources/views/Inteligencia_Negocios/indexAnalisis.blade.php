@extends('layouts.app')

@section('content')
    <div class="container mt-7">
        <h1 class="text-center mb-8">INTELIGENCIA DE NEGOCIOS</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->has('error'))
            <div class="alert alert-danger d-flex align-items-center" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2"
                    viewBox="0 0 16 16" role="img" aria-label="Warning:" width="24" height="24" fill="currentColor">
                    <path
                        d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                </svg>
                <div>
                    {{ $errors->first('error') }}
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-xs-12">
                <div id='tableauViz'>
                    <script >
                        // Configurar la URL de la visualización
                                //var url = 'https://prod-useast-b.online.tableau.com/t/biwebcoopec2/views/AnalisisExterno/MapaInteractivo?:origin=card_share_link&:embed=n';
                                var url = 'https://prod-useast-a.online.tableau.com/t/businessintelligencewebcoopec/views/Indicadores_17176868137900/INFORMACINFINACNIERA?:origin=card_share_link&:embed=n';
                                // Configurar las opciones de la visualización
                                var options = {
                                width: '100%',
                                height: '800px',
                                hideToolbar: true,
                                hideTabs: true
                                };
                                // Obtener el contenedor de la visualización
                                var containerDiv = document.getElementById('tableauViz');

                                // Crear el objeto de visualización de Tableau
                                var viz = new tableau.Viz(containerDiv, url, options);
                    </script>
                </div>
            </div>   


   
@endsection

