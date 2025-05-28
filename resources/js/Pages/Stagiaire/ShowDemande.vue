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

// Fonction pour obtenir une couleur selon le statut
const getStatusColor = (statut) => {
  switch (statut) {
    case 'En attente':
      return 'text-amber-700 bg-gradient-to-r from-amber-50 to-amber-100 border border-amber-200';
    case 'Acceptée':
      return 'text-emerald-700 bg-gradient-to-r from-emerald-50 to-emerald-100 border border-emerald-200';
    case 'Refusée':
      return 'text-red-700 bg-gradient-to-r from-red-50 to-red-100 border border-red-200';
    default:
      return 'text-slate-700 bg-gradient-to-r from-slate-50 to-slate-100 border border-slate-200';
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
          <h2 class="text-2xl font-bold text-slate-900">Détails de la demande</h2>
          <p class="mt-1 text-sm text-slate-600">Consultation des informations détaillées</p>
        </div>
      </div>
    </template>

    <div class="py-8">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Header avec code de suivi et bouton retour -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div class="flex items-center gap-4">
            <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
            </div>
            <div>
              <h1 class="text-3xl font-bold text-slate-900">Demande #{{ demande.code_suivi }}</h1>
              <p class="text-slate-600 font-mono text-sm mt-1">{{ demande.code_suivi }}</p>
            </div>
          </div>
          <a :href="route('mes.demandes')" 
            class="inline-flex items-center gap-2 px-6 py-3 bg-white text-slate-700 rounded-xl border border-slate-200 hover:bg-slate-50 hover:border-slate-300 transition-all duration-200 shadow-sm hover:shadow-md font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour à mes demandes
          </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Colonne principale -->
          <div class="lg:col-span-2 space-y-8">
            <!-- Statut et informations principales -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
              <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white">
                <div class="flex items-center gap-3">
                  <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <h2 class="text-xl font-semibold text-slate-900">Informations générales</h2>
                </div>
              </div>
              <div class="p-6 space-y-6">
                <!-- Statut -->
                <div class="flex items-start justify-between">
                  <div>
                    <label class="block text-sm font-medium text-slate-500 mb-2">Statut de la demande</label>
                    <div class="flex items-center gap-3">
                      <div :class="getStatusColor(demande.statut)" class="px-4 py-2 rounded-full flex items-center gap-2 font-semibold text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="getStatusIcon(demande.statut)" />
                        </svg>
                        {{ demande.statut }}
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Grille d'informations -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div class="space-y-1">
                    <label class="block text-sm font-medium text-slate-500">Structure d'accueil</label>
                    <p class="text-lg font-semibold text-slate-900">{{ demande.structure?.libelle || '-' }}</p>
                  </div>
                  
                  <div class="space-y-1">
                    <label class="block text-sm font-medium text-slate-500">Type de stage</label>
                    <div class="flex items-center gap-2">
                      <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                      <p class="text-lg font-semibold text-slate-900">{{ demande.type }}</p>
                    </div>
                  </div>
                  
                  <div class="space-y-1">
                    <label class="block text-sm font-medium text-slate-500">Nature du stage</label>
                    <div class="flex items-center gap-2">
                      <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                      <p class="text-lg font-semibold text-slate-900">{{ demande.nature }}</p>
                    </div>
                  </div>
                  
                  <div class="space-y-1">
                    <label class="block text-sm font-medium text-slate-500">Date de soumission</label>
                    <p class="text-lg font-semibold text-slate-900">{{ formatDate(demande.date_soumission) }}</p>
                  </div>
                </div>

                <!-- Période -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-100">
                  <div class="flex items-center gap-3 mb-3">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h3 class="font-semibold text-blue-900">Période de stage</h3>
                  </div>
                  <p class="text-blue-800 font-medium">
                    Du <span class="font-bold">{{ formatDate(demande.date_debut) }}</span> 
                    au <span class="font-bold">{{ formatDate(demande.date_fin) }}</span>
                  </p>
                </div>
              </div>
            </div>

            <!-- Membres du groupe -->
            <div v-if="demande.nature === 'Groupe' && demande.membres && demande.membres.length > 0" 
                 class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
              <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-purple-50 to-white">
                <div class="flex items-center gap-3">
                  <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                  </svg>
                  <h2 class="text-xl font-semibold text-slate-900">Membres du groupe</h2>
                  <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs font-medium rounded-full">
                    {{ demande.membres.length }} membre{{ demande.membres.length > 1 ? 's' : '' }}
                  </span>
                </div>
              </div>
              <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div v-for="membre in demande.membres" :key="membre.id" 
                       class="bg-gradient-to-br from-slate-50 to-white p-4 rounded-xl border border-slate-200 hover:shadow-md transition-all duration-200">
                    <div class="flex items-center gap-3">
                      <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center">
                        <span class="text-white font-semibold text-sm">
                          {{ membre.user?.prenom?.charAt(0) }}{{ membre.user?.nom?.charAt(0) }}
                        </span>
                      </div>
                      <div>
                        <p class="font-semibold text-slate-900">{{ membre.user?.nom }} {{ membre.user?.prenom }}</p>
                        <p class="text-sm text-slate-600">{{ membre.user?.email }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Documents attachés -->
            <div v-if="demande.lettre_cv_path || demande.diplomes_path" 
                 class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
              <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-green-50 to-white">
                <div class="flex items-center gap-3">
                  <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                  <h2 class="text-xl font-semibold text-slate-900">Documents attachés</h2>
                </div>
              </div>
              <div class="p-6 space-y-4">
                <!-- Documents pour demande académique -->
                <div v-if="demande.type === 'Académique' && demande.lettre_cv_path" 
                     class="group bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-5 border border-blue-100 hover:shadow-md transition-all duration-200">
                  <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                      <div class="p-3 bg-blue-500 rounded-xl shadow-sm group-hover:shadow-md transition-shadow duration-200">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                      </div>
                      <div>
                        <h3 class="font-semibold text-slate-900">Lettre de recommandation</h3>
                        <p class="text-sm text-slate-600">Document de recommandation académique</p>
                      </div>
                    </div>
                    <a :href="'/storage/' + demande.lettre_cv_path" target="_blank" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all duration-200 shadow-sm hover:shadow-md font-medium">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                       class="group bg-gradient-to-r from-emerald-50 to-green-50 rounded-xl p-5 border border-emerald-100 hover:shadow-md transition-all duration-200">
                    <div class="flex items-center justify-between">
                      <div class="flex items-center gap-4">
                        <div class="p-3 bg-emerald-500 rounded-xl shadow-sm group-hover:shadow-md transition-shadow duration-200">
                          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                          </svg>
                        </div>
                        <div>
                          <h3 class="font-semibold text-slate-900">CV et Lettre de motivation</h3>
                          <p class="text-sm text-slate-600">Documents principaux de la candidature</p>
                        </div>
                      </div>
                      <a :href="'/storage/' + demande.lettre_cv_path" target="_blank" 
                         class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-all duration-200 shadow-sm hover:shadow-md font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Télécharger
                      </a>
                    </div>
                  </div>

                  <!-- Diplômes -->
                  <div v-if="demande.diplomes_path" 
                       class="group bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl p-5 border border-amber-100 hover:shadow-md transition-all duration-200">
                    <div class="flex items-center justify-between">
                      <div class="flex items-center gap-4">
                        <div class="p-3 bg-amber-500 rounded-xl shadow-sm group-hover:shadow-md transition-shadow duration-200">
                          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                          </svg>
                        </div>
                        <div>
                          <h3 class="font-semibold text-slate-900">Diplômes</h3>
                          <p class="text-sm text-slate-600">Documents justificatifs des diplômes</p>
                        </div>
                      </div>
                      <a :href="'/storage/' + demande.diplomes_path" target="_blank" 
                         class="inline-flex items-center gap-2 px-4 py-2 bg-amber-600 text-white rounded-xl hover:bg-amber-700 transition-all duration-200 shadow-sm hover:shadow-md font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

          <!-- Sidebar - Informations du stagiaire -->
          <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden sticky top-8">
              <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-indigo-50 to-white">
                <div class="flex items-center gap-3">
                  <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                  </svg>
                  <h2 class="text-xl font-semibold text-slate-900">Profil stagiaire</h2>
                </div>
              </div>
              <div class="p-6 space-y-6">
                <!-- Avatar et nom -->
                <div class="text-center">
                  <div class="w-20 h-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full mx-auto flex items-center justify-center mb-4 shadow-lg">
                    <span class="text-white font-bold text-2xl">
                      {{ demande.stagiaire?.user?.prenom?.charAt(0) }}{{ demande.stagiaire?.user?.nom?.charAt(0) }}
                    </span>
                  </div>
                  <h3 class="text-xl font-bold text-slate-900">
                    {{ demande.stagiaire?.user?.nom }} {{ demande.stagiaire?.user?.prenom }}
                  </h3>
                </div>

                <!-- Informations de contact -->
                <div class="space-y-4">
                  <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <div class="flex-1 min-w-0">
                      <p class="text-sm text-slate-500">Email</p>
                      <p class="font-medium text-slate-900 truncate">{{ demande.stagiaire?.user?.email }}</p>
                    </div>
                  </div>

                  <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    <div class="flex-1">
                      <p class="text-sm text-slate-500">Téléphone</p>
                      <p class="font-medium text-slate-900">{{ demande.stagiaire?.user?.telephone || '-' }}</p>
                    </div>
                  </div>
                </div>

                <!-- Informations académiques -->
                <div class="space-y-4 pt-4 border-t border-slate-100">
                  <h4 class="font-semibold text-slate-900 flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                    </svg>
                    Formation
                  </h4>
                  
                  <div class="space-y-3">
                    <div>
                      <p class="text-sm text-slate-500">Université</p>
                      <p class="font-medium text-slate-900">{{ demande.stagiaire?.universite || '-' }}</p>
                    </div>
                    
                    <div>
                      <p class="text-sm text-slate-500">Filière</p>
                      <p class="font-medium text-slate-900">{{ demande.stagiaire?.filiere || '-' }}</p>
                    </div>
                    
                    <div>
                      <p class="text-sm text-slate-500">Niveau d'étude</p>
                      <div class="inline-flex items-center px-3 py-1 bg-indigo-100 text-indigo-800 text-sm font-medium rounded-full">
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
  </Stagiaire>
</template>