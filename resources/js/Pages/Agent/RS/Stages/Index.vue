<template>
  <RSLayout>
    <template #header>
      <div class="flex items-center gap-4 mb-2">
        <div class="bg-gradient-to-br from-blue-600 to-blue-700 text-white rounded-xl w-16 h-16 flex items-center justify-center shadow-lg transform transition-transform hover:scale-105">
          <DocumentTextIcon class="w-8 h-8" />
        </div>
        <div>
          <h1 class="text-2xl font-bold text-gray-800 leading-tight md:text-3xl tracking-tight">Suivi des Stages</h1>
          <p class="text-sm text-gray-600 mt-1 font-medium">
            Structure: <span class="font-semibold text-blue-700">{{ structure.libelle }}</span>
          </p>
        </div>
      </div>
    </template>

    <!-- Fond gris léger pour toute la page -->
    <div class="min-h-screen bg-gray-50 py-8">
      <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Carte principale avec design moderne -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100/50 backdrop-blur-sm">
          <!-- En-tête avec gradient amélioré -->
          <div class="bg-gradient-to-r from-blue-600 via-blue-700 to-blue-800 py-6 px-8 relative overflow-hidden">
            <div class="absolute inset-0 bg-black/5"></div>
            <div class="relative">
              <h2 class="font-semibold text-white text-xl tracking-wide flex items-center gap-3">
                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                  <DocumentTextIcon class="w-5 h-5" />
                </div>
                Stages de votre structure
              </h2>
              <p class="text-blue-100 text-sm mt-1 opacity-90">Gérez et suivez tous les stages en temps réel</p>
            </div>
          </div>

          <div class="p-8">
            <!-- Filtres avec style glassmorphisme moderne -->
            <div class="mb-8">
              <div class="bg-gradient-to-r from-gray-50 to-gray-100/80 backdrop-blur-xl p-6 rounded-2xl border border-gray-200/50 shadow-sm">
                <form @submit.prevent="applyFilters" class="flex flex-col lg:flex-row flex-wrap gap-6">
                  <div class="w-full lg:w-auto min-w-[200px]">
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Statut du stage</label>
                    <select
                      v-model="filters.status"
                      id="status"
                      class="w-full rounded-xl border-0 bg-white shadow-sm ring-1 ring-gray-200 focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 text-sm px-4 py-3 transition-all duration-200 hover:shadow-md"
                    >
                      <option value="all">🔍 Tous les statuts</option>
                      <option value="En cours">🔄 En cours</option>
                      <option value="Terminé">✅ Terminé</option>
                    </select>
                  </div>

                  <div class="w-full lg:flex-1 min-w-[300px]">
                    <label for="search" class="block text-sm font-semibold text-gray-700 mb-2">Recherche avancée</label>
                    <div class="relative group">
                      <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-all duration-200 group-focus-within:text-blue-500">
                        <MagnifyingGlassIcon class="h-5 w-5 text-gray-400" />
                      </div>
                      <input
                        v-model="filters.search"
                        id="search"
                        type="text"
                        placeholder="Rechercher par nom, prénom ou email du stagiaire..."
                        class="w-full rounded-xl border-0 bg-white pl-12 pr-4 py-3 shadow-sm ring-1 ring-gray-200 focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 text-sm transition-all duration-200 hover:shadow-md placeholder-gray-400"
                      />
                    </div>
                  </div>

                  <div class="w-full lg:w-auto flex items-end">
                    <button
                      type="submit"
                      class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl transition-all duration-300 shadow-sm hover:shadow-lg flex items-center gap-2 font-medium transform hover:scale-105 focus:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                      <FunnelIcon class="w-5 h-5" />
                      <span>Filtrer les résultats</span>
                    </button>
                  </div>
                </form>
              </div>
            </div>

            <!-- Tableau moderne avec design amélioré -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
              <div class="overflow-x-auto">
                <table class="w-full min-w-full divide-y divide-gray-100">
                  <thead class="bg-gradient-to-r from-gray-50 to-gray-100/50">
                    <tr>
                      <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        <div class="flex items-center gap-2">
                          <UserIcon class="w-4 h-4" />
                          Stagiaire
                        </div>
                      </th>
                      <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        <div class="flex items-center gap-2">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                          </svg>
                          Structure
                        </div>
                      </th>
                      <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        <div class="flex items-center gap-2">
                          <CalendarIcon class="w-4 h-4" />
                          Période
                        </div>
                      </th>
                      <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        <div class="flex items-center gap-2">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                          </svg>
                          Statut
                        </div>
                      </th>
                      <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        <div class="flex items-center gap-2">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                          </svg>
                          Maître de Stage
                        </div>
                      </th>
                      <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        Actions
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-50">
                    <tr v-for="stage in stages.data" :key="stage.id" class="hover:bg-gray-50/50 transition-all duration-200 group">
                      <td class="px-6 py-5 whitespace-nowrap">
                        <div v-if="stage.demande_stage?.stagiaire?.user" class="flex items-center gap-4">
                          <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center flex-shrink-0 shadow-md group-hover:shadow-lg transition-all duration-200">
                            <UserIcon class="h-6 w-6 text-white" />
                          </div>
                          <div>
                            <div class="text-sm font-semibold text-gray-900 leading-tight">
                              {{ stage.demande_stage.stagiaire.user.nom }} {{ stage.demande_stage.stagiaire.user.prenom }}
                            </div>
                            <div class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                              </svg>
                              {{ stage.demande_stage.stagiaire.user.email }}
                            </div>
                          </div>
                        </div>
                        <div v-else class="text-gray-400 italic text-sm flex items-center gap-2">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                          </svg>
                          Informations non disponibles
                        </div>
                      </td>
                      
                      <td class="px-6 py-5 whitespace-nowrap">
                        <div v-if="stage.statut === 'En cours' && getCurrentMaitreStage(stage) && getCurrentMaitreStage(stage).structure && getCurrentMaitreStage(stage).structure.sigle" class="space-y-1">
                          <div class="text-sm font-medium text-gray-900">{{ getCurrentMaitreStage(stage).structure.sigle }}</div>
                          </div>
                        <div v-else-if="stage.structure" class="space-y-1">
                          <div class="text-sm font-medium text-gray-900">{{ stage.structure.sigle }}</div>
                        </div>
                        <div v-else class="text-gray-400 italic text-sm flex items-center gap-2">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                          </svg>
                          Structure non assignée
                        </div>
                      </td>
                      
                      <td class="px-6 py-5 whitespace-nowrap">
                        <div v-if="stage.date_debut && stage.date_fin" class="space-y-2">
                          <div class="flex items-center gap-2 text-sm text-gray-700">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            <span class="font-medium">{{ formatDate(stage.date_debut) }}</span>
                          </div>
                          <div class="flex items-center gap-2 text-sm text-gray-700">
                            <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                            <span class="font-medium">{{ formatDate(stage.date_fin) }}</span>
                          </div>
                        </div>
                        <div v-else class="text-gray-400 italic text-sm flex items-center gap-2">
                          <CalendarIcon class="w-4 h-4" />
                          Dates à définir
                        </div>
                      </td>
                      
                      <td class="px-6 py-5 whitespace-nowrap">
                        <div class="flex items-center">
                          <span
                            class="px-3 py-2 text-xs font-semibold rounded-xl inline-flex items-center gap-2 shadow-sm"
                            :class="getStatusStyle(stage.statut)"
                          >
                            <span v-if="stage.statut === 'En cours'" class="relative flex h-2 w-2">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 bg-blue-400"></span>
                              <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                            </span>
                            <span v-else-if="stage.statut === 'Terminé'" class="w-2 h-2 bg-green-500 rounded-full"></span>
                            <span v-else class="w-2 h-2 bg-gray-400 rounded-full"></span>
                            {{ stage.statut }}
                          </span>
                        </div>
                      </td>
                      
                      <td class="px-6 py-5 whitespace-nowrap">
                        <div v-if="getCurrentMaitreStage(stage)" class="flex items-center gap-3">
                          <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                          </div>
                          <div class="text-sm font-medium text-gray-900">
                            {{ getCurrentMaitreStage(stage).nom }} {{ getCurrentMaitreStage(stage).prenom }}
                          </div>
                        </div>
                        <div v-else class="text-gray-400 italic text-sm flex items-center gap-2">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                          </svg>
                          À assigner
                        </div>
                      </td>
                      
                      <td class="px-6 py-5 whitespace-nowrap">
                        <Link
                          :href="route('agent.rs.stages.show', stage.id)"
                          class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-50 to-blue-100 text-blue-700 rounded-xl hover:from-blue-100 hover:to-blue-200 transition-all duration-200 shadow-sm hover:shadow-md font-medium group transform hover:scale-105"
                        >
                          <EyeIcon class="w-4 h-4 group-hover:scale-110 transition-transform duration-200" />
                          <span>Voir détails</span>
                        </Link>
                      </td>
                    </tr>
                    <tr v-if="stages.data.length === 0">
                      <td colspan="6" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center justify-center space-y-4">
                          <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center">
                            <FolderOpenIcon class="w-10 h-10 text-gray-400" />
                          </div>
                          <div class="space-y-2">
                            <p class="text-gray-600 font-medium">Aucun stage trouvé</p>
                            <p class="text-gray-400 text-sm">Essayez de modifier vos critères de recherche</p>
                          </div>
                          <button
                            @click="resetFilters"
                            class="px-4 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors text-sm font-medium"
                          >
                            Réinitialiser les filtres
                          </button>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Pagination avec style moderne -->
            <div class="mt-8 flex justify-center">
              <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-2">
                <Pagination :links="stages.links" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </RSLayout>
