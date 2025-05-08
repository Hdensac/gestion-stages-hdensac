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
            <div class="border-b border-gray-100 bg-gray-50 px-6 py-4 flex justify-between items-center">
              <div class="flex items-center gap-2">
                <BuildingOfficeIcon class="w-5 h-5 text-blue-600" />
                <h2 class="font-medium text-gray-700">Structure organisationnelle</h2>
              </div>

              <div class="flex items-center gap-2 no-print">
                <button class="text-sm text-gray-600 px-3 py-1 rounded-md hover:bg-gray-100 transition-colors flex items-center gap-1">
                  <ArrowPathIcon class="w-4 h-4" />
                  <span>Actualiser</span>
                </button>
                <button
                  class="text-sm bg-blue-50 text-blue-700 px-3 py-1 rounded-md hover:bg-blue-100 transition-colors flex items-center gap-1"
                  @click="printOrganigramme"
                >
                  <PrinterIcon class="w-4 h-4" />
                  <span>Imprimer</span>
                </button>
              </div>
            </div>

            <div class="p-6">
              <StructureTree
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
  const page = usePage();

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

  onMounted(showToastFromFlash);
  watch(() => page.props.flash, showToastFromFlash);
  </script>

  <style scoped>
  @media print {
    nav, .sidebar, .header, .footer, .no-print {
      display: none !important;
    }
    body {
      background: white !important;
    }
  }
  </style>

