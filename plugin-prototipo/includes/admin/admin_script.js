function mostrarDescricaoCompleta(id) {
    var descricaoResumida = document.getElementById('descricaoResumida_' + id);
    var descricaoCompleta = document.getElementById('descricaoCompleta_' + id);
    var botao = document.querySelector('button[data-id="' + id + '"]');

    if (descricaoResumida.style.display === 'none') {
        descricaoResumida.style.display = 'inline';
        descricaoCompleta.style.display = 'none';
        botao.innerText = 'Ver mais';
    } else {
        descricaoResumida.style.display = 'none';
        descricaoCompleta.style.display = 'inline';
        botao.innerText = 'Ver menos';
    }
}
function destacarLinhaTabela(id) {
    var tabela = document.getElementById('tabela-Aprovado');
    var linha = document.getElementById(id);

    // Loop para remover a linha-destacada de todas as linhas
    for (var i = 0, row; (row = tabela.rows[i]); i++) {
        row.classList.remove('linha-destacada');
    }

    linha.classList.add('linha-destacada'); // Adiciona a classe 'linha-destacada'
    linha.scrollIntoView({ behavior: 'smooth' }); // Rola a página para a linha

    // Remove a classe linha-destacada depois de um determinado tempo
    setTimeout(function () {
        linha.classList.remove('linha-destacada');
    }, 2000);
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
        L.marker([formulario.latitude, formulario.longitude]).addTo(mapAdmin).on('click', function() {

            destacarLinhaTabela(formulario.id);
        });
    });
}

function initSortButtons() {
    // Adiciona um evento de clique aos botões de ordenação
    var sortButtons = document.querySelectorAll('.sort-btn');

    sortButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var table = button.closest('table');
            var columnIndex = Array.from(button.parentNode.parentNode.children).indexOf(button.parentNode);
            var order = button.getAttribute('data-order') || 'asc';

            order = (order === 'asc') ? 'desc' : 'asc';
            button.setAttribute('data-order', order);

            // Atualiza o ícone do botão de ordenação
            var icon = button.querySelector('.sort-icon');

            // Remove e adiciona a classe do ícone com base na direção da ordenação
            if (order === 'asc') {
                icon.innerHTML = '&#9660;'; // Triângulo para baixo (ordem crescente)
            } else {
                icon.innerHTML = '&#9650;'; // Triângulo para cima (ordem decrescente)
            }

            // Obtém todas as linhas da tabela, exceto a primeira (cabeçalho)
            var rows = Array.from(table.querySelectorAll('tbody > tr'));

            // Determina o critério de ordenação com base na classe do botão
            if (button.classList.contains('sort-by-date')) {
                rows.sort(function(a, b) {
                    var aValue = new Date(a.children[columnIndex].textContent.trim().replace(/(\d{2})\/(\d{2})\/(\d{4}) (\d{2}):(\d{2}):(\d{2})/, '$3-$2-$1T$4:$5:$6'));
                    var bValue = new Date(b.children[columnIndex].textContent.trim().replace(/(\d{2})\/(\d{2})\/(\d{4}) (\d{2}):(\d{2}):(\d{2})/, '$3-$2-$1T$4:$5:$6'));

                    return (order === 'asc') ? aValue - bValue : bValue - aValue;
                });
            } else if (button.classList.contains('sort-by-email')) {
                rows.sort(function(a, b) {
                    var aValue = a.children[columnIndex].textContent.trim().toLowerCase();
                    var bValue = b.children[columnIndex].textContent.trim().toLowerCase();

                    return (order === 'asc') ? aValue.localeCompare(bValue) : bValue.localeCompare(aValue);
                });
            } else {
                // Caso padrão: ordenação por texto
                rows.sort(function(a, b) {
                    var aValue = a.children[columnIndex].textContent.trim().toLowerCase();
                    var bValue = b.children[columnIndex].textContent.trim().toLowerCase();

                    return (order === 'asc') ? aValue.localeCompare(bValue) : bValue.localeCompare(aValue);
                });
            }

            // Limpa o conteúdo da tabela antes de reordenar
            while (table.querySelector('tbody').firstChild) {
                table.querySelector('tbody').removeChild(table.querySelector('tbody').firstChild);
            }

            // Reinsere as linhas ordenadas na tabela
            rows.forEach(function(row) {
                table.querySelector('tbody').appendChild(row);
            });
        });
    });
}

// Adiciona um evento de clique a todos os botões de "Ver mais/menos"
document.querySelectorAll('.ver-mais-btn').forEach(function(button) {
    button.addEventListener('click', function() {
        var id = button.getAttribute('data-id');
        mostrarDescricaoCompleta(id);
    });
});

// Inicializa o mapa e os botões de ordenação quando a página carrega
window.addEventListener('load', function() {
    initMapAdmin();
    initSortButtons();
});
