<template>
  <Head title="Mes Stages - Maître de Stage" />

  <MSLayout>
    <template #header>
      <div class="flex items-center gap-3 mb-2">
        <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-600 text-white rounded-xl w-12 h-12 flex items-center justify-center shadow-lg shadow-blue-500/20">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
        </div>
        <div class="flex-1">
          <h1 class="text-2xl font-black bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent leading-tight">
            Mes Stages
          </h1>
          <p class="text-sm text-slate-600 mt-1 font-medium">
            Suivez et gérez les stages sous votre supervision
          </p>
          <div class="flex items-center gap-3 mt-2">
            <div class="flex items-center gap-2 bg-blue-100 px-2 py-1 rounded-full">
              <div class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-pulse"></div>
              <span class="text-xs font-bold text-blue-700">{{ filteredStages.length }} stage(s)</span>
            </div>
            <div class="text-xs text-slate-500 font-mono">
              {{ new Date().toLocaleTimeString('fr-FR') }}
            </div>
          </div>
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

          <!-- Carte principale moderne -->
          <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl border-2 border-slate-200/50 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-600 to-slate-700 py-6 px-8">
              <h2 class="text-xl font-bold text-white flex items-center gap-3">
                <div class="p-2 bg-white/20 rounded-xl">
                  <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                  </svg>
                </div>
                Liste de mes stages
              </h2>
            </div>
            
            <div class="p-8">
              <!-- Filtres modernes -->
              <div class="mb-8 bg-gradient-to-br from-slate-50 to-slate-100 rounded-2xl p-6 border border-slate-200">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <div>
                    <label for="search" class="block text-sm font-bold text-slate-700 mb-2">Rechercher</label>
                    <div class="relative">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                      </div>
                      <input
                        id="search"
                        v-model="filters.search"
                        type="text"
                        placeholder="Rechercher un stagiaire..."
                        class="pl-10 w-full rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-200 focus:border-blue-500 bg-white text-slate-800 placeholder-slate-400 py-2.5"
                      />
                    </div>
                  </div>

                  <div>
                    <label for="statut" class="block text-sm font-bold text-slate-700 mb-2">Statut</label>
                    <select
                      id="statut"
                      v-model="filters.statut"
                      class="w-full rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-200 focus:border-blue-500 bg-white text-slate-800 py-2.5"
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
                      class="px-4 py-2.5 bg-slate-200 text-slate-700 rounded-xl hover:bg-slate-300 transition-all duration-300 flex items-center gap-2 font-medium"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
              
              <!-- Tableau des stages moderne -->
              <div v-else class="overflow-hidden rounded-2xl border border-slate-200 bg-white/50 backdrop-blur-sm">
                <table class="min-w-full">
                  <thead class="bg-gradient-to-r from-slate-50 to-blue-50">
                    <tr>
                      <th class="px-8 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider border-b border-slate-200">Stagiaire</th>
                      <th class="px-8 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider border-b border-slate-200">Période</th>
                      <th class="px-8 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider border-b border-slate-200">Statut</th>
                      <th class="px-8 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider border-b border-slate-200">Actions</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-100">
                      <tr v-for="(stage, index) in filteredStages" :key="stage.id"
                          class="group hover:bg-blue-50/50 transition-all duration-300"
                          :style="{ animationDelay: `${index * 50}ms` }">
                        <td class="px-8 py-6">
                          <div class="flex items-center gap-4">
                            <div class="relative">
                              <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <span class="text-blue-600 font-bold">{{ getStagiaireInitials(stage) }}</span>
                              </div>
                              <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-400 rounded-full border-2 border-white"></div>
                            </div>
                            <div>
                              <span class="font-bold text-slate-800 group-hover:text-blue-800 transition-colors">
                                <template v-if="stage.stagiaire_info?.nom">
                                  {{ stage.stagiaire_info.nom }} {{ stage.stagiaire_info.prenom }}
                                </template>
                                <template v-else-if="stage.demandeStage?.stagiaire?.user?.nom">
                                  {{ stage.demandeStage.stagiaire.user.nom }} {{ stage.demandeStage.stagiaire.user.prenom }}
                                </template>
                                <template v-else>
                                  <span class="italic text-slate-500">Nom non disponible</span>
                                </template>
                              </span>
                              <p class="text-sm text-slate-500">
                                <template v-if="stage.stagiaire_info?.email">
                                  {{ stage.stagiaire_info.email }}
                                </template>
                                <template v-else-if="stage.demandeStage?.stagiaire?.user?.email">
                                  {{ stage.demandeStage.stagiaire.user.email }}
                                </template>
                                <template v-else>
                                  Email non disponible
                                </template>
                              </p>
                            </div>
                          </div>
                        </td>
                        <td class="px-8 py-6">
                          <div class="flex items-center gap-3">
                            <div class="p-2 bg-slate-100 rounded-xl group-hover:bg-blue-100 transition-colors">
                              <svg class="h-4 w-4 text-slate-500 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                              </svg>
                            </div>
                            <div>
                              <div class="font-semibold text-slate-700">{{ formatDate(stage.date_debut) }} - {{ formatDate(stage.date_fin) }}</div>
                              <div class="text-xs text-slate-500">{{ calculateDuration(stage.date_debut, stage.date_fin) }}</div>
                            </div>
                          </div>
                        </td>
                        <td class="px-8 py-6">
                          <span :class="getModernStatusClass(stage.statut)"
                                class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold rounded-2xl">
                            <div :class="getStatusDotClass(stage.statut)" class="w-2 h-2 rounded-full"></div>
                            {{ stage.statut }}
                          </span>
                        </td>
                        <td class="px-8 py-6 text-right">
                          <div class="flex items-center justify-end gap-2">
                            <Link
                              :href="route('agent.ms.stages.show', stage.id)"
                              class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all duration-200"
                              title="Voir les détails"
                            >
                              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                              </svg>
                            </Link>
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

// Fonctions pour les styles modernes
const getModernStatusClass = (statut) => {
  switch (statut) {
    case 'En cours':
      return 'bg-blue-100 text-blue-800 border border-blue-200';
    case 'Terminé':
      return 'bg-emerald-100 text-emerald-800 border border-emerald-200';
    case 'En attente':
      return 'bg-amber-100 text-amber-800 border border-amber-200';
    default:
      return 'bg-slate-100 text-slate-800 border border-slate-200';
  }
};

const getStatusDotClass = (statut) => {
  switch (statut) {
    case 'En cours':
      return 'bg-blue-500 animate-pulse';
    case 'Terminé':
      return 'bg-emerald-500';
    case 'En attente':
      return 'bg-amber-500';
    default:
      return 'bg-slate-500';
  }
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