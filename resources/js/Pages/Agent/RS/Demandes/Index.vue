<template>
  <Head title="Gestion des Demandes" />

  <RSLayout>
    <template #header>
      <div class="flex items-center gap-4 mb-2">
        <div class="bg-blue-600 text-white rounded-lg w-14 h-14 flex items-center justify-center shadow-md">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
        </div>
        <div>
          <h1 class="text-2xl font-bold text-gray-800 leading-tight md:text-3xl">Demandes de Stage</h1>
          <p class="text-sm text-gray-500 mt-1">{{ structure.sigle }} - {{ structure.libelle }}</p>
        </div>
      </div>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Messages flash -->
        <div v-if="$page.props.flash.success" class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-md shadow-sm flex items-start" role="alert">
          <div class="mr-3 text-emerald-500 mt-0.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
          </div>
          <div>
            <p class="font-medium">Succès!</p>
            <p>{{ $page.props.flash.success }}</p>
          </div>
        </div>
        
        <div v-if="$page.props.flash.error" class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-md shadow-sm flex items-start" role="alert">
          <div class="mr-3 text-red-500 mt-0.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
          </div>
          <div>
            <p class="font-medium">Erreur!</p>
            <p>{{ $page.props.flash.error }}</p>
          </div>
        </div>

        <!-- Filtres -->
        <div class="bg-white shadow-md rounded-lg mb-8 overflow-hidden">
          <div class="border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-800 py-4 px-6">
            <h3 class="text-lg font-semibold text-white flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
              </svg>
              Filtres de recherche
            </h3>
          </div>
          
          <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
              <!-- Filtre par statut -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <div class="relative">
                  <select v-model="filters.status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-20 text-gray-700">
                    <option value="">Tous les statuts</option>
                    <option value="En attente">En attente</option>
                    <option value="Approuvée">Approuvée</option>
                    <option value="Refusée">Refusée</option>
                  </select>
                  <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                  </div>
                </div>
              </div>
              
              <!-- Recherche par nom -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Rechercher</label>
                <div class="relative">
                  <input v-model="filters.search" type="text" placeholder="Nom, prénom ou email..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-20 pl-10 text-gray-700" />
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                  </div>
                  <div v-if="isLoading" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                  </div>
                </div>
              </div>
              
              <!-- Filtre par date -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Période</label>
                <div class="relative">
                  <select v-model="filters.period" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-20 text-gray-700">
                    <option value="">Toutes les périodes</option>
                    <option value="today">Aujourd'hui</option>
                    <option value="week">Cette semaine</option>
                    <option value="month">Ce mois</option>
                    <option value="year">Cette année</option>
                  </select>
                  <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                  </div>
                </div>
              </div>
              
              <!-- Bouton de réinitialisation -->
              <div class="flex items-end">
                <button @click="resetFilters" class="w-full px-4 py-2 bg-gray-50 text-gray-700 rounded-md border border-gray-300 hover:bg-gray-100 transition-colors flex items-center justify-center gap-2 font-medium" title="Réinitialiser tous les filtres">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                  </svg>
                  Réinitialiser
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Liste des demandes -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
          <div class="border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-800 py-4 px-6">
            <h3 class="text-lg font-semibold text-white flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
              </svg>
              Liste des demandes
            </h3>
          </div>
          
          <div class="p-6">
            <div v-if="props.demandes.data.length === 0" class="py-12 px-6 text-center">
              <div class="bg-blue-50 rounded-lg p-8 max-w-md mx-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-blue-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-700 mb-1">Aucune demande trouvée</h3>
                <p class="text-sm text-gray-500">
                  {{ filters.status || filters.search || filters.period ? 'Aucune demande ne correspond à vos critères de recherche.' : 'Aucune demande de stage n\'a été soumise pour votre structure.' }}
                </p>
              </div>
            </div>
            <div v-else>
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Stagiaire</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date de soumission</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date d'affectation</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Statut</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="demande in props.demandes.data" :key="demande.id" class="hover:bg-gray-50 transition">
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                          <div class="flex-shrink-0 h-10 w-10">
                            <div class="h-10 w-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-medium shadow-sm">
                              {{ demande.stagiaire.user.nom.charAt(0) }}{{ demande.stagiaire.user.prenom.charAt(0) }}
                            </div>
                          </div>
                          <div class="ml-4">
                            <div class="text-sm font-semibold text-gray-900">
                              {{ demande.stagiaire.user.nom }} {{ demande.stagiaire.user.prenom }}
                            </div>
                            <div class="text-xs text-gray-500">
                              {{ demande.stagiaire.user.email }}
                            </div>
                          </div>
                        </div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                          {{ formatDate(demande.date_soumission || demande.created_at) }}
                        </div>
                        <div class="text-xs text-gray-500">
                          {{ formatTime(demande.date_soumission || demande.created_at) }}
                        </div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div v-if="getAffectationDate(demande)" class="text-sm text-gray-900">
                          {{ formatDate(getAffectationDate(demande)) }}
                        </div>
                        <div v-if="getAffectationDate(demande)" class="text-xs text-gray-500">
                          {{ formatTime(getAffectationDate(demande)) }}
                        </div>
                        <div v-else class="text-xs text-gray-400 italic">
                          Non affectée
                        </div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <span :class="getStatusClass(demande.statut) + ' px-3 py-1 rounded-full text-xs font-medium inline-flex items-center'">
                          <span class="h-1.5 w-1.5 rounded-full mr-1.5" :class="getStatusDotClass(demande.statut)"></span>
                          {{ demande.statut }}
                        </span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <Link
                          :href="route('agent.rs.demandes.show', demande.id)"
                          class="text-blue-600 hover:text-blue-800 font-medium transition flex items-center gap-1 group"
                        >
                          <span>Détails</span>
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                          </svg>
                        </Link>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- Pagination -->
              <div class="mt-8 flex justify-center">
                <Pagination :links="props.demandes.links" @change="handlePageChange" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </RSLayout>
