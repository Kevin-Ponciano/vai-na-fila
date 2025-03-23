import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    darkMode: 'selector', // or 'class'
    theme: {
        colors: {
            primary: {
                light: "#BDEDFB", // Azul Claro - Suavidade e Acessibilidade
                DEFAULT: "#007BFF", // Azul Principal - Confiança e Profissionalismo
                dark: "#1565C0", // Azul Escuro - Profundidade e Seriedade
            },
            secondary: {
                light: "#FFE082", // Laranja Claro - Otimismo e Destaque Suave
                DEFAULT: "#FF6F00", // Laranja Principal - Energia e Dinamismo
                dark: "#E65100", // Laranja Escuro - Força e Impacto
            },
            neutral: {
                light: "#ECEFF1", // Cinza Claro - Suavidade e Legibilidade
                DEFAULT: "#B0BEC5", // Cinza Médio - Equilíbrio Visual
                dark: "#37474F", // Cinza Escuro - Sofisticação e Modernidade
            },
        },
        screens: {
            'sm': '340px',
            // => @media (min-width: 640px) { ... }

            'md': '768px',
            // => @media (min-width: 768px) { ... }

            'lg': '1024px',
            // => @media (min-width: 1024px) { ... }

            'xl': '1280px',
            // => @media (min-width: 1280px) { ... }

            '2xl': '1536px',
            // => @media (min-width: 1536px) { ... }
            'DEFAULT': '1920px',
        },
        extend: {
            fontFamily: {
                poppins: ['Poppins', ...defaultTheme.fontFamily.sans],
                //sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms, typography],
};
