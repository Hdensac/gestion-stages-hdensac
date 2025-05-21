<template>
  <AgentDPAF>
    <template #header>
      <div class="flex items-center gap-4">
        <div class="bg-blue-600 text-white rounded-lg w-12 h-12 flex items-center justify-center shadow-md">
                    <ClipboardDocumentListIcon class="w-6 h-6" />
        </div>
        <div>
          <h1 class="text-xl font-bold text-gray-800 leading-tight md:text-2xl">Demandes de stage</h1>
          <p class="text-sm text-gray-500 mt-1">Gérez les demandes et assignez-les aux structures</p>
        </div>
      </div>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
          <div class="border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-800 py-4 px-6">
            <h2 class="font-medium text-white text-lg flex items-center gap-2">
                            <FunnelIcon class="h-5 w-5" />
              Filtrer les demandes
            </h2>
          </div>

          <div class="p-6">
            <!-- Filtres -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
              <div>
                                <label for="search"
                                    class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <MagnifyingGlassIcon class="h-4 w-4 text-gray-400" />
                  </div>
                                    <input id="search" v-model="search" type="text"
                    placeholder="Rechercher un stagiaire..."
                                        class="w-full rounded-md border-gray-300 pl-10 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-20" />
                </div>
              </div>
              
              <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                                <select id="status" v-model="status"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-20">
                  <option v-for="option in statusOptions" :key="option.value" :value="option.value">
                    {{ option.label }}
                  </option>
                </select>
              </div>
              
              <div>
                                <label for="structure"
                                    class="block text-sm font-medium text-gray-700 mb-1">Structure</label>
                                <select id="structure" v-model="structure_id"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-20">
                  <option value="">Toutes les structures</option>
                  <option v-for="structure in structures" :key="structure.id" :value="structure.id">
                    {{ structure.libelle }}
                  </option>
                </select>
                <div v-if="!structures || structures.length === 0" class="text-xs text-gray-500 mt-1">
                  Chargement des structures...
                </div>
              </div>
            </div>

            <!-- Actions pour les demandes sélectionnées -->
            <div v-if="hasSelectedDemandes" class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-100">
              <div class="flex flex-wrap items-center justify-between gap-3">
                <span class="text-sm text-blue-700 font-medium">
                  {{ selectedDemandes.length }} demande(s) sélectionnée(s)
                </span>
                                <button @click="submitDirectGroupAffectation" :disabled="!canAffectSelectedDemandes"
                  :class="[
                    'px-4 py-2 rounded-md transition-all duration-200 flex items-center gap-2 shadow-sm',
                    canAffectSelectedDemandes
                      ? 'bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white' 
                      : 'bg-gray-200 text-gray-400 cursor-not-allowed'
                  ]">
                  <UserPlusIcon class="h-5 w-5" />
                  <span>Affecter</span>
                </button>
              </div>
            </div>

            <!-- Liste des demandes -->
            <div class="overflow-x-auto border border-gray-100 rounded-lg">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-left">
                      <div class="flex items-center">
                                                <input type="checkbox" :checked="allWithStructureSelected"
                          @change="selectAllWithStructure"
                                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                      </div>
                    </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Stagiaire</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date de soumission</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Structure</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Statut</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="demande in props.demandes?.data" :key="demande.id"
                                        class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                                            <input v-if="hasStructure(demande)" type="checkbox" :value="demande.id"
                        v-model="selectedDemandes"
                                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center gap-3">
                                                <div
                                                    class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                          <UserIcon class="h-5 w-5 text-blue-600" />
                        </div>
                        <div>
                          <div class="text-sm font-medium text-gray-800">
                                                        {{ demande.stagiaire.user.nom }} {{
                                                        demande.stagiaire.user.prenom }}
                          </div>
                                                    <div class="text-xs text-gray-500">{{ demande.stagiaire.user.email
                                                        }}</div>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-700 flex items-center gap-2">
                        <CalendarIcon class="h-4 w-4 text-gray-400" />
                        {{ formatDate(demande.date_soumission) }}
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div v-if="demande.structure" class="flex items-center gap-2">
                                                <div class="text-sm font-medium text-gray-800">{{
                                                    demande.structure.libelle }}</div>
                                                <span
                                                    class="text-xs bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full">{{
                                                    demande.structure.sigle }}</span>
                      </div>
                                            <span v-else class="text-xs text-gray-400 italic">Aucune structure
                                                selectionnée</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="getStatusClass(demande.statut)"
                                                class="px-3 py-1 text-xs font-medium rounded-full inline-flex items-center gap-1">
                                                <span v-if="demande.statut === 'En attente'"
                                                    class="relative flex h-2 w-2">
                      <span 
                                                        class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75"
                                                        :class="getStatusDotClass(demande.statut)"></span>
                                                    <span class="relative inline-flex rounded-full h-2 w-2"
                                                        :class="getStatusDotClass(demande.statut)"></span>
                        </span>
                        {{ demande.statut }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                      <div class="flex gap-2">
                                                <Link :href="route('agent.demandes.show', demande.id)"
                          class="p-1.5 bg-blue-50 text-blue-700 rounded-md hover:bg-blue-100 transition-colors"
                                                    title="Voir les détails">
                          <EyeIcon class="h-4 w-4" />
                        </Link>
                                                <button v-if="demande.statut === 'En attente'"
                          @click="openAffectationModal(demande)" 
                                                    class="p-1.5 bg-amber-50 text-amber-700 rounded-md border border-amber-200 hover:bg-amber-100 transition-colors flex items-center gap-1"
                                                    title="Affecter">
                          <UserPlusIcon class="h-4 w-4" />
                                                    À affecter
                        </button>
                      </div>
                    </td>
                  </tr>
                  <tr v-if="!props.demandes?.data || props.demandes.data.length === 0">
                    <td colspan="6" class="px-6 py-10 text-center">
                      <div class="flex flex-col items-center justify-center">
                        <InboxIcon class="h-12 w-12 text-gray-300 mb-3" />
                        <p class="text-gray-500">Aucune demande trouvée</p>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
                        <div class="mt-6"
                            v-if="props.demandes && props.demandes.links && props.demandes.links.length > 3">
              <div class="flex justify-between items-center">
                <div class="text-sm text-gray-500">
                                    Affichage de {{ props.demandes.from }} à {{ props.demandes.to }} sur {{
                                    props.demandes.total }} demandes
                </div>
                <div class="flex gap-1">
                                    <template v-for="(link, index) in props.demandes.links" :key="index">
                                        <span v-if="!link.url" :class="['px-3 py-1 rounded-md text-sm font-medium bg-gray-100 text-gray-400 cursor-not-allowed']">{{ link.label }}</span>
                                        <Link v-else :href="link.url" :class="['px-3 py-1 rounded-md text-sm font-medium', link.active ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50']">{{ link.label }}</Link>
                                    </template>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal d'affectation -->
    <div v-if="showAffectationModal" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 transition-opacity" @click="closeAffectationModal">
          <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <!-- Modal -->
                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-4 py-3 flex justify-between items-center">
            <h3 class="text-white font-medium">
              {{ selectedDemande ? 'Affecter une structure' : 'Affectation groupée' }}
            </h3>
            <button @click="closeAffectationModal" class="text-white hover:text-gray-200">
                            <XMarkIcon class="h-5 w-5" />
            </button>
          </div>
          
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="mb-4">
              <p class="text-sm text-gray-700">
                {{ selectedDemande 
                                ? `Affecter la demande de ${selectedDemande.stagiaire.user.nom}
                                ${selectedDemande.stagiaire.user.prenom}`
                  : `Affecter ${selectedDemandes.length} demande(s) sélectionnée(s)` 
                }}
              </p>
            </div>
            
            <div class="mb-4">
                            <label for="structure_id"
                                class="block text-sm font-medium text-gray-700 mb-1">Structure</label>
                            <select id="structure_id" v-model="selectedStructureId"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-20">
                <option value="">Sélectionner une structure</option>
                <option v-for="structure in structures" :key="structure.id" :value="structure.id">
                  {{ structure.libelle }}
                </option>
              </select>
            </div>
          </div>
          
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button @click="submitAffectation" :disabled="!selectedStructureId" :class="[
                'sm:ml-3 w-full sm:w-auto px-4 py-2 rounded-md text-white shadow-sm font-medium',
                selectedStructureId 
                  ? 'bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800' 
                  : 'bg-gray-300 cursor-not-allowed'
              ]">
              Affecter
            </button>
                        <button @click="closeAffectationModal"
                            class="mt-3 sm:mt-0 w-full sm:w-auto px-4 py-2 bg-white text-gray-700 rounded-md border border-gray-300 shadow-sm hover:bg-gray-50 font-medium">
              Annuler
            </button>
          </div>
        </div>
      </div>
    </div>
  </AgentDPAF>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, watch, computed, onMounted } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import AgentDPAF from '@/Layouts/AgentDPAF.vue';
