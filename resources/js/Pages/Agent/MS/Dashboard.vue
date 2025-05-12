<template>
  <Head title="Dashboard Maître de Stage" />

  <MSLayout>
    <template #header>
      <div class="flex items-center gap-4 mb-2">
        <div class="bg-green-100 text-green-700 rounded-full w-12 h-12 flex items-center justify-center text-2xl font-bold shadow">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
          </svg>
        </div>
        <h1 class="text-3xl font-extrabold text-green-800 leading-tight">Tableau de bord Maître de Stage</h1>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Message d'erreur -->
        <div v-if="error" class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md shadow">
          <p class="font-bold">Erreur</p>
          <p>{{ error }}</p>
        </div>

        <!-- Informations du maître de stage -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
          <div class="flex items-center gap-4">
            <div class="bg-green-100 text-green-700 rounded-full w-16 h-16 flex items-center justify-center text-2xl font-bold shadow">
              <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
            </div>
            <div>
              <h2 class="text-2xl font-bold text-gray-800">{{ agent?.user?.nom }} {{ agent?.user?.prenom }}</h2>
              <p class="text-gray-600">{{ agent?.fonction }}</p>
              <p class="text-green-600 font-semibold">Maître de Stage</p>
            </div>
          </div>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
          <!-- Stages en cours -->
          <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-shadow duration-300 p-6 flex items-center gap-4">
            <div class="p-4 rounded-full bg-blue-100 text-blue-600 shadow">
              <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
              </svg>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-600">Stages en cours</p>
              <p class="text-3xl font-extrabold text-gray-800">{{ stats.stagesEnCours }}</p>
            </div>
          </div>
          <!-- Stages terminés -->
          <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-shadow duration-300 p-6 flex items-center gap-4">
            <div class="p-4 rounded-full bg-green-100 text-green-600 shadow">
              <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-600">Stages terminés</p>
              <p class="text-3xl font-extrabold text-gray-800">{{ stats.stagesTermines }}</p>
            </div>
          </div>
          <!-- Total stagiaires -->
          <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-shadow duration-300 p-6 flex items-center gap-4">
            <div class="p-4 rounded-full bg-purple-100 text-purple-600 shadow">
              <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-600">Total stagiaires</p>
              <p class="text-3xl font-extrabold text-gray-800">{{ stats.totalStagiaires }}</p>
            </div>
          </div>
        </div>

        <!-- Filtres pour les stages -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
          <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
              <h2 class="text-xl font-semibold text-gray-800">Mes stages</h2>
              <div class="flex flex-wrap gap-3">
                <select v-model="filters.statut" class="rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                  <option value="">Tous les statuts</option>
                  <option value="En cours">En cours</option>
                  <option value="Terminé">Terminés</option>
                  <option value="En attente">En attente</option>
                </select>
                <input
                  v-model="filters.search"
                  type="text"
                  placeholder="Rechercher un stagiaire..."
                  class="rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                />
                <button
                  @click="resetFilters"
                  class="px-3 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors duration-200"
                >
                  Réinitialiser
                </button>
              </div>
            </div>

            <div v-if="filteredStages.length === 0" class="text-gray-500 italic py-4">
              Aucun stage correspondant aux critères de recherche.
            </div>
            <div v-else class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stagiaire</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Structure</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Période</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="stage in filteredStages" :key="stage.id" class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center">
                        <div>
                          <div class="text-sm font-medium text-gray-900">
                            <template v-if="stage.demandeStage?.stagiaire?.user?.nom">
                              {{ stage.demandeStage.stagiaire.user.nom }} {{ stage.demandeStage.stagiaire.user.prenom }}
                            </template>
                            <template v-else>
                              <span class="italic text-gray-500">Nom non disponible</span>
                            </template>
                          </div>
                          <div class="text-sm text-gray-500">
                            <template v-if="stage.demandeStage?.stagiaire?.user?.email">
                              {{ stage.demandeStage.stagiaire.user.email }}
                            </template>
                            <template v-else>
                              <span class="italic">Email non disponible</span>
                            </template>
                          </div>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">{{ stage.structure?.libelle }}</div>
                      <div class="text-sm text-gray-500">{{ stage.structure?.sigle }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">
                        {{ formatDate(stage.date_debut) }} - {{ formatDate(stage.date_fin) }}
                      </div>
                      <div class="text-sm text-gray-500">
                        {{ calculateDuration(stage.date_debut, stage.date_fin) }}
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                        :class="{
                          'bg-green-100 text-green-800': stage.statut === 'Terminé',
                          'bg-blue-100 text-blue-800': stage.statut === 'En cours',
                          'bg-yellow-100 text-yellow-800': stage.statut === 'En attente',
                        }">
                        {{ stage.statut }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <div class="flex space-x-2">
                        <button
                          @click="viewStageDetails(stage)"
                          class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-2 py-1 rounded transition-colors duration-200"
                          title="Voir les détails"
                        >
                          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                          </svg>
                        </button>
                        <button
                          @click="contactStagiaire(stage)"
                          class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 px-2 py-1 rounded transition-colors duration-200"
                          title="Contacter le stagiaire"
                        >
                          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                          </svg>
                        </button>
                        <button
                          v-if="stage.statut === 'En cours'"
                          @click="updateStageStatus(stage, 'Terminé')"
                          class="text-purple-600 hover:text-purple-900 bg-purple-50 hover:bg-purple-100 px-2 py-1 rounded transition-colors duration-200"
                          title="Marquer comme terminé"
                        >
                          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                          </svg>
                        </button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Modal pour les détails du stage -->
        <div v-if="showStageDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
          <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
              <div class="flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-900">Détails du stage</h3>
                <button @click="showStageDetailsModal = false" class="text-gray-400 hover:text-gray-500">
                  <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
            </div>
            <div class="p-6" v-if="selectedStage">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <h4 class="text-lg font-semibold mb-2">Informations du stagiaire</h4>
                  <div class="bg-gray-50 p-4 rounded-lg">
                    <p><span class="font-medium">Nom :</span> {{ selectedStage.demandeStage?.stagiaire?.user?.nom }} {{ selectedStage.demandeStage?.stagiaire?.user?.prenom }}</p>
                    <p><span class="font-medium">Email :</span> {{ selectedStage.demandeStage?.stagiaire?.user?.email }}</p>
                    <p><span class="font-medium">Téléphone :</span> {{ selectedStage.demandeStage?.stagiaire?.user?.telephone || 'Non renseigné' }}</p>
                    <p><span class="font-medium">Niveau d'étude :</span> {{ selectedStage.demandeStage?.stagiaire?.niveau_etude || 'Non renseigné' }}</p>
                    <p><span class="font-medium">Université :</span> {{ selectedStage.demandeStage?.stagiaire?.universite || 'Non renseignée' }}</p>
                    <p><span class="font-medium">Filière :</span> {{ selectedStage.demandeStage?.stagiaire?.filiere || 'Non renseignée' }}</p>
                  </div>
                </div>
                <div>
                  <h4 class="text-lg font-semibold mb-2">Informations du stage</h4>
                  <div class="bg-gray-50 p-4 rounded-lg">
                    <p><span class="font-medium">Structure :</span> {{ selectedStage.structure?.libelle }}</p>
                    <p><span class="font-medium">Type :</span> {{ selectedStage.type || 'Non renseigné' }}</p>
                    <p><span class="font-medium">Période :</span> {{ formatDate(selectedStage.date_debut) }} - {{ formatDate(selectedStage.date_fin) }}</p>
                    <p><span class="font-medium">Durée :</span> {{ calculateDuration(selectedStage.date_debut, selectedStage.date_fin) }}</p>
                    <p><span class="font-medium">Statut :</span> {{ selectedStage.statut }}</p>
                    <p><span class="font-medium">Note :</span> {{ selectedStage.note || 'Non évalué' }}</p>
                  </div>
                </div>
              </div>

              <!-- Actions -->
              <div class="mt-6 flex justify-end space-x-3">
                <button
                  @click="contactStagiaire(selectedStage)"
                  class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors duration-200"
                >
                  Contacter le stagiaire
                </button>
                <button
                  v-if="selectedStage.statut === 'En cours'"
                  @click="updateStageStatus(selectedStage, 'Terminé')"
                  class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors duration-200"
                >
                  Marquer comme terminé
                </button>
                <button
                  @click="showStageDetailsModal = false"
                  class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors duration-200"
                >
                  Fermer
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal pour contacter le stagiaire -->
        <div v-if="showContactModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
          <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full">
            <div class="p-6 border-b border-gray-200">
              <div class="flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-900">Contacter le stagiaire</h3>
                <button @click="showContactModal = false" class="text-gray-400 hover:text-gray-500">
                  <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
            </div>
            <div class="p-6" v-if="selectedStage">
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
                <div class="flex justify-end space-x-3">
                  <button
                    type="button"
                    @click="showContactModal = false"
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors duration-200"
                  >
                    Annuler
                  </button>
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
  </MSLayout>
</template>

<script setup>
import { Head, router } from '@inertiajs/vue3';
import MSLayout from '@/Layouts/MSLayout.vue';
import { ref, computed, onMounted } from 'vue';

const props = defineProps({
  stats: Object,
  derniersStages: Array,
  agent: Object,
  error: String
});

// État pour les filtres
const filters = ref({
  statut: '',
  search: ''
});

// État pour les modals
const showStageDetailsModal = ref(false);
const showContactModal = ref(false);
const selectedStage = ref(null);
const contactForm = ref({
  subject: '',
  message: ''
});

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

// Filtrer les stages en fonction des critères de recherche
const filteredStages = computed(() => {
  if (!props.derniersStages) return [];

  return props.derniersStages.filter(stage => {
    // Filtrer par statut si un statut est sélectionné
    if (filters.value.statut && stage.statut !== filters.value.statut) {
      return false;
    }

    // Filtrer par recherche si une recherche est effectuée
    if (filters.value.search) {
      const searchTerm = filters.value.search.toLowerCase();
      const stagiaireNom = stage.demandeStage?.stagiaire?.user?.nom?.toLowerCase() || '';
      const stagiairePrenom = stage.demandeStage?.stagiaire?.user?.prenom?.toLowerCase() || '';
      const stagiaireEmail = stage.demandeStage?.stagiaire?.user?.email?.toLowerCase() || '';
      const structureLibelle = stage.structure?.libelle?.toLowerCase() || '';
      const structureSigle = stage.structure?.sigle?.toLowerCase() || '';

      return stagiaireNom.includes(searchTerm) ||
             stagiairePrenom.includes(searchTerm) ||
             stagiaireEmail.includes(searchTerm) ||
             structureLibelle.includes(searchTerm) ||
             structureSigle.includes(searchTerm);
    }

    return true;
  });
});

// Réinitialiser les filtres
const resetFilters = () => {
  filters.value = {
    statut: '',
    search: ''
  };
};

// Afficher les détails d'un stage
const viewStageDetails = (stage) => {
  selectedStage.value = stage;
  showStageDetailsModal.value = true;
};

// Contacter un stagiaire
const contactStagiaire = (stage) => {
  selectedStage.value = stage;
  contactForm.value = {
    subject: `Stage - ${stage.structure?.libelle || 'Non spécifié'}`,
    message: ''
  };
  showContactModal.value = true;
};

// Envoyer un message au stagiaire
const sendMessage = () => {
  // Simuler l'envoi d'un message (à implémenter avec une vraie API)
  alert(`Message envoyé à ${selectedStage.value.demandeStage?.stagiaire?.user?.email} avec succès !`);

  // Fermer la modal
  showContactModal.value = false;

  // Réinitialiser le formulaire
  contactForm.value = {
    subject: '',
    message: ''
  };
};

// Mettre à jour le statut d'un stage
const updateStageStatus = (stage, newStatus) => {
  // Simuler la mise à jour du statut (à implémenter avec une vraie API)
  if (confirm(`Êtes-vous sûr de vouloir marquer ce stage comme ${newStatus} ?`)) {
    // Dans une vraie implémentation, vous feriez une requête API ici
    router.post(route('agent.ms.update-stage-status', { stage: stage.id }), {
      statut: newStatus
    }, {
      onSuccess: () => {
        // Fermer la modal si elle est ouverte
        if (showStageDetailsModal.value) {
          showStageDetailsModal.value = false;
        }

        // Recharger la page pour voir les changements
        router.reload();
      },
      onError: (errors) => {
        console.error('Erreur lors de la mise à jour du statut du stage:', errors);
        alert('Une erreur est survenue lors de la mise à jour du statut du stage.');
      },
      // Simuler la réponse pour le moment
      _simulate: true
    });
  }
};
</script>
