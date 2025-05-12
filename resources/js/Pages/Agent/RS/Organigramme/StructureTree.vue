<template>
  <div class="relative mb-4 structure-tree-component">
    <!-- Carte principale -->
    <div
      class="relative bg-white rounded-xl shadow-sm p-5 group transition-all duration-300 hover:shadow-md border border-gray-100 flex items-start gap-4"
      :class="{'border-l-4 border-l-blue-500': structure.parent_id === null}"
    >
      <!-- Icône avec badge pour indiquer le niveau hiérarchique -->
      <div class="relative">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-lg p-3 flex-shrink-0 shadow-sm">
          <BuildingOfficeIcon v-if="iconForType === 'BuildingOfficeIcon'" class="w-6 h-6" />
          <UserGroupIcon v-else-if="iconForType === 'UserGroupIcon'" class="w-6 h-6" />
          <UserIcon v-else class="w-6 h-6" />
        </div>
        <div v-if="hasChildren"
          class="absolute -bottom-1 -right-1 bg-blue-100 text-blue-700 rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold border border-white shadow-sm"
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
            class="font-semibold text-gray-800 truncate cursor-pointer relative group text-base"
            :title="structure.description || structure.libelle"
          >
            {{ structure.nom || structure.libelle }}
          </span>
          <span
            v-if="structure.type_structure"
            class="text-xs bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full font-medium border border-blue-100"
          >
            {{ structure.type_structure }}
          </span>

          <!-- Badge pour indiquer si c'est une structure principale -->
          <span
            v-if="structure.parent_id === null"
            class="text-xs bg-green-50 text-green-600 px-2 py-0.5 rounded-full font-medium border border-green-100"
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
            class="flex items-center gap-1 px-3 py-1.5 text-xs bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-md hover:from-blue-600 hover:to-blue-700 transition-all duration-300 shadow-sm font-medium"
            @click="showForm = !showForm; isEdit = false; fillForm()"
          >
            <PlusIcon class="w-4 h-4" />
            Ajouter
          </button>
          <button
            class="flex items-center gap-1 px-3 py-1.5 text-xs bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-all duration-300 shadow-sm font-medium"
            @click="showForm = !showForm; isEdit = true; fillForm(props.structure)"
            :aria-label="'Modifier'"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13h3l8-8a2.828 2.828 0 00-4-4l-8 8v3h3z" /></svg>
            Modifier
          </button>
          <button
            class="flex items-center gap-1 px-3 py-1.5 text-xs bg-red-50 text-red-600 rounded-md hover:bg-red-100 transition-all duration-300 shadow-sm font-medium"
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
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-4 py-3 flex justify-between items-center">
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
                class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 text-sm p-2.5 transition-all"
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
                class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 text-sm p-2.5 transition-all"
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
              class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 text-sm p-2.5 transition-all"
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
              class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 text-sm p-2.5 transition-all"
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
              class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md text-sm hover:bg-gray-50 transition-all duration-300 shadow-sm flex items-center"
              @click="showForm = false"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
              Annuler
            </button>
            <button
              type="button"
              class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-md text-sm transition-all duration-300 shadow-sm flex items-center"
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
        <div class="absolute left-0 top-0 bottom-0 w-0.5 bg-gradient-to-b from-blue-400 to-blue-100"></div>

        <!-- Lignes horizontales de connexion pour chaque enfant -->
        <div v-for="child in structure.children" :key="child.id" class="relative">
          <div class="absolute left-0 top-6 w-8 h-0.5 bg-blue-200"></div>
          <StructureTree
            :structure="child"
            :agents="agents"
            class="mb-4"
          />
        </div>
      </div>
    </transition>

    <!-- Affecter un agent -->
    <div class="mt-3">
      <!-- <button
        class="px-3 py-1.5 text-xs bg-gradient-to-r from-green-500 to-green-600 text-white rounded-md hover:from-green-600 hover:to-green-700 transition-all duration-300 shadow-sm font-medium flex items-center gap-1"
        @click="showAffecter = !showAffecter"
        v-if="structure.parent_id"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
        </svg>
        Affecter un agent
      </button> -->

      <transition name="slide-fade">
        <div v-if="showAffecter" class="mt-3 bg-white rounded-lg border border-gray-200 shadow-sm p-4">
          <h4 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            Affecter un agent à cette structure
          </h4>

          <form @submit.prevent="affecterAgent" class="space-y-3">
            <div>
              <label for="agent-select" class="block text-xs font-medium text-gray-700 mb-1">
                Sélectionner un agent
              </label>
              <select
                id="agent-select"
                v-model="selectedAgent"
                class="w-full rounded-md border border-gray-300 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 text-sm p-2.5 transition-all"
              >
                <option disabled value="">Sélectionner un agent ({{ filteredAgents.length }} disponibles)</option>
                <option v-for="agent in filteredAgents" :key="agent.id" :value="agent.id">
                  {{ agent.user?.nom || 'Sans nom' }} {{ agent.user?.prenom || '' }}
                  {{ agent.role_agent ? `(${agent.role_agent})` : '(Sans rôle)' }}
                </option>
              </select>
            </div>

            <div class="flex justify-end gap-2">
              <button
                type="button"
                class="px-3 py-1.5 bg-white border border-gray-300 text-gray-700 rounded-md text-xs hover:bg-gray-50 transition-all duration-300 shadow-sm flex items-center"
                @click="showAffecter = false"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Annuler
              </button>
              <button
                type="submit"
                class="px-3 py-1.5 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-md text-xs transition-all duration-300 shadow-sm flex items-center"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Valider
              </button>
            </div>
          </form>
        </div>
      </transition>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import {
  PlusIcon,
  XMarkIcon,
  CheckIcon,
  BuildingOfficeIcon,
  UserGroupIcon,
  UserIcon
} from '@heroicons/vue/24/solid';

