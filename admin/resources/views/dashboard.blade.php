@extends('layouts.main')

@section('title', 'Home')
@section('breadcrumb-item', 'Painel')

@section('breadcrumb-item-active', 'Painel')

@section('css')
    <!-- map-vector css -->
    <link rel="stylesheet" href="{{ URL::asset('build/css/plugins/jsvectormap.min.css') }}">
@endsection

@section('content')

    <!-- [ Main Content ] start -->
    <div class="row">
        <div class="col-md-4 col-sm-6">
            <div class="card statistics-card-1 overflow-hidden ">
                <div class="card-body">
                    <img src="{{ URL::asset('build/images/widget/img-status-4.svg') }}" alt="img" class="img-fluid img-bg">
                    <h5 class="mb-4">Contas a Receber</h5>
                    <div class="d-flex align-items-center mt-3">
                        <h3 class="f-w-300 d-flex align-items-center m-b-0">R$ 5.249,95</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-6">
            <div class="card statistics-card-1 overflow-hidden ">
                <div class="card-body">
                    <img src="{{ URL::asset('build/images/widget/img-status-4.svg') }}" alt="img" class="img-fluid img-bg">
                    <h5 class="mb-4">Contas a pagar</h5>
                    <div class="d-flex align-items-center mt-3">
                        <h3 class="f-w-300 d-flex align-items-center m-b-0">R$ 5.249,95</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-6">
            <div class="card statistics-card-1 overflow-hidden ">
                <div class="card-body">
                    <img src="{{ URL::asset('build/images/widget/img-status-4.svg') }}" alt="img" class="img-fluid img-bg">
                    <h5 class="mb-4">Notas Fiscais para emitir</h5>
                    <div class="d-flex align-items-center mt-3">
                        <h3 class="f-w-300 d-flex align-items-center m-b-0">R$ 5.249,95</h3>
                    </div>
                </div>
            </div>
        </div>

       

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-4">Serviços em Andamento</h5>
                    <div id="map" style="height: 400px;"></div>
                </div>
            </div>
        </div>

        <script>
            function initMap() {
                var map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 6,
                    center: {lat: -30.062164754822422, lng: -51.107141863652394} // Example coordinates (São Paulo, Brazil)
                });     

                var locations = [
                    @foreach($localidadesOS as $localidade)
                        {lat: {{ $localidade['latitude'] }}, lng: {{ $localidade['longitude'] }}, title: '{{ $localidade['cliente'] }}'},
                    @endforeach
                ];

                locations.forEach(function(location) {
                    new google.maps.Marker({
                        position: {lat: location.lat, lng: location.lng},
                        map: map,
                        title: location.title
                    });
                });
            }
        </script>
        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCWElviRT5t3A1PhEGKEId4EE2EDXOc4w4&callback=initMap"></script>


    </div>
    <!-- [ Main Content ] end -->
@endsection

@section('scripts')
    <!-- [Page Specific JS] start -->
    <script src="{{ URL::asset('build/js/plugins/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/plugins/jsvectormap.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/plugins/world.js') }}"></script>
    <script src="{{ URL::asset('build/js/plugins/world-merc.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/dashboard-default.js') }}"></script>
    <!-- [Page Specific JS] end -->
@endsection
