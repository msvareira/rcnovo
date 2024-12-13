@extends('layouts.main')

@section('title', 'Ordens de Serviço')
@section('breadcrumb-item', 'Ordens de Serviço')

@section('breadcrumb-item-active', 'Listagem de Ordens de Serviço')

@section('css')
    <!-- [Page specific CSS] start -->
    <!-- data tables css -->
    <link rel="stylesheet" href="{{ URL::asset('build/css/plugins/dataTables.bootstrap5.min.css') }}">
    <!-- [Page specific CSS] end -->
@endsection

@section('content')
    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- DOM/Jquery table start -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <a href="#" id="btnAddOrdemServicoModal" class="btn btn-success float-end mb-3">Adicionar OS</a>
                </div>
                <div class="card-body">
                    <div class="dt-responsive">
                        <div class="table-responsive">
                            <table id="dom-jqry" class="table table-striped table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <th>Código OS</th>
                                        <th>Funcionário Resp.</th>
                                        <th>Cliente</th>
                                        <th>Fantasia</th>
                                        <th>CPF/CNPJ</th>
                                        <th>Data OS</th>
                                        <th>Total Serviços</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ordens as $os)
                                        <tr>
                                            <td>{{ $os->id }}</td>
                                            <td>{{ $os->funcionario->nome }}</td>
                                            <td>{{ $os->cliente->razao }}</td>
                                            <td>{{ $os->cliente->fantasia }}</td>
                                            <td>{{ $os->cliente->cpf_cnpj }}</td>
                                            <td>{{ \App\Http\Helpers\Utils::formatardata($os->data) }}</td>
                                            <td>{{ number_format($os->servicos->sum('pivot.valor'), 2, ',', '.') }}</td>
                                            <td>
                                                @if ($os->status == 'Aberta')
                                                    <span class="badge bg-warning">{{ $os->status }}</span>
                                                @elseif ($os->status == 'Concluída')
                                                    <span class="badge bg-success">{{ $os->status }}</span>
                                                @elseif ($os->status == 'Faturada')
                                                    <span class="badge bg-primary">{{ $os->status }}</span>
                                                @elseif ($os->status == 'Cancelada')
                                                    <span class="badge bg-danger">{{ $os->status }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $os->status }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($os->status == 'Aberta')
                                                    <a href="#" class="btn btn-primary btn-edit-os" data-id="{{ $os->id }}" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endif
                                                <button type="button" class="btn btn-secondary btn-print-os" data-id="{{ $os->id }}" title="Imprimir">
                                                    <i class="fas fa-print"></i>
                                                </button>

                                                <button type="button" class="btn btn-info btn-email-os" data-id="{{ $os->id }}" title="Enviar por Email">
                                                    <i class="fas fa-envelope"></i>
                                                </button>

                                                @if ($os->status != 'Faturada')
                                                    @if ($os->status == 'Concluída')
                                                        <button type="button" class="btn btn-warning btn-reopen-os" data-id="{{ $os->id }}" title="Reabrir">
                                                            <i class="fas fa-undo"></i>
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-success btn-complete-os" data-id="{{ $os->id }}" title="Concluir">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    @endif

                                                    <form action="{{ route('os.destroy', $os->id) }}" method="POST" style="display: inline;" class="form-delete">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger" title="Excluir">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>                                                
    
                                                @endif

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- DOM/Jquery table end -->
    </div>
    <!-- [ Main Content ] end -->

@endsection

