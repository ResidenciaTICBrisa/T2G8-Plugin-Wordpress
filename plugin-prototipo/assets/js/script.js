var pagina;
var resultados = []; // Array para armazenar os locais relacionados
var mapas = {
    "div_index" : "mapa_index",
    "div_form" : "mapa_formulario",
    "div_saida" : "mapa_saida"
}

// Função para destacar uma determinada linha na tabela de formulários aprovados
function destacarLinhaTabela(id) {
    var tabela = document.getElementById('tabela-Aprovado');
    var linha = document.getElementById(id)

    // Loop para remover a linha-destacada de todas as linhas 
    for (var i = 0, row; row = tabela.rows[i]; i++) {
        row.classList.remove('linha-destacada');
    }

    linha.classList.add('linha-destacada'); // Adiciona a classe 'linha-destacada'
    linha.scrollIntoView({ behavior: 'smooth' }) // Rola a página para a linha

    // Remove a classe linha-destacada depois de um determinado tempo 
    setTimeout(function () {
        linha.classList.remove('linha-destacada');
    }, 2000);
}

function conseguirLocalizacao(mapa) {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            var user_position = [];
            user_position.push(position.coords.latitude); 
            user_position.push(position.coords.longitude); 
            mudarPosicao(mapa, user_position);
        });
    } else {
        alert("Geolocalização não é suportada por este navegador.");
    }
}

function mudarPosicao(mapa, posicao) {
    mapa.setView(posicao, 13);
}

function retornarLocalizacao(posicao)
{
    return posicao;
}

class Mapa {
    container;

    constructor(container) {
        this.container = container;
        this.mapa = L.map(container, { doubleClickZoom: false }).setView([-15.8267, -47.9218], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(this.mapa);

        this.marcadores = [];
    }

    adicionarMarcador(marcador) {
        marcador.addTo(this.mapa);
        this.marcadores.push(marcador);
    }

    mudarLocalizacao(coordernadas) {
        this.mapa.setView(coordernadas, 13);
    }
}

class Pagina {
    id;
    constructor(id) {
        this.id = id;
        this.div = document.getElementById(id);
        this.mapa = null;

    }

    inicializar() {
        this.div.style.display = "block";
        this.mapa = new Mapa(mapas[this.id]);
        conseguirLocalizacao(this.mapa.mapa);
    }

    destruir() {
        this.div.style.display = "none";
        setTimeout(function () {
            if (this.mapa !== null) {
                this.mapa.remove();
                this.mapa = null;
            }
        }, 0);
    }
}

class PaginaInicial extends Pagina {
    inicializar() {
        super.inicializar();

        for(var i = 0; i<formularios_aprovados.length; i++)
        {
            var formulario = formularios_aprovados[i];
            var popupConteudo = `
            <div>
                <h4>Nome do Local:${formulario.nome}</h4>
                <p><strong>Descrição:</strong> ${formulario.descricao}</p>
            </div>
                `;
            this.mapa.adicionarMarcador(L.marker([formulario.latitude, formulario.longitude]).bindPopup(popupConteudo));
        }
    }

    destruir() {
        super.destruir();
    }
}

class PaginaFormulario extends Pagina {
    inicializar() {
        super.inicializar();

        this.mapa.on('click', function(e) {

            marcador = L.marker(e.latlng).addTo(mapFormulario);

            var lat = e.latlng.lat; // Latitude
            var lng = e.latlng.lng; // Longitude

            // Atualiza os valores dos campos de entrada ocultos
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        })

        for(var i = 0; i<formularios_aprovados.length; i++)
        {
            var formulario = formularios_aprovados[i];
            this.mapa.adicionarMarcador(L.marker([formulario.latitude, formulario.longitude]));
        }
    }

    destruir() {
        super.destruir();
        document.getElementById("meu_formulario").reset();
    }
}

class PaginaAdmin extends Pagina {
    inicializar() {
        super.inicializar();
    }

    destruir() {
        super.destruir();
    }
}

function inicializarPlugin()
{
    pagina = new PaginaInicial("div_index");
    pagina.inicializar();
}

window.onload = function () {
    inicializarPlugin();
};

function mostrarOutro() {
    var select = document.getElementById("servico");
    var outroCampo = document.getElementById("outroServico");
    var outroInput = document.getElementById("servico_outro");
    if (select.value === "outro") {
        outroCampo.classList.remove("escondido");
        outroInput.setAttribute("required", "required");
    }
    else {
        outroCampo.classList.add("escondido");
        outroInput.removeAttribute("required");
    }
}

function updateSelectValue() {
    var select = document.getElementById("servico");
    var outroInput = document.getElementById("servico_outro");

    if (select.value === "outro") {
        select.value = outroInput.value;
    }
}

document.getElementById("meu_formulario").addEventListener("submit", updateSelectValue);

function searchButtonClicked() {
    var searchTerm = document.getElementById('searchInputIndex').value;
    resultados = [];
    searchLocations(searchTerm, 'listaResultadosIndex');
    return false;
}

function searchButtonClickedForm() {
    var searchTerm = document.getElementById('searchInputForm').value;
    resultados = [];
    searchLocations(searchTerm, 'listaResultadosForms');
    return false;
}

function searchLocations(query, resultListId) {
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
            imprimirResultados(resultados, resultListId);
        })
        .catch(error => console.error('Erro ao buscar locais:', error));
}

function imprimirResultados(resultados, resultListId) {
    var listaResultados = document.getElementById(resultListId);
    listaResultados.innerHTML = '';
    var div = document.createElement('div');
    resultados.forEach(resultado => {
        var divResultado = document.createElement('div');
        divResultado.style.border = '2px solid black';
        divResultado.style.borderRadius = '3px';
        divResultado.style.padding = '3px';
        divResultado.style.margin = '5px 5px 5px 0px';
        divResultado.style.cursor = 'pointer';
        divResultado.textContent = resultado.display_name;
        divResultado.addEventListener('click', function () {
            changeMapLocation(resultado.lat, resultado.lon);
        });
        div.appendChild(divResultado);
    });
    listaResultados.appendChild(div);
}

function changeMapLocation(latitude, longitude) {
    if (map) {
        map.setView([latitude, longitude], 13);
    }
    if (mapFormulario) {
        mapFormulario.setView([latitude, longitude], 13);
    }
}
