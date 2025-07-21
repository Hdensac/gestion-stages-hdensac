<template>
  <Head title="Dashboard Maître de Stage" />

  <MSLayout>
    <!-- Composant Toast pour les notifications -->
    <AdminToast ref="toast" />
    
    <!-- En-tête compact et moderne -->
    <template #header>
      <div class="flex items-center gap-3 mb-2">
        <div class="bg-gradient-to-br from-green-600 via-green-700 to-emerald-600 text-white rounded-xl w-12 h-12 flex items-center justify-center shadow-lg shadow-green-500/20">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
          </svg>
        </div>
        <div class="flex-1">
          <h1 class="text-2xl font-black bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent leading-tight">
            Tableau de bord MS
          </h1>
          <p class="text-sm text-slate-600 mt-1 font-medium">
            <span v-if="structureResponsable">{{ structureResponsable.sigle }} - {{ structureResponsable.libelle }}</span>
            <span v-else>Maître de Stage</span>
          </p>
          <div class="flex items-center gap-3 mt-2">
            <div class="flex items-center gap-2 bg-green-100 px-2 py-1 rounded-full">
              <div class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></div>
              <span class="text-xs font-bold text-green-700">En ligne</span>
            </div>
            <div class="text-xs text-slate-500 font-mono">
              {{ new Date().toLocaleTimeString('fr-FR') }}
            </div>
          </div>
        </div>

        <!-- Notifications rapides -->
        <div class="flex items-center gap-2">
          <div v-if="stats && stats.stagesEnCours > 0" class="bg-blue-100 border border-blue-300 text-blue-800 px-3 py-1 rounded-lg flex items-center gap-2 shadow-sm">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <span class="text-xs font-bold">{{ stats.stagesEnCours }} en cours</span>
          </div>
        </div>
      </div>
    </template>

    <!-- Arrière-plan grisé -->
    <div class="min-h-screen bg-gradient-to-br from-gray-100 via-slate-100 to-gray-200">
      <div class="py-8">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <!-- Message d'erreur amélioré -->
          <div v-if="error" class="mb-8 bg-gradient-to-r from-red-100 to-rose-100 border-2 border-red-300 text-red-800 p-6 rounded-3xl shadow-xl transform transition-all duration-300 hover:scale-[1.02]">
            <div class="flex items-center gap-4">
              <div class="p-3 bg-gradient-to-br from-red-500 to-rose-600 rounded-2xl shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
          </div>
              <div>
                <p class="font-bold text-lg">Erreur</p>
                <p class="mt-1 font-medium">{{ error }}</p>
              </div>
            </div>
        </div>



          <!-- Statistiques avec couleurs pleines -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
            <!-- Stages en cours - thème bleu -->
            <div class="bg-gradient-to-br from-blue-50 via-sky-50 to-blue-50 rounded-3xl shadow-2xl border-2 border-blue-200 p-8 transform transition-all duration-300 hover:shadow-3xl hover:scale-105">
              <div class="flex items-center gap-6">
                <div class="bg-gradient-to-br from-blue-600 to-sky-700 text-white rounded-2xl p-5 shadow-xl shadow-blue-500/30">
                  <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                  </svg>
                </div>
                <div>
                  <p class="text-base font-bold text-blue-700 mb-2">Stages en cours</p>
                  <p class="text-4xl font-black text-slate-800">
                  {{ stats.stagesEnCours }}
                </p>
            </div>
          </div>
          </div>

            <!-- Stages terminés - thème vert -->
            <div class="bg-gradient-to-br from-green-50 via-emerald-50 to-green-50 rounded-3xl shadow-2xl border-2 border-green-200 p-8 transform transition-all duration-300 hover:shadow-3xl hover:scale-105">
              <div class="flex items-center gap-6">
                <div class="bg-gradient-to-br from-green-600 to-emerald-700 text-white rounded-2xl p-5 shadow-xl shadow-green-500/30">
                  <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div>
                  <p class="text-base font-bold text-green-700 mb-2">Stages terminés</p>
                  <p class="text-4xl font-black text-slate-800">
                  {{ stats.stagesTermines }}
                </p>
            </div>
          </div>
          </div>

            <!-- Total stagiaires - thème violet -->
            <div class="bg-gradient-to-br from-purple-50 via-violet-50 to-purple-50 rounded-3xl shadow-2xl border-2 border-purple-200 p-8 transform transition-all duration-300 hover:shadow-3xl hover:scale-105">
              <div class="flex items-center gap-6">
                <div class="bg-gradient-to-br from-purple-600 to-violet-700 text-white rounded-2xl p-5 shadow-xl shadow-purple-500/30">
                  <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                  </svg>
                </div>
                <div>
                  <p class="text-base font-bold text-purple-700 mb-2">Total stagiaires</p>
                  <p class="text-4xl font-black text-slate-800">
                  {{ stats.totalStagiaires }}
                </p>
              </div>
            </div>
          </div>
        </div>

          <!-- Actions rapides MS -->
          <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl border-2 border-slate-200/50 overflow-hidden mb-8">
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
              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <Link :href="route('agent.ms.stages')"
                      class="group p-6 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl border-2 border-blue-200/50 hover:border-blue-300/50 hover:shadow-xl transition-all duration-300 hover:scale-105">
                  <div class="flex flex-col items-center text-center">
                    <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl text-white mb-4 group-hover:scale-110 transition-transform duration-300">
                      <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                      </svg>
                    </div>
                    <h4 class="font-bold text-blue-900 mb-1">Mes Stages</h4>
                    <p class="text-sm text-blue-700">Gérer tous les stages</p>
                  </div>
                </Link>

                <div class="group p-6 bg-gradient-to-br from-emerald-50 to-green-50 rounded-2xl border-2 border-emerald-200/50 hover:border-emerald-300/50 hover:shadow-xl transition-all duration-300 hover:scale-105 cursor-pointer">
                  <div class="flex flex-col items-center text-center">
                    <div class="p-3 bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl text-white mb-4 group-hover:scale-110 transition-transform duration-300">
                      <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                      </svg>
                    </div>
                    <h4 class="font-bold text-emerald-900 mb-1">Contact</h4>
                    <p class="text-sm text-emerald-700">Contacter stagiaires</p>
                  </div>
                </div>

                <div class="group p-6 bg-gradient-to-br from-purple-50 to-indigo-50 rounded-2xl border-2 border-purple-200/50 hover:border-purple-300/50 hover:shadow-xl transition-all duration-300 hover:scale-105 cursor-pointer">
                  <div class="flex flex-col items-center text-center">
                    <div class="p-3 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl text-white mb-4 group-hover:scale-110 transition-transform duration-300">
                      <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                      </svg>
                    </div>
                    <h4 class="font-bold text-purple-900 mb-1">Évaluations</h4>
                    <p class="text-sm text-purple-700">Noter les stages</p>
                  </div>
                </div>

                <div class="group p-6 bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl border-2 border-amber-200/50 hover:border-amber-300/50 hover:shadow-xl transition-all duration-300 hover:scale-105 cursor-pointer">
                  <div class="flex flex-col items-center text-center">
                    <div class="p-3 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl text-white mb-4 group-hover:scale-110 transition-transform duration-300">
                      <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                      </svg>
                    </div>
                    <h4 class="font-bold text-amber-900 mb-1">Réaffectation</h4>
                    <p class="text-sm text-amber-700">Transférer stages</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Filtres pour les stages - thème orange -->
          <div class="bg-gradient-to-br from-orange-50 via-amber-50 to-orange-50 rounded-3xl shadow-2xl border-2 border-orange-200 mb-8">
            <div class="p-8">
              <div class="flex flex-wrap items-center justify-between gap-6 mb-6">
                <h2 class="text-3xl font-black bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                Mes stages
              </h2>
                <Link :href="route('agent.ms.stages')" 
                  class="px-8 py-4 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-2xl font-bold shadow-xl transition-all duration-300 transform hover:scale-105">
                  Voir tous
                </Link>
              </div>

              <div class="flex flex-wrap gap-4">
                <select 
                  v-model="filters.statut" 
                  class="bg-orange-100 border-2 border-orange-300 rounded-2xl px-4 py-3 shadow-lg focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all duration-300 font-medium text-slate-800"
                >
                  <option value="">Tous les statuts</option>
                  <option value="En cours">En cours</option>
                  <option value="Terminé">Terminés</option>
                  <option value="En attente">En attente</option>
                </select>
                <input
                  v-model="filters.search"
                  type="text"
                  placeholder="Rechercher un stagiaire..."
                  class="bg-orange-100 border-2 border-orange-300 rounded-2xl px-4 py-3 shadow-lg focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all duration-300 font-medium text-slate-800 placeholder-slate-500"
                />
                <button
                  @click="resetFilters"
                  class="px-6 py-3 bg-gradient-to-r from-slate-200 to-slate-300 text-slate-700 rounded-2xl hover:from-slate-300 hover:to-slate-400 transition-all duration-300 shadow-lg font-bold transform hover:scale-105"
                >
                  Réinitialiser
                </button>
              </div>
              </div>
            </div>

          <!-- Tableau des stages avec couleurs pleines -->
          <div v-if="filteredStages.length === 0" class="text-center py-20">
            <div class="bg-gradient-to-br from-slate-100 to-gray-200 rounded-3xl p-16 shadow-xl border-2 border-slate-300">
              <svg class="w-16 h-16 mx-auto mb-6 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <p class="text-slate-600 text-xl font-bold">Aucun stage correspondant aux critères de recherche.</p>
            </div>
          </div>
          <div v-else class="bg-gradient-to-br from-indigo-50 via-blue-50 to-indigo-50 rounded-3xl shadow-2xl border-2 border-indigo-200 overflow-hidden">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y-2 divide-indigo-200">
                <thead class="bg-gradient-to-r from-indigo-100 to-blue-100">
                  <tr>
                    <th scope="col" class="px-8 py-6 text-left text-sm font-black text-indigo-800 uppercase tracking-wider">Stagiaire</th>
                    <th scope="col" class="px-8 py-6 text-left text-sm font-black text-indigo-800 uppercase tracking-wider">Structure</th>
                    <th scope="col" class="px-8 py-6 text-left text-sm font-black text-indigo-800 uppercase tracking-wider">Période</th>
                    <th scope="col" class="px-8 py-6 text-left text-sm font-black text-indigo-800 uppercase tracking-wider">Statut</th>
                  </tr>
                </thead>
                <tbody class="bg-indigo-50 divide-y-2 divide-indigo-100">
                  <tr v-for="stage in top5DerniersStages" :key="stage.id" 
                      class="hover:bg-gradient-to-r hover:from-indigo-100 hover:to-blue-100 transition-all duration-300 group"
                      :class="{ 'bg-gradient-to-r from-yellow-100 to-amber-100': stage.est_reaffecte }">
                    <td class="px-8 py-6 whitespace-nowrap">
                      <div class="flex items-center">
                        <div>
                          <div class="text-lg font-bold text-slate-900">
                            <template v-if="stage.demandeStage?.stagiaire?.user?.nom">
                              {{ stage.demandeStage.stagiaire.user.nom }} {{ stage.demandeStage.stagiaire.user.prenom }}
                            </template>
                            <template v-else>
                              <span class="italic text-slate-500 font-medium">Nom non disponible</span>
                            </template>
                          </div>
                          <div class="text-base text-slate-600 font-medium">
                            <template v-if="stage.demandeStage?.stagiaire?.user?.email">
                              {{ stage.demandeStage.stagiaire.user.email }}
                            </template>
                            <template v-else>
                              <span class="italic">Email non disponible</span>
                            </template>
                          </div>
                        </div>
                      </div>
                    </td>
                    <td class="px-8 py-6 whitespace-nowrap">
                      <div class="text-lg font-bold text-slate-900">{{ stage.structure?.libelle }}</div>
                      <div class="text-base text-slate-600 font-medium">{{ stage.structure?.sigle }}</div>
                      <div v-if="stage.est_reaffecte && stage.reaffectation_info" class="mt-3 bg-gradient-to-r from-yellow-100 to-amber-100 p-4 rounded-2xl border-2 border-yellow-300 shadow-lg">
                        <p class="font-bold text-yellow-800">Réaffecté à:</p>
                        <p class="text-slate-800 font-bold">{{ stage.reaffectation_info.nouveau_ms_prenom }} {{ stage.reaffectation_info.nouveau_ms_nom }}</p>
                        <p class="text-slate-700 font-medium">{{ stage.reaffectation_info.structure_libelle }}</p>
                        <p class="text-slate-600 text-sm mt-1">Le {{ formatDate(stage.reaffectation_info.date_reaffectation) }}</p>
                      </div>
                    </td>
                    <td class="px-8 py-6 whitespace-nowrap">
                      <div class="text-lg font-bold text-slate-900">
                        {{ formatDate(stage.date_debut) }} - {{ formatDate(stage.date_fin) }}
                      </div>
                      <div class="text-base text-slate-600 font-medium">
                        {{ calculateDuration(stage.date_debut, stage.date_fin) }}
                      </div>
                    </td>
                    <td class="px-8 py-6 whitespace-nowrap">
                      <span class="px-4 py-2 text-base font-bold rounded-2xl shadow-lg"
                        :class="{
                          'bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800 border-2 border-yellow-300': stage.est_reaffecte,
                          'bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border-2 border-green-300': !stage.est_reaffecte && stage.statut === 'Terminé',
                          'bg-gradient-to-r from-blue-100 to-sky-100 text-blue-800 border-2 border-blue-300': !stage.est_reaffecte && stage.statut === 'En cours',
                          'bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800 border-2 border-yellow-300': !stage.est_reaffecte && stage.statut === 'En attente',
                        }">
                          {{ stage.est_reaffecte ? 'Réaffectée' : stage.statut }}
                        </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Section conseils MS -->
          <div class="bg-gradient-to-br from-green-50 via-emerald-50 to-green-50 rounded-3xl shadow-2xl border-2 border-green-200/50 overflow-hidden mt-8">
            <div class="bg-gradient-to-r from-green-600 to-emerald-700 py-4 px-6">
              <h3 class="text-lg font-bold text-white flex items-center gap-3">
                <div class="p-2 bg-white/20 rounded-xl">
                  <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                </div>
                Conseils et bonnes pratiques MS
              </h3>
            </div>
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white/80 rounded-xl p-4 border border-green-200/50 hover:shadow-lg transition-all duration-300">
                  <div class="flex items-start gap-3">
                    <div class="p-2 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg text-white flex-shrink-0">
                      <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                      </svg>
                    </div>
                    <div>
                      <h4 class="font-bold text-green-900 mb-1 text-sm">Communication régulière</h4>
                      <p class="text-xs text-green-700">Maintenez un contact hebdomadaire avec vos stagiaires pour un suivi optimal.</p>
                    </div>
                  </div>
                </div>

                <div class="bg-white/80 rounded-xl p-4 border border-green-200/50 hover:shadow-lg transition-all duration-300">
                  <div class="flex items-start gap-3">
                    <div class="p-2 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-lg text-white flex-shrink-0">
                      <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                      </svg>
                    </div>
                    <div>
                      <h4 class="font-bold text-green-900 mb-1 text-sm">Évaluation continue</h4>
                      <p class="text-xs text-green-700">Évaluez régulièrement les progrès et donnez des retours constructifs.</p>
                    </div>
                  </div>
                </div>

                <div class="bg-white/80 rounded-xl p-4 border border-green-200/50 hover:shadow-lg transition-all duration-300">
                  <div class="flex items-start gap-3">
                    <div class="p-2 bg-gradient-to-br from-emerald-500 to-green-600 rounded-lg text-white flex-shrink-0">
                      <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                      </svg>
                    </div>
                    <div>
                      <h4 class="font-bold text-green-900 mb-1 text-sm">Suivi des échéances</h4>
                      <p class="text-xs text-green-700">Respectez les délais de fin de stage et préparez les évaluations finales.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </MSLayout>