import { 
  ClipboardDocumentListIcon, 
  UserIcon, 
  FunnelIcon, 
  MagnifyingGlassIcon, 
  CalendarIcon, 
  UserPlusIcon, 
  EyeIcon, 
  XMarkIcon,
  InboxIcon
} from '@heroicons/vue/24/outline';
import debounce from 'lodash/debounce';

const props = defineProps({
  demandes: Object,
  filters: Object,
  structures: Array
});

const page = usePage();
const toast = ref(null);

// Surveiller les messages flash et les afficher automatiquement
onMounted(() => {
  setTimeout(() => {
    const { flash } = page.props;
    if (flash) {
      if (flash.success && toast.value) {
        toast.value.addToast({
          type: 'success',
          title: 'Succès',
          message: flash.success
        });
      }
      if (flash.error && toast.value) {
        toast.value.addToast({
          type: 'error',
          title: 'Erreur',
          message: flash.error
        });
      }
    }
  }, 100);
});

const search = ref(props.filters?.search || '');
const status = ref(props.filters?.status || '');
const structure_id = ref(props.filters?.structure_id || '');
const selectedDemandes = ref([]);

// Ajout des refs pour le modal d'affectation
const showAffectationModal = ref(false);
const selectedDemande = ref(null);
const selectedStructureId = ref('');

