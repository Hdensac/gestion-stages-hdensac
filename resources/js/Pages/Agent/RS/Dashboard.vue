<template>
  <Head title="Dashboard RS" />

  <RSLayout>
    <template #header>
      <div class="flex items-center gap-4 mb-2">
        <div class="bg-blue-600 text-white rounded-lg w-14 h-14 flex items-center justify-center shadow-md">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 01-8 0M12 3v4m0 0a4 4 0 01-4 4H4m8-4a4 4 0 014 4h4m-8 0v4m0 0a4 4 0 004 4h4m-8-4a4 4 0 01-4 4H4" />
          </svg>
        </div>
        <div>
          <h1 class="text-2xl font-bold text-gray-800 leading-tight md:text-3xl">Tableau de bord</h1>
          <p class="text-sm text-gray-500 mt-1">Espace Responsable de Structure</p>
        </div>
      </div>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Message d'erreur -->
        <div v-if="error" class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-sm" role="alert">
          <p class="font-bold">Erreur</p>
          <p>{{ error }}</p>
        </div>

        <!-- Information de la structure -->
        <div v-if="structure" class="bg-white shadow-md rounded-lg mb-8 overflow-hidden">
          <div class="border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-800 py-3 px-6">
            <h3 class="text-lg font-semibold text-white flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>
                <rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect>
                <line x1="6" y1="6" x2="6" y2="6"></line>
                <line x1="6" y1="18" x2="6" y2="18"></line>
              </svg>
              Votre Structure
            </h3>
          </div>
          <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                <p class="text-sm font-medium text-blue-600 mb-1">Sigle</p>
                <p class="text-xl font-semibold text-gray-800">{{ structure.sigle }}</p>
              </div>
              <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                <p class="text-sm font-medium text-blue-600 mb-1">Libellé</p>
                <p class="text-xl font-semibold text-gray-800">{{ structure.libelle }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <!-- Demandes en attente -->
          <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
            <div class="p-6 flex items-center gap-4">
              <div class="p-3 rounded-lg bg-amber-100 text-amber-600">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div>
                <p class="text-sm font-medium text-gray-500">Demandes en attente</p>
                <div class="flex items-baseline">
                  <p class="text-2xl font-bold text-gray-800">{{ stats.demandesEnAttente }}</p>
                </div>
              </div>
            </div>
            <div class="bg-amber-50 py-2 px-6 border-t border-amber-100">
              <p class="text-xs text-amber-600">Requêtes à traiter</p>
            </div>
          </div>
          
          <!-- Demandes acceptées -->
          <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
            <div class="p-6 flex items-center gap-4">
              <div class="p-3 rounded-lg bg-emerald-100 text-emerald-600">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
              </div>
              <div>
                <p class="text-sm font-medium text-gray-500">Demandes acceptées</p>
                <div class="flex items-baseline">
                  <p class="text-2xl font-bold text-gray-800">{{ stats.demandesAcceptees }}</p>
                </div>
              </div>
            </div>
            <div class="bg-emerald-50 py-2 px-6 border-t border-emerald-100">
              <p class="text-xs text-emerald-600">Requêtes approuvées</p>
            </div>
          </div>
          
          <!-- Demandes rejetées -->
          <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
            <div class="p-6 flex items-center gap-4">
              <div class="p-3 rounded-lg bg-red-100 text-red-600">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </div>
              <div>
                <p class="text-sm font-medium text-gray-500">Demandes rejetées</p>
                <div class="flex items-baseline">
                  <p class="text-2xl font-bold text-gray-800">{{ stats.demandesRejetees }}</p>
                </div>
              </div>
            </div>
            <div class="bg-red-50 py-2 px-6 border-t border-red-100">
              <p class="text-xs text-red-600">Requêtes refusées</p>
            </div>
          </div>
        </div>

        <!-- Dernières demandes -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
          <div class="border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-800 py-4 px-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h3 class="text-lg font-semibold text-white flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
              </svg>
              Dernières demandes
            </h3>
            <Link 
              :href="route('agent.rs.demandes')" 
              class="px-4 py-2 bg-white text-blue-700 rounded-md shadow-sm hover:bg-blue-50 transition-colors font-medium text-sm flex items-center gap-1"
            >
              <span>Voir toutes</span>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
              </svg>
            </Link>
          </div>

          <div v-if="dernieresDemandes.length === 0" class="py-12 px-6 text-center">
            <div class="bg-blue-50 rounded-lg p-8 max-w-md mx-auto">
              <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-blue-400 mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
              </svg>
              <h3 class="text-lg font-semibold text-gray-700 mb-1">Aucune demande à afficher</h3>
              <p class="text-sm text-gray-500">Les nouvelles demandes de stage apparaîtront ici</p>
            </div>
          </div>

          <div v-else class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Stagiaire</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date de soumission</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Statut</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="demande in dernieresDemandes" :key="demande.id" class="hover:bg-gray-50 transition">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="h-10 w-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-medium shadow-sm">
                        {{ getInitials(demande.stagiaire.user.nom, demande.stagiaire.user.prenom) }}
                      </div>
                      <div class="ml-4">
                        <div class="text-sm font-semibold text-gray-900">
                          {{ demande.stagiaire.user.nom }} {{ demande.stagiaire.user.prenom }}
                        </div>
                        <div class="text-xs text-gray-500">{{ demande.stagiaire.user.email }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">
                      {{ formatDate(demande.created_at) }}
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
        </div>
      </div>
    </div>
  </RSLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import RSLayout from '@/Layouts/RSLayout.vue';

defineProps({
  stats: Object,
  structure: Object,
  dernieresDemandes: Array,
  error: String,
});

function formatDate(date) {
  return new Date(date).toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });
}

function getStatusClass(status) {
  switch (status) {
    case 'En attente':
      return 'bg-amber-50 text-amber-700';
    case 'En cours':
      return 'bg-blue-50 text-blue-700';
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
    case 'Acceptée':
      return 'bg-emerald-500';
    case 'Refusée':
      return 'bg-red-500';
    default:
      return 'bg-gray-500';
  }
}

function getInitials(nom, prenom) {
  return `${prenom.charAt(0)}${nom.charAt(0)}`.toUpperCase();
}
</script>