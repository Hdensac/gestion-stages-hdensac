<template>
  <Head title="Structures" />
  <Admin>
    <div class="py-12 bg-gray-50 min-h-screen">
      <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 space-y-8">
        <!-- Header modernisé -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
          <div class="flex items-center gap-3">
            <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 17a5 5 0 0 1 5-5h14a5 5 0 0 1 5 5v5H2z"/><path d="M6 9V2h12v7"/></svg>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-800">Gestion des structures</h1>
            <span class="ml-2 bg-indigo-100 text-indigo-700 text-xs font-semibold px-3 py-1 rounded-full">{{ props.structures.length }} structures</span>
          </div>
          <button @click="openModal()" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-500 to-blue-500 text-white rounded-xl shadow hover:from-indigo-600 hover:to-blue-600 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2 font-semibold text-lg transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Ajouter une structure
          </button>
        </div>
        <!-- Carte liste structures -->
        <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-indigo-100">
          <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
            <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 17a5 5 0 0 1 5-5h14a5 5 0 0 1 5 5v5H2z"/><path d="M6 9V2h12v7"/></svg>
            <h2 class="text-lg font-semibold text-indigo-800">Liste des structures</h2>
          </div>
          <div v-if="props.structures.length === 0" class="p-12 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-4 text-gray-300">
              <path d="M2 17a5 5 0 0 1 5-5h14a5 5 0 0 1 5 5v5H2z"></path>
              <path d="M6 9V2h12v7"></path>
            </svg>
            <p class="text-gray-400 text-lg">Aucune structure n'a été ajoutée</p>
            <button @click="openModal()" class="mt-4 px-5 py-2 bg-indigo-600 text-white rounded-xl shadow hover:bg-indigo-700 transition font-semibold">Ajouter votre première structure</button>
          </div>
          <div v-else class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Sigle</th>
                  <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Libellé</th>
                  <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Description</th>
                  <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-100">
                <tr v-for="structure in props.structures" :key="structure.id" class="hover:bg-indigo-50 transition">
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ structure.sigle }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ structure.libelle }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ structure.description ?? '-' }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-center">
                    <div class="flex justify-center gap-2">
                      <button @click="openModal(structure)" class="text-indigo-600 hover:text-indigo-900 font-medium flex items-center gap-1" title="Modifier">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.85 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
                        
                      </button>
                      <button @click="openDeleteModal(structure)" class="text-red-600 hover:text-red-800 font-medium flex items-center gap-1" title="Supprimer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                        
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <!-- Modal d'édition/création -->
        <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
          <div class="bg-white rounded-lg shadow-xl max-w-lg w-full mx-4">
            <!-- En-tête de la modale -->
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
              <h3 class="text-xl font-bold text-gray-800">
                {{ editingId ? 'Modifier une structure' : 'Ajouter une structure' }}
              </h3>
              <button @click="closeModal" class="text-gray-500 hover:text-gray-700 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <!-- Formulaire -->
            <form @submit.prevent="submit" class="px-6 py-4">
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Sigle</label>
                  <input 
                    v-model="form.sigle" 
                    class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Ex: ONU"
                  />
                  <div v-if="form.errors.sigle" class="mt-1 text-sm text-red-600">{{ form.errors.sigle }}</div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Libellé</label>
                  <input 
                    v-model="form.libelle" 
                    class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                    placeholder="Ex: Organisation des Nations Unies"
                  />
                  <div v-if="form.errors.libelle" class="mt-1 text-sm text-red-600">{{ form.errors.libelle }}</div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                  <textarea 
                    v-model="form.description" 
                    class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    rows="3"
                    placeholder="Description de la structure..."
                  ></textarea>
                  <div v-if="form.errors.description" class="mt-1 text-sm text-red-600">{{ form.errors.description }}</div>
                </div>
                
                <!-- Champ pour sélectionner une structure si le rôle est "RS" -->
                <div v-if="form.role_agent === 'RS'">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Structure</label>
                  <select v-model="form.structure_id" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="">Sélectionner une structure</option>
                    <option v-for="structure in availableStructures" :key="structure.id" :value="structure.id">
                      {{ structure.libelle }}
                    </option>
                  </select>
                  <div v-if="form.errors.structure_id" class="text-red-600 text-sm mt-1">{{ form.errors.structure_id }}</div>
                </div>
              </div>

              <!-- Boutons de navigation -->
              <div class="flex justify-between pt-6 border-t mt-6">
                <button 
                  type="button" 
                  @click="closeModal()" 
                  class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400 flex items-center gap-1"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18M6 6l12 12"/>
                  </svg>
                  Annuler
                </button>

                <button 
                  type="submit" 
                  class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 flex items-center gap-1"
                  :disabled="form.processing"
                >
                  <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  <span v-if="editingId">Mettre à jour</span>
                  <span v-else>Ajouter</span>
                  <svg v-if="!form.processing" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14"/>
                    <path d="m12 5 7 7-7 7"/>
                  </svg>
                </button>
              </div>
            </form>
          </div>
        </div>
        
        <!-- Modal de confirmation de suppression -->
        <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
          <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 overflow-hidden">
            <div class="px-6 py-4 bg-red-50 border-b border-red-100">
              <div class="flex items-center">
                <div class="flex-shrink-0 bg-red-100 rounded-full p-2 mr-3">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </div>
                <h3 class="text-lg font-medium text-red-800">Supprimer la structure</h3>
              </div>
            </div>
            
            <div class="px-6 py-4">
              <p class="text-gray-700 mb-4">
                Voulez-vous vraiment supprimer la structure "{{ structureToDelete?.libelle || '' }}" ?<br>
                Cette action est irréversible.
              </p>
              
              <div class="flex justify-end space-x-3">
                <button 
                  @click="closeDeleteModal" 
                  class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors"
                >
                  Annuler
                </button>
                <button 
                  @click="confirmDelete" 
                  class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
                >
                  Supprimer
                </button>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Ajout du composant AdminToast -->
        <AdminToast ref="toast" />
      </div>
    </div>
  </Admin>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { Head, useForm, router, usePage } from '@inertiajs/vue3';