// Fonction pour vérifier si une demande a une structure spécifiée
const hasStructure = (demande) => {
  return demande.structure !== null && demande.structure !== undefined;
};

// Fonction pour gérer la sélection de toutes les demandes avec structure
const selectAllWithStructure = () => {
  const demandesWithStructure = (props.demandes && props.demandes.data ? props.demandes.data : []).filter(hasStructure);
  if (selectedDemandes.value.length === demandesWithStructure.length) {
    selectedDemandes.value = [];
  } else {
    selectedDemandes.value = demandesWithStructure.map(d => d.id);
  }
};

// Fonction pour vérifier si toutes les demandes avec structure sont sélectionnées
const allWithStructureSelected = computed(() => {
  const demandesWithStructure = (props.demandes && props.demandes.data ? props.demandes.data : []).filter(hasStructure);
  return demandesWithStructure.length > 0 &&
         demandesWithStructure.every(d => selectedDemandes.value.includes(d.id));
});

// Debounced search function
const debouncedSearch = debounce(() => {
  router.get(route('agent.demandes'), {
    search: search.value,
    status: status.value,
    structure_id: structure_id.value
  }, {
    preserveState: true,
    preserveScroll: true
  });
}, 300);

// Watch for changes in filters
watch(search, debouncedSearch);
watch(status, debouncedSearch);
watch(structure_id, debouncedSearch);

const statusOptions = [
  { value: '', label: 'Tous les statuts' },
  { value: 'En attente', label: 'En attente' },
  { value: 'En cours', label: 'En cours' },
    { value: 'Acceptée', label: 'Acceptée' },
  { value: 'Refusée', label: 'Refusée' }
];

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('fr-FR');
};

// Fonction pour obtenir la classe du statut
const getStatusClass = (status) => {
  const classes = {
    'En attente': 'bg-amber-100 text-amber-700',
    'En cours': 'bg-blue-100 text-blue-700',
    'Acceptée': 'bg-emerald-100 text-emerald-700',
    'Refusée': 'bg-red-100 text-red-700'
  };
  return classes[status] || 'bg-gray-100 text-gray-700';
};

