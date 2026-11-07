import defaultTheme from 'tailwindcss/defaultTheme';
import colors from 'tailwindcss/colors';
import forms from '@tailwindcss/forms';

// Verde pino: reemplaza el azul/índigo/violeta por defecto en todas las vistas.
const pine = {
    50: '#f1f8f5',
    100: '#dcefe7',
    200: '#b9dfd0',
    300: '#8ac7b2',
    400: '#55a78f',
    500: '#277a67',
    600: '#1f6657',
    700: '#195247',
    800: '#144239',
    900: '#10352e',
    950: '#091f1b',
};

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    safelist: [
        'dt-container',
        'dt-layout-start',
        'dt-layout-end',
        'dt-layout-table',
        'dt-length',
        'dt-search',
    ],

    theme: {
        extend: {
            colors: {
                blue: pine,
                indigo: pine,
                violet: pine,
                purple: pine,
                // Neutros cálidos en lugar del gris azulado.
                slate: colors.stone,
                gray: colors.stone,
                // Acento terracota, usado con moderación (precios, detalles).
                clay: {
                    50: '#fbf4ef',
                    100: '#f5e3d6',
                    500: '#b4532a',
                    600: '#9a4422',
                    700: '#7c371c',
                },
            },
            fontFamily: {
                sans: ['"Instrument Sans"', ...defaultTheme.fontFamily.sans],
                display: ['Fraunces', 'Georgia', ...defaultTheme.fontFamily.serif],
            },
        },
    },

    plugins: [forms],
};
