<template>

  <Head :title="`Stage de ${getStagiaireName(stage)}`" />

  <MSLayout>
    <!-- Composant Toast pour les notifications -->
    <AdminToast ref="toast" />

    <template #header>
      <div class="flex items-center gap-3 mb-2">
        <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-600 text-white rounded-xl w-12 h-12 flex items-center justify-center shadow-lg shadow-blue-500/20">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
        </div>
        <div class="flex-1">
          <h1 class="text-2xl font-black bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent leading-tight">
            Détails du Stage
          </h1>
          <p class="text-sm text-slate-600 mt-1 font-medium">
            {{ getStagiaireName(stage) }}
          </p>
          <div class="flex items-center gap-3 mt-2">
            <div class="flex items-center gap-2 bg-blue-100 px-2 py-1 rounded-full">
              <div class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-pulse"></div>
              <span class="text-xs font-bold text-blue-700">{{ stage.statut }}</span>
            </div>
            <div class="text-xs text-slate-500 font-mono">
              {{ new Date().toLocaleTimeString('fr-FR') }}
            </div>
          </div>
        </div>
      </div>
    </template>

    <!-- Arrière-plan grisé pour tout le contenu -->
    <div class="min-h-screen bg-gradient-to-br from-gray-100 via-slate-100 to-gray-200">
      <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <!-- Messages d'état modernisés -->
          <div v-if="error"
            class="mb-8 bg-gradient-to-r from-red-100 to-rose-100 border-2 border-red-300 text-red-800 p-6 rounded-3xl shadow-xl transform transition-all duration-300 hover:scale-[1.02]">
            <div class="flex items-center gap-4">
              <div class="p-3 bg-gradient-to-br from-red-500 to-rose-600 rounded-2xl shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div>
                <p class="font-bold text-lg">Erreur</p>
                <p class="mt-1 font-medium">{{ error }}</p>
              </div>
            </div>
          </div>

          <div v-if="success"
            class="mb-8 bg-gradient-to-r from-blue-100 to-sky-100 border-2 border-blue-300 text-blue-800 p-6 rounded-3xl shadow-xl transform transition-all duration-300 hover:scale-[1.02]">
            <div class="flex items-center gap-4">
              <div class="p-3 bg-gradient-to-br from-blue-500 to-sky-600 rounded-2xl shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
              </div>
              <div>
                <p class="font-bold text-lg">Succès</p>
                <p class="mt-1 font-medium">{{ success }}</p>
              </div>
            </div>
          </div>

          <!-- Navigation moderne -->
          <div class="mb-8 flex flex-wrap justify-between items-center gap-4">
            <Link :href="route('agent.ms.stages')"
              class="inline-flex items-center px-4 py-2 bg-blue-100 hover:bg-blue-200 border border-blue-300 rounded-xl font-medium text-blue-800 transition-all duration-300">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
              </svg>
              Retour à la liste
            </Link>

            <!-- Badge de réaffectation -->
            <div v-if="stage.est_reaffecte"
              class="bg-amber-100 text-amber-800 px-4 py-2 rounded-xl border border-amber-300 flex items-center">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span class="font-medium text-sm">Stage réaffecté - Consultation uniquement</span>
            </div>
          </div>

          <!-- Informations de réaffectation modernisées -->
          <div v-if="stage.est_reaffecte && stage.reaffectation_info"
            class="mb-8 bg-gradient-to-br from-amber-50 via-yellow-50 to-amber-50 p-8 rounded-3xl border-2 border-amber-300 shadow-2xl">
            <h3 class="font-black text-amber-900 mb-6 flex items-center text-xl">
              <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
              </svg>
              Informations de réaffectation
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-3">
                <p class="text-base"><span class="font-bold text-amber-900">Réaffecté à :</span> <span
                    class="font-medium text-slate-700">{{ stage.reaffectation_info.nouveau_ms_prenom }} {{
                      stage.reaffectation_info.nouveau_ms_nom }}</span></p>
                <p class="text-base"><span class="font-bold text-amber-900">Structure :</span> <span
                    class="font-medium text-slate-700">{{ stage.reaffectation_info.structure_libelle }} {{
                      stage.reaffectation_info.structure_sigle ? `(${stage.reaffectation_info.structure_sigle})` : ''
                    }}</span></p>
              </div>
              <div class="space-y-3">
                <p class="text-base"><span class="font-bold text-amber-900">Date de réaffectation :</span> <span
                    class="font-medium text-slate-700">{{ formatDate(stage.reaffectation_info.date_reaffectation)
                    }}</span>
                </p>
                <p class="text-base" v-if="stage.reaffectation_info.motif"><span class="font-bold text-amber-900">Motif
                    :</span> <span class="font-medium text-slate-700">{{ stage.reaffectation_info.motif }}</span></p>
              </div>
            </div>
          </div>
          <!-- Onglets modernisés -->
          <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl border-2 border-slate-200/50 overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-slate-50 to-blue-50 border-b border-slate-200">
              <nav class="flex space-x-1 p-2">
                <button v-for="tab in tabs" :key="tab.id" @click="activeTab = tab.id" :class="[
                  'relative py-3 px-6 text-sm font-bold focus:outline-none transition-all duration-300 rounded-xl',
                  activeTab === tab.id
                    ? 'text-white bg-gradient-to-r from-blue-600 to-indigo-600 shadow-lg'
                    : 'text-slate-600 hover:text-blue-600 hover:bg-blue-50'
                ]">
                  <div class="flex items-center gap-2">
                    <component :is="tab.icon" class="w-4 h-4" />
                    <span>{{ tab.label }}</span>
                  </div>
                </button>
              </nav>
            </div>

            <!-- Contenu des onglets -->
            <div class="p-8">
              <!-- Le contenu des onglets suivra ici -->
            </div>
          </div>
          <!-- Informations générales modernisées -->
          <div v-if="activeTab === 'infos'" class="space-y-8">
            <!-- Timeline du stage modernisée -->
            <div class="bg-gradient-to-br from-purple-50 via-violet-50 to-purple-50 p-6 rounded-2xl shadow-lg border border-purple-200">
              <h3 class="text-lg font-bold mb-4 flex items-center text-purple-900">
                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
                Progression du stage
              </h3>
              <StageTimeline :start-date="stage.date_debut" :end-date="stage.date_fin" :events="getStageEvents()" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
              <!-- Informations du stagiaire modernisées -->
              <div>
                <h3 class="text-lg font-bold mb-4 flex items-center text-blue-900">
                  <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                  </svg>
                  Informations du stagiaire
                </h3>
                <div class="bg-gradient-to-br from-blue-50 via-sky-50 to-blue-50 p-6 rounded-2xl border border-blue-200 shadow-lg">
                  <div class="flex items-center mb-4">
                    <div class="bg-gradient-to-br from-blue-600 to-sky-700 text-white rounded-xl w-12 h-12 flex items-center justify-center text-lg font-bold shadow-lg mr-4">
                      {{ getStagiaireInitials() }}
                    </div>
                    <div>
                      <h4 class="text-lg font-bold text-slate-900">{{ getStagiaireName(stage) }}</h4>
                      <p class="text-sm text-slate-600 font-medium mt-1">
                        <template v-if="stage.stagiaire_info?.email">
                          {{ stage.stagiaire_info.email }}
                        </template>
                        <template v-else-if="stage.demandeStage?.stagiaire?.user?.email">
                          {{ stage.demandeStage.stagiaire.user.email }}
                        </template>
                        <template v-else>
                          <span class="italic">Email non disponible</span>
                        </template>
                      </p>
                    </div>
                  </div>
                  <div class="space-y-3">
                    <div v-if="stage.stagiaire_info?.telephone || stage.demandeStage?.stagiaire?.user?.telephone"
                      class="flex items-center space-x-3 text-sm bg-blue-100 p-3 rounded-xl border border-blue-300">
                      <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                      </svg>
                      <span class="font-medium text-slate-800">
                        {{ stage.stagiaire_info?.telephone || stage.demandeStage?.stagiaire?.user?.telephone }}
                      </span>
                    </div>
                    <div v-if="stage.stagiaire_info?.adresse || stage.demandeStage?.stagiaire?.user?.adresse"
                      class="flex items-start space-x-3 text-sm bg-blue-100 p-3 rounded-xl border border-blue-300">
                      <svg class="w-4 h-4 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                      </svg>
                      <span class="font-medium text-slate-800">
                        {{ stage.stagiaire_info?.adresse || stage.demandeStage?.stagiaire?.user?.adresse }}
                      </span>
                    </div>
                    <div class="flex items-center space-x-3 text-sm bg-blue-100 p-3 rounded-xl border border-blue-300">
                      <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                      </svg>
                      <span class="font-medium text-slate-800">
                        <template v-if="stage.stagiaire_info?.email">
                          {{ stage.stagiaire_info.email }}
                        </template>
                        <template v-else-if="stage.demandeStage?.stagiaire?.user?.email">
                          {{ stage.demandeStage.stagiaire.user.email }}
                        </template>
                        <template v-else>
                          <span class="italic">Email non disponible</span>
                        </template>
                      </span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Structure d'accueil modernisée -->
              <div>
                <h3 class="text-2xl font-black mb-6 flex items-center text-green-900">
                  <svg class="w-7 h-7 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                  </svg>
                  Structure d'accueil
                </h3>
                <div
                  class="bg-gradient-to-br from-green-50 via-emerald-50 to-green-50 p-8 rounded-3xl border-2 border-green-200 shadow-2xl">
                  <h4 class="text-xl font-black mb-6 text-slate-900">{{ stage.structure?.libelle }}</h4>
                  <div class="space-y-4">
                    <div
                      class="flex items-center space-x-3 text-base bg-green-100 p-3 rounded-2xl border border-green-300">
                      <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                      </svg>
                      <span class="font-medium text-slate-800">{{ stage.structure?.sigle || 'Sigle non disponible'
                        }}</span>
                    </div>

                    <div v-if="stage.structure?.adresse"
                      class="flex items-start space-x-3 text-base bg-green-100 p-3 rounded-2xl border border-green-300">
                      <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                      </svg>
                      <span class="font-medium text-slate-800">{{ stage.structure.adresse }}</span>
                    </div>

                    <div v-if="stage.structure?.telephone"
                      class="flex items-center space-x-3 text-base bg-green-100 p-3 rounded-2xl border border-green-300">
                      <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                      </svg>
                      <span class="font-medium text-slate-800">{{ stage.structure.telephone }}</span>
                    </div>

                    <div v-if="stage.structure?.email"
                      class="flex items-center space-x-3 text-base bg-green-100 p-3 rounded-2xl border border-green-300">
                      <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                      </svg>
                      <span class="font-medium text-slate-800">{{ stage.structure.email }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
              <!-- Période du stage -->
              <div>
                <h3 class="text-2xl font-black mb-6 flex items-center text-orange-900">
                  <svg class="w-7 h-7 mr-3 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  Période du stage
                </h3>
                <div
                  class="bg-gradient-to-br from-orange-50 via-amber-50 to-orange-50 p-8 rounded-3xl border-2 border-orange-200 shadow-2xl">
                  <div class="space-y-6">
                    <div class="flex justify-between items-center p-4 bg-orange-100 rounded-2xl border border-orange-300">
                      <span class="text-lg font-bold text-orange-800">Date de début</span>
                      <span class="text-xl font-black text-slate-800">{{ formatDate(stage.date_debut) }}</span>
                    </div>
                    <div class="flex justify-between items-center p-4 bg-orange-100 rounded-2xl border border-orange-300">
                      <span class="text-lg font-bold text-orange-800">Date de fin</span>
                      <span class="text-xl font-black text-slate-800">{{ formatDate(stage.date_fin) }}</span>
                    </div>
                    <div class="flex justify-between items-center p-4 bg-orange-100 rounded-2xl border border-orange-300">
                      <span class="text-lg font-bold text-orange-800">Durée</span>
                      <span class="text-xl font-black text-slate-800">{{ calculateDuration(stage.date_debut,
                        stage.date_fin)
                        }}</span>
                    </div>
                    <div class="flex justify-between items-center p-4 bg-orange-100 rounded-2xl border border-orange-300">
                      <span class="text-lg font-bold text-orange-800">Statut</span>
                      <span class="px-4 py-2 text-base font-bold rounded-2xl shadow-lg border-2" :class="{
                        'bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-800 border-emerald-300': stage.statut === 'Terminé',
                        'bg-gradient-to-r from-blue-100 to-sky-100 text-blue-800 border-blue-300': stage.statut === 'En cours',
                        'bg-gradient-to-r from-amber-100 to-yellow-100 text-amber-800 border-amber-300': stage.statut === 'En attente',
                      }">
                        {{ stage.statut }}
                      </span>
                    </div>
                    <!-- Bouton de confirmation modernisé -->
                    <div
                      v-if="stage.statut === 'En cours' && new Date(stage.date_fin) <= new Date() && !stage.termine_par_ms"
                      class="mt-6">
                      <button @click="confirmerFinStage"
                        class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-6 py-4 rounded-2xl font-bold text-lg shadow-xl transition-all duration-300 transform hover:scale-105">
                        Confirmer la fin du stage
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Détails du thème -->
              <div>
                <h3 class="text-2xl font-black mb-6 flex items-center text-purple-900">
                  <svg class="w-7 h-7 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                  Détails du thème
                </h3>
                <div
                  class="bg-gradient-to-br from-purple-50 via-violet-50 to-purple-50 p-8 rounded-3xl border-2 border-purple-200 shadow-2xl">
                  <div v-if="stage.themeStage" class="space-y-6">
                    <div class="p-4 bg-purple-100 rounded-2xl border border-purple-300">
                      <span class="text-lg font-bold text-purple-800 block mb-2">Titre</span>
                      <span class="text-xl font-black text-slate-800">{{ stage.themeStage.intitule }}</span>
                    </div>
                    <div class="p-4 bg-purple-100 rounded-2xl border border-purple-300">
                      <span class="text-lg font-bold text-purple-800 block mb-3">Description</span>
                      <p class="text-base text-slate-700 font-medium leading-relaxed">{{ stage.themeStage.description }}
                      </p>
                    </div>
                    <div class="flex justify-between items-center p-4 bg-purple-100 rounded-2xl border border-purple-300">
                      <span class="text-lg font-bold text-purple-800">État</span>
                      <span class="px-4 py-2 text-base font-bold rounded-2xl shadow-lg border-2" :class="{
                        'bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-800 border-emerald-300': stage.themeStage.etat === 'Validé',
                        'bg-gradient-to-r from-amber-100 to-yellow-100 text-amber-800 border-amber-300': stage.themeStage.etat === 'Proposé',
                        'bg-gradient-to-r from-red-100 to-rose-100 text-red-800 border-red-300': stage.themeStage.etat === 'Refusé',
                        'bg-gradient-to-r from-blue-100 to-sky-100 text-blue-800 border-blue-300': stage.themeStage.etat === 'Modifié',
                      }">
                        {{ stage.themeStage.etat }}
                      </span>
                    </div>
                  </div>
                  <div v-else class="py-12 text-center">
                    <div class="bg-gradient-to-br from-slate-100 to-gray-200 p-8 rounded-3xl border-2 border-slate-300">
                      <svg class="w-16 h-16 mx-auto text-slate-500 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                      </svg>
                      <p class="text-lg text-slate-600 font-bold mb-6">Aucun thème défini pour ce stage</p>
                      <div v-if="!stage.est_reaffecte" class="mt-6">
                        <button @click="activeTab = 'theme'"
                          class="inline-flex items-center px-6 py-3 border-2 border-blue-400 shadow-xl text-lg font-bold rounded-2xl text-blue-700 bg-gradient-to-r from-blue-100 to-sky-100 hover:from-blue-200 hover:to-sky-200 focus:outline-none focus:ring-4 focus:ring-blue-300 transition-all duration-300 transform hover:scale-105">
                          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                          </svg>
                          Définir un thème
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Dans les autres onglets, NE PAS inclure la grille à deux colonnes -->
          <div v-if="activeTab === 'theme'">
            <!-- Message de réaffectation modernisé -->
            <div v-if="stage.est_reaffecte"
              class="bg-gradient-to-r from-amber-100 to-orange-100 border-2 border-amber-300 text-amber-800 p-6 rounded-3xl shadow-xl transform transition-all duration-300 hover:scale-[1.02]">
              <div class="flex items-center gap-4">
                <div class="p-3 bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl shadow-lg">
                  <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                  </svg>
                </div>
                <p class="font-bold text-lg">Ce stage a été réaffecté. Vous ne pouvez pas modifier le thème.</p>
              </div>
            </div>

            <div v-else>
              <!-- Formulaire de proposition de thème modernisé -->
              <div v-if="!themesProposes.some(t => t.etat === 'Validé')" class="mb-8">
                <h3 class="text-2xl font-black mb-6 flex items-center text-orange-900">
                  <svg class="w-7 h-7 mr-3 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                  </svg>
                  Proposer un thème (Maître de stage)
                </h3>

                <form @submit.prevent="proposerThemeMS"
                  class="bg-gradient-to-br from-orange-50 via-amber-50 to-orange-50 p-8 rounded-3xl border-2 border-orange-200 shadow-2xl">
                  <div class="space-y-6">
                    <div>
                      <label class="block text-lg font-black mb-3 text-slate-900">Titre du thème *</label>
                      <input v-model="themeMSForm.intitule" required maxlength="255"
                        class="w-full border-2 border-orange-300 rounded-2xl px-4 py-3 text-base font-medium bg-orange-50 focus:border-orange-500 focus:ring-4 focus:ring-orange-200 transition-all duration-300" />
                    </div>

                    <div>
                      <label class="block text-lg font-black mb-3 text-slate-900">Description *</label>
                      <textarea v-model="themeMSForm.description" required maxlength="2000" rows="4"
                        class="w-full border-2 border-orange-300 rounded-2xl px-4 py-3 text-base font-medium bg-orange-50 focus:border-orange-500 focus:ring-4 focus:ring-orange-200 transition-all duration-300"></textarea>
                    </div>

                    <div>
                      <label class="block text-lg font-black mb-3 text-slate-900">Mots-clés (optionnel)</label>
                      <input v-model="themeMSForm.mots_cles" maxlength="255"
                        class="w-full border-2 border-orange-300 rounded-2xl px-4 py-3 text-base font-medium bg-orange-50 focus:border-orange-500 focus:ring-4 focus:ring-orange-200 transition-all duration-300" />
                    </div>

                    <div class="flex gap-4 items-center pt-4">
                      <button type="submit" :disabled="themeMSLoading"
                        class="px-8 py-4 bg-gradient-to-r from-orange-600 to-amber-600 text-white font-black text-lg rounded-2xl shadow-xl hover:from-orange-700 hover:to-amber-700 transform hover:scale-105 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                        {{ themeMSLoading ? 'Envoi...' : 'Proposer le thème' }}
                      </button>
                      <span v-if="themeMSSuccess"
                        class="text-emerald-600 font-bold text-base bg-emerald-100 px-4 py-2 rounded-2xl border border-emerald-300">{{
                        themeMSSuccess }}</span>
                      <span v-if="themeMSError"
                        class="text-red-600 font-bold text-base bg-red-100 px-4 py-2 rounded-2xl border border-red-300">{{
                        themeMSError }}</span>
                    </div>
                  </div>
                </form>
              </div>

              <!-- Liste des thèmes proposés modernisée -->
              <div>
                <h3 class="text-2xl font-black mb-6 flex items-center text-orange-900">
                  <svg class="w-7 h-7 mr-3 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                  Propositions de thèmes
                </h3>

                <div v-if="loadingThemes"
                  class="text-center py-12 bg-gradient-to-br from-orange-50 to-amber-50 rounded-3xl border-2 border-orange-200">
                  <div
                    class="animate-spin rounded-full h-12 w-12 border-4 border-orange-600 border-t-transparent mx-auto mb-4">
                  </div>
                  <p class="text-lg font-bold text-slate-700">Chargement des propositions...</p>
                </div>

                <div v-else>
                  <div v-if="errorThemes"
                    class="mb-6 bg-gradient-to-r from-red-100 to-rose-100 border-2 border-red-300 text-red-800 p-6 rounded-3xl shadow-xl">
                    <div class="flex items-center gap-4">
                      <div class="p-3 bg-gradient-to-br from-red-500 to-rose-600 rounded-2xl shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                      </div>
                      <p class="font-bold text-lg">{{ errorThemes }}</p>
                    </div>
                  </div>

                  <div v-if="themesProposes.length === 0"
                    class="text-center py-16 bg-gradient-to-br from-orange-50 to-amber-50 rounded-3xl border-2 border-orange-200 shadow-xl">
                    <div
                      class="p-6 bg-gradient-to-br from-orange-100 to-amber-100 rounded-3xl w-24 h-24 flex items-center justify-center mx-auto mb-6 shadow-xl">
                      <svg class="w-12 h-12 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                      </svg>
                    </div>
                    <p class="text-xl font-bold text-slate-700">Aucune proposition de thème pour ce stage.</p>
                  </div>

                  <ul v-else class="space-y-6">
                    <li v-for="theme in themesProposes" :key="theme.id"
                      class="bg-gradient-to-br from-orange-50 via-amber-50 to-orange-50 p-8 rounded-3xl border-2 border-orange-200 shadow-2xl hover:shadow-3xl transform hover:scale-[1.02] transition-all duration-300">

                      <div class="flex justify-between items-start mb-6">
                        <div class="flex-1">
                          <h4 class="text-2xl font-black text-slate-900 mb-4">{{ theme.intitule }}</h4>
                          <p class="text-base font-medium text-slate-700 leading-relaxed">{{ theme.description }}</p>
                        </div>

                        <span class="ml-6 px-4 py-2 rounded-2xl text-base font-black shadow-lg" :class="{
                          'bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-800 border-2 border-emerald-300': theme.etat === 'Validé',
                          'bg-gradient-to-r from-amber-100 to-yellow-100 text-amber-800 border-2 border-amber-300': theme.etat === 'Proposé',
                          'bg-gradient-to-r from-red-100 to-rose-100 text-red-800 border-2 border-red-300': theme.etat === 'Refusé'
                        }">
                          {{ theme.etat }}
                        </span>
                      </div>

                      <div class="space-y-4">
                        <div v-if="theme.mots_cles" class="flex flex-wrap gap-3">
                          <span v-for="(motCle, index) in theme.mots_cles.split(',')" :key="index"
                            class="px-3 py-2 bg-gradient-to-r from-blue-100 to-sky-100 text-blue-800 rounded-2xl text-sm font-bold border border-blue-300 shadow-md">
                            {{ motCle.trim() }}
                          </span>
                        </div>

                        <div
                          class="text-base font-medium text-slate-600 bg-orange-100 p-3 rounded-2xl border border-orange-300">
                          Proposé le {{ formatDate(theme.created_at) }}
                          <span v-if="theme.user && theme.user.prenom && theme.user.nom" class="font-bold text-orange-800">
                            par {{ theme.user.prenom }} {{ theme.user.nom }}
                          </span>
                          <span v-else-if="theme.propose_par === 'stagiaire'" class="font-bold text-orange-800">par le stagiaire</span>
                          <span v-else class="font-bold text-orange-800">par le maître de stage</span>
                        </div>

                        <div v-if="theme.etat === 'Refusé' && theme.motif_refus"
                          class="bg-gradient-to-r from-red-50 to-rose-50 p-6 rounded-3xl border-2 border-red-200 shadow-xl">
                          <p class="text-lg font-black text-red-800 mb-3">Motif du refus :</p>
                          <p class="text-base font-medium text-red-700">{{ theme.motif_refus }}</p>
                        </div>

                        <!-- Actions pour les thèmes en attente modernisées -->
                        <div v-if="theme.etat === 'Proposé'" class="flex gap-4 pt-4">
                          <button @click="validerTheme(theme)"
                            class="px-6 py-3 bg-gradient-to-r from-emerald-600 to-green-600 text-white font-black text-base rounded-2xl shadow-xl hover:from-emerald-700 hover:to-green-700 transform hover:scale-105 transition-all duration-300 flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                            </svg>
                            Valider le thème
                          </button>

                          <button @click="refuserTheme(theme)"
                            class="px-6 py-3 bg-gradient-to-r from-red-600 to-rose-600 text-white font-black text-base rounded-2xl shadow-xl hover:from-red-700 hover:to-rose-700 transform hover:scale-105 transition-all duration-300 flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Refuser le thème
                          </button>
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>

              <!-- Modal de refus modernisé -->
              <div v-if="themeEnCours"
                class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center p-6 z-50">
                <div
                  class="bg-gradient-to-br from-white via-gray-50 to-white rounded-3xl p-8 max-w-2xl w-full shadow-3xl border-2 border-gray-200 transform scale-100 transition-all duration-300">
                  <h3 class="text-2xl font-black mb-6 text-slate-900 flex items-center">
                    <svg class="w-8 h-8 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Refuser le thème
                  </h3>

                  <div class="bg-gradient-to-r from-red-50 to-rose-50 p-6 rounded-2xl border-2 border-red-200 mb-6">
                    <p class="text-base font-medium text-slate-700 mb-4">Veuillez indiquer le motif du refus pour le
                      thème :
                    </p>
                    <p class="text-lg font-black text-slate-900 bg-red-100 p-3 rounded-2xl border border-red-300">{{
                      themeEnCours.intitule }}</p>
                  </div>

                  <textarea v-model="motifRefus"
                    class="w-full border-2 border-gray-300 rounded-2xl p-4 text-base font-medium bg-gray-50 focus:border-red-500 focus:ring-4 focus:ring-red-200 transition-all duration-300 mb-6"
                    rows="5" placeholder="Motif du refus..."></textarea>

                  <div class="flex justify-end gap-4">
                    <button @click="annulerRefus"
                      class="px-6 py-3 bg-gradient-to-r from-gray-200 to-gray-300 text-gray-800 font-black text-base rounded-2xl shadow-xl hover:from-gray-300 hover:to-gray-400 transform hover:scale-105 transition-all duration-300">
                      Annuler
                    </button>
                    <button @click="confirmerRefus"
                      class="px-6 py-3 bg-gradient-to-r from-red-600 to-rose-600 text-white font-black text-base rounded-2xl shadow-xl hover:from-red-700 hover:to-rose-700 transform hover:scale-105 transition-all duration-300">
                      Confirmer le refus
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Evaluation modernisée -->
          <div v-if="activeTab === 'evaluation'" class="space-y-8">
            <h3 class="text-2xl font-black mb-6 flex items-center text-purple-900">
              <svg class="w-7 h-7 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
              Évaluation des membres du groupe
            </h3>

            <div v-if="membresEvaluation.length > 0" class="space-y-6">
              <div v-for="membre in membresEvaluation" :key="membre.id"
                class="bg-gradient-to-br from-purple-50 via-violet-50 to-purple-50 p-8 rounded-3xl border-2 border-purple-200 shadow-2xl hover:shadow-3xl transform hover:scale-[1.02] transition-all duration-300">

                <div class="flex justify-between items-center">
                  <!-- Informations du membre -->
                  <div class="flex items-center gap-6">
                    <div
                      class="bg-gradient-to-br from-purple-100 to-violet-100 rounded-3xl w-16 h-16 flex items-center justify-center shadow-xl border-2 border-purple-300">
                      <div class="text-purple-700 text-xl font-black">
                        {{ membre.user.prenom.charAt(0) }}{{ membre.user.nom.charAt(0) }}
                      </div>
                    </div>
                    <div>
                      <h4 class="text-2xl font-black text-slate-900 mb-2">{{ membre.user.prenom }} {{ membre.user.nom }}
                      </h4>
                      <p
                        class="text-base font-bold text-purple-700 bg-purple-100 px-4 py-2 rounded-2xl border border-purple-300 inline-block">
                        Stagiaire</p>
                    </div>
                  </div>

                  <!-- Actions et notes -->
                  <div class="flex items-center gap-4">
                    <template v-if="membre.evaluationMembre && membre.evaluationMembre.note_totale">
                      <!-- Note affichée -->
                      <div
                        class="bg-gradient-to-r from-emerald-100 to-green-100 border-2 border-emerald-300 px-6 py-4 rounded-3xl shadow-xl">
                        <span class="text-xl font-black text-emerald-800">Note : {{ membre.evaluationMembre.note_totale
                          }}/20</span>
                      </div>

                      <!-- Bouton consulter -->
                      <button @click="consulterEvaluation(membre)"
                        class="px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-black text-base rounded-2xl shadow-xl hover:from-blue-700 hover:to-indigo-700 transform hover:scale-105 transition-all duration-300 flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Voir l'évaluation
                      </button>
                    </template>

                    <template v-else>
                      <!-- Bouton évaluer (actif) -->
                      <button v-if="membre.statutStage === 'Terminé'" @click="ouvrirModalEvaluation(membre)"
                        class="px-8 py-4 bg-gradient-to-r from-purple-600 to-violet-600 text-white font-black text-lg rounded-2xl shadow-xl hover:from-purple-700 hover:to-violet-700 transform hover:scale-105 transition-all duration-300 flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Évaluer le stagiaire
                      </button>

                      <!-- Bouton évaluer (désactivé) -->
                      <div v-else
                        class="px-8 py-4 bg-gradient-to-r from-gray-300 to-gray-400 text-gray-600 font-black text-lg rounded-2xl shadow-xl cursor-not-allowed relative group"
                        :title="'Le stage doit être terminé pour pouvoir évaluer ce stagiaire.'">
                        <div class="flex items-center">
                          <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18 12M6 6l12 12" />
                          </svg>
                          Évaluer (indisponible)
                        </div>

                        <!-- Tooltip -->
                        <div
                          class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-4 py-2 bg-gray-800 text-white text-sm rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap z-10">
                          Le stage doit être terminé pour pouvoir évaluer ce stagiaire
                        </div>
                      </div>
                    </template>
                  </div>
                </div>

                <!-- Indicateur de statut du stage -->
                <div class="mt-6 flex items-center gap-3">
                  <span class="text-base font-bold text-slate-700">Statut du stage :</span>
                  <span class="px-4 py-2 rounded-2xl text-base font-black" :class="{
                    'bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-800 border-2 border-emerald-300': membre.statutStage === 'Terminé',
                    'bg-gradient-to-r from-amber-100 to-yellow-100 text-amber-800 border-2 border-amber-300': membre.statutStage === 'En cours',
                    'bg-gradient-to-r from-blue-100 to-sky-100 text-blue-800 border-2 border-blue-300': membre.statutStage === 'En attente'
                  }">
                    {{ membre.statutStage }}
                  </span>
                </div>
              </div>
            </div>

            <!-- Message quand aucun membre -->
            <div v-else
              class="text-center py-16 bg-gradient-to-br from-purple-50 to-violet-50 rounded-3xl border-2 border-purple-200 shadow-xl">
              <div
                class="p-6 bg-gradient-to-br from-purple-100 to-violet-100 rounded-3xl w-24 h-24 flex items-center justify-center mx-auto mb-6 shadow-xl">
                <svg class="w-12 h-12 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
              </div>
              <p class="text-xl font-bold text-slate-700">Aucun membre du groupe trouvé.</p>
              <p class="text-base font-medium text-purple-600 mt-2">Les évaluations apparaîtront ici une fois que des
                membres seront assignés au groupe.</p>
            </div>
          </div>
          <!-- Contacter le stagiaire modernisé -->
          <div v-if="activeTab === 'contacter'" class="space-y-8">
            <h3 class="text-2xl font-black mb-6 flex items-center text-cyan-900">
              <svg class="w-7 h-7 mr-3 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
              Contacter le stagiaire
            </h3>

            <div
              class="bg-gradient-to-br from-cyan-50 via-teal-50 to-cyan-50 p-8 rounded-3xl border-2 border-cyan-200 shadow-2xl">
              <!-- En-tête avec informations du stagiaire -->
              <div
                class="mb-8 bg-gradient-to-r from-cyan-100 to-teal-100 p-6 rounded-3xl border-2 border-cyan-300 shadow-xl">
                <div class="flex items-center gap-4">
                  <div
                    class="bg-gradient-to-br from-cyan-500 to-teal-600 text-white rounded-3xl w-16 h-16 flex items-center justify-center shadow-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                  </div>
                  <div>
                    <h4 class="text-xl font-black text-slate-900 mb-2">Envoyer un message à</h4>
                    <p class="text-lg font-bold text-cyan-800">{{ getStagiaireName(stage) }}</p>
                    <p class="text-base font-medium text-slate-600">
                      <template v-if="stage.stagiaire_info?.email">
                        {{ stage.stagiaire_info.email }}
                      </template>
                      <template v-else-if="stage.demandeStage?.stagiaire?.user?.email">
                        {{ stage.demandeStage.stagiaire.user.email }}
                      </template>
                    </p>
                  </div>
                </div>
              </div>

              <!-- Formulaire de contact -->
              <div class="space-y-6">
                <div>
                  <label for="contact_subject" class="block text-lg font-black mb-3 text-slate-900">
                    Sujet du message
                    <span class="text-red-600 ml-1">*</span>
                  </label>
                  <input id="contact_subject" v-model="contactForm.subject" type="text" required
                    class="w-full border-2 border-cyan-300 rounded-2xl px-4 py-4 text-base font-medium bg-cyan-50 focus:border-cyan-500 focus:ring-4 focus:ring-cyan-200 transition-all duration-300 placeholder-slate-500"
                    placeholder="Saisir le sujet de votre message" />
                </div>

                <div>
                  <label for="contact_message" class="block text-lg font-black mb-3 text-slate-900">
                    Message
                    <span class="text-red-600 ml-1">*</span>
                  </label>
                  <textarea id="contact_message" v-model="contactForm.message" rows="8" required
                    class="w-full border-2 border-cyan-300 rounded-2xl px-4 py-4 text-base font-medium bg-cyan-50 focus:border-cyan-500 focus:ring-4 focus:ring-cyan-200 transition-all duration-300 placeholder-slate-500 resize-none"
                    placeholder="Rédigez votre message..."></textarea>
                  <div class="mt-2 text-sm font-medium text-slate-600">
                    <svg class="w-4 h-4 inline mr-1 text-cyan-600" fill="none" stroke="currentColor"
                      viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Soyez précis et professionnel dans votre communication
                  </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-end gap-4 pt-6 border-t-2 border-cyan-200">
                  <button type="button" @click="activeTab = 'infos'"
                    class="px-8 py-4 bg-gradient-to-r from-gray-200 to-gray-300 text-gray-800 font-black text-base rounded-2xl shadow-xl hover:from-gray-300 hover:to-gray-400 transform hover:scale-105 transition-all duration-300 flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Annuler
                  </button>

                  <button @click="onContactSubmit"
                    class="px-8 py-4 bg-gradient-to-r from-cyan-600 to-teal-600 text-white font-black text-base rounded-2xl shadow-xl hover:from-cyan-700 hover:to-teal-700 transform hover:scale-105 transition-all duration-300 flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Envoyer le message
                  </button>
                </div>
              </div>

              <!-- Section d'aide -->
              <div class="mt-8 bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-3xl border-2 border-blue-200">
                <h5 class="text-lg font-black text-blue-900 mb-3 flex items-center">
                  <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  Conseils pour votre message
                </h5>
                <ul class="space-y-2 text-base font-medium text-blue-800">
                  <li class="flex items-start gap-2">
                    <span class="text-blue-600 font-bold">•</span>
                    Soyez clair et précis dans vos instructions
                  </li>
                  <li class="flex items-start gap-2">
                    <span class="text-blue-600 font-bold">•</span>
                    Mentionnez les échéances importantes
                  </li>
                  <li class="flex items-start gap-2">
                    <span class="text-blue-600 font-bold">•</span>
                    Restez professionnel dans le ton
                  </li>
                  <li class="flex items-start gap-2">
                    <span class="text-blue-600 font-bold">•</span>
                    N'hésitez pas à poser des questions spécifiques
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <!-- Réaffecter le stage modernisé -->
          <div v-if="activeTab === 'reaffecter'" class="space-y-8">
            <!-- Message si déjà réaffecté -->
            <div v-if="stage.est_reaffecte"
              class="bg-gradient-to-r from-amber-100 to-orange-100 border-2 border-amber-300 text-amber-800 p-6 rounded-3xl shadow-xl transform transition-all duration-300 hover:scale-[1.02]">
              <div class="flex items-center gap-4">
                <div class="p-3 bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl shadow-lg">
                  <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                  </svg>
                </div>
                <div>
                  <p class="font-bold text-lg">Stage déjà réaffecté</p>
                  <p class="font-medium text-base">Ce stage a déjà été réaffecté. Vous ne pouvez pas le réaffecter à
                    nouveau.</p>
                </div>
              </div>
            </div>

            <div v-else>
              <h3 class="text-2xl font-black mb-6 flex items-center text-rose-900">
                <svg class="w-7 h-7 mr-3 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
                Réaffecter le stage
              </h3>

              <!-- Avertissement de sécurité -->
              <div
                class="bg-gradient-to-r from-red-100 to-rose-100 border-2 border-red-300 p-8 rounded-3xl shadow-xl mb-8">
                <div class="flex items-start gap-6">
                  <div class="p-4 bg-gradient-to-br from-red-500 to-rose-600 rounded-3xl shadow-xl">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                  </div>
                  <div class="flex-1">
                    <h4 class="text-xl font-black text-red-800 mb-4">⚠️ Attention - Action irréversible</h4>
                    <div class="text-base font-medium text-red-700 space-y-3">
                      <p>La réaffectation d'un stage est une action <strong>définitive</strong> qui transfère la
                        responsabilité à un autre maître de stage.</p>
                      <p>Vous n'aurez plus la possibilité de modifier les informations de ce stage après cette action.
                      </p>
                      <p>Vous pourrez seulement le <strong>consulter en lecture seule</strong>.</p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Formulaire de réaffectation -->
              <form @submit.prevent="onReaffectationSubmit"
                class="bg-gradient-to-br from-rose-50 via-pink-50 to-rose-50 p-8 rounded-3xl border-2 border-rose-200 shadow-2xl">
                <div class="space-y-8">
                  <!-- Sélection du nouveau maître et date -->
                  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                      <label for="nouveau_maitre_stage_id" class="block text-lg font-black mb-3 text-slate-900">
                        Nouveau maître de stage
                        <span class="text-red-600 ml-1">*</span>
                      </label>
                      <select id="nouveau_maitre_stage_id" v-model="reaffectationForm.nouveau_maitre_stage_id" required
                        class="w-full border-2 border-rose-300 rounded-2xl px-4 py-4 text-base font-medium bg-rose-50 focus:border-rose-500 focus:ring-4 focus:ring-rose-200 transition-all duration-300">
                        <option value="">Sélectionner un maître de stage</option>
                        <option v-for="ms in maitresDeStage" :key="ms.id" :value="ms.id">
                          {{ ms.nom }} {{ ms.prenom }} ({{ ms.structure.sigle }})
                        </option>
                      </select>
                    </div>

                    <div>
                      <label for="date_reaffectation" class="block text-lg font-black mb-3 text-slate-900">
                        Date de réaffectation
                        <span class="text-red-600 ml-1">*</span>
                      </label>
                      <input id="date_reaffectation" v-model="reaffectationForm.date_reaffectation" type="date" required
                        class="w-full border-2 border-rose-300 rounded-2xl px-4 py-4 text-base font-medium bg-rose-50 focus:border-rose-500 focus:ring-4 focus:ring-rose-200 transition-all duration-300"
                        :min="today" />
                    </div>
                  </div>

                  <!-- Motif de la réaffectation -->
                  <div>
                    <label for="motif" class="block text-lg font-black mb-3 text-slate-900">
                      Motif de la réaffectation
                      <span class="text-red-600 ml-1">*</span>
                    </label>
                    <textarea id="motif" v-model="reaffectationForm.motif" rows="6" required
                      class="w-full border-2 border-rose-300 rounded-2xl px-4 py-4 text-base font-medium bg-rose-50 focus:border-rose-500 focus:ring-4 focus:ring-rose-200 transition-all duration-300 placeholder-slate-500 resize-none"
                      placeholder="Expliquez le motif de cette réaffectation..."></textarea>
                    <div class="mt-2 text-sm font-medium text-slate-600">
                      <svg class="w-4 h-4 inline mr-1 text-rose-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      Détaillez les raisons qui justifient cette réaffectation
                    </div>
                  </div>

                  <!-- Confirmation avec checkbox -->
                  <div class="bg-gradient-to-r from-red-50 to-rose-50 p-6 rounded-3xl border-2 border-red-200">
                    <div class="flex items-start gap-4">
                      <div class="flex items-center h-6 mt-1">
                        <input id="confirmation" v-model="reaffectationForm.confirmation" type="checkbox" required
                          class="h-5 w-5 text-rose-600 border-2 border-rose-400 rounded-lg focus:ring-rose-500 focus:ring-4" />
                      </div>
                      <div class="flex-1">
                        <label for="confirmation" class="text-lg font-black text-slate-900 cursor-pointer">
                          Je confirme la réaffectation de ce stage
                        </label>
                        <p class="text-base font-medium text-red-700 mt-2">
                          Je comprends que cette action est <strong>irréversible</strong> et que je n'aurai plus qu'un
                          accès
                          en lecture à ce stage après la réaffectation.
                        </p>
                      </div>
                    </div>
                  </div>

                  <!-- Boutons d'action -->
                  <div class="flex justify-end gap-4 pt-6 border-t-2 border-rose-200">
                    <button type="button" @click="activeTab = 'infos'"
                      class="px-8 py-4 bg-gradient-to-r from-gray-200 to-gray-300 text-gray-800 font-black text-base rounded-2xl shadow-xl hover:from-gray-300 hover:to-gray-400 transform hover:scale-105 transition-all duration-300 flex items-center">
                      <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12" />
                      </svg>
                      Annuler
                    </button>

                    <button type="submit"
                      class="px-8 py-4 bg-gradient-to-r from-red-600 to-rose-600 text-white font-black text-base rounded-2xl shadow-xl hover:from-red-700 hover:to-rose-700 transform hover:scale-105 transition-all duration-300 flex items-center">
                      <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                      </svg>
                      Réaffecter le stage
                    </button>
                  </div>
                </div>
              </form>

              <!-- Section informative -->
              <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-3xl border-2 border-blue-200">
                <h5 class="text-lg font-black text-blue-900 mb-3 flex items-center">
                  <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  Après la réaffectation
                </h5>
                <ul class="space-y-2 text-base font-medium text-blue-800">
                  <li class="flex items-start gap-2">
                    <span class="text-blue-600 font-bold">•</span>
                    Le nouveau maître de stage recevra une notification
                  </li>
                  <li class="flex items-start gap-2">
                    <span class="text-blue-600 font-bold">•</span>
                    Votre accès sera limité à la consultation
                  </li>
                  <li class="flex items-start gap-2">
                    <span class="text-blue-600 font-bold">•</span>
                    L'historique des actions sera conservé
                  </li>
                  <li class="flex items-start gap-2">
                    <span class="text-blue-600 font-bold">•</span>
                    Le stagiaire sera informé du changement
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <!-- Le contenu suivra ici -->
        </div>
      </div>
    </div>
  </MSLayout>

  <!-- Modal de confirmation de fin de stage -->
  <div v-if="showConfirmFinModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300">
      <div class="p-6">
        <!-- En-tête du modal -->
        <div class="flex items-center mb-4">
          <div class="flex-shrink-0">
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
              <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <h3 class="text-lg font-bold text-gray-900">Confirmer la fin du stage</h3>
            <p class="text-sm text-gray-500">Cette action est irréversible</p>
          </div>
        </div>

        <!-- Contenu du modal -->
        <div class="mb-6">
          <p class="text-gray-700 mb-4">
            Êtes-vous sûr de vouloir confirmer la fin de ce stage ?
          </p>
          <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
            <div class="flex">
              <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
              </div>
              <div class="ml-3">
                <p class="text-sm text-amber-700">
                  Une fois confirmée, cette action ne pourra pas être annulée. Le stage sera marqué comme terminé.
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Boutons d'action -->
        <div class="flex justify-end space-x-3">
          <button
            @click="showConfirmFinModal = false"
            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
          >
            Annuler
          </button>
          <button
            @click="confirmerFinStageDefinitif"
            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200 font-medium"
          >
            Confirmer la fin du stage
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Après la liste des membres à évaluer dans l'onglet 'Évaluation' -->
  <div v-if="modalEvalOuvert" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50">
    <div class="bg-white rounded-3xl p-8 max-w-2xl w-full shadow-3xl border-2 border-gray-200">
      <h3 class="text-2xl font-black mb-6 text-purple-900 flex items-center">
        <svg class="w-8 h-8 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
        </svg>
        Évaluer {{ membreAevaluer?.user?.prenom }} {{ membreAevaluer?.user?.nom }}
      </h3>
      <form @submit.prevent="soumettreEvaluationMembre">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div v-for="(label, key) in {
            ponctualite: 'Ponctualité',
            motivation: 'Motivation',
            capacite_apprendre: 'Capacité à apprendre',
            qualite_travail: 'Qualité du travail',
            rapidite_execution: 'Rapidité d\'exécution',
            jugement: 'Jugement',
            esprit_motivation: 'Esprit de motivation',
            esprit_collaboration: 'Esprit de collaboration',
            sens_responsabilite: 'Sens des responsabilités',
            communication: 'Communication'
          }" :key="key">
            <label class="block font-bold mb-2">{{ label }}</label>
            <input type="number" v-model="formEvalMembre[key]" min="1" max="2" class="w-full border rounded p-2" />
          </div>
        </div>
        <div class="mt-4">
          <label class="block font-bold mb-2">Commentaire général</label>
          <textarea v-model="formEvalMembre.commentaire_general" rows="3" class="w-full border rounded p-2"></textarea>
        </div>
        <div class="flex justify-end gap-4 mt-6">
          <button type="button" @click="fermerModalEval" class="px-6 py-3 bg-gray-200 rounded-2xl font-bold">Annuler</button>
          <button type="submit" class="px-6 py-3 bg-purple-600 text-white rounded-2xl font-bold">Valider</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import MSLayout from '@/Layouts/MSLayout.vue';