const props = defineProps({
  structure: Object,
  agents: Array
});

// Débogage détaillé
console.log('StructureTree.vue - Structure:', props.structure?.id, props.structure?.libelle);
console.log('StructureTree.vue - Agents:', {
  count: props.agents?.length,
  agents: props.agents?.map(a => ({
    id: a.id,
    nom: a.user?.nom,
    prenom: a.user?.prenom,
    role: a.role_agent,
    structure_id: a.structure_id
  }))
});

const showForm = ref(false);
const isEdit = ref(false);
const isExpanded = ref(true);
const showAffecter = ref(false);
const selectedAgent = ref('');

// Exposer des méthodes pour permettre le contrôle externe
defineExpose({
  expand: () => { isExpanded.value = true; },
  collapse: () => { isExpanded.value = false; },
  expandAll: function() {
    // Déplier ce nœud
    isExpanded.value = true;

    // Déplier récursivement tous les enfants
    // On utilise setTimeout pour permettre au DOM de se mettre à jour
    setTimeout(() => {
      const childComponents = document.querySelectorAll('.structure-tree-component');
      childComponents.forEach(el => {
        const component = el.__vueParentComponent?.ctx;
        if (component && typeof component.expand === 'function') {
          component.expand();
        }
      });
    }, 0);
  },
  collapseAll: function() {
    // Replier ce nœud
    isExpanded.value = false;

    // Replier récursivement tous les enfants
    // On utilise setTimeout pour permettre au DOM de se mettre à jour
    setTimeout(() => {
      const childComponents = document.querySelectorAll('.structure-tree-component');
      childComponents.forEach(el => {
        const component = el.__vueParentComponent?.ctx;
        if (component && typeof component.collapse === 'function') {
          component.collapse();
        }
      });
    }, 0);
  }
});

const form = ref({
  sigle: '',
  libelle: '',
  description: '',
  type_structure: ''
});

function fillForm(structure = null) {
  if (isEdit.value && structure) {
    form.value.sigle = structure.sigle || '';
    form.value.libelle = structure.libelle || '';
    form.value.description = structure.description || '';
    form.value.type_structure = structure.type_structure || '';
  } else {
    form.value.sigle = '';
    form.value.libelle = '';
    form.value.description = '';
    form.value.type_structure = '';
  }
}