// Fonction pour obtenir la classe du point de statut
const getStatusDotClass = (status) => {
  const classes = {
    'En attente': 'bg-amber-500',
    'En cours': 'bg-blue-500',
    'Acceptée': 'bg-emerald-500',
    'Refusée': 'bg-red-500'
  };
  return classes[status] || 'bg-gray-500';
};

// Fonction pour ouvrir le modal d'affectation
const openAffectationModal = (demande = null) => {
  if (demande) {
    selectedDemande.value = demande;
    selectedStructureId.value = demande.structure_id || '';
  } else {
    selectedDemande.value = null;
    selectedStructureId.value = '';
  }
  showAffectationModal.value = true;
};

// Fonction pour fermer le modal d'affectation
const closeAffectationModal = () => {
  showAffectationModal.value = false;
  selectedDemande.value = null;
  selectedStructureId.value = '';
};

// Fonction pour l'affectation groupée directe
const submitDirectGroupAffectation = () => {
  if (selectedDemandes.value.length === 0) return;

  const demandesWithStructures = (props.demandes && props.demandes.data ? props.demandes.data : [])
    .filter(d => selectedDemandes.value.includes(d.id))
    .filter(d => d.structure);

  // Afficher une seule notification pour toutes les affectations
  let notificationShown = false;

  // Traiter les demandes séquentiellement pour éviter les problèmes de concurrence
  const processNextDemande = (index = 0) => {
    if (index >= demandesWithStructures.length) {
      // Toutes les demandes ont été traitées
      selectedDemandes.value = [];

      // Afficher une seule notification à la fin
      if (!notificationShown && toast.value) {
        notificationShown = true;
        toast.value.addToast({
          type: 'success',
          title: 'Succès',
          message: 'Les demandes ont été affectées avec succès',
          duration: 3000 // Réduire la durée d'affichage
        });
      }

      // Recharger la page pour mettre à jour les données
      router.reload({ only: ['demandes'] });
      return;
    }

    const demande = demandesWithStructures[index];
    router.post(route('agent.demandes.affecter', demande.id), {
      structure_id: demande.structure.id
    }, {
      preserveScroll: true,
      onSuccess: () => {
        // Passer à la demande suivante
        processNextDemande(index + 1);
      },
      onError: () => {
        // En cas d'erreur, afficher une notification et continuer
        if (toast.value) {
          toast.value.addToast({
            type: 'error',
            title: 'Erreur',
            message: `Erreur lors de l'affectation de la demande ${demande.code_suivi}`,
            duration: 3000
          });
        }
        // Continuer avec la demande suivante malgré l'erreur
        processNextDemande(index + 1);
      }
    });
  };

  // Démarrer le traitement séquentiel
  processNextDemande();
};

// Fonction pour soumettre l'affectation
const submitAffectation = () => {
  if (!selectedStructureId.value) return;

  if (selectedDemande.value) {
    // Affectation individuelle
    router.post(route('agent.demandes.affecter', selectedDemande.value.id), {
      structure_id: selectedStructureId.value
    }, {
      preserveScroll: true,
      onSuccess: () => {
        closeAffectationModal();
        if (toast.value) {
          toast.value.addToast({
            type: 'success',
            title: 'Succès',
            message: 'La demande a été affectée avec succès',
            duration: 3000
          });
        }
        router.reload({ only: ['demandes'] });
      },
      onError: () => {
        if (toast.value) {
          toast.value.addToast({
            type: 'error',
            title: 'Erreur',
            message: 'Une erreur est survenue lors de l\'affectation',
            duration: 3000
          });
        }
      }
    });
  } else {
    // Affectation groupée
    submitDirectGroupAffectation();
  }
};

// Computed property pour vérifier si des demandes sont sélectionnées
const hasSelectedDemandes = computed(() => selectedDemandes.value.length > 0);

// Computed property pour vérifier si les demandes sélectionnées peuvent être affectées
const canAffectSelectedDemandes = computed(() => {
  return selectedDemandes.value.length > 0 &&
         (props.demandes && props.demandes.data ? props.demandes.data : [])
             .filter(d => selectedDemandes.value.includes(d.id))
             .every(d => d.statut === 'En attente');
});
</script>