# 💇 Sistema de Agendamento para Salão de Beleza

## 📌 Sobre o Projeto

Este projeto consiste no desenvolvimento de um sistema web para gerenciamento e agendamento de serviços de um salão de beleza.

O projeto está sendo desenvolvido como parte da disciplina de **Atividades Práticas Interdisciplinares de Extensão IV**, do curso de **Ciência da Computação**, tendo como objetivo aplicar conhecimentos de desenvolvimento web na criação de uma solução tecnológica para uma comunidade/empreendimento local.

A solução será desenvolvida a partir do levantamento das necessidades reais do estabelecimento, buscando facilitar o gerenciamento dos horários e permitir maior praticidade no processo de agendamento dos clientes.

---

## 🎯 Objetivo

Desenvolver uma aplicação web que permita ao salão organizar seus serviços, profissionais, horários e agendamentos, proporcionando uma forma mais eficiente de gerenciar sua agenda e facilitar o atendimento aos clientes.

### Objetivos específicos

* Levantar as necessidades e dificuldades atuais do estabelecimento;
* Identificar os principais problemas relacionados ao processo de agendamento;
* Especificar os requisitos do sistema;
* Desenvolver uma aplicação web para gerenciamento dos agendamentos;
* Facilitar a consulta de horários disponíveis;
* Permitir o gerenciamento de serviços e profissionais;
* Centralizar as informações relacionadas aos agendamentos;
* Disponibilizar uma solução acessível e de fácil utilização;
* Realizar a implantação da aplicação em ambiente de produção;
* Avaliar a utilização da solução após sua implantação.

---

## 🏢 Contexto do Projeto

O sistema será desenvolvido para um salão de beleza local, buscando solucionar problemas identificados durante o levantamento de requisitos.

Atualmente, os processos relacionados aos agendamentos podem envolver ferramentas como aplicativos de mensagens, ligações telefônicas ou outros meios de comunicação.

O sistema proposto pretende centralizar essas informações e proporcionar uma maneira mais organizada de administrar a agenda do estabelecimento.

---

## 🔎 Levantamento de Requisitos

Antes do desenvolvimento da aplicação, será realizado um levantamento de requisitos junto ao estabelecimento.

O levantamento busca compreender:

* Como os agendamentos são realizados atualmente;
* Como os horários são controlados;
* Quais serviços são oferecidos;
* Como os profissionais são organizados;
* Como os clientes realizam os agendamentos;
* Quais problemas ocorrem atualmente;
* Quais funcionalidades são consideradas prioritárias;
* Quais melhorias são esperadas com a implantação do sistema.

As informações coletadas serão utilizadas para elaboração do **Documento de Especificação de Requisitos** e definição do escopo do projeto.

---

## 🚀 Funcionalidades previstas

As funcionalidades abaixo representam o escopo inicial planejado e poderão ser ajustadas após a conclusão do levantamento de requisitos.

### 👤 Clientes

* Cadastro de clientes;
* Consulta de informações;
* Consulta de agendamentos;
* Realização de agendamentos;
* Cancelamento de agendamentos.

### 💇 Serviços

* Cadastro de serviços;
* Edição de serviços;
* Definição de preço;
* Definição da duração do serviço;
* Consulta dos serviços disponíveis.

### 👩‍💼 Profissionais

* Cadastro de profissionais;
* Associação de profissionais aos serviços;
* Definição de horários de atendimento;
* Controle de disponibilidade.

### 📅 Agendamentos

* Consulta de horários disponíveis;
* Criação de agendamentos;
* Alteração de agendamentos;
* Cancelamento de agendamentos;
* Visualização da agenda;
* Controle da disponibilidade de horários.

### 🔔 Notificações

Dependendo do resultado do levantamento de requisitos e da viabilidade técnica:

* Confirmação de agendamento;
* Notificação de cancelamento;
* Lembretes de agendamento.

---

## 🛠️ Tecnologias

A aplicação será desenvolvida utilizando como base as tecnologias e conhecimentos apresentados no curso utilizado como referência para o projeto.

### Backend

* PHP
* CodeIgniter 4

### Frontend

* HTML5
* CSS3
* JavaScript
* Bootstrap

### Banco de Dados

* MySQL

### Ferramentas

* Git
* GitHub
* Visual Studio Code
* Laragon

