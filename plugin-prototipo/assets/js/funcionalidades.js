var resultados = []; // Array para armazenar os locais relacionados

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
    if (pagina)
    {
        pagina.mapa.mudarLocalizacao([latitude, longitude]);
    }
}

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