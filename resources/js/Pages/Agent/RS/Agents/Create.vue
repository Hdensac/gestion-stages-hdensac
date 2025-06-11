<template>
  <RSLayout>
    <template #header>
      <div class="flex items-center gap-4 mb-2">
        <div class="bg-blue-600 text-white rounded-lg w-14 h-14 flex items-center justify-center shadow-md">
          <UserPlusIcon class="w-7 h-7" />
        </div>
        <div>
          <h1 class="text-2xl font-bold text-gray-800 leading-tight md:text-3xl">Ajouter un agent</h1>
          <p class="text-sm text-gray-500 mt-1">Créez un nouvel agent pour votre structure</p>
        </div>
      </div>
    </template>

    <div class="py-6">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
          <div class="border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-800 py-4 px-6 flex items-center gap-2">
            <UserPlusIcon class="h-5 w-5 text-white" />
            <h2 class="font-medium text-white text-lg">Formulaire d'ajout</h2>
          </div>

          <div class="p-6">
            <form @submit.prevent="submit" class="space-y-6">
              <!-- Informations personnelles -->
              <div>
                <h3 class="text-lg font-medium text-gray-800 mb-3 border-b pb-2">Informations personnelles</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom <span class="text-red-500">*</span></label>
                    <input 
                      v-model="form.nom" 
                      type="text" 
                      class="w-full border rounded-md p-2 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                      required 
                    />
                    <div v-if="form.errors.nom" class="text-red-500 text-xs mt-1">{{ form.errors.nom }}</div>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prénom <span class="text-red-500">*</span></label>
                    <input 
                      v-model="form.prenom" 
                      type="text" 
                      class="w-full border rounded-md p-2 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                      required 
                    />
                    <div v-if="form.errors.prenom" class="text-red-500 text-xs mt-1">{{ form.errors.prenom }}</div>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <div class="relative">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <EnvelopeIcon class="h-4 w-4 text-gray-400" />
                      </div>
                      <input 
                        v-model="form.email" 
                        type="email" 
                        class="w-full border rounded-md pl-10 p-2 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                        required 
                      />
                    </div>
                    <div v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</div>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone <span class="text-red-500">*</span></label>
                    <div class="relative">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <PhoneIcon class="h-4 w-4 text-gray-400" />
                      </div>
                      <input 
                        v-model="form.telephone" 
                        type="text" 
                        class="w-full border rounded-md pl-10 p-2 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                        required 
                      />
                    </div>
                    <div v-if="form.errors.telephone" class="text-red-500 text-xs mt-1">{{ form.errors.telephone }}</div>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date de naissance <span class="text-red-500">*</span></label>
                    <div class="relative">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <CalendarIcon class="h-4 w-4 text-gray-400" />
                      </div>
                      <input 
                        v-model="form.date_de_naissance" 
                        type="date" 
                        class="w-full border rounded-md pl-10 p-2 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                        required 
                      />
                    </div>
                    <div v-if="form.errors.date_de_naissance" class="text-red-500 text-xs mt-1">{{ form.errors.date_de_naissance }}</div>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sexe <span class="text-red-500">*</span></label>
                    <select 
                      v-model="form.sexe" 
                      class="w-full border rounded-md p-2 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                      required
                    >
                      <option value="">Sélectionner</option>
                      <option value="Homme">Homme</option>
                      <option value="Femme">Femme</option>
                    </select>
                    <div v-if="form.errors.sexe" class="text-red-500 text-xs mt-1">{{ form.errors.sexe }}</div>
                  </div>
                </div>
              </div>

              <!-- Informations professionnelles -->
              <div>
                <h3 class="text-lg font-medium text-gray-800 mb-3 border-b pb-2">Informations professionnelles</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Matricule <span class="text-red-500">*</span></label>
                    <input 
                      v-model="form.matricule" 
                      type="text" 
                      class="w-full border rounded-md p-2 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                      required 
                    />
                    <div v-if="form.errors.matricule" class="text-red-500 text-xs mt-1">{{ form.errors.matricule }}</div>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fonction <span class="text-red-500">*</span></label>
                    <input 
                      v-model="form.fonction" 
                      type="text" 
                      class="w-full border rounded-md p-2 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                      required 
                    />
                    <div v-if="form.errors.fonction" class="text-red-500 text-xs mt-1">{{ form.errors.fonction }}</div>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date d'embauche</label>
                    <div class="relative">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <CalendarIcon class="h-4 w-4 text-gray-400" />
                      </div>
                      <input 
                        v-model="form.date_embauche" 
                        type="date" 
                        class="w-full border rounded-md pl-10 p-2 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                      />
                    </div>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe <span class="text-red-500">*</span></label>
                    <div class="relative">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <KeyIcon class="h-4 w-4 text-gray-400" />
                      </div>
                      <input 
                        v-model="form.password" 
                        type="password" 
                        class="w-full border rounded-md pl-10 p-2 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                        required 
                      />
                    </div>
                    <div v-if="form.errors.password" class="text-red-500 text-xs mt-1">{{ form.errors.password }}</div>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sous-structure <span class="text-red-500">*</span></label>
                    <select
                      v-model="form.structure_id"
                      class="w-full border rounded-md p-2 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      required
                    >
                      <option value="">Sélectionner une sous-structure</option>
                      <option v-for="s in sousStructures" :key="s.id" :value="s.id">
                        {{ s.libelle }}
                      </option>
                    </select>
                    <div v-if="form.errors.structure_id" class="text-red-500 text-xs mt-1">{{ form.errors.structure_id }}</div>
                  </div>
                </div>
              </div>

              <!-- Attribution de responsabilité -->
              <div>
                <h3 class="text-lg font-medium text-gray-800 mb-3 border-b pb-2">Attribution de responsabilité</h3>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Responsable de la structure</label>
                  <select 
                    v-model="form.structure_responsable_id" 
                    class="w-full border rounded-md p-2 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  >
                    <option :value="null">Aucune structure</option>
                    <option v-for="structure in structures" :key="structure.id" :value="structure.id">
                      {{ getStructureDisplayName(structure) }}
                    </option>
                  </select>
                  <p class="text-sm text-gray-500 mt-2 bg-blue-50 p-3 rounded-md border-l-4 border-blue-500">
                    Sélectionnez la structure dont cet agent sera responsable. Un agent ne peut être responsable que d'une seule structure à la fois.
                  </p>
                </div>
              </div>

              <div class="flex justify-end space-x-3 pt-4 border-t">
                <Link 
                  :href="route('agent.rs.agents.index')" 
                  class="px-5 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors font-medium flex items-center gap-2"
                >
                  <ArrowLeftIcon class="w-4 h-4" />
                  Retour
                </Link>
                <button 
                  type="submit" 
                  class="px-5 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-md shadow-sm transition-all duration-200 font-medium flex items-center gap-2"
                >
                  <UserPlusIcon class="w-4 h-4" />
                  Ajouter l'agent
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
import { Link, useForm } from '@inertiajs/vue3';
import RSLayout from '@/Layouts/RSLayout.vue';
import { 
  UserPlusIcon, 
  EnvelopeIcon, 
  PhoneIcon, 
  CalendarIcon, 
  KeyIcon, 
  ArrowLeftIcon 
} from '@heroicons/vue/24/outline';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({
  structures: Array
});

