import { Tabela } from '../../lgbtq_connect/includes/admin/admin_script.js';

describe("manipulação da tabela do admin", () => {

    let arr;

    beforeEach(() => {
        // Simulate the DOM elements
        document.body.innerHTML = `
            <form id="editForm">
                <input type="text" id="editId" />
                <input type="text" id="editNome" />
                <input type="text" id="editEmail" />
            </form>
            <table class="wp-list-table widefat striped" id="tabela">
                <thead>
                    <tr>
                        <th class="sort-header">Nome <button class="sort-btn" data-order="asc"><span class="sort-icon">&#9652;</span></button></th>
                        <th class="sort-header">Email <button class="sort-btn sort-by-email" data-order="asc"><span class="sort-icon">&#9652;</span></button></th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Serviço</th>
                        <th>Descrição</th>
                        <th class="sort-header">Data e hora <button class="sort-btn sort-by-date" data-order="asc"><span class="sort-icon">&#9652;</span></button></th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody id="tabela-body"></tbody>
            </table>
        `;

        // Define the test data
        arr = [
            {
                "id": 1,
                "nome": "Cabana",
                "email": "exemplo1@gmail.com",
                "latitude": 50.2,
                "city": "Cidade das boas",
                "road": "Rua das boas",
                "longitude": 20.3,
                "data_hora": "2024-01-01 00:00:00",
                "servico": "entretenimento",
                "descricao": "Bom demais",
                "situacao": "Aprovado"
            }
        ];
    });

    test('a geração de linhas está funcionando', () => {
        const tabela = document.getElementById('tabela');
        const tabelaObj = new Tabela(arr, tabela);
        tabelaObj.gerarLinhas();

        const tbody = document.getElementById("tabela-body");
        
        // Verifica se o nome está correto
        expect(tbody.querySelector("#formulario-1-nome").textContent).toBe("Cabana");

        // Verifica se o email está correto
        expect(tbody.querySelector("#formulario-1-email").textContent).toBe("exemplo1@gmail.com");

        // Verifica se a cidade está correta
        expect(tbody.querySelector("#formulario-1-cidade").textContent).toBe("Cidade das boas");

        // Verifica se a rua está correta
        expect(tbody.querySelector("#formulario-1-rua").textContent).toBe("Rua das boas");

        // Verifica se a data e hora estão corretas
        expect(tbody.querySelector("#formulario-1-data_hora").textContent).toBe("01/01/2024 00:00:00");

        // Verifica se o serviço está correto
        expect(tbody.querySelector("#formulario-1-servico").textContent).toBe("entretenimento");

        // Verifica se a descrição está correta
        expect(tbody.querySelector("#formulario-1-descricao").textContent).toBe("Bom demais");

        // Verifica se a situação está correta
        expect(tbody.querySelector("#formulario-1-situacao").textContent).toBe("Aprovado");
    });

    test('a exclusão de linhas está funcionando', () => {
        const tabela = document.getElementById('tabela');
        const tabelaObj = new Tabela(arr, tabela);
        tabelaObj.gerarLinhas(); // Gera as linhas primeiro
        tabelaObj.excluirLinhas(); // Em seguida, exclui as linhas

        const tbody = document.getElementById("tabela-body");
        expect(tbody.innerHTML).toBe("");
    });
});
