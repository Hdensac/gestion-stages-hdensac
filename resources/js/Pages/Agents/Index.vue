<script setup>
import { ref, watch, onMounted, computed } from 'vue';

import { Head, useForm, usePage, router } from '@inertiajs/vue3';
import Admin from '@/Layouts/Admin.vue';
import AdminToast from '@/Components/AdminToast.vue';
import axios from 'axios';


const props = defineProps({
  agents: Array, // Tableau simple d'agents
});

const page = usePage();
const flash = computed(() => page.props.flash); 

const toast = ref(null);
const showModal = ref(false);
const showDeleteModal = ref(false);
const agentToDelete = ref(null);
const editingId = ref(null);
const step = ref(1);

const passwordConfirmation = ref('');
const passwordError = ref('');

// Ajout pour gérer les structures disponibles
const availableStructures = ref([]);

// ✅ Correction ici : déclaration déplacée au-dessus du watch
const form = useForm({
  nom: '',
  prenom: '',
  email: '',
  telephone: '',
  date_de_naissance: '',
  sexe: '',
  matricule: '',
  fonction: '',
  password: '',
  password_confirmation: '',
  date_embauche: '',
  role_agent: '',
  structure_id: null, // Renommé pour plus de clarté
});

// Charger les structures disponibles si le rôle est RS
watch(() => form.role_agent, async (newRole) => {
  if (newRole === 'RS') {
    await loadAvailableStructures();
  } else {
    form.structure_id = null;
  }
});

// Surveiller les messages flash et les afficher automatiquement
onMounted(() => {
  setTimeout(() => {
    const { flash } = page.props;
    if (flash) {
      if (flash.success && toast.value) {
        toast.value.addToast({
          type: 'success',
          title: 'Succès',
          message: flash.success,
        });
      }
      
      if (flash.error && toast.value) {
        toast.value.addToast({
          type: 'error',
          title: 'Erreur',
          message: flash.error,
        });
      }
    }
  }, 100);
});

// Ajout d'une fonction pour charger les structures disponibles
async function loadAvailableStructures() {
  try {
    const response = await axios.get(route('admin.structures.available', {
      agent_id: editingId.value
    }));
    availableStructures.value = response.data;
  } catch (error) {
    console.error('Erreur lors du chargement des structures disponibles :', error);
    if (toast.value) {
      toast.value.addToast({
        type: 'error',
        title: 'Erreur',
        message: 'Impossible de charger les structures disponibles'
      });
    }
  }
}

