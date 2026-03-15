# ⛪ ShepherdPath (Caminho do Pastor)

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2+">
  <img src="https://img.shields.io/badge/PostgreSQL-16-336791?style=for-the-badge&logo=postgresql&logoColor=white" alt="PostgreSQL">
  <img src="https://img.shields.io/badge/Redis-DC382D?style=for-the-badge&logo=redis&logoColor=white" alt="Redis">
  <img src="https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white" alt="Docker">
</p>

O **ShepherdPath** é um sistema inteligente de gestão de presença para catequese, projetado para eliminar o trabalho manual e repetitivo de coordenadores paroquiais. Desenvolvido com **Laravel 12** e **PostgreSQL**, ele transforma um processo "enrolado" de planilhas em um fluxo automatizado e profissional.

---

> 🇧🇷 **Você fala português?** [Leia a versão em Português aqui](./README.pt-BR.md)

---

## 📖 Índice

- [O Problema](#-o-problema-o-gargalo-do-domingo-à-noite)
- [A Solução](#-a-solução-automação-com-propósito)
- [Stack Tecnológica](#️-stack-tecnológica)
- [Pré-requisitos](#-pré-requisitos)
- [Instalação](#-instalação)
- [Como Funciona](#️-como-funciona)
- [Contribuição](#-contribuição)
- [Licença](#-licença)

---

## 🚀 O Problema: O "Gargalo" do Domingo à Noite

Antes do ShepherdPath, o registro de presença dependia de ferramentas genéricas (como formulários online convencionais) que geravam um fluxo de trabalho exaustivo:

- **Limpeza Manual:** Necessidade de excluir dezenas de colunas vazias geradas pela lógica de ramificação dos formulários.
- **Ordenação Complexa:** Perda de tempo organizando centenas de alunos por catequista e por ordem alfabética toda semana.
- **Exportação Braçal:** Geração manual de relatórios ou PDFs para envio aos responsáveis via WhatsApp.

## ✨ A Solução: Automação com Propósito

O sistema atua na raiz do problema, estruturando os dados desde o momento do check-in:

- 📱 **Check-in Inteligente via QR Code:** Interface *mobile-first* onde o aluno registra a presença na entrada da missa.
- 🎯 **Filtro Dinâmico por Etapa:** Utiliza **Alpine.js** para mostrar apenas os catequistas da etapa selecionada (1ª ou 2ª), evitando erros de preenchimento.
- 📄 **Relatórios Automatizados:** Um **Job** em segundo plano processa as presenças da semana e gera PDFs limpos e organizados por turma, prontos para distribuição.
- ⚡ **Performance Robusta:** Sessões e filas gerenciadas via **Redis**, garantindo rapidez mesmo com centenas de acessos simultâneos no horário da missa.

## 🛠️ Stack Tecnológica

- **Backend:** Laravel 12 (PHP 8.2+)
- **Banco de Dados:** PostgreSQL (Relacionamentos íntegros e performantes)
- **Cache/Fila:** Redis
- **Painel Administrativo:** FilamentPHP v4
- **Frontend:** Tailwind CSS & Alpine.js
- **Ambiente:** Docker (Laravel Sail)

---

## 📋 Pré-requisitos

Antes de iniciar as configurações, certifique-se de que você possui as seguintes ferramentas instaladas em sua máquina:
- [Docker e Docker Compose](https://www.docker.com/products/docker-desktop)
- [Git](https://git-scm.com/)

---

## 📦 Instalação

O projeto é executado com **Laravel Sail**, o que garante um ambiente de desenvolvimento limpo e com suporte às versões mais recentes utilizando *containers* do Docker.

1. **Clone o repositório:**
   ```bash
   git clone https://github.com/SiqueiraAndre/shepherd-path.git
   cd shepherd-path
   ```

2. **Crie o arquivo de ambiente:**
   ```bash
   cp .env.example .env
   ```

3. **Inicie a instalação das instâncias do sail:**
   Caso a máquina local não possua o Composer/PHP instalados, é possível utilizar a imagem Docker:
   ```bash
   docker run --rm \
       -u "$(id -u):$(id -g)" \
       -v "$(pwd):/var/www/html" \
       -w /var/www/html \
       laravelsail/php84-composer:latest \
       composer install --ignore-platform-reqs
   ```

4. **Suba o ambiente Docker:**
   ```bash
   ./vendor/bin/sail up -d
   ```

5. **Ajuste de configuração inicial da base de dados (Chave e Migrations com seeders reais):**
   ```bash
   ./vendor/bin/sail artisan key:generate
   ./vendor/bin/sail artisan migrate --seed
   ```

6. **Inicie a execução das filas e trabalhos:**
   ```bash
   ./vendor/bin/sail artisan queue:work
   ```

7. **Acesse a aplicação no navegador:**
   - **Sistema (Check-in):** [http://localhost](http://localhost)
   - **Painel Administrativo:** [http://localhost/admin](http://localhost/admin)

---

## ⚙️ Como Funciona

1. **Configuração:** O coordenador acessa o painel administrativo para cadastrar as *Etapas*, os *Catequistas* e as *Missas*.
2. **Registro de Presença:** Na porta da igreja, os alunos escaneiam o QR Code para marcar a presença, utilizando uma interface simples e à prova de falhas.
3. **Processamento Seguro:** O sistema valida os dados recebidos e os armazena de maneira estruturada no PostgreSQL.
4. **Entrega Programada:** Toda segunda-feira (ou no horário configurado), o sistema consolida os dados, gera PDFs de cada turma e os entrega por e-mail ou WhatsApp, sem que nenhum arquivo Excel precise ser tocado.

---

## 🤝 Contribuição

Contribuições são fundamentais para o desenvolvimento open-source! Para colaborar:

1. Realize um *Fork* do projeto
2. Crie uma branch para a nova funcionalidade (`git checkout -b feature/SuaFeature`)
3. Faça o _commit_ das alterações (`git commit -m 'Add: nova funcionalidade'`)
4. Faça o push para a branch (`git push origin feature/SuaFeature`)
5. Abra um **Pull Request**

---

## 📄 Licença

O projeto original do ShepherdPath foi projetado para automação e aperfeiçoamento comunitário paroquial, sendo distribuído sob a licença **MIT**. Leia as documentações nativas de licenças para mais informações.

---
<p align="center">Feito com ❤️ pela equipe para garantir mais tempo na catequese real.</p>