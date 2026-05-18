/** @type {import('tailwindcss').Config} */
export default {
  darkMode: 'class', // Correctly set
  theme: {
    extend: {
      colors: {
        primary: {
          50: '#fff7ed',
          500: '#f97316',
          600: '#ea580c',
          700: '#c2410c'
        },
        // Quotes use kora hoyeche jate blade e hyphen (-) diye lekha jay
        'dark-body': '#020617', 
        'dark-card': '#0f172a',
        'dark-border': '#1e293b'
      },
    },
  },
  plugins: [],
}