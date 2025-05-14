<template>
  <Head title="Mes Stages - Maître de Stage" />

  <MSLayout>
    <template #header>
      <div class="flex items-center gap-4 mb-2">
        <div class="bg-green-100 text-green-700 rounded-full w-12 h-12 flex items-center justify-center text-2xl font-bold shadow">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
        </div>
        <h1 class="text-3xl font-extrabold text-green-800 leading-tight">Mes Stages</h1>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Message d'erreur -->
        <div v-if="error" class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md shadow">
          <p class="font-bold">Erreur</p>
          <p>{{ error }}</p>
        </div>

        <!-- Filtres pour les stages -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
          <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
              <h2 class="text-xl font-semibold text-gray-800">Liste de mes stages</h2>
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
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thème</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="stage in filteredStages" :key="stage.id" class="hover:bg-gray-50" :class="{ 'bg-gray-100': stage.est_reaffecte }">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center">
                        <div>
                          <div class="text-sm font-medium text-gray-900">
                            <template v-if="stage.stagiaire_info?.nom">
                              {{ stage.stagiaire_info.nom }} {{ stage.stagiaire_info.prenom }}
                            </template>
                            <template v-else-if="stage.demandeStage?.stagiaire?.user?.nom">
                              {{ stage.demandeStage.stagiaire.user.nom }} {{ stage.demandeStage.stagiaire.user.prenom }}
                            </template>
                            <template v-else>
                              <span class="italic text-gray-500">Nom non disponible</span>
                            </template>
                          </div>
                          <div class="text-sm text-gray-500">
                            <template v-if="stage.stagiaire_info?.email">
                              {{ stage.stagiaire_info.email }}
                            </template>
                            <template v-else-if="stage.demandeStage?.stagiaire?.user?.email">
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

                      <!-- Afficher les informations de réaffectation si le stage a été réaffecté -->
                      <div v-if="stage.est_reaffecte && stage.reaffectation_info" class="mt-2 text-xs bg-yellow-50 p-2 rounded border border-yellow-200">
                        <p class="font-semibold text-yellow-700">Réaffecté à:</p>
                        <p class="text-gray-700">{{ stage.reaffectation_info.nouveau_ms_prenom }} {{ stage.reaffectation_info.nouveau_ms_nom }}</p>
                        <p class="text-gray-600">{{ stage.reaffectation_info.structure_libelle }}</p>
                        <p class="text-gray-500 text-xs mt-1">Le {{ formatDate(stage.reaffectation_info.date_reaffectation) }}</p>
                      </div>
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

                      <!-- Badge de réaffectation -->
                      <span v-if="stage.est_reaffecte" class="mt-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                        Réaffecté
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span v-if="stage.themeStage" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                        :class="{
                          'bg-green-100 text-green-800': stage.themeStage.etat === 'Validé',
                          'bg-yellow-100 text-yellow-800': stage.themeStage.etat === 'Proposé',
                          'bg-red-100 text-red-800': stage.themeStage.etat === 'Refusé',
                          'bg-blue-100 text-blue-800': stage.themeStage.etat === 'Modifié',
                        }">
                        {{ stage.themeStage.etat }}
                      </span>
                      <span v-else class="text-gray-500 italic">Non défini</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <div class="flex space-x-2">
                        <Link
                          :href="route('agent.ms.stages.show', stage.id)"
                          class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-2 py-1 rounded transition-colors duration-200"
                          title="Voir les détails"
                        >
                          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                          </svg>
                        </Link>
                        <!-- N'afficher les boutons d'action que si le stage est actif -->
                        <template v-if="stage.est_actif !== false">
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
                        </template>
                        <!-- Message informatif pour les stages réaffectés -->
                        <span v-else-if="stage.est_reaffecte" class="text-gray-500 text-xs italic">
                          Réaffecté
                        </span>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </MSLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import MSLayout from '@/Layouts/MSLayout.vue';
import { ref, computed } from 'vue';

const props = defineProps({
  stages: Array,
  error: String
});

// Débogage des données reçues
console.log('Stages reçus:', props.stages);
if (props.stages && props.stages.length > 0) {
  console.log('Premier stage:', props.stages[0]);
  console.log('Infos stagiaire du premier stage:', props.stages[0].stagiaire_info);
}

// État pour les filtres
const filters = ref({
  statut: '',
  search: ''
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
  if (!props.stages) return [];

  return props.stages.filter(stage => {
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

// Contacter un stagiaire
const contactStagiaire = (stage) => {
  // Rediriger vers la page de détails du stage avec l'onglet de contact ouvert
  router.visit(route('agent.ms.stages.show', stage.id), {
    data: { openContact: true }
  });
};

// Mettre à jour le statut d'un stage
const updateStageStatus = (stage, newStatus) => {
  if (confirm(`Êtes-vous sûr de vouloir marquer ce stage comme ${newStatus} ?`)) {
    router.post(route('agent.ms.stages.update-status', stage.id), {
      statut: newStatus
    });
  }
};
</script>
