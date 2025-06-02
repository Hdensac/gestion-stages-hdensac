<template>
  <Head title="Dashboard RS" />

  <RSLayout>
    <template #header>
      <div class="flex items-center gap-4 mb-2">
        <div class="bg-gradient-to-br from-blue-600 to-blue-700 text-white rounded-xl w-16 h-16 flex items-center justify-center shadow-lg shadow-blue-200/50 ring-1 ring-white/10">
          <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z" />
          </svg>
        </div>
        <div>
          <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent leading-tight">
            Tableau de bord
          </h1>
          <p class="text-sm text-gray-500 mt-1 font-medium">Espace Responsable de Structure</p>
        </div>
      </div>
    </template>

    <div class="py-2">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <!-- Message d'erreur -->
        <div v-if="error" class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-xl shadow-sm backdrop-blur-sm" role="alert">
          <div class="flex items-center gap-3">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
              </svg>
            </div>
            <div>
              <h3 class="text-sm font-semibold">Erreur détectée</h3>
              <p class="text-sm mt-1">{{ error }}</p>
            </div>
          </div>
        </div>

        <!-- Information de la structure -->
        <div v-if="structure" class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100/50 backdrop-blur-sm">
          <div class="bg-gradient-to-r from-blue-600 via-blue-700 to-blue-800 py-6 px-8 relative overflow-hidden">
            <div class="absolute inset-0 opacity-20">
              <div class="w-full h-full" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.05&quot;%3E%3Ccircle cx=&quot;30&quot; cy=&quot;30&quot; r=&quot;4&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')"></div>
            </div>
            <div class="relative">
              <h3 class="text-xl font-bold text-white flex items-center gap-3">
                <div class="p-2 bg-white/10 rounded-lg backdrop-blur-sm">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>
                    <rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect>
                    <line x1="6" y1="6" x2="6" y2="6"></line>
                    <line x1="6" y1="18" x2="6" y2="18"></line>
                  </svg>
                </div>
                Votre Structure
              </h3>
            </div>
          </div>
          <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="group hover:scale-105 transition-all duration-300">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100/70 rounded-xl p-6 border-2 border-blue-100 hover:border-blue-200 hover:shadow-lg transition-all duration-300">
                  <p class="text-sm font-semibold text-blue-600 mb-2 uppercase tracking-wide">Sigle</p>
                  <p class="text-2xl font-bold text-gray-800">{{ structure.sigle }}</p>
                </div>
              </div>
              <div class="group hover:scale-105 transition-all duration-300">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100/70 rounded-xl p-6 border-2 border-blue-100 hover:border-blue-200 hover:shadow-lg transition-all duration-300">
                  <p class="text-sm font-semibold text-blue-600 mb-2 uppercase tracking-wide">Libellé</p>
                  <p class="text-2xl font-bold text-gray-800">{{ structure.libelle }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <!-- Demandes en attente -->
          <div class="group hover:scale-105 hover:-translate-y-1 transition-all duration-300">
            <div class="bg-white rounded-2xl shadow-xl hover:shadow-2xl border border-gray-100/50 overflow-hidden relative">
              <div class="absolute inset-0 bg-gradient-to-br from-amber-500/5 to-orange-500/5"></div>
              <div class="relative p-6">
                <div class="flex items-center justify-between mb-4">
                  <div class="p-3 rounded-xl bg-gradient-to-br from-amber-100 to-amber-200 text-amber-600 shadow-lg">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                  <div class="text-right">
                    <p class="text-3xl font-bold text-gray-800 mb-1">{{ stats.demandesEnAttente }}</p>
                    <div class="flex items-center gap-1 text-amber-600 text-sm font-medium">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                      </svg>
                    </div>
                  </div>
                </div>
                <div>
                  <h4 class="text-lg font-semibold text-gray-800 mb-1">En attente</h4>
                  <p class="text-sm text-gray-500">Demandes à traiter</p>
                </div>
              </div>
              <div class="bg-gradient-to-r from-amber-50 to-orange-50 py-3 px-6 border-t border-amber-100">
                <span class="text-xs font-medium text-amber-700 flex items-center gap-2">
                  <div class="w-2 h-2 bg-amber-500 rounded-full animate-pulse"></div>
                  Requêtes en cours de traitement
                </span>
              </div>
            </div>
          </div>
          
          <!-- Demandes acceptées -->
          <div class="group hover:scale-105 hover:-translate-y-1 transition-all duration-300">
            <div class="bg-white rounded-2xl shadow-xl hover:shadow-2xl border border-gray-100/50 overflow-hidden relative">
              <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-green-500/5"></div>
              <div class="relative p-6">
                <div class="flex items-center justify-between mb-4">
                  <div class="p-3 rounded-xl bg-gradient-to-br from-emerald-100 to-emerald-200 text-emerald-600 shadow-lg">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                  <div class="text-right">
                    <p class="text-3xl font-bold text-gray-800 mb-1">{{ stats.demandesAcceptees }}</p>
                    <div class="flex items-center gap-1 text-emerald-600 text-sm font-medium">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                      </svg>
                    </div>
                  </div>
                </div>
                <div>
                  <h4 class="text-lg font-semibold text-gray-800 mb-1">Acceptées</h4>
                  <p class="text-sm text-gray-500">Demandes approuvées</p>
                </div>
              </div>
              <div class="bg-gradient-to-r from-emerald-50 to-green-50 py-3 px-6 border-t border-emerald-100">
                <p class="text-xs font-medium text-emerald-700 flex items-center gap-2">
                  <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                  Requêtes validées avec succès
                </p>
              </div>
            </div>
          </div>
          
          <!-- Demandes rejetées -->
          <div class="group hover:scale-105 hover:-translate-y-1 transition-all duration-300">
            <div class="bg-white rounded-2xl shadow-xl hover:shadow-2xl border border-gray-100/50 overflow-hidden relative">
              <div class="absolute inset-0 bg-gradient-to-br from-red-500/5 to-rose-500/5"></div>
              <div class="relative p-6">
                <div class="flex items-center justify-between mb-4">
                  <div class="p-3 rounded-xl bg-gradient-to-br from-red-100 to-red-200 text-red-600 shadow-lg">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                  <div class="text-right">
                    <p class="text-3xl font-bold text-gray-800 mb-1">{{ stats.demandesRejetees }}</p>
                    <div class="flex items-center gap-1 text-red-600 text-sm font-medium">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                      </svg>
                    </div>
                  </div>
                </div>
                <div>
                  <h4 class="text-lg font-semibold text-gray-800 mb-1">Rejetées</h4>
                  <p class="text-sm text-gray-500">Demandes refusées</p>
                </div>
              </div>
              <div class="bg-gradient-to-r from-red-50 to-rose-50 py-3 px-6 border-t border-red-100">
                <span class="text-xs font-medium text-red-700 flex items-center gap-2">
                  <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                  Requêtes non conformes
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Dernières demandes -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100/50 backdrop-blur-sm">
            <div class="absolute inset-0 opacity-20">
              <div class="w-full h-full" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.05&quot;%3E%3Ccircle cx=&quot;30&quot; cy=&quot;30&quot; r=&quot;4&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')"></div>
            </div>
            <div class="relative flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
              <h3 class="text-xl font-bold text-white flex items-center gap-3">
                <div class="p-2 bg-white/10 rounded-lg backdrop-blur-sm">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                  </svg>
                </div>
                Dernières demandes
              </h3>
              <Link 
                :href="route('agent.rs.demandes')" 
                class="group px-6 py-3 bg-white/10 hover:bg-white/20 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 font-semibold text-sm flex items-center gap-2 backdrop-blur-sm border border-white/20 hover:border-white/30"
              >
                <span>Voir toutes</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
              </Link>
            </div>
          </div>

          <div v-if="dernieresDemandes.length === 0" class="py-16 px-8">
            <div class="max-w-md mx-auto text-center">
              <div class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-2xl p-12 relative overflow-hidden">
                <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg%20width%3D%2260%22%20height%3D%2260%22%20viewBox%3D%220%200%2060%2060%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cg%20fill%3D%22none%22%20fill-rule%3D%22evenodd%22%3E%3Cg%20fill%3D%22%236366f1%22%20fill-opacity%3D%220.05%22%3E%3Ccircle%20cx%3D%2230%22%20cy%3D%2230%22%20r%3D%224%22/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
                <div class="relative">
                  <div class="mx-auto h-16 w-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center mb-6 shadow-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                      <polyline points="14 2 14 8 20 8"></polyline>
                      <line x1="16" y1="13" x2="8" y2="13"></line>
                      <line x1="16" y1="17" x2="8" y2="17"></line>
                      <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                  </div>
                  <h3 class="text-xl font-bold text-gray-800 mb-3">Aucune demande récente</h3>
                  <p class="text-gray-600 leading-relaxed">Les nouvelles demandes de stage apparaîtront ici dès qu'elles seront soumises</p>
                </div>
              </div>
            </div>
          </div>

          <div v-else class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                  <th scope="col" class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Stagiaire</th>
                  <th scope="col" class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Date de soumission</th>
                  <th scope="col" class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Statut</th>
                  <th scope="col" class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-100">
                <tr v-for="demande in dernieresDemandes" :key="demande.id" class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200 group">
                  <td class="px-8 py-6 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-600 to-blue-700 text-white flex items-center justify-center font-bold shadow-lg group-hover:shadow-xl transition-shadow duration-200">
                        {{ getInitials(demande.stagiaire.user.nom, demande.stagiaire.user.prenom) }}
                      </div>
                      <div class="ml-4">
                        <div class="text-sm font-bold text-gray-900">
                          {{ demande.stagiaire.user.nom }} {{ demande.stagiaire.user.prenom }}
                        </div>
                        <div class="text-xs text-gray-500 font-medium">{{ demande.stagiaire.user.email }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-8 py-6 whitespace-nowrap">
                    <div class="text-sm font-semibold text-gray-900">
                      {{ formatDate(demande.created_at) }}
                    </div>
                  </td>
                  <td class="px-8 py-6 whitespace-nowrap">
                    <span :class="getStatusClass(demande.statut) + ' px-4 py-2 rounded-full text-xs font-bold inline-flex items-center shadow-sm'">
                      <span class="h-2 w-2 rounded-full mr-2 animate-pulse" :class="getStatusDotClass(demande.statut)"></span>
                      {{ demande.statut }}
                    </span>
                  </td>
                  <td class="px-8 py-6 whitespace-nowrap text-sm font-medium">
                    <Link 
                      :href="route('agent.rs.demandes.show', demande.id)"
                      class="group inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg font-semibold transition-all duration-200 shadow-lg hover:shadow-xl"
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
      return 'bg-gradient-to-r from-amber-100 to-orange-100 text-amber-800 border border-amber-200';
    case 'En cours':
      return 'bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 border border-blue-200';
    case 'Acceptée':
      return 'bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-800 border border-emerald-200';
    case 'Refusée':
      return 'bg-gradient-to-r from-red-100 to-rose-100 text-red-800 border border-red-200';
    default:
      return 'bg-gradient-to-r from-gray-100 to-slate-100 text-gray-800 border border-gray-200';
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