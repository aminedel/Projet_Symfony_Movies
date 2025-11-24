<template>
    <div class="container mt-5">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>🎬 Liste des Films</h1>
        <NuxtLink to="/login" class="btn btn-outline-primary">Connexion Admin</NuxtLink>
      </div>
  
      <div v-if="loading" class="text-center">Chargement...</div>
  
      <div v-else class="row">
        <div v-for="movie in movies" :key="movie.id" class="col-md-4 mb-4">
          <div class="card h-100 shadow-sm">
            <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Affiche">
            
            <div class="card-body">
              <div v-if="!movie.isEditing">
                <h5 class="card-title fw-bold">{{ movie.title }}</h5>
                <p class="card-text text-muted">{{ movie.description?.substring(0, 100) }}...</p>
                <span class="badge bg-info mb-2" v-if="movie.category">{{ movie.category.name }}</span>
                
                <div class="mt-2">
                  <button @click="activerEdition(movie)" class="btn btn-warning btn-sm w-100">
                    ✏️ Modifier Titre (Exercice)
                  </button>
                </div>
              </div>
  
              <div v-else>
                <label class="form-label">Nouveau titre :</label>
                <input v-model="movie.tempTitle" class="form-control mb-2">
                <div class="d-flex gap-2">
                  <button @click="sauvegarder(movie)" class="btn btn-success btn-sm flex-grow-1">Valider</button>
                  <button @click="movie.isEditing = false" class="btn btn-secondary btn-sm">Annuler</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
import axios from 'axios'
const config = useRuntimeConfig()
const movies = ref([])
const loading = ref(true)
const searchQuery = ref('')

const chargerFilms = async (recherche = '') => {
  loading.value = true
  try {
    const url = recherche 
      ? `${config.public.apiBase}/movies?title=${recherche}`
      : `${config.public.apiBase}/movies`
      
    const res = await axios.get(url)
    
    // --- CORRECTION ICI ---
    // On cherche la liste dans 'hydra:member', ou 'member', ou directement dans data
    const rawData = res.data['hydra:member'] || res.data.member || res.data
    
    // Sécurité : on s'assure que c'est bien une liste (tableau)
    const data = Array.isArray(rawData) ? rawData : []
    // ----------------------

    movies.value = data.map(m => ({
      ...m, 
      isEditing: false,
      tempTitle: m.title
    }))
  } catch (e) {
    console.error("Erreur API:", e)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  chargerFilms()
})

const rechercher = () => {
  chargerFilms(searchQuery.value)
}

const activerEdition = (movie) => {
  movie.isEditing = true
  movie.tempTitle = movie.title
}

const sauvegarder = async (movie) => {
  const token = localStorage.getItem('token')
  if (!token) {
    alert("Connexion requise !")
    return navigateTo('/login')
  }
  try {
    await axios.patch(`${config.public.apiBase}/movies/${movie.id}`, 
      { title: movie.tempTitle },
      { headers: { Authorization: `Bearer ${token}`, 'Content-Type': 'application/merge-patch+json' }}
    )
    movie.title = movie.tempTitle
    movie.isEditing = false
  } catch (e) {
    alert("Erreur sauvegarde")
  }
}
</script>