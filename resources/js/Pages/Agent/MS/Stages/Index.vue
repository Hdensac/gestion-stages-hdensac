<template>
  <Head title="Mes Stages - Maître de Stage" />

  <MSLayout>
    <template #header>
      <div class="flex items-center gap-4 mb-2">
        <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-600 text-white rounded-2xl w-18 h-18 flex items-center justify-center shadow-xl shadow-blue-500/30">
          <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
        </div>
        <div>
          <h1 class="text-3xl font-black bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent leading-tight">
            Mes Stages
          </h1>
          <p class="text-base text-slate-600 mt-1 flex items-center gap-2 font-mono bg-slate-200 px-3 py-1 rounded-lg inline-block">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Suivez et gérez les stages sous votre supervision
          </p>
        </div>
      </div>
    </template>

    <!-- Arrière-plan grisé -->
    <div class="min-h-screen bg-gradient-to-br from-gray-100 via-slate-100 to-gray-200">
      <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <!-- Message d'erreur amélioré -->
          <div v-if="error" class="mb-8 bg-gradient-to-r from-red-100 to-rose-100 border-2 border-red-300 text-red-800 p-6 rounded-3xl shadow-xl transform transition-all duration-300 hover:scale-[1.02]">
            <div class="flex items-center gap-4">
              <div class="p-3 bg-gradient-to-br from-red-500 to-rose-600 rounded-2xl shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
              </div>
              <div>
                <p class="font-bold text-lg">{{ error }}</p>
              </div>
            </div>
          </div>

          <!-- Carte principale - thème bleu -->
          <div class="backdrop-blur-lg bg-white/30 rounded-2xl shadow-lg border border-white/20 mb-6 transform transition-all duration-300 hover:shadow-xl mx-auto" style="max-width: 1400px;">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 py-6 px-8 rounded-t-3xl">
              <h2 class="text-2xl font-black text-white flex items-center gap-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                Liste de mes stages
              </h2>
            </div>
            
            <div class="p-8">
              <!-- Filtres - thème orange -->
              <div class="mb-8 bg-gradient-to-br from-orange-50 via-amber-50 to-orange-50 rounded-3xl p-6 border-2 border-orange-200 shadow-xl">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                  <div>
                    <label for="search" class="block text-base font-bold text-orange-800 mb-3">Rechercher</label>
                    <div class="relative rounded-2xl shadow-lg">
                      <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                      </div>
                      <input
                        id="search"
                        v-model="filters.search"
                        type="text"
                        placeholder="Rechercher un stagiaire..."
                        class="pl-12 w-full rounded-2xl border-2 border-orange-300 shadow-lg focus:ring-2 focus:ring-orange-200 focus:border-orange-500 bg-orange-100 font-medium text-slate-800 placeholder-slate-500 py-3"
                      />
                    </div>
                  </div>
                  
                  <div>
                    <label for="statut" class="block text-base font-bold text-orange-800 mb-3">Statut</label>
                    <select
                      id="statut"
                      v-model="filters.statut"
                      class="w-full rounded-2xl border-2 border-orange-300 shadow-lg focus:ring-2 focus:ring-orange-200 focus:border-orange-500 bg-orange-100 font-medium text-slate-800 py-3"
                    >
                      <option value="">Tous les statuts</option>
                      <option value="En cours">En cours</option>
                      <option value="Terminé">Terminés</option>
                      <option value="En attente">En attente</option>
                    </select>
                  </div>
                  
                  <div class="flex items-end">
                    <button
                      @click="resetFilters"
                      class="px-6 py-3 bg-gradient-to-r from-slate-200 to-slate-300 text-slate-700 rounded-2xl hover:from-slate-300 hover:to-slate-400 transition-all duration-300 shadow-lg flex items-center gap-3 font-bold transform hover:scale-105"
                    >
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                      </svg>
                      Réinitialiser
                    </button>
                  </div>
                </div>
              </div>

              <!-- Résultats vides améliorés -->
              <div v-if="filteredStages.length === 0" class="text-center py-20">
                <div class="bg-gradient-to-br from-slate-100 to-gray-200 rounded-3xl p-16 shadow-xl border-2 border-slate-300">
                  <svg class="w-16 h-16 mx-auto text-slate-500 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                  </svg>
                  <p class="text-slate-600 text-xl font-bold mb-2">Aucun stage correspondant aux critères de recherche</p>
                  <p class="text-lg text-slate-500 font-medium">Modifiez vos filtres ou consultez tous les stages</p>
                </div>
              </div>
              
              <!-- Tableau des stages - thème violet -->
              <div v-else class="border border-gray-100/50 rounded-xl shadow-sm backdrop-blur-lg bg-white/30" style="max-width: 1300px; margin: 0 auto;">
                <div class="overflow-x-auto">
                  <table class="w-full divide-y divide-gray-200/50" style="min-width: 1000px;">
                    <thead class="bg-gray-50/50">
                      <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stagiaire</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Période</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                      </tr>
                    </thead>
                    <tbody class="bg-white/50 divide-y divide-gray-200/50">
                      <tr v-for="(stage, index) in filteredStages" :key="stage.id" 
                          :class="{ 
                            'bg-white/50': index % 2 === 0, 
                            'bg-gray-50/50': index % 2 === 1, 
                            'bg-yellow-50/50': stage.est_reaffecte 
                          }" 
                          class="hover:bg-gray-100/50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                          <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 text-white flex items-center justify-center shadow-lg transform hover:scale-105 transition-transform duration-300">
                              {{ getStagiaireInitials(stage) }}
                            </div>
                            <div class="ml-4">
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
                              <div class="text-xs text-gray-500 truncate max-w-[200px]">
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
                          <div class="text-sm text-gray-900 flex items-center">
                            <svg class="w-4 h-4 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ formatDate(stage.date_debut) }} - {{ formatDate(stage.date_fin) }}
                          </div>
                          <div class="text-xs text-gray-500 mt-1">
                            {{ calculateDuration(stage.date_debut, stage.date_fin) }}
                          </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                          <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                            :class="{
                              'bg-emerald-100/50 text-emerald-800 border border-emerald-200/50': stage.statut === 'Terminé',
                              'bg-blue-100/50 text-blue-800 border border-blue-200/50': stage.statut === 'En cours',
                              'bg-amber-100/50 text-amber-800 border border-amber-200/50': stage.statut === 'En attente',
                            }">
                            <span class="flex items-center">
                              <span v-if="stage.statut === 'En cours'" class="relative flex mr-1.5">
                                <span class="animate-ping absolute h-2 w-2 rounded-full opacity-75" 
                                      :class="{
                                        'bg-blue-400': stage.statut === 'En cours',
                                      }"></span>
                                <span class="relative rounded-full h-2 w-2" 
                                      :class="{
                                        'bg-blue-400': stage.statut === 'En cours',
                                      }"></span>
                              </span>
                              {{ stage.statut }}
                            </span>
                          </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                          <div class="flex space-x-3">
                            <Link
                              :href="route('agent.ms.stages.show', stage.id)"
                              class="text-blue-700 hover:text-blue-900 bg-gradient-to-r from-blue-100 to-sky-100 hover:from-blue-200 hover:to-sky-200 p-3 rounded-2xl transition-all duration-300 transform hover:scale-105 flex items-center shadow-lg border-2 border-blue-300"
                              title="Voir les détails"
                            >
                              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                              </svg>
                            </Link>
                            <!-- N'afficher les boutons d'action que si le stage est actif -->
                           
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

