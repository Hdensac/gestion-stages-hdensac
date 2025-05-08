<!-- resources/js/Pages/Users/Index.vue -->
<template>
  <Head title="Gestion des utilisateurs" />
  <Admin>
    <div class="py-12 bg-gray-50 min-h-screen">
      <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 space-y-8">
        <!-- Header modernisé -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
          <div class="flex items-center gap-3">
            <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-800">Gestion des utilisateurs</h1>
            <span class="ml-2 bg-indigo-100 text-indigo-700 text-xs font-semibold px-3 py-1 rounded-full">{{ users.length }} utilisateurs</span>
          </div>
          <button @click="openModal()" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-500 to-blue-500 text-white rounded-xl shadow hover:from-indigo-600 hover:to-blue-600 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2 font-semibold text-lg transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Ajouter un utilisateur
          </button>
        </div>
        <!-- Carte liste utilisateurs -->
        <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-indigo-100">
          <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
            <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <h2 class="text-lg font-semibold text-indigo-800">Liste des utilisateurs</h2>
          </div>
          <div v-if="users.length === 0" class="p-12 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-4 text-gray-300">
              <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
              <circle cx="9" cy="7" r="4"/>
              <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
              <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            <p class="text-gray-400 text-lg">Aucun utilisateur n'a été ajouté</p>
            <button @click="openModal()" class="mt-4 px-5 py-2 bg-indigo-600 text-white rounded-xl shadow hover:bg-indigo-700 transition font-semibold">Ajouter votre premier utilisateur</button>
          </div>
          <div v-else class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nom</th>
                  <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Email</th>
                  <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Rôle</th>
                  <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-100">
                <tr v-for="user in users" :key="user.id" class="hover:bg-indigo-50 transition">
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ user.nom }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ user.email }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span :class="[
                      'px-3 py-1 rounded-full text-xs font-bold',
                      user.role.toLowerCase() === 'admin' ? 'bg-blue-100 text-blue-800' : '',
                      user.role.toLowerCase() === 'agent' ? 'bg-green-100 text-green-800' : '',
                      user.role.toLowerCase() === 'stagiaire' ? 'bg-yellow-100 text-yellow-800' : ''
                    ]">
                      {{ user.role }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-center">
                    <div class="flex justify-center gap-2">
                      <template v-if="user.role.toLowerCase() !== 'stagiaire'">
                        <button @click="openModal(user)" class="text-indigo-600 hover:text-indigo-900 font-medium flex items-center gap-1" title="Modifier">
                          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.85 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
                          
                        </button>
                        <button @click="openDeleteModal(user)" class="text-red-600 hover:text-red-800 font-medium flex items-center gap-1" title="Supprimer">
                          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                          
                        </button>
                      </template>
                      <template v-else>
                        <span class="text-gray-400 italic">Aucune action</span>
                      </template>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <!-- Modale pour l'ajout/modification d'utilisateur -->
        <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
          <div class="bg-white rounded-lg shadow-xl max-w-lg w-full mx-4">
            <!-- En-tête de la modale -->
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
              <h3 class="text-xl font-bold text-gray-800">
                {{ editingId ? 'Modifier un utilisateur' : 'Ajouter un utilisateur' }}
              </h3>
              <button @click="closeModal" class="text-gray-500 hover:text-gray-700 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <!-- Formulaire -->
            <form @submit.prevent="submitForm" class="px-6 py-4">
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                  <input 
                    v-model="form.nom" 
                    type="text"
                    placeholder="Nom de l'utilisateur"
                    class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  />
                  <div v-if="form.errors.nom" class="mt-1 text-sm text-red-600">{{ form.errors.nom }}</div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                  <input 
                    v-model="form.email" 
                    type="email"
                    placeholder="adresse@email.com"
                    class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  />
                  <div v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Rôle</label>
                  <select 
                    v-model="form.role" 
                    class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  >
                    <option value="">Sélectionner un rôle</option>
                    <option value="admin">Admin</option>
                    <option value="agent">Agent</option>
                    <!-- <option value="stagiaire">Stagiaire</option> -->
                  </select>
                  <div v-if="form.errors.role" class="mt-1 text-sm text-red-600">{{ form.errors.role }}</div>
                </div>

                <div v-if="!editingId || form.password">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                  <input 
                    v-model="form.password" 
                    type="password"
                    placeholder="Minimum 8 caractères"
                    class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    :required="!editingId"
                  />
                  <div v-if="form.errors.password" class="mt-1 text-sm text-red-600">{{ form.errors.password }}</div>
                </div>

                <div v-if="!editingId || form.password">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
                  <input 
                    v-model="form.password_confirmation" 
                    type="password"
                    placeholder="Confirmer le mot de passe"
                    class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    :required="!editingId"
                  />
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
                  <span>{{ editingId ? 'Mettre à jour' : 'Ajouter' }}</span>
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                <h3 class="text-lg font-medium text-red-800">Supprimer l'utilisateur</h3>
              </div>
            </div>
            
            <div class="px-6 py-4">
              <p class="text-gray-700 mb-4">
                Voulez-vous vraiment supprimer l'utilisateur "{{ userToDelete?.nom || '' }}" ?<br>
                <span class="text-sm text-gray-500">Email : {{ userToDelete?.email || '' }}</span><br>
                <span class="text-sm text-gray-500">Rôle : {{ userToDelete?.role || '' }}</span><br>
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
import { ref, onMounted } from 'vue';
import { Head, useForm, router, usePage } from '@inertiajs/vue3';
import Admin from '@/Layouts/Admin.vue';
//import SimpleLayout from '@/Layouts/SimpleLayout.vue';
import AdminToast from '@/Components/AdminToast.vue';

const props = defineProps({
  users: Array,
});

const page = usePage();
const toast = ref(null);
const showModal = ref(false);
const editingId = ref(null);

// Variables pour la confirmation de suppression
const showDeleteModal = ref(false);
const userToDelete = ref(null);

// Surveiller les messages flash et les afficher automatiquement
onMounted(() => {
  // Vérifier si des messages flash existent au chargement
  setTimeout(() => {
    const { flash } = page.props;
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
  }, 100);
});

// Formulaire pour les données utilisateur
const form = useForm({
  nom: '',
  email: '',
  role: '',
  password: '',
  password_confirmation: '',
});

function openModal(user = null) {
  if (user) {
    form.nom = user.nom;
    form.email = user.email;
    form.role = user.role;
    form.password = '';
    form.password_confirmation = '';
    editingId.value = user.id;
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
function openDeleteModal(user) {
  userToDelete.value = user;
  showDeleteModal.value = true;
}

function closeDeleteModal() {
  showDeleteModal.value = false;
  userToDelete.value = null;
}

function confirmDelete() {
  if (!userToDelete.value) return;
  
  deleteUser(userToDelete.value.id);
  closeDeleteModal();
}

function submitForm() {
  if (editingId.value) {
    // Mise à jour d'un utilisateur existant
    form.put(route('admin.users.update', editingId.value), {
      onSuccess: () => {
        closeModal();
        if (toast.value) {
          toast.value.addToast({
            type: 'success',
            title: 'Utilisateur modifié',
            message: `L'utilisateur "${form.nom}" (${form.email}) avec le rôle "${form.role}" a été mis à jour avec succès.`
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
    // Création d'un nouvel utilisateur
    form.post(route('admin.users.store'), {
      onSuccess: () => {
        closeModal();
        if (toast.value) {
          toast.value.addToast({
            type: 'success',
            title: 'Utilisateur ajouté',
            message: `L'utilisateur "${form.nom}" (${form.email}) avec le rôle "${form.role}" a été ajouté avec succès.`
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

function deleteUser(id) {
  // Trouver l'utilisateur pour afficher son nom dans le message de confirmation
  const user = props.users.find(u => u.id === id);
  
  router.delete(route('admin.users.destroy', id), {
    onSuccess: () => {
      if (toast.value) {
        toast.value.addToast({
          type: 'success',
          title: 'Utilisateur supprimé',
          message: `L'utilisateur "${user?.nom || ''}" (${user?.email || ''}) avec le rôle "${user?.role || ''}" a été supprimé avec succès.`
        });
      }
    },
    onError: () => {
      if (toast.value) {
        toast.value.addToast({
          type: 'error',
          title: 'Erreur de suppression',
          message: 'Impossible de supprimer cet utilisateur'
        });
      }
    }
  });
}
</script>