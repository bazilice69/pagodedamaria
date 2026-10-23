# 🎨 Tema visual — Pagode da Dona Maria

As cores ficam centralizadas como **variáveis CSS** em `resources/css/app.css`
(e espelhadas no `tailwind.config.js`). Para trocar de paleta, basta alterar
os valores das variáveis `--cor-*` — nenhuma tela precisa ser refeita.

## Paleta ATUAL (padrão): Opção 2 — Verde + Terracota

Escolhida por passar afeto, brasilidade e um ar artesanal/humano, ideal para
uma associação comunitária que acolhe crianças e famílias.

| Papel          | Cor            | Hex       |
|----------------|----------------|-----------|
| Primária       | Verde escuro   | `#164A36` |
| Secundária     | Terracota      | `#C96F4A` |
| Fundo claro    | Bege quente    | `#F4E6D2` |
| Destaque       | Amarelo manteiga | `#F2C94C` |
| Texto escuro   | Marrom café    | `#39251F` |

## Outras opções aprovadas (trocar quando quiser)

### Opção 1 — Verde original (tradição, natureza, confiança)
- Primária: `#075B24` · Secundária: `#1E8A45` · Fundo: `#FFF8E8`
- Destaque: `#D8A83E` · Texto: `#26332B`

### Opção 3 — Azul, coral e creme (moderna, institucional)
- Primária: `#124C5F` · Secundária: `#68B7C4` · Destaque: `#E97B62`
- Fundo: `#FFF5E6` · Texto: `#1D2930`

## Logo

O logo está em desenvolvimento pelo Thiago. Quando pronto, salvar em
`public/img/logo.png` (e opcional `logo-branco.png` para fundos escuros).
O sistema também permite subir o logo pelo painel em Configurações, que grava
em `storage/app/public` e é exibido no header/rodapé via `$assoc->logo`.
