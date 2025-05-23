/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './**/*.php',
    './assets/js/**/*.js',
    './components/**/*.php',
    './template-parts/**/*.php',
  ],
  theme: {
    extend: {
      // Design tokens will be mapped here from JSON export
      colors: {
        // Primary brand colors
        'primary': {
          50: '#eff6ff',
          100: '#dbeafe',
          200: '#bfdbfe',
          300: '#93c5fd',
          400: '#60a5fa',
          500: '#3b82f6', // Primary blue
          600: '#2563eb',
          700: '#1d4ed8',
          800: '#1e40af',
          900: '#1e3a8a',
          950: '#172554',
        },
        'secondary': {
          50: '#ecfdf5',
          100: '#d1fae5',
          200: '#a7f3d0',
          300: '#6ee7b7',
          400: '#34d399',
          500: '#10b981', // Secondary green
          600: '#059669',
          700: '#047857',
          800: '#065f46',
          900: '#064e3b',
          950: '#022c22',
        },
        // Neutral colors for accessibility
        'neutral': {
          50: '#f9fafb',
          100: '#f3f4f6',
          200: '#e5e7eb',
          300: '#d1d5db',
          400: '#9ca3af',
          500: '#6b7280',
          600: '#4b5563',
          700: '#374151',
          800: '#1f2937',
          900: '#111827',
          950: '#030712',
        },
        // Status colors for forms and notifications
        'success': '#10b981',
        'warning': '#f59e0b',
        'error': '#ef4444',
        'info': '#3b82f6',
      },
      fontFamily: {
        'sans': ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', '"Segoe UI"', 'Roboto', '"Helvetica Neue"', 'Arial', '"Noto Sans"', 'sans-serif'],
        'serif': ['ui-serif', 'Georgia', 'Cambria', '"Times New Roman"', 'Times', 'serif'],
        'mono': ['ui-monospace', 'SFMono-Regular', '"SF Mono"', 'Consolas', '"Liberation Mono"', 'Menlo', 'monospace'],
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
        '5xl': ['3rem', { lineHeight: '1' }],
        '6xl': ['3.75rem', { lineHeight: '1' }],
        '7xl': ['4.5rem', { lineHeight: '1' }],
        '8xl': ['6rem', { lineHeight: '1' }],
        '9xl': ['8rem', { lineHeight: '1' }],
      },
      spacing: {
        '18': '4.5rem',
        '88': '22rem',
        '128': '32rem',
        '144': '36rem',
      },
      maxWidth: {
        '8xl': '88rem',
        '9xl': '96rem',
      },
      borderRadius: {
        '4xl': '2rem',
      },
      boxShadow: {
        'neuomorphism': '8px 8px 16px #d1d5db, -8px -8px 16px #ffffff',
        'inner-neuomorphism': 'inset 8px 8px 16px #d1d5db, inset -8px -8px 16px #ffffff',
      },
      animation: {
        'fade-in': 'fadeIn 0.5s ease-in-out',
        'slide-up': 'slideUp 0.3s ease-out',
        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        slideUp: {
          '0%': { transform: 'translateY(20px)', opacity: '0' },
          '100%': { transform: 'translateY(0)', opacity: '1' },
        },
      },
      // Breakpoints for responsive design
      screens: {
        'xs': '475px',
        'sm': '640px',
        'md': '768px',
        'lg': '1024px',
        'xl': '1280px',
        '2xl': '1536px',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms')({
      strategy: 'class', // Only add form styles to elements with .form-input, .form-select, etc.
    }),
    require('@tailwindcss/typography'),
    require('@tailwindcss/aspect-ratio'),
    // Custom plugin for accessibility utilities
    function({ addUtilities, theme }) {
      const newUtilities = {
        '.skip-link': {
          position: 'absolute',
          top: '-40px',
          left: '6px',
          background: theme('colors.primary.600'),
          color: 'white',
          padding: '8px',
          'z-index': '100',
          'text-decoration': 'none',
          '&:focus': {
            top: '6px',
          },
        },
        '.screen-reader-text': {
          clip: 'rect(1px, 1px, 1px, 1px)',
          position: 'absolute !important',
          height: '1px',
          width: '1px',
          overflow: 'hidden',
          'word-wrap': 'normal !important',
          '&:focus': {
            'background-color': theme('colors.neutral.100'),
            'border-radius': '3px',
            'box-shadow': '0 0 2px 2px rgba(0, 0, 0, 0.6)',
            clip: 'auto !important',
            color: theme('colors.primary.600'),
            display: 'block',
            'font-size': '14px',
            'font-weight': 'bold',
            height: 'auto',
            left: '5px',
            'line-height': 'normal',
            padding: '15px 23px 14px',
            'text-decoration': 'none',
            top: '5px',
            width: 'auto',
            'z-index': '100000',
          },
        },
        '.focus-visible': {
          'outline': '2px solid ' + theme('colors.primary.600'),
          'outline-offset': '2px',
        },
        '.reduced-motion': {
          'animation-duration': '0.01ms !important',
          'animation-iteration-count': '1 !important',
          'transition-duration': '0.01ms !important',
          'scroll-behavior': 'auto !important',
        },
      }
      addUtilities(newUtilities)
    },
  ],
  // Dark mode configuration (optional for future use)
  darkMode: 'class',
} 