/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./src/**/*.{html,js,php}",
    "./*.php",
    "./js/**/*.js"
  ],
  theme: {
    extend: {
      colors: {
        primary: '#f97316',
        secondary: '#0d9488'
      }
    }
  },
  plugins: [],
}
