<template>
  <AgentDPAF>
    <template #header>
      <div class="flex items-center gap-4 mb-2">
        <div class="bg-gradient-to-br from-blue-600 to-blue-700 text-white rounded-xl w-16 h-16 flex items-center justify-center shadow-lg">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <div>
          <h1 class="text-2xl font-bold text-gray-800 leading-tight md:text-3xl tracking-tight">Tous les stages</h1>
          <p class="text-sm text-gray-600 mt-1 font-medium">Vue globale de tous les stages dans toutes les structures</p>
        </div>
      </div>
    </template>
    <div class="min-h-screen bg-gray-50 py-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Filtres -->
        <div class="mb-6 flex flex-wrap gap-4 items-center">
          <label class="font-medium text-gray-700">Statut :</label>
          <select v-model="selectedStatut" @change="onFilterChange" class="rounded-lg border-gray-300 px-4 py-2">
            <option value="all">Tous</option>
            <option value="En cours">En cours</option>
            <option value="Terminé">Terminé</option>
          </select>
          <label class="font-medium text-gray-700 ml-4">Structure :</label>
          <select v-model="selectedStructure" @change="onFilterChange" class="rounded-lg border-gray-300 px-4 py-2">
            <option value="all">Toutes</option>
            <option v-for="structure in structures" :key="structure.id" :value="structure.id">{{ structure.libelle }}</option>
          </select>
        </div>
        <!-- Table des stages -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100/50">
          <div class="p-6">
            <table class="min-w-full divide-y divide-gray-100">
              <thead class="bg-gradient-to-r from-gray-50 to-gray-100/50">
                <tr>
                  <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Stagiaire</th>
                  <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Structure</th>
                  <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Type</th>
                  <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Période</th>
                  <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Statut</th>
                  <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-50">
                <tr v-for="stage in stages.data" :key="stage.id" class="hover:bg-gray-50/50 transition-all duration-200 group">
                  <td class="px-6 py-5 whitespace-nowrap">
                    <div class="font-bold text-gray-900">{{ stage.stagiaire?.user?.nom }} {{ stage.stagiaire?.user?.prenom }}</div>
                    <div class="text-xs text-gray-500">{{ stage.stagiaire?.user?.email }}</div>
                  </td>
                  <td class="px-6 py-5 whitespace-nowrap">
                    <div class="font-semibold text-gray-800">{{ stage.structure?.sigle }}</div>
                  </td>
                  <td class="px-6 py-5 whitespace-nowrap">
                    <span
                      class="inline-block px-3 py-1 rounded-xl font-semibold text-xs"
                      :class="{
                        'bg-blue-100 text-blue-700': stage.type === 'professionnel',
                        'bg-emerald-100 text-emerald-700': stage.type === 'academique',
                        'bg-gray-100 text-gray-700': stage.type !== 'professionnel' && stage.type !== 'academique'
                      }"
                    >
                      {{ stage.type === 'professionnel' ? 'Professionnel' : stage.type === 'academique' ? 'Académique' : stage.type }}
                    </span>
                  </td>
                  <td class="px-6 py-5 whitespace-nowrap">
                    <div class="text-sm text-gray-700">Du {{ formatDate(stage.date_debut) }}<br>au {{ formatDate(stage.date_fin) }}</div>
                  </td>
                  <td class="px-6 py-5 whitespace-nowrap">
                    <span class="inline-block px-3 py-1 rounded-xl font-semibold text-xs"
                      :class="{
                        'bg-blue-100 text-blue-700': stage.statut === 'En cours',
                        'bg-emerald-100 text-emerald-700': stage.statut === 'Terminé',
                        'bg-gray-100 text-gray-700': stage.statut !== 'En cours' && stage.statut !== 'Terminé'
                      }">
                      {{ stage.statut }}
                    </span>
                  </td>
                  <td class="px-6 py-5 whitespace-nowrap">
                    <Link :href="route('agent.stages-dpaf.show', stage.id)" class="text-blue-600 hover:underline font-semibold">Voir</Link>
                  </td>
                </tr>
                <tr v-if="stages.data.length === 0">
                  <td colspan="6" class="px-6 py-16 text-center text-gray-400">Aucun stage trouvé.</td>
                </tr>
              </tbody>
            </table>
            <!-- Pagination -->
            <div class="mt-6 flex justify-center gap-2">
              <button
                :disabled="!stages.prev_page_url"
                @click="goToPage(stages.prev_page_url)"
                class="px-4 py-2 rounded-lg border text-sm font-medium"
                :class="[stages.prev_page_url ? 'bg-white text-blue-700 border-gray-300 hover:bg-blue-50' : 'opacity-50 cursor-not-allowed bg-white text-gray-400 border-gray-200']"
              >
                &laquo; Précédent
              </button>
              <span class="px-4 py-2 rounded-lg border bg-blue-600 text-white border-blue-600">{{ stages.current_page }}</span>
              <button
                :disabled="!stages.next_page_url"
                @click="goToPage(stages.next_page_url)"
                class="px-4 py-2 rounded-lg border text-sm font-medium"
                :class="[stages.next_page_url ? 'bg-white text-blue-700 border-gray-300 hover:bg-blue-50' : 'opacity-50 cursor-not-allowed bg-white text-gray-400 border-gray-200']"
              >
                Suivant &raquo;
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AgentDPAF>
</template>

<script setup>
import AgentDPAF from '@/Layouts/AgentDPAF.vue';
import { ref } from 'vue';
import { router, Link } from '@inertiajs/vue3';

const props = defineProps({
  stages: Object,
  structures: Array,
  filters: Object
});

const selectedStatut = ref(props.filters?.statut || 'all');
const selectedStructure = ref(props.filters?.structure_id || 'all');

function onFilterChange() {
  router.get(route('agent.stages-dpaf.index'), { statut: selectedStatut.value, structure_id: selectedStructure.value }, { preserveState: true });
}

function goToPage(url) {
  if (url) {
    router.visit(url, { preserveState: true });
  }
}

function formatDate(date) {
  if (!date) return 'Non définie';
  return new Date(date).toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  });
}
</script> 