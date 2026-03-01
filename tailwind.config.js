/** @type {import('tailwindcss').Config} */
module.exports = {
 content: [
  "./index.html",
  "./*.html",
  "./**/*.php",            // <-- scan all PHP files
  "./admin/**/*.{html,js,php}",
  "./cashier/**/*.{html,js,php}",
],
  theme: {
    extend: {
      fontFamily: {
        inter: ['Inter', 'sans-serif'], // font name + fallback
        poppins: ['Poppins', 'san-serif']
      },
    },
  },
  plugins: [],
}