import AdminToast from '@/Components/AdminToast.vue';
import StageTimeline from '@/Components/StageTimeline.vue';
import { ref, computed, onMounted, watch } from 'vue';
import axios from 'axios'; // Ajout si pas déjà présent

const props = defineProps({
  stage: {
    type: Object,
    required: true
  }
});

const error = ref(null)
const success = ref(null)
const activeTab = ref('infos')
const toast = ref(null)
const showConfirmFinModal = ref(false)

// Variables pour la gestion des thèmes
const themesProposes = ref([])
const loadingThemes = ref(false)
const errorThemes = ref(null)
const motifRefus = ref('')
const themeEnCours = ref(null)

// Fonctions pour la gestion des thèmes
const fetchThemesProposes = async () => {
  loadingThemes.value = true
  errorThemes.value = null
  try {
    const response = await axios.get(`/agent/ms/stages/${props.stage.id}/themes-proposes`)
    themesProposes.value = response.data.themes
  } catch (error) {
    errorThemes.value = "Erreur lors du chargement des propositions de thèmes"
    console.error(error)
  } finally {
    loadingThemes.value = false
  }
}

const validerTheme = async (theme) => {
  if (!confirm('Êtes-vous sûr de vouloir valider ce thème ?')) return

  try {
    await axios.post(`/agent/ms/stages/${props.stage.id}/themes/${theme.id}/action`, {
      action: 'valider'
    })
    await fetchThemesProposes()
    // Rafraîchir les données du stage
    await fetchStage()
  } catch (error) {
    console.error(error)
    alert('Erreur lors de la validation du thème')
  }
}

