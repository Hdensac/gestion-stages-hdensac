<template>
  <Head :title="`Stage de ${getStagiaireName(stage)}`" />

  <MSLayout>
    <template #header>
      <div class="flex items-center gap-4 mb-2">
        <div class="bg-green-100 text-green-700 rounded-full w-12 h-12 flex items-center justify-center text-2xl font-bold shadow">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
        </div>
        <h1 class="text-3xl font-extrabold text-green-800 leading-tight">Détails du Stage</h1>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
          <Link :href="route('agent.ms.stages')" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour à la liste
          </Link>
        </div>

        <!-- Onglets -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
          <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
              <button
                @click="activeTab = 'infos'"
                :class="[
                  'py-4 px-6 text-center border-b-2 font-medium text-sm leading-5 focus:outline-none transition duration-150 ease-in-out',
                  activeTab === 'infos'
                    ? 'border-green-500 text-green-600'
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
                    ? 'border-green-500 text-green-600'
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
                    ? 'border-green-500 text-green-600'
                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                ]"
              >
                Évaluation
              </button>
              <button
                @click="activeTab = 'contact'"
                :class="[
                  'py-4 px-6 text-center border-b-2 font-medium text-sm leading-5 focus:outline-none transition duration-150 ease-in-out',
                  activeTab === 'contact'
                    ? 'border-green-500 text-green-600'
                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                ]"
              >
                Contacter le stagiaire
              </button>
            </nav>
          </div>

          <!-- Contenu des onglets -->
          <div class="p-6">
            <!-- Informations générales -->
            <div v-if="activeTab === 'infos'" class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <h3 class="text-lg font-semibold mb-4">Informations du stagiaire</h3>
                  <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center mb-4">
                      <div class="bg-green-100 text-green-700 rounded-full w-12 h-12 flex items-center justify-center text-xl font-bold shadow mr-4">
                        {{ getStagiaireInitials(stage) }}
                      </div>
                      <div>
                        <p class="font-bold text-lg">{{ getStagiaireName(stage) }}</p>
                        <p class="text-gray-600">{{ getStagiaireEmail(stage) }}</p>
                      </div>
                    </div>
                    <div class="space-y-2">
                      <p><span class="font-medium">Téléphone :</span> {{ stage.demandeStage?.stagiaire?.user?.telephone || 'Non renseigné' }}</p>
                      <p><span class="font-medium">Niveau d'étude :</span> {{ stage.demandeStage?.stagiaire?.niveau_etude || 'Non renseigné' }}</p>
                      <p><span class="font-medium">Université :</span> {{ stage.demandeStage?.stagiaire?.universite || 'Non renseignée' }}</p>
                      <p><span class="font-medium">Filière :</span> {{ stage.demandeStage?.stagiaire?.filiere || 'Non renseignée' }}</p>
                    </div>
                  </div>
                </div>
                <div>
                  <h3 class="text-lg font-semibold mb-4">Informations du stage</h3>
                  <div class="bg-gray-50 p-4 rounded-lg">
                    <p><span class="font-medium">Structure :</span> {{ stage.structure?.libelle }}</p>
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
                    <div class="mt-4" v-if="stage.statut === 'En cours'">
                      <button
                        @click="updateStageStatus(stage, 'Terminé')"
                        class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors duration-200"
                      >
                        Marquer comme terminé
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Thème du stage -->
            <div v-if="activeTab === 'theme'" class="space-y-6">
              <div v-if="stage.themeStage" class="bg-gray-50 p-6 rounded-lg">
                <div class="flex justify-between items-start mb-4">
                  <h3 class="text-lg font-semibold">{{ stage.themeStage.intitule }}</h3>
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
                      class="px-2 py-1 bg-gray-200 text-gray-700 rounded-full text-xs">
                      {{ motCle }}
                    </span>
                  </div>
                </div>
                <div class="flex space-x-3 mt-6" v-if="stage.themeStage.etat === 'Proposé'">
                  <button
                    @click="validerTheme(stage.themeStage.id)"
                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors duration-200"
                  >
                    Valider le thème
                  </button>
                  <button
                    @click="showRefuserThemeModal = true"
                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors duration-200"
                  >
                    Refuser le thème
                  </button>
                </div>
              </div>
              <div v-else class="bg-gray-50 p-6 rounded-lg">
                <p class="text-gray-500 italic">Aucun thème n'a encore été proposé pour ce stage.</p>
              </div>
            </div>

            <!-- Évaluation -->
            <div v-if="activeTab === 'evaluation'" class="space-y-6">
              <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold mb-4">Évaluation du stagiaire</h3>
                <div v-if="stage.note">
                  <p class="mb-2"><span class="font-medium">Note :</span> {{ stage.note }}/20</p>
                  <p class="mb-4"><span class="font-medium">Commentaire :</span> {{ stage.commentaire_evaluation || 'Aucun commentaire' }}</p>
                  <button
                    @click="showEvaluationModal = true; initEvaluationForm()"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200"
                  >
                    Modifier l'évaluation
                  </button>
                </div>
                <div v-else>
                  <p class="text-gray-500 italic mb-4">Ce stage n'a pas encore été évalué.</p>
                  <button
                    @click="showEvaluationModal = true; initEvaluationForm()"
                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors duration-200"
                    :disabled="stage.statut !== 'Terminé'"
                  >
                    Évaluer le stagiaire
                  </button>
                  <p v-if="stage.statut !== 'Terminé'" class="text-sm text-gray-500 mt-2">
                    Le stage doit être marqué comme terminé avant de pouvoir être évalué.
                  </p>
                </div>
              </div>
            </div>

            <!-- Contact -->
            <div v-if="activeTab === 'contact'" class="space-y-6">
              <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold mb-4">Contacter le stagiaire</h3>
                <form @submit.prevent="sendMessage">
                  <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="subject">
                      Sujet
                    </label>
                    <input
                      v-model="contactForm.subject"
                      id="subject"
                      type="text"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                      placeholder="Sujet du message"
                      required
                    />
                  </div>
                  <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="message">
                      Message
                    </label>
                    <textarea
                      v-model="contactForm.message"
                      id="message"
                      rows="5"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                      placeholder="Votre message..."
                      required
                    ></textarea>
                  </div>
                  <div class="flex justify-end">
                    <button
                      type="submit"
                      class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors duration-200"
                    >
                      Envoyer
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal pour refuser le thème -->
    <div v-if="showRefuserThemeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full">
        <div class="p-6 border-b border-gray-200">
          <div class="flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-900">Refuser le thème</h3>
            <button @click="showRefuserThemeModal = false" class="text-gray-400 hover:text-gray-500">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
        <div class="p-6">
          <form @submit.prevent="refuserTheme">
            <div class="mb-6">
              <label class="block text-gray-700 text-sm font-bold mb-2" for="motif_refus">
                Motif du refus
              </label>
              <textarea
                v-model="refusForm.motif"
                id="motif_refus"
                rows="5"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                placeholder="Veuillez expliquer pourquoi vous refusez ce thème..."
                required
              ></textarea>
            </div>
            <div class="flex justify-end space-x-3">
              <button
                type="button"
                @click="showRefuserThemeModal = false"
                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors duration-200"
              >
                Annuler
              </button>
              <button
                type="submit"
                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors duration-200"
              >
                Confirmer le refus
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Modal pour l'évaluation -->
    <div v-if="showEvaluationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full">
        <div class="p-6 border-b border-gray-200">
          <div class="flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-900">Évaluer le stagiaire</h3>
            <button @click="showEvaluationModal = false" class="text-gray-400 hover:text-gray-500">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
        <div class="p-6">
          <form @submit.prevent="submitEvaluation">
            <div class="mb-4">
              <label class="block text-gray-700 text-sm font-bold mb-2" for="note">
                Note (sur 20)
              </label>
              <input
                v-model="evaluationForm.note"
                id="note"
                type="number"
                min="0"
                max="20"
                step="0.5"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                required
              />
            </div>
            <div class="mb-6">
              <label class="block text-gray-700 text-sm font-bold mb-2" for="commentaire">
                Commentaire
              </label>
              <textarea
                v-model="evaluationForm.commentaire"
                id="commentaire"
                rows="5"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                placeholder="Commentaire sur le travail du stagiaire..."
              ></textarea>
            </div>
            <div class="flex justify-end space-x-3">
              <button
                type="button"
                @click="showEvaluationModal = false"
                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors duration-200"
              >
                Annuler
              </button>
              <button
                type="submit"
                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors duration-200"
              >
                Enregistrer l'évaluation
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </MSLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import MSLayout from '@/Layouts/MSLayout.vue';
import { ref, onMounted } from 'vue';

