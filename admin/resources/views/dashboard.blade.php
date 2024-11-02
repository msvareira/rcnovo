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
        <script async defer src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_API_KEY')}}&callback=initMap"></script>


    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="mb-4">Próximas Preventivas</h5>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Funcionário Sugerido</th>
                                <th>Status</th>
                                <th>Descrição</th>
                                <th>Data de Execução</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($preventivasPendentes as $preventiva)
                                <tr>
                                    <td>{{ $preventiva->cliente->razao }}</td>
                                    <td class="funcionario-nome">{{ $preventiva->funcionario ? $preventiva->funcionario->nome : '' }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($preventiva->status == 'Concluído') 
                                                bg-success 
                                            @elseif(\Carbon\Carbon::parse($preventiva->data_execucao)->isPast()) 
                                                bg-danger 
                                            @else 
                                                bg-warning 
                                            @endif">
                                            {{ \Carbon\Carbon::parse($preventiva->data_execucao)->isPast() && $preventiva->status != 'Concluído' ? 'Atrasada' : $preventiva->status }}
                                        </span>
                                    </td>
                                    <td>{{ $preventiva->descricao }}</td>
                                    <td>{{ \Carbon\Carbon::parse($preventiva->data_execucao)->format('d/m/Y') }}</td>
                                    <td>
                                        <button type="button" title="Executar Preventiva" class="btn btn-primary btn-sm executar-preventiva" data-id="{{ $preventiva->id }}" data-descricao="{{ $preventiva->descricao }}">
                                            <i class="fas fa-play"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="executarPreventivaModal" tabindex="-1" role="dialog" aria-labelledby="executarPreventivaModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="executarPreventivaModalLabel">Executar Preventiva</h5>
                </div>
                <div class="modal-body">
                    <form id="executarPreventivaForm" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="funcionarioId">Funcionário</label>
                            <select class="form-control" id="funcionarioId" required>
                                <option value="">Selecione um Funcionário</option>
                                @foreach($funcionarios as $funcionario)
                                    <option value="{{ $funcionario->id }}">{{ $funcionario->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="descricaoExistente">Descrição</label>
                            <textarea class="form-control" id="descricaoExistente" rows="3" readonly></textarea>
                        </div>
                        <div class="form-group">
                            <label for="descricaoComplemento">Detalhes da Execução</label>
                            <textarea class="form-control" id="descricaoComplemento" rows="6" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="dataExecucao">Data de Execução</label>
                            <input type="date" class="form-control" id="dataExecucao" required>
                        </div>
                        <div class="form-group">
                            <label for="anexos">Anexos (somente imagens)</label>
                            <input type="file" class="form-control" id="anexos" name="anexos[]" accept="image/*" multiple required>
                        </div>
                        <input type="hidden" id="preventivaId">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="confirmarExecucao">Executar</button>
                </div>
            </div>
        </div>
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

    <script>
        $(document).ready(function() {
            $('.executar-preventiva').on('click', function() {
                var preventivaId = $(this).data('id');
                var descricaoExistente = $(this).data('descricao');

                $('#preventivaId').val(preventivaId);
                $('#descricaoExistente').val(descricaoExistente);
                $('#dataExecucao').val(new Date().toISOString().split('T')[0]); // Set current date as default
                $('#executarPreventivaModal').modal('show');
            });

            $('#confirmarExecucao').on('click', function() {
                var preventivaId = $('#preventivaId').val();
                var funcionarioId = $('#funcionarioId').val();
                var descricaoComplemento = $('#descricaoComplemento').val();
                var descricaoExistente = $('#descricaoExistente').val();
                var dataExecucao = $('#dataExecucao').val();
                var anexos = $('#anexos')[0].files;

                if (!funcionarioId || !descricaoComplemento || !dataExecucao || anexos.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Atenção',
                        text: 'Todos os campos são obrigatórios.'
                    });
                    return;
                }

                var formData = new FormData();
                formData.append('preventiva_id', preventivaId);
                formData.append('funcionario', funcionarioId);
                formData.append('descricao', descricaoExistente + ' - ' + descricaoComplemento);
                formData.append('data_execucao', dataExecucao);
                formData.append('_token', '{{ csrf_token() }}');

                for (var i = 0; i < anexos.length; i++) {
                    formData.append('anexos[]', anexos[i]);
                }

                Swal.fire({
                    title: 'Executando...',
                    text: 'Por favor, aguarde.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: '{{ route("preventivas.executar") }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        Swal.close();
                        $('#executarPreventivaModal').modal('hide');
                        var row = $('button[data-id="' + preventivaId + '"]').closest('tr');
                        row.find('.badge').removeClass('bg-warning').removeClass('bg-danger').addClass('bg-success').text('Concluído');
                        row.find('.funcionario-nome').text($('#funcionarioId option:selected').text());
                        $('button[data-id="' + preventivaId + '"]').remove();
                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso',
                            text: response.success
                        });
                    },
                    error: function(xhr) {
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro',
                            text: 'Erro ao executar preventiva.'
                        });
                    }
                });
            });
        });
    </script>
@endsection
