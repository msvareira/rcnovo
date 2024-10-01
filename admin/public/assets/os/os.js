$(document).ready(function(){
    
    var table = $('#dom-jqry').DataTable(
      {
        "lengthMenu": [
        [10, 25, 50, -1],
        [10, 25, 50, "Todos"]
        ],
        responsive: false,
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

    $('#btnAddOrdemServicoModal').on('click', function (param) {                 
      // Clear all input fields
      $('#addOrdemServicoModal').find('input, textarea').not('input[name="_token"]').val('');
      $('#total_servicos').text('0,00');

      // Set the current date to the date input field
      var currentDate = new Date().toISOString().split('T')[0];
      $('#data').val(currentDate);
      $('#addOrdemServicoModal').find('select').val('').trigger('change');
      
      // Clear the services table
      $('#servicosTableBody').empty();        
      $('#addOrdemServicoModal').modal('show');
    });

      

});