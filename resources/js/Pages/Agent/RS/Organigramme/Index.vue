<template>
  <RSLayout>
    <template #header>
      <div class="flex items-center gap-4 mb-2">
        <div class="bg-blue-600 text-white rounded-lg w-14 h-14 flex items-center justify-center shadow-md">
          <UsersIcon class="w-7 h-7" />
        </div>
        <div>
          <h1 class="text-2xl font-bold text-gray-800 leading-tight md:text-3xl">Organigramme</h1>
          <p class="text-sm text-gray-500 mt-1">Visualisez et gérez la hiérarchie de votre organisation</p>
        </div>
      </div>
    </template>
    
    <Toast ref="toastRef" />
    
    <div class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Contenu principal -->
        <div v-if="props.structure" class="bg-white rounded-lg shadow-md overflow-hidden">
          <div class="border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-800 py-4 px-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
              <div class="bg-white/20 p-2 rounded-lg">
                <BuildingOfficeIcon class="w-5 h-5 text-white" />
              </div>
              <h2 class="font-medium text-white text-lg">Structure organisationnelle</h2>
            </div>

            <div class="flex flex-wrap items-center gap-2 no-print">
              <button
                @click="isAllExpanded ? collapseAll() : expandAll()"
                class="text-sm bg-white/20 text-white px-3 py-2 rounded-md hover:bg-white/30 transition-all duration-200 flex items-center gap-2 backdrop-blur-sm"
              >
                <span>{{ isAllExpanded ? 'Tout replier' : 'Tout déplier' }}</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    :d="isAllExpanded ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'" />
                </svg>
              </button>

              <button
                class="text-sm bg-white/20 text-white px-3 py-2 rounded-md hover:bg-white/30 transition-all duration-200 flex items-center gap-2 backdrop-blur-sm"
                @click="refreshPage"
              >
                <ArrowPathIcon class="w-4 h-4" />
                <span>Actualiser</span>
              </button>
              
              <button
                class="text-sm bg-white/20 text-white px-3 py-2 rounded-md hover:bg-white/30 transition-all duration-200 flex items-center gap-2 backdrop-blur-sm"
                @click="printOrganigramme"
              >
                <PrinterIcon class="w-4 h-4" />
                <span>Imprimer</span>
              </button>
            </div>
          </div>

          <div class="p-6">
            <StructureTree
              ref="structureTree"
              class="structure-tree-component"
              :structure="props.structure"
              :agents="props.agents"
            />
          </div>
        </div>

        <!-- État vide -->
        <div v-else class="bg-white rounded-lg shadow-md p-10 text-center">
          <div class="max-w-md mx-auto">
            <div class="bg-blue-50 rounded-full h-20 w-20 flex items-center justify-center mx-auto mb-6">
              <FolderOpenIcon class="w-10 h-10 text-blue-400" />
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">
              Aucune structure principale trouvée
            </h3>
            <p class="text-gray-500 mb-6">
              Aucune structure principale n'a été trouvée pour ce responsable.
              Veuillez contacter l'administrateur pour créer la structure.
            </p>
            <button class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-md hover:from-blue-700 hover:to-blue-800 transition-all duration-200 flex items-center gap-2 mx-auto">
              <PlusCircleIcon class="w-5 h-5" />
              <span>Créer une structure</span>
            </button>
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
/* Styles d'impression */
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
}

/* Styles pour l'animation de la page */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}

/* Styles responsifs */
@media (max-width: 768px) {
  .flex.items-center.gap-3.no-print {
    flex-wrap: wrap;
  }
}
</style>