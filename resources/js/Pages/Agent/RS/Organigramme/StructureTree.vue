<template>
  <div class="relative mb-4">
    <!-- Carte principale -->
    <div
      class="relative bg-white rounded-xl shadow-md p-4 group transition hover:shadow-lg border border-blue-100 flex items-start gap-3"
    >
      <!-- Icône -->
      <div class="bg-blue-100 text-blue-600 rounded-full p-2 flex-shrink-0">
        <component :is="iconForType" class="w-6 h-6" />
      </div>
      <!-- Infos structure -->
      <div class="flex-1 min-w-0">
        <div class="flex items-center gap-2">
          <span
            class="font-semibold text-gray-800 truncate cursor-pointer relative group"
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
        </div>
        <span
          v-if="structure.sigle && structure.sigle !== structure.libelle"
          class="text-xs text-gray-500"
        >
          {{ structure.sigle }}
        </span>
        <div v-if="structure.description" class="mt-1 text-xs text-gray-600 italic">
          {{ structure.description }}
        </div>
      </div>
      <!-- Actions -->
      <div class="flex flex-col items-end gap-2 ml-auto">
        <div class="flex gap-2">
          <button
            class="flex items-center gap-1 px-2 py-1 text-xs bg-blue-600 text-white rounded-md hover:bg-blue-700 transition shadow-sm font-medium"
            @click="showForm = !showForm; isEdit = false; fillForm()"
          >
            <PlusIcon class="w-4 h-4" />
            Ajouter
          </button>
          <button
            class="flex items-center gap-1 px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded-md hover:bg-blue-50 transition shadow-sm font-medium"
            @click="showForm = !showForm; isEdit = true; fillForm(props.structure)"
            :aria-label="'Modifier'"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13h3l8-8a2.828 2.828 0 00-4-4l-8 8v3h3z" /></svg>
            Modifier
          </button>
          <button
            class="flex items-center gap-1 px-2 py-1 text-xs bg-red-100 text-red-700 rounded-md hover:bg-red-200 transition shadow-sm font-medium"
            @click="confirmDelete"
            :aria-label="'Supprimer'"
            :disabled="!structure.parent_id"
            :class="{'opacity-50 cursor-not-allowed': !structure.parent_id}"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            Supprimer
          </button>
        </div>
        <button
          v-if="hasChildren"
          @click="isExpanded = !isExpanded"
          class="text-blue-500 hover:bg-blue-50 rounded-full p-1 transition-colors"
          :aria-label="isExpanded ? 'Réduire' : 'Déplier'"
        >
          <component :is="isExpanded ? 'ChevronDownIcon' : 'ChevronRightIcon'" class="w-4 h-4" />
        </button>
      </div>
    </div>

    <!-- Formulaire d'ajout/modification -->
    <transition name="fade">
      <div v-if="showForm" class="mb-3 mt-2 bg-white border border-blue-100 rounded-lg shadow-sm overflow-hidden">
        <div class="bg-blue-50 px-4 py-2 border-b border-blue-100 flex justify-between items-center">
          <h3 class="text-sm font-medium text-blue-800">
            {{ isEdit ? 'Modifier la structure' : 'Nouvelle sous-structure' }}
          </h3>
          <button
            @click="showForm = false"
            class="text-gray-400 hover:text-gray-600"
          >
            <XMarkIcon class="w-4 h-4" />
          </button>
        </div>
        <div class="p-4 space-y-3">
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label for="sigle" class="block text-xs font-medium text-gray-700 mb-1">
                Sigle
              </label>
              <input
                id="sigle"
                v-model="form.sigle"
                type="text"
                class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm p-2"
                required
              />
            </div>
            <div>
              <label for="libelle" class="block text-xs font-medium text-gray-700 mb-1">
                Libellé
              </label>
              <input
                id="libelle"
                v-model="form.libelle"
                type="text"
                class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm p-2"
                required
              />
            </div>
          </div>
          <div>
            <label for="description" class="block text-xs font-medium text-gray-700 mb-1">
              Description
            </label>
            <textarea
              id="description"
              v-model="form.description"
              class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm p-2"
              rows="2"
            ></textarea>
          </div>
          <div>
            <label for="type_structure" class="block text-xs font-medium text-gray-700 mb-1">
              Type de structure
            </label>
            <input
              id="type_structure"
              v-model="form.type_structure"
              type="text"
              class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm p-2"
            />
          </div>
          <div class="flex justify-end gap-2 pt-2">
            <button
              type="button"
              class="px-3 py-1.5 bg-white border border-gray-300 text-gray-700 rounded-md text-sm hover:bg-gray-50 transition"
              @click="showForm = false"
            >
              Annuler
            </button>
            <button
              type="button"
              class="px-3 py-1.5 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700 transition flex items-center gap-1"
              @click="isEdit ? submitEdit() : submit()"
            >
              <CheckIcon class="w-4 h-4" />
              {{ isEdit ? 'Enregistrer' : 'Ajouter' }}
            </button>
          </div>
        </div>
      </div>
    </transition>

    <!-- Enfants de la structure -->
    <transition name="fade">
      <div v-if="hasChildren && isExpanded" class="pl-8 mt-2 border-l-2 border-blue-100">
        <StructureTree
          v-for="child in structure.children"
          :key="child.id"
          :structure="child"
          :agents="agents"
        />
      </div>
    </transition>

    <!-- Affecter un agent -->
    <div class="mt-2">
      <button
        class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition font-medium"
        @click="showAffecter = !showAffecter"
        v-if="structure.parent_id"
      >
        Affecter un agent
      </button>
      <form v-if="showAffecter" @submit.prevent="affecterAgent" class="flex items-center gap-2 mt-2">
        <select v-model="selectedAgent" class="rounded border-gray-300 text-sm">
          <option disabled value="">Sélectionner un agent ({{ filteredAgents.length }} disponibles)</option>
          <option v-for="agent in filteredAgents" :key="agent.id" :value="agent.id">
            {{ agent.user?.nom || 'Sans nom' }} {{ agent.user?.prenom || '' }}
            {{ agent.role_agent ? `(${agent.role_agent})` : '(Sans rôle)' }}
          </option>
        </select>
        <button
          type="submit"
          class="px-2 py-1 text-xs bg-blue-600 text-white rounded-md hover:bg-blue-700 transition font-medium"
        >
          Valider
        </button>
        <button
          type="button"
          class="px-2 py-1 text-xs bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition font-medium"
          @click="showAffecter = false"
        >
          Annuler
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import {
  ChevronDownIcon,
  ChevronRightIcon,
  PlusIcon,
  XMarkIcon,
  CheckIcon,
  BuildingOffice2Icon,
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
    return 'BuildingOffice2Icon';
  }
  if (type.includes('équipe') || type.includes('groupe')) {
    return 'UserGroupIcon';
  }
  return 'UserIcon';
});
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: all 0.3s cubic-bezier(.4,2,.6,1);
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
  max-height: 0;
  transform: translateY(-10px);
}
.fade-enter-to, .fade-leave-from {
  opacity: 1;
  max-height: 1000px;
  transform: translateY(0);
}
</style>
