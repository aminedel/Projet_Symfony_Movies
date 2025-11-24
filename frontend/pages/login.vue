<template>
    <div class="container mt-5">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="card">
            <div class="card-header bg-primary text-white">Connexion</div>
            <div class="card-body">
              <form @submit.prevent="login">
                <div class="mb-3">
                  <label class="form-label">Email</label>
                  <input v-model="email" type="email" class="form-control" placeholder="admin@test.com" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Mot de passe</label>
                  <input v-model="password" type="password" class="form-control" placeholder="password" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Se connecter</button>
              </form>
              <p v-if="error" class="text-danger mt-3 text-center">{{ error }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import axios from 'axios'
  const email = ref('admin@test.com') // Pré-rempli pour aller vite
  const password = ref('password')
  const error = ref('')
  const router = useRouter()
  const config = useRuntimeConfig()
  
  const login = async () => {
    try {
      // On appelle la route de connexion de Symfony
      const response = await axios.post(`${config.public.apiBase}/login_check`, {
        email: email.value,
        password: password.value
      })
      
      // On stocke le Token de sécurité dans le navigateur
      localStorage.setItem('token', response.data.token)
      
      // On redirige vers l'accueil
      router.push('/')
    } catch (e) {
      error.value = "Erreur de connexion : Vérifie tes identifiants"
    }
  }
  </script>