const refuserTheme = (theme) => {
  themeEnCours.value = theme
  motifRefus.value = ''
}

const confirmerRefus = async () => {
  if (!motifRefus.value.trim()) {
    alert('Veuillez indiquer un motif de refus')
    return
  }

  try {
    await axios.post(`/agent/ms/stages/${props.stage.id}/themes/${themeEnCours.value.id}/action`, {
      action: 'refuser',
      motif_refus: motifRefus.value
    })
    await fetchThemesProposes()
    themeEnCours.value = null
    motifRefus.value = ''
  } catch (error) {
    console.error(error)
    alert('Erreur lors du refus du thème')
  }
}

const annulerRefus = () => {
  themeEnCours.value = null
  motifRefus.value = ''
}

// Charger les thèmes au montage du composant
onMounted(() => {
  fetchThemesProposes()
})

// Ajout : liste dynamique des maîtres de stage
const maitresDeStage = ref([]);
const loadingMaitres = ref(false);
const errorMaitres = ref('');

const loadMaitresStage = async () => {
  loadingMaitres.value = true;
  errorMaitres.value = '';
  maitresDeStage.value = [];
  try {
    const response = await axios.get(route('agent.ms.stages.maitres-stage-substructures', props.stage.id));
    if (response.data.success) {
      maitresDeStage.value = response.data.maitresStage;
    } else {
      errorMaitres.value = response.data.message || 'Erreur lors du chargement des maîtres de stage.';
    }
  } catch (error) {
    errorMaitres.value = error.response?.data?.message || 'Erreur lors du chargement des maîtres de stage.';
  } finally {
    loadingMaitres.value = false;
  }
};

