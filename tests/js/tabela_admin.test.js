import {Tabela} from '../../lgbtq_connect/includes/admin/admin_script.js';

describe("manipulação da tabela do admin", () => {

    let arr=[];

    let formulario1 = {
        "id": 1,
        "nome": "Cabana",
        "email": "exemplo1@gmail.com",
        "latitude": 50.2,
        "longitude": 20.3,
        "data_hora": "2024-01-01 00:00:00",
        "servico": "entretenimento",
        "descricao": "Bom demais",
        "situacao": "Aprovado"
    }

    arr.push(formulario1);
    
    test('a geração de linhas está funcionando', () => {
        document.body.innerHTML =
        '<table class="wp-list-table widefat striped" id="tabela">' +
        '        <thead>' +
        '                <tr>' +
        '                <th class="sort-header">Nome <button class="sort-btn" data-order="asc"><span class="sort-icon">&#9652;</span></button></th>' +
        '                <th class="sort-header">Email <button class="sort-btn sort-by-email" data-order="asc"><span class="sort-icon">&#9652;</span></button></th>' +
        '                <th>Latitude</th>' +
        '                <th>Longitude</th>' +
        '                <th>Serviço</th>' +
        '               <th>Descrição</th>' +
        '                <th class="sort-header">Data e hora <button class="sort-btn sort-by-date" data-order="asc"><span class="sort-icon">&#9652;</span></button></th>' +
        '                <th>Status</th>' +
        '                <th>Ações</th> ' +
        '                </tr>' +
        '        </thead>' + 
        '        <tbody>';

        const tabela = document.getElementById('tabela');
        const tabelaObj = new Tabela(arr, tabela);
        tabelaObj.gerarLinhas();

        // Verifica se o nome está correto
        expect(document.getElementById("formulario-1-nome").innerHTML).toBe("Cabana");

        // Verifica se o email está correto
        expect(document.getElementById("formulario-1-email").innerHTML).toBe("exemplo1@gmail.com");

        // Verifica se a latitude está correta
        expect(document.getElementById("formulario-1-latitude").innerHTML).toBe("50.2");

        // Verifica se a longitude está correta
        expect(document.getElementById("formulario-1-longitude").innerHTML).toBe("20.3");

        // Verifica se a data e hora está correta
        expect(document.getElementById("formulario-1-data_hora").innerHTML).toBe("01/01/2024 00:00:00");

        // Verifica se o serviço está correto
        expect(document.getElementById("formulario-1-servico").innerHTML).toBe("entretenimento");

        // Verifica se a descrição está correta
        expect(document.getElementById("formulario-1-descricao").innerHTML).toBe("Bom demais");

        // Verifica se a situação está correta
        expect(document.getElementById("formulario-1-situacao").innerHTML).toBe("Aprovado");
    })

    test('a exclusão de linhas está funcionando', () => {
        const tabelaObj = new Tabela(arr, tabela);

        tabelaObj.excluirLinhas();
        expect(tabela.querySelector("tbody").innerHTML).toBe("");
    })
})