const props = defineProps({
  stage: Object,
  error: String,
  success: String,
  openContact: Boolean
});

// État pour les onglets et modals
const activeTab = ref('infos');
const showRefuserThemeModal = ref(false);
const showEvaluationModal = ref(false);

// Formulaires
const contactForm = ref({
  subject: '',
  message: ''
});

const refusForm = ref({
  motif: ''
});

const evaluationForm = ref({
  note: 0,
  commentaire: ''
});

// Initialiser le formulaire d'évaluation avec les valeurs existantes
const initEvaluationForm = () => {
  evaluationForm.value.note = props.stage.note || 0;
  evaluationForm.value.commentaire = props.stage.commentaire_evaluation || '';
};

// Fonction pour formater les dates
const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  const date = new Date(dateString);
  return date.toLocaleDateString('fr-FR');
};

// Fonction pour calculer la durée entre deux dates
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

// Récupérer le nom du stagiaire
const getStagiaireName = (stage) => {
  if (stage.demandeStage?.stagiaire?.user) {
    return `${stage.demandeStage.stagiaire.user.prenom} ${stage.demandeStage.stagiaire.user.nom}`;
  }
  return 'Stagiaire';
};

// Récupérer l'email du stagiaire
const getStagiaireEmail = (stage) => {
  if (stage.demandeStage?.stagiaire?.user) {
    return stage.demandeStage.stagiaire.user.email;
  }
  return '';
};

