<template>
  <Head :title="`Stage - ${stage.structure?.libelle || 'Détails'}`" />
  <Stagiaire>
    <template #header>
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2">
          <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
          </svg>
          <h2 class="text-xl font-semibold text-indigo-800">Détails du Stage</h2>
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

        <!-- Message de succès -->
        <div v-if="success" class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow">
          <p class="font-bold">Succès</p>
          <p>{{ success }}</p>
        </div>

        <!-- Bouton de retour -->
        <div class="mb-6">
          <Link :href="route('stagiaire.stages')" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour à la liste
          </Link>
        </div>

        <!-- Timeline du stage -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mb-6">
          <h3 class="text-lg font-semibold mb-4 text-indigo-800">Progression du stage</h3>
          <StageTimeline 
            :start-date="stage.date_debut" 
            :end-date="stage.date_fin"
            :events="getStageEvents()"
          />
        </div>

        <!-- Informations principales -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
          <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
              <button
                @click="activeTab = 'infos'"
                :class="[
                  'py-4 px-6 text-center border-b-2 font-medium text-sm leading-5 focus:outline-none transition duration-150 ease-in-out',
                  activeTab === 'infos'
                    ? 'border-indigo-500 text-indigo-600'
                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                ]"
              >
                Informations générales
              </button>
              <button
                @click="activeTab = 'theme'"
                :class="[
                  'py-4 px-6 text-center border-b-2 font-medium text-sm leading-5 focus:outline-none transition duration-150 ease-in-out',
                  activeTab === 'theme'
                    ? 'border-indigo-500 text-indigo-600'
                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                ]"
              >
                Thème du stage
              </button>
              <button
                @click="activeTab = 'evaluation'"
                :class="[
                  'py-4 px-6 text-center border-b-2 font-medium text-sm leading-5 focus:outline-none transition duration-150 ease-in-out',
                  activeTab === 'evaluation'
                    ? 'border-indigo-500 text-indigo-600'
                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                ]"
              >
                Évaluation
              </button>
            </nav>
          </div>

          <!-- Contenu des onglets -->
          <div class="p-6">
            <!-- Informations générales -->
            <div v-if="activeTab === 'infos'" class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <h3 class="text-lg font-semibold mb-4 text-indigo-800">Informations du stage</h3>
                  <div class="bg-indigo-50 p-4 rounded-lg">
                    <p><span class="font-medium">Structure :</span> {{ stage.structure?.libelle || 'Non spécifiée' }}</p>
                    <p><span class="font-medium">Type :</span> {{ stage.type || 'Non renseigné' }}</p>
                    <p><span class="font-medium">Période :</span> {{ formatDate(stage.date_debut) }} - {{ formatDate(stage.date_fin) }}</p>
                    <p><span class="font-medium">Durée :</span> {{ calculateDuration(stage.date_debut, stage.date_fin) }}</p>
                    <p class="mt-2">
                      <span class="font-medium">Statut :</span>
                      <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full"
                        :class="{
                          'bg-green-100 text-green-800': stage.statut === 'Terminé',
                          'bg-blue-100 text-blue-800': stage.statut === 'En cours',
                          'bg-yellow-100 text-yellow-800': stage.statut === 'En attente',
                        }">
                        {{ stage.statut }}
                      </span>
                    </p>
                  </div>
                </div>
                <div>
                  <h3 class="text-lg font-semibold mb-4 text-indigo-800">Maître de stage</h3>
                  <div class="bg-indigo-50 p-4 rounded-lg">
                    <div v-if="stage.maitre_stage_actuel || getMaitreStageActuel()">
                      <div class="flex items-center mb-4">
                        <div class="bg-indigo-100 text-indigo-700 rounded-full w-12 h-12 flex items-center justify-center text-xl font-bold shadow mr-4">
                          {{ getMaitreStageInitials() }}
                        </div>
                        <div>
                          <p class="font-bold text-lg">
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
                          <p class="text-gray-600">
                            <template v-if="stage.maitre_stage_actuel">
                              {{ stage.maitre_stage_actuel.email }}
                            </template>
                            <template v-else>
                              {{ getMaitreStageActuel().email }}
                            </template>
                          </p>
                        </div>
                      </div>
                      <p><span class="font-medium">Date d'affectation :</span> {{ formatDate(getAffectationActuelle()?.date_affectation) }}</p>
                    </div>
                    <div v-else class="text-gray-500 italic p-4 text-center">
                      <p>Aucun maître de stage n'a encore été assigné à ce stage.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Thème du stage -->
            <div v-if="activeTab === 'theme'" class="space-y-6">
              <div v-if="stage.themeStage" class="bg-indigo-50 p-6 rounded-lg">
                <div class="flex justify-between items-start mb-4">
                  <h3 class="text-lg font-semibold text-indigo-800">{{ stage.themeStage.intitule }}</h3>
                  <span class="px-2 py-1 text-xs font-semibold rounded-full"
                    :class="{
                      'bg-green-100 text-green-800': stage.themeStage.etat === 'Validé',
                      'bg-yellow-100 text-yellow-800': stage.themeStage.etat === 'Proposé',
                      'bg-red-100 text-red-800': stage.themeStage.etat === 'Refusé',
                      'bg-blue-100 text-blue-800': stage.themeStage.etat === 'Modifié',
                    }">
                    {{ stage.themeStage.etat }}
                  </span>
                </div>
                <div class="mb-4">
                  <p class="text-gray-700">{{ stage.themeStage.description }}</p>
                </div>
                <div class="mb-4">
                  <p class="font-medium">Mots-clés :</p>
                  <div class="flex flex-wrap gap-2 mt-2">
                    <span v-for="(motCle, index) in getMotsCles(stage.themeStage)" :key="index"
                      class="px-2 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs">
                      {{ motCle }}
                    </span>
                  </div>
                </div>
              </div>
              <div v-else class="bg-indigo-50 p-6 rounded-lg">
                <p class="text-gray-500 italic">Aucun thème n'a encore été proposé pour ce stage.</p>
              </div>
            </div>

            <!-- Évaluation -->
            <div v-if="activeTab === 'evaluation'" class="space-y-6">
              <div class="bg-indigo-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 text-indigo-800">Évaluation du stage</h3>
                <div v-if="stage.note">
                  <p class="mb-2"><span class="font-medium">Note :</span> {{ stage.note }}/20</p>
                  <p class="mb-4"><span class="font-medium">Commentaire :</span> {{ stage.commentaire_evaluation || 'Aucun commentaire' }}</p>
                </div>
                <div v-else>
                  <p class="text-gray-500 italic mb-4">Ce stage n'a pas encore été évalué.</p>
                  <p v-if="stage.statut !== 'Terminé'" class="text-sm text-gray-500 mt-2">
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
  error: String,
  success: String
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
