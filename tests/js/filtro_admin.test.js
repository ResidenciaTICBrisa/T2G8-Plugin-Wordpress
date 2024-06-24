import {Filtro} from '../../lgbtq_connect/includes/admin/admin_script.js';

describe("filtro (página do admin)", () => {
    let arr = [];
    let arr_filtrada;

    let formulario1 = {
        nome: "Cabana",
        situacao: "Aprovado",
        servico: "entretenimento"
    }
    let formulario2 = {
        nome: "pizza",
        situacao: "Negado",
        servico: "bar/restaurante"  
    }
    let formulario3 = {
        nome: "Escola",
        situacao: "Pendente",
        servico: "ensino"
    }

    arr.push(formulario1, formulario2, formulario3);
    test('filtro por nome está funcionando', () => {
        Filtro.reiniciarFiltro();
        Filtro.nome="Cabana ";
        arr_filtrada = Filtro.realizarFiltragem(arr);
        expect(arr_filtrada).toContain(formulario1);
        expect(arr_filtrada).toHaveLength(1);
    })

    test('filtro por status está funcionando', () => {
        Filtro.reiniciarFiltro();
        Filtro.status="Pendente";
        arr_filtrada = Filtro.realizarFiltragem(arr);
        expect(arr_filtrada).toContain(formulario3);
        expect(arr_filtrada).toHaveLength(1);
    })

    test('filtro por serviço está funcionando', () => {
        Filtro.reiniciarFiltro();
        Filtro.servico="ensino"
        arr_filtrada = Filtro.realizarFiltragem(arr)
        expect(arr_filtrada).toContain(formulario3);
        expect(arr_filtrada).toHaveLength(1);
    })

    test('todos os filtros ao mesmo tempo estão funcionando com valores não-nulos', () => {
        Filtro.reiniciarFiltro();
        Filtro.nome=" Cabana ";
        Filtro.status="Aprovado";
        Filtro.servico="entretenimento"
        arr_filtrada = Filtro.realizarFiltragem(arr)
        expect(arr_filtrada).toContain(formulario1);
        expect(arr_filtrada).toHaveLength(1);
    })

    test('todos os filtros ao mesmo tempo estão funcionando com valores nulos', () => {
        Filtro.reiniciarFiltro();
        arr_filtrada = Filtro.realizarFiltragem(arr)
        expect(arr_filtrada).toHaveLength(3);
    })
})