import { Filtro } from '../../lgbtq_connect/includes/admin/admin_script.js';

describe("filtro (página do admin)", () => {
    let arr;
    let arr_filtrada;

    const formulario1 = {
        nome: "Cabana",
        situacao: "Aprovado",
        servico: "entretenimento"
    };
    const formulario2 = {
        nome: "pizza",
        situacao: "Negado",
        servico: "bar/restaurante"
    };
    const formulario3 = {
        nome: "Escola",
        situacao: "Pendente",
        servico: "ensino"
    };

    beforeEach(() => {
        // Reset the array and filters before each test
        arr = [formulario1, formulario2, formulario3];
        Filtro.reiniciarFiltro();

        // Simulate the DOM elements
        document.body.innerHTML = `
            <form id="editForm">
                <input type="text" id="editId" />
                <input type="text" id="editNome" />
                <input type="text" id="editEmail" />
            </form>
        `;
    });

    test('filtro por nome está funcionando', () => {
        Filtro.nome = "Cabana ";
        arr_filtrada = Filtro.realizarFiltragem(arr);
        expect(arr_filtrada).toContain(formulario1);
        expect(arr_filtrada).toHaveLength(1);
    });

    test('filtro por status está funcionando', () => {
        Filtro.status = "Pendente";
        arr_filtrada = Filtro.realizarFiltragem(arr);
        expect(arr_filtrada).toContain(formulario3);
        expect(arr_filtrada).toHaveLength(1);
    });

    test('filtro por serviço está funcionando', () => {
        Filtro.servico = "ensino";
        arr_filtrada = Filtro.realizarFiltragem(arr);
        expect(arr_filtrada).toContain(formulario3);
        expect(arr_filtrada).toHaveLength(1);
    });

    test('todos os filtros ao mesmo tempo estão funcionando com valores não-nulos', () => {
        Filtro.nome = "Cabana ";
        Filtro.status = "Aprovado";
        Filtro.servico = "entretenimento";
        arr_filtrada = Filtro.realizarFiltragem(arr);
        expect(arr_filtrada).toContain(formulario1);
        expect(arr_filtrada).toHaveLength(1);
    });

    test('todos os filtros ao mesmo tempo estão funcionando com valores nulos', () => {
        arr_filtrada = Filtro.realizarFiltragem(arr);
        expect(arr_filtrada).toHaveLength(3);
    });
});
