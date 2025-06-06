<template>
  <Head title="Gestion des Demandes" />

  <RSLayout>
    <template #header>
      <div class="flex items-center gap-4 mb-2">
        <div class="bg-gradient-to-br from-blue-600 to-blue-700 text-white rounded-xl w-16 h-16 flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
          <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
        </div>
        <div>
          <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-800 to-blue-800 bg-clip-text text-transparent leading-tight md:text-4xl">
            Demandes de Stage
          </h1>
          <p class="text-sm text-gray-600 mt-1 font-medium">{{ structure.sigle }} - {{ structure.libelle }}</p>
        </div>
      </div>
    </template>

    <!-- Fond personnalisé avec dégradé gris/bleu subtil -->
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50/30 to-slate-100/50 relative">
      <!-- Texture de fond subtile -->
      <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 2px 2px, rgba(59, 130, 246, 0.1) 1px, transparent 0); background-size: 20px 20px;"></div>
      
      <div class="relative py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          
          <!-- Messages flash avec design moderne -->
          <div v-if="$page.props.flash.success" class="mb-8 bg-gradient-to-r from-emerald-500/10 to-emerald-600/10 backdrop-blur-sm border border-emerald-200/50 text-emerald-700 p-5 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300" role="alert">
            <div class="flex items-start gap-4">
              <div class="flex-shrink-0 text-emerald-500 mt-0.5">
                <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                  </svg>
                </div>
              </div>
              <div>
                <p class="font-semibold text-emerald-800">Succès!</p>
                <p class="text-emerald-700">{{ $page.props.flash.success }}</p>
              </div>
            </div>
          </div>
          
          <div v-if="$page.props.flash.error" class="mb-8 bg-gradient-to-r from-red-500/10 to-red-600/10 backdrop-blur-sm border border-red-200/50 text-red-700 p-5 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300" role="alert">
            <div class="flex items-start gap-4">
              <div class="flex-shrink-0 text-red-500 mt-0.5">
                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                  </svg>
                </div>
              </div>
              <div>
                <p class="font-semibold text-red-800">Erreur!</p>
                <p class="text-red-700">{{ $page.props.flash.error }}</p>
              </div>
            </div>
          </div>

          <!-- Statistiques rapides -->
          <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white/80 backdrop-blur-lg border border-white/20 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
              <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                </div>
                <div>
                  <p class="text-2xl font-bold text-gray-800">{{ props.demandes.total || 0 }}</p>
                  <p class="text-sm text-gray-600 font-medium">Total des demandes</p>
                </div>
              </div>
            </div>
            
            <div class="bg-white/80 backdrop-blur-lg border border-white/20 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
              <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div>
                  <p class="text-2xl font-bold text-gray-800">{{ getStatusCount('En attente') }}</p>
                  <p class="text-sm text-gray-600 font-medium">En attente</p>
                </div>
              </div>
            </div>

            <div class="bg-white/80 backdrop-blur-lg border border-white/20 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
              <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                </div>
                <div>
                  <p class="text-2xl font-bold text-gray-800">{{ getStatusCount('Acceptée') }}</p>
                  <p class="text-sm text-gray-600 font-medium">Acceptées</p>
                </div>
              </div>
            </div>

            <div class="bg-white/80 backdrop-blur-lg border border-white/20 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
              <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                  </svg>
                </div>
                <div>
                  <p class="text-2xl font-bold text-gray-800">{{ getStatusCount('En cours') }}</p>
                  <p class="text-sm text-gray-600 font-medium">En cours</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Filtres modernisés -->
          <div class="bg-white/80 backdrop-blur-lg border border-white/20 shadow-xl rounded-2xl mb-8 overflow-hidden hover:shadow-2xl transition-all duration-500">
            <div class="bg-gradient-to-r from-blue-600 via-blue-700 to-blue-800 py-6 px-8">
              <h3 class="text-xl font-bold text-white flex items-center gap-3">
                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                  </svg>
                </div>
                Filtres de recherche
              </h3>
            </div>
            
            <div class="p-8">
              <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Filtre par statut -->
                <div class="group">
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Statut</label>
                  <div class="relative">
                    <select v-model="filters.status" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-gray-700 bg-white/90 backdrop-blur-sm py-3 px-4 transition-all duration-200 hover:shadow-md">
                      <option value="">Tous les statuts</option>
                      <option value="En cours">En cours</option>
                      <option value="En attente">En attente</option>
                      <option value="Acceptée">Acceptée</option>
                      <option value="Refusée">Refusée</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                      <svg class="h-5 w-5 transition-transform group-hover:rotate-180 duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                      </svg>
                    </div>
                  </div>
                </div>
                
                <!-- Recherche par nom -->
                <div class="group">
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Rechercher</label>
                  <div class="relative">
                    <input v-model="filters.search" type="text" placeholder="Nom, prénom ou email..." class="w-full rounded-xl border-gray-200 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 pl-12 pr-12 py-3 text-gray-700 bg-white/90 backdrop-blur-sm transition-all duration-200 hover:shadow-md" />
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                      <svg class="h-5 w-5 text-gray-400 group-hover:text-blue-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                      </svg>
                    </div>
                    <div v-if="isLoading" class="absolute inset-y-0 right-0 pr-4 flex items-center">
                      <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                    </div>
                  </div>
                </div>
                
                <!-- Filtre par date -->
                <div class="group">
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Période</label>
                  <div class="relative">
                    <select v-model="filters.period" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-gray-700 bg-white/90 backdrop-blur-sm py-3 px-4 transition-all duration-200 hover:shadow-md">
                      <option value="">Toutes les périodes</option>
                      <option value="today">Aujourd'hui</option>
                      <option value="week">Cette semaine</option>
                      <option value="month">Ce mois</option>
                      <option value="year">Cette année</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                      <svg class="h-5 w-5 transition-transform group-hover:rotate-180 duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                      </svg>
                    </div>
                  </div>
                </div>
                
                <!-- Bouton de réinitialisation -->
                <div class="flex items-end">
                  <button @click="resetFilters" class="w-full px-6 py-3 bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 rounded-xl border border-gray-200 hover:from-gray-200 hover:to-gray-300 hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-2 font-semibold group hover:-translate-y-0.5" title="Réinitialiser tous les filtres">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:rotate-180 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Réinitialiser
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Liste des demandes modernisée -->
          <div class="bg-white/80 backdrop-blur-lg border border-white/20 shadow-xl rounded-2xl overflow-hidden hover:shadow-2xl transition-all duration-500">
            <div class="bg-gradient-to-r from-blue-600 via-blue-700 to-blue-800 py-6 px-8">
              <h3 class="text-xl font-bold text-white flex items-center gap-3">
                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                  </svg>
                </div>
                Liste des demandes
              </h3>
            </div>
            
            <div class="p-8">
              <div v-if="props.demandes.data.length === 0" class="py-16 px-6 text-center">
                <div class="bg-gradient-to-br from-blue-50 to-gray-50 rounded-2xl p-12 max-w-md mx-auto border border-blue-100/50 shadow-lg">
                  <div class="w-20 h-20 bg-gradient-to-br from-blue-100 to-blue-200 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                  </div>
                  <h3 class="text-xl font-bold text-gray-800 mb-3">Aucune demande trouvée</h3>
                  <p class="text-gray-600 leading-relaxed">
                    {{ filters.status || filters.search || filters.period ? 'Aucune demande ne correspond à vos critères de recherche.' : 'Aucune demande de stage n\'a été soumise pour votre structure.' }}
                  </p>
                </div>
              </div>
              
              <div v-else>
                <div class="overflow-hidden rounded-xl border border-gray-200/50 shadow-lg">
                  <table class="min-w-full divide-y divide-gray-200/50">
                    <thead class="bg-gradient-to-r from-gray-50 to-blue-50/30">
                      <tr>
                        <th scope="col" class="px-8 py-5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Stagiaire</th>
                        <th scope="col" class="px-8 py-5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Date de soumission</th>
                        <th scope="col" class="px-8 py-5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Date d'affectation</th>
                        <th scope="col" class="px-8 py-5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Statut</th>
