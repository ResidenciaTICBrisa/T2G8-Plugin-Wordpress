let mapAdmin;
let mapEdit;

class Filtro {
    static status = "Todos";
    static nome = "";
    static servico = "todos";

    static realizarFiltragem(arr) {
        const self = this;
        return arr.filter(function (formulario) {
            return (self.checarStatus(formulario) && self.checarNome(formulario) && self.checarServico(formulario));
        });
    }

    static checarStatus(formulario) {
        if (this.status !== "Todos") {
            return (this.status == formulario.situacao);
        }
        else {
            return true;
        }
    }
    
    static checarNome(formulario) {
        return(formulario.nome.toLowerCase().trim().startsWith(this.nome));
    }

    static checarServico(formulario) {
        if (this.servico !== "") {
            return(this.servico == formulario.servico);
        }
        else {
            return true;
        }
    }
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
    if (document.getElementById('mapa_admin') == null) {
        return;
    }

    // Verifica se o mapa já foi inicializado e destrói se necessário
    if (mapAdmin !== undefined) {
        mapAdmin.remove();
    }

    mapAdmin = L.map('mapa_admin', { doubleClickZoom: false }).setView([-15.8267, -47.9218], 13);

    // Adiciona o provedor de mapa OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(mapAdmin);

    formularios_aprovados.forEach(function(formulario) {
        L.marker([formulario.latitude, formulario.longitude]).addTo(mapAdmin).on('click', function() {
            destacarLinhaTabela(formulario.id);
        });
    });
}

function initMapEdit(latitude, longitude, nome, servico, descricao) {
    document.getElementById('mapa_admin').style.display = "none";

    // Verifica se o mapa já foi inicializado e destrói se necessário
    if (mapEdit !== undefined) {
        mapEdit.remove();
    }

    mapEdit = L.map('mapa_formulario_edit', { doubleClickZoom: false }).setView([-15.8267, -47.9218], 13);

    // Adiciona o provedor de mapa OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(mapEdit);

    var popupConteudo = `
    <div class="pop">
        <h4><strong>${nome}</strong></h4>
        <i>${servico}</i>
        <div class="gradiente"></div>
        <p><strong>${descricao}</strong></p>
    </div>
        `;

    L.marker([latitude, longitude]).addTo(mapEdit).bindPopup(popupConteudo);
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
    confirmBtn.onclick = function() {
        formulario.querySelector('input[name="action"]').value = acao;
        formulario.submit();
    };

    // Quando o usuário clica em "Cancelar"
    cancelBtn.onclick = function() {
        modal.style.display = "none";
    };

    // Quando o usuário clica fora do modal
    window.onclick = function(event) {
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
        <td>${dados.latitude}</td>
        <td>${dados.longitude}</td>
        <td>${dados.servico}</td>
        <td>${descricao}</td>
        <td>${dataFormatada}</td>
        <td>${dados.situacao}</td>
        <td>
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

function abrirModalEdicao(dados) {
    var modal = document.getElementById('editModal');
    var cancelEditBtn = document.getElementById('cancelEditBtn');

    // Preenche os campos do formulário com os dados fornecidos
    document.getElementById('editId').value = dados.id;
    document.getElementById('editNome').value = dados.nome;
    document.getElementById('editEmail').value = dados.email;
    document.getElementById('editServico').value = dados.servico;
    document.getElementById('editDescricao').value = dados.descricao;

    initMapEdit(dados.latitude, dados.longitude, dados.nome, dados.servico, dados.descricao);

    // Exibe o modal de edição
    modal.style.display = "block";

    // Atualiza o tamanho do mapa e define a visualização após um pequeno atraso para garantir que o modal tenha sido completamente exibido
    setTimeout(function() {
        mapEdit.invalidateSize();
        mapEdit.setView([dados.latitude, dados.longitude], 13);
    }, 200);

    // Fecha o modal de edição quando o botão cancelar é clicado
    cancelEditBtn.onclick = function() {
        fecharEditor();
    };

    // Fecha o modal de edição quando o usuário clica fora do modal
    window.onclick = function(event) {
        if (event.target == modal) {
            fecharEditor();
        }
    };
}

function fecharEditor() {
    document.getElementById('editModal').style.display = "none";
    document.getElementById('mapa_admin').style.display = "block";
}

function filtrar(elemento) {
    let arr = [];

    const filtro_nome = document.getElementById("busca_nome");
    const filtro_servico = document.getElementById("selecao_servico");

    const contador_resultados = document.getElementById("contador_resultados");

    if (elemento) {
        Filtro.status = elemento.value;
    }

    if(filtro_nome)
    {
        Filtro.nome = filtro_nome.value.toLowerCase().trim();
    }

    if (filtro_servico)
    {
        Filtro.servico = filtro_servico.value;
    }

    arr = Filtro.realizarFiltragem(formularios_todos);
    contador_resultados.innerHTML = `
        <p>${arr.length} resultados encontrados<p>
    `;

    const tabela = document.getElementById("tabela");
    excluirLinhas(tabela);
    gerarLinhas(tabela, arr);
}

// Adiciona um evento de clique a todos os botões de "Ver mais/menos"
document.querySelectorAll('.ver-mais-btn').forEach(function(button) {
    button.addEventListener('click', function() {
        var id = button.getAttribute('data-id');
        mostrarDescricaoCompleta(id);
    });
});

// Inicializa o mapa e os botões de ordenação quando a página carrega
window.onload = function () {
    initSortButtons();
    initMapAdmin();
};

