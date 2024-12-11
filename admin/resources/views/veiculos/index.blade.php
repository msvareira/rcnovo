@extends('layouts.main')

@section('title', 'Veículos')
@section('breadcrumb-item', 'Veículos')

@section('breadcrumb-item-active', 'Listagem de Veículos')

@section('css')
    <!-- [Page specific CSS] start -->
    <!-- data tables css -->
    <link rel="stylesheet" href="{{ URL::asset('build/css/plugins/dataTables.bootstrap5.min.css') }}">
    <!-- [Page specific CSS] end -->
    <style>
        /* Estilos para a tela de carregamento */
        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            z-index: 10000;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .loading-overlay .spinner-border {
            width: 3rem;
            height: 3rem;
        }
    </style>
@endsection

@section('content')
    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- DOM/Jquery table start -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('veiculos.create') }}" class="btn btn-success float-end mb-3">Adicionar Veículo</a>
                </div>

                <div class="card-body">
                    <div class="dt-responsive">
                        <table id="dom-jqry" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>Placa</th>
                                    <th>Modelo</th>
                                    <th>Ano</th>
                                    <th>Quilometragem Inicial</th>
                                    <th>Data Seguro</th>
                                    <th>Data Inspeção</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($veiculos as $veiculo)
                                    <tr>
                                        <td>{{ $veiculo->placa }}</td>
                                        <td>{{ $veiculo->modelo }}</td>
                                        <td>{{ $veiculo->ano }}</td>
                                        <td>{{ $veiculo->quilometragem_inicial }}</td>
                                        <td>{{ \Carbon\Carbon::parse($veiculo->data_seguro)->format('d/m/Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($veiculo->data_inspecao)->format('d/m/Y') }}</td>
                                        <td class="text-center">
                                            @if ($veiculo->status == 'Disponível')
                                                <span class="badge bg-success">{{ $veiculo->status }}</span>
                                            @elseif ($veiculo->status == 'Em uso')
                                                <span class="badge bg-primary">{{ $veiculo->status }}</span>
                                            @elseif ($veiculo->status == 'Em manutenção')
                                                <span class="badge bg-warning">{{ $veiculo->status }}</span>
                                            @elseif ($veiculo->status == 'Aguardando inspeção')
                                                <span class="badge bg-danger">{{ $veiculo->status }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('veiculos.edit', $veiculo->id) }}" class="btn btn-primary"
                                                title="Editar Veículo">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('veiculos.destroy', $veiculo->id) }}" method="POST"
                                                style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-danger" title="Excluir Veículo">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>

                                            <a href="#" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#abastecimentoModal" title="Informar Abastecimento">
                                                <i class="fas fa-gas-pump"></i>
                                            </a>

                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- DOM/Jquery table end -->
    </div>
    <!-- [ Main Content ] end -->

    <!-- Tela de carregamento -->
    <div class="loading-overlay" style="display: none;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- [Page Specific JS] start -->
    <!-- datatable Js -->
    <script>
    $(document).ready(function() {
        $('#confirmarAbastecimento').on('click', function() {
            var form = $('#abastecimentoForm');
            var formData = form.serialize();

            // Show loading overlay
            $('.loading-overlay').show();

            $.ajax({
                url: '{{ route("abastecimentos.store") }}',
                method: 'POST',
                data: formData,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucesso',
                        text: 'Abastecimento registrado com sucesso.'
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;
                    var errorMessage = '';

                    for (var key in errors) {
                        if (errors.hasOwnProperty(key)) {
                            errorMessage += errors[key][0] + '<br>';
                        }
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        html: errorMessage
                    });
                },
                complete: function() {
                    // Hide loading overlay
                    $('.loading-overlay').hide();
                }
            });
        });

        // [ DOM/jquery ]
        var table = $('#dom-jqry').DataTable({
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ],
            "language": {
                "lengthMenu": "Exibir _MENU_ registros por página",
                "zeroRecords": "Nenhum registro encontrado",
                "info": "Exibindo página _PAGE_ de _PAGES_",
                "infoEmpty": "Nenhum registro disponível",
                "infoFiltered": "(filtrado de _MAX_ registros no total)",
                "search": "Buscar:",
                "paginate": {
                    "first": "Primeiro",
                    "last": "Último",
                    "next": "Próximo",
                    "previous": "Anterior"
                },
            },
            "pagingType": "full_numbers",
            "order": [
                [0, 'asc']
            ],
            "lengthChange": true,
            "autoWidth": false,
            "processing": true,
        });
        // SweetAlert for delete confirmation
        $('.btn-danger').on('click', function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            Swal.fire({
                title: 'Tem certeza?',
                text: "Você não poderá reverter isso!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
    </script>
    <!-- [Page Specific JS] end -->
@endsection

<!-- Modal for Abastecimento -->
<div class="modal fade" id="abastecimentoModal" tabindex="-1" role="dialog" aria-labelledby="abastecimentoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="abastecimentoModalLabel">Informar Abastecimento</h5>
            </div>
            <div class="modal-body">
                <form id="abastecimentoForm" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="veiculo_id">Veículo</label>
                        <select class="form-control" id="veiculo_id" name="veiculo_id" required>
                            @foreach($veiculos as $veiculo)
                                <option value="{{ $veiculo->id }}">{{ $veiculo->placa }} - {{ $veiculo->modelo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="quilometragem">Quilometragem</label>
                        <input type="number" min="1" class="form-control" id="quilometragem" name="quilometragem" required>
                    </div>
                    <div class="form-group">
                        <label for="litros">Litros</label>
                        <input type="text" class="form-control currency" id="litros" name="litros" required>
                    </div>
                    <div class="form-group">
                        <label for="custo">Custo</label>
                        <input type="text" class="form-control currency" id="custo" name="custo" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmarAbastecimento">Salvar</button>
            </div>
        </div>
    </div>
</div>
