@extends('layouts.main')

@section('title', 'Funcionários')
@section('breadcrumb-item', 'Funcionários')

@section('breadcrumb-item-active', 'Listagem de Funcionários')

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
          <a href="{{ route('funcionarios.form') }}" class="btn btn-success float-end mb-3">Adicionar
            Funcionário</a>
        </div>

        <div class="card-body">
          <div class="dt-responsive">
            <table id="dom-jqry" class="table table-striped table-bordered nowrap">
              <thead>
                <tr>
                  <th>Nome</th>
                  <th>RG</th>
                  <th>CPF</th>
                  <th>Carta Motorista</th>
                  <th>Carga Horária</th>
                  <th>Valor Hora Extra</th>
                  <th>Salário</th>
                  <th>Crachá</th>
                  <th>Ações</th>
                </tr>
              </thead>
              <tbody>

                @foreach ($funcionarios as $funcionario)
                  <tr>
                    <td>{{ $funcionario->nome }}</td>
                    <td>{{ $funcionario->rg }}</td>
                    <td>{{ $funcionario->cpf }}</td>
                    <td>{{ $funcionario->carta_motorista }}</td>
                    <td>{{ $funcionario->carga_horaria_dia }}</td>
                    <td>{{ \App\Http\Helpers\Utils::formatarnumero($funcionario->valor_hora_extra) }}
                    </td>
                    <td>{{ \App\Http\Helpers\Utils::formatarnumero($funcionario->salario) }}</td>
                    <td>{{ $funcionario->cod_cracha }}</td>
                    <td>
                      <a href="{{ route('funcionarios.form', $funcionario->id) }}"
                        class="btn btn-primary" title="Editar Funcionário">
                        <i class="fas fa-edit"></i>
                      </a>
                      <form action="{{ route('funcionarios.destroy', $funcionario->id) }}"
                        method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" title="Excluir Funcionário">
                        <i class="fas fa-trash-alt"></i>
                        </button>
                      </form>
                      @if(!$funcionario->user)
                        <button type="button" class="btn btn-info" data-bs-toggle="modal"
                          data-bs-target="#userModal" data-funcionario-id="{{ $funcionario->id }}"
                          data-funcionario-cpf="{{ $funcionario->cpf }}" title="Gerenciar Usuário">
                          <i class="fas fa-user"></i>
                        </button>
                      @else
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                          data-bs-target="#passwordModal" data-funcionario-id="{{ $funcionario->id }}"
                          title="Alterar Senha">
                          <i class="fas fa-key"></i>
                        </button>
                      @endif
                    </td>
                  </tr>
                @endforeach

            </table>
          </div>
        </div>
      </div>
    </div>
    <!-- DOM/Jquery table end -->
  </div>
  <!-- [ Main Content ] end -->

  <!-- User Modal -->
  <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="userModalLabel">Gerenciar Usuário</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="userForm" method="POST" action="{{ route('funcionario.users.store') }}">
            @csrf
            <input type="hidden" name="funcionario_id" id="funcionario_id">
            <div class="mb-3">
              <label for="username" class="form-label">Usuário</label>
              <input type="text" class="form-control" id="username" name="username" readonly>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Senha</label>
              <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Password Modal -->
  <div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="passwordModalLabel">Alterar Senha</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="passwordForm" method="POST" action="{{ route('funcionario.users.updatePassword') }}">
            @csrf
            <input type="hidden" name="funcionario_id" id="password_funcionario_id">
            <div class="mb-3">
              <label for="new_password" class="form-label">Nova Senha</label>
              <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
          </form>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('scripts')
  <!-- [Page Specific JS] start -->
  <!-- datatable Js -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="{{ URL::asset('build/js/plugins/dataTables.min.js') }}"></script>
  <script src="{{ URL::asset('build/js/plugins/dataTables.bootstrap5.min.js') }}"></script>
  <script>
    // [ DOM/jquery ]
    var total, pageTotal;
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

    $('#userModal').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget);
      var funcionarioId = button.data('funcionario-id');
      var funcionarioCpf = button.data('funcionario-cpf');
      var modal = $(this);
      modal.find('#funcionario_id').val(funcionarioId);
      modal.find('#username').val(funcionarioCpf);
    });

    $('#passwordModal').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget);
      var funcionarioId = button.data('funcionario-id');
      var modal = $(this);
      modal.find('#password_funcionario_id').val(funcionarioId);
    });
  </script>
  <!-- [Page Specific JS] end -->
@endsection
