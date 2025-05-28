<template>
  <Head title="Détails de la demande" />

  <RSLayout>
    <template #header>
      <div class="flex items-center gap-4 mb-2">
        <div class="bg-blue-600 text-white rounded-lg w-14 h-14 flex items-center justify-center shadow-md">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
        </div>
        <div>
          <h1 class="text-2xl font-bold text-gray-800 leading-tight md:text-3xl">Détails de la demande</h1>
          <p class="text-sm text-gray-500 mt-1">Code de suivi: {{ demande.code_suivi }}</p>
        </div>
      </div>
    </template>

    <!-- Composant Toast pour les notifications -->
    <Toast ref="toast" />

    <div class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Bouton retour -->
        <div class="mb-6 flex justify-end">
          <Link :href="route('agent.rs.demandes')" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-md border border-gray-300 hover:bg-gray-200 transition-colors font-medium text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Retour à la liste
          </Link>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Informations sur la demande -->
          <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-800 py-4 px-6">
              <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                  <polyline points="14 2 14 8 20 8"></polyline>
                  <line x1="16" y1="13" x2="8" y2="13"></line>
                  <line x1="16" y1="17" x2="8" y2="17"></line>
                  <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
                Informations de la demande
              </h3>
            </div>
            <div class="p-6 space-y-4">
              <div class="flex flex-col md:flex-row md:items-center md:justify-between py-2 border-b border-gray-100">
                <span class="text-sm font-medium text-gray-500">Statut</span>
                <span class="px-3 py-1 text-sm font-medium rounded-full mt-1 md:mt-0 inline-flex items-center" :class="getStatusColor(demande.statut)">
                  <span class="h-1.5 w-1.5 rounded-full mr-1.5" :class="getStatusDotColor(demande.statut)"></span>
                  {{ demande.statut }}
                </span>
              </div>

              <div class="flex flex-col md:flex-row md:items-center md:justify-between py-2 border-b border-gray-100">
                <span class="text-sm font-medium text-gray-500">Structure</span>
                <span class="font-medium text-gray-900">{{ demande.structure ? demande.structure.libelle : 'Non spécifiée' }}</span>
              </div>

              <div class="flex flex-col md:flex-row md:items-center md:justify-between py-2 border-b border-gray-100">
                <span class="text-sm font-medium text-gray-500">Type de stage</span>
                <span class="font-medium text-gray-900">{{ demande.type }}</span>
              </div>

              <div class="flex flex-col md:flex-row md:items-center md:justify-between py-2 border-b border-gray-100">
                <span class="text-sm font-medium text-gray-500">Nature</span>
                <span class="font-medium text-gray-900">{{ demande.nature }}</span>
              </div>

              <div class="flex flex-col md:flex-row md:items-center md:justify-between py-2 border-b border-gray-100">
                <span class="text-sm font-medium text-gray-500">Période</span>
                <span class="font-medium text-gray-900">Du {{ formatDate(demande.date_debut) }} au {{ formatDate(demande.date_fin) }}</span>
              </div>

              <div class="flex flex-col md:flex-row md:items-center md:justify-between py-2 border-b border-gray-100">
                <span class="text-sm font-medium text-gray-500">Date de soumission</span>
                <span class="font-medium text-gray-900">{{ formatDate(demande.created_at) }}</span>
              </div>

              <div v-if="demande.date_traitement" class="flex flex-col md:flex-row md:items-center md:justify-between py-2 border-b border-gray-100">
                <span class="text-sm font-medium text-gray-500">Date de traitement</span>
                <span class="font-medium text-gray-900">{{ formatDate(demande.date_traitement) }}</span>
              </div>

              <div v-if="demande.motif_rejet" class="mt-4 p-3 bg-red-50 rounded-md border border-red-100">
                <span class="text-sm font-medium text-red-600 block mb-1">Motif de rejet</span>
                <span class="text-red-700">{{ demande.motif_rejet }}</span>
              </div>
            </div>
          </div>

          <!-- Informations du stagiaire -->
          <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-800 py-4 px-6">
              <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                  <circle cx="12" cy="7" r="4"></circle>
                </svg>
                Informations du stagiaire
              </h3>
            </div>
            <div class="p-6">
              <div class="mb-6 flex items-center">
                <div class="h-16 w-16 bg-blue-600 text-white rounded-full flex items-center justify-center text-xl font-bold shadow-md mr-4">
                  {{ getInitials(demande.stagiaire?.user?.nom, demande.stagiaire?.user?.prenom) }}
                </div>
                <div>
                  <h4 class="text-lg font-semibold text-gray-900">{{ demande.stagiaire?.user?.nom }} {{ demande.stagiaire?.user?.prenom }}</h4>
                  <p class="text-sm text-gray-500">{{ demande.stagiaire?.user?.email }}</p>
                </div>
              </div>

              <div class="space-y-4">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between py-2 border-b border-gray-100">
                  <span class="text-sm font-medium text-gray-500">Téléphone</span>
                  <span class="font-medium text-gray-900">{{ demande.stagiaire?.user?.telephone }}</span>
                </div>

                <div class="flex flex-col md:flex-row md:items-center md:justify-between py-2 border-b border-gray-100">
                  <span class="text-sm font-medium text-gray-500">Université</span>
                  <span class="font-medium text-gray-900">{{ demande.stagiaire?.universite }}</span>
                </div>

                <div class="flex flex-col md:flex-row md:items-center md:justify-between py-2 border-b border-gray-100">
                  <span class="text-sm font-medium text-gray-500">Filière</span>
                  <span class="font-medium text-gray-900">{{ demande.stagiaire?.filiere }}</span>
                </div>

                <div class="flex flex-col md:flex-row md:items-center md:justify-between py-2 border-b border-gray-100">
                  <span class="text-sm font-medium text-gray-500">Niveau d'étude</span>
                  <span class="font-medium text-gray-900">{{ demande.stagiaire?.niveau_etude }}</span>
                </div>
              </div>
              
              <div v-if="demande.nature === 'Groupe' && membres && membres.length > 0" class="mt-8">
                <h4 class="text-lg font-semibold text-blue-700 mb-4 pb-2 border-b border-blue-100">Autres membres du groupe</h4>
                <div class="space-y-3">
                  <div v-for="membre in membres" :key="membre.id" class="bg-blue-50 p-4 rounded-md border border-blue-100">
                    <div class="flex items-center mb-2">
                      <div class="h-8 w-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-medium mr-3">
                        {{ getInitials(membre.user?.nom, membre.user?.prenom) }}
                      </div>
                      <div class="font-semibold text-gray-800">{{ membre.user?.nom }} {{ membre.user?.prenom }}</div>
                    </div>
                    <div class="pl-11 grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                      <div class="text-gray-600">Email: <span class="text-gray-900">{{ membre.user?.email }}</span></div>
                      <div class="text-gray-600">Téléphone: <span class="text-gray-900">{{ membre.user?.telephone }}</span></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Documents attachés -->
        <div class="mt-6 bg-white rounded-lg shadow-md overflow-hidden">
          <div class="border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-800 py-4 px-6">
            <h3 class="text-lg font-semibold text-white flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
              </svg>
              Documents attachés
            </h3>
          </div>

          <div class="p-6">
            <div v-if="demande.lettre_cv_path" class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div class="bg-blue-50 rounded-lg p-4 border border-blue-100 hover:shadow-md transition-shadow flex flex-col">
                <span class="text-sm font-medium text-blue-600 mb-2">{{ demande.type === 'Académique' ? 'Lettre de recommandation' : 'CV' }}</span>
                <a :href="'/storage/' + demande.lettre_cv_path"
                  target="_blank"
                  class="mt-auto flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-sm font-medium">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                  </svg>
                  Télécharger
                </a>
              </div>
            </div>
            <div v-else class="text-center py-6">
              <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
              <p class="mt-2 text-sm text-gray-500">Aucun document n'a été joint à cette demande</p>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div v-if="demande.statut === 'En cours' || demande.statut === 'Encours'" class="mt-6 bg-white rounded-lg shadow-md overflow-hidden">
          <div class="border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-800 py-4 px-6">
            <h3 class="text-lg font-semibold text-white flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="16"></line>
                <line x1="8" y1="12" x2="16" y2="12"></line>
              </svg>
              Actions disponibles
            </h3>
          </div>
          <div class="p-6">
            <div class="flex flex-col sm:flex-row gap-4">
              <button
                @click="showApproveModal = true"
                class="px-4 py-3 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 transition-colors flex items-center justify-center gap-2 font-medium"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                Accepter la demande
              </button>
              <button
                @click="showRejectModal = true"
                class="px-4 py-3 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors flex items-center justify-center gap-2 font-medium"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
                Rejeter la demande
              </button>
            </div>
          </div>
        </div>
        
        <div v-if="demande.statut === 'Acceptée'" class="mt-6 bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl shadow-blue-100/20 overflow-hidden border border-white/20 animate-fade-in-up hover:shadow-2xl hover:shadow-blue-100/30 transition-all duration-500" style="animation-delay: 0.3s">
          <div class="bg-gradient-to-r from-blue-600 via-blue-700 to-blue-800 py-6 px-8 relative overflow-hidden">
            <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
            <h3 class="text-xl font-bold text-white flex items-center gap-3 relative z-10">
              <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="10"></circle>
                  <line x1="12" y1="8" x2="12" y2="16"></line>
                  <line x1="8" y1="12" x2="16" y2="12"></line>
                </svg>
              </div>
              Actions disponibles
            </h3>
          </div>
          <div class="p-8">
            <button
              @click="openMaitreStageModal"
              class="group px-8 py-4 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-xl hover:from-indigo-700 hover:to-indigo-800 transition-all duration-300 flex items-center gap-3 font-bold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:scale-110 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              Affecter un maître de stage
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modales modernisées -->
    <!-- Modal de rejet -->
    <Modal :show="showRejectModal" @close="closeRejectModal">
      <div class="p-8 bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl">
        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
          <div class="p-3 bg-red-100 rounded-xl">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
          </div>
          Rejeter la demande
        </h2>
        <div class="mb-6">
          <label class="block text-sm font-bold text-gray-700 mb-3 uppercase tracking-wide">
            Motif du rejet
          </label>
          <textarea
            v-model="rejectForm.motif_refus"
            rows="5"
            class="w-full rounded-xl border-gray-200 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-gray-900 font-medium"
            placeholder="Veuillez expliquer en détail le motif du rejet..."
          ></textarea>
          <p v-if="rejectForm.errors.motif_refus" class="mt-3 text-sm text-red-600">
            {{ rejectForm.errors.motif_refus }}
          </p>
        </div>
        <div class="flex justify-end gap-4">
          <button
            @click="closeRejectModal"
            class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl border border-gray-200 hover:bg-gray-200 transition-all duration-300 font-medium"
          >
            Annuler
          </button>
          <button
            @click="rejectDemande"
            class="px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 transition-all duration-300 flex items-center gap-2 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
            :disabled="rejectForm.processing"
          >
            <svg v-if="rejectForm.processing" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Confirmer le rejet
          </button>
        </div>
      </div>
    </Modal>

    <!-- Modal de confirmation d'approbation -->
    <Modal :show="showApproveModal" @close="closeApproveModal">
      <div class="p-8 bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl">
        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
          <div class="p-3 bg-emerald-100 rounded-xl">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
          </div>
          Confirmer l'approbation
        </h2>
        <p class="text-sm text-gray-600 mb-6">
          Êtes-vous sûr de vouloir approuver cette demande de stage ? Cette action enverra une notification au stagiaire.
        </p>
        <div class="flex justify-end gap-4">
          <button
            @click="closeApproveModal"
            class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl border border-gray-200 hover:bg-gray-200 transition-all duration-300 font-medium"
          >
            Annuler
          </button>
          <button
            @click="approveDemande"
            class="px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white rounded-xl hover:from-emerald-700 hover:to-emerald-800 transition-all duration-300 flex items-center gap-2 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
            :disabled="approveForm.processing"
          >
            <svg v-if="approveForm.processing" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Confirmer l'approbation
          </button>
        </div>
      </div>
    </Modal>

    <!-- Modal d'affectation de maître de stage -->
    <Modal :show="showMaitreStageModal" @close="closeMaitreStageModal">
      <div class="p-8 bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl">
        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
          <div class="p-3 bg-indigo-100 rounded-xl">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
              <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
            </svg>
          </div>
          Affecter un maître de stage
        </h2>
        <div class="mb-6">
          <label class="block text-sm font-bold text-gray-700 mb-3 uppercase tracking-wide">
            Sélectionner un agent avec le rôle MS
          </label>
          <select
            v-model="selectedMaitreStageId"
            class="w-full rounded-xl border-gray-200 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-gray-900 font-medium"
          >
            <option value="" disabled>Sélectionner un maître de stage</option>
            <option v-for="agent in maitreStageAgents" :key="agent.id" :value="agent.id">
              {{ agent.user?.nom }} {{ agent.user?.prenom }}
              {{ agent.structure_responsable ? '- Responsable de: ' + agent.structure_responsable.libelle : '' }}
            </option>
          </select>
          <p v-if="maitreStageForm.errors.maitre_stage_id" class="mt-3 text-sm text-red-600">
            {{ maitreStageForm.errors.maitre_stage_id }}
          </p>
        </div>
        <div class="flex justify-end gap-4">
          <button
            @click="closeMaitreStageModal"
            class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl border border-gray-200 hover:bg-gray-200 transition-all duration-300 font-medium"
          >
            Annuler
          </button>
          <button
            @click="submitMaitreStage"
            class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-xl hover:from-indigo-700 hover:to-indigo-800 transition-all duration-300 flex items-center gap-2 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
            :disabled="maitreStageForm.processing || !selectedMaitreStageId"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
            Affecter le maître de stage
          </button>
          <button
            @click="fetchMaitreStageAgents"
            class="px-6 py-3 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-xl hover:from-yellow-600 hover:to-yellow-700 transition-all duration-300 flex items-center gap-2 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
            type="button"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
            </svg>
            Rafraîchir la liste
          </button>
        </div>

        <div v-if="maitreStageAgents.length === 0" class="mt-6 p-6 bg-gradient-to-r from-yellow-50 to-yellow-100/50 rounded-xl border border-yellow-200/50">
          <div class="flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <span class="text-yellow-800 font-medium">Aucun agent avec le rôle MS n'a été trouvé. Veuillez vérifier que des agents avec ce rôle existent dans le système.</span>
          </div>
        </div>
      </div>
    </Modal>
  </RSLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import RSLayout from '@/Layouts/RSLayout.vue';
