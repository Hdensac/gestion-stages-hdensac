<template>
  <Head title="Dashboard RS" />

  <RSLayout>
    <template #header>
      <div class="flex items-center gap-4 mb-2">
        <div class="bg-blue-100 text-blue-700 rounded-full w-12 h-12 flex items-center justify-center text-2xl font-bold shadow">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 01-8 0M12 3v4m0 0a4 4 0 01-4 4H4m8-4a4 4 0 014 4h4m-8 0v4m0 0a4 4 0 004 4h4m-8-4a4 4 0 01-4 4H4" />
          </svg>
        </div>
        <h1 class="text-3xl font-extrabold text-blue-800 leading-tight">Tableau de bord Responsable de Structure</h1>
      </div>
    </template>

    <div class="py-10 bg-gray-100 min-h-screen">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Message d'erreur -->
        <div v-if="error" class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow" role="alert">
          <p class="font-bold">Erreur</p>
          <p>{{ error }}</p>
        </div>

        <!-- Information de la structure -->
        <div v-if="structure" class="bg-white shadow-lg rounded-xl mb-8 p-6 flex flex-col md:flex-row md:items-center md:justify-between">
          <div>
            <h3 class="text-lg font-semibold mb-2 text-blue-700 flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>
                <rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect>
                <line x1="6" y1="6" x2="6" y2="6"></line>
                <line x1="6" y1="18" x2="6" y2="18"></line>
              </svg>
              Votre Structure
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <p class="text-sm text-gray-600">Sigle</p>
                <p class="font-medium text-lg">{{ structure.sigle }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-600">Libellé</p>
                <p class="font-medium text-lg">{{ structure.libelle }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
          <!-- Demandes en attente -->
          <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-shadow duration-300 p-6 flex items-center gap-4">
            <div class="p-4 rounded-full bg-yellow-100 text-yellow-600 shadow">
              <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-600">Demandes en attente</p>
              <p class="text-3xl font-extrabold text-gray-800">{{ stats.demandesEnAttente }}</p>
              <p class="text-xs text-gray-500 mt-1">Requêtes à traiter</p>
            </div>
          </div>
          
          <!-- Demandes acceptées -->
          <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-shadow duration-300 p-6 flex items-center gap-4">
            <div class="p-4 rounded-full bg-green-100 text-green-600 shadow">
              <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-600">Demandes acceptées</p>
              <p class="text-3xl font-extrabold text-gray-800">{{ stats.demandesAcceptees }}</p>
              <p class="text-xs text-gray-500 mt-1">Requêtes approuvées</p>
            </div>
          </div>
          
          <!-- Demandes rejetées -->
          <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-shadow duration-300 p-6 flex items-center gap-4">
            <div class="p-4 rounded-full bg-red-100 text-red-600 shadow">
              <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-600">Demandes rejetées</p>
              <p class="text-3xl font-extrabold text-gray-800">{{ stats.demandesRejetees }}</p>
              <p class="text-xs text-gray-500 mt-1">Requêtes refusées</p>
            </div>
          </div>
        </div>

        <!-- Dernières demandes -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
          <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-800">Dernières demandes</h3>
            <Link 
              :href="route('agent.rs.demandes')" 
              class="px-5 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition-colors font-semibold flex items-center gap-1"
            >
              <span>Voir toutes les demandes</span>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
              </svg>
            </Link>
          </div>

          <div v-if="dernieresDemandes.length === 0" class="py-12 px-6 text-center bg-gray-50 rounded-xl">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-16 w-16 text-gray-400 mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
              <polyline points="14 2 14 8 20 8"></polyline>
              <line x1="16" y1="13" x2="8" y2="13"></line>
              <line x1="16" y1="17" x2="8" y2="17"></line>
              <polyline points="10 9 9 9 8 9"></polyline>
            </svg>
            <h3 class="text-lg font-semibold text-gray-700 mb-1">Aucune demande à afficher</h3>
            <p class="text-sm text-gray-500">Les nouvelles demandes de stage apparaîtront ici</p>
          </div>

          <div v-else class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Stagiaire</th>
                  <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date de soumission</th>
                  <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Statut</th>
                  <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="demande in dernieresDemandes" :key="demande.id" class="hover:bg-gray-50 transition">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="h-10 w-10 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-semibold shadow-sm">
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
                    <span :class="getStatusClass(demande.statut) + ' px-3 py-1 rounded-full text-xs font-bold'">
                      {{ demande.statut }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <Link 
                      :href="route('agent.rs.demandes.show', demande.id)"
                      class="text-blue-600 hover:text-blue-900 font-semibold transition flex items-center gap-1 group"
                    >
                      <span>Voir les détails</span>
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
      return 'bg-yellow-100 text-yellow-800';
    case 'En cours':
      return 'bg-blue-100 text-blue-800';
    case 'Acceptée':
      return 'bg-green-100 text-green-800';
    case 'Refusée':
      return 'bg-red-100 text-red-800';
    default:
      return 'bg-gray-100 text-gray-800';
  }
}

function getInitials(nom, prenom) {
  return `${prenom.charAt(0)}${nom.charAt(0)}`.toUpperCase();
}
</script>