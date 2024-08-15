const ajaxUrl = my_ajax_object.ajax_url;

// Função para verificar se a entrada livre possui algum caractere especial
// Retorna verdadeiro se tem
// Retorna falso se não tem
function temCaractereEspecial(el) {
    let tem = /[!#$%&()*+\/<=>?@[\\\]_{|}]/.test(el.value);

    if(tem) {
        el.classList.add('entrada-invalida');
    }
    else {
        el.classList.remove('entrada-invalida');
    }

    return tem;
}

// Função para verificar se a entrada está vazia
// Retorna verdadeiro se estiver vazia
// Retorna falso se não estiver vazia
function entradaEstaVazia(el) {
    let esta = (el.value === '');

    if(esta) {
        el.classList.add('entrada-invalida');
    }
    else {
        el.classList.remove('entrada-invalida');
    }

    return esta;
}

document.addEventListener('DOMContentLoaded', function () {
    $('#meu_formulario').on('submit', function (e) {
        e.preventDefault(); 

        const nome = document.getElementById('nome');
        const email = document.getElementById('email_f');
        const latitude = document.getElementById('latitude');
        const longitude = document.getElementById('longitude');
        const servico = document.getElementById('servico');
        const descricao = document.getElementById('descricao')
        const outroServico = document.getElementById('servico_outro');

        // Todas as entradas do formulário em uma array
        const entradas = [nome, email, latitude, longitude, descricao, servico, outroServico];

        // Entradas livres são aquelas que o usuário pode digitar
        const entradasLivres = [nome, descricao, outroServico];
        
        // Verificar se todas as entradas do formulário estão preenchidas
        let temEntradaVazia = false;
        for(let i=0; i<entradas.length; i++) {
            if(!temEntradaVazia)
                temEntradaVazia = entradaEstaVazia(entradas[i]);
            else
                entradaEstaVazia(entradas[i]);
        }

        // Verificar se todas as entradas livres do formulário não tem caracteres especiais
        let temEntradaComCaractereEspecial = false;
        for(let i=0; i<entradasLivres.length; i++) {
            if(!temEntradaComCaractereEspecial)
                temEntradaComCaractereEspecial = temCaractereEspecial(entradasLivres[i]);
            else
                temCaractereEspecial(entradasLivres[i])
        }
     
        // Encerra a função caso as entradas forem inválidas
        if(temEntradaVazia || temEntradaComCaractereEspecial)
            return;

        // Serializa os dados do formulário
        let formData = $(this).serialize();
        
        // Envia o pedido POST assíncrono por meio do AJAX
        $.ajax({
            type: 'POST',
            url: ajaxUrl,
            data: {
                action: 'processar_formulario', // Hook de ação para o lado do servidor
                formData: formData, // Data do formulário
            },
            success: function (response) {
                // Se o envio for bem-sucedido, realiza a transição para a página de finalização
                transicaoPagina("PaginaComPopup", "div_saida");
            },
            error: function (xhr, status, error) {
                // Resposta caso dê errado
                console.error('Erro no envio do formulário:', error);
            },
        });
    });
});