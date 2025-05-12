<template>
  <RSLayout>
    <div class="flex justify-center items-center min-h-screen bg-gray-50 py-8">
      <div class="bg-white shadow-xl rounded-2xl w-full max-w-2xl p-8 border border-blue-100">
        <div class="flex items-center gap-3 mb-6">
          <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-800">Ajouter un agent</h1>
        </div>
        <form @submit.prevent="submit" class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
              <input v-model="form.nom" type="text" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
              <input v-model="form.prenom" type="text" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
              <input v-model="form.email" type="email" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
              <input v-model="form.telephone" type="text" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Date de naissance</label>
              <input v-model="form.date_de_naissance" type="date" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Sexe</label>
              <select v-model="form.sexe" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                <option value="">Sélectionner</option>
                <option value="Homme">Homme</option>
                <option value="Femme">Femme</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Matricule</label>
              <input v-model="form.matricule" type="text" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Fonction</label>
              <input v-model="form.fonction" type="text" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Date d'embauche</label>
              <input v-model="form.date_embauche" type="date" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
              <input v-model="form.password" type="password" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Responsable de la structure</label>
              <select v-model="form.structure_responsable_id" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option :value="null">Aucune structure</option>
                <option v-for="structure in structures" :key="structure.id" :value="structure.id">
                  {{ getStructureDisplayName(structure) }}
                </option>
              </select>
              <p class="text-xs text-gray-500 mt-1">
                Sélectionnez la structure dont cet agent sera responsable. Un agent ne peut être responsable que d'une seule structure à la fois.
              </p>
            </div>
          </div>
          <div class="flex justify-end gap-2 mt-6">
            <Link :href="route('agent.rs.agents.index')" class="btn">Annuler</Link>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 font-semibold">Ajouter</button>
          </div>
        </form>
      </div>
    </div>
  </RSLayout>
</template>
<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import RSLayout from '@/Layouts/RSLayout.vue';

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