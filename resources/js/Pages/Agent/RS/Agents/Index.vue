<template>
  <RSLayout>
    <template #header>
      <div class="flex items-center gap-4 mb-2">
        <div class="bg-blue-600 text-white rounded-lg w-14 h-14 flex items-center justify-center shadow-md">
          <UsersIcon class="w-7 h-7" />
        </div>
        <div>
          <h1 class="text-2xl font-bold text-gray-800 leading-tight md:text-3xl">Gestion des Agents</h1>
          <p class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <span>Personnel de votre structure</span>
            <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full">
              {{ agents.length }} agents
            </span>
          </p>
        </div>
      </div>
    </template>

    <div class="py-6">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex justify-end mb-6">
          <Link 
            :href="route('agent.rs.agents.create')" 
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-md shadow-sm transition-all duration-200 font-medium"
          >
            <PlusIcon class="h-5 w-5" />
            Ajouter un agent
          </Link>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
          <div class="border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-800 py-4 px-6 flex items-center gap-2">
            <UsersIcon class="h-5 w-5 text-white" />
            <h2 class="font-medium text-white text-lg">Liste des agents</h2>
          </div>

          <div v-if="agents.length === 0" class="p-12 text-center">
            <div class="flex flex-col items-center justify-center">
              <div class="bg-blue-50 rounded-full p-5 mb-4">
                <UsersIcon class="h-12 w-12 text-blue-400" />
              </div>
              <p class="text-gray-500 mb-4">Aucun agent n'a été ajouté à votre structure</p>
              <Link 
                :href="route('agent.rs.agents.create')" 
                class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-md shadow-sm transition-all duration-200 flex items-center gap-2 font-medium"
              >
                <PlusIcon class="h-4 w-4" />
                Ajouter votre premier agent
              </Link>
            </div>
          </div>

          <div v-else class="overflow-x-auto">
            <table class="w-full min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prénom</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Téléphone</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Matricule</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fonction</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Responsable de</th>
                  <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="agent in agents" :key="agent.id" class="hover:bg-gray-50 transition-colors">
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ agent.user?.nom ?? '-' }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ agent.user?.prenom ?? '-' }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ agent.user?.email ?? '-' }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ agent.user?.telephone ?? '-' }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ agent.matricule }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ agent.fonction }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <span v-if="agent.structure_responsable" class="bg-green-100 text-green-700 px-2 py-1 rounded-md text-xs font-medium">
                      {{ agent.structure_responsable }}
                    </span>
                    <span v-else class="text-gray-500">-</span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-center">
                    <div class="flex justify-center gap-2">
                      <Link 
                        :href="route('agent.rs.agents.edit', agent.id)" 
                        class="p-1.5 bg-blue-50 text-blue-700 rounded-md hover:bg-blue-100 transition-colors"
                        title="Modifier"
                      >
                        <PencilIcon class="w-4 h-4" />
                      </Link>
                      <button 
                        @click="deleteAgent(agent.id)" 
                        class="p-1.5 bg-red-50 text-red-700 rounded-md hover:bg-red-100 transition-colors"
                        title="Supprimer"
                      >
                        <TrashIcon class="w-4 h-4" />
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
import { UsersIcon, PlusIcon, PencilIcon, TrashIcon } from '@heroicons/vue/24/outline';

const props = defineProps({ agents: Array });

function deleteAgent(id) {
  if (confirm('Êtes-vous sûr de vouloir supprimer cet agent ?')) {
    router.delete(route('agent.rs.agents.destroy', id));
  }
}
</script>