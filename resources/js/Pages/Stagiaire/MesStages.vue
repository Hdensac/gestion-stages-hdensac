<template>
  <Head title="Mes Stages" />
  <Stagiaire :notifications="notifications">
    <template #header>
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
          <div class="relative">
            <div class="absolute inset-0 bg-indigo-400 rounded-lg blur opacity-25 animate-pulse"></div>
            <div class="relative bg-gradient-to-br from-indigo-500 to-indigo-600 p-3 rounded-lg shadow-lg">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2-2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
            </div>
          </div>
          <div>
            <h2 class="text-2xl font-bold bg-gradient-to-r from-indigo-700 to-purple-700 bg-clip-text text-transparent">
              Mes Stages
            </h2>
            <p class="text-sm text-indigo-600/70 font-medium">Suivez vos stages et progressions</p>
          </div>
        </div>
      </div>
    </template>

    <div class="py-8">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <!-- Messages d'alerte modernisés -->
        <Transition name="slide-fade" appear>
          <div v-if="error" class="mb-8 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-red-500/10 to-pink-500/10 rounded-2xl"></div>
            <div class="relative bg-white/80 backdrop-blur-sm border border-red-200/50 rounded-2xl p-6 shadow-lg">
              <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                  <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                </div>
                <div>
                  <h3 class="text-lg font-semibold text-red-800 mb-1">Une erreur s'est produite</h3>
                  <p class="text-red-700">{{ error }}</p>
                </div>
              </div>
            </div>
          </div>
        </Transition>

        <Transition name="slide-fade" appear>
          <div v-if="message" class="mb-8 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-500/10 to-indigo-500/10 rounded-2xl"></div>
            <div class="relative bg-white/80 backdrop-blur-sm border border-blue-200/50 rounded-2xl p-6 shadow-lg">
              <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                  <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                </div>
                <div>
                  <h3 class="text-lg font-semibold text-blue-800 mb-1">Information</h3>
                  <p class="text-blue-700">{{ message }}</p>
                </div>
              </div>
            </div>
          </div>
        </Transition>

        <!-- État vide modernisé -->
        <Transition name="fade-scale" appear>
          <div v-if="stages.length === 0 && !error && !message" class="relative">
            <!-- Background décoratif -->
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 via-white to-purple-50 rounded-3xl"></div>
            <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-indigo-200/30 to-purple-200/30 rounded-full -translate-y-32 translate-x-32 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-gradient-to-tr from-blue-200/30 to-indigo-200/30 rounded-full translate-y-24 -translate-x-24 blur-3xl"></div>
            
            <div class="relative bg-white/60 backdrop-blur-sm rounded-3xl border border-indigo-100/50 shadow-xl p-12 text-center">
              <!-- Animation de l'icône -->
              <div class="relative mx-auto mb-8 w-32 h-32">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full animate-pulse opacity-20"></div>
                <div class="absolute inset-2 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center">
                  <svg class="w-16 h-16 text-white animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                  </svg>
                </div>
              </div>
              
              <div class="space-y-4 mb-8">
                <h3 class="text-2xl font-bold bg-gradient-to-r from-indigo-700 to-purple-700 bg-clip-text text-transparent">
                  Aucun stage en cours
                </h3>
                <p class="text-gray-600 text-lg max-w-md mx-auto leading-relaxed">
                  Vous n'avez pas encore de stage actif. Vos demandes approuvées apparaîtront ici automatiquement.
                </p>
              </div>
              
              <Link :href="route('mes.demandes')" 
                class="group relative inline-flex items-center px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 border border-transparent rounded-2xl font-semibold text-white shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                <span class="absolute inset-0 bg-gradient-to-r from-indigo-700 to-purple-700 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                <span class="relative flex items-center space-x-3">
                  <svg class="w-5 h-5 transform group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                  <span>Voir mes demandes</span>
                </span>
              </Link>
            </div>
          </div>
        </Transition>

        <!-- Liste des stages modernisée -->
        <div v-if="stages.length > 0" class="space-y-8">
          <!-- En-tête avec statistiques -->
          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
              <h3 class="text-2xl font-bold text-gray-900 mb-2">Vos stages</h3>
              <div class="flex items-center space-x-4 text-sm">
                <div class="flex items-center space-x-2">
                  <div class="w-3 h-3 bg-blue-400 rounded-full animate-pulse"></div>
                  <span class="text-gray-600">{{ stages.filter(s => s.statut_calculé === 'En cours').length }} en cours</span>
                </div>
                <div class="flex items-center space-x-2">
                  <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                  <span class="text-gray-600">{{ stages.filter(s => s.statut_calculé === 'Terminé').length }} terminé(s)</span>
                </div>
                <div class="flex items-center space-x-2">
                  <div class="w-3 h-3 bg-yellow-400 rounded-full"></div>
                  <span class="text-gray-600">{{ stages.filter(s => s.statut_calculé === 'À venir').length }} à venir</span>
                </div>
              </div>
            </div>
            
            <div class="flex items-center space-x-3">
              <div class="bg-gradient-to-r from-indigo-500 to-purple-500 text-white px-4 py-2 rounded-full shadow-lg">
                <span class="font-semibold">{{ stages.length }}</span>
                <span class="ml-1 text-sm opacity-90">stage{{ stages.length > 1 ? 's' : '' }}</span>
              </div>
            </div>
          </div>

          <!-- Grille des cartes stages -->
          <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
            <TransitionGroup name="stagger-fade" appear>
              <div v-for="(stage, index) in stages" :key="stage.id" 
                :style="{ '--delay': index * 100 + 'ms' }"
                class="group relative">
                
                <!-- Effet de halo au hover -->
                <div class="absolute -inset-0.5 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-3xl opacity-0 group-hover:opacity-20 blur transition-all duration-500"></div>
                
                <div class="relative bg-white rounded-3xl shadow-lg border border-gray-100/50 overflow-hidden transform group-hover:-translate-y-2 group-hover:shadow-2xl transition-all duration-500">
                  <!-- En-tête avec gradient -->
                  <div class="relative bg-gradient-to-br from-indigo-50 via-white to-purple-50 px-6 py-5 border-b border-indigo-100/50">
                    <!-- Décoration background -->
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-indigo-200/20 to-purple-200/20 rounded-full -translate-y-16 translate-x-16"></div>
                    
                    <div class="relative flex justify-between items-start">
                      <div class="flex-1 min-w-0">
                        <h4 class="font-bold text-lg text-indigo-900 truncate mb-1">
                          {{ stage.structure?.libelle || 'Structure non spécifiée' }}
                        </h4>
                        <div class="flex items-center space-x-2">
                          <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0H8m8 0v6l-3-2-3 2V6" />
                          </svg>
                          <p class="text-sm font-medium text-indigo-700">{{ stage.type || 'Type non spécifié' }}</p>
                        </div>
                      </div>
                      
                      <div class="ml-4 flex-shrink-0">
                        <span class="inline-flex items-center px-3 py-1.5 text-xs font-bold rounded-full shadow-sm"
                          :class="{
                            'bg-gradient-to-r from-green-400 to-green-500 text-white': stage.statut_calculé === 'Terminé',
                            'bg-gradient-to-r from-blue-400 to-blue-500 text-white animate-pulse': stage.statut_calculé === 'En cours',
                            'bg-gradient-to-r from-yellow-400 to-yellow-500 text-white': stage.statut_calculé === 'À venir',
                          }">
                          <div class="w-1.5 h-1.5 rounded-full bg-white/80 mr-2"></div>
                          {{ stage.statut_calculé }}
                        </span>
                      </div>
                    </div>

                    <!-- Barre de progression pour les stages en cours -->
                    <div v-if="stage.statut_calculé === 'En cours'" class="mt-4">
                      <div class="flex justify-between text-xs text-indigo-600 mb-1">
                        <span>Progression</span>
                        <span>{{ getProgressPercentage(stage) }}%</span>
                      </div>
                      <div class="w-full bg-indigo-100 rounded-full h-2 overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-400 to-purple-500 h-2 rounded-full transition-all duration-1000 ease-out"
                          :style="{ width: getProgressPercentage(stage) + '%' }"></div>
                      </div>
                    </div>
                  </div>

                  <!-- Corps de la carte -->
                  <div class="p-6 space-y-5">
                    <!-- Période avec timeline -->
                    <div class="bg-gray-50/50 rounded-2xl p-4">
                      <div class="flex items-center space-x-2 mb-3">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-sm font-semibold text-gray-700">Période</p>
                      </div>
                      <div class="flex items-center justify-between text-sm">
                        <div class="bg-white rounded-lg px-3 py-2 font-medium text-gray-800 shadow-sm">
                          {{ formatDate(stage.date_debut) }}
                        </div>
                        <div class="flex-1 mx-3">
                          <div class="border-t-2 border-dashed border-indigo-200"></div>
                        </div>
                        <div class="bg-white rounded-lg px-3 py-2 font-medium text-gray-800 shadow-sm">
                          {{ formatDate(stage.date_fin) }}
                        </div>
                      </div>
                    </div>

                    <!-- Maître de stage -->
                    <div>
                      <div class="flex items-center space-x-2 mb-2">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <p class="text-sm font-semibold text-gray-700">Maître de stage</p>
                      </div>
                      <div v-if="stage.maitre_stage_actuel" class="flex items-center space-x-3 bg-indigo-50/50 rounded-xl p-3">
                        <div class="w-8 h-8 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full flex items-center justify-center">
                          <span class="text-white text-sm font-bold">
                            {{ stage.maitre_stage_actuel.prenom?.charAt(0) }}{{ stage.maitre_stage_actuel.nom?.charAt(0) }}
                          </span>
                        </div>
                        <div>
                          <p class="font-medium text-gray-800">
                            {{ stage.maitre_stage_actuel.prenom }} {{ stage.maitre_stage_actuel.nom }}
                          </p>
                        </div>
                      </div>
                      <div v-else class="flex items-center space-x-3 bg-gray-50 rounded-xl p-3">
                        <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                          <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                          </svg>
                        </div>
                        <p class="text-gray-500 italic">Non assigné</p>
                      </div>
                    </div>

                    <!-- Thème -->
                    <div>
                      <div class="flex items-center space-x-2 mb-2">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                        <p class="text-sm font-semibold text-gray-700">Thème</p>
                      </div>
                      <div v-if="stage.themeStage" class="bg-white rounded-xl border border-gray-100 p-4">
                        <p class="font-medium text-gray-800 mb-2">{{ stage.themeStage.intitule }}</p>
                        <span class="inline-flex items-center px-2.5 py-1 text-xs font-bold rounded-lg"
                          :class="{
                            'bg-gradient-to-r from-green-400 to-green-500 text-white': stage.themeStage.etat === 'Validé',
                            'bg-gradient-to-r from-yellow-400 to-yellow-500 text-white': stage.themeStage.etat === 'Proposé',
                            'bg-gradient-to-r from-red-400 to-red-500 text-white': stage.themeStage.etat === 'Refusé',
                          }">
                          {{ stage.themeStage.etat }}
                        </span>
                      </div>
                      <div v-else class="bg-gray-50 rounded-xl p-4 text-center">
                        <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <p class="text-gray-500 text-sm italic">Aucun thème proposé</p>
                      </div>
                    </div>
                  </div>

                  <!-- Action button -->
                  <div class="px-6 pb-6">
                    <Link :href="route('stagiaire.stages.show', stage.id)" 
                      class="group/btn w-full flex items-center justify-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 border border-transparent rounded-2xl font-semibold text-sm text-white shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300">
                      <span class="mr-2">Voir les détails</span>
                      <svg class="w-4 h-4 transform group-hover/btn:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                      </svg>
                    </Link>
                  </div>
                </div>
              </div>
            </TransitionGroup>
          </div>
        </div>
      </div>
    </div>
  </Stagiaire>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Stagiaire from '@/Layouts/Stagiaire.vue';

