<template>
  <Head :title="`Stage de ${getStagiaireName(stage)}`" />

  <MSLayout>
    <!-- Composant Toast pour les notifications -->
    <AdminToast ref="toast" />
    <template #header>
      <div class="flex items-center gap-4 mb-2">
        <div class="bg-green-100 text-green-700 rounded-full w-12 h-12 flex items-center justify-center text-2xl font-bold shadow">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
        </div>
        <h1 class="text-3xl font-extrabold text-green-800 leading-tight">Détails du Stage</h1>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Message d'erreur -->
        <div v-if="error" class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md shadow">
          <p class="font-bold">Erreur</p>
          <p>{{ error }}</p>
        </div>

        <!-- Message de succès -->
        <div v-if="success" class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow">
          <p class="font-bold">Succès</p>
          <p>{{ success }}</p>
        </div>

        <!-- Bouton de retour -->
        <div class="mb-6 flex justify-between items-center">
          <Link :href="route('agent.ms.stages')" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour à la liste
          </Link>

          <!-- Afficher un badge si le stage a été réaffecté -->
          <div v-if="stage.est_reaffecte" class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded-md border border-yellow-200 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Stage réaffecté - Consultation uniquement</span>
          </div>
        </div>

        <!-- Informations de réaffectation -->
        <div v-if="stage.est_reaffecte && stage.reaffectation_info" class="mb-6 bg-yellow-50 p-4 rounded-lg border border-yellow-200">
          <h3 class="font-semibold text-yellow-800 mb-2">Informations de réaffectation</h3>
          <p><span class="font-medium">Réaffecté à :</span> {{ stage.reaffectation_info.nouveau_ms_prenom }} {{ stage.reaffectation_info.nouveau_ms_nom }}</p>
          <p><span class="font-medium">Structure :</span> {{ stage.reaffectation_info.structure_libelle }} {{ stage.reaffectation_info.structure_sigle ? `(${stage.reaffectation_info.structure_sigle})` : '' }}</p>
          <p><span class="font-medium">Date de réaffectation :</span> {{ formatDate(stage.reaffectation_info.date_reaffectation) }}</p>
        </div>

        <!-- Onglets -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
          <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
              <button
                @click="activeTab = 'infos'"
                :class="[
                  'py-4 px-6 text-center border-b-2 font-medium text-sm leading-5 focus:outline-none transition duration-150 ease-in-out',
                  activeTab === 'infos'
                    ? 'border-green-500 text-green-600'
                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                ]"
              >
                Informations générales
              </button>
              <button
                @click="activeTab = 'theme'"
                :class="[
                  'py-4 px-6 text-center border-b-2 font-medium text-sm leading-5 focus:outline-none transition duration-150 ease-in-out',
                  activeTab === 'theme'
                    ? 'border-green-500 text-green-600'
                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                ]"
              >
                Thème du stage
              </button>
              <button
                @click="activeTab = 'evaluation'"
                :class="[
                  'py-4 px-6 text-center border-b-2 font-medium text-sm leading-5 focus:outline-none transition duration-150 ease-in-out',
                  activeTab === 'evaluation'
                    ? 'border-green-500 text-green-600'
                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                ]"
              >
                Évaluation
              </button>
              <button
                @click="activeTab = 'contact'"
                :disabled="stage.est_reaffecte"
                :class="[
                  'py-4 px-6 text-center border-b-2 font-medium text-sm leading-5 focus:outline-none transition duration-150 ease-in-out',
                  activeTab === 'contact'
                    ? 'border-green-500 text-green-600'
                    : stage.est_reaffecte
                      ? 'border-transparent text-gray-400 cursor-not-allowed'
                      : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                ]"
              >
                Contacter le stagiaire
              </button>
              <button
                @click="activeTab = 'reaffectation'"
                :disabled="stage.est_reaffecte"
                :class="[
                  'py-4 px-6 text-center border-b-2 font-medium text-sm leading-5 focus:outline-none transition duration-150 ease-in-out',
                  activeTab === 'reaffectation'
                    ? 'border-green-500 text-green-600'
                    : stage.est_reaffecte
                      ? 'border-transparent text-gray-400 cursor-not-allowed'
                      : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                ]"
              >
                Réaffecter le stage
              </button>
            </nav>
          </div>

          <!-- Contenu des onglets -->
          <div class="p-6">
            <!-- Informations générales -->
            <div v-if="activeTab === 'infos'" class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <h3 class="text-lg font-semibold mb-4">Informations du stagiaire</h3>
                  <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center mb-4">
                      <div class="bg-green-100 text-green-700 rounded-full w-12 h-12 flex items-center justify-center text-xl font-bold shadow mr-4">
                        {{ getStagiaireInitials(stage) }}
                      </div>
                      <div>
                        <p class="font-bold text-lg">{{ getStagiaireName(stage) }}</p>
                        <p class="text-gray-600">{{ getStagiaireEmail(stage) }}</p>

                      </div>
                    </div>
                    <div class="space-y-2">
                      <p><span class="font-medium">Téléphone :</span> {{ stage.stagiaire_info?.telephone || stage.demandeStage?.stagiaire?.telephone || stage.demandeStage?.stagiaire?.user?.telephone || 'Non renseigné' }}</p>
                      <p><span class="font-medium">Niveau d'étude :</span> {{ stage.stagiaire_info?.niveau_etude || stage.demandeStage?.stagiaire?.niveau_etude || 'Non renseigné' }}</p>
                      <p><span class="font-medium">Université :</span> {{ stage.stagiaire_info?.universite || stage.demandeStage?.stagiaire?.universite || 'Non renseignée' }}</p>
                      <p><span class="font-medium">Filière :</span> {{ stage.stagiaire_info?.filiere || stage.demandeStage?.stagiaire?.filiere || 'Non renseignée' }}</p>
                    </div>
                  </div>
                </div>
                <div>
                  <h3 class="text-lg font-semibold mb-4">Informations du stage</h3>
                  <div class="bg-gray-50 p-4 rounded-lg">
                    <p><span class="font-medium">Structure :</span> {{ stage.structure?.libelle }}</p>
                    <p><span class="font-medium">Type :</span> {{ stage.type || 'Non renseigné' }}</p>
                    <p><span class="font-medium">Période :</span> {{ formatDate(stage.date_debut) }} - {{ formatDate(stage.date_fin) }}</p>
                    <p><span class="font-medium">Durée :</span> {{ calculateDuration(stage.date_debut, stage.date_fin) }}</p>
                    <p class="mt-2">
                      <span class="font-medium">Statut :</span>
                      <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full"
                        :class="{
                          'bg-green-100 text-green-800': stage.statut === 'Terminé',
                          'bg-blue-100 text-blue-800': stage.statut === 'En cours',
                          'bg-yellow-100 text-yellow-800': stage.statut === 'En attente',
                        }">
                        {{ stage.statut }}
                      </span>
                    </p>
                    <div class="mt-4" v-if="stage.statut === 'En cours' && !stage.est_reaffecte">
                      <button
                        @click="updateStageStatus(stage, 'Terminé')"
                        class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors duration-200"
                      >
                        Marquer comme terminé
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Thème du stage -->
            <div v-if="activeTab === 'theme'" class="space-y-6">
              <div v-if="stage.themeStage" class="bg-gray-50 p-6 rounded-lg">
                <div class="flex justify-between items-start mb-4">
                  <h3 class="text-lg font-semibold">{{ stage.themeStage.intitule }}</h3>
                  <span class="px-2 py-1 text-xs font-semibold rounded-full"
                    :class="{
                      'bg-green-100 text-green-800': stage.themeStage.etat === 'Validé',
                      'bg-yellow-100 text-yellow-800': stage.themeStage.etat === 'Proposé',
                      'bg-red-100 text-red-800': stage.themeStage.etat === 'Refusé',
                      'bg-blue-100 text-blue-800': stage.themeStage.etat === 'Modifié',
                    }">
                    {{ stage.themeStage.etat }}
                  </span>
                </div>
                <div class="mb-4">
                  <p class="text-gray-700">{{ stage.themeStage.description }}</p>
                </div>
                <div class="mb-4">
                  <p class="font-medium">Mots-clés :</p>
                  <div class="flex flex-wrap gap-2 mt-2">
                    <span v-for="(motCle, index) in getMotsCles(stage.themeStage)" :key="index"
                      class="px-2 py-1 bg-gray-200 text-gray-700 rounded-full text-xs">
                      {{ motCle }}
                    </span>
                  </div>
                </div>
                <div class="flex space-x-3 mt-6" v-if="stage.themeStage.etat === 'Proposé' && !stage.est_reaffecte">
                  <button
                    @click="validerTheme()"
                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors duration-200"
                  >
                    Valider le thème
                  </button>
                  <button
                    @click="showRefuserThemeModal = true"
                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors duration-200"
                  >
                    Refuser le thème
                  </button>
                </div>
                <div class="mt-6 bg-gray-100 p-4 rounded-md" v-else-if="stage.themeStage.etat === 'Proposé' && stage.est_reaffecte">
                  <p class="text-gray-600 italic">Ce stage a été réaffecté. Vous ne pouvez plus valider ou refuser le thème.</p>
                </div>
              </div>
              <div v-else class="bg-gray-50 p-6 rounded-lg">
                <p class="text-gray-500 italic">Aucun thème n'a encore été proposé pour ce stage.</p>
              </div>
            </div>

            <!-- Évaluation -->
            <div v-if="activeTab === 'evaluation'" class="space-y-6">
              <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold mb-4">Évaluation du stagiaire</h3>
                <div v-if="stage.note">
                  <p class="mb-2"><span class="font-medium">Note :</span> {{ stage.note }}/20</p>
                  <p class="mb-4"><span class="font-medium">Commentaire :</span> {{ stage.commentaire_evaluation || 'Aucun commentaire' }}</p>
                  <button
                    v-if="!stage.est_reaffecte"
                    @click="showEvaluationModal = true; initEvaluationForm()"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200"
                  >
                    Modifier l'évaluation
                  </button>
                  <p v-else class="text-gray-600 italic mt-2">
                    Ce stage a été réaffecté. Vous ne pouvez plus modifier l'évaluation.
                  </p>
                </div>
                <div v-else>
                  <p class="text-gray-500 italic mb-4">Ce stage n'a pas encore été évalué.</p>
                  <button
                    v-if="!stage.est_reaffecte"
                    @click="showEvaluationModal = true; initEvaluationForm()"
                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors duration-200"
                    :disabled="stage.statut !== 'Terminé'"
                  >
                    Évaluer le stagiaire
                  </button>
                  <p v-if="stage.statut !== 'Terminé' && !stage.est_reaffecte" class="text-sm text-gray-500 mt-2">
                    Le stage doit être marqué comme terminé avant de pouvoir être évalué.
                  </p>
                  <p v-if="stage.est_reaffecte" class="text-gray-600 italic mt-2">
                    Ce stage a été réaffecté. Vous ne pouvez plus évaluer le stagiaire.
                  </p>
                </div>
              </div>
            </div>

            <!-- Contact -->
            <div v-if="activeTab === 'contact'" class="space-y-6">
              <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold mb-4">Contacter le stagiaire</h3>

                <div v-if="stage.est_reaffecte" class="bg-yellow-50 p-4 rounded-md border border-yellow-200 mb-4">
                  <p class="text-yellow-800">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Ce stage a été réaffecté. Vous ne pouvez plus contacter le stagiaire.
                  </p>
                </div>

                <form v-if="!stage.est_reaffecte" @submit.prevent="sendMessage">
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
                  <div class="flex justify-end">
                    <button
                      type="submit"
                      class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors duration-200"
                    >
                      Envoyer
                    </button>
                  </div>
                </form>
              </div>
            </div>

            <!-- Réaffectation -->
            <div v-if="activeTab === 'reaffectation'" class="space-y-6">
              <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold mb-4">Réaffecter le stage à un autre maître de stage</h3>

                <div v-if="stage.est_reaffecte" class="bg-yellow-50 p-4 rounded-md border border-yellow-200 mb-4">
                  <p class="text-yellow-800">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Ce stage a déjà été réaffecté. Vous ne pouvez pas le réaffecter à nouveau.
                  </p>
                </div>

                <div v-else-if="loading" class="flex justify-center items-center py-8">
                  <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-green-500"></div>
                </div>

                <div v-else-if="errorMsg" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md">
                  <p class="font-bold">Erreur</p>
                  <p>{{ errorMsg }}</p>
                </div>

                <div v-else-if="maitresStage.length === 0" class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-md">
                  <p class="font-bold">Information</p>
                  <p>Aucun maître de stage disponible dans les sous-structures. Vous ne pouvez pas réaffecter ce stage.</p>
                </div>

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
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
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
                      @click="activeTab = 'infos'"
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
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal pour refuser le thème -->
    <div v-if="showRefuserThemeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full">
        <div class="p-6 border-b border-gray-200">
          <div class="flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-900">Refuser le thème</h3>
            <button @click="showRefuserThemeModal = false" class="text-gray-400 hover:text-gray-500">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
        <div class="p-6">
          <form @submit.prevent="refuserTheme">
            <div class="mb-6">
              <label class="block text-gray-700 text-sm font-bold mb-2" for="motif_refus">
                Motif du refus
              </label>
              <textarea
                v-model="refusForm.motif"
                id="motif_refus"
                rows="5"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                placeholder="Veuillez expliquer pourquoi vous refusez ce thème..."
                required
              ></textarea>
            </div>
            <div class="flex justify-end space-x-3">
              <button
                type="button"
                @click="showRefuserThemeModal = false"
                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors duration-200"
              >
                Annuler
              </button>
              <button
                type="submit"
                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors duration-200"
              >
                Confirmer le refus
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Modal pour l'évaluation -->
    <div v-if="showEvaluationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full">
        <div class="p-6 border-b border-gray-200">
          <div class="flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-900">Évaluer le stagiaire</h3>
            <button @click="showEvaluationModal = false" class="text-gray-400 hover:text-gray-500">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
        <div class="p-6">
          <form @submit.prevent="submitEvaluation">
            <div class="mb-4">
              <label class="block text-gray-700 text-sm font-bold mb-2" for="note">
                Note (sur 20)
              </label>
              <input
                v-model="evaluationForm.note"
                id="note"
                type="number"
                min="0"
                max="20"
                step="0.5"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                required
              />
            </div>
            <div class="mb-6">
              <label class="block text-gray-700 text-sm font-bold mb-2" for="commentaire">
                Commentaire
              </label>
              <textarea
                v-model="evaluationForm.commentaire"
                id="commentaire"
                rows="5"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                placeholder="Commentaire sur le travail du stagiaire..."
              ></textarea>
            </div>
            <div class="flex justify-end space-x-3">
              <button
                type="button"
                @click="showEvaluationModal = false"
                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors duration-200"
              >
                Annuler
              </button>
              <button
                type="submit"
                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors duration-200"
              >
                Enregistrer l'évaluation
              </button>
            </div>
          </form>
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
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import MSLayout from '@/Layouts/MSLayout.vue';
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';
import AdminToast from '@/Components/AdminToast.vue';

