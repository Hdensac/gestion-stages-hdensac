<template>
  <RSLayout>
    <template #header>
      <div class="flex items-center gap-4 mb-2">
        <div class="bg-gradient-to-br from-blue-600 to-blue-700 text-white rounded-xl w-16 h-16 flex items-center justify-center shadow-lg transform transition-transform hover:scale-105">
          <ClipboardDocumentIcon class="w-8 h-8" />
        </div>
        <div>
          <h1 class="text-2xl font-bold text-gray-800 leading-tight md:text-3xl tracking-tight">Détails du Stage</h1>
          <p class="text-sm text-gray-600 mt-1 font-medium">
            Gérez les informations du stage et les affectations des maîtres de stage
          </p>
        </div>
      </div>
    </template>

    <!-- Composant Toast pour les notifications -->
    <Toast ref="toast" />

    <!-- Fond gris léger pour toute la page -->
    <div class="min-h-screen bg-gray-50 py-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Navigation et identifiant du stage avec design moderne -->
        <div class="mb-8 flex flex-wrap justify-between items-center gap-6">
          <div class="flex items-center gap-4">
            <div class="bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 px-4 py-2 rounded-xl font-bold text-sm shadow-sm border border-blue-200">
              🔖 Stage #{{ stage.id }}
            </div>
            <div class="flex items-center">
              <span 
                class="px-4 py-2 text-sm font-semibold rounded-xl inline-flex items-center gap-2 shadow-sm"
                :class="getStatusStyle(stage.statut)"
              >
                <span v-if="stage.statut === 'En cours'" class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 bg-blue-400"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                </span>
                <span v-else-if="stage.statut === 'Terminé'" class="w-2 h-2 bg-green-500 rounded-full"></span>
                <span v-else class="w-2 h-2 bg-gray-400 rounded-full"></span>
                {{ stage.statut }}
              </span>
            </div>
          </div>
          
          <Link 
            :href="route('agent.rs.stages')" 
            class="px-6 py-3 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-200 shadow-sm hover:shadow-md flex items-center gap-2 font-medium transform hover:scale-105"
          >
            <ArrowLeftIcon class="w-5 h-5" />
            <span>Retour à la liste</span>
          </Link>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
          <!-- Informations sur le stage avec design moderne -->
          <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100/50 backdrop-blur-sm">
            <div class="bg-gradient-to-r from-blue-600 via-blue-700 to-blue-800 px-6 py-4 relative overflow-hidden">
              <div class="absolute inset-0 bg-black/5"></div>
              <div class="relative">
                <h2 class="text-lg font-semibold text-white flex items-center gap-3">
                  <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                    <DocumentTextIcon class="w-5 h-5" />
                  </div>
                  Informations du stage
                </h2>
              </div>
            </div>
            
            <div class="p-6 space-y-6">
              <!-- Période du stage avec design accrocheur -->
              <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-blue-50 to-blue-100/50 rounded-2xl border border-blue-200/50 shadow-sm">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-3 rounded-xl shadow-md">
                  <CalendarIcon class="w-6 h-6 text-white" />
                </div>
                <div>
                  <div class="text-xs font-semibold text-blue-600 uppercase tracking-wide">Période du stage</div>
                  <div class="font-bold text-gray-800 text-lg">
                    Du {{ formatDate(stage.date_debut) }}
                  </div>
                  <div class="font-bold text-gray-800 text-lg">
                    au {{ formatDate(stage.date_fin) }}
                  </div>
                </div>
              </div>
              
              <!-- Autres informations en grille moderne -->
              <div class="border-t border-gray-100 pt-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                  <div class="space-y-2">
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center gap-2">
                      <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                      Structure
                    </div>
                    <div class="font-semibold text-gray-800 text-sm bg-gray-50 px-3 py-2 rounded-lg">
                      {{ stage.structure ? stage.structure.libelle : 'Non spécifiée' }}
                    </div>
                  </div>
                  
                  <div class="space-y-2">
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center gap-2">
                      <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                      Type de stage
                    </div>
                    <div class="font-semibold text-gray-800 text-sm bg-gray-50 px-3 py-2 rounded-lg">
                      {{ stage.type }}
                    </div>
                  </div>
                  
                  <div class="space-y-2">
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center gap-2">
                      <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                      Thème
                    </div>
                    <div class="font-semibold text-gray-800 text-sm bg-gray-50 px-3 py-2 rounded-lg">
                      {{ stage.theme_stage ? stage.theme_stage.intitule : 'Non défini' }}
                    </div>
                  </div>
                  
                  <div v-if="stage.note" class="space-y-2">
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center gap-2">
                      <div class="w-2 h-2 bg-amber-500 rounded-full"></div>
                      Évaluation
                    </div>
                    <div class="flex items-center gap-2">
                      <div class="bg-gradient-to-r from-amber-400 to-amber-500 text-white px-3 py-2 rounded-lg font-bold text-sm shadow-md">
                        ⭐ {{ stage.note }}/20
                      </div>
                      <div class="text-xs text-gray-500">{{ getGradeDescription(stage.note) }}</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Informations du stagiaire avec design moderne -->
          <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100/50 backdrop-blur-sm">
            <div class="bg-gradient-to-r from-emerald-600 via-emerald-700 to-emerald-800 px-6 py-4 relative overflow-hidden">
              <div class="absolute inset-0 bg-black/5"></div>
              <div class="relative">
                <h2 class="text-lg font-semibold text-white flex items-center gap-3">
                  <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                    <UserIcon class="w-5 h-5" />
                  </div>
                  Informations du stagiaire
                </h2>
              </div>
            </div>
            
            <div class="p-6" v-if="stage.demande_stage?.stagiaire?.user">
              <!-- Profil du stagiaire -->
              <div class="flex items-center gap-4 mb-6 p-4 bg-gradient-to-r from-emerald-50 to-emerald-100/50 rounded-2xl border border-emerald-200/50">
                <div class="h-16 w-16 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg">
                  <UserIcon class="h-8 w-8 text-white" />
                </div>
                <div class="flex-1">
                  <div class="font-bold text-gray-800 text-lg">
                    {{ stage.demande_stage.stagiaire.user.nom }} {{ stage.demande_stage.stagiaire.user.prenom }}
                  </div>
                  <div class="text-sm text-emerald-600 font-medium flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    {{ stage.demande_stage.stagiaire.user.email }}
                  </div>
                </div>
              </div>
              
              <!-- Détails académiques -->
              <div class="border-t border-gray-100 pt-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                  <div class="space-y-2">
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center gap-2">
                      <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                      Téléphone
                    </div>
                    <div class="font-semibold text-gray-800 text-sm bg-gray-50 px-3 py-2 rounded-lg flex items-center gap-2">
                      <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                      </svg>
                      {{ stage.demande_stage.stagiaire.user.telephone }}
                    </div>
                  </div>
                  
                  <div class="space-y-2">
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center gap-2">
                      <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                      Université
                    </div>
                    <div class="font-semibold text-gray-800 text-sm bg-gray-50 px-3 py-2 rounded-lg">
                      {{ stage.demande_stage.stagiaire.universite }}
                    </div>
                  </div>
                  
                  <div class="space-y-2">
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center gap-2">
                      <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                      Filière
                    </div>
                    <div class="font-semibold text-gray-800 text-sm bg-gray-50 px-3 py-2 rounded-lg">
                      {{ stage.demande_stage.stagiaire.filiere }}
                    </div>
                  </div>
                  
                  <div class="space-y-2">
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center gap-2">
                      <div class="w-2 h-2 bg-amber-500 rounded-full"></div>
                      Niveau d'étude
                    </div>
                    <div class="font-semibold text-gray-800 text-sm bg-gray-50 px-3 py-2 rounded-lg">
                      {{ stage.demande_stage.stagiaire.niveau_etude }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div v-else class="p-6 text-center">
              <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <UserIcon class="w-8 h-8 text-gray-400" />
              </div>
              <p class="text-gray-500 font-medium">Informations du stagiaire non disponibles</p>
            </div>
          </div>
        </div>

        <!-- Historique des affectations avec design moderne -->
        <div class="mt-8 bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100/50 backdrop-blur-sm">
          <div class="bg-gradient-to-r from-purple-600 via-purple-700 to-purple-800 px-6 py-4 flex justify-between items-center relative overflow-hidden">
            <div class="absolute inset-0 bg-black/5"></div>
            <div class="relative">
              <h2 class="text-lg font-semibold text-white flex items-center gap-3">
                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                  <UserGroupIcon class="w-5 h-5" />
                </div>
                Historique des affectations
                <span class="bg-white/20 text-white px-2 py-1 rounded-lg text-sm font-bold">
                  {{ stage.affectations_maitre_stage ? stage.affectations_maitre_stage.length : 0 }}
                </span>
              </h2>
            </div>
            
            <button 
              class="text-sm bg-white/20 text-white px-4 py-2 rounded-xl hover:bg-white/30 transition-all duration-200 flex items-center gap-2 font-medium shadow-sm hover:shadow-md transform hover:scale-105"
            >
              <PlusIcon class="w-4 h-4" />
              Nouvelle affectation
            </button>
          </div>

          <div class="p-6">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                  <thead class="bg-gradient-to-r from-gray-50 to-gray-100/50">
                    <tr>
                      <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        <div class="flex items-center gap-2">
                          <CalendarIcon class="w-4 h-4" />
                          Date d'affectation
                        </div>
                      </th>
                      <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        <div class="flex items-center gap-2">
                          <UserIcon class="w-4 h-4" />
                          Maître de stage
                        </div>
                      </th>
                      <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        <div class="flex items-center gap-2">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                          </svg>
                          Affecté par
                        </div>
                      </th>
                      <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        <div class="flex items-center gap-2">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                          </svg>
                          Statut
                        </div>
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-50">
                    <tr v-for="affectation in stage.affectations_maitre_stage" :key="affectation.id" class="hover:bg-gray-50/50 transition-all duration-200 group">
                      <td class="px-6 py-5 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-700 flex items-center gap-3">
                          <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                          {{ formatDate(affectation.date_affectation) }}
                        </div>
                      </td>
                      <td class="px-6 py-5 whitespace-nowrap">
                        <div v-if="affectation.maitre_stage" class="flex items-center gap-3">
                          <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center flex-shrink-0 shadow-md">
                            <UserIcon class="h-5 w-5 text-white" />
                          </div>
                          <div>
                            <div class="text-sm font-semibold text-gray-800">
                              {{ affectation.maitre_stage.nom }} {{ affectation.maitre_stage.prenom }}
                            </div>
                            <div class="text-xs text-gray-500 flex items-center gap-1">
                              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                              </svg>
                              {{ affectation.maitre_stage.email }}
                            </div>
                          </div>
                        </div>
                        <div v-else class="text-gray-400 italic text-sm flex items-center gap-2">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                          </svg>
                          Informations non disponibles
                        </div>
                      </td>
                      <td class="px-6 py-5 whitespace-nowrap">
                        <div v-if="affectation.agent_affectant?.user" class="flex items-center gap-3">
                          <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                          </div>
                          <div class="text-sm font-medium text-gray-700">
                            {{ affectation.agent_affectant.user.nom }} {{ affectation.agent_affectant.user.prenom }}
                          </div>
                        </div>
                        <div v-else class="text-gray-400 italic text-sm flex items-center gap-2">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                          </svg>
                          Non disponible
                        </div>
                      </td>
                      <td class="px-6 py-5 whitespace-nowrap">
                        <span
                          class="px-3 py-2 text-xs font-semibold rounded-xl shadow-sm"
                          :class="getAffectationStatusStyle(affectation.statut)"
                        >
                          {{ affectation.statut }}
                        </span>
                      </td>
                    </tr>
                    <tr v-if="!stage.affectations_maitre_stage || stage.affectations_maitre_stage.length === 0">
                      <td colspan="4" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center justify-center space-y-4">
                          <div class="w-20 h-20 bg-purple-100 rounded-2xl flex items-center justify-center">
                            <UserGroupIcon class="w-10 h-10 text-purple-400" />
                          </div>
                          <div class="space-y-2">
                            <p class="text-gray-600 font-medium">Aucune affectation trouvée</p>
                            <p class="text-gray-400 text-sm">Ce stage n'a pas encore de maître de stage assigné</p>
                          </div>
                          <button class="px-6 py-3 bg-gradient-to-r from-purple-50 to-purple-100 text-purple-700 rounded-xl hover:from-purple-100 hover:to-purple-200 transition-all duration-200 font-medium flex items-center gap-2 shadow-sm hover:shadow-md transform hover:scale-105">
                            <PlusIcon class="w-5 h-5" />
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

// Formater une date avec un style plus moderne
function formatDate(date) {
  if (!date) return 'Non définie';
  return new Date(date).toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  });
}

