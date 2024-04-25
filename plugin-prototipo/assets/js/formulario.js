var ajaxUrl = my_ajax_object.ajax_url;

document.addEventListener('DOMContentLoaded', function () {
    $('#meu_formulario').on('submit', function (e) {
        e.preventDefault(); // Previne que o formulário dê submit na forma padrão

        // Serializa os dados do formulário
        var formData = $(this).serialize();

        // Envia o pedido do AJAX
        $.ajax({
            type: 'POST',
            url: ajaxUrl,
            data: {
                action: 'processar_formulario', // Hook de ação para o lado do servidor
                formData: formData // Data do formulário
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
            }
        });
    });
});

