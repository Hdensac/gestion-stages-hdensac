<template>
  <Head :title="`Stage - ${stage.structure?.libelle || 'Détails'}`" />
  <Stagiaire :notifications="notifications">
    <template #header>
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
          <div class="p-2 bg-indigo-100 rounded-xl">
            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
          </div>
          <div>
            <h2 class="text-2xl font-bold text-gray-900">Détails du Stage</h2>
            <p class="text-base text-gray-600 mt-1 font-medium">{{ stage.structure?.libelle || 'Structure non définie' }}</p>
          </div>
        </div>
        
        <!-- Statut badge moderne -->
        <div class="flex items-center space-x-3">
          <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
            :class="{
              'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-600/20': stage.statut_calculé === 'Terminé',
              'bg-blue-100 text-blue-700 ring-1 ring-blue-600/20': stage.statut_calculé === 'En cours',
              'bg-amber-100 text-amber-700 ring-1 ring-amber-600/20': stage.statut_calculé === 'À venir',
            }">
            <span class="w-1.5 h-1.5 rounded-full mr-1.5"
              :class="{
                'bg-emerald-500': stage.statut_calculé === 'Terminé',
                'bg-blue-500': stage.statut_calculé === 'En cours',
                'bg-amber-500': stage.statut_calculé === 'À venir',
              }">
            </span>
            {{ stage.statut_calculé }}
          </span>
        </div>
      </div>
    </template>

    <div class="py-8">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Messages d'alerte modernisés -->
        <div v-if="error" class="mb-8 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl shadow-sm">
          <div class="flex items-center">
            <svg class="w-5 h-5 text-red-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
            <div>
              <p class="font-semibold">Une erreur s'est produite</p>
              <p class="text-sm">{{ error }}</p>
            </div>
          </div>
        </div>

        <div v-if="success" class="mb-8 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl shadow-sm">
          <div class="flex items-center">
            <svg class="w-5 h-5 text-emerald-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <div>
              <p class="font-semibold">Opération réussie</p>
              <p class="text-sm">{{ success }}</p>
            </div>
          </div>
        </div>

        <!-- Bouton de retour modernisé -->
        <div class="mb-8">
          <Link :href="route('stagiaire.stages')" 
            class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 rounded-xl font-medium text-sm text-gray-700 shadow-sm hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
            <svg class="w-4 h-4 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour à la liste
          </Link>
        </div>

        <!-- Timeline modernisée -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
          <div class="flex items-center mb-6">
            <div class="p-2 bg-indigo-100 rounded-lg mr-3">
              <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900">Progression du stage</h3>
          </div>
          <StageTimeline 
            :start-date="stage.date_debut" 
            :end-date="stage.date_fin"
            :events="getStageEvents()"
          />
        </div>

        <!-- Section principale avec onglets modernisés -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
          <!-- Navigation des onglets -->
          <div class="border-b border-gray-100 bg-gray-50/50">
            <nav class="flex">
              <button
                @click="activeTab = 'infos'"
                :class="[
                  'relative py-4 px-6 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 transition-all duration-200',
                  activeTab === 'infos'
                    ? 'text-indigo-600 bg-white border-b-2 border-indigo-500'
                    : 'text-gray-500 hover:text-gray-700 hover:bg-white/50'
                ]"
              >
                <div class="flex items-center">
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  Informations générales
                </div>
              </button>
              <button
                @click="activeTab = 'theme'"
                :class="[
                  'relative py-4 px-6 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 transition-all duration-200',
                  activeTab === 'theme'
                    ? 'text-indigo-600 bg-white border-b-2 border-indigo-500'
                    : 'text-gray-500 hover:text-gray-700 hover:bg-white/50'
                ]"
              >
                <div class="flex items-center">
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                  </svg>
                  Thème du stage
                </div>
              </button>
              <button
                @click="activeTab = 'evaluation'"
                :class="[
                  'relative py-4 px-6 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 transition-all duration-200',
                  activeTab === 'evaluation'
                    ? 'text-indigo-600 bg-white border-b-2 border-indigo-500'
                    : 'text-gray-500 hover:text-gray-700 hover:bg-white/50'
                ]"
              >
                <div class="flex items-center">
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                  </svg>
                  Évaluation
                </div>
              </button>
            </nav>
          </div>

          <!-- Contenu des onglets -->
          <div class="p-8">
            <!-- Informations générales -->
            <div v-if="activeTab === 'infos'" class="space-y-8">
              <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Informations du stage -->
                <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-xl p-6 border border-indigo-100">
                  <div class="flex items-center mb-4">
                    <div class="p-2 bg-indigo-100 rounded-lg mr-3">
                      <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                      </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900">Informations du stage</h3>
                  </div>
                  
                  <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-indigo-100 last:border-b-0">
                      <span class="text-base font-medium text-gray-700">Structure</span>
                      <span class="text-base text-gray-900 font-semibold">{{ stage.structure?.libelle || 'Non spécifiée' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-indigo-100 last:border-b-0">
                      <span class="text-base font-medium text-gray-700">Type</span>
                      <span class="text-base text-gray-900 font-semibold">{{ stage.type || 'Non renseigné' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-indigo-100 last:border-b-0">
                      <span class="text-base font-medium text-gray-700">Période</span>
                      <span class="text-base text-gray-900 font-semibold">{{ formatDate(stage.date_debut) }} - {{ formatDate(stage.date_fin) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                      <span class="text-base font-medium text-gray-700">Durée</span>
                      <span class="text-base text-gray-900 font-semibold">{{ calculateDuration(stage.date_debut, stage.date_fin) }}</span>
                    </div>
                  </div>
                </div>

                <!-- Maître de stage -->
                <div class="bg-gradient-to-br from-slate-50 to-gray-50 rounded-xl p-6 border border-gray-200">
                  <div class="flex items-center mb-4">
                    <div class="p-2 bg-gray-100 rounded-lg mr-3">
                      <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                      </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Maître de stage</h3>
                  </div>
                  
                  <div v-if="stage.maitre_stage_actuel || getMaitreStageActuel()" class="space-y-4">
                    <div class="flex items-center space-x-4">
                      <div class="bg-gradient-to-br from-indigo-500 to-purple-600 text-white rounded-full w-14 h-14 flex items-center justify-center text-lg font-bold shadow-lg">
                        {{ getMaitreStageInitials() }}
                      </div>
                      <div class="flex-1">
                        <p class="font-semibold text-gray-900 text-lg">
                          <template v-if="stage.maitre_stage_actuel">
                            {{ stage.maitre_stage_actuel.prenom }} {{ stage.maitre_stage_actuel.nom }}
                          </template>
                          <template v-else-if="getMaitreStageActuel().prenom || getMaitreStageActuel().nom">
                            {{ getMaitreStageActuel().prenom }} {{ getMaitreStageActuel().nom }}
                          </template>
                          <template v-else>
                            {{ getMaitreStageActuel().nom }}
                          </template>
                        </p>
                        <p class="text-gray-600 text-sm">
                          <template v-if="stage.maitre_stage_actuel">
                            {{ stage.maitre_stage_actuel.email }}
                          </template>
                          <template v-else>
                            {{ getMaitreStageActuel().email }}
                          </template>
                        </p>
                      </div>
                    </div>
                    <div class="pt-4 border-t border-gray-200">
                      <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-600">Date d'affectation</span>
                        <span class="text-sm text-gray-900 font-medium">{{ formatDate(getAffectationActuelle()?.date_affectation) }}</span>
                      </div>
                    </div>
                  </div>
                  
                  <div v-else class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                      <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                      </svg>
                    </div>
                    <p class="text-gray-500 text-sm">Aucun maître de stage assigné</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Thème du stage -->
            <div v-if="activeTab === 'theme'" class="space-y-6">
              <div v-if="stage.themeStage" class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-xl p-6 border border-indigo-100">
                <div class="flex justify-between items-start mb-6">
                  <div class="flex-1">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ stage.themeStage.intitule }}</h3>
                    <p class="text-gray-600 leading-relaxed">{{ stage.themeStage.description }}</p>
                  </div>
                  <span class="ml-4 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                    :class="{
                      'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-600/20': stage.themeStage.etat === 'Validé',
                      'bg-amber-100 text-amber-700 ring-1 ring-amber-600/20': stage.themeStage.etat === 'Proposé',
                      'bg-red-100 text-red-700 ring-1 ring-red-600/20': stage.themeStage.etat === 'Refusé',
                      'bg-blue-100 text-blue-700 ring-1 ring-blue-600/20': stage.themeStage.etat === 'Modifié',
                    }">
                    {{ stage.themeStage.etat }}
                  </span>
                </div>
                
                <div v-if="getMotsCles(stage.themeStage).length > 0">
                  <h4 class="text-sm font-medium text-gray-700 mb-3">Mots-clés associés</h4>
                  <div class="flex flex-wrap gap-2">
                    <span v-for="(motCle, index) in getMotsCles(stage.themeStage)" :key="index"
                      class="inline-flex items-center px-3 py-1 bg-white border border-indigo-200 text-indigo-700 rounded-full text-sm font-medium hover:bg-indigo-50 transition-colors duration-150">
                      <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                      </svg>
                      {{ motCle }}
                    </span>
                  </div>
                </div>
              </div>
              
              <div v-else class="text-center py-12 bg-gray-50 rounded-xl border border-gray-200">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                  <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                  </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun thème défini</h3>
                <p class="text-gray-500">Le thème de ce stage n'a pas encore été proposé.</p>
              </div>
            </div>

            <!-- Évaluation -->
            <div v-if="activeTab === 'evaluation'" class="space-y-6">
              <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-xl p-6 border border-emerald-100">
                <div class="flex items-center mb-6">
                  <div class="p-2 bg-emerald-100 rounded-lg mr-3">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                  </div>
                  <h3 class="text-lg font-semibold text-gray-900">Évaluation du stage</h3>
                </div>
                
                <div v-if="stage.note" class="space-y-4">
                  <div class="flex items-center justify-between p-4 bg-white rounded-lg border border-emerald-200">
                    <span class="text-sm font-medium text-gray-700">Note obtenue</span>
                    <div class="flex items-center space-x-2">
                      <span class="text-2xl font-bold text-emerald-600">{{ stage.note }}</span>
                      <span class="text-sm text-gray-500">/ 20</span>
                    </div>
                  </div>
                  
                  <div v-if="stage.commentaire_evaluation" class="p-4 bg-white rounded-lg border border-emerald-200">
                    <h4 class="text-base font-semibold text-gray-700 mb-2">Commentaire d'évaluation</h4>
                    <p class="text-base text-gray-600 leading-relaxed">{{ stage.commentaire_evaluation }}</p>
                  </div>
                </div>
                
                <div v-else class="text-center py-8">
                  <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                  <h4 class="text-xl font-semibold text-gray-900 mb-2">Évaluation en attente</h4>
                  <p class="text-base text-gray-600">Ce stage n'a pas encore été évalué.</p>
                  <p v-if="stage.statut !== 'Terminé'" class="text-base text-gray-500 mt-2">
                    L'évaluation sera disponible une fois le stage terminé.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Stagiaire>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Stagiaire from '@/Layouts/Stagiaire.vue';
import StageTimeline from '@/Components/StageTimeline.vue';
import { ref } from 'vue';

const props = defineProps({
  stage: Object,
  notifications: {
    type: Array,
    default: () => []
  },
  error: {
    type: String,
    default: null
  },
  success: {
    type: String,
    default: null
  }
});

console.log('STAGE DETAIL:', props.stage);

// État pour les onglets
const activeTab = ref('infos');

// Formater une date
const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  const date = new Date(dateString);
  return date.toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'short',
    year: 'numeric'
  });
};

// Calculer la durée entre deux dates
const calculateDuration = (startDate, endDate) => {
  if (!startDate || !endDate) return 'N/A';

  const start = new Date(startDate);
  const end = new Date(endDate);
  const diffTime = Math.abs(end - start);
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

  if (diffDays < 30) {
    return `${diffDays} jour${diffDays > 1 ? 's' : ''}`;
  } else {
    const months = Math.floor(diffDays / 30);
    return `${months} mois`;
  }
};

// Récupérer les mots-clés du thème
const getMotsCles = (themeStage) => {
  if (!themeStage || !themeStage.mots_cles) return [];
  return themeStage.mots_cles.split(',').map(mot => mot.trim());
};

// Récupérer le maître de stage actuel
const getMaitreStageActuel = () => {
  if (!props.stage.affectationsMaitreStage || props.stage.affectationsMaitreStage.length === 0) {
    return null;
  }
  // Priorité à l'affectation 'En cours' avec relation
  let activeAffectation = props.stage.affectationsMaitreStage.find(aff => aff.statut === 'En cours' && aff.maitreStage);
  if (!activeAffectation) {
    // Sinon, prendre la dernière affectation existante avec relation
    activeAffectation = [...props.stage.affectationsMaitreStage].filter(aff => aff.maitreStage).sort((a, b) => new Date(b.date_affectation) - new Date(a.date_affectation))[0];
  }
  if (activeAffectation && activeAffectation.maitreStage) {
    return activeAffectation.maitreStage;
  }
  // Affichage de secours : prendre la dernière affectation même sans relation
  const lastAffect = [...props.stage.affectationsMaitreStage].sort((a, b) => new Date(b.date_affectation) - new Date(a.date_affectation))[0];
  if (lastAffect && lastAffect.maitre_stage_id) {
    return { nom: '(ID: ' + lastAffect.maitre_stage_id + ')', prenom: '', email: '' };
  }
  return null;
};

// Récupérer l'affectation actuelle
const getAffectationActuelle = () => {
  if (!props.stage.affectationsMaitreStage || props.stage.affectationsMaitreStage.length === 0) {
    return null;
  }
  
  return props.stage.affectationsMaitreStage.find(aff => aff.statut === 'En cours');
};

// Récupérer les initiales du maître de stage
const getMaitreStageInitials = () => {
  const ms = getMaitreStageActuel();
  if (!ms) return 'MS';
  
  const prenom = ms.prenom || '';
  const nom = ms.nom || '';
  return (prenom.charAt(0) + nom.charAt(0)).toUpperCase();
};

// Générer les événements pour la timeline du stage
const getStageEvents = () => {
  const events = [];
  
  // Ajouter les événements d'affectation
  if (props.stage.affectationsMaitreStage && props.stage.affectationsMaitreStage.length > 0) {
    props.stage.affectationsMaitreStage.forEach(affectation => {
      if (affectation.date_affectation) {
        events.push({
          date: affectation.date_affectation,
          title: `Affectation à ${affectation.maitreStage?.nom || 'un maître de stage'}`
        });
      }
    });
  }
  
  // Ajouter la date de soumission de la demande
  if (props.stage.demandeStage?.date_soumission) {
    events.push({
      date: props.stage.demandeStage.date_soumission,
      title: 'Soumission de la demande'
    });
  }
  
  // Ajouter la date de traitement de la demande
  if (props.stage.demandeStage?.date_traitement) {
    events.push({
      date: props.stage.demandeStage.date_traitement,
      title: 'Traitement de la demande'
    });
  }
  
  return events;
};
</script>