import Modal from '@/Components/Modal.vue';
import Toast from '@/Components/Toast.vue';
import axios from 'axios';

const props = defineProps({
  demande: Object,
  membres: Array,
});

const showRejectModal = ref(false);
const showApproveModal = ref(false);
const toast = ref(null);
const showMaitreStageModal = ref(false);
const selectedMaitreStageId = ref(null);
const maitresStage = ref([]);
const maitreStageAgents = ref([]);

const rejectForm = useForm({
  motif_refus: '',
});

const approveForm = useForm({});
const maitreStageForm = useForm({
  maitre_stage_id: '',
});

function formatDate(date) {
  return new Date(date).toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });
}

function getStatusColor(status) {
  switch (status) {
    case 'En attente':
      return 'bg-amber-50 text-amber-700';
    case 'En cours':
    case 'Encours':
      return 'bg-blue-50 text-blue-700';
    case 'Acceptée':
    case 'Approuvée':
      return 'bg-emerald-50 text-emerald-700';
    case 'Refusée':
      return 'bg-red-50 text-red-700';
    default:
      return 'bg-gray-50 text-gray-700';
  }
}

function getStatusDotColor(status) {
  switch (status) {
    case 'En attente':
      return 'bg-amber-500';
    case 'En cours':
    case 'Encours':
      return 'bg-blue-500';
    case 'Acceptée':
    case 'Approuvée':
      return 'bg-emerald-500';
    case 'Refusée':
      return 'bg-red-500';
    default:
      return 'bg-gray-500';
  }
}