// Charger la liste quand on passe sur l'onglet réaffecter
watch(activeTab, (newTab) => {
  if (newTab === 'reaffecter' && !props.stage.est_reaffecte) {
    loadMaitresStage();
  }
});

// Onglets disponibles
const tabs = [
  {
    id: 'infos',
    label: 'Informations générales',
    icon: 'IconInfo',
    disabled: false
  },
  {
    id: 'theme',
    label: 'Thème du stage',
    icon: 'IconDocument',
    disabled: false
  },
  {
    id: 'evaluation',
    label: 'Évaluation',
    icon: 'IconEdit',
    disabled: false
  },
  {
    id: 'contacter',
    label: 'Contacter le stagiaire',
    icon: 'IconMail',
    disabled: false
  },
  {
    id: 'reaffecter',
    label: 'Réaffecter le stage',
    icon: 'IconUserSwitch',
    disabled: false
  }
];

// Gestion des formulaires
const themeForm = ref({
  titre: props.stage.themeStage?.intitule || '',
  description: props.stage.themeStage?.description || '',
  etat: props.stage.themeStage?.etat || 'Proposé',
  commentaire: props.stage.themeStage?.commentaire || ''
});

const evaluationForm = ref({
  ponctualite: 1,
  motivation: 1,
  capacite_apprendre: 1,
  qualite_travail: 1,
  rapidite_execution: 1,
  jugement: 1,
  esprit_motivation: 1,
  esprit_collaboration: 1,
  sens_responsabilite: 1,
  communication: 1,
  commentaire_general: ''
});