@section('scripts')
    <script src="{{ URL::asset('assets/os/os.js') }}"></script>
    <!-- Adicione o CSS personalizado -->
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

        <!-- Tela de carregamento -->
        <div class="loading-overlay" style="display: none;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        </div>

    <!-- Scripts do Select2 e inicialização -->
    <script>
        $(document).ready(function() {

            $('.btn-complete-os').on('click', function() {
                var osId = $(this).data('id');
                Swal.fire({
                    title: 'Tem certeza?',
                    text: "Você deseja concluir esta Ordem de Serviço?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sim, concluir!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/os/concluir/' + osId,
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sucesso',
                                    text: 'Ordem de Serviço concluída com sucesso!'
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erro',
                                    text: 'Erro ao concluir Ordem de Serviço. Por favor, tente novamente.'
                                });
                            }
                        });
                    }
                });
            });

            $('.btn-reopen-os').on('click', function() {
                var osId = $(this).data('id');
                Swal.fire({
                    title: 'Tem certeza?',
                    text: "Você deseja reabrir esta Ordem de Serviço?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sim, reabrir!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/os/reabrir/' + osId,
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sucesso',
                                    text: 'Ordem de Serviço reaberta com sucesso!'
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erro',
                                    text: 'Erro ao reabrir Ordem de Serviço. Por favor, tente novamente.'
                                });
                            }
                        });
                    }
                });
            });
            $('.select2').select2({
                dropdownParent: $('#addOrdemServicoModal'),
                width: '100%',
                tags: true,
                createTag: function (params) {
                    var term = $.trim(params.term);
                    if (term === '') {
                        return null;
                    }
                    return {
                        id: term,
                        text: term,
                        newOption: true
                    };
                },
                templateResult: function (data) {
                    var $result = $("<span></span>");
                    $result.text(data.text);
                    if (data.newOption) {
                        $result.append(" <i class='fas fa-plus-circle'></i>");
                    }
                    return $result;
                }

            });
    

            // Mostrar tela de carregamento em todas as chamadas AJAX
            $(document).ajaxStart(function() {
                $('.loading-overlay').show();
            }).ajaxStop(function() {
                $('.loading-overlay').hide();
            });
        });
    </script>

    <script>


        $(document).ready(function() {
            var ListaDeAnexos = [];

            // Adicionar anexos da ListaDeAnexos ao os_form antes de enviar
            $('#os_form').on('submit', function(event) {
                if ($('#servicosTableBody tr').length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Atenção',
                        text: 'Por favor, adicione pelo menos um serviço antes de salvar.'
                    });
                    event.preventDefault();
                    return false;
                }
                ListaDeAnexos.forEach(function(anexoInput) {
                    $('#os_form').append(anexoInput);
                });
            });

            

            $('.btn-email-os').on('click', function() {
                var osId = $(this).data('id');
                $.ajax({
                    url: '/os/send-email/' + osId,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso',
                            text: 'Email enviado com sucesso!'
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro',
                            text: 'Erro ao enviar email. Por favor, tente novamente.'
                        });
                    }
                });
            });

            $('.btn-print-os').on('click', function() {
                var osId = $(this).data('id');
                var iframeUrl = '/os/print/' + osId;
                var iframe = '<iframe src="' + iframeUrl + '" frameborder="0" style="width:100%;height:90vh;"></iframe>';
                
                var modalHtml = `
                    <div class="modal fade" id="printOrdemServicoModal" tabindex="-1" aria-labelledby="printOrdemServicoModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="printOrdemServicoModalLabel">Imprimir Ordem de Serviço</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    ${iframe}
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                $('body').append(modalHtml);
                $('#printOrdemServicoModal').modal('show');

                $('#printOrdemServicoModal').on('hidden.bs.modal', function () {
                    $(this).remove();
                });
            });

            $('.btn-edit-os').on('click', function() {
                var osId = $(this).data('id');
                $.ajax({
                    url: '/os/edit/' + osId,
                    method: 'GET',
                    success: function(data) {

                        var servicoSelect = document.getElementById('servico_id');

                        // Clear all input fields
                        $('#addOrdemServicoModal').find('input, textarea').not(
                            'input[name="_token"]').val('');

                        $('#addOrdemServicoModal').find('input[name="id"]').remove();
                        $('#addOrdemServicoModal').find('form').append(
                            '<input type="hidden" name="id" value="' + osId + '">');

                        $('#addOrdemServicoModal').find('#cliente_id').val(data.cliente_id)
                            .trigger('change');
                        $('#addOrdemServicoModal').find('#funcionario_id').val(data
                            .funcionario_id).trigger('change');
                        $('#addOrdemServicoModal').find('#data').val(data.data);
                        $('#addOrdemServicoModal').find('#solicitante').val(data.solicitante);
                        // Populate services
                        var servicosTableBody = $('#addOrdemServicoModal').find(
                            '#servicosTableBody');
                        servicosTableBody.empty();
                        var total = 0;
                        data.servicos.forEach(function(servico) {
                            var uniqueId = Date.now() + Math.random(); // Generate a more unique identifier
                            var anexosHtml = '';
                            if (servico.anexos) {
                                servico.anexos.forEach(function(anexo) {
                                    anexosHtml += `<a href="${anexo.url}" target="_blank">${anexo.descricao}</a><br>`;
                                });
                            }
                            var newRow = `
                                <tr>
                                    <td>
                                        <input type="hidden" name="servicos[${uniqueId}][id]" value="${servico.id}">
                                        ${servico.descricao}
                                    </td>
                                    <td>
                                        <input type="hidden" name="servicos[${uniqueId}][descricao_execucao]" value="${servico.descricao_execucao}">
                                        ${servico.descricao_execucao}
                                    </td>
                                    <td>
                                        <input type="hidden" name="servicos[${uniqueId}][valor]" value="${servico.valor}">
                                        ${servico.valor}
                                    </td>
                                    <td>
                                        <input type="hidden" name="servicos[${uniqueId}][duracao]" value="${servico.duracao}">
                                        ${servico.duracao}
                                    </td>
                                    <td>
                                        ${anexosHtml}
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger removeServico">Remover</button>
                                    </td>
                                </tr>
                            `;
                            servicosTableBody.append(newRow);
                            total += parseFloat(servico.valor.replace('.', '').replace(',', '.'));

                            $('.currency').mask('000.000.000.000.000,00', {
                                reverse: true
                            });

                            // Clear the inputs and force user to select service again
                            servicoSelect.value = '';
                            $('.select2').trigger('change');
                            document.getElementById('descricao_servico').value = '';
                            document.getElementById('valor_servico').value = '';
                            document.getElementById('duracao_servico').value = '';

                            // Add event listener to remove button
                            $('.removeServico').on('click', function() {
                                $(this).closest('tr').remove();
                                updateTotal();
                            });

                        });
                        $('#total_servicos').text(total.toLocaleString('pt-BR', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }));
                        $('#addOrdemServicoModal').modal('show');
                    }
                });
            });

            function updateTotal() {
                var total = 0;
                $('#servicosTableBody tr').each(function() {
                    var valor = $(this).find('input[name*="[valor]"]').val();                    
                    
                    total += parseFloat(valor.replace('.','').replace(',', '.'));
                });
                $('#total_servicos').text(total.toLocaleString('pt-BR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));
            }

            document.getElementById('addServico').addEventListener('click', function() {
                var servicoSelect = document.getElementById('servico_id');
                var servicoText = servicoSelect.options[servicoSelect.selectedIndex].text;
                var servicoId = servicoSelect.value;
                var descricaoServico = document.getElementById('descricao_servico').value;
                var valorServico = document.getElementById('valor_servico').value;
                var duracaoServico = document.getElementById('duracao_servico').value;
                var anexos = document.getElementById('anexos_servico').files;
                var anexosServico = document.getElementById('anexos_servico');


                if (!servicoId) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Atenção',
                        text: 'Por favor, selecione um serviço.'
                    });
                    return;
                }

                var tableBody = document.getElementById('servicosTableBody');
                var newRow = document.createElement('tr');

                // Generate a unique identifier for each service row
                var uniqueId = Date.now();

                anexosServico.name = `servicos[${uniqueId}][anexos][]`;

                
                ListaDeAnexos.push(anexosServico);                


                var anexosHtml = '';
                
                for (var i = 0; i < anexos.length; i++) {
                    anexosHtml += `${anexos[i].name}<br>`;
                }

                newRow.innerHTML = `
                    <td>
                        <input type="hidden" name="servicos[${uniqueId}][id]" value="${servicoId}">
                        ${servicoText}
                    </td>
                    <td>
                        <input type="hidden" name="servicos[${uniqueId}][descricao_execucao]" value="${descricaoServico}">
                        ${descricaoServico}
                    </td>
                    <td>
                        <input type="hidden" name="servicos[${uniqueId}][valor]" value="${valorServico}">
                        ${valorServico}
                    </td>
                    <td>
                        <input type="hidden" name="servicos[${uniqueId}][duracao]" value="${duracaoServico}">
                        ${duracaoServico}
                    </td>
                    <td>
                        ${anexosHtml}
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger removeServico">Remover</button>
                    </td>
                `;

                tableBody.appendChild(newRow);

                $('.currency').mask('000.000.000.000.000,00', {
                    reverse: true
                });

                // Clear the inputs and force user to select service again
                servicoSelect.value = '';
                $('.select2').trigger('change');
                document.getElementById('descricao_servico').value = '';
                document.getElementById('valor_servico').value = '';
                document.getElementById('duracao_servico').value = '';
                // Hide the current file input and create a new one
                var oldAnexosInput = document.getElementById('anexos_servico');
                oldAnexosInput.style.display = 'none';
                oldAnexosInput.id = 'anexos_servico_' + Date.now();

                var newAnexosInput = document.createElement('input');
                newAnexosInput.type = 'file';
                newAnexosInput.className = 'form-control';
                newAnexosInput.id = 'anexos_servico';
                newAnexosInput.name = 'anexos_servico[]';
                newAnexosInput.multiple = true;

                oldAnexosInput.parentNode.insertBefore(newAnexosInput, oldAnexosInput.nextSibling);

                // Add event listener to remove button
                newRow.querySelector('.removeServico').addEventListener('click', function() {
                    newRow.remove();
                    updateTotal();
                    // Remove the corresponding input from ListaDeAnexos
                    ListaDeAnexos = ListaDeAnexos.filter(function(input) {
                        return input.id !== oldAnexosInput.id;
                    });

                });

                updateTotal();
            });
        });
    </script>
    @endsection


    <!-- Modal for Adding Ordem de Serviço -->
    <div class="modal fade" id="addOrdemServicoModal" tabindex="-1" aria-labelledby="addOrdemServicoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" style="width: 95%; max-width: none">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="btnAddOrdemServicoModalLabel">Ordem de Serviço</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('os.store') }}" method="POST" name="os_form" id="os_form" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="cliente_id" class="form-label">Cliente:</label><br />
                                    <select class="form-select select2" id="cliente_id" name="cliente_id" required>
                                        @foreach ($clientes as $cliente)
                                            <option value="{{ $cliente->id }}">{{ $cliente->razao }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="funcionario_id" class="form-label">Funcionário Responsável</label>
                                    <select class="form-select select2" id="funcionario_id" name="funcionario_id" required>
                                        @foreach ($funcionarios as $funcionario)
                                            <option value="{{ $funcionario->id }}">{{ $funcionario->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="data" class="form-label">Data</label>
                                    <input type="date" class="form-control" id="data" name="data" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="solicitante" class="form-label">Solicitante</label>
                                    <input type="text" class="form-control" id="solicitante" name="solicitante" required>
                                </div>
                            </div>
                        </div>

                        <hr />

                        <fieldset>
                            <legend>Serviços</legend>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="servico_id" class="form-label">Serviço</label>
                                        <select class="form-select select2" id="servico_id" name="servico_id">
                                            <option value="" selected disabled>Selecione um serviço</option>
                                            @foreach ($servicos as $servico)
                                                <option value="{{ $servico->id }}">{{ $servico->descricao }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="duracao_servico" class="form-label">Duração (em horas)</label>
                                        <input type="number" class="form-control" id="duracao_servico"
                                            name="duracao_servico">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="valor_servico" class="form-label">Valor</label>
                                        <input type="text" class="form-control currency" id="valor_servico"
                                            name="valor_servico">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="descricao_servico" class="form-label">Descrição do Serviço</label>
                                        <textarea class="form-control" id="descricao_servico" name="descricao_servico" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="anexos_servico" class="form-label">Anexos</label>
                                        <input type="file" class="form-control" id="anexos_servico" name="anexos_servico[]" multiple>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary" id="addServico">Adicionar Serviço</button>
                            <table class="table mt-3">
                                <thead>
                                    <tr>
                                        <th>Serviço</th>
                                        <th>Descrição</th>
                                        <th>Valor</th>
                                        <th>Duração</th>
                                        <th>Anexos</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="servicosTableBody">
                                    <!-- Serviços adicionados serão inseridos aqui -->
                                </tbody>
                            </table>
                            <div class="mt-3">
                                <strong>Total: R$ <span id="total_servicos">0,00</span></strong>
                            </div>
                        </fieldset>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