const props = defineProps({
  stage: Object,
  error: String,
  success: String,
  openContact: Boolean
});

// Débogage des données reçues
console.log('Stage reçu:', props.stage);
if (props.stage) {
  console.log('Infos stagiaire:', props.stage.stagiaire_info);
  console.log('Demande de stage:', props.stage.demandeStage);
  if (props.stage.demandeStage) {
    console.log('Stagiaire:', props.stage.demandeStage.stagiaire);
    if (props.stage.demandeStage.stagiaire) {
      console.log('User du stagiaire:', props.stage.demandeStage.stagiaire.user);
    }
  }
}

// État pour les onglets et modals
const activeTab = ref('infos');
const showRefuserThemeModal = ref(false);
const showEvaluationModal = ref(false);

// État pour la réaffectation
const loading = ref(false);
const errorMsg = ref('');
const maitresStage = ref([]);

// Référence au composant Toast
const toast = ref(null);

// État pour la boîte de dialogue de confirmation
const showConfirmDialog = ref(false);
const confirmAction = ref(null);
const confirmMessage = ref('');

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

// Formulaires
const contactForm = ref({
  subject: '',
  message: ''
});

const refusForm = ref({
  motif: ''
});

const evaluationForm = ref({
  note: 0,
  commentaire: ''
});

