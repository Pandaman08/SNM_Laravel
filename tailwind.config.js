module.exports = {
  content: [
    './resources/views/**/*.blade.php',
    './storage/framework/views/*.php',
    './resources/**/*.js',
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Instrument Sans', 'ui-sans-serif', 'system-ui', 'sans-serif', 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji'],
      },
    },
  },
  plugins: [
    require('daisyui'),
  ],
  daisyui: {
    themes: ['light'], // Puedes cambiar a 'dark' o 'emerald'
    darkTheme: 'light',
    base: true,
    styled: true,
    utils: true,
    logs: true,
    rtl: false,
  },
}