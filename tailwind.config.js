/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './app/**/*.{php,html}',    // Inclut tous les fichiers PHP et HTML dans le dossier app
    './app/styles.css',         // Inclut votre fichier CSS principal si vous utilisez des classes Tailwind dedans
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}

