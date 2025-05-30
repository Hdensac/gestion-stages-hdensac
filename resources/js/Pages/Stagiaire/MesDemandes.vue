<script setup>
import { Head, router, Link } from '@inertiajs/vue3';
import Stagiaire from '@/Layouts/Stagiaire.vue';
import { ref, onMounted, computed, watch } from 'vue';

const props = defineProps({
  demandes: Object,
  errors: Object,
  toast: Object,
  notifications: {
    type: Array,
    default: () => []
  }
});

// Système de toast amélioré avec animations
const toasts = ref([]);
let toastCounter = 0;

const addToast = ({ type = 'info', message = '', duration = 5000, actions = false, onConfirm = null }) => {
  const id = toastCounter++;
  toasts.value.push({
    id,
    type,
    message,
    actions,
    onConfirm,
    timeout: setTimeout(() => removeToast(id), duration)
  });
};

const removeToast = (id) => {
  const index = toasts.value.findIndex(toast => toast.id === id);
  if (index !== -1) {
    clearTimeout(toasts.value[index].timeout);
    toasts.value.splice(index, 1);
  }
};

onMounted(() => {
  if (props.toast) {
    addToast({
      type: props.toast.type,
      message: props.toast.message,
      duration: 5000
    });
  }
});

// Recherche et filtres avancés
const searchQuery = ref('');
const codeRecherche = ref('');
const isSearchExpanded = ref(false);
const statusFilter = ref('all');
const sortBy = ref('date_soumission');
const sortOrder = ref('desc');
const viewMode = ref('table');
const isLoading = ref(false);

// Fonction de recherche par code
const rechercherParCode = () => {
  if (!codeRecherche.value.trim()) return;
  isLoading.value = true;
  router.visit(route('demandes.search'), {
    method: 'post',
    data: { code_suivi: codeRecherche.value.trim() },
    preserveState: false,
    onFinish: () => {
      isLoading.value = false;
    }
  });
};

// Fonction de formatage de date améliorée
const formatDate = (dateString) => {
  if (!dateString) return '-';
  const date = new Date(dateString);
  return date.toLocaleDateString('fr-FR', {
    day: '2-digit',
    month: 'short',
    year: 'numeric'
  });
};

// Fonction pour obtenir une couleur selon le statut avec plus de styles
const getStatusConfig = (statut) => {
  const configs = {
    'En attente': {
      bg: 'bg-gradient-to-r from-amber-50 to-yellow-50',
      text: 'text-amber-800',
      border: 'border-amber-300',
      icon: 'clock',
      pulse: true
    },
    'En cours': {
      bg: 'bg-gradient-to-r from-blue-50 to-indigo-50',
      text: 'text-blue-800',
      border: 'border-blue-300',
      icon: 'refresh',
      pulse: true
    },
    'Acceptée': {
      bg: 'bg-gradient-to-r from-emerald-50 to-green-50',
      text: 'text-emerald-800',
      border: 'border-emerald-300',
      icon: 'check-circle'
    },
    'Refusée': {
      bg: 'bg-gradient-to-r from-rose-50 to-red-50',
      text: 'text-rose-800',
      border: 'border-rose-300',
      icon: 'x-circle'
    }
  };
  return configs[statut] || {
    bg: 'bg-gradient-to-r from-slate-50 to-gray-50',
    text: 'text-slate-800',
    border: 'border-slate-300',
    icon: 'question-mark-circle'
  };
};

// Fonction pour annuler une demande avec amélioration UX
const annulerDemande = (id) => {
  addToast({
    type: 'warning',
    message: 'Êtes-vous sûr de vouloir annuler cette demande ? Cette action est irréversible.',
    duration: 15000,
    actions: true,
    onConfirm: () => {
      isLoading.value = true;
      router.delete(route('mes.demandes.annuler', id), {
        preserveScroll: true,
        onSuccess: () => {
          addToast({
            type: 'success',
            message: 'Demande annulée avec succès',
            duration: 4000
          });
        },
        onError: (errors) => {
          addToast({
            type: 'error',
            message: errors.message || 'Une erreur est survenue lors de l\'annulation',
            duration: 6000
          });
        },
        onFinish: () => {
          isLoading.value = false;
        }
      });
    }
  });
};