</template>

<script setup>
import { Head, router, usePage } from '@inertiajs/vue3';
import MSLayout from '@/Layouts/MSLayout.vue';
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import AdminToast from '@/Components/AdminToast.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  stats: Object,
  derniersStages: Array,
  agent: Object,
  structureResponsable: Object,
  error: String
});

// Référence au composant Toast
const toast = ref(null);

// Vérifier les messages flash au chargement de la page
onMounted(() => {
  // Vérifier si des messages flash existent au chargement
  setTimeout(() => {
    const { flash } = usePage().props;
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
  }, 100); // Petit délai pour s'assurer que le composant est monté
});

// État pour les filtres
const filters = ref({
  statut: '',
  search: ''
});

// État pour les modals
const showStageDetailsModal = ref(false);
const showContactModal = ref(false);
const showReaffectationModal = ref(false);
const selectedStage = ref(null);
const contactForm = ref({
  subject: '',
  message: ''
});

// État pour la réaffectation
const loading = ref(false);
const errorMsg = ref('');
const maitresStage = ref([]);
const reaffectationForm = ref({
  nouveauMaitreStageId: ''
});

// État pour la boîte de dialogue de confirmation
const showConfirmDialog = ref(false);
const confirmAction = ref(null);
const confirmMessage = ref('');