</template>

<script setup>
import { ref, watch, onMounted, computed } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import RSLayout from '@/Layouts/RSLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import debounce from 'lodash/debounce';

const props = defineProps({
  demandes: Object,
  structure: Object,
  filters: Object
});

const isLoading = ref(false);
const filters = ref({
  status: props.filters?.status || '',
  search: props.filters?.search || '',
  period: props.filters?.period || '',
  page: props.filters?.page || '1',
});

// Ajoutons les fonctions utilitaires
function getStatusClass(status) {
  switch (status) {
    case 'En attente':
      return 'bg-amber-50 text-amber-700';
    case 'En cours':
      return 'bg-blue-50 text-blue-700';
    case 'Approuvée':
    case 'Acceptée':
      return 'bg-emerald-50 text-emerald-700';
    case 'Refusée':
      return 'bg-red-50 text-red-700';
    default:
      return 'bg-gray-50 text-gray-700';
  }
}

function getStatusDotClass(status) {
  switch (status) {
    case 'En attente':
      return 'bg-amber-500';
    case 'En cours':
      return 'bg-blue-500';
    case 'Approuvée':
    case 'Acceptée':
      return 'bg-emerald-500';
    case 'Refusée':
      return 'bg-red-500';
    default:
      return 'bg-gray-500';
  }
}

function formatDate(date) {
  return new Date(date).toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });
}

function formatTime(date) {
  return new Date(date).toLocaleTimeString('fr-FR', {
    hour: '2-digit',
    minute: '2-digit',
  });
}

function getAffectationDate(demande) {
  return demande.date_affectation || null;
}

function resetFilters() {
  filters.value = {
    status: '',
    search: '',
    period: '',
    page: '1',
  };
}

// Restons fidèle aux fonctions existantes
function handlePageChange(page) {
  filters.value.page = page.toString();
}

// Utilisation de debounce pour la recherche
const debouncedSearch = debounce(() => {
  isLoading.value = true;
  router.get(route('agent.rs.demandes'), filters.value, {
    preserveState: true,
    preserveScroll: true,
    onFinish: () => {
      isLoading.value = false;
    }
  });
}, 500);

// Observer les changements de filtres
watch(() => [filters.value.status, filters.value.search, filters.value.period], () => {
  filters.value.page = '1';
  debouncedSearch();
}, { deep: true });

// Lorsque la page change
watch(() => filters.value.page, () => {
  router.get(route('agent.rs.demandes'), filters.value, {
    preserveState: true,
    preserveScroll: true,
  });
});
</script>