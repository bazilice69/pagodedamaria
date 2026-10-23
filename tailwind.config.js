/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                // Paleta padrao: Opcao 2 — Verde + Terracota.
                // Para trocar de paleta, altere tambem as variaveis em app.css.
                marca: {
                    DEFAULT: '#164A36', // verde escuro (primaria)
                    escuro: '#0F3326',
                    claro: '#1E6B4D',
                },
                terracota: {
                    DEFAULT: '#C96F4A', // secundaria
                    escuro: '#A8562F',
                    claro: '#E08E6B',
                },
                creme: '#F4E6D2',       // fundo quente
                manteiga: '#F2C94C',    // destaque
                cafe: '#39251F',        // texto escuro
            },
            fontFamily: {
                sans: ['Figtree', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },
            borderRadius: {
                xl2: '1.25rem',
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
};
