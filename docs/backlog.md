Este documento oferece uma visão abrangente do backlog de nosso produto, destacando as funcionalidades, melhorias e tarefas prioritárias que nossa equipe de desenvolvimento planeja abordar. Ele atua como um guia essencial para alinhar nossos esforços com as demandas dos clientes e os objetivos empresariais, facilitando uma gestão eficiente do ciclo de desenvolvimento e garantindo a entrega constante de valor aos usuários finais.

## Histórico de Versões

| Data       | Versão | Descrição            |         Autor             |
|:----------:|:------:|:--------------------:|:-------------------------:|
| 10/03/2024 | 0.1.1 | Criação do Documento Backlog com épicos, funcionalidades | @WillxBernardo |
| 13/03/2024 | 0.1.2 | Criação do plugin protótipo que adiciona o formulário na página | @WillxBernardo |
| 20/03/2024 | 0.1.3 | Implementação do uso da localização do usário dentro do plugin | @Max-Rohrer20 |
| 28/03/2024 | 0.2.0 | Inicialização do plugin ser o mapa com acesso para o formulário | @WillxBernardo |

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
|    TS00   | Dev | Entender o funcionamento do wordpress e plugins | Eu consiga implementar uma arquitetura de um plugin | FT00 |    
|    TS01    | Dev | Entender sobre como utilizar o openstreetmap em JS | Eu consiga utilizar as ferramentas da API para criar mapas | FT00 |
|    TS02    | Dev | Que o plugin possa ser utilizado por qualquer site que utilize o WordPress | O plugin possa ser utilizado por outras pessoas e/ou sites que se interessem por essa funcionalidade | FT00 |
|    TS03    | Dev | Entender sobre a comunicação e manipulação do banco de dados do admin | O armazenamento dos formulários funcione corretamente | FT00 |
|    TS04    | Dev | Entender sobre as funcionalidades que o WordPress oferece | Eu consiga estruturar de forma correta o código do plugin | FT00 |
|    US00    | Administrador | Adicionar um formulário em qualquer página do meu site  | Os usuários possam enviar os estabelecimentos/serviços | FT01 |
|    US01   | Usuário | Enviar um formulário | Seja enviado informações relevantes sobre os locais amigáveis a comunidade LGBTQ+ | FT01 |
|    US02    | Usuário | Ter um mapa juntamente com o formulário | Eu consiga marcar no mapa a localização do estabelecimento | FT01 |
|    US03    | Usuário | Iniciar o mapa do formulário com minha localização | Seja mais fácil me situar dentro do mapa | FT01 |
|    US04    | Usuário | Buscar a minha localização por meio de texto | Facilite o manuseio do mapa | FT01 |
|    US05    | Usuário | Poder alterar a marcação dentro do mapa  | Eu não precise estar fisicamente no estabelecimento que eu desejo apresentar no formulário | FT01 |
|    US06    | Administrador | Enviar um e-mail ao usuário ao ele enviar suas respostas do formulário  | O usuário tenha ciência de que suas respostas foram recebidas | FT01 |
|    US07    | Administrador | Filtrar formulários enviados com conteúdo indesejado  |O banco de dados seja o mais preservado | FT01 |
|    US08    | Administrador | Validar os formulários enviados | Consiga aceitar/rejeitar os formulários | FT02 |
|    US09    | Administrador | Eu acesse as respostas do formulário diretamente no painel do WordPress | Não seja necessário acessar toda vez o meu banco de dados | FT02 |
|    US10    | Administrador | Enviar um e-mail ao usuário quando sua solicitação for respondida | O usuário tenha ciência do resultado do processamento | FT02 |
|    US11    | Administrador |  editar as informações de uma zona segura existente, como o nome, endereço e descrição |  para manter as informações atualizadas | FT02 |
|    US12    | Administrador | Poder adicionar um mapa na página | Seja exibido no mapa quais estabelecimentos já estão cadastrados | FT03 |
|    US13    | Usuário | poder visualizar detalhes de uma zona segura, como o nome, endereço, descrição e classificação | decidir se é um local adequado para mim | FT03 |
|    US14    | Usuário | Acompanhar o Status da região|  Receber um e-mail de confirmação de envio do formulário | FT03 |
|    US15    | Usuário |Buscar Por Zonas Seguras | Poder buscar por zonas seguras proximas à minha localização atual | FT03 |
|    US16    | Usuário | Baixar o plugin | Poder instalá-lo e ultilizá-lo em uma plataforma wordpress | FT03 |