const hasChildren = computed(() => {
  return props.structure.children && props.structure.children.length > 0;
});

// Filtrer les agents pour n'afficher que ceux qui appartiennent à la structure actuelle
const filteredAgents = computed(() => {
  if (!props.agents) return [];

  // On affiche directement les agents car ils sont déjà filtrés par le contrôleur
  return props.agents;
});

function submit() {
  router.post(route('agent.rs.organigramme.store'), {
    sigle: form.value.sigle,
    libelle: form.value.libelle,
    description: form.value.description,
    type_structure: form.value.type_structure,
    parent_id: Number(props.structure.id),
  }, {
    onSuccess: () => {
      fillForm();
      showForm.value = false;
    }
  });
}

function submitEdit() {
  router.put(route('agent.rs.organigramme.update', props.structure.id), {
    sigle: form.value.sigle,
    libelle: form.value.libelle,
    description: form.value.description,
    type_structure: form.value.type_structure,
  }, {
    onSuccess: () => {
      fillForm();
      showForm.value = false;
      router.reload();
    }
  });
}

function confirmDelete() {
  // Vérifier si la structure a des enfants
  const hasChildStructures = props.structure.children && props.structure.children.length > 0;
  if (hasChildStructures) {
    window.alert('Impossible de supprimer une structure qui contient des sous-structures. Veuillez d\'abord supprimer toutes les sous-structures.');
    return;
  }

  // Vérifier si c'est une structure principale
  if (!props.structure.parent_id) {
    window.alert('Impossible de supprimer une structure principale.');
    return;
  }

  // Confirmation de suppression
  if (window.confirm('Êtes-vous sûr de vouloir supprimer cette structure ?')) {
    router.delete(route('agent.rs.organigramme.destroy', props.structure.id));
  }
}

function affecterAgent() {
  if (!selectedAgent.value) {
    alert('Veuillez sélectionner un agent');
    return;
  }

  console.log('Affectation de l\'agent ID:', selectedAgent.value, 'à la structure ID:', props.structure.id);

  router.post(
    route('agent.rs.organigramme.assign-agent', props.structure.id),
    { agent_id: selectedAgent.value },
    {
      onSuccess: () => {
        showAffecter.value = false;
        selectedAgent.value = '';
        router.reload();
      },
      onError: (errors) => {
        console.error('Erreur lors de l\'affectation:', errors);
        alert('Erreur lors de l\'affectation: ' + JSON.stringify(errors));
      }
    }
  );
}

// Choix d'icône selon le type de structure
const iconForType = computed(() => {
  const type = (props.structure.type_structure || '').toLowerCase();

  if (type.includes('direction') || type.includes('service') || type.includes('département')) {
    return 'BuildingOfficeIcon';
  }
  if (type.includes('équipe') || type.includes('groupe')) {
    return 'UserGroupIcon';
  }

  return 'UserIcon';
});
</script>

<style scoped>
/* Animations améliorées */
.slide-fade-enter-active {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.slide-fade-leave-active {
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}
.slide-fade-enter-from, .slide-fade-leave-to {
  opacity: 0;
  max-height: 0;
  transform: translateY(-10px);
}
.slide-fade-enter-to, .slide-fade-leave-from {
  opacity: 1;
  max-height: 1000px;
  transform: translateY(0);
}

/* Styles pour les cartes de structure */
.structure-tree-component {
  position: relative;
}

/* Effet de survol sur les cartes */
.structure-tree-component > div:first-child {
  transition: all 0.3s ease;
}
.structure-tree-component > div:first-child:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

/* Styles pour les lignes de connexion */
.structure-tree-component .relative .absolute {
  transition: all 0.3s ease;
}

/* Styles responsifs */
@media (max-width: 768px) {
  .structure-tree-component > div:first-child {
    flex-direction: column;
  }
  .structure-tree-component > div:first-child > div:last-child {
    margin-top: 1rem;
    margin-left: 0;
    width: 100%;
  }
  .structure-tree-component > div:first-child > div:last-child > div {
    justify-content: flex-start;
  }
}
</style>
