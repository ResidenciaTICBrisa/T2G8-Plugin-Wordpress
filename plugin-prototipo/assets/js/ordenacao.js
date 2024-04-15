jQuery(document).ready(function($) {
    // Adiciona um evento de clique aos botões de ordenação
    $('.sort-btn').on('click', function() {
        var $table = $(this).closest('table');  // Localiza a tabela mais próxima
        var columnIndex = $(this).parent().index();  // Obtém o índice da coluna
        var order = $(this).data('order') || 'asc';  // Obtém a ordem atual ou define como 'asc' por padrão

        // Alterna a ordem entre 'asc' e 'desc' ao clicar
        order = (order === 'asc') ? 'desc' : 'asc';
        $(this).data('order', order);  // Atualiza a ordem no botão

        // Remove a classe de ordenação de outras colunas
        $table.find('.sort-btn').removeClass('asc desc');

        // Adiciona a classe de ordenação na coluna atual
        $(this).addClass(order === 'asc' ? 'asc' : 'desc');

        // Obtém todas as linhas da tabela, exceto a primeira (cabeçalho)
        var rows = $table.find('tbody > tr').get();

        // Ordena as linhas com base no conteúdo da coluna clicada
        rows.sort(function(a, b) {
            var aValue = $(a).find('td').eq(columnIndex).text().toLowerCase();
            var bValue = $(b).find('td').eq(columnIndex).text().toLowerCase();

            if (order === 'asc') {
                return aValue.localeCompare(bValue);  // Ordem crescente
            } else {
                return bValue.localeCompare(aValue);  // Ordem decrescente
            }
        });

        // Reinsere as linhas ordenadas na tabela
        $table.find('tbody').empty().append(rows);
        console.log('Botão de ordenação clicado!'); // Mensagem de console.log()
    });
});