<!--                         <th scope="col" class="px-8 py-5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Structure</th> -->
                        <th scope="col" class="px-8 py-5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Actions</th>
                      </tr>
                    </thead>
                    <tbody class="bg-white/50 backdrop-blur-sm divide-y divide-gray-200/30">
                      <tr v-for="demande in props.demandes.data" :key="demande.id" class="hover:bg-blue-50/50 transition-all duration-200 group">
                        <td class="px-8 py-6 whitespace-nowrap">
                          <div class="flex items-center">
                            <div class="flex-shrink-0 h-12 w-12">
                              <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 text-white flex items-center justify-center font-bold shadow-lg group-hover:shadow-xl transition-all duration-200 group-hover:scale-105">
                                {{ getInitials(demande.stagiaire.user.nom, demande.stagiaire.user.prenom) }}
                              </div>
                            </div>
                            <div class="ml-4">
                              <div class="text-base font-semibold text-gray-900">
                                {{ demande.stagiaire.user.nom }} {{ demande.stagiaire.user.prenom }}
                              </div>
                              <div class="text-sm text-gray-600">
                                {{ demande.stagiaire.user.email }}
                              </div>
                            </div>
                          </div>
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap">
                          <div class="text-sm font-medium text-gray-900">
                            {{ formatDate(demande.date_soumission || demande.created_at) }}
                          </div>
                          <div class="text-xs text-gray-500 font-medium">
                            {{ formatTime(demande.date_soumission || demande.created_at) }}
                          </div>
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap">
                          <div v-if="getAffectationDate(demande)" class="text-sm font-medium text-gray-900">
                            {{ formatDate(getAffectationDate(demande)) }}
                          </div>
                          <div v-if="getAffectationDate(demande)" class="text-xs text-gray-500 font-medium">
                            {{ formatTime(getAffectationDate(demande)) }}
                          </div>
                          <div v-else class="text-sm text-gray-400 italic font-medium">
                            Non affectée
                          </div>
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap">
                          <span :class="getStatusColor(demande.statut) + ' px-4 py-2 rounded-full text-sm font-semibold inline-flex items-center shadow-sm hover:shadow-md transition-all duration-200'">
                            <span class="h-2 w-2 rounded-full mr-2 animate-pulse" :class="getStatusDotColor(demande.statut)"></span>
                            {{ getStatutAffichageRS(demande.statut) }}
                          </span>
                        </td>
                       
                        <td class="px-8 py-6 whitespace-nowrap text-sm font-medium">
                          <Link
                            :href="route('agent.rs.demandes.show', demande.id)"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-lg hover:shadow-xl hover:-translate-y-0.5 group"
                          >
                            <span>Détails</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:translate-x-1 duration-200" viewBox="0 0 20 20" fill="currentColor">
                              <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                          </Link>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                
                <!-- Pagination modernisée -->
                <div class="mt-10 flex justify-center">
                  <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-white/20 p-2">
                    <Pagination :links="props.demandes.links" @change="handlePageChange" />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </RSLayout>
