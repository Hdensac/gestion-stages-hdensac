<template>
  <RSLayout>
    <template #header>
      <h2 class="text-xl font-semibold text-gray-800">Suivi des Stages</h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
          <div class="p-6">
            <div class="mb-6 flex justify-between items-center">
              <h1 class="text-2xl font-bold text-gray-800">Stages de votre structure</h1>
              <div class="text-sm text-gray-600">
                Structure: <span class="font-semibold">{{ structure.libelle }}</span>
              </div>
            </div>

            <!-- Filtres -->
            <div class="mb-6 bg-gray-50 p-4 rounded-lg">
              <form @submit.prevent="applyFilters" class="flex flex-wrap gap-4">
                <div class="w-full md:w-auto">
                  <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                  <select
                    v-model="filters.status"
                    id="status"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                  >
                    <option value="all">Tous les statuts</option>
                    <option value="En cours">En cours</option>
                    <option value="Terminé">Terminé</option>
                  </select>
                </div>

                <div class="w-full md:w-auto flex-grow">
                  <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                  <input
                    v-model="filters.search"
                    id="search"
                    type="text"
                    placeholder="Rechercher par nom, prénom ou email du stagiaire"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                  />
                </div>

                <div class="w-full md:w-auto flex items-end">
                  <button
                    type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
                  >
                    Filtrer
                  </button>
                </div>
              </form>
            </div>

            <!-- Tableau des stages -->
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                   <!--  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      ID
                    </th> -->
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Stagiaire
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Structure
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Période
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Statut
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Maître de Stage Actuel
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Actions
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="stage in stages.data" :key="stage.id" class="hover:bg-gray-50">
                    <!-- <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                      {{ stage.id }}
                    </td> -->
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      <div v-if="stage.demande_stage?.stagiaire?.user">
                        {{ stage.demande_stage.stagiaire.user.nom }} {{ stage.demande_stage.stagiaire.user.prenom }}
                        <div class="text-xs text-gray-400">{{ stage.demande_stage.stagiaire.user.email }}</div>
                      </div>
                      <div v-else class="text-gray-400 italic">Non disponible</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      <div v-if="stage.structure">
                        {{ stage.structure.libelle }}
                        <div class="text-xs text-gray-400">{{ stage.structure.sigle }}</div>
                      </div>
                      <div v-else class="text-gray-400 italic">Non assignée</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      <div v-if="stage.date_debut && stage.date_fin">
                        Du {{ formatDate(stage.date_debut) }} au {{ formatDate(stage.date_fin) }}
                      </div>
                      <div v-else class="text-gray-400 italic">Dates non définies</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span
                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                        :class="getStatusColor(stage.statut)"
                      >
                        {{ stage.statut }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      <div v-if="getCurrentMaitreStage(stage)">
                        {{ getCurrentMaitreStage(stage).nom }} {{ getCurrentMaitreStage(stage).prenom }}
                      </div>
                      <div v-else class="text-gray-400 italic">Non assigné</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <Link
                        :href="route('agent.rs.stages.show', stage.id)"
                        class="text-blue-600 hover:text-blue-900 mr-3"
                      >
                        Détails
                      </Link>
                    </td>
                  </tr>
                  <tr v-if="stages.data.length === 0">
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                      Aucun stage trouvé
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
      return 'bg-blue-100 text-blue-800';
    case 'Terminé':
      return 'bg-green-100 text-green-800';
    default:
      return 'bg-gray-100 text-gray-800';
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
