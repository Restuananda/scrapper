import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            colors: {
                // Primary brand - Deep ocean blue with electric accents
                primary: {
                    50: '#eef8ff',
                    100: '#d9eeff',
                    200: '#bbe2ff',
                    300: '#8cd0ff',
                    400: '#54b4ff',
                    500: '#2c91ff',
                    600: '#1570f5',
                    700: '#0e5ae1',
                    800: '#1249b6',
                    900: '#15408f',
                    950: '#122857',
                },
                // Accent - Shopee-inspired orange/coral
                accent: {
                    50: '#fff7ed',
                    100: '#ffeed4',
                    200: '#ffd9a8',
                    300: '#ffbd71',
                    400: '#ff9538',
                    500: '#ff7711',
                    600: '#f05c07',
                    700: '#c74408',
                    800: '#9e360f',
                    900: '#7f2f10',
                    950: '#451506',
                },
                // Success - Emerald
                success: {
                    50: '#ecfdf5',
                    100: '#d1fae5',
                    200: '#a7f3d0',
                    300: '#6ee7b7',
                    400: '#34d399',
                    500: '#10b981',
                    600: '#059669',
                    700: '#047857',
                    800: '#065f46',
                    900: '#064e3b',
                    950: '#022c22',
                },
                // Warning - Amber
                warning: {
                    50: '#fffbeb',
                    100: '#fef3c7',
                    200: '#fde68a',
                    300: '#fcd34d',
                    400: '#fbbf24',
                    500: '#f59e0b',
                    600: '#d97706',
                    700: '#b45309',
                    800: '#92400e',
                    900: '#78350f',
                    950: '#451a03',
                },
                // Danger - Rose
                danger: {
                    50: '#fff1f2',
                    100: '#ffe4e6',
                    200: '#fecdd3',
                    300: '#fda4af',
                    400: '#fb7185',
                    500: '#f43f5e',
                    600: '#e11d48',
                    700: '#be123c',
                    800: '#9f1239',
                    900: '#881337',
                    950: '#4c0519',
                },
                // Dark theme surface colors
                surface: {
                    50: '#f8fafc',
                    100: '#f1f5f9',
                    200: '#e2e8f0',
                    300: '#cbd5e1',
                    400: '#94a3b8',
                    500: '#64748b',
                    600: '#475569',
                    700: '#334155',
                    800: '#1e293b',
                    850: '#172033',
                    900: '#0f172a',
                    950: '#020617',
                },
            },
            fontFamily: {
                sans: ['Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
                display: ['Clash Display', 'Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
                mono: ['JetBrains Mono', ...defaultTheme.fontFamily.mono],
            },
            fontSize: {
                'xs': ['0.75rem', { lineHeight: '1rem' }],
                'sm': ['0.875rem', { lineHeight: '1.25rem' }],
                'base': ['1rem', { lineHeight: '1.5rem' }],
                'lg': ['1.125rem', { lineHeight: '1.75rem' }],
                'xl': ['1.25rem', { lineHeight: '1.75rem' }],
                '2xl': ['1.5rem', { lineHeight: '2rem' }],
                '3xl': ['1.875rem', { lineHeight: '2.25rem' }],
                '4xl': ['2.25rem', { lineHeight: '2.5rem' }],
                '5xl': ['3rem', { lineHeight: '1.16' }],
                '6xl': ['3.75rem', { lineHeight: '1.1' }],
                '7xl': ['4.5rem', { lineHeight: '1.05' }],
            },
            spacing: {
                '18': '4.5rem',
                '88': '22rem',
                '128': '32rem',
            },
            borderRadius: {
                '4xl': '2rem',
                '5xl': '2.5rem',
            },
            boxShadow: {
                'glow': '0 0 20px -5px rgba(44, 145, 255, 0.4)',
                'glow-lg': '0 0 40px -10px rgba(44, 145, 255, 0.5)',
                'glow-accent': '0 0 20px -5px rgba(255, 119, 17, 0.4)',
                'inner-glow': 'inset 0 2px 4px 0 rgba(255, 255, 255, 0.06)',
                'glass': '0 8px 32px rgba(0, 0, 0, 0.12)',
                'card': '0 1px 3px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.06)',
                'card-hover': '0 10px 40px rgba(0, 0, 0, 0.15), 0 2px 6px rgba(0, 0, 0, 0.08)',
            },
            backgroundImage: {
                'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
                'gradient-conic': 'conic-gradient(from 180deg at 50% 50%, var(--tw-gradient-stops))',
                'mesh-gradient': 'url("data:image/svg+xml,%3Csvg viewBox=\'0 0 400 400\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cfilter id=\'noiseFilter\'%3E%3CfeTurbulence type=\'fractalNoise\' baseFrequency=\'0.65\' numOctaves=\'3\' stitchTiles=\'stitch\'/%3E%3C/filter%3E%3Crect width=\'100%25\' height=\'100%25\' filter=\'url(%23noiseFilter)\'/%3E%3C/svg%3E")',
            },
            animation: {
                'fade-in': 'fadeIn 0.5s ease-out',
                'fade-in-up': 'fadeInUp 0.5s ease-out',
                'fade-in-down': 'fadeInDown 0.5s ease-out',
                'slide-in-left': 'slideInLeft 0.3s ease-out',
                'slide-in-right': 'slideInRight 0.3s ease-out',
                'scale-in': 'scaleIn 0.2s ease-out',
                'spin-slow': 'spin 3s linear infinite',
                'pulse-glow': 'pulseGlow 2s ease-in-out infinite',
                'float': 'float 6s ease-in-out infinite',
                'shimmer': 'shimmer 2s linear infinite',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                fadeInUp: {
                    '0%': { opacity: '0', transform: 'translateY(10px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                fadeInDown: {
                    '0%': { opacity: '0', transform: 'translateY(-10px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                slideInLeft: {
                    '0%': { opacity: '0', transform: 'translateX(-20px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                slideInRight: {
                    '0%': { opacity: '0', transform: 'translateX(20px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                scaleIn: {
                    '0%': { opacity: '0', transform: 'scale(0.95)' },
                    '100%': { opacity: '1', transform: 'scale(1)' },
                },
                pulseGlow: {
                    '0%, 100%': { boxShadow: '0 0 20px -5px rgba(44, 145, 255, 0.4)' },
                    '50%': { boxShadow: '0 0 30px -5px rgba(44, 145, 255, 0.6)' },
                },
                float: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-10px)' },
                },
                shimmer: {
                    '0%': { backgroundPosition: '-200% 0' },
                    '100%': { backgroundPosition: '200% 0' },
                },
            },
            transitionDuration: {
                '400': '400ms',
            },
            backdropBlur: {
                xs: '2px',
            },
        },
    },

    plugins: [
        forms,
        // Custom plugin for glass morphism
        function({ addUtilities }) {
            addUtilities({
                '.glass': {
                    'background': 'rgba(255, 255, 255, 0.05)',
                    'backdrop-filter': 'blur(10px)',
                    'border': '1px solid rgba(255, 255, 255, 0.1)',
                },
                '.glass-dark': {
                    'background': 'rgba(15, 23, 42, 0.8)',
                    'backdrop-filter': 'blur(10px)',
                    'border': '1px solid rgba(255, 255, 255, 0.05)',
                },
                '.text-gradient': {
                    'background': 'linear-gradient(135deg, #2c91ff 0%, #ff7711 100%)',
                    '-webkit-background-clip': 'text',
                    '-webkit-text-fill-color': 'transparent',
                    'background-clip': 'text',
                },
                '.text-gradient-primary': {
                    'background': 'linear-gradient(135deg, #54b4ff 0%, #0e5ae1 100%)',
                    '-webkit-background-clip': 'text',
                    '-webkit-text-fill-color': 'transparent',
                    'background-clip': 'text',
                },
            });
        },
    ],
};
