<template>
  <AgentDPAF>
    <template #header>
      <div class="flex items-center gap-4 mb-2">
        <div class="bg-blue-600 text-white rounded-lg w-14 h-14 flex items-center justify-center shadow-md">
          <ClipboardDocumentIcon class="w-7 h-7" />
        </div>
        <div>
          <h1 class="text-2xl font-bold text-gray-800 leading-tight md:text-3xl">Tableau de bord DPAF</h1>
          <p class="text-sm text-gray-500 mt-1">Suivi des demandes et affectations</p>
        </div>
      </div>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Cartes statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <!-- Demandes en attente -->
          <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100 transition-all duration-200 hover:shadow-lg">
            <div class="p-5">
              <div class="flex items-center">
                <div class="flex-shrink-0 p-3 bg-amber-100 rounded-lg">
                  <ClockIcon class="h-8 w-8 text-amber-600" />
                </div>
                <div class="ml-4">
                  <h3 class="text-lg font-semibold text-gray-800">Demandes en attente</h3>
                  <div class="flex items-baseline">
                    <p class="text-3xl font-bold text-gray-800">{{ stats.demandesEnAttente }}</p>
                    <p class="ml-2 text-sm text-gray-600">demandes</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="bg-amber-50 px-5 py-3">
              <div class="text-sm text-amber-700 font-medium">Nécessitent une affectation</div>
            </div>
          </div>

          <!-- Demandes traitées -->
          <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100 transition-all duration-200 hover:shadow-lg">
            <div class="p-5">
              <div class="flex items-center">
                <div class="flex-shrink-0 p-3 bg-emerald-100 rounded-lg">
                  <CheckCircleIcon class="h-8 w-8 text-emerald-600" />
                </div>
                <div class="ml-4">
                  <h3 class="text-lg font-semibold text-gray-800">Demandes traitées</h3>
                  <div class="flex items-baseline">
                    <p class="text-3xl font-bold text-gray-800">{{ stats.demandesTraitees }}</p>
                    <p class="ml-2 text-sm text-gray-600">demandes</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="bg-emerald-50 px-5 py-3">
              <div class="text-sm text-emerald-700 font-medium">Traitées avec succès</div>
            </div>
          </div>

          <!-- Demandes affectées -->
          <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100 transition-all duration-200 hover:shadow-lg">
            <div class="p-5">
              <div class="flex items-center">
                <div class="flex-shrink-0 p-3 bg-blue-100 rounded-lg">
                  <UsersIcon class="h-8 w-8 text-blue-600" />
                </div>
                <div class="ml-4">
                  <h3 class="text-lg font-semibold text-gray-800">Demandes affectées</h3>
                  <div class="flex items-baseline">
                    <p class="text-3xl font-bold text-gray-800">{{ stats.demandesAffectees }}</p>
                    <p class="ml-2 text-sm text-gray-600">demandes</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="bg-blue-50 px-5 py-3">
              <div class="text-sm text-blue-700 font-medium">En cours de traitement</div>
            </div>
          </div>
        </div>

        <!-- Dernières demandes -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
          <div class="border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-800 py-4 px-6 flex justify-between items-center">
            <div class="flex items-center gap-2">
              <ClipboardDocumentListIcon class="h-5 w-5 text-white" />
              <h2 class="font-medium text-white text-lg">Dernières demandes</h2>
            </div>
            <Link 
              :href="route('agent.demandes')" 
              class="text-sm bg-white/20 text-white px-3 py-1.5 rounded-md hover:bg-white/30 transition-all duration-200 flex items-center gap-2"
            >
              <EyeIcon class="w-4 h-4" />
              <span>Voir tout</span>
            </Link>
          </div>
          
          <div class="p-6">
            <div class="overflow-x-auto border border-gray-100 rounded-lg">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stagiaire</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date de soumission</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Structure</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="demande in dernieresDemandes" :key="demande.id" class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center gap-3">
                        <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                          <UserIcon class="h-4 w-4 text-blue-600" />
                        </div>
                        <span class="text-sm font-medium text-gray-700">{{ demande.stagiaire.user.nom }}</span>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-600 flex items-center gap-2">
                        <CalendarIcon class="h-4 w-4 text-gray-400" />
                        {{ formatDate(demande.date_soumission) }}
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span v-if="demande.structure" class="text-sm text-gray-700">{{ demande.structure.libelle }}</span>
                      <span v-else class="text-sm text-gray-400 italic">Non assignée</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="getStatusClass(demande.statut)" class="px-3 py-1 text-xs font-medium rounded-full">
                        {{ demande.statut }}
                      </span>
                    </td>
                  </tr>
                  <tr v-if="dernieresDemandes.length === 0">
                    <td colspan="4" class="px-6 py-10 text-center">
                      <div class="flex flex-col items-center justify-center">
                        <InboxIcon class="w-12 h-12 text-gray-300 mb-2" />
                        <p class="text-gray-500 text-sm">Aucune demande récente trouvée</p>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AgentDPAF>
</template>

<script setup>
import { onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import AgentDPAF from '@/Layouts/AgentDPAF.vue';
import { 
  ClipboardDocumentIcon, 
  ClockIcon, 
  CheckCircleIcon, 
  UsersIcon, 
  UserIcon,
  CalendarIcon,
  EyeIcon,
  ClipboardDocumentListIcon,
  InboxIcon
} from '@heroicons/vue/24/outline';

const props = defineProps({
  stats: {
    type: Object,
    required: true
  },
  dernieresDemandes: {
    type: Array,
    required: true
  },
  structures: {
    type: Array,
    required: true
  },
  debug: {
    type: Object,
    required: false
  }
});

onMounted(() => {
  console.log('Agent Dashboard mounted', props.debug);
});

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('fr-FR');
};

const getStatusClass = (status) => {
  const classes = {
    'En attente': 'bg-amber-100 text-amber-700',
    'Acceptée': 'bg-emerald-100 text-emerald-700',
    'Refusée': 'bg-red-100 text-red-700',
    'En cours': 'bg-blue-100 text-blue-700'
  };
  return classes[status] || 'bg-gray-100 text-gray-700';
};
</script>