import Admin from '@/Layouts/Admin.vue';
//import SimpleLayout from '@/Layouts/SimpleLayout.vue';
import AdminToast from '@/Components/AdminToast.vue';
import axios from 'axios';

const props = defineProps({
  structures: Array,
});

const page = usePage();
const toast = ref(null);
const showModal = ref(false);
const editingId = ref(null);
const availableStructures = ref([]); // Variable pour stocker les structures disponibles

// Ajout des variables pour la confirmation de suppression
const showDeleteModal = ref(false);
const structureToDelete = ref(null);

const form = useForm({
  sigle: '',
  libelle: '',
  description: '',
  role_agent: '',
  structure_id: null,
});

// Fonction pour charger les structures disponibles
async function loadAvailableStructures() {
  try {
    const response = await axios.get('/admin/structures/available');
    availableStructures.value = response.data; // Stockez les données dans la variable
  } catch (error) {
    console.error('Erreur lors du chargement des structures disponibles :', error);
  }
}

// Surveillez les changements dans le rôle
watch(() => form.role_agent, (newRole) => {
  if (newRole === 'RS') {
    loadAvailableStructures(); // Chargez les structures disponibles
  } else {
    form.structure_id = null; // Réinitialisez la structure sélectionnée
  }
});

// Surveiller les messages flash et les afficher automatiquement
onMounted(() => {
  // Vérifier si des messages flash existent au chargement
  setTimeout(() => {
    const flash = usePage().props.flash || {};
    if (flash) {
      if (flash.success && toast.value) {
        toast.value.addToast({
          type: 'success',
          title: 'Succès',
          message: flash.success
        });
      }
      
      if (flash.error && toast.value) {
        toast.value.addToast({
          type: 'error',
          title: 'Erreur',
          message: flash.error
        });
      }
    }
  }, 100); // Petit délai pour s'assurer que le composant est monté
});

function openModal(structure = null) {
  if (structure) {
    form.sigle = structure.sigle;
    form.libelle = structure.libelle;
    form.description = structure.description;
    editingId.value = structure.id;
  } else {
    form.reset();
    editingId.value = null;
  }
  showModal.value = true;
}

function closeModal() {
  showModal.value = false;
  form.reset();
  editingId.value = null;
}

// Fonctions pour le modal de confirmation de suppression
function openDeleteModal(structure) {
  structureToDelete.value = structure;
  showDeleteModal.value = true;
}

function closeDeleteModal() {
  showDeleteModal.value = false;
  structureToDelete.value = null;
}

function confirmDelete() {
  if (!structureToDelete.value) return;
  
  destroy(structureToDelete.value.id);
  closeDeleteModal();
}

function submit() {
  if (editingId.value) {
    form.put(route('admin.structures.update', editingId.value), {
      preserveScroll: true,
      onSuccess: () => {
        closeModal();
        // Afficher un message personnalisé
        if (toast.value) {
          toast.value.addToast({
            type: 'success',
            title: 'Structure modifiée',
            message: `La structure "${form.libelle}" a été mise à jour avec succès.`
          });
        }
      },
      onError: () => {
        if (toast.value) {
          toast.value.addToast({
            type: 'error',
            title: 'Erreur de validation',
            message: 'Veuillez vérifier les informations saisies'
          });
        }
      }
    });
  } else {
    form.post(route('admin.structures.store'), {
      preserveScroll: true,
      onSuccess: () => {
        closeModal();
        // Afficher un message personnalisé
        if (toast.value) {
          toast.value.addToast({
            type: 'success',
            title: 'Structure ajoutée',
            message: `La structure "${form.libelle}" a été ajoutée avec succès.`
          });
        }
      },
      onError: () => {
        if (toast.value) {
          toast.value.addToast({
            type: 'error',
            title: 'Erreur de validation',
            message: 'Veuillez vérifier les informations saisies'
          });
        }
      }
    });
  }
}

function destroy(id) {
  // Trouver la structure pour afficher son nom dans le message de confirmation
  const structure = props.structures.find(s => s.id === id);
  
  router.delete(route('admin.structures.destroy', id), {
    onSuccess: () => {
      // Afficher un message personnalisé
      if (toast.value) {
        toast.value.addToast({
          type: 'success',
          title: 'Structure supprimée',
          message: `La structure "${structure?.libelle || ''}" a été supprimée avec succès.`
        });
      }
    },
    onError: () => {
      if (toast.value) {
        toast.value.addToast({
          type: 'error',
          title: 'Erreur de suppression',
          message: 'Impossible de supprimer cette structure'
        });
      }
    }
  });
}
</script>