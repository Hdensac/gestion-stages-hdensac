<template>
    <RSLayout>
      <Toast ref="toastRef" />
      <div class="py-8 bg-gray-50 min-h-screen">
        <div class="mx-auto w-full max-w-4xl px-4 sm:px-6 lg:px-8">
          <!-- En-tête avec animation et style amélioré -->
          <div class="flex items-center gap-4 mb-8">
            <div class="p-3 bg-blue-50 rounded-lg shadow-sm">
              <UsersIcon class="w-6 h-6 text-blue-600" />
            </div>
            <div>
              <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
                Organigramme de la structure
              </h1>
              <p class="text-sm text-gray-500 mt-1">
                Visualisez et gérez la hiérarchie de votre organisation
              </p>
            </div>
          </div>

          <!-- Contenu principal avec structure améliorée -->
          <div v-if="props.structure" class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
            <div class="border-b border-gray-100 bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4 flex justify-between items-center">
              <div class="flex items-center gap-3">
                <div class="bg-white/20 p-2 rounded-lg">
                  <BuildingOfficeIcon class="w-5 h-5 text-white" />
                </div>
                <h2 class="font-medium text-white text-lg">Structure organisationnelle</h2>
              </div>

              <div class="flex items-center gap-3 no-print">
                <button
                  @click="isAllExpanded ? collapseAll() : expandAll()"
                  class="text-sm bg-white/20 text-white px-4 py-2 rounded-md hover:bg-white/30 transition-all duration-300 flex items-center gap-2 backdrop-blur-sm"
                >
                  <span>{{ isAllExpanded ? 'Tout replier' : 'Tout déplier' }}</span>
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      :d="isAllExpanded ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'" />
                  </svg>
                </button>

                <button
                  class="text-sm bg-white/20 text-white px-4 py-2 rounded-md hover:bg-white/30 transition-all duration-300 flex items-center gap-2 backdrop-blur-sm"
                  @click="refreshPage"
                >
                  <ArrowPathIcon class="w-4 h-4" />
                  <span>Actualiser</span>
                </button>
                <button
                  class="text-sm bg-white/20 text-white px-4 py-2 rounded-md hover:bg-white/30 transition-all duration-300 flex items-center gap-2 backdrop-blur-sm"
                  @click="printOrganigramme"
                >
                  <PrinterIcon class="w-4 h-4" />
                  <span>Imprimer</span>
                </button>
              </div>
            </div>

            <div class="p-8">
              <StructureTree
                ref="structureTree"
                class="structure-tree-component"
                :structure="props.structure"
                :agents="props.agents"
              />
            </div>
          </div>

          <!-- État vide amélioré -->
          <div v-else class="bg-white rounded-xl shadow-md border border-gray-100 p-10 text-center">
            <div class="flex justify-center mb-4">
              <FolderOpenIcon class="w-16 h-16 text-gray-300" />
            </div>
            <h3 class="text-lg font-medium text-gray-700 mb-2">
              Aucune structure principale trouvée
            </h3>
            <p class="text-gray-500 max-w-md mx-auto">
              Aucune structure principale n'a été trouvée pour ce responsable.
              Veuillez contacter l'administrateur pour créer la structure.
            </p>
            <div class="mt-6">
              <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors flex items-center gap-1 mx-auto">
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
    // Utiliser la référence directe au composant StructureTree
    if (structureTree.value) {
      // Appeler la méthode expandAll du composant StructureTree
      structureTree.value.expandAll();
      isAllExpanded.value = true;
    }
  }

  function collapseAll() {
    // Utiliser la référence directe au composant StructureTree
    if (structureTree.value) {
      // Appeler la méthode collapseAll du composant StructureTree
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
    transition: opacity 0.5s ease;
  }
  .fade-enter-from, .fade-leave-to {
    opacity: 0;
  }

  /* Styles pour les menus déroulants */
  .dropdown-menu {
    min-width: 160px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    z-index: 100;
  }

  /* Styles responsifs */
  @media (max-width: 768px) {
    .flex.items-center.gap-3.no-print {
      flex-direction: column;
      align-items: stretch;
    }
    .flex.items-center.gap-3.no-print button {
      margin-bottom: 0.5rem;
    }
  }
  </style>

