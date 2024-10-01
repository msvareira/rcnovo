$(document).ready(function() {
    // Assuming you have input fields with ids 'cep', 'logradouro', 'bairro', 'cidade', and 'estado'
    $('#cep').blur(function() {
        var cep = $(this).val().replace(/\D/g, '');
        if (cep.length == 8) {
            $.getJSON('https://viacep.com.br/ws/' + cep + '/json/', function(data) {
                if (!("erro" in data)) {
                    $('#rua').val(data.logradouro);
                    $('#bairro').val(data.bairro);
                    $('#cidade').val(data.localidade);
                    $('#estado').val(data.uf);
                    $('#cod_ibge').val(data.ibge);
                } else {
                    $('#rua').val('');
                    $('#bairro').val('');
                    $('#cidade').val('');
                    $('#estado').val('');
                    $('#cod_ibge').val('');
                    
                    createMessage('danger', 'CEP não encontrado');
                }
            });
        }else{
            $('#rua').val('');
            $('#bairro').val('');
            $('#cidade').val('');
            $('#estado').val('');
            $('#cod_ibge').val('');
        }
    });


});