let mapAdmin;
let mapEdit;
let isSearching = false;

class Filtro {
    static status = "Todos";
    static nome = "";
    static servico = "";

    static realizarFiltragem(arr) {
        const self = this;
        return arr.filter(function (formulario) {
            return (self.checarStatus(formulario) && self.checarNome(formulario) && self.checarServico(formulario));
        });
    }
    static checarStatus(formulario) {
        if (this.status !== "Todos") {
            return (this.status == formulario.situacao);
        } else {
            return true;
        }
    }

    static checarNome(formulario) {
        return (formulario.nome.toLowerCase().trim().startsWith(this.nome.toLowerCase().trim()));
    }

    static checarServico(formulario) {
        if (this.servico !== "") {
            return (this.servico == formulario.servico);
        } else {
            return true;
        }
    }

    static reiniciarFiltro() {
        this.status = "Todos";
        this.nome = "";
        this.servico = "";
    }
}

class Ordenador {
    static coluna = "nome";
    static ordem = "asc";

    static realizarOrdenacao(arr) {
        const self = this;
        return arr.sort(function (a, b) {
            const aValue = a[self.coluna].trim().toLowerCase();
            const bValue = b[self.coluna].trim().toLowerCase();

            return (self.ordem === 'asc') ? aValue.localeCompare(bValue) : bValue.localeCompare(aValue);
        });
    }
}

// Classe Singleton com todos os métodos e atributos relacionados à tabela
class Tabela {
    static adicionarZero(numero) {
        return numero < 10 ? '0' + numero : numero;
    }

    static formatarDataHora(data) {
        const dia = Tabela.adicionarZero(data.getDate());
        const mes = Tabela.adicionarZero(data.getMonth() + 1); // Adiciona 1 porque os meses são indexados de 0 a 11
        const ano = data.getFullYear();
        const hora = Tabela.adicionarZero(data.getHours());
        const minutos = Tabela.adicionarZero(data.getMinutes());
        const segundos = Tabela.adicionarZero(data.getSeconds());

        return `${dia}/${mes}/${ano} ${hora}:${minutos}:${segundos}`;
    }

    constructor(arr, tabela) {
        if (Tabela.instance)
            return Tabela.instance;

        this.arr = arr;
        this.tabela = tabela;
        Tabela.instance = this;
    }

    // Limpa o conteúdo da tabela
    excluirLinhas() {
        const tbody = this.tabela.querySelector('tbody');
        tbody.innerHTML = "";
    }

    // Gera novas linhas na tabela de acordo com a array definida
    gerarLinhas() {
        const STATUS_BOTOES = {
            "Aprovado": `
            <button type="button" onclick="confirmarAcao('Tem certeza que quer negar a sugestão?', this.form, 'reprove')">Negar</button>
            `,
            "Negado": `
            <button type="button" onclick="confirmarAcao('Tem certeza que quer aprovar a sugestão?', this.form, 'approve')">Aprovar</button>
            `,
            "Pendente": `
            <button type="button" onclick="confirmarAcao('Tem certeza que quer aprovar a sugestão?', this.form, 'approve')">Aprovar</button>
            <button type="button" onclick="confirmarAcao('Tem certeza que quer negar a sugestão?', this.form, 'reprove')">Negar</button>
            `
        }
        const tbody = this.tabela.querySelector('tbody');

        this.arr.forEach(dados => {
            const linha = document.createElement('tr');
            linha.id = "formulario-" + dados.id;
            let descricao;
            let data = new Date(dados.data_hora);
            let dataFormatada = Tabela.formatarDataHora(data);
            if (dados.descricao.length > 10) {
                descricao = `
                <span id="descricaoResumida_${dados.id}">${dados.descricao.substring(0, 10)}...</span>
                <span id="descricaoCompleta_${dados.id}" style="display:none;">${dados.descricao}</span>
                <button data-id="${dados.id}" onclick="mostrarDescricaoCompleta(${dados.id})">Ver mais</button>
                `;
            } else {
                descricao = dados.descricao;
            }

            let acoes = STATUS_BOTOES[dados.situacao];

            linha.innerHTML = `
            <td id="formulario-${dados.id}-nome">${dados.nome}</td>
            <td id="formulario-${dados.id}-email">${dados.email}</td>
            <td id="formulario-${dados.id}-latitude">${dados.latitude}</td>
            <td id="formulario-${dados.id}-longitude">${dados.longitude}</td>
            <td id="formulario-${dados.id}-servico">${dados.servico}</td>
            <td id="formulario-${dados.id}-descricao">${descricao}</td>
            <td id="formulario-${dados.id}-data_hora">${dataFormatada}</td>
            <td id="formulario-${dados.id}-situacao">${dados.situacao}</td>
            <td id="formulario-${dados.id}-acoes">
                <form method="post" action="">
                <input type="hidden" name="id" value="${dados.id}">
                <input type="hidden" name="action" value="">
                ${acoes}
                <button type="button" onclick='abrirModalEdicao(${JSON.stringify(dados)})'>Editar</button>
                <button type="button" onclick="confirmarAcao('Tem certeza que quer excluir a sugestão?', this.form, 'exclude')">Excluir</button>
            </td>
            `;
            tbody.appendChild(linha);
        });
    }
}