const contactForm = ref({
  subject: '',
  message: ''
});

const reaffectationForm = ref({
  nouveau_maitre_stage_id: '', // nom attendu par le backend
  date_reaffectation: new Date().toISOString().split('T')[0],
  motif: '',
  confirmation: false
});

// Date du jour pour le formulaire de réaffectation
const today = computed(() => {
  return new Date().toISOString().split('T')[0];
});

// Fonction pour obtenir le nom du stagiaire
const getStagiaireName = (stage) => {
  if (stage.stagiaire_info?.nom) {
    return `${stage.stagiaire_info.prenom} ${stage.stagiaire_info.nom}`;
  } else if (stage.demandeStage?.stagiaire?.user?.nom) {
    return `${stage.demandeStage.stagiaire.user.prenom} ${stage.demandeStage.stagiaire.user.nom}`;
  }
  return 'Stagiaire';
};

// Fonction pour obtenir les initiales du stagiaire
const getStagiaireInitials = () => {
  if (props.stage.stagiaire_info?.nom) {
    return (props.stage.stagiaire_info.nom.charAt(0) + (props.stage.stagiaire_info.prenom?.charAt(0) || '')).toUpperCase();
  } else if (props.stage.demandeStage?.stagiaire?.user?.nom) {
    return (props.stage.demandeStage.stagiaire.user.nom.charAt(0) + (props.stage.demandeStage.stagiaire.user.prenom?.charAt(0) || '')).toUpperCase();
  }
  return 'ST';
};

