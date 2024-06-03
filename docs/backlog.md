---
hide:
    - navigation
---

# **Backlog**

Este documento oferece uma visão abrangente do backlog de nosso produto, destacando as funcionalidades, melhorias e tarefas prioritárias que nossa equipe de desenvolvimento planeja abordar. Ele atua como um guia essencial para alinhar nossos esforços com as demandas dos clientes e os objetivos empresariais, facilitando uma gestão eficiente do ciclo de desenvolvimento e garantindo a entrega constante de valor aos usuários finais.


## Épicos
| ID | DESCRIÇÃO |
|----|-----------|
| EP01 | Como administrador, quero georreferenciar locais/serviços no meu site|
| EP02 | Como administrador, desejo administrar os locais/serviços do meu site e gerenciar pedidos de adição no sistema via interface|

## Funcionalidades (Features)
| ID | DESCRIÇÃO | ID RELACIONADO (ÉPICOS) |
|----|-----------|-------------------------|
| FT00 | Estudos gerais sobre o wordpress| --- |
| FT01 | Formulário de pedido | EP01 |
| FT02 | Interface de gerenciamento de formulários | EP02 |
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
|    US11    | Administrador | Editar as informações de uma zona segura existente, como o nome, endereço e descrição |  para manter as informações atualizadas | FT02 |
|    US12    | Administrador | Personalizar os campos do formulário |  Eu receba as informações pertinentes do local que eu considere relevantes | FT02 |
|    US13    | Administrador | Poder adicionar um mapa na página | Seja exibido no mapa quais estabelecimentos já estão cadastrados | FT03 |
|    US14    | Usuário | visualizar detalhes de uma zona segura, como: nome, endereço, descrição e classificação | decidir se é um local adequado para mim | FT03 |
|    US15    | Administrador | Um mapa na interface do administrador | Veja visualmente todos os formulários já aprovados | FT02 |
|    US16    | Administrador | Poder ter uma maior interação entre o mapa da interface do administrador e os formulários | Possa acessar o formulário através do marcador no mapa | FT02 |
|    US17    | Usuário | Todos os locais aprovados estejam marcados no mapa | Possa ter uma noção de locais seguros perto de mim | FT01 |
|    US18    | Administrador | Excluir os formulários | Limpar o banco de dados de formulários indesejados ou nocivos | FT02 |
|    US19    | Administrador | Os formulários sejam divididos em diferentes tabelas de acordo com o seu status | Tenha uma divisão clara entre os formulários aprovados, negados e pendentes | FT02 |
|    US20    | Administrador | Implementar ferramentas de ordenação da interface do administrador | A tabela contendo os formulários esteja organizada de acordo com minha vontade | FT02 |

## Requisitos

### Requisitos Funcionais

- **Integração com Mapas:** O plugin deve se integrar com serviços de mapas, com o OpenStreetMap, para exibir visualmente as Safe Zones e permitir a navegação pelos mapas.

- **Formulário de Proposição de Safe Zone:** O plugin deve fornecer um formulário na interface para que os usuários possam propor novas Safe Zones, inserindo informações como nome, endereço e descrição do local.

- **Avaliação:** Os usuários devem poder avaliar as Safe Zones existentes através da interface do plugin. As avaliações devem ser exibidos de forma clara e organizada na interface para que outros usuários possam ver.

- **Visualização de Sugestões de Locais:** O sistema deve fornecer uma interface para os administradores visualizarem todas as sugestões de locais enviadas pelos usuários para verificação.

- **Aprovação/Rejeição de Sugestões de Locais:** Os administradores devem ter a capacidade de aprovar ou rejeitar sugestões de locais enviadas pelos usuários, atribuindo o status de "Aprovado" ou "Rejeitado" a cada uma delas.

- **Busca por Zonas Seguras:** O sistema deve permitir aos usuários realizar buscas por zonas seguras próximas à sua localização atual para encontrar locais seguros na região desejada.

- **Visualização de Detalhes de Zonas Seguras:** Os usuários devem ser capazes de visualizar detalhes de uma zona segura, incluindo nome, endereço, descrição e classificação, para avaliar se é um local adequado para eles.

- **Acompanhamento do Status da Região:** O sistema deve enviar um e-mail de confirmação para os usuários após o envio do formulário, para que possam acompanhar o status da região que sugeriram.


### Requisitos Não Funcionais

- **Usabilidade:** O plugin deve ser fácil de usar e intuitivo para o usuário final, com uma interface amigável e instruções claras.

- **Desempenho:** O sistema deve ser capaz de lidar com um grande volume de dados e usuários simultâneos sem comprometer a velocidade ou a qualidade do serviço.

- **Segurança:** O plugin deve garantir a segurança dos dados dos usuários e a integridade das informações, utilizando criptografia e práticas de segurança recomendadas.

- **Compatibilidade:** O plugin deve ser compatível com diferentes navegadores web, sistemas operacionais e  com dispositivos móveis, garantindo uma experiência consistente para todos os usuários.

- **Manutenibilidade:** O código do plugin deve ser bem estruturado e documentado, facilitando futuras atualizações e manutenções.

- **Escalabilidade:** O sistema deve ser capaz de se adaptar e escalar conforme necessário, para lidar com um aumento no número de usuários e locais cadastrados.

- **Disponibilidade:** O plugin deve estar disponível e acessível para os usuários a maior parte do tempo, com um tempo de inatividade mínimo planejado para manutenção.

- **Privacidade:** O plugin deve respeitar a privacidade dos usuários, garantindo que suas informações pessoais sejam protegidas e utilizadas apenas para os fins específicos do plugin.

- **Acessibilidade:** O plugin deve ser acessível para usuários com deficiências, seguindo as diretrizes de acessibilidade web e garantindo uma experiência inclusiva para todos.


## Histórico de Versões

| Data       | Versão | Descrição            |         Autor             |
|:----------:|:------:|:--------------------:|:-------------------------:|
| 10/03/2024 | 0.1.1 | Criação do Documento Backlog com épicos, funcionalidades | @WillxBernardo |
| 13/03/2024 | 0.1.2 | Criação do plugin protótipo que adiciona o formulário na página | @WillxBernardo |
| 20/03/2024 | 0.1.3 | Implementação do uso da localização do usário dentro do plugin | @Max-Rohrer20 |
| 28/03/2024 | 0.2.0 | Inicialização do plugin ser o mapa com acesso para o formulário | @WillxBernardo |
| 05/04/2024 | 0.3.0 | Reestruturação do código base | @WillxBernardo |