// Fonction pour formater les dates
const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  const date = new Date(dateString);
  return date.toLocaleDateString('fr-FR');
};

// Fonction pour calculer la durée entre deux dates
const calculateDuration = (startDate, endDate) => {
  if (!startDate || !endDate) return 'N/A';

  const start = new Date(startDate);
  const end = new Date(endDate);
  const diffTime = Math.abs(end - start);
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

  if (diffDays < 30) {
    return `${diffDays} jour${diffDays > 1 ? 's' : ''}`;
  } else {
    const months = Math.floor(diffDays / 30);
    return `${months} mois`;
  }
};

// Filtrer les stages en fonction des critères de recherche
const filteredStages = computed(() => {
  // S'assurer que derniersStages est un tableau
  const stages = Array.isArray(props.derniersStages) ? props.derniersStages :
                (props.derniersStages ? Object.values(props.derniersStages) : []);

  if (!stages.length) return [];

  return stages.filter(stage => {
    // Filtrer par statut si un statut est sélectionné
    if (filters.value.statut && stage.statut !== filters.value.statut) {
      return false;
    }
    
    // Filtrer par recherche si une recherche est effectuée
    if (filters.value.search) {
      const searchTerm = filters.value.search.toLowerCase();
      const stagiaireNom = stage.demandeStage?.stagiaire?.user?.nom?.toLowerCase() || '';
      const stagiairePrenom = stage.demandeStage?.stagiaire?.user?.prenom?.toLowerCase() || '';
      const stagiaireEmail = stage.demandeStage?.stagiaire?.user?.email?.toLowerCase() || '';
      const structureLibelle = stage.structure?.libelle?.toLowerCase() || '';
      const structureSigle = stage.structure?.sigle?.toLowerCase() || '';
      
      return stagiaireNom.includes(searchTerm) || 
             stagiairePrenom.includes(searchTerm) || 
             stagiaireEmail.includes(searchTerm) ||
             structureLibelle.includes(searchTerm) ||
             structureSigle.includes(searchTerm);
    }
    
    return true;
  });
});

