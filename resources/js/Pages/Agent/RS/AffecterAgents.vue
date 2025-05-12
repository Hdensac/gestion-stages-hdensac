<template>
  <RSLayout>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <div class="mb-6">
              <h2 class="text-2xl font-semibold text-gray-800">
                Gestion des agents de la structure
              </h2>
              <p class="text-gray-600 mt-1">
                {{ structure.libelle }} ({{ structure.sigle }})
              </p>
              <div v-if="error" class="mt-4 p-4 bg-red-50 border border-red-200 rounded-md text-red-700">
                {{ error }}
              </div>
              <div v-if="structureResponsable" class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-md text-blue-700">
                <p><strong>Vous êtes responsable de :</strong> {{ structureResponsable.libelle }} ({{ structureResponsable.sigle }})</p>
              </div>
            </div>

            <!-- Agents déjà affectés -->
            <div class="mb-8">
              <h3 class="text-lg font-medium text-gray-800 mb-4">Agents affectés à cette structure</h3>

              <div v-if="agentsAffectes.length === 0" class="p-4 bg-gray-50 rounded-lg text-gray-500">
                Aucun agent n'est affecté à cette structure.
              </div>

              <div v-else class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nom
                      </th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Rôle
                      </th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Statut
                      </th>
                      <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="agent in agentsAffectes" :key="agent.id">
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                          <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center">
                            <span class="text-gray-500 font-medium">{{ agent.user.nom.charAt(0) }}{{ agent.user.prenom.charAt(0) }}</span>
                          </div>
                          <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">
                              {{ agent.user.nom }} {{ agent.user.prenom }}
                            </div>
                            <div class="text-sm text-gray-500">
                              {{ agent.user.email }}
                            </div>
                          </div>
                        </div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                              :class="{
                                'bg-blue-100 text-blue-800': agent.role_agent === 'MS',
                                'bg-green-100 text-green-800': agent.role_agent === 'RS',
                                'bg-purple-100 text-purple-800': agent.role_agent === 'DPAF',
                                'bg-gray-100 text-gray-800': agent.role_agent === 'SR'
                              }">
                          {{ agent.role_agent }}
                        </span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <span v-if="structure.responsable_id === agent.id" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                          Responsable
                        </span>
                        <span v-else class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                          Membre
                        </span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button
                          v-if="structure.responsable_id !== agent.id"
                          @click="desaffecterAgent(agent.id)"
                          class="text-red-600 hover:text-red-900"
                        >
                          Désaffecter
                        </button>
                        <span v-else class="text-gray-400">
                          Désaffectation impossible
                        </span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Affecter de nouveaux agents -->
            <div>
              <h3 class="text-lg font-medium text-gray-800 mb-4">Affecter un nouvel agent</h3>

              <form @submit.prevent="affecterAgent">
                <div class="flex flex-wrap gap-4 mb-4">
                  <div class="flex-1 min-w-[300px]">
                    <select
                      v-model="form.agent_id"
                      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                      :class="{ 'border-red-500': errors.agent_id }"
                      required
                    >
                      <option value="" disabled selected>Sélectionner un agent</option>
                      <option v-for="agent in filteredAgents" :key="agent.id" :value="agent.id">
                        {{ agent.user.nom }} {{ agent.user.prenom }} ({{ agent.role_agent }})
                      </option>
                    </select>
                    <p v-if="errors.agent_id" class="mt-1 text-sm text-red-600">{{ errors.agent_id }}</p>
                  </div>

                  <div>
                    <button
                      type="submit"
                      class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
                      :disabled="processing || !form.agent_id"
                    >
                      <span v-if="processing">Traitement en cours...</span>
                      <span v-else>Affecter</span>
                    </button>
                  </div>
                </div>
              </form>

              <!-- Filtres -->
              <div class="mt-6">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Filtrer par rôle</h4>
                <div class="flex flex-wrap gap-2">
                  <button
                    @click="roleFilter = 'all'"
                    class="px-3 py-1 rounded-full text-xs font-medium"
                    :class="roleFilter === 'all' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-800 hover:bg-gray-200'"
                  >
                    Tous
                  </button>
                  <button
                    @click="roleFilter = 'MS'"
                    class="px-3 py-1 rounded-full text-xs font-medium"
                    :class="roleFilter === 'MS' ? 'bg-blue-600 text-white' : 'bg-blue-100 text-blue-800 hover:bg-blue-200'"
                  >
                    MS
                  </button>
                  <button
                    @click="roleFilter = 'RS'"
                    class="px-3 py-1 rounded-full text-xs font-medium"
                    :class="roleFilter === 'RS' ? 'bg-green-600 text-white' : 'bg-green-100 text-green-800 hover:bg-green-200'"
                  >
                    RS
                  </button>
                  <button
                    @click="roleFilter = 'DPAF'"
                    class="px-3 py-1 rounded-full text-xs font-medium"
                    :class="roleFilter === 'DPAF' ? 'bg-purple-600 text-white' : 'bg-purple-100 text-purple-800 hover:bg-purple-200'"
                  >
                    DPAF
                  </button>
                  <button
                    @click="roleFilter = 'SR'"
                    class="px-3 py-1 rounded-full text-xs font-medium"
                    :class="roleFilter === 'SR' ? 'bg-gray-600 text-white' : 'bg-gray-100 text-gray-800 hover:bg-gray-200'"
                  >
                    SR
                  </button>
                </div>
              </div>
            </div>

            <!-- Bouton de retour -->
            <div class="mt-8">
              <Link
                :href="route('agent.rs.organigramme.index')"
                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors"
              >
                Retour à l'organigramme
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </RSLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import RSLayout from '@/Layouts/RSLayout.vue';

const props = defineProps({
  structure: Object,
  agents: Array,
  agentsAffectes: Array,
  structureResponsable: Object,
  error: String,
});

const processing = ref(false);
const roleFilter = ref('all');

// Initialiser le formulaire
const form = useForm({
  agent_id: '',
});

// Filtrer les agents par rôle
const filteredAgents = computed(() => {
  if (!props.agents) return [];

  return props.agents.filter(agent => {
    return roleFilter.value === 'all' || agent.role_agent === roleFilter.value;
  });
});

// Affecter un agent
const affecterAgent = () => {
  processing.value = true;

  form.post(route('agent.rs.organigramme.affecter-agent', props.structure.id), {
    onSuccess: () => {
      processing.value = false;
      form.reset();
    },
    onError: () => {
      processing.value = false;
    },
  });
};

// Désaffecter un agent
const desaffecterAgent = (agentId) => {
  if (confirm('Êtes-vous sûr de vouloir désaffecter cet agent de la structure ?')) {
    processing.value = true;

    router.delete(route('agent.rs.organigramme.desaffecter-agent', [props.structure.id, agentId]), {
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