function destacarLinhaTabela(id) {
    let tabela = document.getElementById("tabela");
    let linha = document.getElementById(("formulario-" + id));

    if (linha === null) {
        return;
    }

    // Loop para remover a linha-destacada de todas as linhas
    for (let i = 0, row; (row = tabela.rows[i]); i++) {
        row.classList.remove('linha-destacada');
    }

    linha.classList.add('linha-destacada'); // Adiciona a classe 'linha-destacada'
    linha.scrollIntoView({ behavior: 'smooth' }); // Rola a página para a linha
    setTimeout(function () {
        linha.classList.remove('linha-destacada');
    }, 3000);
}

function mostrarDescricaoCompleta(id) {
    var descricaoResumida = document.getElementById('descricaoResumida_' + id);
    var descricaoCompleta = document.getElementById('descricaoCompleta_' + id);
    var botao = document.querySelector('button[data-id="' + id + '"]');
    if (descricaoResumida.style.display === 'none') {
        descricaoResumida.style.display = 'inline';
        descricaoCompleta.style.display = 'none';
        botao.innerText = 'Ver mais';
    } else {
        descricaoResumida.style.display = 'none';
        descricaoCompleta.style.display = 'inline';
        botao.innerText = 'Ver menos';
    }
}
function destacarLinhaTabela(id) {
    var tabela = document.getElementById("tabela");
    var linha = document.getElementById(id);

    // Loop para remover a linha-destacada de todas as linhas
    for (var i = 0, row; (row = tabela.rows[i]); i++) {
        row.classList.remove('linha-destacada');
    }

    linha.classList.add('linha-destacada'); // Adiciona a classe 'linha-destacada'
    linha.scrollIntoView({ behavior: 'smooth' }); // Rola a página para a linha

    // Remove a classe linha-destacada depois de um determinado tempo
    setTimeout(function () {
        linha.classList.remove('linha-destacada');
    }, 2000);
}
function initMapAdmin() {
    // Definindo o ícone personalizado no escopo global
    const personalIcon = L.icon({
        iconUrl: 'https://res.cloudinary.com/dxsx0emuu/image/upload/f_auto,q_auto/lc_marker',
        iconSize: [20, 30], // tamanho do ícone
        popupAnchor: [1, -10]
    });

    if (document.getElementById('mapa_admin') == null) {   
        return;
    }

    mapAdmin = L.map('mapa_admin', { doubleClickZoom: false }).setView([-15.8267, -47.9218], 13);

    // Adiciona o provedor de mapa OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(mapAdmin);

    formularios_aprovados.forEach(function (formulario) {
        L.marker([formulario.latitude, formulario.longitude], { icon: personalIcon }).addTo(mapAdmin).on('click', function () {
            destacarLinhaTabela(formulario.id);
        });
    });
}
function initSortButtons() {
    // Adiciona um evento de clique aos botões de ordenação
    var sortButtons = document.querySelectorAll('.sort-btn');

    sortButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var table = button.closest('table');
            var columnIndex = Array.from(button.parentNode.parentNode.children).indexOf(button.parentNode);
            var order = button.getAttribute('data-order') || 'asc';

            order = (order === 'asc') ? 'desc' : 'asc';
            button.setAttribute('data-order', order);

            // Atualiza o ícone do botão de ordenação
            var icon = button.querySelector('.sort-icon');

            // Remove e adiciona a classe do ícone com base na direção da ordenação
            if (order === 'asc') {
                icon.innerHTML = '&#9662;'; // Triângulo para baixo (ordem crescente)
            } else {
                icon.innerHTML = '&#9652;'; // Triângulo para cima (ordem decrescente)
            }

            // Obtém todas as linhas da tabela, exceto a primeira (cabeçalho)
            var rows = Array.from(table.querySelectorAll('tbody > tr'));

            // Determina o critério de ordenação com base na classe do botão
            if (button.classList.contains('sort-by-date')) {
                rows.sort(function(a, b) {
                    var aValue = new Date(a.children[columnIndex].textContent.trim().replace(/(\d{2})\/(\d{2})\/(\d{4}) (\d{2}):(\d{2}):(\d{2})/, '$3-$2-$1T$4:$5:$6'));
                    var bValue = new Date(b.children[columnIndex].textContent.trim().replace(/(\d{2})\/(\d{2})\/(\d{4}) (\d{2}):(\d{2}):(\d{2})/, '$3-$2-$1T$4:$5:$6'));

                    return (order === 'asc') ? aValue - bValue : bValue - aValue;
                });
            } else if (button.classList.contains('sort-by-email')) {
                rows.sort(function(a, b) {
                    var aValue = a.children[columnIndex].textContent.trim().toLowerCase();
                    var bValue = b.children[columnIndex].textContent.trim().toLowerCase();

                    return (order === 'asc') ? aValue.localeCompare(bValue) : bValue.localeCompare(aValue);
                });
            } else {
                // Caso padrão: ordenação por texto
                rows.sort(function(a, b) {
                    var aValue = a.children[columnIndex].textContent.trim().toLowerCase();
                    var bValue = b.children[columnIndex].textContent.trim().toLowerCase();

                    return (order === 'asc') ? aValue.localeCompare(bValue) : bValue.localeCompare(aValue);
                });
            }

            // Limpa o conteúdo da tabela antes de reordenar
            while (table.querySelector('tbody').firstChild) {
                table.querySelector('tbody').removeChild(table.querySelector('tbody').firstChild);
            }

            // Reinsere as linhas ordenadas na tabela
            rows.forEach(function(row) {
                table.querySelector('tbody').appendChild(row);
            });
        });
    });
}
// Limpa o conteúdo da tabela
function excluirLinhas(tabela)
{
    while (tabela.querySelector('tbody').firstChild) {
        tabela.querySelector('tbody').removeChild(tabela.querySelector('tbody').firstChild);
    }
}