function getInitials(nom, prenom) {
  if (!nom || !prenom) return '?';
  return `${nom.charAt(0)}${prenom.charAt(0)}`.toUpperCase();
}

function closeRejectModal() {
  showRejectModal.value = false;
  rejectForm.reset();
}

function rejectDemande() {
  rejectForm.post(route('agent.rs.demandes.reject', props.demande.id), {
    onSuccess: () => {
      closeRejectModal();
      if (toast.value) {
        toast.value.show({
          type: 'success',
          title: 'Succès',
          message: 'La demande a été rejetée avec succès.'
        });
      }
    },
    onError: () => {
      if (toast.value) {
        toast.value.show({
          type: 'error',
          title: 'Erreur',
          message: 'Une erreur est survenue lors du rejet de la demande.'
        });
      }
    }
  });
}

function closeApproveModal() {
  showApproveModal.value = false;
}

function approveDemande() {
  approveForm.post(route('agent.rs.demandes.approve', props.demande.id), {
    onSuccess: () => {
      closeApproveModal();
      if (toast.value) {
        toast.value.show({
          type: 'success',
          title: 'Succès',
          message: 'La demande a été approuvée avec succès.'
        });
      }
    },
    onError: () => {
      if (toast.value) {
        toast.value.show({
          type: 'error',
          title: 'Erreur',
          message: 'Une erreur est survenue lors de l\'approbation de la demande.'
        });
      }
    }
  });
}

