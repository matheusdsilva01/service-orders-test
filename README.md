# Ordens de serviço
Este projeto é um sistema de gerenciamento de ordens de serviço, desenvolvido seguindo definições BDD de um teste técnico.

## O que esse projeto faz?
- Usuários podem acessar a plataforma para gerenciamento de ordens de serviço.
- Disparos de email quando serviços são finalizados.
- Visualização do total de valores dos serviços do usuário e serviços pendentes.
- Mensagens flash.

## Características técnicas
- Container de injeção de dependências(Pode ser melhorado para resolução recursiva de dependências semelhante de como funciona no laravel).
- Sistema de rotas simples.
- Arquitetura MVC.
- Configurações por variáveis de ambiente.
- Middlewares de acesso.

## Requisitos
- PHP 8.4+ com extensão `pdo_mysql`
- MySQL
- Mailpit - Ferramenta para envio de emails que funciona localmente, sem enviar email de verdade.

## Como rodar o projeto
Copie o arquivo `.env.example` para `.env` e configure as variáveis de ambiente.
```shell
cp .env.example .env
```

Inicie o MySQL e crie o banco de dados conforme as [variáveis de ambiente](.env.example) isso é necessário para que os comandos de seed e criação de tabelas funcionem corretamente.
```shell
mysql -u root -p
```
```sql
CREATE DATABASE service_orders;
USE service_orders;
```

Antes de executar o projeto, execute os scripts de criação das tabelas e seed do banco de dados:
```shell
php .\database\create-tables.php
php .\database\seed.php
```

Inicie o serviço do Mailpit:

O Mailpit não é obrigatório nem impeditivo para observar as demais funcionalidades do sistema,
aparecerá apenas uma mensagem "e-mail não pôde ser enviado." o fluxo seguirá normalmente,
mas você pode instalar o Mailpit seguindo as instruções do site oficial: https://mailpit.axllent.org/
```shell
mailpit --smtp 127.0.0.1:1025 --listen 127.0.0.1:8025
```
Você pode acessar a interface do Mailpit em `http://localhost:8025` para visualizar os emails enviados.

Execute o projeto PHP com o comando:
```shell
php -S localhost:8080 -t public
```
Acesse http://localhost:8080/login

Pode usar o sistema com esse usuário:
- Email: `m@email.com`
- Senha: `password`