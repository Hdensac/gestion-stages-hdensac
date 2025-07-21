<template>
  <AgentDPAF>
    <template #header>
      <div class="flex items-center gap-3 mb-2">
        <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-600 text-white rounded-xl w-12 h-12 flex items-center justify-center shadow-lg shadow-blue-500/20">
          <ClipboardDocumentIcon class="w-6 h-6" />
        </div>
        <div class="flex-1">
          <h1 class="text-2xl font-black bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent leading-tight">
            Tableau de bord DPAF
          </h1>
          <p class="text-sm text-slate-600 mt-1 font-medium">
            Validation et suivi des attestations
          </p>
          <div class="flex items-center gap-3 mt-2">
            <div class="flex items-center gap-2 bg-blue-100 px-2 py-1 rounded-full">
              <div class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-pulse"></div>
              <span class="text-xs font-bold text-blue-700">En ligne</span>
            </div>
            <div class="text-xs text-slate-500 font-mono">
              {{ new Date().toLocaleTimeString('fr-FR') }}
            </div>
          </div>
        </div>

        <!-- Notifications rapides -->
        <div class="flex items-center gap-2">
          <div v-if="stats && stats.demandesEnAttente > 0" class="bg-amber-100 border border-amber-300 text-amber-800 px-3 py-1 rounded-lg flex items-center gap-2 shadow-sm">
            <ClockIcon class="w-3 h-3" />
            <span class="text-xs font-bold">{{ stats.demandesEnAttente }} en attente</span>
          </div>
        </div>
      </div>
    </template>

    <div class="py-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Cartes statistiques améliorées -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
          <!-- Demandes en attente -->
          <div class="group relative bg-white/70 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 overflow-hidden transition-all duration-500 hover:shadow-2xl hover:-translate-y-1">
            <div class="absolute inset-0 bg-gradient-to-br from-amber-500/5 via-orange-500/5 to-yellow-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative p-6">
              <div class="flex items-start justify-between mb-4">
                <div class="flex-shrink-0 p-4 bg-gradient-to-br from-amber-100 to-orange-100 rounded-2xl group-hover:scale-110 transition-transform duration-300">
                  <ClockIcon class="h-8 w-8 text-amber-600" />
                </div>
                <div class="text-right">
                  <div class="w-16 h-2 bg-amber-100 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-amber-400 to-orange-500 rounded-full animate-pulse" 
                         :style="{ width: '70%' }"></div>
                  </div>
                </div>
              </div>
              <div class="space-y-2">
                <h3 class="text-lg font-bold text-slate-800 group-hover:text-amber-700 transition-colors">
                  Demandes en attente
                </h3>
                <div class="flex items-baseline gap-2">
                  <span class="text-4xl font-black bg-gradient-to-r from-amber-600 to-orange-600 bg-clip-text text-transparent">
                    {{ stats.demandesEnAttente }}
                  </span>
                  <span class="text-sm text-slate-500 font-medium">demandes</span>
                </div>
              </div>
            </div>
            <div class="bg-gradient-to-r from-amber-50 to-orange-50 px-6 py-4 border-t border-amber-100/50">
              <div class="flex items-center gap-2">
                <div class="w-2 h-2 bg-amber-500 rounded-full animate-pulse"></div>
                <span class="text-sm text-amber-800 font-semibold">Nécessitent une affectation urgente</span>
              </div>
            </div>
          </div>

          <!-- Demandes traitées -->
          <div class="group relative bg-white/70 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 overflow-hidden transition-all duration-500 hover:shadow-2xl hover:-translate-y-1">
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 via-teal-500/5 to-green-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative p-6">
              <div class="flex items-start justify-between mb-4">
                <div class="flex-shrink-0 p-4 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-2xl group-hover:scale-110 transition-transform duration-300">
                  <CheckCircleIcon class="h-8 w-8 text-emerald-600" />
                </div>
                <div class="text-right">
                  <div class="w-16 h-2 bg-emerald-100 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-emerald-400 to-teal-500 rounded-full" 
                         :style="{ width: '100%' }"></div>
                  </div>
                </div>
              </div>
              <div class="space-y-2">
                <h3 class="text-lg font-bold text-slate-800 group-hover:text-emerald-700 transition-colors">
                  Demandes traitées
                </h3>
                <div class="flex items-baseline gap-2">
                  <span class="text-4xl font-black bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">
                    {{ stats.demandesTraitees }}
                  </span>
                  <span class="text-sm text-slate-500 font-medium">demandes</span>
                </div>
              </div>
            </div>
            <div class="bg-gradient-to-r from-emerald-50 to-teal-50 px-6 py-4 border-t border-emerald-100/50">
              <div class="flex items-center gap-2">
                <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                <span class="text-sm text-emerald-800 font-semibold">Traitées avec succès</span>
              </div>
            </div>
          </div>

          <!-- Demandes affectées -->
          <div class="group relative bg-white/70 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 overflow-hidden transition-all duration-500 hover:shadow-2xl hover:-translate-y-1">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 via-indigo-500/5 to-cyan-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative p-6">
              <div class="flex items-start justify-between mb-4">
                <div class="flex-shrink-0 p-4 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-2xl group-hover:scale-110 transition-transform duration-300">
                  <UsersIcon class="h-8 w-8 text-blue-600" />
                </div>
                <div class="text-right">
                  <div class="w-16 h-2 bg-blue-100 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-blue-400 to-indigo-500 rounded-full animate-pulse" 
                         :style="{ width: '85%' }"></div>
                  </div>
                </div>
              </div>
              <div class="space-y-2">
                <h3 class="text-lg font-bold text-slate-800 group-hover:text-blue-700 transition-colors">
                  Demandes affectées
                </h3>
                <div class="flex items-baseline gap-2">
                  <span class="text-4xl font-black bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                    {{ stats.demandesAffectees }}
                  </span>
                  <span class="text-sm text-slate-500 font-medium">demandes</span>
                </div>
              </div>
            </div>
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-t border-blue-100/50">
              <div class="flex items-center gap-2">
                <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                <span class="text-sm text-blue-800 font-semibold">En cours de traitement</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Actions rapides DPAF -->
        <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl border-2 border-slate-200/50 overflow-hidden mb-12">
          <div class="bg-gradient-to-r from-slate-600 to-slate-700 py-6 px-8">
            <h3 class="text-xl font-bold text-white flex items-center gap-3">
              <div class="p-2 bg-white/20 rounded-xl">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
              </div>
              Actions rapides
            </h3>
          </div>
          <div class="p-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
              <Link :href="route('agent.dpaf.attestations.index')"
                    class="group p-6 bg-gradient-to-br from-purple-50 to-indigo-50 rounded-2xl border-2 border-purple-200/50 hover:border-purple-300/50 hover:shadow-xl transition-all duration-300 hover:scale-105">
                <div class="flex flex-col items-center text-center">
                  <div class="p-3 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl text-white mb-4 group-hover:scale-110 transition-transform duration-300">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                  </div>
                  <h4 class="font-bold text-purple-900 mb-1">Attestations</h4>
                  <p class="text-sm text-purple-700">Valider et générer</p>
                </div>
              </Link>

              <Link :href="route('agent.demandes')"
                    class="group p-6 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl border-2 border-blue-200/50 hover:border-blue-300/50 hover:shadow-xl transition-all duration-300 hover:scale-105">
                <div class="flex flex-col items-center text-center">
                  <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl text-white mb-4 group-hover:scale-110 transition-transform duration-300">
                    <ClipboardDocumentListIcon class="h-6 w-6" />
                  </div>
                  <h4 class="font-bold text-blue-900 mb-1">Demandes</h4>
                  <p class="text-sm text-blue-700">Consulter toutes</p>
                </div>
              </Link>

              <div class="group p-6 bg-gradient-to-br from-emerald-50 to-green-50 rounded-2xl border-2 border-emerald-200/50 hover:border-emerald-300/50 hover:shadow-xl transition-all duration-300 hover:scale-105 cursor-pointer">
                <div class="flex flex-col items-center text-center">
                  <div class="p-3 bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl text-white mb-4 group-hover:scale-110 transition-transform duration-300">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                  </div>
                  <h4 class="font-bold text-emerald-900 mb-1">Statistiques</h4>
                  <p class="text-sm text-emerald-700">Rapports détaillés</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Section des demandes avec filtres -->
        <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 overflow-hidden">
          <!-- Header avec actions -->
          <div class="relative bg-gradient-to-r from-slate-800 via-blue-800 to-indigo-900 py-6 px-8">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-600/20 to-indigo-600/20"></div>
            <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-4">
              <div class="flex items-center gap-3">
                <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                  <ClipboardDocumentListIcon class="h-6 w-6 text-white" />
                </div>
                <div>
                  <h2 class="font-bold text-white text-xl">Dernières demandes</h2>
                  <p class="text-blue-100 text-sm">Gestion intelligente des affectations</p>
                </div>
              </div>
              
              <div class="flex items-center gap-3">
                <!-- Filtre rapide -->
                <div class="relative">
                  <select v-model="filtreStatut" 
                          class="bg-white text-slate-700 text-sm px-4 py-2 rounded-xl border border-white/30 backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-blue-200 appearance-none pr-8">
                    <option value="">Tous les statuts</option>
                    <option value="En attente">En attente</option>
                    <option value="Acceptée">Acceptée</option>
                    <option value="En cours">En cours</option>
                  </select>
                  <ChevronDownIcon class="absolute right-2 top-1/2 transform -translate-y-1/2 h-4 w-4 text-white/70" />
                </div>
                
                <Link 
                  :href="route('agent.demandes')" 
                  class="bg-white/20 text-white px-6 py-2.5 rounded-xl hover:bg-white/30 transition-all duration-300 flex items-center gap-2 font-medium backdrop-blur-sm border border-white/30 hover:border-white/50"
                >
                  <EyeIcon class="w-4 h-4" />
                  <span>Voir tout</span>
                </Link>
              </div>
            </div>
          </div>
          
          <!-- Tableau moderne -->
          <div class="p-8">
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white/50 backdrop-blur-sm">
              <table class="min-w-full">
                <thead class="bg-gradient-to-r from-slate-50 to-blue-50">
                  <tr>
                    <th class="px-8 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider border-b border-slate-200">
                      Stagiaire
                    </th>
                    <th class="px-8 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider border-b border-slate-200">
                      Date de soumission
                    </th>
                    <th class="px-8 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider border-b border-slate-200">
                      Structure
                    </th>
                    <th class="px-8 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider border-b border-slate-200">
                      Statut
                    </th>
                   <!--  <th class="px-8 py-4 text-right text-xs font-bold text-slate-600 uppercase tracking-wider border-b border-slate-200">
                      Actions
                    </th> -->
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                  <tr v-for="(demande, index) in demandesFiltrees" 
                      :key="demande.id" 
                      class="group hover:bg-blue-50/50 transition-all duration-300"
                      :style="{ animationDelay: `${index * 50}ms` }">
                    <td class="px-8 py-6">
                      <div class="flex items-center gap-4">
                        <div class="relative">
                          <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <UserIcon class="h-5 w-5 text-blue-600" />
                          </div>
                          <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-400 rounded-full border-2 border-white"></div>
                        </div>
                        <div>
                          <span class="font-bold text-slate-800 group-hover:text-blue-800 transition-colors">
                            {{ demande.stagiaire.user.nom }}
                          </span>
                          <p class="text-sm text-slate-500">{{ demande.stagiaire.user.email || 'Email non renseigné' }}</p>
                        </div>
                      </div>
                    </td>
                    <td class="px-8 py-6">
                      <div class="flex items-center gap-3">
                        <div class="p-2 bg-slate-100 rounded-xl group-hover:bg-blue-100 transition-colors">
                          <CalendarIcon class="h-4 w-4 text-slate-500 group-hover:text-blue-600" />
                        </div>
                        <div>
                          <div class="font-semibold text-slate-700">{{ formatDate(demande.date_soumission) }}</div>
                          <div class="text-xs text-slate-500">{{ formatTimeAgo(demande.date_soumission) }}</div>
                        </div>
                      </div>
                    </td>
                    <td class="px-8 py-6">
                      <div v-if="demande.structure" class="flex items-center gap-3">
                        <div class="p-2 bg-emerald-100 rounded-xl">
                          <BuildingOfficeIcon class="h-4 w-4 text-emerald-600" />
                        </div>
                        <span class="font-medium text-slate-700">{{ demande.structure.libelle }}</span>
                      </div>
                      <div v-else class="flex items-center gap-3">
                        <div class="p-2 bg-slate-100 rounded-xl">
                          <ExclamationTriangleIcon class="h-4 w-4 text-slate-400" />
                        </div>
                        <span class="text-slate-400 italic font-medium">Non assignée</span>
                      </div>
                    </td>
                    <td class="px-8 py-6">
                      <span :class="getModernStatusClass(demande.statut)" 
                            class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold rounded-2xl">
                        <div :class="getStatusDotClass(demande.statut)" class="w-2 h-2 rounded-full"></div>
                        {{ demande.statut }}
                      </span>
                    </td>
                    <!-- <td class="px-8 py-6 text-right">
                      <div class="flex items-center justify-end gap-2">
                        <button class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all duration-200">
                          <EyeIcon class="h-4 w-4" />
                        </button>
                        <button class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all duration-200">
                          <PencilIcon class="h-4 w-4" />
                        </button>
                      </div>
                    </td> -->
                  </tr>
                  <tr v-if="demandesFiltrees.length === 0">
                    <td colspan="5" class="px-8 py-16 text-center">
                      <div class="flex flex-col items-center justify-center space-y-4">
                        <div class="p-6 bg-slate-100 rounded-3xl">
                          <InboxIcon class="w-16 h-16 text-slate-300" />
                        </div>
                        <div>
                          <p class="text-slate-500 font-semibold text-lg mb-2">Aucune demande trouvée</p>
                          <p class="text-slate-400 text-sm">Aucune demande ne correspond aux critères sélectionnés</p>
                        </div>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Section conseils DPAF -->
        <div class="bg-gradient-to-br from-indigo-50 via-blue-50 to-indigo-50 rounded-3xl shadow-2xl border-2 border-indigo-200/50 overflow-hidden mt-12">
          <div class="bg-gradient-to-r from-indigo-600 to-blue-700 py-4 px-6">
            <h3 class="text-lg font-bold text-white flex items-center gap-3">
              <div class="p-2 bg-white/20 rounded-xl">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
              Conseils et bonnes pratiques DPAF
            </h3>
          </div>
          <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div class="bg-white/80 rounded-xl p-4 border border-indigo-200/50 hover:shadow-lg transition-all duration-300">
                <div class="flex items-start gap-3">
                  <div class="p-2 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-lg text-white flex-shrink-0">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                  </div>
                  <div>
                    <h4 class="font-bold text-indigo-900 mb-1 text-sm">Validation rapide</h4>
                    <p class="text-xs text-indigo-700">Validez les attestations de structure dans les 24h pour éviter les retards.</p>
                  </div>
                </div>
              </div>

              <div class="bg-white/80 rounded-xl p-4 border border-indigo-200/50 hover:shadow-lg transition-all duration-300">
                <div class="flex items-start gap-3">
                  <div class="p-2 bg-gradient-to-br from-emerald-500 to-green-600 rounded-lg text-white flex-shrink-0">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                  </div>
                  <div>
                    <h4 class="font-bold text-indigo-900 mb-1 text-sm">Contrôle qualité</h4>
                    <p class="text-xs text-indigo-700">Vérifiez la conformité des informations avant génération de l'attestation DPAF.</p>
                  </div>
                </div>
              </div>

              <div class="bg-white/80 rounded-xl p-4 border border-indigo-200/50 hover:shadow-lg transition-all duration-300">
                <div class="flex items-start gap-3">
                  <div class="p-2 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg text-white flex-shrink-0">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                  </div>
                  <div>
                    <h4 class="font-bold text-indigo-900 mb-1 text-sm">Suivi statistique</h4>
                    <p class="text-xs text-indigo-700">Consultez régulièrement les statistiques pour optimiser les processus.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AgentDPAF>
