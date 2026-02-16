# 📌 Task Manager - Sistema de Gerenciamento de Tarefas

 **Sistema desenvolvido como desafio técnico para gerenciamento de projetos e tarefas** , permitindo colaboração entre membros da equipe, controle de progresso e acompanhamento das atividades diárias.

O projeto segue com arquitetura  **MVC** , práticas basicas de segurança e organização.

---

# 🚀 Tecnologias Utilizadas

* **Back-end:** PHP 8.4 (FPM), Laravel (MVC)
* **Banco de Dados:** MySQL 8.3
* **Servidor Web:** Nginx
* **Front-end:** TailwindCSS + Blade + Vite
* **Node.js 20** (build front-end e assets)
* **Containerização:** Docker + Docker Compose
* **Gerenciamento de pacotes:** Composer & NPM

---

# 🐳 Estrutura Docker

| Serviço | Container          | Porta | Função             |
| -------- | ------------------ | ----- | -------------------- |
| app      | task-manager-app   | 9000  | PHP-FPM (Laravel)    |
| nginx    | task-manager-nginx | 8000  | Servidor web         |
| mysql    | task-manager-mysql | 3306  | Banco de dados MySQL |

Volumes persistentes: `dbdata` para MySQL.

Rede interna: `laravel-network`.

---

# ⚙️ Instalação e Configuração

## 1️⃣ Clonar o repositório

<pre class="overflow-visible! px-0!" data-start="1492" data-end="1577"><div class="contain-inline-size rounded-2xl corner-superellipse/1.1 relative bg-token-sidebar-surface-primary"><div class="sticky top-[calc(var(--sticky-padding-top)+9*var(--spacing))]"><div class="absolute end-0 bottom-0 flex h-9 items-center pe-2"><div class="bg-token-bg-elevated-secondary text-token-text-secondary flex items-center gap-4 rounded-sm px-2 font-sans text-xs"></div></div></div><div class="overflow-y-auto p-4" dir="ltr"><code class="whitespace-pre! language-bash"><span><span>git </span><span>clone</span><span> https://github.com/VictorIshizuka/task-manager-laravel.git
</span><span>cd</span><span> task-manager-laravel
</span></span></code></div></div></pre>

## 2️⃣ Criar arquivo `.env`

<pre class="overflow-visible! px-0!" data-start="1608" data-end="1648"><div class="contain-inline-size rounded-2xl corner-superellipse/1.1 relative bg-token-sidebar-surface-primary"><div class="sticky top-[calc(var(--sticky-padding-top)+9*var(--spacing))]"><div class="absolute end-0 bottom-0 flex h-9 items-center pe-2"><div class="bg-token-bg-elevated-secondary text-token-text-secondary flex items-center gap-4 rounded-sm px-2 font-sans text-xs"></div></div></div><div class="overflow-y-auto p-4" dir="ltr"><code class="whitespace-pre! language-bash"><span><span>cp</span><span> src/.env.example src/.env
</span></span></code></div></div></pre>

Configurar banco de dados:

<pre class="overflow-visible! px-0!" data-start="1678" data-end="1799"><div class="contain-inline-size rounded-2xl corner-superellipse/1.1 relative bg-token-sidebar-surface-primary"><div class="sticky top-[calc(var(--sticky-padding-top)+9*var(--spacing))]"><div class="absolute end-0 bottom-0 flex h-9 items-center pe-2"><div class="bg-token-bg-elevated-secondary text-token-text-secondary flex items-center gap-4 rounded-sm px-2 font-sans text-xs"></div></div></div><div class="overflow-y-auto p-4" dir="ltr"><code class="whitespace-pre! language-env"><span>DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=laravel
DB_PASSWORD=secret
</span></code></div></div></pre>

## 3️⃣ Subir containers

<pre class="overflow-visible! px-0!" data-start="1826" data-end="1866"><div class="contain-inline-size rounded-2xl corner-superellipse/1.1 relative bg-token-sidebar-surface-primary"><div class="sticky top-[calc(var(--sticky-padding-top)+9*var(--spacing))]"><div class="absolute end-0 bottom-0 flex h-9 items-center pe-2"><div class="bg-token-bg-elevated-secondary text-token-text-secondary flex items-center gap-4 rounded-sm px-2 font-sans text-xs"></div></div></div><div class="overflow-y-auto p-4" dir="ltr"><code class="whitespace-pre! language-bash"><span><span>docker-compose up -d --build
</span></span></code></div></div></pre>

## 4️⃣ Instalar dependências e rodar migrations

<pre class="overflow-visible! px-0!" data-start="1917" data-end="2059"><div class="contain-inline-size rounded-2xl corner-superellipse/1.1 relative bg-token-sidebar-surface-primary"><div class="sticky top-[calc(var(--sticky-padding-top)+9*var(--spacing))]"><div class="absolute end-0 bottom-0 flex h-9 items-center pe-2"><div class="bg-token-bg-elevated-secondary text-token-text-secondary flex items-center gap-4 rounded-sm px-2 font-sans text-xs"></div></div></div><div class="overflow-y-auto p-4" dir="ltr"><code class="whitespace-pre! language-bash"><span><span>docker </span><span>exec</span><span> -it task-manager-app bash
composer install
npm install
npm run build
php artisan key:generate
php artisan migrate
php artisan db:seed
</span><span>exit</span><span>
</span></span></code></div></div></pre>

