<template>
  <AgentDPAF>
    <div>
      <div class="flex items-center gap-4 mb-2">
        <div
          class="bg-gradient-to-br from-blue-600 to-blue-700 text-white rounded-xl w-16 h-16 flex items-center justify-center shadow-lg">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6" />
          </svg>
        </div>
        <div>
          <h1 class="text-2xl font-bold text-gray-800 leading-tight md:text-3xl tracking-tight">Détails du Stage</h1>
          <p class="text-sm text-gray-600 mt-1 font-medium">Vue DPAF – Informations détaillées du stage</p>
        </div>
      </div>
      <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="mb-8 flex flex-wrap justify-between items-center gap-6">
            <div class="flex items-center gap-4">
              <div
                class="bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 px-4 py-2 rounded-xl font-bold text-sm shadow-sm border border-blue-200">
                🔖 Stage #{{ stage.id }}
              </div>
              <span class="px-4 py-2 text-sm font-semibold rounded-xl inline-flex items-center gap-2 shadow-sm"
                :class="getStatusStyle(stage.statut)">
                {{ stage.statut }}
              </span>
            </div>
            <Link :href="route('agent.stages-dpaf.index')"
              class="px-6 py-3 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-200 shadow-sm flex items-center gap-2 font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            <span>Retour à la liste</span>
            </Link>
          </div>
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Bloc infos stage -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100/50">
              <div class="bg-gradient-to-r from-blue-600 via-blue-700 to-blue-800 px-6 py-4">
                <h2 class="text-lg font-semibold text-white flex items-center gap-3">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-3-3v6m9 4a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h7l5 5v10z" />
                  </svg>
                  Informations du stage
                </h2>
              </div>
              <div class="p-6 space-y-6">
                <div
                  class="flex items-center gap-4 p-4 bg-gradient-to-r from-blue-50 to-blue-100/50 rounded-2xl border border-blue-200/50">
                  <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-3 rounded-xl">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                  </div>
                  <div>
                    <div class="text-xs font-semibold text-blue-600 uppercase tracking-wide">Période du stage</div>
                    <div class="font-bold text-gray-800 text-lg">Du {{ formatDate(stage.date_debut) }}</div>
                    <div class="font-bold text-gray-800 text-lg">au {{ formatDate(stage.date_fin) }}</div>
                  </div>
                </div>
                <div class="border-t border-gray-100 pt-6">
                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="space-y-2">
                      <div class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center gap-2">
                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        Structure
                      </div>
                      <div class="font-semibold text-gray-800 text-sm bg-gray-50 px-3 py-2 rounded-lg">
                        {{ stage.structure ? stage.structure.libelle : 'Non spécifiée' }}
                      </div>
                    </div>
                    <div class="space-y-2">
                      <div class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center gap-2">
                        <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                        Type de stage
                      </div>
                      <div class="font-semibold text-gray-800 text-sm bg-gray-50 px-3 py-2 rounded-lg">
                        {{ stage.type === 'professionnel' ? 'Professionnel' : stage.type === 'academique' ? 'Académique'
                        : stage.type }}
                      </div>
                    </div>
                    <div class="space-y-2">
                      <div class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center gap-2">
                        <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                        Thème
                      </div>
                      <div class="font-semibold text-gray-800 text-sm bg-gray-50 px-3 py-2 rounded-lg">
                        {{ stage.theme_stage ? stage.theme_stage.intitule : 'Non défini' }}
                      </div>
                    </div>
                    <div class="space-y-2">
                      <div class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center gap-2">
                        <div class="w-2 h-2 bg-pink-500 rounded-full"></div>
                        Nature
                      </div>
                      <div class="font-semibold text-gray-800 text-sm bg-gray-50 px-3 py-2 rounded-lg">
                        {{ stage.demande_stage?.nature ? stage.demande_stage.nature : 'Non renseignée' }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Bloc Membres du groupe EN DEHORS de la grille -->
      <div v-if="stage.demande_stage?.nature === 'Groupe' && stage.demande_stage?.membres?.length"
        class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200 mt-4 max-w-4xl mx-auto flex flex-col">
        <!-- En-tête -->
        <div class="bg-gradient-to-r from-pink-500 via-pink-600 to-pink-700 px-6 py-4">
          <h2 class="text-lg font-semibold text-white flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M16 3.13a4 4 0 010 7.75M8 3.13a4 4 0 000 7.75" />
            </svg>
            Membres du groupe
          </h2>
        </div>

        <!-- Contenu -->
        <div class="p-6">
          <ul class="divide-y divide-gray-100">
            <li v-for="membre in stage.demande_stage.membres" :key="membre.id"
              class="py-4 flex items-start gap-4 hover:bg-gray-50 px-2 rounded-xl transition-all duration-200">
              <div class="flex-1">
                <div class="font-medium text-gray-800 flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-4">
                  <span class="text-base">{{ membre.user?.nom }} {{ membre.user?.prenom }}</span>
                  <span v-if="membre.user?.telephone" class="text-xs text-gray-500 flex items-center gap-1">
                    📞 {{ membre.user.telephone }}
                  </span>
                  <span v-if="membre.user?.stagiaire?.filiere"
                    class="text-xs text-purple-600 font-semibold flex items-center gap-1">
                    🎓 {{ membre.user.stagiaire.filiere }}
                  </span>
                </div>
                <div class="text-xs text-gray-500 mt-1">{{ membre.user?.email }}</div>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </AgentDPAF>
</template>

<script setup>
import AgentDPAF from '@/Layouts/AgentDPAF.vue';
import { Link, usePage } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
  stage: Object
})

function formatDate(date) {
  if (!date) return ''
  return new Date(date).toLocaleDateString('fr-FR', { year: 'numeric', month: 'long', day: 'numeric' })
}

function getStatusStyle(statut) {
  if (statut === 'En cours') return 'bg-blue-100 text-blue-700'
  if (statut === 'Terminé') return 'bg-green-100 text-green-700'
  if (statut === 'Réaffectée') return 'bg-yellow-100 text-yellow-700'
  return 'bg-gray-100 text-gray-700'
}
</script>