// Récupérer les initiales du stagiaire
const getStagiaireInitials = (stage) => {
  if (stage.demandeStage?.stagiaire?.user) {
    const prenom = stage.demandeStage.stagiaire.user.prenom || '';
    const nom = stage.demandeStage.stagiaire.user.nom || '';
    return (prenom.charAt(0) + nom.charAt(0)).toUpperCase();
  }
  return 'S';
};

// Récupérer les mots-clés du thème
const getMotsCles = (themeStage) => {
  if (!themeStage || !themeStage.mots_cles) return [];
  return themeStage.mots_cles.split(',').map(mot => mot.trim());
};

// Mettre à jour le statut d'un stage
const updateStageStatus = (stage, newStatus) => {
  if (confirm(`Êtes-vous sûr de vouloir marquer ce stage comme ${newStatus} ?`)) {
    router.post(route('agent.ms.stages.update-status', stage.id), {
      statut: newStatus
    });
  }
};

// Valider le thème du stage
const validerTheme = (themeId) => {
  if (confirm('Êtes-vous sûr de vouloir valider ce thème ?')) {
    router.post(route('agent.ms.stages.valider-theme', props.stage.id));
  }
};

// Refuser le thème du stage
const refuserTheme = () => {
  router.post(route('agent.ms.stages.refuser-theme', props.stage.id), {
    motif_refus: refusForm.value.motif
  }, {
    onSuccess: () => {
      showRefuserThemeModal.value = false;
      refusForm.value.motif = '';
    }
  });
};

// Soumettre l'évaluation
const submitEvaluation = () => {
  router.post(route('agent.ms.stages.noter', props.stage.id), {
    note: evaluationForm.value.note,
    commentaire: evaluationForm.value.commentaire
  }, {
    onSuccess: () => {
      showEvaluationModal.value = false;
    }
  });
};

// Envoyer un message au stagiaire
const sendMessage = () => {
  // Simuler l'envoi d'un message (à implémenter avec une vraie API)
  alert(`Message envoyé à ${getStagiaireEmail(props.stage)} avec succès !`);
  
  // Réinitialiser le formulaire
  contactForm.value = {
    subject: '',
    message: ''
  };
  
  // Rediriger vers l'onglet infos
  activeTab.value = 'infos';
};

// Ouvrir l'onglet de contact si demandé
onMounted(() => {
  if (props.openContact) {
    activeTab.value = 'contact';
  }
});
</script>
