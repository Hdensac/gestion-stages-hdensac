<script setup>
import { Head, router, Link } from '@inertiajs/vue3';
import Stagiaire from '@/Layouts/Stagiaire.vue';
import { ref, onMounted, computed } from 'vue';

const props = defineProps({
  demandes: Object,
  errors: Object,
  toast: Object,
});

// Système de toast amélioré
const toasts = ref([]);
let toastCounter = 0;

// Fonction pour ajouter un toast
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

// Fonction pour retirer un toast
const removeToast = (id) => {
  const index = toasts.value.findIndex(toast => toast.id === id);
  if (index !== -1) {
    clearTimeout(toasts.value[index].timeout);
    toasts.value.splice(index, 1);
  }
};

// Afficher le toast à partir des props (si présent)
onMounted(() => {
  if (props.toast) {
    addToast({
      type: props.toast.type,
      message: props.toast.message,
      duration: 5000
    });
  }
});

// Référence pour le code de suivi à rechercher
const codeRecherche = ref('');
const isSearchExpanded = ref(false);

// Fonction pour rechercher une demande par code de suivi
const rechercherParCode = () => {
  if (!codeRecherche.value.trim()) {
    return;
  }
  router.visit(route('demandes.search'), {
    method: 'post',
    data: { code_suivi: codeRecherche.value.trim() },
    preserveState: false
  });
};

// Fonction pour formater une date
const formatDate = (dateString) => {
  if (!dateString) return '-';
  return new Date(dateString).toLocaleDateString('fr-FR');
};

// Fonction pour obtenir une couleur selon le statut
const getStatusColor = (statut) => {
  switch (statut) {
    case 'En attente':
      return 'bg-amber-100 text-amber-800 border-amber-300';
    case 'En cours':
      return 'bg-blue-100 text-blue-800 border-blue-300';
    case 'Acceptée':
      return 'bg-emerald-100 text-emerald-800 border-emerald-300';
    case 'Refusée':
      return 'bg-rose-100 text-rose-800 border-rose-300';
    default:
      return 'bg-slate-100 text-slate-800 border-slate-300';
  }
};

// Fonction pour annuler une demande
const annulerDemande = (id) => {
  addToast({
    type: 'warning',
    message: 'Êtes-vous sûr de vouloir annuler cette demande ?',
    duration: 10000,
    actions: true,
    onConfirm: () => {
      router.delete(route('mes.demandes.annuler', id), {
        preserveScroll: true,
        onSuccess: () => {
          // Le toast de succès sera géré par la logique onMounted
        },
        onError: (errors) => {
          addToast({
            type: 'error',
            message: errors.message || 'Une erreur est survenue lors de l\'annulation',
            duration: 5000
          });
        }
      });
    }
  });
};

// Fonction pour voir les détails d'une demande
const voirDetails = (demandeId) => {
  router.visit(route('mes.demandes.show', demandeId));
};

// Vérifier si des demandes sont disponibles
const hasDemandes = computed(() => {
  return props.demandes && props.demandes.data && props.demandes.data.length > 0;
});

// Filtres pour les demandes
const statusFilter = ref('all');
const filteredDemandes = computed(() => {
  if (statusFilter.value === 'all') {
    return props.demandes?.data || [];
  }
  return (props.demandes?.data || []).filter(demande => demande.statut === statusFilter.value);
});

// Mode affichage (table ou carte)
const viewMode = ref('table'); // 'table' ou 'card'
</script>

