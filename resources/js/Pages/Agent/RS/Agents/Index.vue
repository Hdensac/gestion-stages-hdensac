<template>
  <RSLayout>
    <template #header>
      <div class="flex items-center gap-3">
        <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-800">Gestion des Agents sans rôle</h1>
        <span class="ml-2 bg-blue-100 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full">{{ agents.length }} agents</span>
      </div>
    </template>
    <div class="py-8 bg-gray-50 min-h-screen">
      <div class="mx-auto w-full max-w-6xl px-4 sm:px-6 lg:px-8 space-y-8">
        <div class="flex justify-end mb-4">
          <Link :href="route('agent.rs.agents.create')" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-700 text-white rounded-xl shadow hover:from-blue-600 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 font-semibold text-lg transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Ajouter un agent
          </Link>
        </div>
        <div class="bg-white shadow-lg rounded-2xl border border-blue-100 overflow-x-visible">
          <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <h2 class="text-lg font-semibold text-blue-800">Liste des agents</h2>
          </div>
          <div v-if="agents.length === 0" class="p-12 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-4 text-gray-300">
              <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
              <circle cx="9" cy="7" r="4"/>
              <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
              <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            <p class="text-gray-400 text-lg">Aucun agent n'a été ajouté</p>
            <Link :href="route('agent.rs.agents.create')" class="mt-4 px-5 py-2 bg-blue-600 text-white rounded-xl shadow hover:bg-blue-700 transition font-semibold inline-block">Ajouter votre premier agent</Link>
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
                  <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Responsable de</th>
                  <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-100">
                <tr v-for="agent in agents" :key="agent.id" class="hover:bg-blue-50 transition">
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ agent.user?.nom ?? '-' }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ agent.user?.prenom ?? '-' }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ agent.user?.email ?? '-' }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ agent.user?.telephone ?? '-' }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ agent.matricule }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ agent.fonction }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ agent.structure_responsable ?? '-' }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-center">
                    <div class="flex justify-center gap-2">
                      <Link :href="route('agent.rs.agents.edit', agent.id)" class="text-blue-600 hover:text-blue-900 font-medium flex items-center gap-1" title="Modifier">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.85 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>

                      </Link>
                      <button @click="deleteAgent(agent.id)" class="text-red-600 hover:text-red-800 font-medium flex items-center gap-1" title="Supprimer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>

                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </RSLayout>
</template>
<script setup>
import { Link, router } from '@inertiajs/vue3';
import RSLayout from '@/Layouts/RSLayout.vue';
const props = defineProps({ agents: Array });
function deleteAgent(id) {
  if (confirm('Supprimer cet agent ?')) {
    router.delete(route('agent.rs.agents.destroy', id));
  }
}
</script>