// Remplacer top3StagesEnCours par top5DerniersStages
const top5DerniersStages = computed(() => {
  return filteredStages.value
    .slice()
    .sort((a, b) => new Date(b.date_debut) - new Date(a.date_debut))
    .slice(0, 5);
});

// Réinitialiser les filtres
const resetFilters = () => {
  filters.value = {
    statut: '',
    search: ''
  };
};

// Afficher les détails d'un stage
const viewStageDetails = (stage) => {
  selectedStage.value = stage;
  showStageDetailsModal.value = true;
};

// Contacter un stagiaire
const contactStagiaire = (stage) => {
  selectedStage.value = stage;
  contactForm.value = {
    subject: `Stage - ${stage.structure?.libelle || 'Non spécifié'}`,
    message: ''
  };
  showContactModal.value = true;
};

// Envoyer un message au stagiaire
const sendMessage = async () => {
  let loadingToastId = null;

  try {
    // Afficher un indicateur de chargement
    if (toast.value) {
      loadingToastId = toast.value.addToast({
        type: 'info',
        title: 'Envoi en cours',
        message: 'Envoi du message en cours...',
        duration: 0 // Ne pas disparaître automatiquement
      });
    }

    // Envoyer le message via la route web
    const response = await axios.post(route('agent.ms.stages.send-message'), {
      stage_id: selectedStage.value.id,
      subject: contactForm.value.subject,
      message: contactForm.value.message
    });

    // Vérifier la réponse
    if (response.data.success) {
      // Fermer la modal
      showContactModal.value = false;

      // Réinitialiser le formulaire
      contactForm.value = {
        subject: '',
        message: ''
      };

      // Afficher un toast de succès
      if (toast.value) {
        toast.value.addToast({
          type: 'success',
          title: 'Message envoyé',
          message: `Message envoyé à ${selectedStage.value.demandeStage?.stagiaire?.user?.email} avec succès !`
        });
      }
    } else {
      throw new Error(response.data.message || 'Une erreur est survenue lors de l\'envoi du message.');
    }
  } catch (error) {
    console.error('Erreur lors de l\'envoi du message:', error);

    // Afficher un toast d'erreur
    if (toast.value) {
      toast.value.addToast({
        type: 'error',
        title: 'Erreur',
        message: error.response?.data?.message || error.message || 'Une erreur est survenue lors de l\'envoi du message.'
      });
    }
  } finally {
    // Supprimer le toast de chargement dans tous les cas
    if (loadingToastId !== null && toast.value) {
      setTimeout(() => {
        toast.value.removeToast(loadingToastId);
      }, 100); // Petit délai pour s'assurer que la suppression fonctionne
    }
  }
};

