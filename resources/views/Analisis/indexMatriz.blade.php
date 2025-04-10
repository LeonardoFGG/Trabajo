@extends('layouts.inteligencia')

@section('content')

<div class="container-fluid" style="padding: 0; margin: 0;">
    <div class="row">
        <div id="tableauViz" style="width: 100vw; height: 100vh;"></div>
    </div>
</div>

<script type="text/javascript" src="https://public.tableau.com/javascripts/api/tableau-2.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Configura la URL de la visualización
        var url = 'https://prod-useast-a.online.tableau.com/t/businessintelligencewebcoopec/views/ANALISISBALANCESWEBCOOPEC/Historia1?:origin=card_share_link&:embed=n';

        // Configura las opciones de la visualización
        var options = {
            width: '100%',
            height: '100%',
            hideToolbar: true,
            hideTabs: true
        };

        // Obtén el contenedor de la visualización
        var containerDiv = document.getElementById('tableauViz');

        // Crea la visualización
        var viz = new tableau.Viz(containerDiv, url, options);
    });
</script>
@endsection
