# 📖 Product Requirements Document (PRD): ShepherdPath

## 1. Visão Geral do Projeto
O **ShepherdPath** (Caminho do Pastor) é um sistema de gestão de presença para a catequese paroquial. O objetivo é substituir um processo manual e moroso baseado em Microsoft Forms e planilhas de Excel por uma solução web automatizada, robusta e escalável.

## 2. O Problema (The Bottleneck)
Atualmente, o coordenador da catequese enfrenta os seguintes gargalos:
* **Coleta manual**: Uso de formulários genéricos com lógica de ramificação complexa.
* **Tratamento de dados**: O Excel gerado contém dezenas de colunas vazias que precisam ser excluídas manualmente todas as semanas.
* **Relatórios**: A organização por catequista e a geração de PDFs para prestação de contas é feita de forma braçal, consumindo horas do final de semana.

## 3. Objetivos da Solução
* **Automatizar o fluxo**: Da coleta via QR Code à geração do PDF final.
* **Garantir a integridade**: Evitar erros de preenchimento filtrando catequistas por etapa.
* **Escalabilidade**: Utilizar uma stack moderna (Laravel 12 + PostgreSQL) capaz de rodar em ambientes virtualizados como Proxmox.

## 4. Requisitos Funcionais (Funcionalidades)

### 4.1. Check-in de Presença (Público)
* Interface mobile-first para preenchimento rápido na porta da missa.
* Seleção obrigatória do horário da missa (Sábado 16h/19h, Domingo 7h30/10h/19h).
* Filtro dinâmico: Ao selecionar a etapa (1ª ou 2ª), o sistema deve exibir apenas os catequistas vinculados àquela etapa.
* Busca por nome de aluno para evitar erros de digitação.

### 4.2. Painel Administrativo (Privado)
* Gerenciamento de Etapas, Catequistas e Alunos via FilamentPHP.
* Dashboard com contagem de presenças por missa.

### 4.3. Relatórios e Automação
* **Job Semanal**: Geração automática de relatórios em PDF organizados por catequista e ordem alfabética de alunos.
* **Fim da limpeza de dados**: O sistema deve exportar apenas dados preenchidos, sem colunas vazias.

## 5. Arquitetura Técnica
* **Framework**: Laravel 12 (PHP 8.5+).
* **Banco de Dados**: PostgreSQL (Relacional).
* **Cache/Fila**: Redis (Para sessões e processamento de PDFs em background).
* **Frontend**: Tailwind CSS + Alpine.js.
* **Infraestrutura**: Docker (Laravel Sail).

## 6. Modelo de Dados (ERD)
* **etapas**: `id`, `nome` (1ª etapa, 2ª etapa).
* **catequistas**: `id`, `nomes`, `etapa_id`.
* **missas**: `id`, `descricao`.
* **alunos**: `id`, `nome_completo`, `etapa_id`, `catequista_id`.
* **presencas**: `id`, `aluno_id`, `missa_id`, `data_missa`.

## 7. Cronograma de Implementação (Tasks para IA)
1. **Fase 1**: Setup do ambiente Docker/Sail com PostgreSQL e Redis.
2. **Fase 2**: Migrations e Models com relacionamentos Eloquent.
3. **Fase 3**: Desenvolvimento do formulário de check-in com lógica Alpine.js.
4. **Fase 4**: Implementação do FilamentPHP para gestão administrativa.
5. **Fase 5**: Criação do Job de exportação de PDF (Laravel-DomPDF).