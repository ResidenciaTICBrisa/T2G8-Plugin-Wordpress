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
    var listaResultadosOcultados = [];
    var listaResultados = document.getElementById(resultListId);
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
            changeMapLocation(resultado.lat, resultado.lon);
        });
        count += 1;
        if(count <=5){
            div.appendChild(divResultado);
        } else {
            divResultado.style.display = 'none';
            listaResultadosOcultados.push(divResultado);
            div.appendChild(divResultado);
        } 
    });
    

    listaResultados.appendChild(div);

    if (count > 5){
        // Adicionando botão "Ver Mais"
        var verMaisButton = document.createElement('button');
        verMaisButton.textContent = 'Ver Mais';
        verMaisButton.setAttribute('type', 'button');
        verMaisButton.addEventListener('click', function() {
            MostrarMaisResultados();
        });
        
        listaResultados.appendChild(verMaisButton);
        
        // Adicionando botão "Ver Menos"
        var verMenosButton = document.createElement('button');
        verMenosButton.textContent = 'Ver Menos';
        verMenosButton.setAttribute('type', 'button');
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

function updateSelectValue(){
    var select = document.getElementById("servico");
    var outroInput = document.getElementById("servico_outro");

    if(select.value === "outro"){
        outroInput.setAttribute("name","servico");
        select.value = outroInput.value;
    }
}

document.getElementById("meu_formulario").addEventListener("submit",updateSelectValue);