function adicionarZero(numero) {
    return numero < 10 ? '0' + numero : numero;
}

function formatarDataHora(data) {
    const dia = adicionarZero(data.getDate());
    const mes = adicionarZero(data.getMonth() + 1); // Adiciona 1 porque os meses são indexados de 0 a 11
    const ano = data.getFullYear();
    const hora = adicionarZero(data.getHours());
    const minutos = adicionarZero(data.getMinutes());
    const segundos = adicionarZero(data.getSeconds());
    
    return `${dia}/${mes}/${ano} ${hora}:${minutos}:${segundos}`;
}

function confirmarAcao(mensagem, formulario, acao) {
    // Seleciona o modal e seus elementos
    var modal = document.getElementById('confirmModal');
    var confirmMessage = document.getElementById('confirmMessage');
    var confirmBtn = document.getElementById('confirmBtn');
    var cancelBtn = document.getElementById('cancelBtn');

    // Define a mensagem do modal
    confirmMessage.textContent = mensagem;

    // Exibe o modal
    modal.style.display = "block";

    // Quando o usuário clica em "Confirmar"
    confirmBtn.onclick = function () {
        formulario.querySelector('input[name="action"]').value = acao;
        formulario.submit();
    };

    // Quando o usuário clica em "Cancelar"
    cancelBtn.onclick = function () {
        modal.style.display = "none";
    };

    // Quando o usuário clica fora do modal
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };
}
function gerarLinhas(tabela, arr)
{
    const STATUS_BOTOES = {
        "Aprovado" : `
        <button type="button" onclick="confirmarAcao('Tem certeza que quer negar a sugestão?', this.form, 'reprove')">Negar</button>
        `,
        "Negado" : `
        <button type="button" onclick="confirmarAcao('Tem certeza que quer aprovar a sugestão?', this.form, 'approve')">Aprovar</button>
        `,
        "Pendente" : `
        <button type="button" onclick="confirmarAcao('Tem certeza que quer aprovar a sugestão?', this.form, 'approve')">Aprovar</button>
        <button type="button" onclick="confirmarAcao('Tem certeza que quer negar a sugestão?', this.form, 'reprove')">Negar</button>
        `
    }
    var tbody = tabela.querySelector('tbody');

    arr.forEach(dados => {
        var linha = document.createElement('tr');
        linha.id = dados.id;
        var descricao;
        var data = new Date(dados.data_hora);
        var dataFormatada = formatarDataHora(data);
        if (dados.descricao.length > 10){
            descricao = `
            <span id="descricaoResumida_${dados.id}">${dados.descricao.substring(0, 10)}...</span>
            <span id="descricaoCompleta_${dados.id}" style="display:none;">${dados.descricao}</span>
            <button data-id="${dados.id}" onclick="mostrarDescricaoCompleta(${dados.id})">Ver mais</button>
            `
        }
        else {
            descricao = dados.descricao;
        }
        
        acoes = STATUS_BOTOES[dados.situacao];

        linha.innerHTML = `
        <td>${dados.nome}</td>
        <td>${dados.email}</td>
        <td>${dados.city}</td>
        <td>${dados.road}</td>
        <td>${dados.servico}</td>
        <td>${descricao}</td>
        <td>${dataFormatada}</td>
        <td>${dados.situacao}</td>
        <td>
            <form method="post" action="">
            <input type="hidden" name="id" value="${dados.id}">
            <input type="hidden" name="action" value="">
            ${acoes}
            <button type="button">Editar</button>
            <button type="button" onclick="confirmarAcao('Tem certeza que quer excluir a sugestão?', this.form, 'exclude')">Excluir</button>
        </td>
        `;
        tbody.appendChild(linha);
    });
}


