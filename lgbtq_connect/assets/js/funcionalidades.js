class Pesquisador {
    constructor(listaId) {
        this.listaId = listaId;
        this.flag = false;
    }

    pesquisarLocalizacoes(query) {
        if(this.flag) {
            return;
        }

        this.flag = true;

        let resultados = [];
        const self = this;
        let apiUrl = 'https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(query);
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
                self.imprimirResultados(resultados);
            
                self.flag = false; // Marca a busca no formulário como concluída
            })
            .catch(error => {
                console.error('Erro ao buscar locais:', error);
            
                self.flag = false; // Marca a busca no formulário como concluída
            });
    }

    imprimirResultados(resultados) {
        let listaResultadosOcultados = [];
        const listaResultados = document.getElementById(this.listaId);
        listaResultados.innerHTML = '';
        let count = 0;
        const div = document.createElement('div');
        resultados.forEach(resultado => {
            const divResultado = document.createElement('div');
            divResultado.classList.add('celula_resultado');
            divResultado.style.borderRadius = '3px';
            divResultado.style.margin = '5px 5px 5px 0px';
            divResultado.style.cursor = 'pointer';
            divResultado.innerHTML = '<img src="https://i.imgur.com/4ZnmAxk.png" width="20px" height="20px">' + resultado.display_name;
            divResultado.addEventListener('click', function () {
                if (pagina) {
                    pagina.mapa.mudarLocalizacao([resultado.lat, resultado.lon]);
                }
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
            const verMaisButton = document.createElement('button');
            verMaisButton.textContent = 'Ver Mais';
            verMaisButton.setAttribute('type', 'button');
            verMaisButton.setAttribute('class', 'ver');
            verMaisButton.addEventListener('click', function() {
                MostrarMaisResultados();
            });
        
            listaResultados.appendChild(verMaisButton);
        
            // Adicionando botão "Ver Menos"
            const verMenosButton = document.createElement('button');
            verMenosButton.textContent = 'Ver Menos';
            verMenosButton.setAttribute('type', 'button');
            verMenosButton.setAttribute('class', 'ver');
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
}

function pesquisar(id, listaId) {
    const el = document.getElementById(id);
    let query = el.value;
    const pesquisador = new Pesquisador(listaId)
    pesquisador.pesquisarLocalizacoes(query);
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