<template>
  <RSLayout>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <div class="mb-6">
              <h2 class="text-2xl font-semibold text-gray-800">
                {{ structure ? 'Modifier la structure' : 'Créer une nouvelle structure' }}
              </h2>
              <p class="text-gray-600 mt-1">
                {{ structure ? 'Modifiez les informations de la structure existante' : 'Ajoutez une nouvelle structure à l\'organigramme' }}
              </p>
            </div>

            <form @submit.prevent="submit">
              <!-- Champ parent_id (si création d'une sous-structure) -->
              <div v-if="parentStructure && !structure" class="mb-6">
                <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
                  <p class="text-blue-800">
                    <span class="font-medium">Structure parente:</span> {{ parentStructure.libelle }}
                  </p>
                  <input type="hidden" v-model="form.parent_id" />
                </div>
              </div>

              <!-- Champ libellé -->
              <div class="mb-6">
                <label for="libelle" class="block text-sm font-medium text-gray-700 mb-1">Libellé *</label>
                <input
                  id="libelle"
                  v-model="form.libelle"
                  type="text"
                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  :class="{ 'border-red-500': errors.libelle }"
                  required
                />
                <p v-if="errors.libelle" class="mt-1 text-sm text-red-600">{{ errors.libelle }}</p>
              </div>

              <!-- Champ sigle -->
              <div class="mb-6">
                <label for="sigle" class="block text-sm font-medium text-gray-700 mb-1">Sigle *</label>
                <input
                  id="sigle"
                  v-model="form.sigle"
                  type="text"
                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  :class="{ 'border-red-500': errors.sigle }"
                  required
                />
                <p v-if="errors.sigle" class="mt-1 text-sm text-red-600">{{ errors.sigle }}</p>
              </div>

              <!-- Champ description -->
              <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea
                  id="description"
                  v-model="form.description"
                  rows="4"
                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  :class="{ 'border-red-500': errors.description }"
                ></textarea>
                <p v-if="errors.description" class="mt-1 text-sm text-red-600">{{ errors.description }}</p>
              </div>

              <!-- Champ responsable_id -->
              <div class="mb-6">
                <label for="responsable_id" class="block text-sm font-medium text-gray-700 mb-1">Responsable</label>
                <select
                  id="responsable_id"
                  v-model="form.responsable_id"
                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  :class="{ 'border-red-500': errors.responsable_id }"
                >
                  <option :value="null">Aucun responsable</option>
                  <option v-for="agent in agents" :key="agent.id" :value="agent.id">
                    {{ agent.user.nom }} {{ agent.user.prenom }}
                  </option>
                </select>
                <p v-if="errors.responsable_id" class="mt-1 text-sm text-red-600">{{ errors.responsable_id }}</p>
                <p class="mt-1 text-sm text-gray-500">
                  Note: Si l'agent n'est pas déjà un Responsable de Structure (RS), son rôle sera automatiquement mis à jour.
                </p>
              </div>

              <!-- Boutons d'action -->
              <div class="flex justify-end space-x-3">
                <Link
                  :href="route('agent.rs.organigramme.index')"
                  class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors"
                >
                  Annuler
                </Link>
                <button
                  type="submit"
                  class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
                  :disabled="processing"
                >
                  <span v-if="processing">Traitement en cours...</span>
                  <span v-else>{{ structure ? 'Mettre à jour' : 'Créer' }}</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </RSLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import RSLayout from '@/Layouts/RSLayout.vue';

const props = defineProps({
  structure: Object,
  parentStructure: Object,
  agents: Array,
});

const processing = ref(false);

// Initialiser le formulaire
const form = useForm({
  libelle: props.structure?.libelle || '',
  sigle: props.structure?.sigle || '',
  description: props.structure?.description || '',
  parent_id: props.structure?.parent_id || props.parentStructure?.id || null,
  responsable_id: props.structure?.responsable_id || null,
});

// Soumettre le formulaire
const submit = () => {
  processing.value = true;
  
  if (props.structure) {
    form.put(route('agent.rs.structures.update', props.structure.id), {
      onSuccess: () => {
        processing.value = false;
      },
      onError: () => {
        processing.value = false;
      },
    });
  } else {
    form.post(route('agent.rs.structures.store'), {
      onSuccess: () => {
        processing.value = false;
      },
      onError: () => {
        processing.value = false;
      },
    });
  }
};
</script>