// Fonction pour calculer la durée d'un stage
const calculateDuration = (dateDebut, dateFin) => {
  if (!dateDebut || !dateFin) return 'Durée indéterminée';
  
  const debut = new Date(dateDebut);
  const fin = new Date(dateFin);
  
  // Calculer la différence en jours
  const differenceMs = fin - debut;
  const differenceDays = Math.ceil(differenceMs / (1000 * 60 * 60 * 24));
  
  if (differenceDays <= 0) return 'Erreur de date';
  
  // Convertir en semaines/mois/jours
  if (differenceDays < 7) {
    return `${differenceDays} jour${differenceDays > 1 ? 's' : ''}`;
  } else if (differenceDays < 31) {
    const weeks = Math.floor(differenceDays / 7);
    const remainingDays = differenceDays % 7;
    return `${weeks} semaine${weeks > 1 ? 's' : ''}${remainingDays > 0 ? ` et ${remainingDays} jour${remainingDays > 1 ? 's' : ''}` : ''}`;
  } else {
    const months = Math.floor(differenceDays / 30);
    const remainingDays = differenceDays % 30;
    return `${months} mois${remainingDays > 0 ? ` et ${remainingDays} jour${remainingDays > 1 ? 's' : ''}` : ''}`;
  }
};

// Fonction pour obtenir les initiales du stagiaire
const getStagiaireInitials = (stage) => {
  if (stage.stagiaire_info?.nom) {
    return (stage.stagiaire_info.nom.charAt(0) + (stage.stagiaire_info.prenom?.charAt(0) || '')).toUpperCase();
  } else if (stage.demandeStage?.stagiaire?.user?.nom) {
    return (stage.demandeStage.stagiaire.user.nom.charAt(0) + (stage.demandeStage.stagiaire.user.prenom?.charAt(0) || '')).toUpperCase();
  }
  return 'ST';
};

// Fonction pour réinitialiser les filtres
const resetFilters = () => {
  filters.value = {
    statut: '',
    search: ''
  };
};

// Filtrer les stages en fonction des critères
const filteredStages = computed(() => {
  if (!props.stages) return [];
  
  return props.stages.filter(stage => {
    // Filtre par statut
    if (filters.value.statut && stage.statut !== filters.value.statut) {
      return false;
    }
    
    // Filtre par recherche
    if (filters.value.search) {
      const searchTerm = filters.value.search.toLowerCase();
      const stagiaireNom = stage.stagiaire_info?.nom?.toLowerCase() || stage.demandeStage?.stagiaire?.user?.nom?.toLowerCase() || '';
      const stagiairePrenom = stage.stagiaire_info?.prenom?.toLowerCase() || stage.demandeStage?.stagiaire?.user?.prenom?.toLowerCase() || '';
      const stagiaireEmail = stage.stagiaire_info?.email?.toLowerCase() || stage.demandeStage?.stagiaire?.user?.email?.toLowerCase() || '';
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

// Fonction pour contacter un stagiaire
const contactStagiaire = (stage) => {
  const email = stage.stagiaire_info?.email || stage.demandeStage?.stagiaire?.user?.email;
  if (email) {
    // Remplacer :
    // window.location.href = `mailto:${email}?subject=Stage à ${stage.structure?.libelle || 'notre structure'}`;
    // Par un appel axios similaire à Dashboard.vue
  } else {
    alert('Aucune adresse email disponible pour ce stagiaire.');
  }
};

// Fonction pour mettre à jour le statut d'un stage
const updateStageStatus = (stage, newStatus) => {
  router.post(route('agent.ms.stages.updateStatus', stage.id), {
    statut: newStatus
  }, {
    onSuccess: () => {
      // Le succès sera géré par les messages flash
    }
  });
};
</script>