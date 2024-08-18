let pagina = null;

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

        this.mapa.addControl(new CustomControl());
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

            // Usa a função getMarcador passando o tipo de serviço
            const icon = getMarcador(formulario.servico);
            if (icon) {
                this.mapa.adicionarMarcador(L.marker([formulario.latitude, formulario.longitude], { icon: icon }).bindPopup(popupConteudo));
            } else {
                console.error('Erro ao criar o ícone para:', formulario.servico);
            }
        }
    }
}

class PaginaFormulario extends Pagina {
    inicializar() {
        super.inicializar();
        this.marcador = null;
        var self = this;

        this.mapa.mapa.on('click', function (e) {
            if (self.marcador === null) {
                const icon = getMarcador(undefined, true); 
                self.marcador = L.marker(e.latlng, { icon: icon }).addTo(self.mapa.mapa);
            } else {
                self.marcador.setLatLng(e.latlng);
            }
            document.getElementById('latitude').value = e.latlng.lat;
            document.getElementById('longitude').value = e.latlng.lng;
        
            Verificador.verificarCoordenadas();
        });
        

        this.mapa.mapa.on('contextmenu', function (e) {
            // Verifica se existe um marcador atual
            if (self.marcador !== null) {
                // Remove o marcador do mapa
                self.mapa.mapa.removeLayer(self.marcador);
                document.getElementById('latitude').value = '';
                document.getElementById('longitude').value = '';
                Verificador.verificarCoordenadas();
                self.marcador = null;
            }
        });

        // Adiciona marcadores ao mapa com base em formulários aprovados
        for (var i = 0; i < formularios_aprovados.length; i++) {
            var formulario = formularios_aprovados[i];
            const icon = getMarcador(formulario.servico);
            if (icon) {
                this.mapa.adicionarMarcador(L.marker([formulario.latitude, formulario.longitude], { icon: icon }));
            } else {
                console.error('Erro ao criar o ícone para:', formulario.servico);
            }
        }
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

// Define a URL do ícone padrão
const DEFAULT_ICON_URL = marcador_mapa; // Usa a URL definida pelo PHP

// Função para obter o ícone do marcador
function getMarcador(tipoServico = 'outro', usarPadrao = false) {
    const url = usarPadrao ? marcadores['outro'] : (marcadores[tipoServico] || marcadores['outro']);
    
    // Verifica se a URL está definida e válida
    if (!url) {
        console.error('URL do ícone não foi definida corretamente:', { tipoServico, usarPadrao });
        return L.icon({
            iconUrl: DEFAULT_ICON_URL, // Usa a URL do ícone padrão
            iconSize: [60, 60],
            popupAnchor: [1, -10]
        });
    }

    return L.icon({
        iconUrl: url,
        iconSize: [60, 60],
        popupAnchor: [1, -10]
    });
}


// Definindo o ícone personalizado no escopo global usando a função getMarcador
const personalIcon = getMarcador();

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
    transicaoPagina("PaginaComPopup", "div_index");
    Verificador.nome = document.getElementById("nome");
    Verificador.email = document.getElementById("email_f");
    Verificador.servico = document.getElementById("servico"); 
    Verificador.servico_outro = document.getElementById("servico_outro");
    Verificador.descricao = document.getElementById("descricao");
    Verificador.latitude = document.getElementById("latitude");
    Verificador.longitude = document.getElementById("longitude"); 
}

function abrirFiltroServico(){
    const modal = document.getElementById('modal_filtro');
    const botao = document.getElementById('filtro_servico');

    // Alterna entre mostrar e esconder o modal
    if (modal.style.display === "none" || modal.style.display === "") {
        modal.style.display = "block";
        botao.style.backgroundColor = '#e2e2e2';
        botao.style.border = '1px solid #979797';
    } else {
        modal.style.display = "none";
        botao.style.backgroundColor = '#f5f5f5';
        botao.style.border = '1px solid rgb(209, 216, 212)';
    }
    
}

// Previne o fechamento do modal ao clicar nos checkboxes
document.querySelectorAll('.name_servico').forEach(checkbox => {
    checkbox.addEventListener('click', function(event) {
        event.stopPropagation(); // Impede que o clique se propague e feche o modal
    });
});

// Fechar o modal se clicar fora dele
window.onclick = function(event) {
    const modal = document.getElementById('modal_filtro');
    const botao = document.getElementById('filtro_servico');
    if (event.target === modal) {
        modal.style.display = "none";
        botao.style.backgroundColor = '#f5f5f5';
        botao.style.border = '1px solid rgb(209, 216, 212)';
    }
}

// Adiciona a lógica para o botão de filtro
document.getElementById("filtro_servico").addEventListener("click", abrirFiltroServico);
