# SOS-Mulher

**SOS-Mulher** é uma plataforma desenvolvida com o framework Laravel com o objetivo de apoiar mulheres vítimas de qualquer tipo de violência, oferecendo um canal de comunicação, apoio e gestão de atendimentos de forma segura e eficiente.

Repositório oficial: [github.com/BernardoCCosta/sos_mulher](https://github.com/BernardoCCosta/sos_mulher.git)

---

## Requisitos

Para executar o projeto localmente, é necessário ter instalado:

- PHP >= 8.1
- Composer
- Node.js e NPM
- Redis (para suporte a eventos em tempo real)
- Laravel Echo Server (`npm install -g laravel-echo-server`)

---

## Instalação

Siga os passos abaixo para configurar o ambiente de desenvolvimento:

### 1. Clone o repositório

```bash
git clone https://github.com/BernardoCCosta/sos_mulher.git
cd sos_mulher
```

### 2. Instale as dependências PHP

```bash
composer install
```

### 3. Instale as dependências JavaScript

```bash
npm install
```

### 4. Configure o arquivo `.env`

```bash
cp .env.example .env
php artisan key:generate
```

> Lembre-se de configurar corretamente o banco de dados e as variáveis de broadcast no `.env`.

---

## Executando o projeto

Abra **três terminais separados** e execute os seguintes comandos:

### 1. Servidor Laravel

```bash
php artisan serve
```

### 2. Compilação dos assets (modo desenvolvimento)

```bash
npm run dev
```

### 3. Laravel Echo Server

```bash
laravel-echo-server start
```

---

## Funcionalidades

- Cadastro e autenticação de utilizadoras e gestores
- Sistema de mensagens em tempo real com Laravel Echo
- Gestão de casos e atendimentos
- Painel administrativo com estatísticas
- Interface moderna e responsiva

---

## Produção

Para compilar os assets para produção, use:

```bash
npm run build
```

---

## Licença

Este projeto está licenciado sob os termos da licença MIT.

---

## Contato

Para dúvidas, sugestões ou apoio, entre em contato com os responsáveis pelo projeto através do GitHub:  
[github.com/BernardoCCosta](https://github.com/BernardoCCosta)







Rodar dev (local):

APP_ENV=dev make up


Rodar staging (teste remoto):

APP_ENV=staging make up


Rodar prod (produção):

APP_ENV=prod make up


Logs contínuos:

make logs


Reiniciar containers:

make restart