</template>

<script setup>
import { onMounted, ref, computed } from 'vue';
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
  PencilIcon,
  ClipboardDocumentListIcon,
  InboxIcon,
  BuildingOfficeIcon,
  ExclamationTriangleIcon,
  ChevronDownIcon
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

const filtreStatut = ref('');

const demandesFiltrees = computed(() => {
  if (!filtreStatut.value) return props.dernieresDemandes;
  return props.dernieresDemandes.filter(demande => demande.statut === filtreStatut.value);
});

onMounted(() => {
  console.log('Agent Dashboard mounted', props.debug);
});

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('fr-FR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  });
};

const formatTimeAgo = (date) => {
  const now = new Date();
  const past = new Date(date);
  const diffInDays = Math.floor((now - past) / (1000 * 60 * 60 * 24));
  
  if (diffInDays === 0) return "Aujourd'hui";
  if (diffInDays === 1) return "Hier";
  if (diffInDays < 7) return `Il y a ${diffInDays} jours`;
  if (diffInDays < 30) return `Il y a ${Math.floor(diffInDays / 7)} semaines`;
  return `Il y a ${Math.floor(diffInDays / 30)} mois`;
};

const getModernStatusClass = (status) => {
  const classes = {
    'En attente': 'bg-gradient-to-r from-amber-100 to-orange-100 text-amber-800 border border-amber-200',
    'Acceptée': 'bg-gradient-to-r from-emerald-100 to-teal-100 text-emerald-800 border border-emerald-200',
    'Refusée': 'bg-gradient-to-r from-red-100 to-rose-100 text-red-800 border border-red-200',
    'En cours': 'bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 border border-blue-200'
  };
  return classes[status] || 'bg-gradient-to-r from-slate-100 to-gray-100 text-slate-800 border border-slate-200';
};

const getStatusDotClass = (status) => {
  const classes = {
    'En attente': 'bg-amber-500 animate-pulse',
    'Acceptée': 'bg-emerald-500',
    'Refusée': 'bg-red-500',
    'En cours': 'bg-blue-500 animate-pulse'
  };
  return classes[status] || 'bg-slate-500';
};
</script>

<style scoped>
@keyframes slideInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

tbody tr {
  animation: slideInUp 0.6s ease-out forwards;
  opacity: 0;
}

.group:hover tbody tr {
  animation-play-state: running;
}

/* Scrollbar styling */
::-webkit-scrollbar {
  width: 6px;
  height: 6px;
}

::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb {
  background: linear-gradient(to bottom, #3b82f6, #6366f1);
  border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(to bottom, #2563eb, #4f46e5);
}
</style>