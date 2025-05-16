<template>
  <RSLayout>
    <template #header>
      <div class="flex items-center gap-4 mb-2">
        <div class="bg-blue-600 text-white rounded-lg w-14 h-14 flex items-center justify-center shadow-md">
          <ClipboardDocumentIcon class="w-7 h-7" />
        </div>
        <div>
          <h1 class="text-2xl font-bold text-gray-800 leading-tight md:text-3xl">Détails du Stage</h1>
          <p class="text-sm text-gray-500 mt-1">
            Gérez les informations du stage et les affectations des maîtres de stage
          </p>
        </div>
      </div>
    </template>

    <!-- Composant Toast pour les notifications -->
    <Toast ref="toast" />

    <div class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Bouton de retour et identifiant du stage -->
        <div class="mb-6 flex flex-wrap justify-between items-center gap-4">
          <div class="flex items-center gap-3">
            <div class="bg-blue-100 text-blue-700 px-3 py-1 rounded-md font-semibold text-sm">
              Stage #{{ stage.id }}
            </div>
            <span 
              class="px-3 py-1 text-xs font-semibold rounded-full inline-flex items-center gap-1"
              :class="getStatusColor(stage.statut)"
            >
              <span v-if="stage.statut === 'En cours'" class="relative flex h-2 w-2 mr-1">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 bg-blue-400"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
              </span>
              {{ stage.statut }}
            </span>
          </div>
          
          <Link 
            :href="route('agent.rs.stages')" 
            class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-all duration-200 shadow-sm flex items-center gap-2"
          >
            <ArrowLeftIcon class="w-4 h-4" />
            <span>Retour à la liste</span>
          </Link>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Informations sur le stage -->
          <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-4 py-3">
              <h2 class="text-sm font-medium text-white flex items-center gap-2">
                <DocumentTextIcon class="w-5 h-5" />
                Informations du stage
              </h2>
            </div>
            
            <div class="p-5 space-y-4">
              <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-md border border-gray-100">
                <div class="bg-blue-100 p-2 rounded-md">
                  <CalendarIcon class="w-5 h-5 text-blue-700" />
                </div>
                <div>
                  <div class="text-xs text-gray-500">Période du stage</div>
                  <div class="font-medium text-gray-800">
                    Du {{ formatDate(stage.date_debut) }} au {{ formatDate(stage.date_fin) }}
                  </div>
                </div>
              </div>
              
              <div class="border-t border-gray-100 pt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <div class="text-xs text-gray-500">Structure</div>
                  <div class="font-medium text-gray-800">
                    {{ stage.structure ? stage.structure.libelle : 'Non spécifiée' }}
                  </div>
                </div>
                
                <div>
                  <div class="text-xs text-gray-500">Type de stage</div>
                  <div class="font-medium text-gray-800">{{ stage.type }}</div>
                </div>
                
                <div>
                  <div class="text-xs text-gray-500">Thème</div>
                  <div class="font-medium text-gray-800">
                    {{ stage.theme_stage ? stage.theme_stage.intitule : 'Non défini' }}
                  </div>
                </div>
                
                <div v-if="stage.note">
                  <div class="text-xs text-gray-500">Note</div>
                  <div class="font-medium flex items-center gap-1">
                    <div class="bg-amber-100 text-amber-700 px-2 py-1 rounded font-semibold">
                      {{ stage.note }}/20
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Informations du stagiaire -->
          <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-4 py-3">
              <h2 class="text-sm font-medium text-white flex items-center gap-2">
                <UserIcon class="w-5 h-5" />
                Informations du stagiaire
              </h2>
            </div>
            
            <div class="p-5" v-if="stage.demande_stage?.stagiaire?.user">
              <div class="flex items-center gap-4 mb-4">
                <div class="h-14 w-14 rounded-full bg-blue-100 flex items-center justify-center">
                  <UserIcon class="h-8 w-8 text-blue-600" />
                </div>
                <div>
                  <div class="font-semibold text-gray-800">
                    {{ stage.demande_stage.stagiaire.user.nom }} {{ stage.demande_stage.stagiaire.user.prenom }}
                  </div>
                  <div class="text-sm text-gray-500">
                    {{ stage.demande_stage.stagiaire.user.email }}
                  </div>
                </div>
              </div>
              
              <div class="border-t border-gray-100 pt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <div class="text-xs text-gray-500">Téléphone</div>
                  <div class="font-medium text-gray-800">
                    {{ stage.demande_stage.stagiaire.user.telephone }}
                  </div>
                </div>
                
                <div>
                  <div class="text-xs text-gray-500">Université</div>
                  <div class="font-medium text-gray-800">
                    {{ stage.demande_stage.stagiaire.universite }}
                  </div>
                </div>
                
                <div>
                  <div class="text-xs text-gray-500">Filière</div>
                  <div class="font-medium text-gray-800">
                    {{ stage.demande_stage.stagiaire.filiere }}
                  </div>
                </div>
                
                <div>
                  <div class="text-xs text-gray-500">Niveau d'étude</div>
                  <div class="font-medium text-gray-800">
                    {{ stage.demande_stage.stagiaire.niveau_etude }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Historique des affectations -->
        <div class="mt-6 bg-white rounded-lg shadow-md overflow-hidden">
          <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-4 py-3 flex justify-between items-center">
            <h2 class="text-sm font-medium text-white flex items-center gap-2">
              <UserGroupIcon class="w-5 h-5" />
              Historique des affectations
            </h2>
            
            <button 
              class="text-sm bg-white/20 text-white px-3 py-1.5 rounded-md hover:bg-white/30 transition-all duration-200 flex items-center gap-2"
            >
              <PlusIcon class="w-4 h-4" />
              Nouvelle affectation
            </button>
          </div>

          <div class="p-5">
            <div class="overflow-x-auto border border-gray-100 rounded-lg">
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
                  <tr v-for="affectation in stage.affectations_maitre_stage" :key="affectation.id" class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-700 flex items-center gap-2">
                        <CalendarIcon class="w-4 h-4 text-gray-400" />
                        {{ formatDate(affectation.date_affectation) }}
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div v-if="affectation.maitre_stage" class="flex items-start gap-3">
                        <div class="h-9 w-9 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                          <UserIcon class="h-5 w-5 text-blue-600" />
                        </div>
                        <div>
                          <div class="text-sm font-medium text-gray-800">
                            {{ affectation.maitre_stage.nom }} {{ affectation.maitre_stage.prenom }}
                          </div>
                          <div class="text-xs text-gray-500">{{ affectation.maitre_stage.email }}</div>
                        </div>
                      </div>
                      <div v-else class="text-gray-400 italic text-sm">Non disponible</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div v-if="affectation.agent_affectant?.user" class="text-sm text-gray-700">
                        {{ affectation.agent_affectant.user.nom }} {{ affectation.agent_affectant.user.prenom }}
                      </div>
                      <div v-else class="text-gray-400 italic text-sm">Non disponible</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span
                        class="px-3 py-1 text-xs font-medium rounded-full"
                        :class="getAffectationStatusColor(affectation.statut)"
                      >
                        {{ affectation.statut }}
                      </span>
                    </td>
                  </tr>
                  <tr v-if="!stage.affectations_maitre_stage || stage.affectations_maitre_stage.length === 0">
                    <td colspan="4" class="px-6 py-10 text-center">
                      <div class="flex flex-col items-center justify-center">
                        <UserGroupIcon class="w-12 h-12 text-gray-300 mb-2" />
                        <p class="text-gray-500 text-sm">Aucune affectation trouvée</p>
                        <button class="mt-3 px-4 py-2 bg-blue-50 text-blue-700 rounded-md hover:bg-blue-100 transition-colors text-sm font-medium flex items-center gap-2">
                          <PlusIcon class="w-4 h-4" />
                          Affecter un maître de stage
                        </button>
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
  </RSLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import RSLayout from '@/Layouts/RSLayout.vue';
import Toast from '@/Components/Toast.vue';
import { 
  ClipboardDocumentIcon, 
  UserIcon, 
  CalendarIcon, 
  DocumentTextIcon, 
  UserGroupIcon,
  PlusIcon,
  ArrowLeftIcon
} from '@heroicons/vue/24/outline';

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
      return 'bg-blue-100 text-blue-700';
    case 'Terminé':
      return 'bg-emerald-100 text-emerald-700';
    default:
      return 'bg-gray-100 text-gray-700';
  }
}

// Obtenir la couleur du statut de l'affectation
function getAffectationStatusColor(status) {
  switch (status) {
    case 'En cours':
      return 'bg-blue-100 text-blue-700';
    case 'Acceptée':
      return 'bg-emerald-100 text-emerald-700';
    case 'Terminée':
      return 'bg-gray-100 text-gray-700';
    case 'Refusée':
      return 'bg-red-100 text-red-700';
    default:
      return 'bg-gray-100 text-gray-700';
  }
}
</script>

<style scoped>
/* Animation pour les badges "En cours" */
@keyframes pulse {
  0% {
    transform: scale(0.95);
    box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7);
  }
  
  70% {
    transform: scale(1);
    box-shadow: 0 0 0 5px rgba(59, 130, 246, 0);
  }
  
  100% {
    transform: scale(0.95);
    box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
  }
}
</style>