<template>
  <Head title="Mes Demandes de Stage" />
  <Stagiaire>
    <template #header>
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2">
          <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          <h2 class="text-xl font-semibold leading-tight text-gray-800">Mes Demandes de <span class="text-indigo-600">Stage</span></h2>
        </div>
        <div class="flex items-center gap-3">
          <!-- Toggle view mode -->
          <div class="bg-white/80 rounded-lg shadow-sm border border-indigo-200 p-1 inline-flex">
            <button
              @click="viewMode = 'table'"
              class="p-2 rounded-md transition-colors"
              :class="viewMode === 'table' ? 'bg-indigo-100 text-indigo-700' : 'text-slate-600 hover:bg-slate-100'"
              title="Vue tableau"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
              </svg>
            </button>
            <button
              @click="viewMode = 'card'"
              class="p-2 rounded-md transition-colors"
              :class="viewMode === 'card' ? 'bg-indigo-100 text-indigo-700' : 'text-slate-600 hover:bg-slate-100'"
              title="Vue cartes"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
              </svg>
            </button>
          </div>

          <!-- Recherche -->
          <div class="relative">
            <div
              class="bg-white/80 rounded-lg shadow-sm border border-indigo-200 flex items-center overflow-hidden"
              :class="{ 'w-64': isSearchExpanded, 'w-10': !isSearchExpanded }"
            >
              <input
                v-show="isSearchExpanded"
                type="text"
                v-model="codeRecherche"
                placeholder="Code de suivi..."
                class="w-full py-2 px-3 border-none focus:ring-0 text-sm text-indigo-800 bg-transparent"
                @keyup.enter="rechercherParCode"
              />
              <button
                @click="isSearchExpanded = !isSearchExpanded; $nextTick(() => isSearchExpanded && $refs.searchInput?.focus())"
                class="p-2 text-indigo-500 hover:text-indigo-800 focus:outline-none"
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
            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-500 to-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:from-indigo-600 hover:to-blue-600 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2 transition-all duration-200 shadow-md"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Nouvelle demande
          </button>
        </div>
      </div>
    </template>

    <!-- Toast notifications -->
    <div class="fixed top-5 right-5 z-50 flex flex-col gap-2 w-96">
      <TransitionGroup name="toast">
        <div
          v-for="toast in toasts"
          :key="toast.id"
          :class="{
            'bg-emerald-50 border-emerald-500 text-emerald-800': toast.type === 'success',
            'bg-rose-50 border-rose-500 text-rose-800': toast.type === 'error',
            'bg-sky-50 border-sky-500 text-sky-800': toast.type === 'info',
            'bg-amber-50 border-amber-500 text-amber-800': toast.type === 'warning'
          }"
          class="p-4 border-l-4 shadow-lg rounded-md flex flex-col backdrop-blur-md bg-opacity-95"
        >
          <div class="flex justify-between items-center">
            <div class="flex items-center">
              <svg
                v-if="toast.type === 'success'"
                class="w-5 h-5 mr-2"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <svg
                v-if="toast.type === 'error'"
                class="w-5 h-5 mr-2"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <svg
                v-if="toast.type === 'warning'"
                class="w-5 h-5 mr-2"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
              </svg>
              <span class="font-medium">{{ toast.message }}</span>
            </div>
            <button @click="removeToast(toast.id)" class="text-slate-500 hover:text-slate-800 focus:outline-none">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>

          <!-- Boutons d'action pour les toasts de confirmation -->
          <div v-if="toast.type === 'warning'" class="mt-3 flex justify-end gap-2">
            <button
              @click="removeToast(toast.id)"
              class="px-3 py-1 bg-white border border-slate-300 text-slate-700 rounded-md hover:bg-slate-50 transition-colors shadow-sm"
            >
              Annuler
            </button>
            <button
              @click="() => { toast.onConfirm(); removeToast(toast.id); }"
              class="px-3 py-1 bg-rose-500 text-white rounded-md hover:bg-rose-600 transition-colors shadow-sm"
            >
              Confirmer
            </button>
          </div>
        </div>
      </TransitionGroup>
    </div>

    <div class="py-6">
      <div class="mx-auto max-w-full px-4 sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-gradient-to-br from-blue-50 via-white to-indigo-50 shadow-lg sm:rounded-2xl border border-blue-100">
          <div class="p-6">
            <div class="flex items-center justify-between mb-6">
              <h1 class="text-2xl font-bold text-indigo-800 flex items-center gap-2">
                <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                Historique de mes demandes
              </h1>

              <!-- Filtres de statut -->
              <div class="flex items-center gap-2">
                <span class="text-sm text-indigo-600 font-medium">Filtrer par:</span>
                <div class="inline-flex rounded-md shadow-sm">
                  <button
                    @click="statusFilter = 'all'"
                    class="py-1.5 px-3 text-xs font-medium rounded-l-md transition-colors border"
                    :class="statusFilter === 'all' ? 'bg-indigo-100 text-indigo-700 border-indigo-300' : 'bg-white/80 text-slate-700 border-indigo-200 hover:bg-indigo-50'"
                  >
                    Tous
                  </button>
                  <button
                    @click="statusFilter = 'En attente'"
                    class="py-1.5 px-3 text-xs font-medium transition-colors border-t border-b border-r"
                    :class="statusFilter === 'En attente' ? 'bg-amber-100 text-amber-700 border-amber-300' : 'bg-white/80 text-slate-700 border-indigo-200 hover:bg-indigo-50'"
                  >
                    En attente
                  </button>
                  <button
                    @click="statusFilter = 'En cours'"
                    class="py-1.5 px-3 text-xs font-medium transition-colors border-t border-b border-r"
                    :class="statusFilter === 'En cours' ? 'bg-blue-100 text-blue-700 border-blue-300' : 'bg-white/80 text-slate-700 border-indigo-200 hover:bg-indigo-50'"
                  >
                    En cours
                  </button>
                  <button
                    @click="statusFilter = 'Acceptée'"
                    class="py-1.5 px-3 text-xs font-medium transition-colors border-t border-b border-r"
                    :class="statusFilter === 'Acceptée' ? 'bg-emerald-100 text-emerald-700 border-emerald-300' : 'bg-white/80 text-slate-700 border-indigo-200 hover:bg-indigo-50'"
                  >
                    Acceptée
                  </button>
                  <button
                    @click="statusFilter = 'Refusée'"
                    class="py-1.5 px-3 text-xs font-medium rounded-r-md transition-colors border-t border-b border-r"
                    :class="statusFilter === 'Refusée' ? 'bg-rose-100 text-rose-700 border-rose-300' : 'bg-white/80 text-slate-700 border-indigo-200 hover:bg-indigo-50'"
                  >
                    Refusée
                  </button>
                </div>
              </div>
            </div>

            <!-- Vue tableau -->
            <div v-if="viewMode === 'table' && hasDemandes" class="overflow-x-auto">
              <table class="min-w-full bg-white/90 border-collapse rounded-lg overflow-hidden shadow-sm">
                <thead>
                  <tr class="border-b border-indigo-200 bg-indigo-50/80">
                    <th class="p-3 text-xs font-semibold tracking-wider text-left text-indigo-800 uppercase">Code de suivi</th>
                    <th class="p-3 text-xs font-semibold tracking-wider text-left text-indigo-800 uppercase">Structure</th>
                    <th class="p-3 text-xs font-semibold tracking-wider text-left text-indigo-800 uppercase">Nature</th>
                    <th class="p-3 text-xs font-semibold tracking-wider text-left text-indigo-800 uppercase">Période</th>
                    <th class="p-3 text-xs font-semibold tracking-wider text-left text-indigo-800 uppercase">Statut</th>
                    <th class="p-3 text-xs font-semibold tracking-wider text-left text-indigo-800 uppercase">Soumis le</th>
                    <th class="p-3 text-xs font-semibold tracking-wider text-left text-indigo-800 uppercase">Actions</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-indigo-100">
                  <tr v-for="demande in filteredDemandes" :key="demande.id" class="hover:bg-indigo-50/50 transition-colors">
                    <td class="p-3 text-sm font-medium text-indigo-600">{{ demande.code_suivi }}</td>
                    <td class="p-3 text-sm text-slate-700">{{ demande.structure?.libelle || '-' }}</td>
                    <td class="p-3 text-sm text-slate-700">{{ demande.nature }}</td>
                    <td class="p-3 text-sm text-slate-700">
                      {{ formatDate(demande.date_debut) }} - {{ formatDate(demande.date_fin) }}
                    </td>
                    <td class="p-3 text-sm">
                      <span
                        class="px-2.5 py-1 text-xs font-medium rounded-full border shadow-sm"
                        :class="getStatusColor(demande.statut)"
                      >
                        {{ demande.statut }}
                      </span>
                    </td>
                    <td class="p-3 text-sm text-slate-700">{{ formatDate(demande.date_soumission) }}</td>
                    <td class="p-3">
                      <div class="flex space-x-2">
                        <button
                          class="p-2 bg-gradient-to-r from-indigo-500 to-blue-500 text-white rounded-md hover:from-indigo-600 hover:to-blue-600 transition-all duration-200 shadow-sm"
                          @click="voirDetails(demande.id)"
                          title="Voir les détails"
                        >
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                          </svg>
                        </button>
                        <button
                          v-if="demande.statut === 'En attente'"
                          class="p-2 bg-white border border-rose-500 text-rose-500 rounded-md hover:bg-rose-50 transition-colors shadow-sm"
                          @click="annulerDemande(demande.id)"
                          title="Annuler la demande"
                        >
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                          </svg>
                        </button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Vue cartes -->
            <div v-if="viewMode === 'card' && hasDemandes" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <div
                v-for="demande in filteredDemandes"
                :key="demande.id"
                class="bg-white/90 rounded-xl border border-indigo-200 shadow-md hover:shadow-lg transition-all duration-300 hover:scale-[1.02] overflow-hidden"
              >
                <div class="border-b border-indigo-100 p-4 bg-gradient-to-r from-indigo-50 to-blue-50">
                  <div class="flex justify-between items-start">
                    <div>
                      <h3 class="font-semibold text-indigo-600">{{ demande.code_suivi }}</h3>
                      <p class="text-indigo-800 font-medium mt-1">{{ demande.structure?.libelle || '-' }}</p>
                    </div>
                    <span
                      class="px-2.5 py-1 text-xs font-medium rounded-full border shadow-sm"
                      :class="getStatusColor(demande.statut)"
                    >
                      {{ demande.statut }}
                    </span>
                  </div>
                </div>
                <div class="p-4 space-y-3">
                  <div class="flex items-start gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <div>
                      <span class="text-xs font-medium text-indigo-600">Nature</span>
                      <p class="text-sm text-slate-700">{{ demande.nature }}</p>
                    </div>
                  </div>
                  <div class="flex items-start gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <div>
                      <span class="text-xs font-medium text-indigo-600">Période</span>
                      <p class="text-sm text-slate-700">{{ formatDate(demande.date_debut) }} - {{ formatDate(demande.date_fin) }}</p>
                    </div>
                  </div>
                  <div class="flex items-start gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                      <span class="text-xs font-medium text-indigo-600">Soumis le</span>
                      <p class="text-sm text-slate-700">{{ formatDate(demande.date_soumission) }}</p>
                    </div>
                  </div>
                </div>
                <div class="border-t border-indigo-100 p-4 flex justify-end gap-2 bg-gradient-to-r from-indigo-50/50 to-blue-50/50">
                  <button
                    class="px-3 py-1.5 bg-gradient-to-r from-indigo-500 to-blue-500 text-white text-sm rounded-md hover:from-indigo-600 hover:to-blue-600 transition-all duration-200 shadow-sm"
                    @click="voirDetails(demande.id)"
                  >
                    Voir détails
                  </button>
                  <button
                    v-if="demande.statut === 'En attente'"
                    class="px-3 py-1.5 bg-white border border-rose-500 text-rose-500 text-sm rounded-md hover:bg-rose-50 transition-colors shadow-sm"
                    @click="annulerDemande(demande.id)"
                  >
                    Annuler
                  </button>
                </div>
              </div>
            </div>

            <!-- Aucune demande -->
            <div v-if="!hasDemandes" class="py-12 text-center bg-gradient-to-br from-indigo-50 via-white to-blue-50 rounded-xl border border-indigo-100 shadow-md">
              <div class="inline-flex justify-center items-center p-5 bg-indigo-100 rounded-full mb-4 shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
              </div>
              <h3 class="text-2xl font-bold text-indigo-800 mb-2">Aucune demande de stage</h3>
              <p class="text-indigo-600 mb-8 max-w-md mx-auto">Vous n'avez pas encore soumis de demande de stage. Commencez dès maintenant !</p>
              <button
                @click="router.visit(route('dashboard'))"
                class="px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-indigo-500 to-blue-500 rounded-lg hover:from-indigo-600 hover:to-blue-600 transition-all duration-200 shadow-md"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Soumettre une demande
              </button>
            </div>

            <!-- Pagination -->
            <div v-if="demandes && demandes.links && demandes.links.length > 3" class="mt-8 px-4 py-3 flex items-center justify-between border-t border-indigo-100 bg-gradient-to-r from-indigo-50/50 to-blue-50/50 rounded-b-lg sm:px-6">
              <!-- Version desktop de la pagination -->
              <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                  <p class="text-sm text-indigo-700 bg-indigo-50/50 px-3 py-1.5 rounded-md border border-indigo-100 inline-flex items-center">
                    <svg class="w-4 h-4 mr-1.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Affichage de <span class="font-medium mx-1">{{ demandes.from }}</span> à
                    <span class="font-medium mx-1">{{ demandes.to }}</span> sur
                    <span class="font-medium mx-1">{{ demandes.total }}</span> demandes
                  </p>
                </div>
                <div>
                  <nav class="relative z-0 inline-flex shadow-sm -space-x-px rounded-md overflow-hidden" aria-label="Pagination">
                    <template v-for="(link, index) in demandes.links" :key="index">
                      <div
                        v-if="!link.url"
                        class="relative inline-flex items-center px-3 py-2 border border-indigo-200 bg-indigo-50/50 text-sm font-medium text-indigo-400 cursor-not-allowed"
                      >
                        <span v-if="link.label === '&laquo; Previous'">« Précédent</span>
                        <span v-else-if="link.label === 'Next &raquo;'">Suivant »</span>
                        <span v-else>{{ link.label }}</span>
                      </div>
                      <Link
                        v-else
                        :href="link.url"
                        class="relative inline-flex items-center px-3 py-2 border text-sm font-medium"
                        :class="[
                          link.active
                            ? 'z-10 bg-indigo-100 border-indigo-500 text-indigo-600 font-semibold'
                            : 'bg-white border-indigo-200 text-indigo-600 hover:bg-indigo-50'
                        ]"
                      >
                        <span v-if="link.label === '&laquo; Previous'">« Précédent</span>
                        <span v-else-if="link.label === 'Next &raquo;'">Suivant »</span>
                        <span v-else>{{ link.label }}</span>
                      </Link>
                    </template>
                  </nav>
                </div>
              </div>

              <!-- Version mobile de la pagination -->
              <div class="flex items-center justify-between sm:hidden">
                <template v-if="demandes.prev_page_url">
                <Link
                  :href="demandes.prev_page_url"
                  class="relative inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-indigo-700 bg-white border border-indigo-200 hover:bg-indigo-50 shadow-sm transition-all duration-200"
                >
                  <svg class="h-4 w-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                  </svg>
                  Précédent
                </Link>
                </template>
                <div v-else class="relative inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-indigo-300 bg-indigo-50/50 border border-indigo-100 cursor-not-allowed">
                  <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                  </svg>
                  Précédent
                </div>

                <template v-if="demandes.next_page_url">
                <Link
                  :href="demandes.next_page_url"
                  class="ml-3 relative inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-indigo-700 bg-white border border-indigo-200 hover:bg-indigo-50 shadow-sm transition-all duration-200"
                >
                  Suivant
                  <svg class="h-4 w-4 ml-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                  </svg>
                </Link>
                </template>
                <div v-else class="ml-3 relative inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-indigo-300 bg-indigo-50/50 border border-indigo-100 cursor-not-allowed">
                  Suivant
                  <svg class="h-4 w-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                  </svg>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Stagiaire>
</template>

<style scoped>
/* Animation pour les toasts */
.toast-enter-active,
.toast-leave-active {
  transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

.toast-enter-from {
  transform: translateX(30px);
  opacity: 0;
}

.toast-leave-to {
  transform: translateX(30px);
  opacity: 0;
}

/* Effet de hover sur les cartes */
.card {
  transition: all 0.3s ease;
}

.card:hover {
  transform: translateY(-2px);
}

/* Animations pour les changements de statut */
@keyframes pulse {
  0% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.05);
  }
  100% {
    transform: scale(1);
  }
}

.pulse {
  animation: pulse 0.6s ease-in-out;
}

/* Masquer les contours au focus pour le navigateur par défaut mais les garder accessibles */
:focus:not(:focus-visible) {
  outline: none;
}

:focus-visible {
  outline: 2px solid rgb(99, 102, 241);
  outline-offset: 2px;
}

/* Media queries pour la réactivité */
@media (max-width: 768px) {
  .overflow-x-auto {
    margin: 0 -1rem;
    width: calc(100% + 2rem);
  }

  table {
    width: 100%;
  }
}
</style>