# Decisão Arquitetônica - Divisão da Arquitetura em Frontend e Backend

<p align="center">
  <img src="https://github.com/ResidenciaTICBrisa/T2G8-Plugin-Wordpress/blob/main/docs/assets/images/architecture.png" width="190" />
</p>

## Problema
A necessidade de organizar a arquitetura do plugin de forma a separar claramente as responsabilidades entre a camada de apresentação (frontend) e a camada de lógica e dados (backend).

## Decisão
Dividir a arquitetura do plugin em frontend e backend, utilizando PHP como lógica de negócios no backend devido à necessidade do WordPress e HTML, CSS e JavaScript para o frontend.

## Status
Decidida

## Suposições
- A equipe possui conhecimento e habilidades para implementar e manter a arquitetura proposta.

## Restrições
- O frontend e o backend devem se comunicar de forma eficiente e segura.
- O uso de PHP e SQL para backend está em conformidade com os padrões tecnológicos aceitos pela organização.

## Argumento
A divisão da arquitetura permite uma melhor organização do código, facilitando a manutenção e a evolução do plugin. O uso de PHP e SQL é uma escolha sólida para garantir a eficiência e a segurança das operações do plugin.

## Requisitos Relacionados
- Requisitos de desempenho para garantir que a comunicação entre os elementos seja rápida e eficiente.
- Requisitos de segurança para proteger os dados transmitidos entre o frontend e backend.

## Princípios Relacionados
- Princípios de separação de responsabilidades para garantir que cada camada da arquitetura tenha uma função clara e específica.

