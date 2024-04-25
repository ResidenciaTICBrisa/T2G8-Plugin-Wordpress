var ajaxUrl = my_ajax_object.ajax_url;

document.addEventListener('DOMContentLoaded', function () {
    $('#meu_formulario').on('submit', function (e) {
        e.preventDefault(); // Previne que o formulário dê submit na forma padrão
        // Verifica se os campos estão preenchidos
        var nome = document.getElementById('nome').value;
        var email = document.getElementById('email').value;
        var latitude = document.getElementById('latitude').value;
        var longitude = document.getElementById('longitude').value;
        var servico = document.getElementById('servico').value;
        var descricao = document.getElementById('descricao').value;
        var outroServico = document.getElementById('servico_outro').value
        if (nome === '' || email === '' || latitude === '' || longitude === '' || (servico === 'outro' && outroServico === '')) {
            alert('Por favor, preencha todos os campos.');
            console.log(nome,email,latitude,longitude,servico,descricao,outroServico)
            return;
        }else {
            // Serializa os dados do formulário
            var formData = $(this).serialize();

            // Envia o pedido do AJAX
            $.ajax({
                type: 'POST',
                url: ajaxUrl,
                data: {
                    action: 'processar_formulario', // Hook de ação para o lado do servidor
                    formData: formData, // Data do formulário
                },
                success: function (response) {
                    // Resposta caso dê certo
                    console.log(response);
                    // Se o envio for bem-sucedido, executar a função exit_page()
                    transicaoPagina("PaginaComPopup", "div_saida");
                },
                error: function (xhr, status, error) {
                    // Resposta caso dê errado
                    console.error(error);
                },
            });
        }
    });
});

