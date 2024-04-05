# Introdução

## Propósito

Este documento tem como objetivo descrever o sistema de mapeamento de zonas seguras para a comunidade LGBTQI+, que será desenvolvido como um plugin para WordPress. Ele explicará a finalidade e as características do sistema, as interfaces do sistema, suas funcionalidades, as restrições sob as quais deve operar e como o sistema reagirá aos estímulos externos. Este documento destina-se a desenvolvedores e ao cliente que propôs o projeto.

## Escopo do Projeto

O projeto consiste no desenvolvimento de um plugin de rastreamento de áreas seguras (Safe Zones) para a comunidade LGBT no Brasil. O objetivo principal é fornecer uma plataforma segura e inclusiva que permita aos usuários identificar e compartilhar locais seguros em suas comunidades, como bares, restaurantes, centros comunitários e outros estabelecimentos que sejam acolhedores e respeitosos com a comunidade LGBT. O plugin terá uma interface intuitiva e responsiva, permitindo aos usuários navegar facilmente pelo mapa de Safe Zones, adicionar novos locais e avaliar a segurança e inclusão de estabelecimentos existentes. A integração com uma plataforma de rastreamento de áreas seguras permitirá a comunicação em tempo real e o compartilhamento de informações entre os usuários. O projeto será desenvolvido em quatro releases principais, com atividades como definição de objetivos, pesquisa, desenvolvimento do front-end e back-end, integração com a plataforma de rastreamento, testes e correções, lançamento e promoção do plugin, e monitoramento e manutenção inicial após o lançamento. O objetivo final é criar uma ferramenta poderosa e positiva que contribua para a segurança e bem-estar da comunidade LGBT no Brasil.

## Glossário

| Termo     | Descrição                                                                   |
|-----------|-----------------------------------------------------------------------------|
| Safe Zone | Local considerado seguro e inclusivo para a comunidade LGBT.               |
| ADM       | Administrador, responsável por verificar e validar as informações dos estabelecimentos propostos como zonas seguras, gerenciar a base de dados, moderar conteúdo e fornecer suporte aos usuários. |
| SGBD      | Sistema Gerenciador de Banco de Dados, responsável pelo armazenamento, recuperação e manipulação dos dados relacionados às zonas seguras, garantindo a integridade e segurança das informações. |

## Referências

IEEE. IEEE Std 830-1998 IEEE Recommended Practice for Software Requirements Specifications. IEEE Computer Society, 1998.

## Visão Geral do Documento

O próximo capítulo, que é a Descrição Geral, dá uma visão geral de como o produto funciona. Ele fala dos requisitos de forma mais simples e serve para preparar o terreno para a parte técnica dos requisitos que vem no próximo capítulo.

Já o terceiro capítulo, que é a Especificação de Requisitos, foi feito principalmente para os desenvolvedores. Ele entra em detalhes técnicos sobre como o produto vai funcionar. Ambas as partes do documento falam sobre o mesmo produto.

# Descrição Geral

## Ambiente do Sistema

![Ambiente do sistema](/assets/images/Ambiente_do_sistema.drawio.png)

O projeto do plugin de rastreamento de áreas seguras para a comunidade LGBT no Brasil contará com três tipos principais de usuários. O primeiro é o usuário final, que utilizará a plataforma para encontrar zonas seguras em suas comunidades e sugerir novos locais que considerem seguros e inclusivos. Esse usuário pode ser um membro da comunidade LGBT em busca de locais acolhedores ou um aliado procurando apoiar estabelecimentos inclusivos.

O segundo tipo de usuário é o administrador, responsável por verificar e validar as informações dos estabelecimentos propostos como zonas seguras. Além disso, os administradores podem gerenciar a base de dados, moderar conteúdo e fornecer suporte aos usuários.

Por fim, temos o sistema gerenciador de banco de dados, que cuidará do armazenamento, recuperação e manipulação dos dados relacionados às zonas seguras. Ele garantirá a integridade e segurança das informações, garantindo que estejam disponíveis quando necessário.

Esses três tipos de usuários trabalharão em conjunto para garantir que o plugin forneça informações precisas e úteis para a comunidade LGBT no Brasil.

## Especificação de Requisitos Funcionais

Esta seção descreve os casos de uso de forma isolada, apresentando os três principais atores envolvidos no sistema: o usuário final, o administrador (ADM) e o sistema gerenciador de banco de dados.

### Caso de uso do Usuário Final

#### Caso de Uso: Procurando uma safe zone

![Procurando uma safe zone](/assets/images/procurando.drawio.png)

##### Breve Descrição:

O usuário final deseja visitar um local e verificar se ele é considerado uma Safe Zone antes de sua visita.

##### Passos:

1. O usuário acessa o plugin de rastreamento de áreas seguras.
2. O usuário insere o nome ou endereço do local desejado.
3. O sistema pesquisa em seu banco de dados se o local é uma Safe Zone.
4. O sistema exibe a informação de se o local é ou não uma Safe Zone para o usuário.
5. O usuário decide se deseja visitar o local com base na informação fornecida pelo sistema.

#### Caso de Uso: Propor uma Safe Zone

![Propor uma Safe Zone](/assets/images/propondo.drawio.png)

##### Breve Descrição:

O usuário final deseja sugerir um local como uma Safe Zone para inclusão no sistema.

##### Passos:

