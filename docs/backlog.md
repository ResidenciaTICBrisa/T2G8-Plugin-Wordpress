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
| 12/03/2024 | 0.1.0 | Criação do plugin protótipo (Plugin teste) | @WillxBernardo |
| 14/03/2024 | 0.2.0 | Funcionalidade de criação da tabela no BD | @WillxBernardo |
| 18/03/2024 | 0.3.0 | Adicionando formulário a tela do plugin | @WillxBernardo |
| 18/03/2024 | 0.4.0 | Adicionando mapa no formulário (Overleaf)| @WillxBernardo |
| 20/03/2024 | 0.4.0 | Mapa sendo iniciado na localização do usuário| @Max-Rohrer20 |
| 26/03/2024 | 0.4.0 | Adição de interface de administração no painel WordPress| @Max-Rohrer20 |
| 26/03/2024 | 0.5.0 | Adição da tabela com os formulários na interface | @Max-Rohrer20 |
| 29/03/2024 | 0.5.1 | Bugfix: Login no wordpress| @WillxBernardo |
| 02/04/2024 | 0.5.2 | Bugfix: Erro ao adicionar o admin menu (Interface)| @WillxBernardo |
| 02/04/2024 | 0.5.3 | Bugfix: Erro ao se conectar ao banco de dados do wordpress| @WillxBernardo |
| 02/04/2024 | 0.5.4 | Bugfix: Erro ao  excluir formulários na interface| @WillxBernardo |
| 02/04/2024 | 0.5.5 | Bugfix: Redirecionamento após o envio de formulário| @WillxBernardo |
| 07/04/2024 | 0.5.6 | Bugfix: Adição dos formulários enviados no Banco de dados | @WillxBernardo |
| 09/04/2024 | 0.6.0 | Adição de novos campos no formulário | @WillxBernardo |
| 10/04/2024 | 0.7.0 | Adicionando funcoes de aprovar e rejeitar na interface | @WillxBernardo |
| 11/04/2024 | 0.8.0 | Adicionando funcionalidades de navegação via botão | @MarcosViniciusG |
| 11/04/2024 | 0.9.0 | Adição das tabelas de Formulários Aprovados e Negados | @Max-Rohrer20 |
| 11/04/2024 | 0.10.0 | Funcionalidades nos botões de aprovar e negar | @Max-Rohrer20 |
| 11/04/2024 | 0.10.1 | Bugfix: Corrigindo formatação das tabelas do painel de administração| @Max-Rohrer20 |
| 12/04/2024 | 0.11.0 | Botões de validação na interface do admin| @WillxBernardo |
| 13/04/2024 | 0.12.0 | Botão ver mais do campo descrição da interface| @Punkrig |
| 13/04/2024 | 0.12.1 | Bugfix: Envio de formulário sem localização| @Punkrig |
| 14/04/2024 | 0.13.0 | Plotagem dos formulários em status aprovado nos mapas| @WillxBernardo |
| 16/04/2024 | 0.14.0 | Botões de ordenação em ordem alfabética no campo nome| @Max-Rohrer20 |
| 16/04/2024 | 0.15.0 | Popups nos marcadores dos mapas| @WillxBernardo |
| 16/04/2024 | 0.16.0 | Mapa na interface do administrador | @MarcosViniciusG |
| 17/04/2024 | 0.17.0 | Tipo de serviço 'outro' no formulário| @guslnhm |
| 18/04/2024 | 0.18.0 | Destaque na linha da tabela após clique em marcador| @MarcosViniciusG |
| 18/04/2024 | 0.19.0 | Popups contendo informações sobre o local| @Max-Rohrer20 |
| 18/04/2024 | 0.20.0 | Botão ver menos no campo de descrição da tabela| @Punkrig |
| 19/04/2024 | 0.20.1 | Bugfix: Armazenamento dos formulários com o tipo de serviço 'outro'| @guslnhm |
| 19/04/2024 | 0.21.0 | Mecanismo de busca nos mapas| @WillxBernardo |
| 23/04/2024 | 0.21.1 | Bugfix: Formulários com o tipo de serviço predefinidos| @guslnhm |
| 23/04/2024 | 0.22.0 | Ordenação dos campos de e-mail e data e hora da interface do admin| @Max-Rohrer20 |
| 23/04/2024 | 0.22.1 | Bugfix: Ordenação do campo de data e hora| @Max-Rohrer20 |
| 23/04/2024 | 0.22.1 | Bugfix: duplicacao dos resultados nos campos de busca dos mapas| @WillxBernardo |
| 23/04/2024 | 0.22.2 | Bugfix: Campo vazio no formulario | @WillxBernardo |
| 01/05/2024 | 0.23.0 | Adicionando resultados limitados a quantidade e botao ver mais | @WillxBernardo |
| 02/05/2024 | 0.23.1 | Bugfix: interface recarrega e não mostra mais o marcador excluido| @MarcosViniciusG |
| 02/05/2024 | 0.24.0 | Funcionalidade de ver mais e ver menos resultados | @WillxBernardo |
| 09/05/2024 | 0.25.0 | Envio de e-mail para o administrador do site | @Max-Rohrer20 |
| 10/05/2024 | 0.26.0 | Filtro por status | @MarcosViniciusG |
| 16/05/2024 | 0.27.0 | Filtro por nome | @MarcosViniciusG |
| 16/05/2024 | 0.28.0 | Filtro por serviço | @MarcosViniciusG |
| 16/05/2024 | 0.28.0 | Filtro por serviço | @MarcosViniciusG |
| 16/05/2024 | 0.29.0 | Envio de e-mail para as mudanças de status dos formulários | @Max-Rohrer20  |
| 16/05/2024 | 0.30.0 | Contador de resultados na interface do administrador | @MarcosViniciusG  |
| 16/05/2024 | 0.31.0 | Notificação para acões do administrador | @Max-Rohrer20  |
| 17/05/2024 | 0.31.1 |Bugfix: Botão de confirmação não funciona para todos os formulários|@MarcosViniciusG |
| 22/05/2024 | 0.31.2 | Bugfix: Criação do BD ao ativar o plugin | @WillxBernardo |