</template>

<script setup>
import { ref, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import RSLayout from '@/Layouts/RSLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { 
  DocumentTextIcon, 
  MagnifyingGlassIcon, 
  FunnelIcon, 
  UserIcon, 
  CalendarIcon, 
  EyeIcon, 
  FolderOpenIcon 
} from '@heroicons/vue/24/outline';

const props = defineProps({
  stages: Object,
  structure: Object,
  filters: Object,
});

const filters = ref({
  status: props.filters.status || 'all',
  search: props.filters.search || '',
  page: props.filters.page || 1,
});

// Surveiller les changements de filtres pour mettre à jour l'URL
watch(filters.value, (newFilters) => {
  if (newFilters.page === 1) {
    delete newFilters.page;
  }
});

// Appliquer les filtres
function applyFilters() {
  router.get(route('agent.rs.stages'), filters.value, {
    preserveState: true,
    replace: true,
  });
}

// Réinitialiser les filtres
function resetFilters() {
  filters.value = {
    status: 'all',
    search: '',
    page: 1,
  };
  applyFilters();
}

// Formater une date avec un style plus moderne
function formatDate(date) {
  return new Date(date).toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  });
}

// Obtenir le style du statut avec des couleurs modernisées
function getStatusStyle(status) {
  switch (status) {
    case 'En cours':
      return 'bg-gradient-to-r from-blue-50 to-blue-100 text-blue-700 border border-blue-200';
    case 'Terminé':
      return 'bg-gradient-to-r from-emerald-50 to-emerald-100 text-emerald-700 border border-emerald-200';
    default:
      return 'bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700 border border-gray-200';
  }
}

// Obtenir le maître de stage actuel
function getCurrentMaitreStage(stage) {
  if (!stage.historique_affectations || stage.historique_affectations.length === 0) {
    return null;
  }
  
  // Trouver l'affectation active (la plus récente avec statut "En cours" ou "Acceptée")
  const activeAffectation = stage.historique_affectations.find(
    (aff) => aff.statut === 'En cours' || aff.statut === 'Acceptée'
  );
  
  return activeAffectation ? activeAffectation.maitre_stage : null;
}
</script>