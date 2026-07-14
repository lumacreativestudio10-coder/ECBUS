module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        'primary-maroon': '#800000',
        'dark-maroon': '#4A0000',
        'primary-gold': '#D4AF37',
        'light-gold': '#F2D675',
        'cream': '#FFF9EE',
        'dark-text': '#2B1B1B',
      },
      fontFamily: {
        sans: ['Inter', 'sans-serif'],
      },
    },
  },
  plugins: [],
}
