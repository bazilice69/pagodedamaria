# 📲 Como colocar pra Dona Maria testar

Três caminhos, do mais rápido/grátis ao mais "de verdade". Escolha conforme o momento.

---

## 🟢 Opção 1 — ngrok (grátis, ideal pra DEMONSTRAÇÃO rápida)

Roda o sistema **no seu PC** e gera um **link público temporário** que você
manda no WhatsApp pra ela abrir no celular. Perfeito pra mostrar e impressionar.
Não precisa de servidor nem cartão.

**Passos:**
```bash
# 1. Deixe o sistema rodando (ver README: migrate --seed, storage:link, build)
php artisan serve        # sobe em http://localhost:8000

# 2. Em outro terminal, exponha com o ngrok
#    (baixe em ngrok.com, crie conta grátis e autentique uma vez)
ngrok http 8000
```
O ngrok mostra uma URL tipo `https://abc-123.ngrok-free.app` → **é esse link que
você manda pra Dona Maria**. Enquanto seu PC e os dois terminais estiverem
abertos, ela acessa de qualquer lugar.

⚠️ **Importante:** o link morre quando você fecha o ngrok/PC. É pra demonstração,
não pra uso do dia a dia. E adicione a URL do ngrok ao `APP_URL` do `.env`
(ou rode `php artisan serve` normalmente — para a demo funciona).

> Dica: no `.env`, deixe `APP_ENV=local` e `APP_DEBUG=true` enquanto demonstra,
> pra ver erros caso apareçam.

---

## 🟡 Opção 2 — Hospedagem grátis na nuvem (link que fica no ar)

Pra ela testar com calma, no tempo dela, sem seu PC ligado. Plataformas com
plano grátis que rodam Laravel + banco:

- **Railway** (railway.com) — conecta no GitHub, detecta Laravel, sobe sozinho.
- **Render** (render.com) — plano free (hiberna quando sem uso; demora uns
  segundos no 1º acesso, mas é grátis).

Fluxo geral (Railway, resumido):
1. Suba este repositório no GitHub (já está em `bazilice69/pagodedamaria`).
2. Crie conta na Railway e **New Project → Deploy from GitHub repo**.
3. Adicione um banco (MySQL/Postgres) pelo próprio painel.
4. Configure as variáveis de ambiente (copie do `.env.example`, aponte o banco).
5. No deploy, rode `php artisan migrate --seed` e `php artisan storage:link`.

> Observação honesta: os planos grátis têm limites (hibernação, cota). Pra uso
> real contínuo, a **Opção 3** é a recomendada.

---

## 🔵 Opção 3 — Hostinger (produção de verdade, quando for pra valer)

Quando a Andreia/Dona Maria decidir adotar, a Hostinger roda Laravel liso e é
barata. Veja o passo a passo de produção no `README.md` (MySQL, `migrate --seed`,
`storage:link`, caches e `npm run build`).

---

## ✅ Antes de mandar pra ela (checklist de 1 minuto)

- [ ] Rodou `php artisan migrate --seed` (cria o login e os dados de exemplo)
- [ ] Rodou `php artisan storage:link` (pra logo e fotos aparecerem)
- [ ] Rodou `npm run build` (visual final; sem isso usa o Tailwind via CDN)
- [ ] O vídeo de intro está em `public/video/intro.mp4` ✅ (já está!)
- [ ] A logo está em `public/img/logo.png` ✅ (já está!)
- [ ] **Trocou a senha** do admin (o seed usa `senha123` — mude antes de expor)

### 👤 Logins de demonstração (senha `senha123` — troque antes de enviar!)
- **Presidente** (acesso total + gestão de equipe): `admin@pagodedadonamaria.org`
- **Diretor** (gestão, aprova voluntários): `diretor@pagodedadonamaria.org`
- **Voluntário** (acesso limitado): `voluntario@pagodedadonamaria.org`

### 👪 Portal do Responsável (sem senha)
Acesse `/portal` com o **código de um aluno** (ex: `PDM-000003`, está na
carteirinha em Cadastros) + a **data de nascimento** dele.

### 🔄 Puxou mudanças da V2/V3? Recrie o banco
```bash
php artisan migrate:fresh --seed
php artisan optimize:clear
```

### 💬 Sugestão de mensagem pra Dona Maria
> Oi Dona Maria! 💜 Fiz um sisteminha pro Pagode. Dá uma olhada nesse link
> [COLE O LINK], mexe à vontade. Tem a parte que aparece pro público (campanhas,
> o que a gente precisa, como ajudar) e a parte de controle, que é só sua.
> Pra entrar no controle: botão "Acesso da equipe" lá embaixo.
> Me fala o que achou e o que você mudaria! 🎶

---

## 🗺️ O que ela vai ver (roteiro da demo)

1. **Abre o link** → a intro da Dona Maria toca 🎬 (botão pra ligar o som do pagode)
2. **Home** → logo, campanhas com barra de progresso, "o que estamos precisando"
3. **Como ajudar** → Pix (copiar chave), doar itens, ser voluntário
4. **Acesso da equipe** (rodapé) → entra no painel
5. **Painel** → resumo do mês, estoque com alertas, cadastra doação/campanha/etc.
