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
                                                <a href="#" class="btn btn-primary btn-edit-os" data-id="{{ $os->id }}" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('os.destroy', $os->id) }}" method="POST" style="display: inline;" class="form-delete">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" title="Excluir">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                                <a href="{{ route('os.print', $os->id) }}" class="btn btn-secondary" title="Imprimir">
                                                    <i class="fas fa-print"></i>
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
        </div>
        <!-- DOM/Jquery table end -->
    </div>
    <!-- [ Main Content ] end -->

@endsection

@section('scripts')
    <script src="{{ URL::asset('assets/os/os.js') }}"></script>
    <!-- Adicione o CSS personalizado -->
    <style>
        .select2-container {
            z-index: 9999 !important;
        }

        .select2-dropdown {
            width: auto !important;
            /* Ajusta a largura do dropdown */
            min-width: 600px !important;
            /* Garante que a largura mínima seja a do select */
        }
    </style>

    <!-- Scripts do Select2 e inicialização -->
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                dropdownParent: $('#addOrdemServicoModal'),
                width: '100%' // Ajusta a largura do dropdown
            });
        });
    </script>


    <script>
        $(document).ready(function() {
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

                if (!servicoId) {
                    alert('Por favor, selecione um serviço.');
                    return;
                }

                var tableBody = document.getElementById('servicosTableBody');
                var newRow = document.createElement('tr');

                // Generate a unique identifier for each service row
                var uniqueId = Date.now();

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

                // Add event listener to remove button
                newRow.querySelector('.removeServico').addEventListener('click', function() {
                    newRow.remove();
                    updateTotal();
                });

                updateTotal();
            });
        });
    </script>
    @endsection

    <!-- Modal for Adding Ordem de Serviço -->
    <div class="modal fade" id="addOrdemServicoModal" tabindex="-1" aria-labelledby="addOrdemServicoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="btnAddOrdemServicoModalLabel">Adicionar Ordem de Serviço</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('os.store') }}" method="POST" name="os_form" id="os_form">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="cliente_id" class="form-label">Cliente:</label><br />
                                    <select class="form-select select2" id="cliente_id" name="cliente_id" required>
                                        @foreach ($clientes as $cliente)
                                            <option value="{{ $cliente->id }}">{{ $cliente->razao }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="funcionario_id" class="form-label">Funcionário Responsável</label>
                                    <select class="form-select select2" id="funcionario_id" name="funcionario_id" required>
                                        @foreach ($funcionarios as $funcionario)
                                            <option value="{{ $funcionario->id }}">{{ $funcionario->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="data" class="form-label">Data</label>
                                    <input type="date" class="form-control" id="data" name="data" required>
                                </div>
                            </div>
                            <div class="col-md-8">
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
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="valor_servico" class="form-label">Valor</label>
                                        <input type="text" class="form-control currency" id="valor_servico"
                                            name="valor_servico">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="duracao_servico" class="form-label">Duração (em horas)</label>
                                        <input type="number" class="form-control" id="duracao_servico"
                                            name="duracao_servico">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="descricao_servico" class="form-label">Descrição do Serviço</label>
                                        <textarea class="form-control" id="descricao_servico" name="descricao_servico" rows="3"></textarea>
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
