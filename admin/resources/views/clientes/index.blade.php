@extends('layouts.main')

@section('title', 'Clientes')
@section('breadcrumb-item', 'Clientes')

@section('breadcrumb-item-active', 'Listagem de Clientes')

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
                <a href="{{ route('clientes.form') }}" class="btn btn-success float-end mb-3">Adicionar Cliente</a>
              </div>
              <div class="card-body">
                <div class="dt-responsive">
                  <table id="dom-jqry" class="table table-striped table-bordered nowrap table-responsive">
                    <thead>
                      <tr>
                        <th>Razão social</th>
                        <th>Nome Fantasia</th>
                        <th>CPF/CNPJ</th>
                        <th>Fone 1</th>
                        <th>Ações</th>
                      </tr>
                    </thead>
                    <tbody>

                      @foreach($clientes as $cliente)
                        <tr>
                          <td>{{ $cliente->razao }}</td>
                          <td>{{ $cliente->fantasia }}</td>
                          <td>{{ $cliente->cpf_cnpj }}</td>
                          <td>{{ $cliente->fone1 }}</td>
                          <td>
                            <a href="{{ route('clientes.form', $cliente->id) }}" class="btn btn-primary">Editar</a>
                            <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" style="display: inline;">
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
          ordering: false,
          responsive: true,
         
        }
      );

    </script>
    <!-- [Page Specific JS] end -->
@endsection