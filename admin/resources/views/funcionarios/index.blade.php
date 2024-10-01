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
                <a href="{{ route('funcionarios.form') }}" class="btn btn-success float-end mb-3">Adicionar Funcionário</a>
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
                            <td>{{ \App\Http\Helpers\Utils::formatarnumero($funcionario->valor_hora_extra) }}</td>
                            <td>{{ \App\Http\Helpers\Utils::formatarnumero($funcionario->salario) }}</td>
                            <td>{{ $funcionario->cod_cracha }}</td>
                            <td>
                              <a href="{{ route('funcionarios.form', $funcionario->id) }}" class="btn btn-primary">Editar</a>
                              <form action="{{ route('funcionarios.destroy', $funcionario->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Excluir</button>
                              </form>
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
      var table = $('#dom-jqry').DataTable(
        {
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
        }
      );

    </script>
    <!-- [Page Specific JS] end -->
@endsection