function openModal(agent = null) {
  step.value = 1;
  passwordError.value = '';
  
  if (agent) {
    form.nom = agent.user?.nom || '';
    form.prenom = agent.user?.prenom || '';
    form.email = agent.user?.email || '';
    form.telephone = agent.user?.telephone || '';
    form.date_de_naissance = agent.user?.date_de_naissance || '';
    form.sexe = agent.user?.sexe || '';
    form.matricule = agent.matricule;
    form.fonction = agent.fonction;
    form.date_embauche = agent.date_embauche;
    form.password = '';
    form.password_confirmation = '';
    form.role_agent = agent.role_agent;
    form.structure_id = agent.structure_id;
    editingId.value = agent.id;

    // Charger les structures disponibles si l'agent est RS
    if (agent.role_agent === 'RS') {
      loadAvailableStructures();
    }
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
  step.value = 1;
  passwordError.value = '';
}

function openDeleteModal(agent) {
  agentToDelete.value = agent;
  showDeleteModal.value = true;
}

function closeDeleteModal() {
  showDeleteModal.value = false;
  agentToDelete.value = null;
}

function confirmDelete() {
  if (!agentToDelete.value) return;
  
  destroy(agentToDelete.value.id);
  closeDeleteModal();
}

function validatePasswords() {
  if (editingId.value && !form.password) {
    return true;
  }
  
  if (form.password !== form.password_confirmation) {
    passwordError.value = 'Les mots de passe ne correspondent pas';
    return false;
  }
  
  if (!editingId.value && !form.password) {
    passwordError.value = 'Le mot de passe est obligatoire';
    return false;
  }
  
  if (form.password && form.password.length < 8) {
    passwordError.value = 'Le mot de passe doit contenir au moins 8 caractères';
    return false;
  }
  
  passwordError.value = '';
  return true;
}

function nextStep() {
  if (step.value === 1) {
    if (!validatePasswords()) {
      return;
    }
    step.value = 2;
  }
}

function submit() {
  if (!validatePasswords()) {
    step.value = 1;
    return;
  }
  
  if (editingId.value) {
    form.put(route('admin.agents.update', editingId.value), {
      preserveScroll: true,
      onSuccess: () => {
        closeModal();
        if (toast.value) {
          toast.value.addToast({
            type: 'success',
            title: 'Agent modifié',
            message: `L'agent "${form.prenom} ${form.nom}" a été mis à jour avec succès.`,
          });
        }
      },
      onError: () => {
        if (toast.value) {
          toast.value.addToast({
            type: 'error',
            title: 'Erreur de validation',
            message: 'Veuillez vérifier les informations saisies',
          });
        }
      },
    });
  } else {
    form.post(route('admin.agents.store'), {
      preserveScroll: true,
      onSuccess: () => {
        closeModal();
        if (toast.value) {
          toast.value.addToast({
            type: 'success',
            title: 'Agent ajouté',
            message: `L'agent "${form.prenom} ${form.nom}" a été ajouté avec succès.`,
          });
        }
      },
      onError: () => {
        if (toast.value) {
          toast.value.addToast({
            type: 'error',
            title: 'Erreur de validation',
            message: 'Veuillez vérifier les informations saisies',
          });
        }
      },
    });
  }
}

function destroy(id) {
  const agent = props.agents.find((a) => a.id === id);
  
  router.delete(route('agents.destroy', id), {
    onSuccess: () => {
      if (toast.value) {
        toast.value.addToast({
          type: 'success',
          title: 'Agent supprimé',
          message: `L'agent "${agent?.user?.prenom || ''} ${agent?.user?.nom || ''}" a été supprimé avec succès.`,
        });
      }
    },
    onError: () => {
      if (toast.value) {
        toast.value.addToast({
          type: 'error',
          title: 'Erreur de suppression',
          message: 'Impossible de supprimer cet agent',
        });
      }
    },
  });
}
</script>

<template>
  <Head title="Gestion des agents" />
  <Admin>
    <div class="py-12 bg-gray-50 min-h-screen">
      <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 space-y-8">
        <!-- Header modernisé -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
          <div class="flex items-center gap-3">
            <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-800">Gestion des agents</h1>
            <span class="ml-2 bg-indigo-100 text-indigo-700 text-xs font-semibold px-3 py-1 rounded-full">{{ agents.length }} agents</span>
          </div>
          <button @click="openModal()"
            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-500 to-blue-500 text-white rounded-xl shadow hover:from-indigo-600 hover:to-blue-600 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2 font-semibold text-lg transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Ajouter un agent
          </button>
        </div>
        <!-- Carte liste agents -->
        <div class="bg-white shadow-lg rounded-2xl border border-indigo-100 overflow-x-visible">
          <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
            <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <h2 class="text-lg font-semibold text-indigo-800">Liste des agents</h2>
          </div>
          <div v-if="agents.length === 0" class="p-12 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-4 text-gray-300">
              <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
              <circle cx="9" cy="7" r="4"/>
              <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
              <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            <p class="text-gray-400 text-lg">Aucun agent n'a été ajouté</p>
            <button @click="openModal()" class="mt-4 px-5 py-2 bg-indigo-600 text-white rounded-xl shadow hover:bg-indigo-700 transition font-semibold">Ajouter votre premier agent</button>
          </div>
          <div v-else class="overflow-x-auto">
            <table class="w-full min-w-full divide-y divide-gray-200 table-fixed">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nom</th>
                  <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Prénom</th>
                  <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Email</th>
                  <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Téléphone</th>
                  <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Matricule</th>
                  <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Fonction</th>
                  <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Rôle</th>
                  <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-100">
                <tr v-for="agent in agents" :key="agent.id" class="hover:bg-indigo-50 transition">
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ agent.user?.nom ?? '-' }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ agent.user?.prenom ?? '-' }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ agent.user?.email ?? '-' }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ agent.user?.telephone ?? '-' }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ agent.matricule }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ agent.fonction }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ agent.role_agent }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-center">
                    <div class="flex justify-center gap-2">
                      <button @click="openModal(agent)" class="text-indigo-600 hover:text-indigo-900 font-medium flex items-center gap-1" title="Modifier">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.85 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
                        
                      </button>
                      <button @click="openDeleteModal(agent)" class="text-red-600 hover:text-red-800 font-medium flex items-center gap-1" title="Supprimer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                        
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <!-- Modale multi-étapes améliorée -->
        <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
          <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4">
            <!-- En-tête de la modale -->
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
              <h3 class="text-xl font-bold text-gray-800">
                {{ editingId ? 'Modifier un agent' : 'Ajouter un agent' }}
              </h3>
              <button @click="closeModal" class="text-gray-500 hover:text-gray-700 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <!-- Barre de progression améliorée -->
            <div class="px-6 pt-6">
              <div class="flex justify-between mb-2">
                <div class="flex items-center">
                  <div :class="[
                    'flex items-center justify-center w-8 h-8 rounded-full mr-2',
                    step === 1 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'
                  ]">1</div>
                  <span :class="{ 'text-blue-600 font-medium': step === 1 }">Informations personnelles</span>
                </div>
                <div class="flex items-center">
                  <div :class="[
                    'flex items-center justify-center w-8 h-8 rounded-full mr-2',
                    step === 2 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'
                  ]">2</div>
                  <span :class="{ 'text-blue-600 font-medium': step === 2 }">Informations professionnelles</span>
                </div>
              </div>
              <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                <div class="h-full bg-blue-500 transition-all duration-500"
                  :style="{ width: step === 1 ? '50%' : '100%' }"></div>
              </div>
            </div>

            <!-- Formulaire -->
            <form @submit.prevent="submit" class="px-6 py-4">
              <!-- Étape 1 : Informations personnelles -->
              <div v-if="step === 1" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                    <input v-model="form.nom" type="text" placeholder="Nom de l'agent"
                      class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      required />
                    <div v-if="form.errors.nom" class="text-red-600 text-sm mt-1">{{ form.errors.nom }}</div>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
                    <input v-model="form.prenom" type="text" placeholder="Prénom de l'agent"
                      class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      required />
                    <div v-if="form.errors.prenom" class="text-red-600 text-sm mt-1">{{ form.errors.prenom }}</div>
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                  <input v-model="form.email" type="email" placeholder="adresse@email.com"
                    class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    required />
                  <div v-if="form.errors.email" class="text-red-600 text-sm mt-1">{{ form.errors.email }}</div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                  <input v-model="form.telephone" type="number" placeholder="Numéro de téléphone" pattern="[0-9]{10}"
                    class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    required />
                  <div v-if="form.errors.telephone" class="text-red-600 text-sm mt-1">{{ form.errors.telephone }}</div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date de naissance</label>
                    <input v-model="form.date_de_naissance" type="date"
                      class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      required />
                    <div v-if="form.errors.date_de_naissance" class="text-red-600 text-sm mt-1">{{
                      form.errors.date_de_naissance }}</div>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sexe</label>
                    <select v-model="form.sexe"
                      class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      required>
                      <option value="">Sélectionner</option>
                      <option value="Homme">Masculin</option>
                      <option value="Femme">Féminin</option>
                    </select>
                    <div v-if="form.errors.sexe" class="text-red-600 text-sm mt-1">{{ form.errors.sexe }}</div>
                  </div>
                </div>
                
                <!-- Section mot de passe avec confirmation -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                    <input v-model="form.password" type="password" placeholder="Mot de passe"
                      class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      :required="!editingId" />
                    <div v-if="form.errors.password" class="text-red-600 text-sm mt-1">{{ form.errors.password }}</div>
                    <div v-if="editingId" class="text-gray-500 text-xs mt-1">Laissez vide pour conserver le mot de passe actuel</div>
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
                    <input v-model="form.password_confirmation" type="password" placeholder="Confirmez le mot de passe"
                      class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      :required="!editingId || form.password" />
                    <div v-if="passwordError" class="text-red-600 text-sm mt-1">{{ passwordError }}</div>
                  </div>
                </div>
              </div>

              <!-- Étape 2 : Informations professionnelles -->
              <div v-if="step === 2" class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Matricule</label>
                  <input v-model="form.matricule" type="text" placeholder="Matricule de l'agent"
                    class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    required />
                  <div v-if="form.errors.matricule" class="text-red-600 text-sm mt-1">{{ form.errors.matricule }}</div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Fonction</label>
                  <input v-model="form.fonction" type="text" placeholder="Fonction occupée"
                    class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    required />
                  <div v-if="form.errors.fonction" class="text-red-600 text-sm mt-1">{{ form.errors.fonction }}</div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Rôle de l'agent</label>
                  <select v-model="form.role_agent"
                    class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    required>
                    <option value="">Sélectionner un rôle</option>
                    <option value="DPAF">Direction du Patrimoine et des Affaires Foncières (DPAF)</option>
                    <option value="MS">Maître de Stage (MS)</option>
                    <option value="RS">Responsable de Structure (RS)</option>
                  </select>
                  <div v-if="form.errors.role_agent" class="text-red-600 text-sm mt-1">{{ form.errors.role_agent }}</div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Date d'embauche</label>
                  <input v-model="form.date_embauche" type="date"
                    class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    required />
                  <div v-if="form.errors.date_embauche" class="text-red-600 text-sm mt-1">{{ form.errors.date_embauche }}</div>
                </div>

                <!-- Ajout pour la sélection de la structure si le rôle est RS -->
                <div v-if="form.role_agent === 'RS'">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Structure</label>
                  <select 
                    v-model="form.structure_id"
                    class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    required
                  >
                    <option value="">Sélectionner une structure</option>
                    <option 
                      v-for="structure in availableStructures" 
                      :key="structure.id" 
                      :value="structure.id"
                    >
                      {{ structure.libelle }}
                      {{ structure.responsable_id === editingId ? '(Structure actuelle)' : '' }}
                    </option>
                  </select>
                  <div v-if="availableStructures.length === 0 && !editingId" class="text-amber-600 text-sm mt-1">
                    Aucune structure disponible. Toutes les structures ont déjà un responsable.
                  </div>
                  <div v-if="form.errors.structure_id" class="text-red-600 text-sm mt-1">{{ form.errors.structure_id }}</div>
                </div>
              </div>

              <!-- Boutons de navigation améliorés -->
              <div class="flex justify-between pt-6 border-t mt-6">
                <button type="button" @click="step > 1 ? step-- : closeModal()"
                  class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400 flex items-center gap-1">
                  <svg v-if="step > 1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6" />
                  </svg>
                  <svg v-else xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18M6 6l12 12" />
                  </svg>
                  {{ step > 1 ? 'Précédent' : 'Annuler' }}
                </button>

                <button v-if="step < 2" type="button" @click="nextStep()"
                  class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 flex items-center gap-1">
                  Suivant
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m9 18 6-6-6-6" />
                  </svg>
                </button>

                <button v-else type="submit"
                  class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 flex items-center gap-1"
                  :disabled="form.processing">
                  <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                  </svg>
                  <span v-if="editingId">Mettre à jour</span>
                  <span v-else>Ajouter</span>
                  <svg v-if="!form.processing" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M5 12h14" />
                    <path d="m12 5 7 7-7 7" />
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
                <h3 class="text-lg font-medium text-red-800">Supprimer l'agent</h3>
              </div>
            </div>
            
            <div class="px-6 py-4">
              <p class="text-gray-700 mb-4">
                Voulez-vous vraiment supprimer l'agent "{{ agentToDelete?.user?.prenom || '' }} {{ agentToDelete?.user?.nom || '' }}" ?<br>
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
      </div>
    </div>
  </Admin>
  
  <!-- Composant Toast pour les notifications -->
  <AdminToast ref="toast" />
</template>