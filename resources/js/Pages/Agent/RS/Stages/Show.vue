<template>
  <RSLayout>
    <template #header>
      <h2 class="text-xl font-semibold text-gray-800">Détails du Stage</h2>
    </template>

    <!-- Composant Toast pour les notifications -->
    <Toast ref="toast" />

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
          <div class="p-6">
            <div class="mb-6 flex justify-between items-center">
              <h1 class="text-2xl font-bold text-gray-800">Stage #{{ stage.id }}</h1>
              <Link :href="route('agent.rs.stages')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition-colors flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Retour
              </Link>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Informations sur le stage -->
              <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Informations du stage</h2>

                <div class="space-y-3">
                  <div class="flex flex-col">
                    <span class="text-sm text-gray-500">Statut</span>
                    <span class="px-3 py-1 text-sm font-semibold rounded-full inline-block w-fit" :class="getStatusColor(stage.statut)">
                      {{ stage.statut }}
                    </span>
                  </div>

                  <div class="flex flex-col">
                    <span class="text-sm text-gray-500">Structure</span>
                    <span class="font-medium">{{ stage.structure ? stage.structure.libelle : 'Non spécifiée' }}</span>
                  </div>

                  <div class="flex flex-col">
                    <span class="text-sm text-gray-500">Type de stage</span>
                    <span class="font-medium">{{ stage.type }}</span>
                  </div>

                  <div class="flex flex-col">
                    <span class="text-sm text-gray-500">Période</span>
                    <span class="font-medium">Du {{ formatDate(stage.date_debut) }} au {{ formatDate(stage.date_fin) }}</span>
                  </div>

                  <div class="flex flex-col">
                    <span class="text-sm text-gray-500">Thème</span>
                    <span class="font-medium">{{ stage.theme_stage ? stage.theme_stage.intitule : 'Non défini' }}</span>
                  </div>

                  <div v-if="stage.note" class="flex flex-col">
                    <span class="text-sm text-gray-500">Note</span>
                    <span class="font-medium">{{ stage.note }}/20</span>
                  </div>
                </div>
              </div>

              <!-- Informations du stagiaire -->
              <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Informations du stagiaire</h2>

                <div class="space-y-3" v-if="stage.demande_stage?.stagiaire?.user">
                  <div class="flex flex-col">
                    <span class="text-sm text-gray-500">Nom complet</span>
                    <span class="font-medium">{{ stage.demande_stage.stagiaire.user.nom }} {{ stage.demande_stage.stagiaire.user.prenom }}</span>
                  </div>

                  <div class="flex flex-col">
                    <span class="text-sm text-gray-500">Email</span>
                    <span class="font-medium">{{ stage.demande_stage.stagiaire.user.email }}</span>
                  </div>

                  <div class="flex flex-col">
                    <span class="text-sm text-gray-500">Téléphone</span>
                    <span class="font-medium">{{ stage.demande_stage.stagiaire.user.telephone }}</span>
                  </div>

                  <div class="flex flex-col">
                    <span class="text-sm text-gray-500">Université</span>
                    <span class="font-medium">{{ stage.demande_stage.stagiaire.universite }}</span>
                  </div>

                  <div class="flex flex-col">
                    <span class="text-sm text-gray-500">Filière</span>
                    <span class="font-medium">{{ stage.demande_stage.stagiaire.filiere }}</span>
                  </div>

                  <div class="flex flex-col">
                    <span class="text-sm text-gray-500">Niveau d'étude</span>
                    <span class="font-medium">{{ stage.demande_stage.stagiaire.niveau_etude }}</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Historique des affectations -->
            <div class="mt-6 bg-gray-50 p-6 rounded-lg shadow-sm">
              <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Historique des affectations</h2>

              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Date d'affectation
                      </th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Maître de stage
                      </th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Affecté par
                      </th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Statut
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="affectation in stage.affectations_maitre_stage" :key="affectation.id" class="hover:bg-gray-50">
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ formatDate(affectation.date_affectation) }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <div v-if="affectation.maitre_stage">
                          {{ affectation.maitre_stage.nom }} {{ affectation.maitre_stage.prenom }}
                          <div class="text-xs text-gray-400">{{ affectation.maitre_stage.email }}</div>
                        </div>
                        <div v-else class="text-gray-400 italic">Non disponible</div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <div v-if="affectation.agent_affectant?.user">
                          {{ affectation.agent_affectant.user.nom }} {{ affectation.agent_affectant.user.prenom }}
                        </div>
                        <div v-else class="text-gray-400 italic">Non disponible</div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <span
                          class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                          :class="getAffectationStatusColor(affectation.statut)"
                        >
                          {{ affectation.statut }}
                        </span>
                      </td>
                    </tr>
                    <tr v-if="!stage.affectations_maitre_stage || stage.affectations_maitre_stage.length === 0">
                      <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                        Aucune affectation trouvée
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </RSLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import RSLayout from '@/Layouts/RSLayout.vue';
import Toast from '@/Components/Toast.vue';

const props = defineProps({
  stage: Object,
  structure: Object,
});

const toast = ref(null);

// Formater une date
function formatDate(date) {
  if (!date) return 'Non définie';
  return new Date(date).toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });
}

// Obtenir la couleur du statut du stage
function getStatusColor(status) {
  switch (status) {
    case 'En cours':
      return 'text-blue-600 bg-blue-100';
    case 'Terminé':
      return 'text-green-600 bg-green-100';
    default:
      return 'text-gray-600 bg-gray-100';
  }
}

// Obtenir la couleur du statut de l'affectation
function getAffectationStatusColor(status) {
  switch (status) {
    case 'En cours':
      return 'bg-blue-100 text-blue-800';
    case 'Acceptée':
      return 'bg-green-100 text-green-800';
    case 'Terminée':
      return 'bg-gray-100 text-gray-800';
    case 'Refusée':
      return 'bg-red-100 text-red-800';
    default:
      return 'bg-gray-100 text-gray-800';
  }
}
</script>
