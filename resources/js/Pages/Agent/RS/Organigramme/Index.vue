<template>
  <RSLayout>
    <template #header>
      <div class="flex items-center gap-4 mb-2">
        <div class="bg-gradient-to-br from-purple-600 via-purple-700 to-violet-600 text-white rounded-2xl w-18 h-18 flex items-center justify-center shadow-xl shadow-purple-500/30">
          <UsersIcon class="w-10 h-10" />
        </div>
        <div>
          <h1 class="text-3xl font-black bg-gradient-to-r from-slate-800 to-purple-600 bg-clip-text text-transparent leading-tight">
            Organigramme
          </h1>
          <p class="text-base text-slate-600 mt-1 font-medium">Visualisez et gérez la hiérarchie de votre organisation</p>
        </div>
      </div>
    </template>
    
    <Toast ref="toastRef" />
    
    <!-- Arrière-plan moderne avec dégradé -->
    <div class="min-h-screen bg-gradient-to-br from-purple-50 via-violet-50 to-purple-50">
      <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <!-- Contenu principal modernisé -->
          <div v-if="props.structure" class="bg-gradient-to-br from-white via-purple-50/30 to-white rounded-3xl shadow-2xl border-2 border-purple-200 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 via-purple-700 to-violet-700 py-8 px-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
              <div class="flex items-center gap-4">
                <div class="bg-white/20 p-4 rounded-2xl shadow-lg backdrop-blur-sm">
                  <BuildingOfficeIcon class="w-7 h-7 text-white" />
                </div>
                <div>
                  <h2 class="font-black text-white text-2xl">Structure organisationnelle</h2>
                  <p class="text-purple-100 font-medium mt-1">Hiérarchie complète de l'organisation</p>
                </div>
              </div>

              <div class="flex flex-wrap items-center gap-3 no-print">
                <button
                  @click="isAllExpanded ? collapseAll() : expandAll()"
                  class="bg-white/20 text-white px-6 py-3 rounded-2xl hover:bg-white/30 transition-all duration-300 flex items-center gap-3 backdrop-blur-sm font-bold shadow-xl hover:shadow-2xl transform hover:scale-105"
                >
                  <span class="text-base">{{ isAllExpanded ? 'Tout replier' : 'Tout déplier' }}</span>
                  <svg class="w-5 h-5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      :d="isAllExpanded ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'" />
                  </svg>
                </button>

                <button
                  class="bg-white/20 text-white px-6 py-3 rounded-2xl hover:bg-white/30 transition-all duration-300 flex items-center gap-3 backdrop-blur-sm font-bold shadow-xl hover:shadow-2xl transform hover:scale-105"
                  @click="refreshPage"
                >
                  <ArrowPathIcon class="w-5 h-5" />
                  <span class="text-base">Actualiser</span>
                </button>
                
                <button
                  class="bg-white/20 text-white px-6 py-3 rounded-2xl hover:bg-white/30 transition-all duration-300 flex items-center gap-3 backdrop-blur-sm font-bold shadow-xl hover:shadow-2xl transform hover:scale-105"
                  @click="printOrganigramme"
                >
                  <PrinterIcon class="w-5 h-5" />
                  <span class="text-base">Imprimer</span>
                </button>
              </div>
            </div>

            <!-- Contenu de l'organigramme -->
            <div class="p-10">
              <div class="bg-gradient-to-br from-purple-50 via-white to-violet-50 rounded-3xl p-8 border-2 border-purple-100 shadow-inner">
                <StructureTree
                  ref="structureTree"
                  class="structure-tree-component"
                  :structure="props.structure"
                  :agents="props.agents"
                />
              </div>
            </div>
          </div>

          <!-- État vide modernisé -->
          <div v-else class="bg-gradient-to-br from-white via-purple-50/30 to-white rounded-3xl shadow-2xl border-2 border-purple-200 p-16 text-center">
            <div class="max-w-lg mx-auto">
              <div class="bg-gradient-to-br from-purple-100 to-violet-200 rounded-3xl h-24 w-24 flex items-center justify-center mx-auto mb-8 shadow-xl">
                <FolderOpenIcon class="w-12 h-12 text-purple-600" />
              </div>
              <h3 class="text-2xl font-black text-slate-900 mb-4">
                Aucune structure principale trouvée
              </h3>
              <p class="text-lg font-medium text-slate-600 mb-8 leading-relaxed">
                Aucune structure principale n'a été trouvée pour ce responsable.
                Veuillez contacter l'administrateur pour créer la structure.
              </p>
              <button class="px-8 py-4 bg-gradient-to-r from-purple-600 to-violet-600 text-white font-black text-lg rounded-2xl hover:from-purple-700 hover:to-violet-700 transition-all duration-300 flex items-center gap-3 mx-auto shadow-xl hover:shadow-2xl transform hover:scale-105">
                <PlusCircleIcon class="w-6 h-6" />
                <span>Créer une structure</span>
              </button>
            </div>
          </div>

          <!-- Section d'informations supplémentaires -->
          <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gradient-to-br from-purple-100 to-violet-100 p-6 rounded-3xl border-2 border-purple-200 shadow-xl">
              <div class="flex items-center gap-4 mb-4">
                <div class="bg-gradient-to-br from-purple-600 to-violet-600 p-3 rounded-2xl shadow-lg">
                  <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                  </svg>
                </div>
                <h4 class="text-lg font-black text-purple-900">Navigation rapide</h4>
              </div>
              <p class="text-base font-medium text-purple-800">
                Utilisez les boutons "Tout déplier/replier" pour naviguer facilement dans l'organigramme.
              </p>
            </div>

            <div class="bg-gradient-to-br from-violet-100 to-purple-100 p-6 rounded-3xl border-2 border-violet-200 shadow-xl">
              <div class="flex items-center gap-4 mb-4">
                <div class="bg-gradient-to-br from-violet-600 to-purple-600 p-3 rounded-2xl shadow-lg">
                  <PrinterIcon class="w-6 h-6 text-white" />
                </div>
                <h4 class="text-lg font-black text-violet-900">Impression</h4>
              </div>
              <p class="text-base font-medium text-violet-800">
                Imprimez l'organigramme complet avec une mise en page optimisée.
              </p>
            </div>

            <div class="bg-gradient-to-br from-purple-100 to-violet-100 p-6 rounded-3xl border-2 border-purple-200 shadow-xl">
              <div class="flex items-center gap-4 mb-4">
                <div class="bg-gradient-to-br from-purple-600 to-violet-600 p-3 rounded-2xl shadow-lg">
                  <ArrowPathIcon class="w-6 h-6 text-white" />
                </div>
                <h4 class="text-lg font-black text-purple-900">Actualisation</h4>
              </div>
              <p class="text-base font-medium text-purple-800">
                Rechargez les données pour voir les dernières modifications de structure.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </RSLayout>
