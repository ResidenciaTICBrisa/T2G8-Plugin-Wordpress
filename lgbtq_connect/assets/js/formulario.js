const ajaxUrl = my_ajax_object.ajax_url;

// Classe estática contendo todas as funções 
// relacionadas a verificação dos valores de entrada do formulário
class Verificador {
    static nome="";
    static email = "";
    static servico = "";
    static servico_outro = "";
    static descricao = "";
    static latitude = "";
    static longitude = "";

    static verificarTudo() {
        let nome = this.verificarNome();
        let email_f = this.verificarEmail();
        let servico = this.verificarServico();
        let descricao = this.verificarDescricao();
        let coordernadas = this.verificarCoordenadas();

        return (nome && email_f && servico && descricao && coordernadas);
    }

    static verificarNome() {
        let verificacao = true;

        let vazio = this.entradaEstaVazia(this.nome);
        let especial = this.temCaractereEspecial(this.nome);

        if(vazio || especial)
            verificacao = false;

        this.verificarFinal(this.nome, verificacao);
        return verificacao;
    }

    static verificarEmail() {
        let verificacao = true;

        let vazio = this.entradaEstaVazia(this.email);

        if(vazio)
            verificacao = false;

        if (verificacao) {
            let padrao = /^\w+@[a-zA-Z_]+?\.[a-zA-Z]+?/;
            verificacao = padrao.test(this.email.value);
        }

        this.verificarFinal(this.email, verificacao);
        return verificacao;
    }

    static verificarServico() {
        let verificacao = true;
        const self = this;

        if(self.servico_outro.parentNode.classList.contains("escondido")) {
            if(self.entradaEstaVazia(self.servico))
                verificacao = false;
        }
        else {
            if(self.entradaEstaVazia(self.servico_outro) || self.temCaractereEspecial(self.servico_outro))
                verificacao = false;
        }

        this.verificarFinal(this.servico_outro, verificacao);
        return verificacao;
    }

    static verificarDescricao() {
        let verificacao = true;

        let vazio = this.entradaEstaVazia(this.descricao);
        let especial = this.temCaractereEspecial(this.descricao);

        if(vazio || especial)
            verificacao = false;

        this.verificarFinal(this.descricao, verificacao); 
        return verificacao;
    }

    static verificarCoordenadas() {
        let verificacao = true;
        
        let vazioLatitude = this.entradaEstaVazia(this.latitude);
        let vazioLongitude = this.entradaEstaVazia(this.longitude);

        if(vazioLatitude || vazioLongitude)
            verificacao = false;

        this.verificarFinal(this.latitude, verificacao);
        return verificacao;
    }

    static verificarFinal(el, verificacao) {
        const pacote = el.parentNode;

        if(verificacao) {
            pacote.classList.add('entrada-valida');
            pacote.classList.remove('entrada-invalida');
        }
        else {
            pacote.classList.add('entrada-invalida');
            pacote.classList.remove('entrada-valida');
        }
    }

    static temCaractereEspecial(el) {    
        return /[!#$%&()*+\/<=>?@[\\\]_{|}]/.test(el.value);
    }

    static entradaEstaVazia(el) {
        return el.value.length < 3;
    }

    static reiniciarEntradas() {
        this.nome.parentNode.className="";
        this.email.parentNode.className="";
        this.servico.parentNode.className="";
        this.servico_outro.parentNode.className="";
        this.latitude.parentNode.className="";
        this.descricao.parentNode.className="";
    }
}

document.addEventListener('DOMContentLoaded', function () {
    $('#meu_formulario').on('submit', function (e) {
        e.preventDefault(); 

        // Encerra a função caso as entradas forem inválidas
        if(!Verificador.verificarTudo())
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
                // Reinicia as entradas
                Verificador.reiniciarEntradas();

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