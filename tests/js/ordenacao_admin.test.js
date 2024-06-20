import {Ordenador} from '../../lgbtq_connect/includes/admin/admin_script.js';

describe("ordenador (página do admin)", () => {
    let arr = [];

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

    let formulario2 = {
        "id": 1,
        "nome": "Pizza",
        "email": "exemplo2@gmail.com",
        "latitude": 50.2,
        "longitude": 20.3,
        "data_hora": "2024-01-02 00:00:00",
        "servico": "bar/restaurante",
        "descricao": "Bom demais",
        "situacao": "Aprovado"
    }

    let formulario3 = {
        "id": 1,
        "nome": "Hamburguer",
        "email": "exemplo3@gmail.com",
        "latitude": 50.2,
        "longitude": 20.3,
        "data_hora": "2024-01-01 00:00:01",
        "servico": "bar/restaurante",
        "descricao": "Bom demais",
        "situacao": "Aprovado"
    }

    arr.push(formulario1, formulario2, formulario3);
    test('ordenação por nome está funcionando', () => {
        Ordenador.coluna = "nome";

        Ordenador.ordem = "asc";
        arr = Ordenador.realizarOrdenacao(arr);
        expect(arr[0]).toBe(formulario1);

        Ordenador.ordem = "desc";
        arr = Ordenador.realizarOrdenacao(arr);
        expect(arr[0]).toBe(formulario2);
    })

    test('ordenação por email está funcionando', () => {
        Ordenador.coluna = "email";

        Ordenador.ordem = "asc";
        arr = Ordenador.realizarOrdenacao(arr);
        expect(arr[0]).toBe(formulario1);

        Ordenador.ordem = "desc";
        arr = Ordenador.realizarOrdenacao(arr);
        expect(arr[0]).toBe(formulario3);
    })

    test('ordenação por data e hora está funcionando', () => {
        Ordenador.coluna = "data_hora";

        Ordenador.ordem = "asc";
        arr = Ordenador.realizarOrdenacao(arr);
        expect(arr[0]).toBe(formulario1);

        Ordenador.ordem = "desc";
        arr = Ordenador.realizarOrdenacao(arr);
        expect(arr[0]).toBe(formulario2);
    })
})