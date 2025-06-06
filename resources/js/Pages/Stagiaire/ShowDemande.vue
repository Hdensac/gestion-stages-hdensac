<script setup>
import { Head } from '@inertiajs/vue3';
import Stagiaire from '@/Layouts/Stagiaire.vue';

defineProps({
  demande: Object,
  notifications: {
    type: Array,
    default: () => []
  }
});

// Fonction pour formater une date
const formatDate = (dateString) => {
  if (!dateString) return '-';
  return new Date(dateString).toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  });
};

// Fonction pour obtenir une couleur selon le statut (versions plus vives)
const getStatusColor = (statut) => {
  switch (statut) {
    case 'En attente':
      return 'text-amber-800 bg-gradient-to-r from-amber-100 to-orange-100 border-2 border-amber-300 shadow-lg shadow-amber-200/50';
    case 'Acceptée':
      return 'text-emerald-800 bg-gradient-to-r from-emerald-100 to-green-100 border-2 border-emerald-300 shadow-lg shadow-emerald-200/50';
    case 'Refusée':
      return 'text-red-800 bg-gradient-to-r from-red-100 to-rose-100 border-2 border-red-300 shadow-lg shadow-red-200/50';
    default:
      return 'text-slate-800 bg-gradient-to-r from-slate-100 to-gray-100 border-2 border-slate-300 shadow-lg shadow-slate-200/50';
  }
};

// Fonction pour obtenir l'icône selon le statut
const getStatusIcon = (statut) => {
  switch (statut) {
    case 'En attente':
      return 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z';
    case 'Acceptée':
      return 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z';
    case 'Refusée':
      return 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z';
    default:
      return 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
  }
};
</script>

