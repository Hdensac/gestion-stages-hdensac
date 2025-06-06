<template>
  <Head title="Détails de la demande" />

  <RSLayout>
    <template #header>
      <div class="flex items-center gap-4 mb-2">
        <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-600 text-white rounded-2xl w-16 h-16 flex items-center justify-center shadow-xl shadow-blue-500/30">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
        </div>
        <div>
          <h1 class="text-3xl font-black bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent leading-tight">Détails de la demande</h1>
          <p class="text-base text-slate-600 mt-1 font-mono bg-slate-200/70 px-3 py-1 rounded-lg inline-block">Code: {{ demande.code_suivi }}</p>
        </div>
      </div>
    </template>

    <!-- Composant Toast pour les notifications -->
    <Toast ref="toast" />

    <!-- Arrière-plan grisé -->
    <div class="min-h-screen bg-gradient-to-br from-gray-100 via-slate-100 to-gray-200">
      <div class="py-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <!-- Bouton retour amélioré -->
          <div class="mb-8 flex justify-end">
            <Link :href="route('agent.rs.demandes')" 
                class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-slate-600 to-slate-700 text-white rounded-xl hover:from-slate-700 hover:to-slate-800 transition-all duration-300 shadow-lg hover:shadow-xl font-bold transform hover:scale-105">
              <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Retour à la liste
          </Link>
        </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Informations sur la demande - thème bleu -->
            <div class="bg-gradient-to-br from-blue-50 via-indigo-50 to-blue-50 rounded-3xl shadow-xl border-2 border-blue-200/50 overflow-hidden">
              <div class="bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 py-6 px-8 relative overflow-hidden">
                <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
                <h3 class="text-xl font-bold text-white flex items-center gap-3 relative z-10">
                  <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                  <polyline points="14 2 14 8 20 8"></polyline>
                  <line x1="16" y1="13" x2="8" y2="13"></line>
                  <line x1="16" y1="17" x2="8" y2="17"></line>
                  <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
                  </div>
                Informations de la demande
              </h3>
            </div>
              <div class="p-8 space-y-5">
                <div class="bg-white/80 p-5 rounded-2xl border border-blue-200/50 shadow-sm">
                  <span class="text-sm text-blue-700 font-bold block mb-3">Statut</span>
                  <span class="px-4 py-2 text-sm font-bold rounded-2xl inline-flex items-center shadow-lg" :class="getEnhancedStatusColor(demande.statut)">
                    <span class="h-2 w-2 rounded-full mr-2" :class="getStatusDotColor(demande.statut)"></span>
                  {{ getStatutAffichageRS(demande.statut) }}
                </span>
              </div>

                <div class="bg-white/80 p-5 rounded-2xl border border-blue-200/50 shadow-sm">
                  <span class="text-sm text-blue-700 font-bold block mb-2">Structure</span>
                  <div class="flex flex-col gap-3">
                    <div class="font-bold text-slate-800 text-lg">
                      {{ demande.structure ? demande.structure.libelle : 'Non spécifiée' }}
                      <span v-if="demande.affectations && demande.affectations.length > 0" class="text-sm font-normal text-blue-600">
                        (Structure d'affectation)
                      </span>
                    </div>

                  </div>
              </div>

                <div class="bg-white/80 p-5 rounded-2xl border border-blue-200/50 shadow-sm">
                  <span class="text-sm text-blue-700 font-bold block mb-2">Type de stage</span>
                  <span class="font-bold text-slate-800">{{ demande.type }}</span>
              </div>

                <div class="bg-white/80 p-5 rounded-2xl border border-blue-200/50 shadow-sm">
                  <span class="text-sm text-blue-700 font-bold block mb-2">Nature</span>
                  <span class="font-bold text-slate-800">{{ demande.nature }}</span>
              </div>

                <div class="bg-white/80 p-5 rounded-2xl border border-blue-200/50 shadow-sm">
                  <span class="text-sm text-blue-700 font-bold block mb-2">Période</span>
                  <span class="font-bold text-slate-800">Du {{ formatDate(demande.date_debut) }} au {{ formatDate(demande.date_fin) }}</span>
              </div>

                <div class="bg-white/80 p-5 rounded-2xl border border-blue-200/50 shadow-sm">
                  <span class="text-sm text-blue-700 font-bold block mb-2">Date de soumission</span>
                  <span class="font-bold text-slate-800">{{ formatDate(demande.created_at) }}</span>
              </div>

                <div v-if="demande.date_traitement" class="bg-white/80 p-5 rounded-2xl border border-blue-200/50 shadow-sm">
                  <span class="text-sm text-blue-700 font-bold block mb-2">Date de traitement</span>
                  <span class="font-bold text-slate-800">{{ formatDate(demande.date_traitement) }}</span>
              </div>

                <div v-if="demande.motif_rejet" class="bg-gradient-to-r from-red-100 to-rose-100 p-5 rounded-2xl border-2 border-red-300/50 shadow-lg">
                  <span class="text-sm font-bold text-red-700 block mb-3">Motif de rejet</span>
                  <span class="text-red-800 font-medium">{{ demande.motif_rejet }}</span>
                </div>
            </div>
          </div>

            <!-- Informations du stagiaire - thème violet -->
            <div class="bg-gradient-to-br from-purple-50 via-pink-50 to-purple-50 rounded-3xl shadow-xl border-2 border-purple-200/50 overflow-hidden">
              <div class="bg-gradient-to-r from-purple-600 via-purple-700 to-pink-700 py-6 px-8 relative overflow-hidden">
                <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
                <h3 class="text-xl font-bold text-white flex items-center gap-3 relative z-10">
                  <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                  <circle cx="12" cy="7" r="4"></circle>
                </svg>
                  </div>
                Informations du stagiaire
              </h3>
            </div>
              <div class="p-8">
                <!-- Photo et nom du stagiaire -->
                <div class="mb-8 flex items-center bg-white/80 p-6 rounded-2xl border border-purple-200/50 shadow-lg">
                  <div class="h-20 w-20 rounded-2xl flex items-center justify-center text-xl font-bold shadow-xl mr-6 overflow-hidden bg-gradient-to-br from-purple-600 to-pink-600 text-white">
                    <img v-if="demande.visage_path"
                         :src="'/storage/' + demande.visage_path"
                         alt="Photo du stagiaire"
                         class="object-cover w-full h-full rounded-2xl">
                    <div v-else class="w-full h-full flex items-center justify-center">
                  {{ getInitials(demande.stagiaire?.user?.nom, demande.stagiaire?.user?.prenom) }}
                    </div>
                </div>
                <div>
                    <h4 class="text-xl font-bold text-purple-900">{{ demande.stagiaire?.user?.nom }} {{ demande.stagiaire?.user?.prenom }}</h4>
                    <p class="text-base text-purple-700 font-medium mt-1">{{ demande.stagiaire?.user?.email }}</p>
                  </div>
              </div>

              <div class="space-y-4">
                  <div class="bg-white/80 p-5 rounded-2xl border border-purple-200/50 shadow-sm">
                    <span class="text-sm text-purple-700 font-bold block mb-2">Téléphone</span>
                    <span class="font-bold text-slate-800">{{ demande.stagiaire?.user?.telephone }}</span>
                </div>

                  <div class="bg-white/80 p-5 rounded-2xl border border-purple-200/50 shadow-sm">
                    <span class="text-sm text-purple-700 font-bold block mb-2">Université</span>
                    <span class="font-bold text-slate-800">{{ demande.stagiaire?.universite }}</span>
                </div>

                  <div class="bg-white/80 p-5 rounded-2xl border border-purple-200/50 shadow-sm">
                    <span class="text-sm text-purple-700 font-bold block mb-2">Filière</span>
                    <span class="font-bold text-slate-800">{{ demande.stagiaire?.filiere }}</span>
                </div>

                  <div class="bg-white/80 p-5 rounded-2xl border border-purple-200/50 shadow-sm">
                    <span class="text-sm text-purple-700 font-bold block mb-2">Niveau d'étude</span>
                    <span class="font-bold text-slate-800">{{ demande.stagiaire?.niveau_etude }}</span>
                  </div>
              </div>
              
                <!-- Membres du groupe - thème vert -->
              <div v-if="demande.nature === 'Groupe' && membres && membres.length > 0" class="mt-8">
                  <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl shadow-lg">
                      <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                      </svg>
                    </div>
                    <h4 class="text-lg font-bold text-emerald-800">Autres membres du groupe</h4>
                  </div>
                  <div class="space-y-4">
                    <div v-for="membre in membres" :key="membre.id" class="bg-gradient-to-r from-emerald-100 to-teal-100 p-6 rounded-2xl border-2 border-emerald-200/50 shadow-lg">
                      <div class="flex items-center mb-3">
                        <div class="h-12 w-12 bg-gradient-to-br from-emerald-600 to-teal-600 text-white rounded-xl flex items-center justify-center text-sm font-bold mr-4 shadow-lg">
                        {{ getInitials(membre.user?.nom, membre.user?.prenom) }}
                        </div>
                        <div class="font-bold text-emerald-900 text-lg">{{ membre.user?.nom }} {{ membre.user?.prenom }}</div>
                      </div>
                      <div class="pl-16 grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                        <div class="text-emerald-700 font-medium">Email: <span class="text-emerald-900 font-bold">{{ membre.user?.email }}</span></div>
                        <div class="text-emerald-700 font-medium">Téléphone: <span class="text-emerald-900 font-bold">{{ membre.user?.telephone }}</span></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

          <!-- Documents attachés - thème orange -->
          <div class="mt-8 bg-gradient-to-br from-orange-50 via-amber-50 to-orange-50 rounded-3xl shadow-xl border-2 border-orange-200/50 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-600 via-orange-700 to-amber-700 py-6 px-8 relative overflow-hidden">
              <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
              <h3 class="text-xl font-bold text-white flex items-center gap-3 relative z-10">
                <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                  <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
              </svg>
                </div>
              Documents attachés
            </h3>
          </div>

            <div class="p-8">
              <div v-if="demande.lettre_cv_path" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white/80 rounded-2xl p-6 border-2 border-orange-200/50 shadow-lg hover:shadow-xl transition-all duration-300 flex flex-col">
                  <span class="text-sm font-bold text-orange-700 mb-4 block">{{ demande.type === 'Académique' ? 'Lettre de recommandation' : 'CV' }}</span>
                <a :href="'/storage/' + demande.lettre_cv_path"
                  target="_blank"
                    class="mt-auto flex items-center justify-center gap-2 px-5 py-3 bg-gradient-to-r from-orange-600 to-amber-600 text-white rounded-xl hover:from-orange-700 hover:to-amber-700 transition-all duration-300 shadow-lg hover:shadow-xl font-bold transform hover:scale-105">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                  </svg>
                  Télécharger
                </a>
              </div>
            </div>
              <div v-else class="text-center py-12 bg-white/80 rounded-2xl border-2 border-orange-200/50 shadow-lg">
                <div class="flex flex-col items-center gap-4">
                  <div class="p-4 bg-gradient-to-br from-gray-400 to-gray-500 rounded-2xl shadow-lg">
                    <svg class="w-12 h-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
                  </div>
                  <p class="text-lg text-gray-600 font-medium">Aucun document n'a été joint à cette demande</p>
                </div>
              </div>
          </div>
        </div>

          <!-- Actions - thème vert -->
          <div v-if="demande.statut === 'En cours' || demande.statut === 'Encours'" class="mt-8 bg-gradient-to-br from-green-50 via-emerald-50 to-green-50 rounded-3xl shadow-xl border-2 border-green-200/50 overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 via-green-700 to-emerald-700 py-6 px-8 relative overflow-hidden">
              <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
              <h3 class="text-xl font-bold text-white flex items-center gap-3 relative z-10">
                <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                  <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="16"></line>
                <line x1="8" y1="12" x2="16" y2="12"></line>
              </svg>
                </div>
              Actions disponibles
            </h3>
          </div>
            <div class="p-8">
              <div class="flex flex-col sm:flex-row gap-6">
              <button
                @click="showApproveModal = true"
                  class="px-6 py-4 bg-gradient-to-r from-emerald-600 to-green-600 text-white rounded-xl hover:from-emerald-700 hover:to-green-700 transition-all duration-300 shadow-lg hover:shadow-xl flex items-center justify-center gap-3 font-bold text-lg transform hover:scale-105"
              >
                  <svg class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                Accepter la demande
              </button>
              <button
                @click="showRejectModal = true"
                  class="px-6 py-4 bg-gradient-to-r from-red-600 to-rose-600 text-white rounded-xl hover:from-red-700 hover:to-rose-700 transition-all duration-300 shadow-lg hover:shadow-xl flex items-center justify-center gap-3 font-bold text-lg transform hover:scale-105"
              >
                  <svg class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
                Rejeter la demande
              </button>
            </div>
          </div>
        </div>
        
          <!-- Actions pour demande acceptée - thème indigo -->
          <div v-if="demande.statut === 'Acceptée' && !maitre_stage_deja_affecte" class="mt-8 bg-gradient-to-br from-indigo-50 via-blue-50 to-indigo-50 rounded-3xl shadow-xl border-2 border-indigo-200/50 overflow-hidden animate-fade-in-up hover:shadow-2xl transition-all duration-500">
            <div class="bg-gradient-to-r from-indigo-600 via-indigo-700 to-blue-700 py-6 px-8 relative overflow-hidden">
            <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
            <h3 class="text-xl font-bold text-white flex items-center gap-3 relative z-10">
                <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                  <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
                class="group px-8 py-4 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-xl hover:from-indigo-700 hover:to-blue-700 transition-all duration-300 flex items-center gap-3 font-bold text-lg shadow-lg hover:shadow-xl transform hover:scale-105"
            >
                <svg class="h-6 w-6 group-hover:scale-110 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              Affecter un maître de stage
            </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modales modernisées -->
    <!-- Modal de rejet -->
    <Modal :show="showRejectModal" @close="closeRejectModal">
      <div class="p-8 bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl border-2 border-slate-200/50">
        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
          <div class="p-3 bg-gradient-to-br from-red-500 to-rose-600 rounded-2xl shadow-lg">
            <svg class="h-6 w-6 text-white" viewBox="0 0 20 20" fill="currentColor">
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
            class="w-full rounded-2xl border-2 border-gray-200 shadow-sm focus:border-red-500 focus:ring-2 focus:ring-red-200 focus:ring-opacity-50 text-gray-900 font-medium p-4"
            placeholder="Veuillez expliquer en détail le motif du rejet..."
          ></textarea>
          <p v-if="rejectForm.errors.motif_refus" class="mt-3 text-sm text-red-600 font-medium">
            {{ rejectForm.errors.motif_refus }}
          </p>
        </div>
        <div class="flex justify-end gap-4">
          <button
            @click="closeRejectModal"
            class="px-6 py-3 bg-gradient-to-r from-gray-200 to-gray-300 text-gray-700 rounded-xl hover:from-gray-300 hover:to-gray-400 transition-all duration-300 font-bold transform hover:scale-105"
          >
            Annuler
          </button>
          <button
            @click="rejectDemande"
            class="px-6 py-3 bg-gradient-to-r from-red-600 to-rose-600 text-white rounded-xl hover:from-red-700 hover:to-rose-700 transition-all duration-300 flex items-center gap-2 font-bold shadow-lg hover:shadow-xl transform hover:scale-105"
            :disabled="rejectForm.processing"
          >
            <svg v-if="rejectForm.processing" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
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
      <div class="p-8 bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl border-2 border-slate-200/50">
        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
          <div class="p-3 bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl shadow-lg">
            <svg class="h-6 w-6 text-white" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
          </div>
          Confirmer l'approbation
        </h2>
        <p class="text-sm text-gray-600 mb-6 bg-emerald-50 p-4 rounded-xl border border-emerald-200">
          Êtes-vous sûr de vouloir approuver cette demande de stage ? Cette action enverra une notification au stagiaire.
        </p>
        <div class="flex justify-end gap-4">
          <button
            @click="closeApproveModal"
            class="px-6 py-3 bg-gradient-to-r from-gray-200 to-gray-300 text-gray-700 rounded-xl hover:from-gray-300 hover:to-gray-400 transition-all duration-300 font-bold transform hover:scale-105"
          >
            Annuler
          </button>
          <button
            @click="approveDemande"
            class="px-6 py-3 bg-gradient-to-r from-emerald-600 to-green-600 text-white rounded-xl hover:from-emerald-700 hover:to-green-700 transition-all duration-300 flex items-center gap-2 font-bold shadow-lg hover:shadow-xl transform hover:scale-105"
            :disabled="approveForm.processing"
          >
            <svg v-if="approveForm.processing" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
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
      <div class="p-8 bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl border-2 border-slate-200/50">
        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
          <div class="p-3 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-2xl shadow-lg">
            <svg class="h-6 w-6 text-white" viewBox="0 0 20 20" fill="currentColor">
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
            class="w-full rounded-2xl border-2 border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:ring-opacity-50 text-gray-900 font-medium p-4"
          >
            <option value="" disabled>Sélectionner un maître de stage</option>
            <option v-for="agent in maitreStageAgents" :key="agent.id" :value="agent.id">
              {{ agent.user?.nom }} {{ agent.user?.prenom }}
              {{ agent.structure_responsable ? '- Responsable de: ' + agent.structure_responsable.libelle : '' }}
            </option>
          </select>
          <p v-if="maitreStageForm.errors.maitre_stage_id" class="mt-3 text-sm text-red-600 font-medium">
            {{ maitreStageForm.errors.maitre_stage_id }}
          </p>
        </div>
        <div class="flex justify-end gap-4">
          <button
            @click="closeMaitreStageModal"
            class="px-6 py-3 bg-gradient-to-r from-gray-200 to-gray-300 text-gray-700 rounded-xl hover:from-gray-300 hover:to-gray-400 transition-all duration-300 font-bold transform hover:scale-105"
          >
            Annuler
          </button>
          <button
            @click="submitMaitreStage"
            class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-xl hover:from-indigo-700 hover:to-blue-700 transition-all duration-300 flex items-center gap-2 font-bold shadow-lg hover:shadow-xl transform hover:scale-105"
            :disabled="maitreStageForm.processing || !selectedMaitreStageId"
          >
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
            Affecter le maître de stage
          </button>
          <button
            @click="fetchMaitreStageAgents"
            type="button"
            class="px-6 py-3 bg-gradient-to-r from-yellow-500 to-amber-600 text-white rounded-xl hover:from-yellow-600 hover:to-amber-700 transition-all duration-300 flex items-center gap-2 font-bold shadow-lg hover:shadow-xl transform hover:scale-105"
          >
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
            </svg>
            Rafraîchir la liste
          </button>
        </div>

        <div v-if="maitreStageAgents.length === 0" class="mt-6 p-6 bg-gradient-to-r from-yellow-100 to-amber-100 rounded-2xl border-2 border-yellow-200/50 shadow-lg">
          <div class="flex items-center gap-3">
            <svg class="h-6 w-6 text-yellow-600" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <span class="text-yellow-800 font-bold">Aucun agent avec le rôle MS n'a été trouvé. Veuillez vérifier que des agents avec ce rôle existent dans le système.</span>
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
  maitre_stage_deja_affecte: Boolean,
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

// Fonction améliorée pour les couleurs de statut - plus vives
function getEnhancedStatusColor(status) {
  switch (status) {
    case 'En attente':
      return 'text-amber-800 bg-gradient-to-r from-amber-100 to-orange-100 border-2 border-amber-300';
    case 'En cours':
    case 'Encours':
      return 'text-blue-800 bg-gradient-to-r from-blue-100 to-sky-100 border-2 border-blue-300';
    case 'Acceptée':
    case 'Approuvée':
      return 'text-emerald-800 bg-gradient-to-r from-emerald-100 to-green-100 border-2 border-emerald-300';
    case 'Refusée':
      return 'text-red-800 bg-gradient-to-r from-red-100 to-rose-100 border-2 border-red-300';
    default:
      return 'text-slate-800 bg-gradient-to-r from-slate-100 to-gray-100 border-2 border-slate-300';
  }
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

function getStatutAffichageRS(statut) {
    if (statut === 'A réaffecter') return 'Refusée';
    return statut;
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