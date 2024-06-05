var ajaxUrl = my_ajax_object.ajax_url;

document.addEventListener('DOMContentLoaded', function () {
    // Verifica se há dados no localStorage e os exibe
    if (localStorage.getItem('formData')) {
        console.log('Dados enviados anteriormente:');
        console.log(JSON.parse(localStorage.getItem('formData')));
        localStorage.removeItem('formData'); // Limpa os dados após exibi-los
    }

    $('#meu_formulario').on('submit', function (e) {
        e.preventDefault(); 

        var nome = document.getElementById('nome').value;
        var email = document.getElementById('email_f').value;
        var latitude = document.getElementById('latitude').value;
        var longitude = document.getElementById('longitude').value;
        var servico = document.getElementById('servico').value;
        var descricao = document.getElementById('descricao').value;
        var outroServico = document.getElementById('servico_outro').value;

        // Função para verificar caracteres especiais
        function hasSpecialChars(str) {
            return /[^a-zA-Z0-9 ]/.test(str);
        }

        // Verifica a presença de caracteres especiais
        console.log('Verificação de caracteres especiais:');
        console.log('Nome:', nome, ' - ', hasSpecialChars(nome));
        console.log('Email:', email, ' - ', hasSpecialChars(email));
        console.log('Latitude:', latitude, ' - ', hasSpecialChars(latitude));
        console.log('Longitude:', longitude, ' - ', hasSpecialChars(longitude));
        console.log('Serviço:', servico, ' - ', hasSpecialChars(servico));
        console.log('Descrição:', descricao, ' - ', hasSpecialChars(descricao));
        console.log('Outro Serviço:', outroServico, ' - ', hasSpecialChars(outroServico));

        // Log dos dados do formulário antes do envio
        console.log('Dados do formulário:');
        console.log({
            nome: nome,
            email: email,
            latitude: latitude,
            longitude: longitude,
            servico: servico,
            descricao: descricao,
            outroServico: outroServico
        });

        if (nome === '' || email === '' || latitude === '' || longitude === '' || (servico === 'outro' && outroServico === '')) {
            alert('Por favor, preencha todos os campos.');
            return;
            } else if (hasSpecialChars(nome) || hasSpecialChars(descricao) || hasSpecialChars(outroServico)) {
                alert("Não insira caracteres especiais");
                console.log('Campos com caracteres especiais:', { nome, descricao, outroServico });
                return;
        } else {
            // Serializa os dados do formulário
            var formData = $(this).serialize();
            var formDataObject = {
                nome: nome,
                email: email,
                latitude: latitude,
                longitude: longitude,
                servico: servico,
                descricao: descricao,
                outroServico: outroServico
            };

            // Armazena os dados no localStorage
            localStorage.setItem('formData', JSON.stringify(formDataObject));

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
                    console.log('Resposta do servidor:', response);
                    // Se o envio for bem-sucedido, executar a função exit_page()
                    transicaoPagina("PaginaComPopup", "div_saida");
                },
                error: function (xhr, status, error) {
                    // Resposta caso dê errado
                    console.error('Erro no envio do formulário:', error);
                },
            });
        }
    });
});