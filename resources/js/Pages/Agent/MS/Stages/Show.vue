<template>
  <Head :title="`Stage de ${getStagiaireName(stage)}`" />

  <MSLayout>
    <!-- Composant Toast pour les notifications -->
    <AdminToast ref="toast" />
    
    <template #header>
      <div class="flex items-center gap-4 mb-2">
        <div class="bg-gradient-to-r from-blue-500 to-blue-700 text-white rounded-lg w-12 h-12 flex items-center justify-center shadow-md">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
        </div>
        <div>
          <h1 class="text-2xl font-bold text-gray-800 leading-tight md:text-3xl">Détails du Stage</h1>
          <p class="text-sm text-gray-500 mt-1">{{ getStagiaireName(stage) }}</p>
        </div>
      </div>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Messages d'état -->
        <div v-if="error" class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-md shadow-sm">
          <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="font-semibold">{{ error }}</p>
          </div>
        </div>

        <div v-if="success" class="mb-6 bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 rounded-md shadow-sm">
          <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <p class="font-semibold">{{ success }}</p>
          </div>
        </div>

        <!-- Navigation et badges d'état -->
        <div class="mb-6 flex flex-wrap justify-between items-center gap-4">
          <Link 
            :href="route('agent.ms.stages')" 
            class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-medium text-sm text-gray-700 shadow-sm hover:bg-gray-50 transition-colors duration-200"
          >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour à la liste
          </Link>

          <!-- Badge de réaffectation -->
          <div v-if="stage.est_reaffecte" class="bg-amber-50 text-amber-800 px-4 py-2 rounded-md border border-amber-200 flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Stage réaffecté - Consultation uniquement</span>
          </div>
        </div>

        <!-- Informations de réaffectation -->
        <div v-if="stage.est_reaffecte && stage.reaffectation_info" class="mb-6 bg-amber-50 p-5 rounded-lg border border-amber-200 shadow-sm">
          <h3 class="font-semibold text-amber-800 mb-3 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
            </svg>
            Informations de réaffectation
          </h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <p class="text-sm"><span class="font-medium text-amber-900">Réaffecté à :</span> {{ stage.reaffectation_info.nouveau_ms_prenom }} {{ stage.reaffectation_info.nouveau_ms_nom }}</p>
              <p class="text-sm"><span class="font-medium text-amber-900">Structure :</span> {{ stage.reaffectation_info.structure_libelle }} {{ stage.reaffectation_info.structure_sigle ? `(${stage.reaffectation_info.structure_sigle})` : '' }}</p>
            </div>
            <div>
              <p class="text-sm"><span class="font-medium text-amber-900">Date de réaffectation :</span> {{ formatDate(stage.reaffectation_info.date_reaffectation) }}</p>
              <p class="text-sm" v-if="stage.reaffectation_info.motif"><span class="font-medium text-amber-900">Motif :</span> {{ stage.reaffectation_info.motif }}</p>
            </div>
          </div>
        </div>

        <!-- Onglets -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
          <div class="border-b border-gray-200">
            <nav class="flex overflow-x-auto" aria-label="Tabs">
              <button
                v-for="(tab, index) in tabs"
                :key="index"
                @click="activeTab = tab.id"
                :disabled="tab.disabled && stage.est_reaffecte"
                :class="[
                  'whitespace-nowrap py-4 px-6 text-center border-b-2 font-medium text-sm leading-5 focus:outline-none transition duration-150 ease-in-out',
                  activeTab === tab.id
                    ? 'border-blue-500 text-blue-600'
                    : tab.disabled && stage.est_reaffecte
                      ? 'border-transparent text-gray-400 cursor-not-allowed'
                      : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                ]"
              >
                <div class="flex items-center">
                  <component :is="tab.icon" class="w-4 h-4 mr-2" />
                  {{ tab.label }}
                </div>
              </button>
            </nav>
          </div>

          <!-- Contenu des onglets -->
          <div class="p-6">
            <!-- Informations générales -->
            <div v-if="activeTab === 'infos'" class="space-y-6">
              <!-- Timeline du stage -->
              <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <h3 class="text-lg font-semibold mb-4 flex items-center">
                  <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                  </svg>
                  Progression du stage
                </h3>
                <StageTimeline
                  :start-date="stage.date_debut"
                  :end-date="stage.date_fin"
                  :events="getStageEvents()"
                />
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <h3 class="text-lg font-semibold mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Informations du stagiaire
                  </h3>
                  <div class="bg-gray-50 p-5 rounded-lg border border-gray-100 shadow-sm">
                    <div class="flex items-center mb-4">
                      <div class="bg-blue-100 text-blue-700 rounded-full w-12 h-12 flex items-center justify-center text-xl font-bold shadow mr-4">
                        {{ getStagiaireInitials() }}
                      </div>
                      <div>
                        <h4 class="text-base font-semibold">{{ getStagiaireName(stage) }}</h4>
                        <p class="text-sm text-gray-500">
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
                    <div class="space-y-2">
                      <div v-if="stage.stagiaire_info?.telephone || stage.demandeStage?.stagiaire?.user?.telephone" class="flex items-center space-x-2 text-sm">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <span>
                          {{ stage.stagiaire_info?.telephone || stage.demandeStage?.stagiaire?.user?.telephone }}
                        </span>
                      </div>
                      <div v-if="stage.stagiaire_info?.adresse || stage.demandeStage?.stagiaire?.user?.adresse" class="flex items-start space-x-2 text-sm">
                        <svg class="w-4 h-4 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>
                          {{ stage.stagiaire_info?.adresse || stage.demandeStage?.stagiaire?.user?.adresse }}
                        </span>
                      </div>
                      <div class="flex items-center space-x-2 text-sm">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                        <span>
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

                <div>
                  <h3 class="text-lg font-semibold mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Structure d'accueil
                  </h3>
                  <div class="bg-gray-50 p-5 rounded-lg border border-gray-100 shadow-sm">
                    <h4 class="text-base font-semibold mb-2">{{ stage.structure?.libelle }}</h4>
                    <div class="space-y-2">
                      <div class="flex items-center space-x-2 text-sm">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        <span>{{ stage.structure?.sigle || 'Sigle non disponible' }}</span>
                      </div>

                      <div v-if="stage.structure?.adresse" class="flex items-start space-x-2 text-sm">
                        <svg class="w-4 h-4 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>{{ stage.structure.adresse }}</span>
                      </div>

                      <div v-if="stage.structure?.telephone" class="flex items-center space-x-2 text-sm">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <span>{{ stage.structure.telephone }}</span>
                      </div>

                      <div v-if="stage.structure?.email" class="flex items-center space-x-2 text-sm">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                        <span>{{ stage.structure.email }}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <h3 class="text-lg font-semibold mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Période du stage
                  </h3>
                  <div class="bg-gray-50 p-5 rounded-lg border border-gray-100 shadow-sm">
                    <div class="space-y-3">
                      <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-500">Date de début</span>
                        <span class="text-base font-semibold text-gray-800">{{ formatDate(stage.date_debut) }}</span>
                      </div>
                      <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-500">Date de fin</span>
                        <span class="text-base font-semibold text-gray-800">{{ formatDate(stage.date_fin) }}</span>
                      </div>
                      <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-500">Durée</span>
                        <span class="text-base font-semibold text-gray-800">{{ calculateDuration(stage.date_debut, stage.date_fin) }}</span>
                      </div>
                      <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-500">Statut</span>
                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                        :class="{
                            'bg-emerald-100 text-emerald-800 border border-emerald-200': stage.statut === 'Terminé',
                            'bg-blue-100 text-blue-800 border border-blue-200': stage.statut === 'En cours',
                            'bg-amber-100 text-amber-800 border border-amber-200': stage.statut === 'En attente',
                        }">
                        {{ stage.statut }}
                      </span>
                      </div>
                    </div>
                  </div>
                </div>

                <div>
                  <h3 class="text-lg font-semibold mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Détails du thème
                  </h3>
                  <div class="bg-gray-50 p-5 rounded-lg border border-gray-100 shadow-sm">
                    <div v-if="stage.themeStage" class="space-y-3">
                      <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-500">Titre</span>
                        <span class="text-base font-semibold text-gray-800">{{ stage.themeStage.titre }}</span>
                      </div>
                      <div class="flex flex-col">
                        <span class="text-sm font-medium text-gray-500">Description</span>
                        <p class="text-sm text-gray-700 mt-1">{{ stage.themeStage.description }}</p>
                      </div>
                      <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-500">État</span>
                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                          :class="{
                            'bg-emerald-100 text-emerald-800 border border-emerald-200': stage.themeStage.etat === 'Validé',
                            'bg-amber-100 text-amber-800 border border-amber-200': stage.themeStage.etat === 'Proposé',
                            'bg-red-100 text-red-800 border border-red-200': stage.themeStage.etat === 'Refusé',
                            'bg-blue-100 text-blue-800 border border-blue-200': stage.themeStage.etat === 'Modifié',
                          }">
                          {{ stage.themeStage.etat }}
                        </span>
                      </div>
                    </div>
                    <div v-else class="py-6 text-center">
                      <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                      </svg>
                      <p class="text-sm text-gray-500">Aucun thème défini pour ce stage</p>
                      <div v-if="!stage.est_reaffecte" class="mt-4">
                        <button
                          @click="activeTab = 'theme'"
                          class="inline-flex items-center px-3 py-2 border border-blue-300 shadow-sm text-sm leading-4 font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                          </svg>
                          Définir un thème
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Thème du stage -->
            <div v-if="activeTab === 'theme'" class="space-y-6">
              <div v-if="stage.est_reaffecte" class="bg-amber-50 p-4 rounded-md border border-amber-200 mb-4">
                <p class="text-amber-700 text-sm">Ce stage a été réaffecté. Vous ne pouvez pas modifier le thème.</p>
              </div>

              <div v-else>
                <h3 class="text-lg font-semibold mb-4 flex items-center">
                  <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                  {{ stage.themeStage ? 'Modification du thème' : 'Définition du thème' }}
                </h3>

                <form @submit.prevent="onThemeSubmit" class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                  <div class="grid grid-cols-1 gap-6">
                    <!-- Titre du thème -->
                    <div>
                      <label for="titre" class="block text-sm font-medium text-gray-700 mb-1">
                        Titre du thème <span class="text-red-500">*</span>
                      </label>
                      <input
                        id="titre"
                        v-model="themeForm.titre"
                        type="text"
                        required
                        maxlength="255"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Ex: Développement d'une application web de gestion"
                      />
                      <p class="mt-1 text-sm text-gray-500">{{ themeForm.titre.length }}/255 caractères</p>
                    </div>

                    <!-- Description détaillée -->
                    <div>
                      <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                        Description détaillée <span class="text-red-500">*</span>
                      </label>
                      <textarea
                        id="description"
                        v-model="themeForm.description"
                        rows="6"
                        required
                        maxlength="2000"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Décrivez en détail les objectifs, les tâches à accomplir, les technologies utilisées, les livrables attendus..."
                      ></textarea>
                      <p class="mt-1 text-sm text-gray-500">{{ themeForm.description.length }}/2000 caractères</p>
                    </div>

                    <!-- Suggestions d'amélioration -->
                    <div v-if="stage.themeStage && stage.themeStage.etat === 'Refusé'" class="bg-red-50 p-4 rounded-md border border-red-200">
                      <h4 class="text-sm font-medium text-red-800 mb-2">Motif de refus précédent :</h4>
                      <p class="text-sm text-red-700">{{ stage.themeStage.motif_refus || 'Aucun motif spécifié' }}</p>
                    </div>

                    <!-- État du thème -->
                    <div>
                      <label for="etat" class="block text-sm font-medium text-gray-700 mb-1">État du thème</label>
                      <select
                        id="etat"
                        v-model="themeForm.etat"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                      >
                        <option value="Proposé">Proposé (en attente de validation)</option>
                        <option value="Modifié">Modifié (suite à des commentaires)</option>
                        <option value="Validé">Validé (approuvé définitivement)</option>
                      </select>
                      <p class="mt-1 text-sm text-gray-500">
                        <span v-if="themeForm.etat === 'Proposé'">Le thème sera soumis pour validation</span>
                        <span v-else-if="themeForm.etat === 'Modifié'">Le thème a été modifié suite à des retours</span>
                        <span v-else-if="themeForm.etat === 'Validé'">Le thème est définitivement approuvé</span>
                      </p>
                    </div>

                    <!-- Commentaire optionnel -->
                    <div>
                      <label for="commentaire" class="block text-sm font-medium text-gray-700 mb-1">
                        Notes et commentaires
                      </label>
                      <textarea
                        id="commentaire"
                        v-model="themeForm.commentaire"
                        rows="3"
                        maxlength="1000"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Ajoutez des notes sur le contexte, les contraintes, les ressources disponibles..."
                      ></textarea>
                      <p class="mt-1 text-sm text-gray-500">{{ (themeForm.commentaire || '').length }}/1000 caractères</p>
                    </div>

                    <!-- Aperçu des mots-clés générés automatiquement -->
                    <div v-if="themeForm.titre || themeForm.description" class="bg-blue-50 p-4 rounded-md border border-blue-200">
                      <h4 class="text-sm font-medium text-blue-800 mb-2">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        Mots-clés qui seront générés automatiquement :
                      </h4>
                      <div class="flex flex-wrap gap-2">
                        <span v-for="motCle in generatePreviewMotsCles()" :key="motCle"
                          class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs">
                          {{ motCle }}
                        </span>
                      </div>
                      <p class="text-xs text-blue-600 mt-2">Ces mots-clés faciliteront la recherche et la catégorisation du thème</p>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                      <button
                        type="button"
                        @click="activeTab = 'infos'"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                      >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Annuler
                      </button>
                      <button
                        type="submit"
                        :disabled="!themeForm.titre || !themeForm.description"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                      >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ stage.themeStage ? 'Mettre à jour le thème' : 'Enregistrer le thème' }}
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>

            <!-- Evaluation avancée -->
            <div v-if="activeTab === 'evaluation'" class="space-y-6">
              <div v-if="stage.est_reaffecte" class="bg-amber-50 p-4 rounded-md border border-amber-200 mb-4">
                <p class="text-amber-700 text-sm">Ce stage a été réaffecté. Vous ne pouvez pas modifier l'évaluation.</p>
              </div>

              <!-- Affichage lecture seule si une évaluation existe -->
              <div v-else-if="stage.evaluation">
                <div class="bg-blue-50 p-4 rounded-md border border-blue-200 mb-4">
                  <span class="text-blue-800 font-medium">Vous avez déjà évalué ce stage. Voici votre évaluation :</span>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                  <div class="grid grid-cols-1 gap-6">
                    <div v-for="(label, key, idx) in {
                      ponctualite: 'Ponctualité / Présences',
                      motivation: 'Motivation / Initiative',
                      capacite_apprendre: 'Capacités d\'apprendre',
                      qualite_travail: 'Qualité du travail',
                      rapidite_execution: 'Rapidité d\'exécution',
                      jugement: 'Jugement',
                      esprit_motivation: 'Esprit de motivation',
                      esprit_collaboration: 'Esprit de collaboration',
                      sens_responsabilite: 'Sens de responsabilité',
                      communication: 'Communication'
                    }" :key="key">
                      <div class="flex justify-between items-center">
                        <span class="font-medium">{{ idx+1 }}. {{ label }}</span>
                        <span class="text-base font-semibold text-blue-800">{{ stage.evaluation[key] }}/2</span>
                    </div>
                          </div>
                    <div class="bg-blue-50 p-4 rounded-md border border-blue-100">
                      <h4 class="font-medium text-blue-800 mb-2">Note totale</h4>
                      <div class="flex items-center">
                        <span class="text-base font-semibold text-blue-800">{{ stage.evaluation.note_totale }}/20</span>
                        </div>
                      </div>
                    <div>
                      <h4 class="font-medium text-gray-800 mb-2">Commentaire général</h4>
                      <div class="bg-gray-50 p-4 rounded-md">
                        <p class="text-sm text-gray-600">{{ stage.evaluation.commentaire_general || 'Aucun commentaire général' }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Formulaire de saisie si pas d'évaluation -->
              <div v-else>
                <h3 class="text-lg font-semibold mb-4 flex items-center">
                  <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                  Saisir une évaluation
                </h3>
                <div v-if="stage.statut === 'En attente'" class="bg-yellow-50 p-4 rounded-md border border-yellow-200 mb-4">
                  <p class="text-yellow-700 text-sm">Le stage n'a pas encore commencé. Vous pourrez évaluer le stagiaire une fois le stage en cours.</p>
                </div>
                <form v-else @submit.prevent="onEvaluationSubmit" class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                  <div class="grid grid-cols-1 gap-6">
                    <div v-for="(label, key, idx) in {
                      ponctualite: 'Ponctualité / Présences',
                      motivation: 'Motivation / Initiative',
                      capacite_apprendre: 'Capacités d\'apprendre',
                      qualite_travail: 'Qualité du travail',
                      rapidite_execution: 'Rapidité d\'exécution',
                      jugement: 'Jugement',
                      esprit_motivation: 'Esprit de motivation',
                      esprit_collaboration: 'Esprit de collaboration',
                      sens_responsabilite: 'Sens de responsabilité',
                      communication: 'Communication'
                    }" :key="key">
                      <label :for="key" class="block text-sm font-medium text-gray-700 mb-1">{{ idx+1 }}. {{ label }} <span class="text-red-500">*</span></label>
                      <div class="flex items-center gap-2">
                          <input
                          :id="key"
                          v-model="evaluationForm[key]"
                            type="range"
                          min="0"
                          max="2"
                          step="0.5"
                            required
                            class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
                          />
                        <span class="ml-3 text-sm font-medium w-16">{{ evaluationForm[key] }}/2</span>
                        </div>
                      </div>
                    <div class="bg-blue-50 p-4 rounded-md">
                      <h4 class="font-medium text-blue-800 mb-2">Note totale</h4>
                        <div class="flex items-center">
                        <div class="relative w-full bg-blue-200 rounded-full h-3">
                          <div class="absolute h-3 rounded-full bg-blue-600" :style="`width: ${(getTotal() / 20) * 100}%`"></div>
                        </div>
                        <span class="ml-3 text-base font-semibold text-blue-800">{{ getTotal() }}/20</span>
                      </div>
                    </div>
                    <div>
                      <label for="commentaire_general" class="block text-sm font-medium text-gray-700 mb-1">Commentaire général <span class="text-red-500">*</span></label>
                      <textarea
                        id="commentaire_general"
                        v-model="evaluationForm.commentaire_general"
                        rows="5"
                        required
                        class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Commentaire général sur le stagiaire et son stage..."
                      ></textarea>
                    </div>
                    <div class="flex justify-end space-x-3 pt-2">
                      <button
                        type="button"
                        @click="activeTab = 'infos'"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                      >
                        Annuler
                      </button>
                      <button
                        type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                      >
                        Enregistrer l'évaluation
                      </button>
                    </div>
                  </div>
                </form>
              </div>
                </div>

            <!-- Contacter le stagiaire -->
            <div v-if="activeTab === 'contacter'" class="space-y-6">
              <h3 class="text-lg font-semibold mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                Contacter le stagiaire
              </h3>

              <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="grid grid-cols-1 gap-6">
                  <div>
                    <label for="contact_subject" class="block text-sm font-medium text-gray-700 mb-1">Sujet <span class="text-red-500">*</span></label>
                    <input
                      id="contact_subject"
                      v-model="contactForm.subject"
                      type="text"
                      required
                      class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Saisir le sujet de votre message"
                    />
                  </div>

                  <div>
                    <label for="contact_message" class="block text-sm font-medium text-gray-700 mb-1">Message <span class="text-red-500">*</span></label>
                    <textarea
                      id="contact_message"
                      v-model="contactForm.message"
                      rows="10"
                      required
                      class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Rédigez votre message..."
                    ></textarea>
                </div>

                  <div class="flex justify-end space-x-3 pt-2">
                  <button
                      type="button"
                      @click="activeTab = 'infos'"
                      class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                  >
                      Annuler
                  </button>
                  <button
                      @click="onContactSubmit"
                      class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                  >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                      Envoyer le message
                  </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Réaffecter le stage -->
            <div v-if="activeTab === 'reaffecter'" class="space-y-6">
              <div v-if="stage.est_reaffecte" class="bg-amber-50 p-4 rounded-md border border-amber-200 mb-4">
                <p class="text-amber-700 text-sm">Ce stage a déjà été réaffecté. Vous ne pouvez pas le réaffecter à nouveau.</p>
              </div>

              <div v-else>
                <h3 class="text-lg font-semibold mb-4 flex items-center">
                  <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
                  Réaffecter le stage
                </h3>

                <div class="bg-red-50 p-4 mb-6 rounded-lg border border-red-200">
                  <div class="flex">
                    <div class="flex-shrink-0">
                      <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                      </svg>
                    </div>
                    <div class="ml-3">
                      <h3 class="text-sm font-medium text-red-800">Attention - Action irréversible</h3>
                      <div class="mt-2 text-sm text-red-700">
                        <p>
                          La réaffectation d'un stage est une action définitive qui transfère la responsabilité à un autre maître de stage.
                          Vous n'aurez plus la possibilité de modifier les informations de ce stage après cette action.
                          Vous pourrez seulement le consulter.
                        </p>
                      </div>
                    </div>
                  </div>
                </div>

                <form @submit.prevent="onReaffectationSubmit" class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                  <div class="grid grid-cols-1 gap-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                      <div>
                        <label for="nouveau_ms_id" class="block text-sm font-medium text-gray-700 mb-1">Nouveau maître de stage <span class="text-red-500">*</span></label>
                        <select
                          id="nouveau_ms_id"
                          v-model="reaffectationForm.nouveau_ms_id"
                          required
                          class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        >
                          <option value="">Sélectionner un maître de stage</option>
                          <option v-for="ms in maitresDeStage" :key="ms.id" :value="ms.id">
                            {{ ms.nom }} {{ ms.prenom }} ({{ ms.structure_sigle }})
                          </option>
                        </select>
                      </div>

                      <div>
                        <label for="date_reaffectation" class="block text-sm font-medium text-gray-700 mb-1">Date de réaffectation <span class="text-red-500">*</span></label>
                        <input
                          id="date_reaffectation"
                          v-model="reaffectationForm.date_reaffectation"
                          type="date"
                          required
                          class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                          :min="today"
                        />
                      </div>
                    </div>

                    <div>
                      <label for="motif" class="block text-sm font-medium text-gray-700 mb-1">Motif de la réaffectation <span class="text-red-500">*</span></label>
                      <textarea
                        id="motif"
                        v-model="reaffectationForm.motif"
                        rows="4"
                        required
                        class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Expliquez le motif de cette réaffectation..."
                      ></textarea>
                    </div>

                    <div class="flex items-start">
                      <div class="flex items-center h-5">
                        <input
                          id="confirmation"
                          v-model="reaffectationForm.confirmation"
                          type="checkbox"
                          required
                          class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                        />
                      </div>
                      <div class="ml-3 text-sm">
                        <label for="confirmation" class="font-medium text-gray-700">Je confirme la réaffectation de ce stage</label>
                        <p class="text-gray-500">Je comprends que cette action est irréversible et que je n'aurai plus qu'un accès en lecture à ce stage après la réaffectation.</p>
                      </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-2">
                      <button
                        type="button"
                        @click="activeTab = 'infos'"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                      >
                        Annuler
                      </button>
                <button
                        type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                >
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                  </svg>
                        Réaffecter le stage
                </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </MSLayout>
</template>

<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import MSLayout from '@/Layouts/MSLayout.vue';
import AdminToast from '@/Components/AdminToast.vue';
import StageTimeline from '@/Components/StageTimeline.vue';
import { ref, computed, onMounted, watch } from 'vue';
import axios from 'axios'; // Ajout si pas déjà présent

const props = defineProps({
  stage: Object,
  error: String,
  success: String,
  maitresDeStage: Array
});

const toast = ref(null);
const activeTab = ref('infos');

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
  nouveau_ms_id: '',
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
</script>