<template>
  <div class="relative mb-4 structure-tree-component">
    <!-- Carte principale -->
    <div
      class="relative bg-white rounded-lg shadow-sm p-5 transition-all duration-200 hover:shadow-md border border-gray-200 flex items-start gap-4"
      :class="{'border-l-4 border-l-blue-600': structure.parent_id === null}"
    >
      <!-- Icône avec badge pour indiquer le niveau hiérarchique -->
      <div class="relative">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg p-3 flex-shrink-0 shadow-sm">
          <BuildingOfficeIcon v-if="iconForType === 'BuildingOfficeIcon'" class="w-6 h-6" />
          <UserGroupIcon v-else-if="iconForType === 'UserGroupIcon'" class="w-6 h-6" />
          <UserIcon v-else class="w-6 h-6" />
        </div>
        <div v-if="hasChildren"
          class="absolute -bottom-1 -right-1 bg-blue-100 text-blue-700 rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold border border-white shadow-sm cursor-pointer"
          :class="{'rotate-180 bg-blue-200': !isExpanded}"
          @click.stop="isExpanded = !isExpanded"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </div>
      </div>

      <!-- Infos structure -->
      <div class="flex-1 min-w-0">
        <div class="flex items-center flex-wrap gap-2 mb-1">
          <span
            class="font-semibold text-gray-800 truncate cursor-pointer text-base"
            :title="structure.description || structure.libelle"
          >
            {{ structure.nom || structure.libelle }}
          </span>
          <span
            v-if="structure.type_structure"
            class="text-xs bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full font-medium border border-blue-100"
          >
            {{ structure.type_structure }}
          </span>

          <!-- Badge pour indiquer si c'est une structure principale -->
          <span
            v-if="structure.parent_id === null"
            class="text-xs bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-full font-medium border border-emerald-100"
          >
            Structure principale
          </span>
        </div>

        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-1">
          <span
            v-if="structure.sigle && structure.sigle !== structure.libelle"
            class="text-sm text-gray-500 flex items-center"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 mr-1 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
            </svg>
            {{ structure.sigle }}
          </span>

          <!-- Afficher le responsable si disponible -->
          <span v-if="structure.responsable_id" class="text-sm text-gray-500 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 mr-1 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
            </svg>
            Responsable
          </span>
        </div>

        <div v-if="structure.description" class="mt-2 text-sm text-gray-600 bg-gray-50 p-2 rounded-md border border-gray-100">
          {{ structure.description }}
        </div>
      </div>

      <!-- Actions -->
      <div class="flex flex-col items-end gap-2 ml-auto">
        <div class="flex flex-wrap gap-2 justify-end">
          <button
            class="flex items-center gap-1 px-3 py-1.5 text-xs bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-md hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-sm font-medium"
            @click="showForm = !showForm; isEdit = false; fillForm()"
          >
            <PlusIcon class="w-4 h-4" />
            Ajouter
          </button>
          <button
            class="flex items-center gap-1 px-3 py-1.5 text-xs bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-all duration-200 shadow-sm font-medium"
            @click="showForm = !showForm; isEdit = true; fillForm(props.structure)"
            :aria-label="'Modifier'"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13h3l8-8a2.828 2.828 0 00-4-4l-8 8v3h3z" /></svg>
            Modifier
          </button>
          <button
            class="flex items-center gap-1 px-3 py-1.5 text-xs bg-red-50 text-red-600 rounded-md hover:bg-red-100 transition-all duration-200 shadow-sm font-medium"
            @click="confirmDelete"
            :aria-label="'Supprimer'"
            :disabled="!props.structure.parent_id || hasChildren"
            :class="{'opacity-50 cursor-not-allowed': !props.structure.parent_id || hasChildren}"
            :title="hasChildren ? 'Impossible de supprimer une structure avec des sous-structures' : 'Supprimer cette structure'"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            Supprimer
          </button>
        </div>
      </div>
    </div>

    <!-- Formulaire d'ajout/modification -->
    <transition name="slide-fade">
      <div v-if="showForm" class="mb-4 mt-3 bg-white border border-gray-200 rounded-lg shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-4 py-3 flex justify-between items-center">
          <h3 class="text-sm font-medium text-white flex items-center">
            <svg v-if="isEdit" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            {{ isEdit ? 'Modifier la structure' : 'Nouvelle sous-structure' }}
          </h3>
          <button
            @click="showForm = false"
            class="text-white hover:text-gray-200 transition-colors"
          >
            <XMarkIcon class="w-5 h-5" />
          </button>
        </div>
        <div class="p-5 space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label for="sigle" class="block text-sm font-medium text-gray-700 mb-1">
                Sigle <span class="text-red-500">*</span>
              </label>
              <input
                id="sigle"
                v-model="form.sigle"
                type="text"
                class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-20 text-sm p-2.5"
                required
                placeholder="Ex: DGML"
              />
            </div>
            <div>
              <label for="libelle" class="block text-sm font-medium text-gray-700 mb-1">
                Libellé <span class="text-red-500">*</span>
              </label>
              <input
                id="libelle"
                v-model="form.libelle"
                type="text"
                class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-20 text-sm p-2.5"
                required
                placeholder="Ex: Direction Générale du Matériel et Logistique"
              />
            </div>
          </div>
          <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
              Description
            </label>
            <textarea
              id="description"
              v-model="form.description"
              class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-20 text-sm p-2.5"
              rows="3"
              placeholder="Description détaillée de la structure..."
            ></textarea>
          </div>
          <div>
            <label for="type_structure" class="block text-sm font-medium text-gray-700 mb-1">
              Type de structure
            </label>
            <select
              id="type_structure"
              v-model="form.type_structure"
              class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-20 text-sm p-2.5"
            >
              <option value="">Sélectionner un type</option>
              <option value="Direction">Direction</option>
              <option value="Service">Service</option>
              <option value="Département">Département</option>
              <option value="Division">Division</option>
              <option value="Bureau">Bureau</option>
              <option value="Cellule">Cellule</option>
              <option value="Équipe">Équipe</option>
            </select>
          </div>
          <div class="flex justify-end gap-3 pt-3">
            <button
              type="button"
              class="px-4 py-2 bg-gray-100 border border-gray-300 text-gray-700 rounded-md text-sm hover:bg-gray-200 transition-all duration-200 shadow-sm flex items-center"
              @click="showForm = false"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
              Annuler
            </button>
            <button
              type="button"
              class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-md text-sm transition-all duration-200 shadow-sm flex items-center"
              @click="isEdit ? submitEdit() : submit()"
            >
              <CheckIcon class="w-4 h-4 mr-1.5" />
              {{ isEdit ? 'Enregistrer' : 'Ajouter' }}
            </button>
          </div>
        </div>
      </div>
    </transition>

    <!-- Enfants de la structure -->
    <transition name="slide-fade">
      <div v-if="hasChildren && isExpanded" class="pl-8 mt-3 relative">
        <!-- Ligne verticale de connexion -->
        <div class="absolute left-0 top-0 bottom-0 w-0.5 bg-gradient-to-b from-blue-600 to-blue-300"></div>

        <!-- Lignes horizontales de connexion pour chaque enfant -->
        <div v-for="child in structure.children" :key="child.id" class="relative">
          <div class="absolute left-0 top-6 w-8 h-0.5 bg-blue-400"></div>
          <StructureTree
            :structure="child"
            :agents="agents"
            class="mb-4"
          />
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
// Conservez le script tel quel, car nous modifions principalement le template
// Note: Assurez-vous d'inclure les imports nécessaires
import { ref, computed, defineProps, defineExpose } from 'vue';
import { BuildingOfficeIcon, UserGroupIcon, UserIcon, PlusIcon, XMarkIcon, CheckIcon } from '@heroicons/vue/24/outline';
import axios from 'axios';

