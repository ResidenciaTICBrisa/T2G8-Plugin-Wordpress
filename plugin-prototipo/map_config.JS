function criar_mapa(){
    // Inicializa o mapa
    var map = L.map('mapa', {doubleClickZoom: false}).setView([-15.8267, -47.9218], 10);

    // Adiciona o provedor de mapa OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Função para obter a localização do usuário
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            alert("Geolocalização não é suportada por este navegador.");
        }
    }

    // Função para mostrar a posição do usuário no mapa
    function showPosition(position) {
        var lat = position.coords.latitude; // Latitude
        var lng = position.coords.longitude; // Longitude

        // Atualiza os valores dos campos de entrada ocultos
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;

        // Centraliza o mapa na posição do usuário
        map.setView([lat, lng], 17);
    }

    // Chama getLocation() quando a página for carregada para obter a localização do usuário automaticamente
    window.onload = getLocation;
    return map;
}

function marcador_config($map){
    // Adiciona um pin no mapa quando clicado o mouse 1
    $map.on('click', function(e) {
        // Remove o marcador atual, se existir
        if (marcador) {
            $map.removeLayer(marcador);
        }

        // Adiciona um novo marcador na posição clicada
        marcador = L.marker(e.latlng).addTo($map);            

        var lat = e.latlng.lat; // Latitude
        var lng = e.latlng.lng; // Longitude

        // Atualiza os valores dos campos de entrada ocultos
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
    });

    // Remove o marcador quando clicado com o mouse 2
    $map.on('contextmenu', function(e) {
        // Verifica se existe um marcador atual
        if (marcador) {
            // Remove o marcador do mapa
            $map.removeLayer(marcador);
        }
    });
}