// Mettre à jour le statut d'un stage
const updateStageStatus = (stage, newStatus) => {
  // Utiliser la boîte de dialogue de confirmation personnalisée
  showConfirm(`Êtes-vous sûr de vouloir marquer ce stage comme ${newStatus} ?`, () => {
    // Dans une vraie implémentation, vous feriez une requête API ici
    router.post(route('agent.ms.update-stage-status', { stage: stage.id }), {
      statut: newStatus
    }, {
      onSuccess: () => {
        // Afficher un toast de succès
        if (toast.value) {
          toast.value.addToast({
            type: 'success',
            title: 'Succès',
            message: `Le statut du stage a été mis à jour avec succès.`
          });
        }

        // Fermer la modal si elle est ouverte
        if (showStageDetailsModal.value) {
          showStageDetailsModal.value = false;
        }

        // Recharger la page pour voir les changements
        router.reload();
      },
      onError: (errors) => {
        console.error('Erreur lors de la mise à jour du statut du stage:', errors);

        // Afficher un toast d'erreur
        if (toast.value) {
          toast.value.addToast({
            type: 'error',
            title: 'Erreur',
            message: 'Une erreur est survenue lors de la mise à jour du statut du stage.'
          });
        }
      }
    });
  });
};

// Ouvrir le modal de réaffectation
const openReaffectationModal = (stage) => {
  selectedStage.value = stage;
  reaffectationForm.value.nouveauMaitreStageId = '';
  errorMsg.value = '';
  showReaffectationModal.value = true;

  // Ne charger les maîtres de stage que si le stage n'a pas été réaffecté
  if (!stage.est_reaffecte) {
    loadMaitresStage(stage.id);
  }
};

