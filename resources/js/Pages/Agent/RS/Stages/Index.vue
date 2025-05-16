<template>
  <RSLayout>
    <template #header>
      <div class="flex items-center gap-4 mb-2">
        <div class="bg-blue-600 text-white rounded-lg w-14 h-14 flex items-center justify-center shadow-md">
          <DocumentTextIcon class="w-7 h-7" />
        </div>
        <div>
          <h1 class="text-2xl font-bold text-gray-800 leading-tight md:text-3xl">Suivi des Stages</h1>
          <p class="text-sm text-gray-500 mt-1">
            Structure: <span class="font-semibold">{{ structure.libelle }}</span>
          </p>
        </div>
      </div>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
          <!-- En-tête de la carte avec style cohérent -->
          <div class="border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-800 py-4 px-6">
            <h2 class="font-medium text-white text-lg">Stages de votre structure</h2>
          </div>

          <div class="p-6">
            <!-- Filtres améliorés -->
            <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-100">
              <form @submit.prevent="applyFilters" class="flex flex-col md:flex-row flex-wrap gap-4">
                <div class="w-full md:w-auto">
                  <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                  <select
                    v-model="filters.status"
                    id="status"
                    class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-20 text-sm px-3 py-2"
                  >
                    <option value="all">Tous les statuts</option>
                    <option value="En cours">En cours</option>
                    <option value="Terminé">Terminé</option>
                  </select>
                </div>

                <div class="w-full md:w-auto flex-grow">
                  <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                  <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                      <MagnifyingGlassIcon class="h-4 w-4 text-gray-400" />
                    </div>
                    <input
                      v-model="filters.search"
                      id="search"
                      type="text"
                      placeholder="Rechercher par nom, prénom ou email du stagiaire"
                      class="w-full rounded-md border border-gray-300 pl-10 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-20 text-sm px-3 py-2"
                    />
                  </div>
                </div>

                <div class="w-full md:w-auto flex items-end">
                  <button
                    type="submit"
                    class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-md transition-all duration-200 shadow-sm flex items-center gap-2"
                  >
                    <FunnelIcon class="w-4 h-4" />
                    <span>Filtrer</span>
                  </button>
                </div>
              </form>
            </div>

            <!-- Tableau des stages avec design amélioré -->
            <div class="overflow-x-auto border border-gray-100 rounded-lg">
              <table class="min-w-full divide-y divide-gray-200 text-xs md:text-sm">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="px-2 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Stagiaire
                    </th>
                    <th scope="col" class="px-2 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Structure
                    </th>
                    <th scope="col" class="px-2 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Période
                    </th>
                    <th scope="col" class="px-2 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Statut
                    </th>
                    <th scope="col" class="px-2 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Maître de Stage
                    </th>
                    <th scope="col" class="px-2 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Actions
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="stage in stages.data" :key="stage.id" class="hover:bg-gray-50 transition-colors">
                    <td class="px-2 md:px-6 py-2 md:py-4 whitespace-nowrap">
                      <div v-if="stage.demande_stage?.stagiaire?.user" class="flex items-start gap-3">
                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                          <UserIcon class="h-5 w-5 text-blue-600" />
                        </div>
                        <div>
                          <div class="text-sm font-medium text-gray-800">
                            {{ stage.demande_stage.stagiaire.user.nom }} {{ stage.demande_stage.stagiaire.user.prenom }}
                          </div>
                          <div class="text-xs text-gray-500">{{ stage.demande_stage.stagiaire.user.email }}</div>
                        </div>
                      </div>
                      <div v-else class="text-gray-400 italic">Non disponible</div>
                    </td>
                    
                    <td class="px-2 md:px-6 py-2 md:py-4 whitespace-nowrap">
                      <div v-if="stage.structure" class="text-sm text-gray-700">
                        {{ stage.structure.libelle }}
                        <div class="text-xs text-gray-500">{{ stage.structure.sigle }}</div>
                      </div>
                      <div v-else class="text-gray-400 italic text-sm">Non assignée</div>
                    </td>
                    
                    <td class="px-2 md:px-6 py-2 md:py-4 whitespace-nowrap">
                      <div v-if="stage.date_debut && stage.date_fin" class="text-sm text-gray-700">
                        <div class="flex items-center gap-1">
                          <CalendarIcon class="w-4 h-4 text-gray-400" />
                          <span>{{ formatDate(stage.date_debut) }}</span>
                        </div>
                        <div class="flex items-center gap-1 mt-1">
                          <CalendarIcon class="w-4 h-4 text-gray-400" />
                          <span>{{ formatDate(stage.date_fin) }}</span>
                        </div>
                      </div>
                      <div v-else class="text-gray-400 italic text-sm">Dates non définies</div>
                    </td>
                    
                    <td class="px-2 md:px-6 py-2 md:py-4 whitespace-nowrap">
                      <span
                        class="px-3 py-1 text-xs font-medium rounded-full inline-flex items-center gap-1"
                        :class="getStatusColor(stage.statut)"
                      >
                        <span v-if="stage.statut === 'En cours'" class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 bg-blue-400"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                        </span>
                        {{ stage.statut }}
                      </span>
                    </td>
                    
                    <td class="px-2 md:px-6 py-2 md:py-4 whitespace-nowrap">
                      <div v-if="getCurrentMaitreStage(stage)" class="text-sm text-gray-700">
                        {{ getCurrentMaitreStage(stage).nom }} {{ getCurrentMaitreStage(stage).prenom }}
                      </div>
                      <div v-else class="text-gray-400 italic text-sm">Non assigné</div>
                    </td>
                    
                    <td class="px-2 md:px-6 py-2 md:py-4 whitespace-nowrap text-sm font-medium">
                      <Link
                        :href="route('agent.rs.stages.show', stage.id)"
                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-md hover:bg-blue-100 transition-colors"
                      >
                        <EyeIcon class="w-4 h-4" />
                        Détails
                      </Link>
                    </td>
                  </tr>
                  <tr v-if="stages.data.length === 0">
                    <td colspan="6" class="px-6 py-10 text-center">
                      <div class="flex flex-col items-center justify-center">
                        <FolderOpenIcon class="w-12 h-12 text-gray-300 mb-2" />
                        <p class="text-gray-500 text-sm">Aucun stage trouvé avec les critères sélectionnés</p>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
              <Pagination :links="stages.links" />
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

// Formater une date
function formatDate(date) {
  return new Date(date).toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });
}

// Obtenir la couleur du statut
function getStatusColor(status) {
  switch (status) {
    case 'En cours':
      return 'bg-blue-100 text-blue-700';
    case 'Terminé':
      return 'bg-emerald-100 text-emerald-700';
    default:
      return 'bg-gray-100 text-gray-700';
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