function filtrar(elemento) {
    let arr = [];

    const filtro_nome = document.getElementById("busca_nome");
    const filtro_servico = document.getElementById("selecao_servico");
    const contador_resultados = document.getElementById("contador_resultados");
    if (elemento) {
        Filtro.status = elemento.value;
    }

    if (filtro_nome) {
        Filtro.nome = filtro_nome.value;
    }

    if (filtro_servico) {
        Filtro.servico = filtro_servico.value;
    }
    arr = Filtro.realizarFiltragem(formularios_todos);
    contador_resultados.innerHTML = `
        <p>${arr.length} resultados encontrados<p>
    `;

    const tabela = document.getElementById("tabela");
    const tabelaObj = new Tabela([], tabela);
    tabelaObj.arr = arr;

    tabelaObj.excluirLinhas();
    tabelaObj.gerarLinhas();
}

function ordenar(elemento) {
    let order = elemento.getAttribute('data-order') || 'asc';

    order = (order === 'asc') ? 'desc' : 'asc';
    elemento.setAttribute('data-order', order);

    // Atualiza o ícone do botão de ordenação
    var icon = elemento.querySelector('.sort-icon');

    // Remove e adiciona a classe do ícone com base na direção da ordenação
    if (order === 'asc') {
        icon.innerHTML = '&#9662;'; // Triângulo para baixo (ordem crescente)
    } else {
        icon.innerHTML = '&#9652;'; // Triângulo para cima (ordem decrescente)
    }

    Ordenador.ordem = order;

    // Determina o critério de ordenação com base na classe do botão
    if (elemento.classList.contains('sort-by-date')) {
        Ordenador.coluna = "data_hora";
    } else if (elemento.classList.contains('sort-by-email')) {
        Ordenador.coluna = "email";
    } else {
        // Caso padrão: ordenação por nome
        Ordenador.coluna = "nome";
    }

    const tabelaObj = new Tabela([], tabela);
    tabelaObj.arr = Ordenador.realizarOrdenacao(tabelaObj.arr);

    tabelaObj.excluirLinhas();
    tabelaObj.gerarLinhas();
}

// Adiciona um evento de clique a todos os botões de "Ver mais/menos"
document.querySelectorAll('.ver-mais-btn').forEach(function (button) {
    button.addEventListener('click', function () {
        var id = button.getAttribute('data-id');
        mostrarDescricaoCompleta(id);
    });
});

// Inicializa o mapa e os botões de ordenação quando a página carrega
window.onload = function () {
    initMapAdmin();
};

// Exporta as classes
module.exports = {
    Filtro,
    Ordenador,
    Tabela
};