// Fonction pour formater les dates
const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  const date = new Date(dateString);
  return date.toLocaleDateString('fr-FR');
};

// Fonction pour calculer la durée d'un stage
const calculateDuration = (dateDebut, dateFin) => {
  if (!dateDebut || !dateFin) return 'Durée indéterminée';

  const debut = new Date(dateDebut);
  const fin = new Date(dateFin);

  // Calculer la différence en jours
  const differenceMs = fin - debut;
  const differenceDays = Math.ceil(differenceMs / (1000 * 60 * 60 * 24));

  if (differenceDays <= 0) return 'Erreur de date';

  // Convertir en semaines/mois/jours
  if (differenceDays < 7) {
    return `${differenceDays} jour${differenceDays > 1 ? 's' : ''}`;
  } else if (differenceDays < 31) {
    const weeks = Math.floor(differenceDays / 7);
    const remainingDays = differenceDays % 7;
    return `${weeks} semaine${weeks > 1 ? 's' : ''}${remainingDays > 0 ? ` et ${remainingDays} jour${remainingDays > 1 ? 's' : ''}` : ''}`;
  } else {
    const months = Math.floor(differenceDays / 30);
    const remainingDays = differenceDays % 30;
    return `${months} mois${remainingDays > 0 ? ` et ${remainingDays} jour${remainingDays > 1 ? 's' : ''}` : ''}`;
  }
};