const voirDetails = (demandeId) => {
  router.visit(route('mes.demandes.show', demandeId));
};

// Vérifications et calculs
const hasDemandes = computed(() => {
  return props.demandes && props.demandes.data && props.demandes.data.length > 0;
});

// Filtrage et tri avancés
const filteredAndSortedDemandes = computed(() => {
  let demandes = props.demandes?.data || [];
  
  // Filtrage par statut
  if (statusFilter.value !== 'all') {
    demandes = demandes.filter(demande => demande.statut === statusFilter.value);
  }
  
  // Recherche globale
  if (searchQuery.value.trim()) {
    const query = searchQuery.value.toLowerCase();
    demandes = demandes.filter(demande => 
      demande.code_suivi.toLowerCase().includes(query) ||
      (demande.structure?.libelle || '').toLowerCase().includes(query) ||
      demande.nature.toLowerCase().includes(query)
    );
  }
  
  // Tri
  demandes.sort((a, b) => {
    let aValue = a[sortBy.value];
    let bValue = b[sortBy.value];
    
    if (sortBy.value === 'structure') {
      aValue = a.structure?.libelle || '';
      bValue = b.structure?.libelle || '';
    }
    
    if (typeof aValue === 'string') {
      aValue = aValue.toLowerCase();
      bValue = bValue.toLowerCase();
    }
    
    if (sortOrder.value === 'asc') {
      return aValue > bValue ? 1 : -1;
    } else {
      return aValue < bValue ? 1 : -1;
    }
  });
  
  return demandes;
});

// Statistiques
const statistics = computed(() => {
  const demandes = props.demandes?.data || [];
  return {
    total: demandes.length,
    enAttente: demandes.filter(d => d.statut === 'En attente').length,
    enCours: demandes.filter(d => d.statut === 'En cours').length,
    acceptees: demandes.filter(d => d.statut === 'Acceptée').length,
    refusees: demandes.filter(d => d.statut === 'Refusée').length
  };
});

// Fonction de tri
const toggleSort = (column) => {
  if (sortBy.value === column) {
    sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortBy.value = column;
    sortOrder.value = 'asc';
  }
};

// Watch pour recherche en temps réel
watch(searchQuery, () => {
  // Débounce la recherche si nécessaire
}, { debounce: 300 });
</script>