const reaffectationForm = ref({
  nouveauMaitreStageId: ''
});

// Initialiser le formulaire d'évaluation avec les valeurs existantes
const initEvaluationForm = () => {
  evaluationForm.value.note = props.stage.note || 0;
  evaluationForm.value.commentaire = props.stage.commentaire_evaluation || '';
};

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

// Récupérer le nom du stagiaire
const getStagiaireName = (stage) => {
  if (stage.stagiaire_info?.nom && stage.stagiaire_info?.prenom) {
    return `${stage.stagiaire_info.prenom} ${stage.stagiaire_info.nom}`;
  } else if (stage.demandeStage?.stagiaire?.user) {
    return `${stage.demandeStage.stagiaire.user.prenom} ${stage.demandeStage.stagiaire.user.nom}`;
  }
  return 'Stagiaire';
};

// Récupérer l'email du stagiaire
const getStagiaireEmail = (stage) => {
  if (stage.stagiaire_info?.email) {
    return stage.stagiaire_info.email;
  } else if (stage.demandeStage?.stagiaire?.user) {
    return stage.demandeStage.stagiaire.user.email;
  }
  return '';
};

// Récupérer les initiales du stagiaire
const getStagiaireInitials = (stage) => {
  if (stage.stagiaire_info?.prenom && stage.stagiaire_info?.nom) {
    const prenom = stage.stagiaire_info.prenom || '';
    const nom = stage.stagiaire_info.nom || '';
    return (prenom.charAt(0) + nom.charAt(0)).toUpperCase();
  } else if (stage.demandeStage?.stagiaire?.user) {
    const prenom = stage.demandeStage.stagiaire.user.prenom || '';
    const nom = stage.demandeStage.stagiaire.user.nom || '';
    return (prenom.charAt(0) + nom.charAt(0)).toUpperCase();
  }
  return 'S';
};