// Fonction pour obtenir les événements du stage pour la timeline
const getStageEvents = () => {
  const events = [
    {
      date: props.stage.date_debut,
      title: 'Début du stage',
      description: `Début du stage de ${getStagiaireName(props.stage)}`,
      status: 'done'
    },
    {
      date: props.stage.date_fin,
      title: 'Fin prévue',
      description: 'Fin prévue du stage',
      status: props.stage.statut === 'Terminé' ? 'done' : 'pending'
    }
  ];

  // Ajouter l'événement de réaffectation si applicable
  if (props.stage.est_reaffecte && props.stage.reaffectation_info) {
    events.push({
      date: props.stage.reaffectation_info.date_reaffectation,
      title: 'Réaffectation',
      description: `Stage réaffecté à ${props.stage.reaffectation_info.nouveau_ms_prenom} ${props.stage.reaffectation_info.nouveau_ms_nom}`,
      status: 'done'
    });
  }

  // Ajouter l'événement d'évaluation si applicable
  if (props.stage.evaluation?.date_evaluation) {
    events.push({
      date: props.stage.evaluation.date_evaluation,
      title: 'Évaluation',
      description: 'Évaluation du stagiaire complétée',
      status: 'done'
    });
  }

  // Ajouter les événements de thème si applicable
  if (props.stage.themeStage?.created_at) {
    events.push({
      date: props.stage.themeStage.created_at,
      title: 'Proposition de thème',
      description: `Thème "${props.stage.themeStage.intitule}" proposé`,
      status: 'done'
    });
  }

  if (props.stage.themeStage?.updated_at && props.stage.themeStage.etat === 'Validé') {
    events.push({
      date: props.stage.themeStage.updated_at,
      title: 'Validation du thème',
      description: `Thème "${props.stage.themeStage.intitule}" validé`,
      status: 'done'
    });
  }

  return events.sort((a, b) => new Date(a.date) - new Date(b.date));
};

// Fonction pour générer un aperçu des mots-clés
const generatePreviewMotsCles = () => {
  const motsVides = ['le', 'la', 'les', 'un', 'une', 'des', 'du', 'de', 'et', 'ou', 'avec', 'pour', 'dans', 'sur', 'par', 'à', 'au', 'aux'];

  // Combiner titre et description
  const texte = (themeForm.value.titre + ' ' + themeForm.value.description).toLowerCase();

  // Nettoyer et extraire les mots
  const mots = texte.split(/[^a-zA-ZÀ-ÿ0-9]+/)
    .filter(mot => mot.length > 2 && !motsVides.includes(mot.toLowerCase()))
    .slice(0, 8); // Limiter à 8 mots-clés pour l'aperçu

  return [...new Set(mots)]; // Supprimer les doublons
};

// Soumission du formulaire de thème
const onThemeSubmit = () => {
  router.post(route('agent.ms.stages.theme.store', props.stage.id), themeForm.value, {
    onSuccess: () => {
      router.visit(route('agent.ms.stages'), {
        only: ['success'],
        preserveState: false,
        preserveScroll: true,
      });
    },
    onError: (errors) => {
      if (toast.value) {
        toast.value.show({
          type: 'error',
          message: 'Erreur lors de l\'enregistrement du thème'
        });
      }
      console.log(errors); // Pour voir le détail dans la console
    }
  });
};

// Fonction pour éditer l'évaluation
const editEvaluation = () => {
  if (stage.evaluation) {
    evaluationForm.value = {
      ponctualite: stage.evaluation.ponctualite || 1,
      motivation: stage.evaluation.motivation || 1,
      capacite_apprendre: stage.evaluation.capacite_apprendre || 1,
      qualite_travail: stage.evaluation.qualite_travail || 1,
      rapidite_execution: stage.evaluation.rapidite_execution || 1,
      jugement: stage.evaluation.jugement || 1,
      esprit_motivation: stage.evaluation.esprit_motivation || 1,
      esprit_collaboration: stage.evaluation.esprit_collaboration || 1,
      sens_responsabilite: stage.evaluation.sens_responsabilite || 1,
      communication: stage.evaluation.communication || 1,
      commentaire_general: stage.evaluation.commentaire_general || ''
    };
  }
};

const getTotal = () => {
  return parseFloat((
    parseFloat(evaluationForm.value.ponctualite) +
    parseFloat(evaluationForm.value.motivation) +
    parseFloat(evaluationForm.value.capacite_apprendre) +
    parseFloat(evaluationForm.value.qualite_travail) +
    parseFloat(evaluationForm.value.rapidite_execution) +
    parseFloat(evaluationForm.value.jugement) +
    parseFloat(evaluationForm.value.esprit_motivation) +
    parseFloat(evaluationForm.value.esprit_collaboration) +
    parseFloat(evaluationForm.value.sens_responsabilite) +
    parseFloat(evaluationForm.value.communication)
  ).toFixed(1));
};

// Soumission du formulaire d'évaluation
const evaluationErrors = ref({});

const onEvaluationSubmit = () => {
  evaluationErrors.value = {};
  router.post(route('agent.ms.stages.noter', props.stage.id), {
    ponctualite: evaluationForm.value.ponctualite,
    motivation: evaluationForm.value.motivation,
    capacite_apprendre: evaluationForm.value.capacite_apprendre,
    qualite_travail: evaluationForm.value.qualite_travail,
    rapidite_execution: evaluationForm.value.rapidite_execution,
    jugement: evaluationForm.value.jugement,
    esprit_motivation: evaluationForm.value.esprit_motivation,
    esprit_collaboration: evaluationForm.value.esprit_collaboration,
    sens_responsabilite: evaluationForm.value.sens_responsabilite,
    communication: evaluationForm.value.communication,
    note_totale: getTotal(),
    commentaire_general: evaluationForm.value.commentaire_general
  }, {
    onSuccess: () => {
      activeTab.value = 'infos';
      if (toast.value) {
        toast.value.show({
          type: 'success',
          message: 'Évaluation enregistrée avec succès'
        });
      }
    },
    onError: (errors) => {
      evaluationErrors.value = errors;
      if (toast.value) {
        toast.value.show({
          type: 'error',
          message: 'Erreur lors de l\'enregistrement de l\'évaluation'
        });
      }
      console.log(errors);
    }
  });
};

// Soumission du formulaire de contact
const onContactSubmit = async () => {
  // Vérifier que les champs requis sont remplis
  if (!contactForm.value.subject || !contactForm.value.message) {
    if (toast.value) {
      toast.value.show({
        type: 'error',
        message: 'Veuillez remplir tous les champs obligatoires'
      });
    }
    return;
  }

  // Obtenir l'email du stagiaire
  const stagiaireEmail = props.stage.stagiaire_info?.email || props.stage.demandeStage?.stagiaire?.user?.email;

  if (!stagiaireEmail) {
    if (toast.value) {
      toast.value.show({
        type: 'error',
        message: 'Aucune adresse email disponible pour ce stagiaire'
      });
    }
    return;
  }

  try {
    // Appel API pour envoyer le mail (adapte la route si besoin)
    const response = await axios.post(route('agent.ms.stages.send-message'), {
      stage_id: props.stage.id,
      subject: contactForm.value.subject,
      message: contactForm.value.message
    });

    if (response.data.success) {
      if (toast.value) {
        toast.value.show({
          type: 'success',
          message: `Message envoyé à ${stagiaireEmail} avec succès !`
        });
      }
      // Réinitialiser le formulaire et revenir à l'onglet infos
      contactForm.value = { subject: '', message: '' };
      activeTab.value = 'infos';
    } else {
      throw new Error(response.data.message || 'Erreur lors de l\'envoi du message.');
    }
  } catch (error) {
    if (toast.value) {
      toast.value.show({
        type: 'error',
        message: error.response?.data?.message || error.message || 'Erreur lors de l\'envoi du message.'
      });
    }
  }
};

