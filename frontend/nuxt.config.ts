// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2024-04-03',
  devtools: { enabled: true },

  // 1. On ajoute le CSS de Bootstrap
  css: ['bootstrap/dist/css/bootstrap.min.css'],

  // 2. On configure l'adresse de ton Backend (Symfony)
  runtimeConfig: {
    public: {
      apiBase: 'http://127.0.0.1:8000/api' // juste /api, pas /movies
    }
  },

  // 3. Configuration Vite pour éviter le port WebSocket déjà utilisé
  vite: {
    server: {
      hmr: {
        port: 24679 // port libre
      }
    }
  }
})
