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
    
    function initMapAdmin() {
        if (document.getElementById('mapa_admin') == null) {
            return;
        }

        var mapAdmin = L.map('mapa_admin', { doubleClickZoom: false }).setView([-15.8267, -47.9218], 13);

        // Adiciona o provedor de mapa OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        }).addTo(mapAdmin);

        formularios_aprovados.forEach(function (formulario) {
            L.marker([formulario.latitude, formulario.longitude])
                .addTo(mapAdmin)
                .on('click', function () {
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
    
                rows.sort(function(a, b) {
                    var aValue = a.children[columnIndex].textContent.trim().toLowerCase();
                    var bValue = b.children[columnIndex].textContent.trim().toLowerCase();
    
                    if (order === 'asc') {
                        return aValue.localeCompare(bValue);  // Ordem crescente
                    } else {
                        return bValue.localeCompare(aValue);  // Ordem decrescente
                    }
                });
    
                // Reinsere as linhas ordenadas na tabela
                rows.forEach(function(row) {
                    table.querySelector('tbody').appendChild(row);
                });
            });
        });
    }