const props = defineProps({
  structure: {
    type: Object,
    required: true
  },
  agents: {
    type: Array,
    default: () => []
  }
});

const isExpanded = ref(true);
const showForm = ref(false);
const isEdit = ref(false);
const form = ref({
  sigle: '',
  libelle: '',
  description: '',
  type_structure: '',
  parent_id: props.structure.id
});

const hasChildren = computed(() => {
  return props.structure.children && props.structure.children.length > 0;
});

const iconForType = computed(() => {
  const type = props.structure.type_structure?.toLowerCase() || '';
  if (type.includes('direction') || type.includes('division')) return 'BuildingOfficeIcon';
  if (type.includes('service') || type.includes('département') || type.includes('departement')) return 'UserGroupIcon';
  return 'UserIcon';
});

function fillForm(structure = null) {
  if (structure) {
    form.value = {
      sigle: structure.sigle || '',
      libelle: structure.libelle || '',
      description: structure.description || '',
      type_structure: structure.type_structure || '',
      parent_id: structure.parent_id
    };
  } else {
    form.value = {
      sigle: '',
      libelle: '',
      description: '',
      type_structure: '',
      parent_id: props.structure.id
    };
  }
}

function confirmDelete() {
  if (!props.structure.parent_id || hasChildren.value) return;
  if (!confirm("Voulez-vous vraiment supprimer cette structure ?")) return;
  axios.post(`/agent/rs/organigramme/${props.structure.id}`, {
    _method: 'DELETE'
  })
    .then(() => {
      window.location.reload();
    })
    .catch(e => {
      alert("Erreur lors de la suppression : " + (e.response?.data?.message || e.message));
    });
}

function submit() {
  axios.post('/agent/rs/organigramme', form.value)
    .then(() => {
      showForm.value = false;
      window.location.reload();
    })
    .catch(e => {
      alert("Erreur lors de l'ajout : " + (e.response?.data?.message || e.message));
    });
}

function submitEdit() {
  axios.post(`/agent/rs/organigramme/${props.structure.id}`, {
    ...form.value,
    _method: 'PUT'
  })
    .then(() => {
      showForm.value = false;
      window.location.reload();
    })
    .catch(e => {
      alert("Erreur lors de la modification : " + (e.response?.data?.message || e.message));
    });
}

// Fonctions exposées pour le comportement d'expansion/collapsion
function expandAll() {
  isExpanded.value = true;
  // Récursivement pour tous les enfants
}

function collapseAll() {
  isExpanded.value = false;
  // Récursivement pour tous les enfants
}

defineExpose({
  expandAll,
  collapseAll
});
</script>

<style scoped>
.slide-fade-enter-active,
.slide-fade-leave-active {
  transition: all 0.3s ease;
}
.slide-fade-enter-from,
.slide-fade-leave-to {
  transform: translateY(-10px);
  opacity: 0;
}

/* Styles animés pour les boutons et interactions */
button {
  transition: all 0.2s ease;
}
button:hover {
  transform: translateY(-1px);
}
button:active {
  transform: translateY(0);
}
</style>