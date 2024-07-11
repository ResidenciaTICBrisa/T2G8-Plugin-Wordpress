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
                <button type="button" class="btn btn-danger" onclick="confirmarAcao('Tem certeza que quer negar a sugestão?', this.form, 'reprove')">Negar</button>
            `,
            "Negado": `
                <button type="button" class="btn btn-success" onclick="confirmarAcao('Tem certeza que quer aprovar a sugestão?', this.form, 'approve')">Aprovar</button>
            `,
            "Pendente": `
                <button type="button" class="btn btn-success" onclick="confirmarAcao('Tem certeza que quer aprovar a sugestão?', this.form, 'approve')">Aprovar</button>
                <button type="button" class="btn btn-danger" onclick="confirmarAcao('Tem certeza que quer negar a sugestão?', this.form, 'reprove')">Negar</button>
            `
        };

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
            <td id="formulario-${dados.id}-cidade">${dados.city}</td>
            <td id="formulario-${dados.id}-rua">${dados.road}</td>
            <td id="formulario-${dados.id}-servico">${dados.servico}</td>
            <td id="formulario-${dados.id}-descricao">${descricao}</td>
            <td id="formulario-${dados.id}-data_hora">${dataFormatada}</td>
            <td id="formulario-${dados.id}-situacao">${dados.situacao}</td>
            <td id="formulario-${dados.id}-acoes">
            <div class="btn-group dropend">
                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    Ações
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <form method="post" action="" class="d-flex flex-wrap justify-content-between my-1">
                            <input type="hidden" name="id" value="${dados.id}">
                            <input type="hidden" name="action" value="">
                            <button type="button" class="btn btn-primary mb-1" onclick='abrirModalEdicao(${JSON.stringify(dados)})'>Editar</button>
                            ${acoes}
                            <button type="button" class="btn btn-danger mt-1" onclick="confirmarAcao('Tem certeza que quer excluir a sugestão?', this.form, 'exclude')">Excluir</button>
                        </form>
                    </li>
                </ul>
            </div>
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


    var CustomControl = L.Control.extend({
        options: {
            position: 'bottomright'
        },

        onAdd: function (map) {
            var container = L.DomUtil.create('div', 'leaflet-control-custom');

            container.onclick = function () {
                if (!document.fullscreenElement) {
                    map.getContainer().requestFullscreen();
                } else {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    }
                }
            };

            // Adiciona ouvintes para mudança de estado de tela cheia
            document.addEventListener('fullscreenchange', function () {
                if (document.fullscreenElement) {
                    container.classList.add('fullscreen');
                } else {
                    container.classList.remove('fullscreen');
                }
            });

            return container;
        }
    });

    mapAdmin.addControl(new CustomControl());
}

function initMapEdit(latitude, longitude, nome, servico, descricao) {
    // Definindo o ícone personalizado no escopo global
    const personalIcon = L.icon({
        iconUrl: 'https://res.cloudinary.com/dxsx0emuu/image/upload/f_auto,q_auto/lc_marker',
        iconSize: [20, 30], // tamanho do ícone
        popupAnchor: [1, -10]
    });

    // Verifica se o mapa já foi inicializado e destrói se necessário
    if (mapEdit !== undefined) {
        mapEdit.remove();
    }

    mapEdit = L.map('mapa_formulario_edit', { doubleClickZoom: false }).setView([-15.8267, -47.9218], 13);

    // Adiciona o provedor de mapa OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(mapEdit);


    var CustomControl = L.Control.extend({
        options: {
            position: 'bottomright'
        },

        onAdd: function (map) {
            var container = L.DomUtil.create('div', 'leaflet-control-custom');

            container.onclick = function () {
                if (!document.fullscreenElement) {
                    map.getContainer().requestFullscreen();
                } else {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    }
                }
            };

            // Adiciona ouvintes para mudança de estado de tela cheia
            document.addEventListener('fullscreenchange', function () {
                if (document.fullscreenElement) {
                    container.classList.add('fullscreen');
                } else {
                    container.classList.remove('fullscreen');
                }
            });

            return container;
        }
    });

    mapEdit.addControl(new CustomControl());

    var popupConteudo = `
    <div class="pop">
        <h4><strong>${nome}</strong></h4>
        <i>${servico}</i>
        <div class="gradiente"></div>
        <p><strong>${descricao}</strong></p>
    </div>
        `;
    
    // Função para atualizar os inputs de latitude e longitude
    function updateInputs(lat, lng) {
        document.getElementById('editLatitude').value = lat;
        document.getElementById('editLongitude').value = lng;
    }

    // Adiciona um marcador arrastável
    var marker = L.marker(
        [latitude, longitude],
        {draggable: true, icon: personalIcon }
    ).addTo(mapEdit).bindPopup(popupConteudo);

    marker.on('dragend', function (e) {
        var newPosition = marker.getLatLng();
        updateInputs(newPosition.lat, newPosition.lng);
    });

    document.getElementById('mapa_admin').style.display = "block";
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

function abrirModalEdicao(dados) {
    const popup = document.getElementById("editPopup"); 
    const modal = document.getElementById('editModal');

    // Preenche os campos do formulário com os dados fornecidos
    document.getElementById('editId').value = dados.id;
    document.getElementById('editNome').value = dados.nome;
    document.getElementById('editEmail').value = dados.email;
    document.getElementById('editServico').value = dados.servico;
    document.getElementById('editDescricao').value = dados.descricao;
    document.getElementById('editLatitude').value = dados.latitude;
    document.getElementById('editLongitude').value = dados.longitude;

    initMapEdit(dados.latitude, dados.longitude, dados.nome, dados.servico, dados.descricao);

    // Exibe o modal de edição
    popup.style.display = "flex";
    modal.style.display = "block";

    // Atualiza o tamanho do mapa e define a visualização após um pequeno atraso para garantir que o modal tenha sido completamente exibido
    setTimeout(function() {
        mapEdit.invalidateSize();
        mapEdit.setView([dados.latitude, dados.longitude], 13);
    }, 200);

    modal.scrollIntoView({ behavior: 'smooth' });

    // Fecha o modal de edição quando o usuário clica fora do modal
    window.onclick = function(event) {
        if (event.target == popup) {
            fecharEditor();
        }
    };
}

function fecharEditor() {
    document.getElementById('editPopup').style.display = "none";
    document.getElementById('editModal').style.display = "none";
    document.getElementById('mapa_admin').style.display = "block";
    document.getElementById('listaResultadosEdit').innerHTML = '';
    document.getElementById('searchInputFormEdit').value = '';
}

function searchButtonClickedEdit() {
    if (isSearching) {
        return; // Se uma busca já estiver em andamento, saia da função
    }
    isSearching = true; // Indica que uma busca está em andamento
    var searchTerm = document.getElementById('searchInputFormEdit').value;
    searchLocations(searchTerm);
}

function searchLocations(query) {
    var resultados = [];
    var apiUrl = 'https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(query);
    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            data.forEach(location => {
                resultados.push({
                    display_name: location.display_name,
                    lat: location.lat,
                    lon: location.lon
                });
            });
            imprimirResultados(resultados);
            isSearching = false; // Indica que a busca foi concluída
        })
        .catch(error => {
            console.error('Erro ao buscar locais:', error);
            isSearching = false; // Indica que a busca foi concluída mesmo com erro
        });
}

function imprimirResultados(resultados) {
    var listaResultadosOcultados = [];
    var listaResultados = document.getElementById('listaResultadosEdit');

    listaResultados.innerHTML = '';
    var count = 0;
    var div = document.createElement('div');
    resultados.forEach(resultado => {
        var divResultado = document.createElement('div');
        divResultado.classList.add('celula_resultado');
        divResultado.style.borderRadius = '3px';
        divResultado.style.margin = '5px 5px 5px 0px';
        divResultado.style.cursor = 'pointer';
        divResultado.innerHTML = '<img src="https://i.imgur.com/4ZnmAxk.png" width="20px" height="20px">' + resultado.display_name;
        divResultado.addEventListener('click', function () {
            changeMapView(resultado.lat, resultado.lon);
        });
        count += 1;
        if(count <= 5) {
            div.appendChild(divResultado);
        } else {
            divResultado.style.display = 'none';
            listaResultadosOcultados.push(divResultado);
            div.appendChild(divResultado);
        } 
    });

    listaResultados.appendChild(div);

    if (count > 5) {
        // Adicionando botão "Ver Mais"
        var verMaisButton = document.createElement('button');
        verMaisButton.textContent = 'Ver Mais';
        verMaisButton.setAttribute('type', 'button');
        verMaisButton.setAttribute('class', 'ver');
        verMaisButton.addEventListener('click', function() {
            MostrarMaisResultados();
        });

        listaResultados.appendChild(verMaisButton);

        // Adicionando botão "Ver Menos"
        var verMenosButton = document.createElement('button');
        verMenosButton.textContent = 'Ver Menos';
        verMenosButton.setAttribute('type', 'button');
        verMenosButton.setAttribute('class', 'ver');
        verMenosButton.addEventListener('click', function() {
            MostrarMenosResultados();
        });
        verMenosButton.style.display = 'none';

        listaResultados.appendChild(verMenosButton);

        // Função para mostrar mais resultados
        function MostrarMaisResultados() {
            listaResultadosOcultados.forEach(resultado => {
                resultado.style.display = 'block';
            });
            verMaisButton.style.display = 'none';
            verMenosButton.style.display = 'block';
        }

        // Função para mostrar menos resultados
        function MostrarMenosResultados() {
            listaResultadosOcultados.forEach(resultado => {
                resultado.style.display = 'none';
            });
            verMaisButton.style.display = 'block';
            verMenosButton.style.display = 'none';
        }
    }
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