1. O usuário acessa o plugin de rastreamento de áreas seguras.
2. O usuário seleciona a opção de propor uma Safe Zone.
3. O usuário preenche um formulário com informações sobre o local, como nome, endereço e descrição.
4. O sistema recebe a proposta e a armazena para revisão.
5. Os administradores do sistema revisam a proposta e verificam se o local atende aos critérios para ser considerado uma Safe Zone.
6. O sistema atualiza o banco de dados com a inclusão da nova Safe Zone, se aprovada.

### Caso de Uso do ADM

#### Caso de Uso: Verificar um Local como Safe Zone

![Verificar um Local como Safe Zone](/assets/images/verificando.drawio.png)

##### Breve Descrição:

O administrador deseja verificar se um local proposto atende aos critérios para ser considerado uma Safe Zone.

##### Passos:

1. O administrador acessa o sistema de administração do plugin.
2. O administrador visualiza a lista de Safe Zones existentes.
3. O administrador seleciona o local que deseja excluir.
4. O administrador confirma a exclusão do local como Safe Zone.
5. O sistema remove o local da lista de Safe Zones.

### Características dos Usuários

**Administrador (ADM):**
- Habilidades técnicas para gerenciar e verificar locais propostos como Safe Zones.
- Habilidades de comunicação para lidar com usuários e administradores.

**Usuário Final:**
- Deve possuir habilidades básicas de navegação na internet.
- Capacidade de usar o plugin de forma intuitiva.
- Capaz de encontrar e sugerir locais como Safe Zones, além de avaliar e comentar sobre locais existentes.

### Requisitos Não Funcionais

- **Usabilidade:** O plugin deve ser fácil de usar e intuitivo para o usuário final, com uma interface amigável e instruções claras.
- **Desempenho:** O sistema deve ser capaz de lidar com um grande volume de dados e usuários simultâneos sem comprometer a velocidade ou a qualidade do serviço.
- **Segurança:** O plugin deve garantir a segurança dos dados dos usuários e a integridade das informações, utilizando criptografia e práticas de segurança recomendadas.
- **Compatibilidade:** O plugin deve ser compatível com diferentes navegadores web e sistemas operacionais, garantindo uma experiência consistente para todos os usuários.
- **Manutenibilidade:** O código do plugin deve ser bem estruturado e documentado, facilitando futuras atualizações e manutenções.
- **Escalabilidade:** O sistema deve ser capaz de se adaptar e escalar conforme necessário, para lidar com um aumento no número de usuários e locais cadastrados.
- **Disponibilidade:** O plugin deve estar disponível e acessível para os usuários a maior parte do tempo, com um tempo de inatividade mínimo planejado para manutenção.
- **Localização:** O plugin deve ser localizável, permitindo a tradução da interface para diferentes idiomas e a adaptação a diferentes culturas e regiões.
- **Privacidade:** O plugin deve respeitar a privacidade dos usuários, garantindo que suas informações pessoais sejam protegidas e utilizadas apenas para os fins específicos do plugin.
- **Acessibilidade:** O plugin deve ser acessível para usuários com deficiências, seguindo as diretrizes de acessibilidade web e garantindo uma experiência inclusiva para todos.

## Especificação de Requisitos

### Requisitos de Interface Externa

#### Integração com Mapas:

- O plugin deve se integrar com serviços de mapas, com o OpenStreetMap, para exibir visualmente as Safe Zones e permitir a navegação pelos mapas.
- A integração com mapas deve ser intuitiva e responsiva, permitindo aos usuários interagir facilmente com as Safe Zones no mapa.

#### Formulário de Proposição de Safe Zone:

- O plugin deve fornecer um formulário na interface para que os usuários possam propor novas Safe Zones, inserindo informações como nome, endereço e descrição do local.
- O formulário deve ser fácil de usar e enviar as informações para o sistema de gerenciamento de banco de dados.

#### Avaliação:

- Os usuários devem poder avaliar as Safe Zones existentes através da interface do plugin.
- As avaliações devem ser exibidos de forma clara e organizada na interface para que outros usuários possam ver.

#### Notificações:

- O plugin deve ser capaz de enviar notificações aos usuários sobre novas Safe Zones propostas, alterações no status de uma Safe Zone ou outras informações relevantes.
- As notificações devem ser visíveis e facilmente acessíveis para os usuários.

#### Compatibilidade com Dispositivos Móveis:

- O plugin deve ser compatível com dispositivos móveis, garantindo uma experiência de uso consistente em smartphones e tablets.
- A interface do plugin deve ser responsiva e adaptável a diferentes tamanhos de tela.

### Requisitos Funcionais

#### Visualização de Sugestões de Locais

Como administrador, eu quero poder visualizar todas as sugestões de locais enviadas pelos usuários para verificação.

#### Aprovação/Rejeição de Sugestões de Locais

Como administrador, eu quero poder aprovar ou rejeitar sugestões de locais enviadas pelos usuários, marcando-as como "Aprovado" ou "Rejeitado", respectivamente.

#### Busca por Zonas Seguras

Como usuário, eu quero poder buscar por zonas seguras proximas à minha localização atual para encontrar locais seguros  na minha região.

#### Visualização de Detalhes de Zonas Seguras

Como usuário, eu quero poder visualizar detalhes de uma zona segura, como o nome endereço, descrição e classificação, pra decidir se é um locar adequado para mim.

#### Acompanhamento do Status da região

Como usuário, eu quero receber um e-mail de confimação do envio do formulário.