const props = defineProps({
  stages: {
    type: Array,
    default: () => []
  },
  notifications: {
    type: Array,
    default: () => []
  },
  error: {
    type: String,
    default: null
  },
  message: {
    type: String,
    default: null
  }
});

// Formater une date
const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  const date = new Date(dateString);
  return date.toLocaleDateString('fr-FR', {
    day: '2-digit',
    month: 'short',
    year: 'numeric'
  });
};

// Calculer le pourcentage de progression d'un stage
const getProgressPercentage = (stage) => {
  if (!stage.date_debut || !stage.date_fin) return 0;
  
  const startDate = new Date(stage.date_debut);
  const endDate = new Date(stage.date_fin);
  const currentDate = new Date();
  
  if (currentDate < startDate) return 0;
  if (currentDate > endDate) return 100;
  
  const totalDuration = endDate - startDate;
  const elapsed = currentDate - startDate;
  
  return Math.round((elapsed / totalDuration) * 100);
};
</script>

<style scoped>
/* Animations personnalisées */
.slide-fade-enter-active {
  transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}

.slide-fade-enter-from {
  transform: translateY(-20px);
  opacity: 0;
}

.fade-scale-enter-active {
  transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
}

.fade-scale-enter-from {
  transform: scale(0.95);
  opacity: 0;
}

.stagger-fade-enter-active {
  transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
  transition-delay: var(--delay);
}

.stagger-fade-enter-from {
  transform: translateY(30px);
  opacity: 0;
}

/* Effet de glassmorphisme subtil */
.backdrop-blur-sm {
  -webkit-backdrop-filter: blur(12px);
  backdrop-filter: blur(12px);
}

/* Animation de bounce personnalisée plus subtile */
@keyframes gentle-bounce {
  0%, 20%, 53%, 80%, 100% {
    transform: translate3d(0,0,0);
  }
  40%, 43% {
    transform: translate3d(0, -8px, 0);
  }
  70% {
    transform: translate3d(0, -4px, 0);
  }
  90% {
    transform: translate3d(0, -2px, 0);
  }
}

.animate-bounce {
  animation: gentle-bounce 2s infinite;
}

/* Transition pour les groupes */
.stagger-fade-move,
.stagger-fade-enter-active,
.stagger-fade-leave-active {
  transition: all 0.5s cubic-bezier(0.55, 0, 0.1, 1);
}

.stagger-fade-leave-active {
  position: absolute;
}
</style>