<template>
  <Head title="Détails de la demande" />
  <Stagiaire :notifications="notifications">
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-2xl font-bold text-slate-800">Détails de la demande</h2>
          <p class="mt-1 text-base text-slate-600 font-medium">Consultation des informations détaillées</p>
        </div>
      </div>
    </template>

    <!-- Arrière-plan grisé -->
    <div class="min-h-screen bg-gradient-to-br from-gray-100 via-slate-100 to-gray-200">
      <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <!-- Header avec code de suivi et bouton retour - plus coloré -->
          <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
              <div class="p-4 bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-600 rounded-2xl shadow-xl shadow-blue-500/30">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
              </div>
              <div>
                <h1 class="text-3xl font-black bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Demande #{{ demande.code_suivi }}</h1>
                <p class="text-slate-600 font-mono text-base mt-1 bg-slate-200/70 px-3 py-1 rounded-lg inline-block">{{ demande.code_suivi }}</p>
              </div>
            </div>
            <a :href="route('mes.demandes')" 
              class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-slate-600 to-slate-700 text-white rounded-xl hover:from-slate-700 hover:to-slate-800 transition-all duration-300 shadow-lg hover:shadow-xl font-medium text-base transform hover:scale-105">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
              </svg>
              Retour à mes demandes
            </a>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Colonne principale -->
            <div class="lg:col-span-2 space-y-8">
              <!-- Statut et informations principales - plus coloré -->
              <div class="bg-white rounded-3xl shadow-xl border-2 border-slate-200/50 overflow-hidden backdrop-blur-sm">
                <div class="px-6 py-6 border-b-2 border-slate-100 bg-gradient-to-r from-blue-50 via-indigo-50 to-purple-50">
                  <div class="flex items-center gap-3">
                    <div class="p-2 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg">
                      <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800">Informations générales</h2>
                  </div>
                </div>
                <div class="p-6 space-y-6">
                  <!-- Statut -->
                  <div class="flex items-start justify-between">
                    <div>
                      <label class="block text-sm font-semibold text-slate-600 mb-3">Statut de la demande</label>
                      <div class="flex items-center gap-3">
                        <div :class="getStatusColor(demande.statut)" class="px-6 py-3 rounded-2xl flex items-center gap-3 font-bold text-base">
                          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="getStatusIcon(demande.statut)" />
                          </svg>
                          {{ demande.statut }}
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Grille d'informations - plus colorée -->
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-4 rounded-2xl border-2 border-blue-200/50 shadow-lg">
                      <label class="block text-base font-semibold text-blue-700 mb-2">Structure d'accueil</label>
                      <p class="text-lg font-bold text-slate-800">{{ demande.structure?.libelle || '-' }}</p>
                    </div>
                    
                    <div class="bg-gradient-to-br from-purple-50 to-pink-50 p-4 rounded-2xl border-2 border-purple-200/50 shadow-lg">
                      <label class="block text-base font-semibold text-purple-700 mb-2">Type de stage</label>
                      <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full shadow-lg"></div>
                        <p class="text-lg font-bold text-slate-800">{{ demande.type }}</p>
                      </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-emerald-50 to-teal-50 p-4 rounded-2xl border-2 border-emerald-200/50 shadow-lg">
                      <label class="block text-base font-semibold text-emerald-700 mb-2">Nature du stage</label>
                      <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full shadow-lg"></div>
                        <p class="text-lg font-bold text-slate-800">{{ demande.nature }}</p>
                      </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-orange-50 to-amber-50 p-4 rounded-2xl border-2 border-orange-200/50 shadow-lg">
                      <label class="block text-base font-semibold text-orange-700 mb-2">Date de soumission</label>
                      <p class="text-lg font-bold text-slate-800">{{ formatDate(demande.date_soumission) }}</p>
                    </div>
                  </div>

                  <!-- Période - plus colorée -->
                  <div class="bg-gradient-to-r from-cyan-100 via-blue-100 to-indigo-100 rounded-2xl p-6 border-2 border-cyan-200 shadow-xl">
                    <div class="flex items-center gap-3 mb-4">
                      <div class="p-2 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-xl shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                      </div>
                      <h3 class="font-bold text-cyan-900 text-xl">Période de stage</h3>
                    </div>
                    <p class="text-cyan-800 font-bold text-lg">
                      Du <span class="bg-white/80 px-3 py-1 rounded-lg">{{ formatDate(demande.date_debut) }}</span> 
                      au <span class="bg-white/80 px-3 py-1 rounded-lg">{{ formatDate(demande.date_fin) }}</span>
                    </p>
                  </div>
                </div>
              </div>

              <!-- Membres du groupe - plus coloré -->
              <div v-if="demande.nature === 'Groupe' && demande.membres && demande.membres.length > 0" 
                   class="bg-white rounded-3xl shadow-xl border-2 border-slate-200/50 overflow-hidden">
                <div class="px-6 py-6 border-b-2 border-slate-100 bg-gradient-to-r from-purple-50 via-pink-50 to-purple-50">
                  <div class="flex items-center gap-3">
                    <div class="p-2 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl shadow-lg">
                      <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                      </svg>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800">Membres du groupe</h2>
                    <span class="px-3 py-1 bg-gradient-to-r from-purple-500 to-pink-500 text-white text-sm font-bold rounded-full shadow-lg">
                      {{ demande.membres.length }} membre{{ demande.membres.length > 1 ? 's' : '' }}
                    </span>
                  </div>
                </div>
                <div class="p-6">
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div v-for="membre in demande.membres" :key="membre.id" 
                         class="bg-gradient-to-br from-purple-50 to-pink-50 p-5 rounded-2xl border-2 border-purple-200/50 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                      <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 via-purple-600 to-pink-600 rounded-full flex items-center justify-center shadow-lg">
                          <span class="text-white font-bold text-base">
                            {{ membre.user?.prenom?.charAt(0) }}{{ membre.user?.nom?.charAt(0) }}
                          </span>
                        </div>
                        <div>
                          <p class="font-bold text-slate-800">{{ membre.user?.nom }} {{ membre.user?.prenom }}</p>
                          <p class="text-sm text-purple-700 font-medium">{{ membre.user?.email }}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Documents attachés - plus colorés -->
              <div v-if="demande.lettre_cv_path || demande.diplomes_path" 
                   class="bg-white rounded-3xl shadow-xl border-2 border-slate-200/50 overflow-hidden">
                <div class="px-6 py-6 border-b-2 border-slate-100 bg-gradient-to-r from-green-50 via-emerald-50 to-teal-50">
                  <div class="flex items-center gap-3">
                    <div class="p-2 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl shadow-lg">
                      <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                      </svg>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800">Documents attachés</h2>
                  </div>
                </div>
                <div class="p-6 space-y-5">
                  <!-- Documents pour demande académique -->
                  <div v-if="demande.type === 'Académique' && demande.lettre_cv_path" 
                       class="group bg-gradient-to-r from-blue-100 via-indigo-100 to-blue-100 rounded-2xl p-6 border-2 border-blue-200 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                    <div class="flex items-center justify-between">
                      <div class="flex items-center gap-4">
                        <div class="p-4 bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-600 rounded-2xl shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                          <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                          </svg>
                        </div>
                        <div>
                          <h3 class="font-bold text-slate-800 text-lg">Lettre de recommandation</h3>
                          <p class="text-sm text-blue-700 font-medium">Document de recommandation académique</p>
                        </div>
                      </div>
                      <a :href="'/storage/' + demande.lettre_cv_path" target="_blank" 
                         class="inline-flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl font-bold transform hover:scale-105">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Télécharger
                      </a>
                    </div>
                  </div>

                  <!-- Documents pour demande professionnelle -->
                  <template v-if="demande.type === 'Professionnelle'">
                    <!-- CV et Lettre de motivation -->
                    <div v-if="demande.lettre_cv_path" 
                         class="group bg-gradient-to-r from-emerald-100 via-green-100 to-emerald-100 rounded-2xl p-6 border-2 border-emerald-200 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                      <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                          <div class="p-4 bg-gradient-to-br from-emerald-500 via-emerald-600 to-green-600 rounded-2xl shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                          </div>
                          <div>
                            <h3 class="font-bold text-slate-800 text-lg">CV et Lettre de motivation</h3>
                            <p class="text-sm text-emerald-700 font-medium">Documents principaux de la candidature</p>
                          </div>
                        </div>
                        <a :href="'/storage/' + demande.lettre_cv_path" target="_blank" 
                           class="inline-flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-emerald-600 to-green-600 text-white rounded-xl hover:from-emerald-700 hover:to-green-700 transition-all duration-300 shadow-lg hover:shadow-xl font-bold transform hover:scale-105">
                          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                          </svg>
                          Télécharger
                        </a>
                      </div>
                    </div>

                    <!-- Diplômes -->
                    <div v-if="demande.diplomes_path" 
                         class="group bg-gradient-to-r from-amber-100 via-orange-100 to-amber-100 rounded-2xl p-6 border-2 border-amber-200 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                      <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                          <div class="p-4 bg-gradient-to-br from-amber-500 via-amber-600 to-orange-600 rounded-2xl shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            </svg>
                          </div>
                          <div>
                            <h3 class="font-bold text-slate-800 text-lg">Diplômes</h3>
                            <p class="text-sm text-amber-700 font-medium">Documents justificatifs des diplômes</p>
                          </div>
                        </div>
                        <a :href="'/storage/' + demande.diplomes_path" target="_blank" 
                           class="inline-flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-amber-600 to-orange-600 text-white rounded-xl hover:from-amber-700 hover:to-orange-700 transition-all duration-300 shadow-lg hover:shadow-xl font-bold transform hover:scale-105">
                          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                          </svg>
                          Télécharger
                        </a>
                      </div>
                    </div>
                  </template>
                </div>
              </div>
            </div>

            <!-- Sidebar - Informations du stagiaire - plus colorée -->
            <div class="lg:col-span-1">
              <div class="bg-white rounded-3xl shadow-xl border-2 border-slate-200/50 overflow-hidden sticky top-8">
                <div class="px-6 py-6 border-b-2 border-slate-100 bg-gradient-to-r from-indigo-50 via-purple-50 to-pink-50">
                  <div class="flex items-center gap-3">
                    <div class="p-2 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-lg">
                      <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                      </svg>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800">Profil stagiaire</h2>
                  </div>
                </div>
                <div class="p-6 space-y-6">
                  <!-- Avatar et nom -->
                  <div class="text-center">
                    <div class="w-24 h-24 bg-gradient-to-br from-indigo-500 via-purple-600 to-pink-600 rounded-full mx-auto flex items-center justify-center mb-4 shadow-xl shadow-indigo-500/30">
                      <span class="text-white font-bold text-2xl">
                        {{ demande.stagiaire?.user?.prenom?.charAt(0) }}{{ demande.stagiaire?.user?.nom?.charAt(0) }}
                      </span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800">
                      {{ demande.stagiaire?.user?.nom }} {{ demande.stagiaire?.user?.prenom }}
                    </h3>
                  </div>

                  <!-- Informations de contact - plus colorées -->
                  <div class="space-y-4">
                    <div class="flex items-center gap-3 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl border-2 border-blue-200/50 shadow-lg">
                      <div class="p-2 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                      </div>
                      <div class="flex-1 min-w-0">
                        <p class="text-sm text-blue-700 font-semibold">Email</p>
                        <p class="font-bold text-slate-800 truncate">{{ demande.stagiaire?.user?.email }}</p>
                      </div>
                    </div>

                    <div class="flex items-center gap-3 p-4 bg-gradient-to-r from-emerald-50 to-green-50 rounded-2xl border-2 border-emerald-200/50 shadow-lg">
                      <div class="p-2 bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                      </div>
                      <div class="flex-1">
                        <p class="text-sm text-emerald-700 font-semibold">Téléphone</p>
                        <p class="font-bold text-slate-800">{{ demande.stagiaire?.user?.telephone || '-' }}</p>
                      </div>
                    </div>
                  </div>

                  <!-- Informations académiques - plus colorées -->
                  <div class="space-y-4 pt-4 border-t-2 border-slate-200">
                    <h4 class="font-bold text-slate-800 flex items-center gap-2 text-lg">
                      <div class="p-2 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                        </svg>
                      </div>
                      Formation
                    </h4>
                    
                    <div class="space-y-4">
                      <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-4 rounded-2xl border-2 border-purple-200/50 shadow-lg">
                        <p class="text-base text-purple-700 font-semibold">Université</p>
                        <p class="font-bold text-slate-800 text-base">{{ demande.stagiaire?.universite || '-' }}</p>
                      </div>
                      
                      <div class="bg-gradient-to-r from-cyan-50 to-blue-50 p-4 rounded-2xl border-2 border-cyan-200/50 shadow-lg">
                        <p class="text-base text-cyan-700 font-semibold">Filière</p>
                        <p class="font-bold text-slate-800 text-base">{{ demande.stagiaire?.filiere || '-' }}</p>
                      </div>
                      
                      <div class="bg-gradient-to-r from-orange-50 to-amber-50 p-4 rounded-2xl border-2 border-orange-200/50 shadow-lg">
                        <p class="text-base text-orange-700 font-semibold">Niveau d'étude</p>
                        <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-orange-500 to-amber-500 text-white text-sm font-bold rounded-full shadow-lg mt-2">
                          {{ demande.stagiaire?.niveau_etude || '-' }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Stagiaire>
</template>