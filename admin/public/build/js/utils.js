$(document).ready(function(){
    $('.form-delete').on('submit', function(event) {
        event.preventDefault();
        var form = $(this);
        Swal.fire({
            title: 'Você tem certeza?',
            text: "Esta ação não poderá ser desfeita!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                form.unbind('submit').submit();
            }
        });

    });

    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 10000);


    $('.currency').mask('000.000.000.000.000,00', {reverse: true});
    $('.date').mask('00/00/0000');
    $('.time').mask('00:00:00');
    $('.date_time').mask('00/00/0000 00:00:00');
    $('.numeric').mask('00000000000000000000');
    $('.fone').mask('(99) 99999-9999');
    $('.cep').mask('00000-000');

    $(document).on('blur','.cpf_cnpj', function() {
        var documento = $(this).val().replace(/\D/g, '');
        if (documento.length === 11) {
            // CPF format
            $(this).mask('000.000.000-00');
        } else if (documento.length === 14) {
            // CNPJ format
            $(this).mask('00.000.000/0000-00');
        } else {
            // Clear the mask
            $(this).unmask();
        }
    });



});



function createMessage(type, message) {

    $('.messages').html('<div class="alert alert-' + type + '">' + message + '</div>');

    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 3000);


}