const form = useForm({
  nom: '',
  prenom: '',
  email: '',
  password: '',
  telephone: '',
  date_de_naissance: '',
  sexe: '',
  matricule: '',
  fonction: '',
  date_embauche: '',
  structure_responsable_id: null,
  structure_id: '',
});

const sousStructures = ref([]);
onMounted(async () => {
  try {
    const res = await axios.get('/agent/rs/organigramme/sous-structures');
    sousStructures.value = res.data;
  } catch (e) {
    sousStructures.value = [];
  }
});

function submit() {
  form.post(route('agent.rs.agents.store'));
}

// Fonction pour afficher le nom de la structure avec indication de la hiérarchie
function getStructureDisplayName(structure) {
  let displayName = structure.libelle;
  if (structure.sigle) {
    displayName += ` (${structure.sigle})`;
  }

  // Ajouter un préfixe pour indiquer le niveau hiérarchique
  if (structure.parent_id) {
    // Calculer le niveau de profondeur de la structure
    let depth = 1; // Commencer à 1 pour les sous-structures directes
    let currentParentId = structure.parent_id;

    // Remonter la chaîne des parents pour déterminer le niveau de profondeur
    while (currentParentId) {
      const parent = props.structures.find(s => s.id === currentParentId);
      if (!parent || !parent.parent_id) break; // Arrêter si on atteint la structure principale

      depth++;
      currentParentId = parent.parent_id;
    }

    // Ajouter des flèches en fonction du niveau de profondeur
    let prefix = '';
    for (let i = 0; i < depth; i++) {
      prefix += '→';
    }

    displayName = `${prefix} ${displayName}`;
  }

  return displayName;
}
</script>