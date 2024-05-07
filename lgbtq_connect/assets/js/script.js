var pagina = null;
const mapas = {
    "div_index" : "mapa_index",
    "div_form" : "mapa_formulario",
    "div_saida" : "mapa_saida"
}

        // FUNÇÕES AUXILIARES
function conseguirLocalizacao(mapa) {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            var user_position = [];
            user_position.push(position.coords.latitude); 
            user_position.push(position.coords.longitude); 
            mudarPosicao(mapa, user_position);
        });
    } else {
        alert('Geolocalização não é suportada por este navegador.');
    }
    document.getElementById('latitude').value = '';
    document.getElementById('longitude').value = '';
}

function mudarPosicao(mapa, posicao) {
    mapa.setView(posicao, 13);
}

        // CLASSES
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

    destruirMapa() {
        this.mapa.remove()
        this.mapa = null;
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
        if(this.mapa !== null)
        {
            this.mapa.destruirMapa();
        }
        this.div.style.display = "none";
    }
}

class PaginaComPopup extends Pagina {
    inicializar() {
        super.inicializar();

        for(var i = 0; i<formularios_aprovados.length; i++)
        {
            var formulario = formularios_aprovados[i];
            var popupConteudo = `
            <div class="pop">
                <h4><strong>${formulario.nome}</strong></h4>
                <i>${formulario.servico}</i>
                <div class="gradiente"></div>
                <p><strong>${formulario.descricao}</strong></p>
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
        this.marcador = null;
        var self = this;
        this.mapa.mapa.on('click', function(e) {
            
            if(self.marcador==null)
            {
                self.marcador = L.marker(e.latlng).addTo(self.mapa.mapa);         
            }
            
            self.marcador.setLatLng(e.latlng);

            var lat = e.latlng.lat; // Latitude
            var lng = e.latlng.lng; // Longitude

            // Atualiza os valores dos campos de entrada ocultos
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        })

        this.mapa.mapa.on('contextmenu', function (e) {
            // Verifica se existe um marcador atual
            if (self.marcador !== null) {
                // Remove o marcador do mapa
                self.mapa.mapa.removeLayer(self.marcador);
                document.getElementById('latitude').value = '';
                document.getElementById('longitude').value = '';
                self.marcador = null;
            }
        });

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

const classes = new Map([
    ['PaginaFormulario', PaginaFormulario],
    ['PaginaComPopup', PaginaComPopup]
  ]);

function inicializarPlugin()
{
    pagina = new PaginaComPopup("div_index");
    pagina.inicializar();
}

function transicaoPagina(proxPagina, id)
{
    pagina.destruir();
    pagina = new(classes.get(proxPagina))(id);
    pagina.inicializar();
}

window.onload = function () {
    inicializarPlugin();
};