function openMaitreStageModal() {
  fetchMaitreStageAgents();
  showMaitreStageModal.value = true;
}

function closeMaitreStageModal() {
  showMaitreStageModal.value = false;
  selectedMaitreStageId.value = '';
  maitreStageForm.reset();
}

async function fetchMaitreStageAgents() {
  try {
    const response = await axios.get(route('agent.rs.responsable-agents'));
    maitreStageAgents.value = response.data;
  } catch (error) {
    console.error('Erreur lors de la récupération des maîtres de stage:', error);
  }
}

function submitMaitreStage() {
  if (!selectedMaitreStageId.value) return;

  maitreStageForm.maitre_stage_id = selectedMaitreStageId.value;

  maitreStageForm.post(route('agent.rs.demandes.affecter-maitre', props.demande.id), {
    onSuccess: () => {
      closeMaitreStageModal();
      router.reload();
    },
    onError: (errors) => {
      console.error('Erreur lors de l\'affectation du maître de stage:', errors);
    }
  });
}

onMounted(() => {
  if (props.demande?.statut === 'Acceptée') {
    fetchMaitreStageAgents();
  }
});
</script>

<style scoped>
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

@keyframes slideInRight {
  from {
    opacity: 0;
    transform: translateX(20px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

.animate-fade-in {
  animation: fadeIn 0.5s ease-out forwards;
}

.animate-fade-in-up {
  animation: fadeInUp 0.6s ease-out forwards;
}

.animate-slide-in-right {
  animation: slideInRight 0.5s ease-out forwards;
}

/* Ajout d'un effet de glassmorphism subtil */
.backdrop-blur-sm {
  backdrop-filter: blur(8px);
}

/* Amélioration des transitions */
.transition-all {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 300ms;
}

/* Amélioration des ombres */
.shadow-xl {
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.shadow-2xl {
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

/* Amélioration des hover states */
.hover\:shadow-xl:hover {
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.hover\:shadow-2xl:hover {
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

/* Amélioration des gradients */
.bg-gradient-to-r {
  background-size: 200% 200%;
  animation: gradient 15s ease infinite;
}

@keyframes gradient {
  0% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
  100% {
    background-position: 0% 50%;
  }
}
</style>