// Soumission du formulaire de réaffectation
const onReaffectationSubmit = () => {
  router.post(route('agent.ms.stages.reaffecter', props.stage.id), reaffectationForm.value, {
    onSuccess: () => {
      activeTab.value = 'infos';
      if (toast.value) {
        toast.value.show({
          type: 'success',
          message: 'Stage réaffecté avec succès'
        });
      }
    },
    onError: (errors) => {
      if (toast.value) {
        toast.value.show({
          type: 'error',
          message: 'Erreur lors de la réaffectation du stage'
        });
      }
      console.error(errors);
    }
  });
};

// Composants d'icônes pour les onglets
const IconInfo = {
  template: `
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
  `
};

const IconDocument = {
  template: `
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
    </svg>
  `
};

const IconEdit = {
  template: `
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
    </svg>
  `
};

const IconMail = {
  template: `
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
    </svg>
  `
};

const IconUserSwitch = {
  template: `
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
    </svg>
  `
};

// Afficher message de succès/erreur au chargement si présent
onMounted(() => {
  if (props.success && toast.value) {
    toast.value.show({
      type: 'success',
      message: props.success
    });
  }

  if (props.error && toast.value) {
    toast.value.show({
      type: 'error',
      message: props.error
    });
  }
});

const page = usePage();

watch(
  () => page.props.success,
  (newSuccess) => {
    if (newSuccess && toast.value) {
      toast.value.show({
        type: 'success',
        message: newSuccess
      });
    }
  }
);

watch(
  () => page.props.error,
  (newError) => {
    if (newError && toast.value) {
      toast.value.show({
        type: 'error',
        message: newError
      });
    }
  }
);

const showThemeEditForm = ref(false);

function onCancelThemeEdit() {
  showThemeEditForm.value = false;
}

// Synchroniser le formulaire avec le thème existant à chaque changement
watch(
  () => props.stage.themeStage,
  (theme) => {
    themeForm.value = {
      titre: theme?.intitule || '',
      description: theme?.description || '',
      etat: theme?.etat || 'Proposé',
      commentaire: theme?.commentaire || ''
    };
  },
  { immediate: true }
);

console.log('themeStage reçu:', props.stage.themeStage)

// Formulaire de proposition de thème par le MS
const themeMSForm = ref({
  intitule: '',
  description: '',
  mots_cles: ''
});

const themeMSLoading = ref(false);
const themeMSSuccess = ref('');
const themeMSError = ref('');

const proposerThemeMS = async () => {
  themeMSLoading.value = true;
  themeMSSuccess.value = '';
  themeMSError.value = '';

  try {
    const response = await axios.post(`/agent/ms/stages/${props.stage.id}/themes`, themeMSForm.value);
    if (response.data.success) {
      themeMSSuccess.value = 'Thème proposé avec succès !';
      themeMSForm.value = { intitule: '', description: '', mots_cles: '' };
    } else {
      themeMSError.value = response.data.message || 'Erreur lors de la soumission du thème.';
    }
  } catch (error) {
    themeMSError.value = error.response?.data?.message || error.message || 'Erreur lors de la soumission du thème.';
  } finally {
    themeMSLoading.value = false;
  }
};

// Ajouter la fonction pour consulter l'évaluation d'un membre
const membreSelectionne = ref(null);
const evaluationMembre = ref(null);

const consulterEvaluation = async (membre) => {
  membreSelectionne.value = membre;
  try {
    const response = await axios.get(`/agent/ms/stages/${props.stage.id}/evaluations/${membre.id}`);
    evaluationMembre.value = response.data.evaluation;
  } catch (error) {
    console.error('Erreur lors de la récupération de l\'évaluation:', error);
    evaluationMembre.value = null;
  }
};

const fermerModalEvaluation = () => {
  membreSelectionne.value = null;
  evaluationMembre.value = null;
};

const modalEvalOuvert = ref(false);
const membreAevaluer = ref(null);
const formEvalMembre = ref({
  ponctualite: 1,
  motivation: 1,
  capacite_apprendre: 1,
  qualite_travail: 1,
  rapidite_execution: 1,
  jugement: 1,
  esprit_motivation: 1,
  esprit_collaboration: 1,
  sens_responsabilite: 1,
  communication: 1,
  commentaire_general: ''
});

const ouvrirModalEvaluation = (membre) => {
  console.log('Évaluation pour:', membre.user.nom, membre.statutStage);
  modalEvalOuvert.value = true;
  console.log('modalEvalOuvert:', modalEvalOuvert.value);
  membreAevaluer.value = membre;
  formEvalMembre.value = {
    ponctualite: 1,
    motivation: 1,
    capacite_apprendre: 1,
    qualite_travail: 1,
    rapidite_execution: 1,
    jugement: 1,
    esprit_motivation: 1,
    esprit_collaboration: 1,
    sens_responsabilite: 1,
    communication: 1,
    commentaire_general: ''
  };
};

const fermerModalEval = () => {
  modalEvalOuvert.value = false;
  membreAevaluer.value = null;
};

const getTotalEvalMembre = () => {
  return parseFloat((
    parseFloat(formEvalMembre.value.ponctualite) +
    parseFloat(formEvalMembre.value.motivation) +
    parseFloat(formEvalMembre.value.capacite_apprendre) +
    parseFloat(formEvalMembre.value.qualite_travail) +
    parseFloat(formEvalMembre.value.rapidite_execution) +
    parseFloat(formEvalMembre.value.jugement) +
    parseFloat(formEvalMembre.value.esprit_motivation) +
    parseFloat(formEvalMembre.value.esprit_collaboration) +
    parseFloat(formEvalMembre.value.sens_responsabilite) +
    parseFloat(formEvalMembre.value.communication)
  ).toFixed(1));
};

const soumettreEvaluationMembre = async () => {
  try {
    // Utiliser le stage_id du membre à évaluer (important pour les groupes)
    const response = await axios.post(`/agent/ms/stages/${membreAevaluer.value.stage_id}/noter`, {
      ...formEvalMembre.value,
      membre_id: membreAevaluer.value.user.id,
      note_totale: getTotalEvalMembre(),
    });
    if (response.data.success) {
      if (toast.value && typeof toast.value.show === 'function') toast.value.show({ type: 'success', message: response.data.message });
      modalEvalOuvert.value = false;
      // Rafraîchir la liste ou autre action si besoin
    } else {
      if (toast.value && typeof toast.value.show === 'function') toast.value.show({ type: 'error', message: response.data.message });
    }
  } catch (error) {
    let msg = error.response?.data?.message || error.message || 'Erreur lors de la soumission de l\'évaluation';
    if (toast.value && typeof toast.value.show === 'function') toast.value.show({ type: 'error', message: msg });
  }
};

// Calculer la liste des membres à évaluer (principal + membres)
const membresEvaluation = computed(() => {
  const membres = [];
  // Ajouter le stagiaire principal
  if (props.stage.demandeStage && props.stage.demandeStage.stagiaire && props.stage.demandeStage.stagiaire.user) {
    membres.push({
      id: 'principal',
      user: props.stage.demandeStage.stagiaire.user,
      evaluationMembre: props.stage.evaluation || null,
      statutStage: props.stage.statut,
      stage_id: props.stage.id // Correction : on ajoute le stage_id du principal
    });
  }
  // Ajouter les membres du groupe (hors principal)
  if (props.stage.demandeStage && props.stage.demandeStage.membres) {
    props.stage.demandeStage.membres.forEach(membre => {
      if (!membres.find(m => m.user.id === membre.user.id)) {
        membres.push({
          id: membre.id,
          user: membre.user,
          evaluationMembre: membre.evaluationMembre || null,
          statutStage: membre.statutStage || 'En cours',
          stage_id: membre.stage_id // Correction : on ajoute le stage_id du membre (injecté par le backend)
        });
      }
    });
  }
  return membres;
});

const confirmerFinStage = () => {
  showConfirmFinModal.value = true;
};

const confirmerFinStageDefinitif = async () => {
  showConfirmFinModal.value = false;
  try {
    const response = await axios.post(`/agent/ms/stages/${props.stage.id}/confirmer-fin`);
    if (response.data.success) {
      if (toast.value) toast.value.show({ type: 'success', message: response.data.message });
      // Recharge la page ou les données du stage
      window.location.reload();
    }
  } catch (error) {
    if (toast.value) toast.value.show({ type: 'error', message: error.response?.data?.message || 'Erreur' });
  }
};
</script>