## 5️⃣ Acessar aplicação

<pre class="overflow-visible! px-0!" data-start="2087" data-end="2116"><div class="contain-inline-size rounded-2xl corner-superellipse/1.1 relative bg-token-sidebar-surface-primary"><div class="sticky top-[calc(var(--sticky-padding-top)+9*var(--spacing))]"><div class="absolute end-0 bottom-0 flex h-9 items-center pe-2"><div class="bg-token-bg-elevated-secondary text-token-text-secondary flex items-center gap-4 rounded-sm px-2 font-sans text-xs"></div></div></div><div class="overflow-y-auto p-4" dir="ltr"><code class="whitespace-pre!"><span><span>http:</span><span>//localhost:8000</span><span>
</span></span></code></div></div></pre>

---

# 🏗 Arquitetura do Sistema

O projeto segue **MVC** com camadas bem definidas:

* **Models:** Responsáveis pelas entidades e regras de negócio (`User`, `Project`, `Task`, `ProjectFile`, `TaskFile`)
* **Controllers:** Lógica de fluxo (`ProjectController`, `TaskController`, `AuthController`)
* **Views:** Blade + TailwindCSS, responsividade (básica) e design simples
* **FormRequests:** Validações de requisição
* **Policies:** Controle de autorização
* **Routes:** Web (`routes/web.php`)

---

# 📁 Funcionalidades Implementadas

### Cadastro e Autenticação

* Registro de usuários (nome, e-mail e senha)
* Login e logout
* Validação e proteção contra CSRF

### Gerenciamento de Projetos

* CRUD completo de projetos
* Campos: título, descrição, datas, arquivos anexos
* Compartilhamento com membros da equipe
* Controle de permissões via Policies

### Gerenciamento de Tarefas

* CRUD de tarefas
* Campos: título, descrição, prioridade, status, data de vencimento, arquivos
* Filtro por status e prioridade
* Marcação de tarefas concluídas
* Dashboard com resumo de tarefas pendentes e atrasadas

---

# 🛡 Segurança Técnica

* **Proteção contra SQL Injection** → Todos os queries via Eloquent ORM
* **Proteção contra XSS** → Escape automático nas views Blade
* **Proteção contra CSRF** → Middleware padrão do Laravel
* **Autenticação segura** → Hash de senhas via hash
* **Autorização granular** → Policies e Gates para controlar CRUD de projetos e tarefas
* **Validação centralizada** → FormRequests para todas as entradas de dados
* **Upload seguro de arquivos** → Restrições de tipo e tamanho
* **Boas práticas** → Redirecionamentos corretos, tratamento de erros e mensagens de log claras

---

# 🗄 Banco de Dados

Principais entidades:

* **users**
* **projects**
* **tasks**
* **project_user** (pivot)
* **project_files**
* **task_files**

---

# 📊 DER (Diagrama de Entidade e Relacionamento)

> **Adicione sua imagem do DER abaixo**
>
> ![1771258988542](image/README/1771258988542.png)

---

# 📁 Estrutura de Pastas

<pre class="overflow-visible! px-0!" data-start="4451" data-end="4762"><div class="contain-inline-size rounded-2xl corner-superellipse/1.1 relative bg-token-sidebar-surface-primary"><div class="sticky top-[calc(var(--sticky-padding-top)+9*var(--spacing))]"><div class="absolute end-0 bottom-0 flex h-9 items-center pe-2"><div class="bg-token-bg-elevated-secondary text-token-text-secondary flex items-center gap-4 rounded-sm px-2 font-sans text-xs"></div></div></div><div class="overflow-y-auto p-4" dir="ltr"><code class="whitespace-pre!"><span><span>.
├── docker/
│   ├── php/
│   └── nginx/
├── </span><span>src</span><span>/
│   ├── app/
│   │   ├── Models/
│   │   ├── Policies/
│   │   └── Http/
│   │       ├── Controllers/
│   │       └── Requests/
│   ├── resources/
│   │   ├── views/
│   │   └── js/
│   ├── routes/
│   └── database/
├── docker-compose</span><span>.yml</span><span>
└── README</span><span>.md</span><span>
</span></span></code></div></div></pre>

---

# 🔧 Boas práticas aplicadas

* Arquitetura **MVC**
* **FormRequests** para validação
* **Policies** para controle de acesso
* **Resources** para transformação de dados
* **Service Providers** para configurações globais
* **Rotas organizadas** por módulo
* **Padrão PSR-12** de código
* Comentários claros e consistentes

---

# 👨‍💻 Autor

**Victor Ishizuka**

---

# 📜 Licença

Projeto desenvolvido exclusivamente para avaliação técnica.
