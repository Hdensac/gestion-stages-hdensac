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
            <h3 class="text-lg font-semibold mb-2 text-blue-700">Votre Structure</h3>
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
         <!--  <div class="mt-6 md:mt-0 flex gap-3">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition-colors">Modifier</button>
            <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg shadow hover:bg-gray-300 transition-colors">Voir profil</button>
          </div> -->
        </div>

        <!-- Actions rapides -->
       <!--  <div class="mb-8 flex flex-wrap gap-4">
          <button class="flex items-center gap-2 px-5 py-3 bg-green-600 text-white rounded-xl shadow hover:bg-green-700 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Nouvelle demande
          </button>
          <button class="flex items-center gap-2 px-5 py-3 bg-yellow-500 text-white rounded-xl shadow hover:bg-yellow-600 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 6h18M3 14h18M3 18h18" /></svg>
            Voir toutes les demandes
          </button>
        </div> -->

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
            </div>
          </div>
        </div>

        <!-- Dernières demandes -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
          <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-800">Dernières demandes</h3>
            <Link 
              :href="route('agent.rs.demandes')" 
              class="px-5 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition-colors font-semibold"
            >
              Voir toutes les demandes
            </Link>
          </div>

          <div v-if="dernieresDemandes.length === 0" class="text-center py-8">
            <p class="text-gray-500">Aucune demande à afficher</p>
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
                    <div class="text-sm font-semibold text-gray-900">
                      {{ demande.stagiaire.user.nom }} {{ demande.stagiaire.user.prenom }}
                    </div>
                    <div class="text-xs text-gray-500">{{ demande.stagiaire.user.email }}</div>
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
                      class="text-blue-600 hover:text-blue-900 font-semibold transition"
                    >
                      Voir les détails
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
// import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import RSLayout from '@/Layouts/RSLayout.vue';
//import Pagination from '@/Components/Pagination.vue';

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
</script> 