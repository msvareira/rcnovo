@extends('layouts.main')

@section('title', 'Preventivas')
@section('breadcrumb-item', 'Preventivas')

@section('breadcrumb-item-active', 'Listagem de Preventivas')

@section('css')
    <!-- [Page specific CSS] start -->
    <!-- data tables css -->
    <link rel="stylesheet" href="{{ URL::asset('build/css/plugins/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- [Page specific CSS] end -->
@endsection

@section('content')
        <!-- [ Main Content ] start -->
        <div class="row">
          <!-- DOM/Jquery table start -->
          <div class="col-sm-12">
            <div class="card">
              <div class="card-header">
                <a href="{{ route('preventivas.form') }}" class="btn btn-success float-end mb-3" title="Adicionar Preventiva">
                  Adicionar Preventiva
                </a>
              </div>
              <div class="card-body">
                <div class="dt-responsive">
                  <table id="dom-jqry" class="table table-striped table-bordered nowrap table-responsive">
                    <thead>
                      <tr>
                        <th>Cliente</th>
                        <th>Funcionário</th>
                        <th>Status</th>
                        <th>Prazo</th>
                        <th>Descrição</th>
                        <th>Data de Execução</th>
                        <th>Ações</th>
                      </tr>
                    </thead>
                    <tbody>

                      @foreach($preventivas as $preventiva)
                        <tr>
                          <td>{{ $preventiva->cliente->razao }}</td>
                          <td>{{ $preventiva->funcionario ? $preventiva->funcionario->nome : '' }}</td>
                          <td>
                            <span class="badge {{ $preventiva->status == 'Concluído' ? 'bg-success' : 'bg-warning' }}">
                              {{ $preventiva->status }}
                            </span>
                          </td>
                          <td>{{ $preventiva->prazo }}</td>
                          <td>{{ $preventiva->descricao }}</td>
                          <td>{{ \Carbon\Carbon::parse($preventiva->data_execucao)->format('d/m/Y') }}</td>
                          <td>
                            @if($preventiva->status == 'Concluído')
                              <a target="_blank" href="{{ route('preventivas.print', $preventiva->id) }}" class="btn btn-secondary" title="Imprimir Preventiva">
                                <i class="fas fa-print"></i>
                              </a>
                            @endif
                            <a href="{{ route('preventivas.form', $preventiva->id) }}" class="btn btn-primary" title="Editar Preventiva">
                              <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('preventivas.destroy', $preventiva->id) }}" method="POST" style="display: inline;" class="delete-form">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-danger" title="Excluir Preventiva">
                                <i class="fas fa-trash"></i>
                              </button>
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

      // SweetAlert for delete confirmation
      $('.delete-form').on('submit', function(e) {
        e.preventDefault();
        var form = this;
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

    </script>
    <!-- [Page Specific JS] end -->
@endsection