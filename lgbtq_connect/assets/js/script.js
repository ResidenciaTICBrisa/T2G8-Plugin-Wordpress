let pagina = null;

// Definindo o ícone personalizado no escopo global
var personalIcon = L.icon({
    iconUrl: 'https://res.cloudinary.com/dxsx0emuu/image/upload/f_auto,q_auto/lc_marker',
    iconSize: [20, 30], // tamanho do ícone
    popupAnchor: [1, -10]
});

// CLASSES
class Mapa {
    container;

    static MAPAS = {
        "div_index": "mapa_index",
        "div_form": "mapa_formulario",
        "div_saida": "mapa_saida"
    }

    constructor(container) {
        this.container = Mapa.MAPAS[container];
        this.mapa = L.map(this.container, { doubleClickZoom: false }).setView([-15.8267, -47.9218], 13);

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

    conseguirLocalizacao() {
        const self = this;
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                let user_position = [];
                user_position.push(position.coords.latitude);
                user_position.push(position.coords.longitude);
                self.mudarLocalizacao(user_position);
            });
        } else {
            alert('Geolocalização não é suportada por este navegador.');
        }
        document.getElementById('latitude').value = '';
        document.getElementById('longitude').value = '';
    }

    destruirMapa() {
        this.mapa.remove();
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
        this.mapa = new Mapa(this.id);
        this.mapa.conseguirLocalizacao();
    }

    destruir() {
        if (this.mapa !== null) {
            this.mapa.destruirMapa();
        }
        this.div.style.display = "none";
    }
}

class PaginaComPopup extends Pagina {
    inicializar() {
        super.inicializar();

        for (var i = 0; i < formularios_aprovados.length; i++) {
            var formulario = formularios_aprovados[i];
            var popupConteudo = `
                <div class="pop">
                    <h4><strong>${formulario.nome}</strong></h4>
                    <i>${formulario.servico}</i>
                    <div class="gradiente"></div>
                    <p><strong>${formulario.descricao}</strong></p>
                </div>
            `;
            this.mapa.adicionarMarcador(L.marker([formulario.latitude, formulario.longitude], { icon: personalIcon }).bindPopup(popupConteudo));
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
        this.mapa.mapa.on('click', function (e) {
            if (self.marcador == null) {
                self.marcador = L.marker(e.latlng, { icon: personalIcon }).addTo(self.mapa.mapa);
            }
            self.marcador.setLatLng(e.latlng);

            var lat = e.latlng.lat; // Latitude
            var lng = e.latlng.lng; // Longitude

            // Atualiza os valores dos campos de entrada ocultos
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        });

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

        for (var i = 0; i < formularios_aprovados.length; i++) {
            var formulario = formularios_aprovados[i];
            this.mapa.adicionarMarcador(L.marker([formulario.latitude, formulario.longitude], { icon: personalIcon }));
        }
    }

    destruir() {
        super.destruir();
        document.getElementById("meu_formulario").reset();
    }
}

// Padrão de projeto Factory Method
class FabricaPagina {
    // Map que relaciona strings com classes
    static CLASSES = new Map([
        ['PaginaFormulario', PaginaFormulario],
        ['PaginaComPopup', PaginaComPopup]
    ]);

    // Método para criar uma nova página e retorná-la
    static criar(tipo, id) {
        if (!FabricaPagina.CLASSES.has(tipo))
            throw new Error("Página inválida");

        return new (FabricaPagina.CLASSES.get(tipo))(id);
    }
}

// Função que realiza a transição entre páginas
// Chamada uma vez quando o plugin é inicializado e depois é chamada através de botões nas páginas
function transicaoPagina(tipo, id) {
    // Se o plugin já foi inicializado, deleta a página anterior
    if (pagina !== null) {
        pagina.destruir();
    }

    pagina = FabricaPagina.criar(tipo, id);
    pagina.inicializar();
}

// Inicializa o plugin
window.onload = function () {
    transicaoPagina("PaginaComPopup", "div_index")
}