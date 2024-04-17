// Inicializa o mapa quando a página for carregada
var map;
var marcador;
var mapFormulario;
var mapAdmin
var map_exit;

function highlightTableRow(id) {
    var table = document.getElementById('tabela-Aprovado');
    for (var i = 0, row; row = table.rows[i]; i++) {
        row.classList.remove('destacado');
     }
    document.getElementById(id).classList.add('destacado');
}

// Envia os dados do formulário de forma assíncrona usando AJAX e JQuery
var ajaxUrl = my_ajax_object.ajax_url;

document.addEventListener('DOMContentLoaded', function() {
    $('#meu_formulario').on('submit', function(e) {
        e.preventDefault(); // Previne que o formulário dê submit na forma padrão
        
        // Serializa os dados do formulário
        var formData = $(this).serialize();

        // Envia o pedido do AJAX
        $.ajax({
            type: 'POST',
            url: ajaxUrl,
            data: {
                action: 'processar_formulario', // Hook de ação para o lado do servidor
                formData: formData // Data do formulário
            },
            success: function(response) {
                // Resposta caso dê certo
                console.log(response);
                // Se o envio for bem-sucedido, executar a função exit_page()
                exit_page();
            },
            error: function(xhr, status, error) {
                // Resposta caso dê errado
                console.error(error);
            }
        });
    });
});

        // CRIANDO MAPAS

function initMap() {
    if(document.getElementById('mapa') == null) 
    {   
        return;
    }

    map = L.map('mapa', {doubleClickZoom: false}).setView([-15.8267, -47.9218], 13);

    // Adiciona o provedor de mapa OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    getLocation(map);

    formularios_aprovados.forEach(function(formulario) {
        L.marker([formulario.latitude, formulario.longitude]).bindPopup(formulario.nome).addTo(map);
    });
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

    formularios_aprovados.forEach(function(formulario) {
        L.marker([formulario.latitude, formulario.longitude]).addTo(map_exit);
    });

    console.log("Formulário enviado com sucesso!");
}

function initMapAdmin() {
    if(document.getElementById('mapa_admin') == null) 
    {   
        return;
    }

    mapAdmin = L.map('mapa_admin', {doubleClickZoom: false}).setView([-15.8267, -47.9218], 13);

    // Adiciona o provedor de mapa OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(mapAdmin);

    formularios_aprovados.forEach(function(formulario) {
        L.marker([formulario.latitude, formulario.longitude]).bindPopup(formulario.nome).addTo(mapAdmin).on('click', function() {

            highlightTableRow(formulario.id);
        });
    });
}

        // DESTRUINDO MAPAS

// Função para destruir o mapa
function destroyMap() {
    setTimeout(function(){
        if(map!==null){
            map.remove();
            map=null;
            console.log("Sucesso ao destruir o map");
        }
    }, 0);
}

function destroyMapForm(){
    document.getElementById('meu_formulario').reset();
    setTimeout(function(){
        if(mapFormulario!==null){
            mapFormulario.remove();
            mapFormulario=null;
            console.log("Sucesso ao destruir o map_form");
        }
    }, 0);
}

function destroyExitMap(){
    setTimeout(function(){
        if(map_exit!==null){
            map_exit.remove();
            map_exit=null;
        }
        console.log("Sucesso ao destruir o map_exit");
    }, 0);
}

        // TRANSIÇÕES ENTRE PÁGINAS DO PLUGIN

// Função para mostrar o formulário e destruir o mapa
function mostrarFormulario() {
    destroyMap();
    document.getElementById('div_index').style.display = 'none';
    document.getElementById('div_exit').style.display = 'none';
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

function voltar_exit(){
    destroyExitMap();
    voltar();
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

    formularios_aprovados.forEach(function(formulario) {
        L.marker([formulario.latitude, formulario.longitude]).bindPopup(formulario.nome).addTo(mapFormulario);
    });

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
    });

    // Remove o marcador quando clicado com o mouse 2
    mapFormulario.on('contextmenu', function(e) {
        // Verifica se existe um marcador atual
        if (marcador) {
            // Remove o marcador do mapa
            mapFormulario.removeLayer(marcador);
        }
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
    if(document.getElementById('latitude') && document.getElementById('longitude')){
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
    }

    // Centraliza o mapa na posição do usuário
    mapa.setView([lat, lng], 13);
}

// Chama initMap() quando a página for carregada
window.onload = function() {
    initMap();
    initMapAdmin();
};

// Função que permite voltar da tela final para a tela inicial
function preencher_novamente(){
    destroyExitMap();
    mostrarFormulario();
}

function mostrarOutro(){
    var select = document.getElementById("servico");
    var outroCampo = document.getElementById("outroServico");
    var outroInput = document.getElementById("servico_outro");
    if(select.value==="outro"){
        outroCampo.classList.remove("escondido");
        outroInput.setAttribute("required", "required");
    }
    else{
        outroCampo.classList.add("escondido");
        outroInput.removeAttribute("required");
    }
}