// Obtenir le style du statut du stage
function getStatusStyle(status) {
  switch (status) {
    case 'En cours':
      return 'bg-gradient-to-r from-blue-50 to-blue-100 text-blue-700 border border-blue-200';
    case 'Terminé':
      return 'bg-gradient-to-r from-emerald-50 to-emerald-100 text-emerald-700 border border-emerald-200';
    default:
      return 'bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700 border border-gray-200';
  }
}

// Obtenir le style du statut de l'affectation
function getAffectationStatusStyle(status) {
  switch (status) {
    case 'En cours':
      return 'bg-gradient-to-r from-blue-50 to-blue-100 text-blue-700 border border-blue-200';
    case 'Acceptée':
      return 'bg-gradient-to-r from-emerald-50 to-emerald-100 text-emerald-700 border border-emerald-200';
    case 'Terminée':
      return 'bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700 border border-gray-200';
    case 'Refusée':
      return 'bg-gradient-to-r from-red-50 to-red-100 text-red-700 border border-red-200';
    default:
      return 'bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700 border border-gray-200';
  }
}

// Obtenir la description de la note
function getGradeDescription(note) {
  if (note >= 18) return 'Excellent';
  if (note >= 16) return 'Très bien';
  if (note >= 14) return 'Bien';
  if (note >= 12) return 'Assez bien';
  if (note >= 10) return 'Passable';
  return 'Insuffisant';
}
</script>