<template>
  <Head title="Mes Demandes de Stage" />
  <Stagiaire :notifications="notifications">
    <template #header>
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div class="flex items-center space-x-3">
          <div class="p-2 bg-gradient-to-r from-indigo-500 to-blue-500 rounded-xl shadow-lg">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
          </div>
          <div>
            <h2 class="text-xl font-bold leading-tight text-gray-800">Mes Demandes de <span class="text-indigo-600">Stage</span></h2>
            <p class="text-base text-gray-600 mt-1 font-medium">Gérez et suivez vos demandes</p>
          </div>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
          <!-- Statistiques rapides -->
          <div class="hidden md:flex items-center gap-2">
            <div class="bg-white/90 rounded-lg px-3 py-1.5 border border-indigo-200 shadow-sm">
              <span class="text-sm text-indigo-600 font-medium">Total: </span>
              <span class="text-base font-bold text-indigo-800">{{ statistics.total }}</span>
            </div>
            <div v-if="statistics.enAttente" class="bg-amber-50 rounded-lg px-3 py-1.5 border border-amber-200 shadow-sm">
              <span class="text-sm text-amber-600 font-medium">En attente: </span>
              <span class="text-base font-bold text-amber-800">{{ statistics.enAttente }}</span>
            </div>
          </div>

          <!-- Toggle view mode -->
          <div class="bg-white/90 rounded-xl shadow-sm border border-indigo-200 p-1 inline-flex">
            <button
              @click="viewMode = 'table'"
              class="p-2.5 rounded-lg transition-all duration-300"
              :class="viewMode === 'table' ? 'bg-indigo-100 text-indigo-700 shadow-sm' : 'text-slate-600 hover:bg-slate-100'"
              title="Vue tableau"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
              </svg>
            </button>
            <button
              @click="viewMode = 'card'"
              class="p-2.5 rounded-lg transition-all duration-300"
              :class="viewMode === 'card' ? 'bg-indigo-100 text-indigo-700 shadow-sm' : 'text-slate-600 hover:bg-slate-100'"
              title="Vue cartes"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
              </svg>
            </button>
          </div>

          <!-- Recherche par code améliorée -->
          <div class="relative">
            <div
              class="bg-white/90 rounded-xl shadow-sm border border-indigo-200 flex items-center overflow-hidden transition-all duration-500 ease-out"
              :class="{ 'w-72': isSearchExpanded, 'w-12': !isSearchExpanded }"
            >
              <input
                v-show="isSearchExpanded"
                ref="searchCodeInput"
                type="text"
                v-model="codeRecherche"
                placeholder="Rechercher par code de suivi..."
                class="w-full py-2.5 px-4 border-none focus:ring-0 text-sm text-indigo-800 bg-transparent placeholder-indigo-400"
                @keyup.enter="rechercherParCode"
                @blur="() => { if (!codeRecherche) isSearchExpanded = false }"
              />
              <button
                @click="() => { 
                  isSearchExpanded = !isSearchExpanded; 
                  if (isSearchExpanded) $nextTick(() => $refs.searchCodeInput?.focus());
                  else if (codeRecherche) rechercherParCode();
                }"
                class="p-2.5 text-indigo-500 hover:text-indigo-800 focus:outline-none transition-colors"
                :class="{ 'bg-indigo-50': isSearchExpanded }"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </button>
            </div>
          </div>

          <!-- Nouveau stage -->
          <button
            @click="router.visit(route('dashboard'))"
            class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-indigo-500 to-blue-500 border border-transparent rounded-xl font-semibold text-sm text-white uppercase tracking-wider hover:from-indigo-600 hover:to-blue-600 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Nouvelle demande
          </button>
        </div>
      </div>
    </template>

    <!-- Toast notifications améliorées -->
    <div class="fixed top-5 right-5 z-50 flex flex-col gap-3 w-96 max-w-[90vw]">
      <TransitionGroup name="toast" tag="div">
        <div
          v-for="toast in toasts"
          :key="toast.id"
          :class="{
            'bg-gradient-to-r from-emerald-50 to-green-50 border-emerald-400 text-emerald-800': toast.type === 'success',
            'bg-gradient-to-r from-rose-50 to-red-50 border-rose-400 text-rose-800': toast.type === 'error',
            'bg-gradient-to-r from-sky-50 to-blue-50 border-sky-400 text-sky-800': toast.type === 'info',
            'bg-gradient-to-r from-amber-50 to-yellow-50 border-amber-400 text-amber-800': toast.type === 'warning'
          }"
          class="p-4 border-l-4 shadow-xl rounded-xl flex flex-col backdrop-blur-md bg-opacity-95 border border-opacity-20"
        >
          <div class="flex justify-between items-start">
            <div class="flex items-start">
              <div class="flex-shrink-0 mr-3">
                <!-- Success icon -->
                <div v-if="toast.type === 'success'" class="p-1 bg-emerald-100 rounded-full">
                  <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                </div>
                <!-- Error icon -->
                <div v-else-if="toast.type === 'error'" class="p-1 bg-rose-100 rounded-full">
                  <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                </div>
                <!-- Warning icon -->
                <div v-else-if="toast.type === 'warning'" class="p-1 bg-amber-100 rounded-full">
                  <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                  </svg>
                </div>
                <!-- Info icon -->
                <div v-else class="p-1 bg-sky-100 rounded-full">
                  <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                </div>
              </div>
              <span class="font-medium text-sm leading-relaxed">{{ toast.message }}</span>
            </div>
            <button @click="removeToast(toast.id)" class="text-slate-400 hover:text-slate-600 focus:outline-none transition-colors ml-4">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>

          <!-- Actions pour confirmation -->
          <div v-if="toast.actions" class="mt-4 flex justify-end gap-3">
            <button
              @click="removeToast(toast.id)"
              class="px-4 py-2 bg-white/80 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-all duration-200 shadow-sm text-sm font-medium"
            >
              Annuler
            </button>
            <button
              @click="() => { toast.onConfirm(); removeToast(toast.id); }"
              class="px-4 py-2 bg-gradient-to-r from-rose-500 to-rose-600 text-white rounded-lg hover:from-rose-600 hover:to-rose-700 transition-all duration-200 shadow-sm text-sm font-medium"
            >
              Confirmer
            </button>
          </div>
        </div>
      </TransitionGroup>
    </div>

    <div class="py-8">
      <div class="mx-auto max-w-full px-4 sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-gradient-to-br from-blue-50/50 via-white to-indigo-50/50 shadow-xl sm:rounded-3xl border border-blue-100/50 backdrop-blur-sm">
          <div class="p-8">
            <!-- En-tête avec recherche et filtres -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-8">
              <div class="flex items-center gap-4">
                <div class="p-3 bg-gradient-to-r from-indigo-500 to-blue-500 rounded-2xl shadow-lg">
                  <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                  </svg>
                </div>
                <div>
                  <h1 class="text-3xl font-bold text-indigo-800 flex items-center gap-2">
                    Historique de mes demandes
                  </h1>
                  <p class="text-indigo-600 mt-1">Suivez l'évolution de vos candidatures</p>
                </div>
              </div>

              <!-- Barre de recherche globale -->
              <div class="flex flex-col sm:flex-row gap-4 items-end">
                <div class="relative">
                  <input
                    type="text"
                    v-model="searchQuery"
                    placeholder="Rechercher dans vos demandes..."
                    class="w-full sm:w-80 px-4 py-3 pl-12 bg-white/80 border border-indigo-200 rounded-xl focus:ring-2 focus:ring-indigo-400 focus:border-transparent text-sm shadow-sm transition-all duration-300"
                  />
                  <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                  </div>
                </div>
              </div>
            </div>

            <!-- Statistiques visuelles -->
            <div v-if="hasDemandes" class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
              <!-- En cours card -->
              <div class="bg-gradient-to-r from-blue-400 to-indigo-500 rounded-xl p-4 text-white shadow-lg">
                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-blue-100 text-sm font-medium">En cours</p>
                    <p class="text-2xl font-bold">{{ statistics.enCours }}</p>
                  </div>
                  <div class="p-2 bg-white/20 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                  </div>
                </div>
              </div>

              <!-- En attente card -->
              <div class="bg-gradient-to-r from-amber-400 to-yellow-500 rounded-xl p-4 text-white shadow-lg">
                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-amber-100 text-sm font-medium">En attente</p>
                    <p class="text-2xl font-bold">{{ statistics.enAttente }}</p>
                  </div>
                  <div class="p-2 bg-white/20 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                </div>
              </div>

              <!-- Acceptées card -->
              <div class="bg-gradient-to-r from-emerald-400 to-green-500 rounded-xl p-4 text-white shadow-lg">
                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-emerald-100 text-sm font-medium">Acceptées</p>
                    <p class="text-2xl font-bold">{{ statistics.acceptees }}</p>
                  </div>
                  <div class="p-2 bg-white/20 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                </div>
              </div>

              <!-- Refusées card -->
              <div class="bg-gradient-to-r from-rose-500 to-red-500 rounded-xl p-4 text-white shadow-lg">
                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-rose-100 text-sm font-medium">Refusées</p>
                    <p class="text-2xl font-bold">{{ statistics.refusees }}</p>
                  </div>
                  <div class="p-2 bg-white/20 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                </div>
              </div>
            </div>

            <!-- Filtres de statut améliorés -->
            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
              <div class="flex items-center gap-3">
                <span class="text-sm text-indigo-700 font-semibold">Filtrer par statut:</span>
                <div class="inline-flex rounded-xl shadow-sm overflow-hidden border border-indigo-200">
                  <button
                    @click="statusFilter = 'all'"
                    class="py-2.5 px-4 text-sm font-medium transition-all duration-300"
                    :class="statusFilter === 'all' ? 'bg-indigo-500 text-white shadow-sm' : 'bg-white/80 text-indigo-700 hover:bg-indigo-50'"
                  >
                    Toutes
                  </button>
                  <button
                    @click="statusFilter = 'En attente'"
                    class="py-2.5 px-4 text-sm font-medium transition-all duration-300"
                    :class="statusFilter === 'En attente' ? 'bg-amber-400 text-white shadow-sm' : 'bg-white/80 text-amber-700 hover:bg-amber-50'"
                  >
                    En attente
                  </button>
                  <button
                    @click="statusFilter = 'En cours'"
                    class="py-2.5 px-4 text-sm font-medium transition-all duration-300"
                    :class="statusFilter === 'En cours' ? 'bg-blue-500 text-white shadow-sm' : 'bg-white/80 text-blue-700 hover:bg-blue-50'"
                  >
                    En cours
                  </button>
                  <button
                    @click="statusFilter = 'Acceptée'"
                    class="py-2.5 px-4 text-sm font-medium transition-all duration-300"
                    :class="statusFilter === 'Acceptée' ? 'bg-emerald-500 text-white shadow-sm' : 'bg-white/80 text-emerald-700 hover:bg-emerald-50'"
                  >
                    Acceptées
                  </button>
                  <button
                    @click="statusFilter = 'Refusée'"
                    class="py-2.5 px-4 text-sm font-medium transition-all duration-300"
                    :class="statusFilter === 'Refusée' ? 'bg-rose-500 text-white shadow-sm' : 'bg-white/80 text-rose-700 hover:bg-rose-50'"
                  >
                    Refusées
                  </button>
                </div>
              </div>
              <div>
                <span class="text-xs text-gray-400">Trier par :</span>
                <select v-model="sortBy" class="ml-2 py-1 px-2 border rounded text-sm">
                  <option value="date_soumission">Date de soumission</option>
                  <option value="structure">Structure</option>
                  <option value="statut">Statut</option>
                </select>
                <button @click="toggleSort(sortBy)" class="ml-2 text-indigo-600 hover:underline text-xs">
                  {{ sortOrder === 'asc' ? '▲' : '▼' }}
                </button>
              </div>
            </div>

            <!-- Tableau des demandes -->
            <div v-if="viewMode === 'table'">
              <div v-if="hasDemandes" class="overflow-x-auto rounded-xl shadow border border-indigo-100 bg-white/80">
                <table class="min-w-full divide-y divide-indigo-100">
                  <thead class="bg-indigo-50">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Code</th>
                      <th class="px-6 py-3 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Structure</th>
                      <th class="px-6 py-3 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Nature</th>
                      <th class="px-6 py-3 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Statut</th>
                      <th class="px-6 py-3 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Soumise le</th>
                      <th class="px-6 py-3"></th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-indigo-50">
                    <tr v-for="demande in filteredAndSortedDemandes" :key="demande.id">
                      <td class="px-6 py-4 font-mono text-lg text-indigo-800 font-bold">{{ demande.code_suivi }}</td>
                      <td class="px-6 py-4 text-lg">{{ demande.structure?.libelle || '-' }}</td>
                      <td class="px-6 py-4 text-lg italic">{{ demande.nature }}</td>
                      <td class="px-6 py-4">
                        <span
                          class="inline-flex items-center px-2.5 py-1 rounded-full font-semibold text-sm"
                          :class="getStatusConfig(demande.statut).bg + ' ' + getStatusConfig(demande.statut).text + ' ' + getStatusConfig(demande.statut).border"
                        >
                          {{ demande.statut }}
                        </span>
                      </td>
                      <td class="px-6 py-4 text-sm">{{ formatDate(demande.date_soumission) }}</td>
                      <td class="px-6 py-4 flex gap-2">
                        <button @click="voirDetails(demande.id)" class="text-indigo-600 hover:underline text-xs font-medium">Détails</button>
                        <button
                          v-if="demande.statut === 'En attente'"
                          @click="annulerDemande(demande.id)"
                          class="text-rose-600 hover:underline text-xs font-medium"
                        >
                          Annuler
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div v-else class="text-center py-12 text-gray-400 italic">
                Aucune demande trouvée.
              </div>
            </div>

            <!-- Vue cartes -->
            <div v-else>
              <div v-if="hasDemandes" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                  v-for="demande in filteredAndSortedDemandes"
                  :key="demande.id"
                  class="bg-white/90 border border-indigo-100 rounded-2xl shadow p-6 flex flex-col gap-3"
                >
                  <div class="flex items-center justify-between">
                    <span class="font-mono text-indigo-700 text-lg font-bold">{{ demande.code_suivi }}</span>
                    <span
                      class="inline-flex items-center px-2.5 py-1 rounded-full font-semibold text-sm"
                      :class="getStatusConfig(demande.statut).bg + ' ' + getStatusConfig(demande.statut).text + ' ' + getStatusConfig(demande.statut).border"
                    >
                      {{ demande.statut }}
                    </span>
                  </div>
                  <div class="mt-2">
                    <div class="text-lg font-bold text-indigo-800">{{ demande.structure?.libelle || '-' }}</div>
                    <div class="text-base text-gray-600 italic">Nature : {{ demande.nature }}</div>
                    <div class="text-base text-gray-500">Soumise le {{ formatDate(demande.date_soumission) }}</div>
                  </div>
                  <div class="flex gap-2 mt-4">
                    <button @click="voirDetails(demande.id)" class="text-indigo-600 hover:underline text-base font-medium">Détails</button>
                    <button
                      v-if="demande.statut === 'En attente'"
                      @click="annulerDemande(demande.id)"
                      class="text-rose-600 hover:underline text-xs font-medium"
                    >
                      Annuler
                    </button>
                  </div>
                </div>
              </div>
              <div v-else class="text-center py-12 text-gray-400 italic">
                Aucune demande trouvée.
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Stagiaire>
</template>

<style scoped>
/* Styles pour les animations de toast */
.toast-enter-active, .toast-leave-active {
  transition: all 0.5s ease;
}
.toast-enter-from, .toast-leave-to {
  opacity: 0;
  transform: translateX(100%);
}

.toast-move {
  transition: transform 0.5s ease;
}

/* Animation pour le halo */
@keyframes pulse-halo {
  0% {
    box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.7);
  }
  70% {
    box-shadow: 0 0 0 10px rgba(79, 70, 229, 0);
  }
  100% {
    box-shadow: 0 0 0 0 rgba(79, 70, 229, 0);
  }
}

.animate-pulse-halo {
  animation: pulse-halo 2s infinite;
}

/* Backdrop blur */
.backdrop-blur-md {
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
}

/* Custom scrollbar for notification panel */
/* Works in Chrome, Edge, Safari */
.max-h-96::-webkit-scrollbar {
  width: 8px;
}

.max-h-96::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

.max-h-96::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 10px;
}

.max-h-96::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}

/* Works in Firefox */
.max-h-96 {
  scrollbar-width: thin;
  scrollbar-color: #cbd5e1 #f1f1f1;
}
</style>