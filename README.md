# 💜 Pagode da Dona Maria

Sistema de gestão de **doações, campanhas, necessidades, estoque e voluntários**
para a associação, com uma **área pública** (para quem quer ajudar) e uma
**área administrativa** protegida por login.

> Feito em **Laravel 11 + Blade + Alpine.js + Tailwind CSS**.

---

## ✨ O que já está pronto (V1)

### Área pública (qualquer pessoa)
- 🏠 **Página inicial** com hero (pronto para a animação da Dona Maria em vídeo)
- 🎬 **Intro de abertura (splash)** em vídeo — aparece na home e no site
- 🎯 **Campanhas** com barra de progresso e compartilhamento no WhatsApp
- 🚨 **Necessidades** ("estamos precisando de...") com prioridade e progresso
- ❤️ **Como ajudar** com Pix (copiar chave) e doação de itens
- 🚗 **Formulário "Quero ser voluntário"**
- 📄 Sobre e Contato

### Área administrativa (login)
- 📊 **Painel** com resumo do mês (dados reais) e alerta de estoque baixo
- ❤️ **Doações** (dinheiro ou itens; entrada automática no estoque)
- 🎯 **Campanhas** (CRUD + upload de foto)
- 🚨 **Necessidades** (CRUD)
- 📦 **Estoque** (itens, entradas/saídas, alerta de mínimo)
- 🚗 **Voluntários** (somente admin)
- ⚙️ **Configurações** da associação: dados, redes, Pix, logo (somente admin)

### Segurança
- Login com papéis: **admin**, **voluntário**, **professor**
- Dados sensíveis de crianças **não** ficam públicos (só números agregados)

---

## 🎨 Cores (tema)

Paleta padrão: **Verde + Terracota** (afetiva, comunitária, brasileira).
As cores ficam centralizadas em `resources/css/app.css` e `tailwind.config.js`.
Para trocar de paleta, veja `resources/css/tema.md`.

---

## 🎬 Como ativar a intro / animação

1. Exporte o vídeo (ex.: `.mp4` curto, com o som do pagode embutido).
2. Salve como **`public/video/intro.mp4`**.
3. (Opcional) uma imagem de capa em `public/img/intro-poster.jpg`.

Pronto! A intro passa a aparecer automaticamente:
- como **splash de abertura** (1x por visita, com botão "Pular" e controle de som);
- como **fundo do hero** da página inicial (em loop, discreto).

Enquanto o vídeo não existir, o site funciona normalmente (sem intro).

---

## 🚀 Instalação (local)

Requisitos: PHP 8.2+, Composer, Node 18+.

```bash
# 1. Dependências
composer install
npm install

# 2. Ambiente
cp .env.example .env
php artisan key:generate

# 3. Banco (SQLite por padrão — zero config)
touch database/database.sqlite
php artisan migrate --seed

# 4. Link do storage (para fotos/logo)
php artisan storage:link

# 5. Assets + servidor
npm run build        # ou: npm run dev
php artisan serve
```

Acesse **http://localhost:8000**.

### Login de demonstração (vem do seed)
- **Admin:** `admin@pagodedadonamaria.org` · senha `senha123`
- **Voluntário:** `voluntario@pagodedadonamaria.org` · senha `senha123`

> ⚠️ Troque essas senhas antes de publicar.

---

## 🌐 Produção (Hostinger / MySQL)

No `.env`, troque para MySQL e preencha os dados:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=pagodedamaria
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seudominio.com.br
```

Depois: `php artisan migrate --seed`, `php artisan storage:link`,
`php artisan config:cache route:cache view:cache`, `npm run build`.

---

## 🗺️ Roadmap

- **V2 — Educacional:** Cursos/Turmas, Professores, Alunos, Pais/Responsáveis,
  matrícula, **chamada de frequência** e **Portal do Pai** (acesso leve por
  link/código, sem senha). Também: relatórios, QR Code Pix, arrecadações na rua
  no painel.
- **V3 — Futuro:** app, automação de pagamentos, prestação de contas pública,
  multi-associação.