// Charger les maîtres de stage des sous-structures
const loadMaitresStage = async (stageId) => {
  loading.value = true;
  errorMsg.value = '';
  maitresStage.value = [];

  try {
    const response = await axios.get(route('agent.ms.stages.maitres-stage-substructures', stageId));

    if (response.data.success) {
      maitresStage.value = response.data.maitresStage;
  } else {
      errorMsg.value = response.data.message || 'Une erreur est survenue lors du chargement des maîtres de stage.';
    }
  } catch (error) {
    console.error('Erreur lors du chargement des maîtres de stage:', error);
    errorMsg.value = error.response?.data?.message || 'Une erreur est survenue lors du chargement des maîtres de stage.';
  } finally {
    loading.value = false;
  }
};

// Fonction pour afficher la boîte de dialogue de confirmation
const showConfirm = (message, action) => {
  confirmMessage.value = message;
  confirmAction.value = action;
  showConfirmDialog.value = true;
};

// Fonction pour exécuter l'action de confirmation
const executeConfirmAction = () => {
  if (confirmAction.value) {
    confirmAction.value();
  }
  showConfirmDialog.value = false;
};

// Réaffecter le stage
const reaffecterStage = () => {
  if (!reaffectationForm.value.nouveauMaitreStageId) {
    errorMsg.value = 'Veuillez sélectionner un maître de stage.';
    // Afficher un toast d'erreur
    if (toast.value) {
      toast.value.addToast({
        type: 'error',
        title: 'Erreur',
        message: 'Veuillez sélectionner un maître de stage.'
      });
    }
    return;
  }

  // Utiliser la boîte de dialogue de confirmation personnalisée
  showConfirm('Êtes-vous sûr de vouloir réaffecter ce stage ? Cette action est irréversible.', () => {
    router.post(route('agent.ms.stages.reaffecter', selectedStage.value.id), {
      nouveau_maitre_stage_id: reaffectationForm.value.nouveauMaitreStageId
    }, {
      onSuccess: () => {
        // Afficher un toast de succès
        if (toast.value) {
          toast.value.addToast({
            type: 'success',
            title: 'Succès',
            message: 'Le stage a été réaffecté avec succès.'
          });
        }

        // Fermer les modals
        showReaffectationModal.value = false;
        showStageDetailsModal.value = false;

        // Recharger la page pour voir les changements
        router.reload();
      },
      onError: (errors) => {
        console.error('Erreur lors de la réaffectation du stage:', errors);
        errorMsg.value = 'Une erreur est survenue lors de la réaffectation du stage.';

        // Afficher un toast d'erreur
        if (toast.value) {
          toast.value.addToast({
            type: 'error',
            title: 'Erreur',
            message: 'Une erreur est survenue lors de la réaffectation du stage.'
          });
        }
      }
    });
  });
};
</script>