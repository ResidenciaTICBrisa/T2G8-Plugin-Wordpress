// Inicializa o mapa quando a página for carregada
var map;
var marcador;
var mapFormulario;
var map_exit;

        // CRIANDO MAPAS

function initMap() {
    map = L.map('mapa', {doubleClickZoom: false}).setView([-15.8267, -47.9218], 13);

    // Adiciona o provedor de mapa OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    getLocation(map);
}

function exit_page_map(){
    map_exit = L.map('mapa_exit', {
        doubleClickZoom: false
    }).setView([-15.8267, -47.9218], 13);

    // Adiciona o provedor de mapa OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map_exit);

    getLocation(map_exit);

    console.log("Sucesso");
}

        // DESTRUINDO MAPAS

// Função para destruir o mapa
function destroyMap() {
    map.remove();
    map = null;
}

function destroyMapForm(){
    setTimeout(function(){
        if(mapFormulario!==null){
            mapFormulario.remove();
            mapFormulario=null;
        }
    }, 0);
    console.log("Sucesso ao destruir o mapform");
}

function destroyExitMap(){
    setTimeout(function(){
        if(map_exit!==null){
            map_exit.remove();
            map_exit=null;
        }
    }, 0);
    console.log("Sucesso ao destruir o mapexit");
}

        // TRANSIÇÕES ENTRE PÁGINAS DO PLUGIN

// Função para mostrar o formulário e destruir o mapa
function mostrarFormulario() {
    destroyMap();
    document.getElementById('div_index').style.display = 'none';
    document.getElementById('div_form').style.display = 'block';
    initMapFormulario();
}

function voltar() {
    destroyMapForm();
    document.getElementById('div_form').style.display = 'none';
    document.getElementById('div_exit').style.display = 'none';
    document.getElementById('div_index').style.display = 'block';
    initMap();
}

function exit_page(){
    destroyMapForm();
    document.getElementById('div_form').style.display='none';
    document.getElementById('div_index').style.display='none';
    document.getElementById('div_exit').style.display='block';
    exit_page_map();
}

// Função para inicializar o mapa no formulário
function initMapFormulario() {

    mapFormulario = L.map('mapaFormulario', {doubleClickZoom: false}).setView([-15.8267, -47.9218], 13);

    // Adiciona o provedor de mapa OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(mapFormulario);

    getLocation(mapFormulario);

    // Adiciona um marcador no mapa quando clicado o mouse 1
    mapFormulario.on('click', function(e) {
        // Remove o marcador atual, se existir
        if (marcador) {
            mapFormulario.removeLayer(marcador);
        }

        // Adiciona um novo marcador na posição clicada
        marcador = L.marker(e.latlng).addTo(mapFormulario);            

        var lat = e.latlng.lat; // Latitude
        var lng = e.latlng.lng; // Longitude

        // Atualiza os valores dos campos de entrada ocultos
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;

        // Atualiza o valor de status do marcador para verdadeiro
        document.getElementById('marcadorAtivo').value = 1;
    });

    // Remove o marcador quando clicado com o mouse 2
    mapFormulario.on('contextmenu', function(e) {
        // Verifica se existe um marcador atual
        if (marcador) {
            // Remove o marcador do mapa
            mapFormulario.removeLayer(marcador);
        }

        // Atualiza o valor de status do marcador para falso
        document.getElementById('marcadorAtivo').value = 0;
    });
}

// Função para obter a localização do usuário
function getLocation(mapa) {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            showPosition(position, mapa);
        });
    } else {
        alert("Geolocalização não é suportada por este navegador.");
    }
}

// Função para mostrar a posição do usuário no mapa
function showPosition(position, mapa) {
    var lat = position.coords.latitude; // Latitude
    var lng = position.coords.longitude; // Longitude

    // Atualiza os valores dos campos de entrada ocultos
    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lng;

    // Centraliza o mapa na posição do usuário
    mapa.setView([lat, lng], 13);
}

// Chama initMap() quando a página for carregada
window.onload = function() {
    initMap();
};

// Função que permite voltar da tela final para a tela inicial - no momento está recarregando a página, mas idealmente deve recarregar só o container do plugin
function preencher_novamente(){
    window.location.reload();
}