var resultados = []; // Array para armazenar os locais relacionados
var isSearchingIndex = false; // Status de busca no index
var isSearchingForm = false; // Status de busca no form

function searchButtonClicked() {
    if (!isSearchingIndex) {
        isSearchingIndex = true;
        var searchTerm = document.getElementById('searchInputIndex').value;
        searchLocations(searchTerm, 'listaResultadosIndex');
    }
    return false;
}

function searchButtonClickedForm() {
    if (!isSearchingForm) {
        isSearchingForm = true;
        var searchTerm = document.getElementById('searchInputForm').value;
        searchLocations(searchTerm, 'listaResultadosForms');
    }
    return false;
}

function searchLocations(query, resultListId) {
    resultados = [];
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
            
            // Atualiza o estado da busca após a conclusão
            if (resultListId === 'listaResultadosIndex') {
                isSearchingIndex = false; // Marca a busca na página inicial como concluída
            } else if (resultListId === 'listaResultadosForms') {
                isSearchingForm = false; // Marca a busca no formulário como concluída
            }
        })
        .catch(error => {
            console.error('Erro ao buscar locais:', error);
            
            // Em caso de erro, atualiza o estado da busca para permitir novas buscas
            if (resultListId === 'listaResultadosIndex') {
                isSearchingIndex = false; // Marca a busca na página inicial como concluída
            } else if (resultListId === 'listaResultadosForms') {
                isSearchingForm = false; // Marca a busca no formulário como concluída
            }
        });
}

function imprimirResultados(resultados, resultListId) {
    var listaResultados = document.getElementById(resultListId);
    listaResultados.innerHTML = '';
    var div = document.createElement('div');
    resultados.forEach(resultado => {
        // var icone = document.createElement('img');
        // icone.src = '../img/marker00.png';
        // icone.style.width='10%';
        // icone.style.height='10%';
        var divResultado = document.createElement('div');
        divResultado.classList.add('celula_resultado');
        // divResultado.style.border = '2px solid black';
        // divResultado.appendChild(icone);
        divResultado.style.borderRadius = '3px';
        // divResultado.style.padding = '3px';
        divResultado.style.margin = '5px 5px 5px 0px';
        divResultado.style.cursor = 'pointer';
        // divResultado.innerHTML = '<img src="https://i.imgur.com/4ZnmAxk.png" style="width=0.02%;height=0.02%;">' + resultado.display_name;
        divResultado.innerHTML = '<img src="https://i.imgur.com/4ZnmAxk.png" width="20px" height="20px">' + resultado.display_name;
        // divResultado.textContent = resultado.display_name;
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

// function mostrarOutro() {
//     var select = document.getElementById('servico');
//     var outroCampo = document.getElementById('outroServico');
//     var outroInput = document.getElementById('servico_outro');
//     if (select.value === 'outro') {
//         outroCampo.classList.remove('escondido');
//         outroInput.setAttribute('required', 'required');
//     } else {
//         outroCampo.classList.add('escondido');
//         outroInput.removeAttribute('required');
//     }
// }

function updateSelectValue(){
    var select = document.getElementById("servico");
    var outroInput = document.getElementById("servico_outro");

    if(select.value === "outro"){
        outroInput.setAttribute("name","servico");
        select.value = outroInput.value;
    }
}

document.getElementById("meu_formulario").addEventListener("submit",updateSelectValue);