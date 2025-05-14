<template>
  <Head title="Dashboard Maître de Stage" />

  <MSLayout>
    <!-- Composant Toast pour les notifications -->
    <AdminToast ref="toast" />
    <template #header>
      <div class="flex flex-col gap-1 mb-2">
        <div class="flex items-center gap-4">
          <div class="bg-green-100 text-green-700 rounded-full w-12 h-12 flex items-center justify-center text-2xl font-bold shadow">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
          </div>
          <div>
            <h1 class="text-3xl font-extrabold text-green-800 leading-tight">Tableau de bord Maître de Stage</h1>
            <p v-if="structureResponsable" class="text-blue-600 font-medium">
              Responsable de : {{ structureResponsable.libelle }}
              <span v-if="structureResponsable.sigle" class="text-gray-500">({{ structureResponsable.sigle }})</span>
            </p>
          </div>
        </div>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Message d'erreur -->
        <div v-if="error" class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md shadow">
          <p class="font-bold">Erreur</p>
          <p>{{ error }}</p>
        </div>

        <!-- Informations du maître de stage -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
          <div class="flex items-center gap-4">
            <div class="bg-green-100 text-green-700 rounded-full w-16 h-16 flex items-center justify-center text-2xl font-bold shadow">
              <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
            </div>
            <div>
              <h2 class="text-2xl font-bold text-gray-800">{{ agent?.user?.nom }} {{ agent?.user?.prenom }}</h2>
              <p class="text-gray-600">{{ agent?.fonction }}</p>
              <p class="text-green-600 font-semibold">Maître de Stage</p>
              <p v-if="structureResponsable" class="text-blue-600 mt-1">
                Responsable de : <span class="font-semibold">{{ structureResponsable.libelle }}</span>
                <span v-if="structureResponsable.sigle" class="text-gray-500">({{ structureResponsable.sigle }})</span>
              </p>
            </div>
          </div>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
          <!-- Stages en cours -->
          <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-shadow duration-300 p-6 flex items-center gap-4">
            <div class="p-4 rounded-full bg-blue-100 text-blue-600 shadow">
              <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
              </svg>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-600">Stages en cours</p>
              <p class="text-3xl font-extrabold text-gray-800">{{ stats.stagesEnCours }}</p>
            </div>
          </div>
          <!-- Stages terminés -->
          <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-shadow duration-300 p-6 flex items-center gap-4">
            <div class="p-4 rounded-full bg-green-100 text-green-600 shadow">
              <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-600">Stages terminés</p>
              <p class="text-3xl font-extrabold text-gray-800">{{ stats.stagesTermines }}</p>
            </div>
          </div>
          <!-- Total stagiaires -->
          <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-shadow duration-300 p-6 flex items-center gap-4">
            <div class="p-4 rounded-full bg-purple-100 text-purple-600 shadow">
              <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-600">Total stagiaires</p>
              <p class="text-3xl font-extrabold text-gray-800">{{ stats.totalStagiaires }}</p>
            </div>
          </div>
        </div>

        <!-- Filtres pour les stages -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
          <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
              <h2 class="text-xl font-semibold text-gray-800">Mes stages</h2>
              <div class="flex flex-wrap gap-3">
                <select v-model="filters.statut" class="rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                  <option value="">Tous les statuts</option>
                  <option value="En cours">En cours</option>
                  <option value="Terminé">Terminés</option>
                  <option value="En attente">En attente</option>
                </select>
                <input
                  v-model="filters.search"
                  type="text"
                  placeholder="Rechercher un stagiaire..."
                  class="rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                />
                <button
                  @click="resetFilters"
                  class="px-3 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors duration-200"
                >
                  Réinitialiser
                </button>
              </div>
            </div>

            <div v-if="filteredStages.length === 0" class="text-gray-500 italic py-4">
              Aucun stage correspondant aux critères de recherche.
            </div>
            <div v-else class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stagiaire</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Structure</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Période</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="stage in filteredStages" :key="stage.id" class="hover:bg-gray-50" :class="{ 'bg-gray-100': stage.est_reaffecte }">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center">
                        <div>
                          <div class="text-sm font-medium text-gray-900">
                            <template v-if="stage.demandeStage?.stagiaire?.user?.nom">
                              {{ stage.demandeStage.stagiaire.user.nom }} {{ stage.demandeStage.stagiaire.user.prenom }}
                            </template>
                            <template v-else>
                              <span class="italic text-gray-500">Nom non disponible</span>
                            </template>
                          </div>
                          <div class="text-sm text-gray-500">
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
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">{{ stage.structure?.libelle }}</div>
                      <div class="text-sm text-gray-500">{{ stage.structure?.sigle }}</div>

                      <!-- Afficher les informations de réaffectation si le stage a été réaffecté -->
                      <div v-if="stage.est_reaffecte && stage.reaffectation_info" class="mt-2 text-xs bg-yellow-50 p-2 rounded border border-yellow-200">
                        <p class="font-semibold text-yellow-700">Réaffecté à:</p>
                        <p class="text-gray-700">{{ stage.reaffectation_info.nouveau_ms_prenom }} {{ stage.reaffectation_info.nouveau_ms_nom }}</p>
                        <p class="text-gray-600">{{ stage.reaffectation_info.structure_libelle }}</p>
                        <p class="text-gray-500 text-xs mt-1">Le {{ formatDate(stage.reaffectation_info.date_reaffectation) }}</p>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">
                        {{ formatDate(stage.date_debut) }} - {{ formatDate(stage.date_fin) }}
                      </div>
                      <div class="text-sm text-gray-500">
                        {{ calculateDuration(stage.date_debut, stage.date_fin) }}
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                        :class="{
                          'bg-green-100 text-green-800': stage.statut === 'Terminé',
                          'bg-blue-100 text-blue-800': stage.statut === 'En cours',
                          'bg-yellow-100 text-yellow-800': stage.statut === 'En attente',
                        }">
                        {{ stage.statut }}
                      </span>

                      <!-- Badge de réaffectation -->
                      <span v-if="stage.est_reaffecte" class="mt-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                        Réaffecté
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <div class="flex space-x-2">
                        <button
                          @click="viewStageDetails(stage)"
                          class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-2 py-1 rounded transition-colors duration-200"
                          title="Voir les détails"
                        >
                          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                          </svg>
                        </button>
                        <!-- N'afficher les boutons d'action que si le stage est actif -->
                        <template v-if="stage.est_actif !== false">
                          <button
                            @click="contactStagiaire(stage)"
                            class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 px-2 py-1 rounded transition-colors duration-200"
                            title="Contacter le stagiaire"
                          >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                          </button>
                          <button
                            v-if="stage.statut === 'En cours'"
                            @click="updateStageStatus(stage, 'Terminé')"
                            class="text-purple-600 hover:text-purple-900 bg-purple-50 hover:bg-purple-100 px-2 py-1 rounded transition-colors duration-200"
                            title="Marquer comme terminé"
                          >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                          </button>
                        </template>
                        <!-- Message informatif pour les stages réaffectés -->
                        <span v-else-if="stage.est_reaffecte" class="text-gray-500 text-xs italic">
                          Réaffecté
                        </span>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Modal pour les détails du stage -->
        <div v-if="showStageDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
          <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
              <div class="flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-900">Détails du stage</h3>
                <button @click="showStageDetailsModal = false" class="text-gray-400 hover:text-gray-500">
                  <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
            </div>
            <div class="p-6" v-if="selectedStage">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <h4 class="text-lg font-semibold mb-2">Informations du stagiaire</h4>
                  <div class="bg-gray-50 p-4 rounded-lg">
                    <p><span class="font-medium">Nom :</span> {{ selectedStage.demandeStage?.stagiaire?.user?.nom }} {{ selectedStage.demandeStage?.stagiaire?.user?.prenom }}</p>
                    <p><span class="font-medium">Email :</span> {{ selectedStage.demandeStage?.stagiaire?.user?.email }}</p>
                    <p><span class="font-medium">Téléphone :</span> {{ selectedStage.demandeStage?.stagiaire?.user?.telephone || 'Non renseigné' }}</p>
                    <p><span class="font-medium">Niveau d'étude :</span> {{ selectedStage.demandeStage?.stagiaire?.niveau_etude || 'Non renseigné' }}</p>
                    <p><span class="font-medium">Université :</span> {{ selectedStage.demandeStage?.stagiaire?.universite || 'Non renseignée' }}</p>
                    <p><span class="font-medium">Filière :</span> {{ selectedStage.demandeStage?.stagiaire?.filiere || 'Non renseignée' }}</p>
                  </div>
                </div>
                <div>
                  <h4 class="text-lg font-semibold mb-2">Informations du stage</h4>
                  <div class="bg-gray-50 p-4 rounded-lg">
                    <p><span class="font-medium">Structure :</span> {{ selectedStage.structure?.libelle }}</p>
                    <p><span class="font-medium">Type :</span> {{ selectedStage.type || 'Non renseigné' }}</p>
                    <p><span class="font-medium">Période :</span> {{ formatDate(selectedStage.date_debut) }} - {{ formatDate(selectedStage.date_fin) }}</p>
                    <p><span class="font-medium">Durée :</span> {{ calculateDuration(selectedStage.date_debut, selectedStage.date_fin) }}</p>
                    <p><span class="font-medium">Statut :</span> {{ selectedStage.statut }}</p>
                    <p><span class="font-medium">Note :</span> {{ selectedStage.note || 'Non évalué' }}</p>
                  </div>
                </div>
              </div>

              <!-- Informations de réaffectation -->
              <div v-if="selectedStage.est_reaffecte && selectedStage.reaffectation_info" class="mt-6">
                <h4 class="text-lg font-semibold mb-2">Informations de réaffectation</h4>
                <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                  <p><span class="font-medium">Réaffecté à :</span> {{ selectedStage.reaffectation_info.nouveau_ms_prenom }} {{ selectedStage.reaffectation_info.nouveau_ms_nom }}</p>
                  <p><span class="font-medium">Structure :</span> {{ selectedStage.reaffectation_info.structure_libelle }}</p>
                  <p><span class="font-medium">Date de réaffectation :</span> {{ formatDate(selectedStage.reaffectation_info.date_reaffectation) }}</p>
                  <p class="mt-2 text-yellow-700 italic">Ce stage a été réaffecté. Vous ne pouvez plus effectuer d'actions dessus.</p>
                </div>
              </div>

              <!-- Actions -->
              <div class="mt-6 flex justify-end space-x-3">
                <!-- N'afficher les boutons d'action que si le stage est actif -->
                <template v-if="selectedStage.est_actif !== false">
                  <button
                    @click="contactStagiaire(selectedStage)"
                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors duration-200"
                  >
                    Contacter le stagiaire
                  </button>
                  <button
                    v-if="selectedStage.statut === 'En cours'"
                    @click="updateStageStatus(selectedStage, 'Terminé')"
                    class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors duration-200"
                  >
                    Marquer comme terminé
                  </button>
                  <button
                    @click="openReaffectationModal(selectedStage)"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200"
                  >
                    Réaffecter
                  </button>
                </template>
                <button
                  @click="showStageDetailsModal = false"
                  class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors duration-200"
                >
                  Fermer
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal pour contacter le stagiaire -->
        <div v-if="showContactModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
          <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full">
            <div class="p-6 border-b border-gray-200">
              <div class="flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-900">Contacter le stagiaire</h3>
                <button @click="showContactModal = false" class="text-gray-400 hover:text-gray-500">
                  <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
            </div>
            <div class="p-6" v-if="selectedStage">
              <!-- Message d'avertissement pour les stages réaffectés -->
              <div v-if="selectedStage.est_reaffecte" class="bg-yellow-50 p-4 rounded-md border border-yellow-200 mb-4">
                <p class="text-yellow-800">
                  <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                  </svg>
                  Ce stage a été réaffecté. Vous ne pouvez plus contacter le stagiaire.
                </p>
              </div>

              <!-- Formulaire de contact (uniquement pour les stages actifs) -->
              <form v-if="!selectedStage.est_reaffecte" @submit.prevent="sendMessage">
                <div class="mb-4">
                  <label class="block text-gray-700 text-sm font-bold mb-2" for="subject">
                    Sujet
                  </label>
                  <input
                    v-model="contactForm.subject"
                    id="subject"
                    type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                    placeholder="Sujet du message"
                    required
                  />
                </div>
                <div class="mb-6">
                  <label class="block text-gray-700 text-sm font-bold mb-2" for="message">
                    Message
                  </label>
                  <textarea
                    v-model="contactForm.message"
                    id="message"
                    rows="5"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                    placeholder="Votre message..."
                    required
                  ></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                  <button
                    type="button"
                    @click="showContactModal = false"
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors duration-200"
                  >
                    Annuler
                  </button>
                  <button
                    type="submit"
                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors duration-200"
                  >
                    Envoyer
                  </button>
                </div>
              </form>

              <!-- Bouton de fermeture pour les stages réaffectés -->
              <div v-if="selectedStage.est_reaffecte" class="flex justify-end">
                <button
                  @click="showContactModal = false"
                  class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors duration-200"
                >
                  Fermer
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal pour la réaffectation -->
        <div v-if="showReaffectationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
          <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full">
            <div class="p-6 border-b border-gray-200">
              <div class="flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-900">Réaffecter le stage</h3>
                <button @click="showReaffectationModal = false" class="text-gray-400 hover:text-gray-500">
                  <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
            </div>
            <div class="p-6" v-if="selectedStage">
              <!-- Message d'avertissement pour les stages réaffectés -->
              <div v-if="selectedStage.est_reaffecte" class="bg-yellow-50 p-4 rounded-md border border-yellow-200 mb-4">
                <p class="text-yellow-800">
                  <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                  </svg>
                  Ce stage a déjà été réaffecté. Vous ne pouvez pas le réaffecter à nouveau.
                </p>

                <div v-if="selectedStage.reaffectation_info" class="mt-4 bg-white p-3 rounded-md border border-gray-200">
                  <p><span class="font-medium">Réaffecté à :</span> {{ selectedStage.reaffectation_info.nouveau_ms_prenom }} {{ selectedStage.reaffectation_info.nouveau_ms_nom }}</p>
                  <p><span class="font-medium">Structure :</span> {{ selectedStage.reaffectation_info.structure_libelle }}</p>
                  <p><span class="font-medium">Date de réaffectation :</span> {{ formatDate(selectedStage.reaffectation_info.date_reaffectation) }}</p>
                </div>
              </div>

              <!-- Chargement -->
              <div v-else-if="loading" class="flex justify-center items-center py-8">
                <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
              </div>

              <!-- Message d'erreur -->
              <div v-else-if="errorMsg" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md">
                <p class="font-bold">Erreur</p>
                <p>{{ errorMsg }}</p>
              </div>

              <!-- Aucun maître de stage disponible -->
              <div v-else-if="maitresStage.length === 0" class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-md">
                <p class="font-bold">Information</p>
                <p>Aucun maître de stage disponible dans les sous-structures. Vous ne pouvez pas réaffecter ce stage.</p>
              </div>

              <!-- Formulaire de réaffectation -->
              <div v-else>
                <p class="mb-4 text-gray-700">
                  Vous pouvez réaffecter ce stage à un maître de stage d'une sous-structure.
                  Cette action est irréversible et transférera la responsabilité du stage au nouveau maître de stage.
                </p>

                <div class="mb-6">
                  <label class="block text-gray-700 text-sm font-bold mb-2" for="nouveau_ms">
                    Nouveau maître de stage
                  </label>
                  <select
                    v-model="reaffectationForm.nouveauMaitreStageId"
                    id="nouveau_ms"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required
                  >
                    <option value="" disabled>Sélectionnez un maître de stage</option>
                    <option v-for="ms in maitresStage" :key="ms.id" :value="ms.id">
                      {{ ms.prenom }} {{ ms.nom }} - {{ ms.structure.libelle }} ({{ ms.structure.sigle }})
                    </option>
                  </select>
                </div>

                <div class="flex justify-end space-x-3">
                  <button
                    type="button"
                    @click="showReaffectationModal = false"
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors duration-200"
                  >
                    Annuler
                  </button>
                  <button
                    type="button"
                    @click="reaffecterStage"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200"
                    :disabled="!reaffectationForm.nouveauMaitreStageId"
                  >
                    Réaffecter le stage
                  </button>
                </div>
              </div>

              <!-- Bouton de fermeture pour les stages réaffectés -->
              <div v-if="selectedStage.est_reaffecte" class="flex justify-end mt-4">
                <button
                  @click="showReaffectationModal = false"
                  class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors duration-200"
                >
                  Fermer
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Boîte de dialogue de confirmation -->
    <div v-if="showConfirmDialog" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <div class="p-6 border-b border-gray-200">
          <div class="flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-900">Confirmation</h3>
            <button @click="showConfirmDialog = false" class="text-gray-400 hover:text-gray-500">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
        <div class="p-6">
          <p class="mb-6 text-gray-700">{{ confirmMessage }}</p>
          <div class="flex justify-end space-x-3">
            <button
              @click="showConfirmDialog = false"
              class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors duration-200"
            >
              Annuler
            </button>
            <button
              @click="executeConfirmAction"
              class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200"
            >
              Confirmer
            </button>
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
