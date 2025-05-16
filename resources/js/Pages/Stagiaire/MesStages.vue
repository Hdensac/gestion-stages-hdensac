<template>
  <Head title="Mes Stages" />
  <Stagiaire>
    <template #header>
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2">
          <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
          </svg>
          <h2 class="text-xl font-semibold text-indigo-800">Mes Stages</h2>
        </div>
      </div>
    </template>

    <div class="py-6">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <!-- Message d'erreur -->
        <div v-if="error" class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md shadow">
          <p class="font-bold">Erreur</p>
          <p>{{ error }}</p>
        </div>

        <!-- Message d'information -->
        <div v-if="message" class="mb-6 bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded-md shadow">
          <p class="font-bold">Information</p>
          <p>{{ message }}</p>
        </div>

        <!-- Aucun stage -->
        <div v-if="stages.length === 0 && !error && !message" class="bg-white rounded-lg shadow-md p-6 text-center">
          <svg class="w-16 h-16 text-indigo-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
          <h3 class="text-lg font-semibold text-gray-700 mb-2">Aucun stage en cours</h3>
          <p class="text-gray-600 mb-4">Vous n'avez pas encore de stage actif. Vos demandes approuvées apparaîtront ici.</p>
          <Link :href="route('mes.demandes')" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            Voir mes demandes
          </Link>
        </div>

        <!-- Liste des stages -->
        <div v-if="stages.length > 0" class="space-y-6">
          <!-- En-tête avec compteur -->
          <div class="flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">Liste de vos stages</h3>
            <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ stages.length }} stage(s)</span>
          </div>

          <!-- Cartes des stages -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="stage in stages" :key="stage.id" class="bg-white rounded-lg shadow-md overflow-hidden border border-indigo-100 hover:shadow-lg transition-shadow duration-300">
              <!-- En-tête de la carte -->
              <div class="bg-gradient-to-r from-indigo-50 to-blue-50 px-4 py-3 border-b border-indigo-100">
                <div class="flex justify-between items-start">
                  <div>
                    <h4 class="font-semibold text-indigo-700">{{ stage.structure?.libelle || 'Structure non spécifiée' }}</h4>
                    <p class="text-sm text-indigo-600">{{ stage.type || 'Type non spécifié' }}</p>
                  </div>
                  <span class="px-2 py-1 text-xs font-medium rounded-full"
                    :class="{
                      'bg-green-100 text-green-800': stage.statut === 'Terminé',
                      'bg-blue-100 text-blue-800': stage.statut === 'En cours',
                      'bg-yellow-100 text-yellow-800': stage.statut === 'En attente',
                    }">
                    {{ stage.statut }}
                  </span>
                </div>
              </div>

              <!-- Corps de la carte -->
              <div class="p-4">
                <!-- Période -->
                <div class="mb-3">
                  <p class="text-sm text-gray-600 mb-1">Période :</p>
                  <p class="font-medium">{{ formatDate(stage.date_debut) }} - {{ formatDate(stage.date_fin) }}</p>
                </div>

                <!-- Maître de stage -->
                <div class="mb-3">
                  <p class="text-sm text-gray-600 mb-1">Maître de stage :</p>
                  <p v-if="stage.maitre_stage_actuel" class="font-medium">
                    {{ stage.maitre_stage_actuel.prenom }} {{ stage.maitre_stage_actuel.nom }}
                  </p>
                  <p v-else class="text-gray-500 italic">Non assigné</p>
                </div>

                <!-- Thème -->
                <div class="mb-4">
                  <p class="text-sm text-gray-600 mb-1">Thème :</p>
                  <p v-if="stage.themeStage" class="font-medium">
                    {{ stage.themeStage.intitule }}
                    <span class="ml-2 px-2 py-0.5 text-xs rounded-full"
                      :class="{
                        'bg-green-100 text-green-800': stage.themeStage.etat === 'Validé',
                        'bg-yellow-100 text-yellow-800': stage.themeStage.etat === 'Proposé',
                        'bg-red-100 text-red-800': stage.themeStage.etat === 'Refusé',
                      }">
                      {{ stage.themeStage.etat }}
                    </span>
                  </p>
                  <p v-else class="text-gray-500 italic">Aucun thème proposé</p>
                </div>

                <!-- Bouton de détails -->
                <div class="mt-4">
                  <Link :href="route('stagiaire.stages.show', stage.id)" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Voir les détails
                  </Link>
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

const props = defineProps({
  stages: {
    type: Array,
    default: () => []
  },
  error: {
    type: String,
    default: null
  },
  message: {
    type: String,
    default: null
  }
});

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
</script>