// Récupérer les mots-clés du thème
const getMotsCles = (themeStage) => {
  if (!themeStage || !themeStage.mots_cles) return [];
  return themeStage.mots_cles.split(',').map(mot => mot.trim());
};

// Mettre à jour le statut d'un stage
const updateStageStatus = (stage, newStatus) => {
  showConfirm(`Êtes-vous sûr de vouloir marquer ce stage comme ${newStatus} ?`, () => {
    router.post(route('agent.ms.stages.update-status', stage.id), {
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

// Valider le thème du stage
const validerTheme = () => {
  showConfirm('Êtes-vous sûr de vouloir valider ce thème ?', () => {
    router.post(route('agent.ms.stages.valider-theme', props.stage.id), {}, {
      onSuccess: () => {
        // Afficher un toast de succès
        if (toast.value) {
          toast.value.addToast({
            type: 'success',
            title: 'Succès',
            message: 'Le thème du stage a été validé avec succès.'
          });
        }
      },
      onError: (errors) => {
        console.error('Erreur lors de la validation du thème:', errors);

        // Afficher un toast d'erreur
        if (toast.value) {
          toast.value.addToast({
            type: 'error',
            title: 'Erreur',
            message: 'Une erreur est survenue lors de la validation du thème.'
          });
        }
      }
    });
  });
};

// Refuser le thème du stage
const refuserTheme = () => {
  router.post(route('agent.ms.stages.refuser-theme', props.stage.id), {
    motif_refus: refusForm.value.motif
  }, {
    onSuccess: () => {
      showRefuserThemeModal.value = false;
      refusForm.value.motif = '';

      // Afficher un toast de succès
      if (toast.value) {
        toast.value.addToast({
          type: 'success',
          title: 'Succès',
          message: 'Le thème du stage a été refusé avec succès.'
        });
      }
    },
    onError: (errors) => {
      console.error('Erreur lors du refus du thème:', errors);

      // Afficher un toast d'erreur
      if (toast.value) {
        toast.value.addToast({
          type: 'error',
          title: 'Erreur',
          message: 'Une erreur est survenue lors du refus du thème.'
        });
      }
    }
  });
};

// Soumettre l'évaluation
const submitEvaluation = () => {
  router.post(route('agent.ms.stages.noter', props.stage.id), {
    note: evaluationForm.value.note,
    commentaire: evaluationForm.value.commentaire
  }, {
    onSuccess: () => {
      showEvaluationModal.value = false;

      // Afficher un toast de succès
      if (toast.value) {
        toast.value.addToast({
          type: 'success',
          title: 'Succès',
          message: 'L\'évaluation du stage a été enregistrée avec succès.'
        });
      }
    },
    onError: (errors) => {
      console.error('Erreur lors de l\'évaluation du stage:', errors);

      // Afficher un toast d'erreur
      if (toast.value) {
        toast.value.addToast({
          type: 'error',
          title: 'Erreur',
          message: 'Une erreur est survenue lors de l\'enregistrement de l\'évaluation.'
        });
      }
    }
  });
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
      stage_id: props.stage.id,
      subject: contactForm.value.subject,
      message: contactForm.value.message
    });

    // Vérifier la réponse
    if (response.data.success) {
      // Réinitialiser le formulaire
      contactForm.value = {
        subject: '',
        message: ''
      };

      // Rediriger vers l'onglet infos
      activeTab.value = 'infos';

      // Afficher un toast de succès
      if (toast.value) {
        toast.value.addToast({
          type: 'success',
          title: 'Message envoyé',
          message: `Message envoyé à ${getStagiaireEmail(props.stage)} avec succès !`
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

// Charger les maîtres de stage des sous-structures
const loadMaitresStage = async () => {
  loading.value = true;
  errorMsg.value = '';
  maitresStage.value = [];

  try {
    const response = await axios.get(route('agent.ms.stages.maitres-stage-substructures', props.stage.id));

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

  showConfirm('Êtes-vous sûr de vouloir réaffecter ce stage ? Cette action est irréversible.', () => {
    router.post(route('agent.ms.stages.reaffecter', props.stage.id), {
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
      },
      onError: (errors) => {
        console.error('Erreur lors de la réaffectation du stage:', errors);

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

// Ouvrir l'onglet de contact si demandé
onMounted(() => {
  if (props.openContact) {
    activeTab.value = 'contact';
  }
});

// Charger les maîtres de stage lorsqu'on accède à l'onglet de réaffectation
const watchActiveTab = (newTab) => {
  if (newTab === 'reaffectation') {
    loadMaitresStage();
  }
};

// Observer les changements d'onglet
watch(activeTab, watchActiveTab);
</script>