</template>

<script setup>
import RSLayout from '@/Layouts/RSLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { ref, watch } from 'vue';
import { router, Link } from '@inertiajs/vue3';
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

// Fonctions utilitaires améliorées
function getStatusClass(status) {
  switch (status) {
    case 'En attente':
      return 'bg-gradient-to-r from-amber-100 to-amber-200 text-amber-800 border border-amber-300/50';
    case 'En cours':
      return 'bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 border border-blue-300/50';
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

function getStatusCount(status) {
  if (!props.demandes?.data) return 0;
  return props.demandes.data.filter(demande => demande.statut === status).length;
}

function resetFilters() {
  filters.value = {
    status: '',
    search: '',
    period: '',
    page: '1',
  };
}

function handlePageChange(page) {
  filters.value.page = page.toString();
}

// Utilisation de debounce pour la recherche avec animation
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

function getStatusColor(status) {
  switch (status) {
    case 'En attente':
      return 'bg-amber-100 text-amber-800';
    case 'En cours':
    case 'Encours':
      return 'bg-blue-100 text-blue-800';
    case 'Acceptée':
    case 'Approuvée':
      return 'bg-emerald-100 text-emerald-800';
    case 'Refusée':
      return 'bg-red-100 text-red-800';
    default:
      return 'bg-gray-100 text-gray-800';
  }
}

function getStatusDotColor(status) {
  switch (status) {
    case 'En attente':
      return 'bg-amber-500';
    case 'En cours':
    case 'Encours':
      return 'bg-blue-500';
    case 'Acceptée':
    case 'Approuvée':
      return 'bg-emerald-500';
    case 'Refusée':
      return 'bg-red-500';
    default:
      return 'bg-gray-500';
  }
}

function getInitials(nom, prenom) {
  if (!nom || !prenom) return '?';
  return `${nom.charAt(0)}${prenom.charAt(0)}`.toUpperCase();
}

function getStatutAffichageRS(statut) {
  if (statut === 'A réaffecter') return 'Refusée';
  return statut;
}
</script>

<style scoped>
/* Animations personnalisées */
@keyframes float {
  0%, 100% {
    transform: translateY(0px);
  }
  50% {
    transform: translateY(-4px);
  }
}

.animate-float {
  animation: float 3s ease-in-out infinite;
}

/* Effet glassmorphism renforcé */
.backdrop-blur-lg {
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
}

/* Scrollbar personnalisée */
::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

::-webkit-scrollbar-track {
  background: rgba(243, 244, 246, 0.5);
  border-radius: 4px;
}

::-webkit-scrollbar-thumb {
  background: linear-gradient(to bottom, #3b82f6, #1d4ed8);
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(to bottom, #1d4ed8, #1e40af);
}

/* Effet de survol pour les cartes statistiques */
.hover\:-translate-y-1:hover {
  transform: translateY(-4px);
}

/* Animation du spinner de chargement */
@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

.animate-spin {
  animation: spin 1s linear infinite;
}

/* Animation pulse pour les indicateurs de statut */
@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.7;
  }
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Transitions fluides pour tous les éléments interactifs */
* {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}

/* Effet de focus amélioré pour l'accessibilité */
button:focus-visible,
select:focus-visible,
input:focus-visible {
  outline: 2px solid #3b82f6;
  outline-offset: 2px;
}
</style>