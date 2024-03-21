Este documento oferece uma visão abrangente do backlog de nosso produto, destacando as funcionalidades, melhorias e tarefas prioritárias que nossa equipe de desenvolvimento planeja abordar. Ele atua como um guia essencial para alinhar nossos esforços com as demandas dos clientes e os objetivos empresariais, facilitando uma gestão eficiente do ciclo de desenvolvimento e garantindo a entrega constante de valor aos usuários finais.

## Histórico de Versões

| Data       | Versão | Descrição            |         Autor             |
|:----------:|:------:|:--------------------:|:-------------------------:|
| 10/03/2024 | 0.1.1 | Criação do Documento Backlog com épicos, funcionalidades | @WillxBernardo |
| 13/03/2024 | 0.1.2 | Criação do plugin protótipo que adiciona o formulário na página | @WillxBernardo |
| 20/03/2024 | 0.1.3 | Implementação do uso da localização do usário dentro do plugin | @Max-Rohrer20 |

## Épicos
| ID | DESCRIÇÃO | ID RELACIONADO (TEMA) |
|----|-----------|-----------------------|
| EP01 | Como administrador, quero gerenciar formulários para georreferenciar estabelecimentos/serviços no meu site| TM01 |

## Funcionalidades (Features)
| ID | DESCRIÇÃO | ID RELACIONADO (ÉPICOS) |
|----|-----------|-------------------------|
| FT00 | Estudos gerais sobre o wordpress| --- |
| FT01 | Formulário de pedido | EP01 |
| FT02 | Interface de gerenciamento de formulários | EP01 |
| FT03 | Georreferenciamento dos estabelecimentos/serviços| EP01 |


## User Story

|    ID   |    Eu como    |      Desejo       | De modo que | FEATURES |
|:-------:|:--------------:|:-----------------:|:-----------:|:----------:|
|    US00    | Administrador | Validar um formulário | eu consigo aceitar/recusar os formulários | FT01 |
|    US01    | Administrador | Que o mapa do formulário mostre locais já cadastrados | eu consiga mostrar no mapa quais estabelecimentos estão cadastrados | FT01 |
|    US02    | Admnistrador | Ter um mapa juntamente com o formulário | eu consiga marcar no mapa a localização do estabelecimento | FT01 |
|    US03    | Usuário | Utilizar a minha localização no mapa | Seja mais fácil me situar dentro do mapa presente no formulário | FT01 |
|    US04    | Usuário | Ver as marcações feitas pelo administrador | Eu consiga saber quais estabelecimentos já são conhecidos | FT01 |
|    US05    | Usuário | Ter um mapa juntamente com o formulário | eu consiga marcar no mapa a localização do estabelecimento | FT01 |
|    US06    | Usuário | Poder alterar a marcação dentro do mapa  | Eu não precise estar fisicamente no estabelecimento que eu desejo apresentar no formulário | FT01 |
|    US07    | Administrador | Desejo enviar um e-mail ao usuário ao ele enviar suas respostas do formulário  | O usuário tenha ciência de que suas respostas foram recebidas | FT02
|    US08    | Administrador | Desejo pode ver as respostas do formulário diretamente no painel do WordPress  | Eu não precise entrar toda vez no meu Banco de Dados para ver as respostas | FT02 |
|    TS00   | Dev | Enteder o funcionamento do wordpress e plugins | eu consiga implementar uma arquitetura de um plugin | FT00 |    
|    TS01    | Dev | Enteder sobre as licenças de plugins | eu consiga diferenciar as licenças disponíveis | FT00 |
|    TS02    | Dev | Enteder sobre como utilizar o openstreetmap em JS | eu consiga utilizar as ferramentas da API para criar mapas | FT00 |
|    TS03    | Dev | Desejo que o plugin possa ser utilizado por qualquer site que utilize o WordPress | O plugin possa ser utilizado por outras pessoas e/ou sites que se interessem por essa funcionalidade | FT02