> As tecnologias poderão ser ajustadas durante o desenvolvimento conforme as necessidades do projeto.

---

## 🏗️ Arquitetura

O sistema será desenvolvido seguindo o padrão arquitetural **MVC (Model-View-Controller)** disponibilizado pelo framework CodeIgniter 4.

```text
             ┌───────────────┐
             │    Usuário    │
             └───────┬───────┘
                     │
                     ▼
             ┌───────────────┐
             │  Controller   │
             └───────┬───────┘
                     │
             ┌───────┴───────┐
             ▼               ▼
       ┌───────────┐   ┌───────────┐
       │   Model   │   │    View   │
       └─────┬─────┘   └───────────┘
             │
             ▼
       ┌───────────┐
       │  Banco de │
       │   Dados   │
       └───────────┘
```

---

## ⚙️ Configuração do Ambiente

Para executar o projeto localmente, é necessário configurar o ambiente de desenvolvimento.

### 📋 Pré-requisitos

* [PHP](https://www.php.net/)
* [Composer](https://getcomposer.org/)
* [MySQL](https://www.mysql.com/)
* [Git](https://git-scm.com/)
* [Laragon](https://laragon.org/)
* Visual Studio Code ou outro editor de código

O **Laragon** é utilizado como ambiente de desenvolvimento local, fornecendo ferramentas como servidor web, PHP e banco de dados.

---

## 📥 Instalação do Projeto

### 1. Clonar o repositório

Abra o terminal e execute:

```bash
git clone https://github.com/Davis-leo/Sistema-de-Agendamento---Salao-Fabrica-de-Loiras.git
```

Entre na pasta do projeto:

```bash
cd Sistema-de-Agendamento---Salao-Fabrica-de-Loiras
```

### 2. Instalar as dependências

Execute:

```bash
composer install
```

O Composer irá instalar as dependências necessárias do CodeIgniter 4.

### 3. Configurar o arquivo `.env`

O CodeIgniter utiliza o arquivo `.env` para configurações específicas do ambiente.

Caso o projeto ainda não possua um `.env`, copie o arquivo de exemplo:

```bash
copy env .env
```

No Linux/macOS:

```bash
cp env .env
```

Depois, configure as informações do banco de dados no arquivo `.env`.

Exemplo:

```env
database.default.hostname = localhost
database.default.database = salao
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.port = 3306
```

> Os valores de usuário, senha, porta e nome do banco devem ser ajustados conforme a configuração do ambiente local.

### 4. Criar o banco de dados

No MySQL, crie um banco de dados vazio para o projeto.

Exemplo:

```sql
CREATE DATABASE salao;
```

O nome utilizado deve ser o mesmo configurado no arquivo `.env`.

### 5. Executar as migrations

Com o banco criado, execute:

```bash
php spark migrate
```

As migrations serão responsáveis por criar as tabelas necessárias para o funcionamento do sistema.

### 6. Executar o projeto

Para iniciar o servidor de desenvolvimento do CodeIgniter:

```bash
php spark serve
```

Por padrão, a aplicação poderá ser acessada em:

```text
http://localhost:8080
```

---

## 🔄 Configuração em Caso de Troca de Computador

Caso seja necessário trocar de computador, **não é necessário recriar manualmente as migrations ou as tabelas do banco de dados**.

As migrations fazem parte do código-fonte do projeto e são armazenadas no repositório Git.

### 🖥️ Passo a passo

#### 1. Instalar o ambiente

No novo computador, instale:

* Laragon;
* Git;
* Composer;
* Visual Studio Code.

O Laragon será utilizado para disponibilizar o PHP, MySQL e servidor web necessários para executar o projeto.

#### 2. Clonar o projeto

Abra o terminal dentro da pasta `www` do Laragon:

```text
C:\laragon\www
```

Execute:

```bash
git clone https://github.com/Davis-leo/Sistema-de-Agendamento---Salao-Fabrica-de-Loiras.git
```

O projeto ficará, por exemplo, em:

```text
C:\laragon\www\
└── Sistema-de-Agendamento---Salao-Fabrica-de-Loiras
```

#### 3. Instalar as dependências

Entre na pasta do projeto:

```bash
cd Sistema-de-Agendamento---Salao-Fabrica-de-Loiras
```

Execute:

```bash
composer install
```

#### 4. Configurar o `.env`

Crie o arquivo `.env` a partir do arquivo de exemplo:

```bash
copy env .env
```

Depois configure novamente as informações do banco de dados:

```env
database.default.hostname = localhost
database.default.database = salao
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.port = 3306
```

> O arquivo `.env` normalmente não deve ser versionado no Git, pois pode conter configurações específicas do ambiente e informações sensíveis.

#### 5. Criar um banco de dados vazio

Abra o MySQL através do Laragon e crie um banco de dados com o mesmo nome definido no `.env`.

Por exemplo:

```sql
CREATE DATABASE salao;
```

Não é necessário criar manualmente as tabelas.

#### 6. Executar as migrations

Execute:

```bash
php spark migrate
```

O CodeIgniter irá utilizar as migrations existentes no projeto para recriar a estrutura do banco de dados.

Por exemplo, se o projeto possuir migrations para:

```text
Users
Units
Services
Professionals
Appointments
```

essas tabelas serão criadas automaticamente.

#### 7. Iniciar o sistema

Execute:

```bash
php spark serve
```

E acesse:

```text
http://localhost:8080
```

---

## 💾 Sobre os dados do banco de dados

As **migrations** armazenadas no GitHub permitem recriar a estrutura do banco de dados, mas **não armazenam os dados cadastrados no sistema**.

Por exemplo:

```text
Migration
    ↓
Cria a tabela "services"
    ↓
┌────┬──────────────┬────────┐
│ id │ nome         │ preço  │
├────┼──────────────┼────────┤
│ 1  │ Corte        │ 50.00  │
│ 2  │ Barba        │ 30.00  │
└────┴──────────────┴────────┘
```

A migration cria a tabela e suas colunas, mas os registros de **Corte**, **Barba**, clientes, agendamentos etc. são dados armazenados no banco.

Como o objetivo deste projeto é permitir a reconstrução do ambiente de desenvolvimento, os dados de teste podem ser cadastrados novamente após a configuração do novo computador.

Caso futuramente seja necessário preservar os dados reais do sistema, será necessário realizar um **backup do banco de dados MySQL**.

---

## 🔀 Controle de Versão

O projeto utiliza **Git e GitHub** para controle de versão.

Sempre que uma alteração importante for realizada, recomenda-se criar um commit e enviá-lo para o GitHub.

Exemplo:

```bash
git add .
git commit -m "Adiciona cadastro de serviços"
git push
```

Para obter as alterações mais recentes em outro computador:

```bash
git pull
```

### 📌 O que deve estar no Git

O repositório deve conter os arquivos necessários para reconstruir o projeto, incluindo:

* Código-fonte;
* Controllers;
* Models;
* Views;
* Migrations;
* Rotas;
* Configurações que não contenham informações sensíveis;
* `composer.json`;
* `composer.lock`.

### 🔐 Arquivos de configuração

Informações específicas do ambiente, como senhas e credenciais, não devem ser publicadas no GitHub.

O arquivo `.env` deve ser configurado individualmente em cada computador.

É recomendável manter um arquivo de exemplo, como:

```text
.env.example
```

Esse arquivo pode conter apenas a estrutura das configurações necessárias, sem senhas ou informações sensíveis.

---

## 📁 Estrutura básica do projeto

```text
Sistema-de-Agendamento---Salao-Fabrica-de-Loiras/
│
├── app/
│   ├── Config/
│   ├── Controllers/
│   ├── Database/
│   │   ├── Migrations/
│   │   └── Seeds/
│   ├── Models/
│   └── Views/
│
├── public/
│
├── writable/
│
├── tests/
│
├── .env
├── composer.json
├── composer.lock
└── spark
```

---

## 📌 Resumo para configurar em um novo PC

```text
1. Instalar Laragon
        ↓
2. Instalar Git
        ↓
3. Clonar o projeto
        ↓
4. Executar composer install
        ↓
5. Criar/configurar o .env
        ↓
6. Criar banco MySQL vazio
        ↓
7. Executar php spark migrate
        ↓
8. Executar php spark serve
        ↓
9. Acessar http://localhost:8080
```

> **Importante:** não é necessário executar novamente `php spark make:migration` para migrations que já existem no projeto. Esse comando é utilizado durante o desenvolvimento para **criar novas migrations**. Em um novo computador, basta obter as migrations existentes pelo Git e executar `php spark migrate`.