</template>

<script setup>
import RSLayout from '@/Layouts/RSLayout.vue';
import StructureTree from './StructureTree.vue';
import Toast from '@/Components/Toast.vue';
import { onMounted, ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import {
  UsersIcon,
  BuildingOfficeIcon,
  ArrowPathIcon,
  PrinterIcon,
  FolderOpenIcon,
  PlusCircleIcon
} from '@heroicons/vue/24/outline';

const props = defineProps({
  structure: Object,
  agents: Array
});

const toastRef = ref(null);
const structureTree = ref(null);
const page = usePage();
const isAllExpanded = ref(true); // Par défaut, tout est déplié

function showToastFromFlash() {
  if (page.props.flash?.success) {
    toastRef.value?.addToast({
      type: 'success',
      title: 'Succès',
      message: page.props.flash.success
    });
  }
  if (page.props.flash?.error) {
    toastRef.value?.addToast({
      type: 'error',
      title: 'Erreur',
      message: page.props.flash.error
    });
  }
}

function printOrganigramme() {
  window.print();
}

function refreshPage() {
  window.location.reload();
}

function expandAll() {
  if (structureTree.value) {
    structureTree.value.expandAll();
    isAllExpanded.value = true;
  }
}

function collapseAll() {
  if (structureTree.value) {
    structureTree.value.collapseAll();
    isAllExpanded.value = false;
  }
}

onMounted(showToastFromFlash);
watch(() => page.props.flash, showToastFromFlash);
</script>

<style scoped>
/* Styles d'impression modernisés */
@media print {
  nav,
  .sidebar,
  .header,
  .footer,
  .no-print {
    display: none !important;
  }
  body {
    background: white !important;
  }
  .structure-tree-component > div:first-child {
    break-inside: avoid;
    page-break-inside: avoid;
  }
  
  /* Optimisation pour l'impression */
  * {
    -webkit-print-color-adjust: exact !important;
    color-adjust: exact !important;
  }
}

/* Animations modernes */
.fade-enter-active, .fade-leave-active {
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
  transform: translateY(10px);
}

/* Hover effects pour les boutons */
button:hover {
  transform: translateY(-2px);
}

/* Responsive design amélioré */
@media (max-width: 768px) {
  .flex.items-center.gap-3.no-print {
    flex-wrap: wrap;
    gap: 2rem;
  }
  
  .text-3xl {
    font-size: 1.875rem;
  }
  
  .text-2xl {
    font-size: 1.5rem;
  }
}

/* Animation pour le composant StructureTree */
.structure-tree-component {
  animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>