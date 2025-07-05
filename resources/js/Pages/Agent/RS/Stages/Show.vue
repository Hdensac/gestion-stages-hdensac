<template>
  <RSLayout>
    <template #header>
      <div class="flex items-center gap-4 mb-2">
        <div class="bg-gradient-to-br from-blue-600 to-blue-700 text-white rounded-xl w-16 h-16 flex items-center justify-center shadow-lg transform transition-transform hover:scale-105">
          <ClipboardDocumentIcon class="w-8 h-8" />
        </div>
        <div>
          <h1 class="text-2xl font-bold text-gray-800 leading-tight md:text-3xl tracking-tight">Détails du Stage</h1>
          <p class="text-sm text-gray-600 mt-1 font-medium">
            Gérez les informations du stage et les affectations des maîtres de stage
          </p>
        </div>
      </div>
    </template>

    <!-- Composant Toast pour les notifications -->
    <Toast ref="toast" />

    <!-- Fond gris léger pour toute la page -->
    <div class="min-h-screen bg-gray-50 py-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Navigation et identifiant du stage avec design moderne -->
        <div class="mb-8 flex flex-wrap justify-between items-center gap-6">
          <div class="flex items-center gap-4">
            <div class="bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 px-4 py-2 rounded-xl font-bold text-sm shadow-sm border border-blue-200">
              🔖 Stage #{{ stage.id }}
            </div>
            <div class="flex items-center">
              <span 
                class="px-4 py-2 text-sm font-semibold rounded-xl inline-flex items-center gap-2 shadow-sm"
                :class="getStatusStyle(stage.statut)"
              >
                <span v-if="stage.statut === 'En cours'" class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 bg-blue-400"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                </span>
                <span v-else-if="stage.statut === 'Terminé'" class="w-2 h-2 bg-green-500 rounded-full"></span>
                <span v-else class="w-2 h-2 bg-gray-400 rounded-full"></span>
                {{ stage.statut }}
              </span>
            </div>
          </div>
          
          <Link 
            :href="route('agent.rs.stages')" 
            class="px-6 py-3 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-200 shadow-sm hover:shadow-md flex items-center gap-2 font-medium transform hover:scale-105"
          >
            <ArrowLeftIcon class="w-5 h-5" />
            <span>Retour à la liste</span>
          </Link>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
          <!-- Informations sur le stage avec design moderne -->
          <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100/50 backdrop-blur-sm">
            <div class="bg-gradient-to-r from-blue-600 via-blue-700 to-blue-800 px-6 py-4 relative overflow-hidden">
              <div class="absolute inset-0 bg-black/5"></div>
              <div class="relative">
                <h2 class="text-lg font-semibold text-white flex items-center gap-3">
                  <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                    <DocumentTextIcon class="w-5 h-5" />
                  </div>
                  Informations du stage
                </h2>
              </div>
            </div>
            
            <div class="p-6 space-y-6">
              <!-- Période du stage avec design accrocheur -->
              <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-blue-50 to-blue-100/50 rounded-2xl border border-blue-200/50 shadow-sm">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-3 rounded-xl shadow-md">
                  <CalendarIcon class="w-6 h-6 text-white" />
                </div>
                <div>
                  <div class="text-xs font-semibold text-blue-600 uppercase tracking-wide">Période du stage</div>
                  <div class="font-bold text-gray-800 text-lg">
                    Du {{ formatDate(stage.date_debut) }}
                  </div>
                  <div class="font-bold text-gray-800 text-lg">
                    au {{ formatDate(stage.date_fin) }}
                  </div>
                </div>
              </div>
              
              <!-- Autres informations en grille moderne -->
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
                      {{ stage.type }}
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
                  
                  <!-- <div v-if="stage.evaluation" class="space-y-2">
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center gap-2">
                      <div class="w-2 h-2 bg-amber-500 rounded-full"></div>
                      Évaluation du MS
                    </div>
                    <div class="flex items-center gap-2">
                      <div class="bg-gradient-to-r from-amber-400 to-amber-500 text-white px-3 py-2 rounded-lg font-bold text-sm shadow-md">
                        ⭐ {{ stage.evaluation.note_totale }}/20
                      </div>
                      <div class="text-xs text-gray-500">{{ getGradeDescription(stage.evaluation.note_totale) }}</div>
                    </div>
                  </div> -->
                </div>
              </div>
            </div>
          </div>

          <!-- Informations du stagiaire avec design moderne -->
          <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100/50 backdrop-blur-sm">
            <div class="bg-gradient-to-r from-emerald-600 via-emerald-700 to-emerald-800 px-6 py-4 relative overflow-hidden">
              <div class="absolute inset-0 bg-black/5"></div>
              <div class="relative">
                <h2 class="text-lg font-semibold text-white flex items-center gap-3">
                  <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                    <UserIcon class="w-5 h-5" />
                  </div>
                  Informations du stagiaire
                </h2>
              </div>
            </div>
            
            <div class="p-6" v-if="stage.demande_stage?.stagiaire?.user">
              <!-- Profil du stagiaire -->
              <div class="flex items-center gap-4 mb-6 p-4 bg-gradient-to-r from-emerald-50 to-emerald-100/50 rounded-2xl border border-emerald-200/50">
                <div class="h-16 w-16 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg">
                  <UserIcon class="h-8 w-8 text-white" />
                </div>
                <div class="flex-1">
                  <div class="font-bold text-gray-800 text-lg">
                    {{ stage.demande_stage.stagiaire.user.nom }} {{ stage.demande_stage.stagiaire.user.prenom }}
                  </div>
                  <div class="text-sm text-emerald-600 font-medium flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    {{ stage.demande_stage.stagiaire.user.email }}
                  </div>
                </div>
              </div>
              
              <!-- Détails académiques -->
              <div class="border-t border-gray-100 pt-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                  <div class="space-y-2">
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center gap-2">
                      <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                      Téléphone
                    </div>
                    <div class="font-semibold text-gray-800 text-sm bg-gray-50 px-3 py-2 rounded-lg flex items-center gap-2">
                      <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                      </svg>
                      {{ stage.demande_stage.stagiaire.user.telephone }}
                    </div>
                  </div>
                  
                  <div class="space-y-2" v-if="stage.type === 'academique'">
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center gap-2">
                      <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                      Université
                    </div>
                    <div class="font-semibold text-gray-800 text-sm bg-gray-50 px-3 py-2 rounded-lg">
                      {{ stage.demande_stage.stagiaire.universite }}
                    </div>
                  </div>
                  
                  <div class="space-y-2">
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center gap-2">
                      <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                      Filière
                    </div>
                    <div class="font-semibold text-gray-800 text-sm bg-gray-50 px-3 py-2 rounded-lg">
                      {{ stage.demande_stage.stagiaire.filiere }}
                    </div>
                  </div>
                  
                  <div class="space-y-2">
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center gap-2">
                      <div class="w-2 h-2 bg-amber-500 rounded-full"></div>
                      Niveau d'étude
                    </div>
                    <div class="font-semibold text-gray-800 text-sm bg-gray-50 px-3 py-2 rounded-lg">
                      {{ stage.demande_stage.stagiaire.niveau_etude }}
                    </div>
                  </div>
                </div>
              </div>

              <!-- Autres membres du groupe -->
              <div v-if="props.membres_groupe && props.membres_groupe.length" class="mt-8">
                <h3 class="text-md font-bold text-emerald-700 mb-4 flex items-center gap-2">
                  <UserGroupIcon class="w-5 h-5" />
                  Autres membres du groupe
                </h3>
                <ul class="space-y-4">
                  <li
                    v-for="membre in props.membres_groupe.filter(m => m.user && m.user.id !== stage.demande_stage.stagiaire.user.id)"
                    :key="membre.user.id"
                    class="bg-emerald-50 border border-emerald-100 rounded-xl p-4 flex flex-col sm:flex-row sm:items-center sm:gap-6"
                  >
                    <div class="flex-1">
                      <div class="font-semibold text-gray-800 text-base">{{ membre.user.nom }} {{ membre.user.prenom }}</div>
                      <div class="text-xs text-emerald-600 font-medium">{{ membre.user.email }}</div>
                      <div class="text-xs text-gray-500 mt-1">Téléphone : <span class="font-semibold">{{ membre.user.telephone || 'N/A' }}</span></div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
            
            <div v-else class="p-6 text-center">
              <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <UserIcon class="w-8 h-8 text-gray-400" />
              </div>
              <p class="text-gray-500 font-medium">Informations du stagiaire non disponibles</p>
            </div>
          </div>
        </div>

        <!-- Historique des affectations avec design moderne -->
        <div class="mt-8 bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100/50 backdrop-blur-sm">
          <div class="bg-gradient-to-r from-purple-600 via-purple-700 to-purple-800 px-6 py-4 flex justify-between items-center relative overflow-hidden">
            <div class="absolute inset-0 bg-black/5"></div>
            <div class="relative">
              <h2 class="text-lg font-semibold text-white flex items-center gap-3">
                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                  <UserGroupIcon class="w-5 h-5" />
                </div>
                Historique des affectations
                <span class="bg-white/20 text-white px-2 py-1 rounded-lg text-sm font-bold">
                  {{ stage.affectations_maitre_stage ? stage.affectations_maitre_stage.length : 0 }}
                </span>
              </h2>
            </div>
            
           
          </div>

          <div class="p-6">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                  <thead class="bg-gradient-to-r from-gray-50 to-gray-100/50">
                    <tr>
                      <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        <div class="flex items-center gap-2">
                          <CalendarIcon class="w-4 h-4" />
                          Date d'affectation
                        </div>
                      </th>
                      <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        <div class="flex items-center gap-2">
                          <UserIcon class="w-4 h-4" />
                          Maître de stage
                        </div>
                      </th>
                      <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        <div class="flex items-center gap-2">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                          </svg>
                          Affecté par
                        </div>
                      </th>
                      <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        <div class="flex items-center gap-2">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                          </svg>
                          Statut
                        </div>
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-50">
                    <tr v-for="affectation in stage.affectations_maitre_stage" :key="affectation.id" class="hover:bg-gray-50/50 transition-all duration-200 group">
                      <td class="px-6 py-5 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-700 flex items-center gap-3">
                          <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                          {{ formatDate(affectation.date_affectation) }}
                        </div>
                      </td>
                      <td class="px-6 py-5 whitespace-nowrap">
                        <div v-if="affectation.maitre_stage">
                          <div class="flex items-center gap-2">
                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-purple-200 text-purple-700">
                              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                              </svg>
                            </span>
                            <div>
                              <div class="font-bold text-gray-900">
                                <span v-if="affectation.maitre_stage.nom || affectation.maitre_stage.prenom">
                                  {{ affectation.maitre_stage.nom }} {{ affectation.maitre_stage.prenom }}
                                </span>
                                <span v-else class="italic text-gray-400">Non renseigné</span>
                              </div>
                              <div v-if="affectation.maitre_stage.email" class="text-xs text-gray-500">
                              {{ affectation.maitre_stage.email }}
                              </div>
                            </div>
                          </div>
                        </div>
                        <div v-else class="italic text-gray-400">Aucun maître de stage</div>
                      </td>
                      <td class="px-6 py-5 whitespace-nowrap">
                        <div v-if="affectation.agent_affectant?.user" class="flex items-center gap-3">
                          <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                          </div>
                          <div class="text-sm font-medium text-gray-700">
                            {{ affectation.agent_affectant.user.nom }} {{ affectation.agent_affectant.user.prenom }}
                          </div>
                        </div>
                        <div v-else class="text-gray-400 italic text-sm flex items-center gap-2">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                          </svg>
                          Non disponible
                        </div>
                      </td>
                      <td class="px-6 py-5 whitespace-nowrap">
                        <span
                          class="px-3 py-2 text-xs font-semibold rounded-xl shadow-sm"
                          :class="getAffectationStatusStyle(affectation.statut)"
                        >
                          {{ getStatutAffichage(affectation.statut) }}
                        </span>
                      </td>
                    </tr>
                    <tr v-if="!stage.affectations_maitre_stage || stage.affectations_maitre_stage.length === 0">
                      <td colspan="4" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center justify-center space-y-4">
                          <div class="w-20 h-20 bg-purple-100 rounded-2xl flex items-center justify-center">
                            <UserGroupIcon class="w-10 h-10 text-purple-400" />
                          </div>
                          <div class="space-y-2">
                            <p class="text-gray-600 font-medium">Aucune affectation trouvée</p>
                            <p class="text-gray-400 text-sm">Ce stage n'a pas encore de maître de stage assigné</p>
                          </div>
                          <button 
                            class="px-6 py-3 bg-gradient-to-r from-purple-50 to-purple-100 text-purple-700 rounded-xl hover:from-purple-100 hover:to-purple-200 transition-all duration-200 font-medium flex items-center gap-2 shadow-sm hover:shadow-md transform hover:scale-105"
                            @click="affecterMaitreStage"
                          >
                            <PlusIcon class="w-5 h-5" />
                            Affecter un maître de stage
                          </button>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <div v-if="props.membres_groupe && props.membres_groupe.length" class="mt-10">
          <h2 class="text-xl font-bold text-purple-700 mb-6 flex items-center gap-3">
            <UserGroupIcon class="w-7 h-7" />
            Évaluation des membres du groupe
          </h2>
          <div class="space-y-6">
            <div v-for="membre in props.membres_groupe" :key="membre.user.id" class="p-6 rounded-2xl bg-purple-50 border-2 border-purple-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4 shadow-md">
              <div class="flex items-center gap-4">
                <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shadow-lg">
                  <span class="text-white text-xl font-bold uppercase">{{ membre.user.nom.charAt(0) + membre.user.prenom.charAt(0) }}</span>
                </div>
                <div>
                  <div class="font-bold text-gray-800 text-lg">{{ membre.user.nom }} {{ membre.user.prenom }}</div>
                  <div class="text-xs text-purple-600 font-medium">Stagiaire</div>
                  <div class="text-xs text-gray-500 mt-1">Statut du stage :
                    <span class="inline-block px-3 py-1 rounded-xl font-semibold text-xs ml-1"
                      :class="getStatusStyle(membre.stage ? membre.stage.statut : '')">
                      {{ membre.stage ? membre.stage.statut : 'Non défini' }}
                    </span>
                  </div>
                </div>
              </div>
              <div class="flex items-center gap-4">
                <div v-if="membre.evaluation" class="bg-gradient-to-r from-emerald-200 to-emerald-300 text-emerald-800 px-4 py-2 rounded-lg font-bold text-md shadow">
                  Note : {{ membre.evaluation.note_totale }}/20
                </div>
                <div v-else class="text-gray-400 italic">Non évalué</div>
                <button
                  v-if="membre.stage && membre.stage.termine_par_ms == 1"
                  @click="imprimerAttestation(membre)"
                  class="px-5 py-2 bg-gradient-to-r from-indigo-500 to-blue-600 text-white rounded-xl font-bold shadow hover:from-indigo-600 hover:to-blue-700 transition-all duration-200 flex items-center gap-2"
                >
                  <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                  Imprimer l'attestation
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
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
import { ref } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import RSLayout from '@/Layouts/RSLayout.vue';
import Toast from '@/Components/Toast.vue';
import { 
  ClipboardDocumentIcon, 
  UserIcon, 
  CalendarIcon, 
  DocumentTextIcon, 
  UserGroupIcon,
  PlusIcon,
  ArrowLeftIcon
} from '@heroicons/vue/24/outline';
import Modal from '@/Components/Modal.vue';
import axios from 'axios';

const props = defineProps({
  stage: Object,
  structure: Object,
  membres_groupe: Array,
});

const toast = ref(null);
const showMaitreStageModal = ref(false);
const selectedMaitreStageId = ref(null);
const maitreStageAgents = ref([]);
const maitreStageForm = useForm({
  maitre_stage_id: '',
});

// Formater une date avec un style plus moderne
function formatDate(date) {
  if (!date) return 'Non définie';
  return new Date(date).toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  });
}

// Obtenir le style du statut du stage
function getStatusStyle(status) {
  switch (status) {
    case 'En cours':
      return 'bg-gradient-to-r from-blue-50 to-blue-100 text-blue-700 border border-blue-200';
    case 'Terminé':
      return 'bg-gradient-to-r from-emerald-50 to-emerald-100 text-emerald-700 border border-emerald-200';
    default:
      return 'bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700 border border-gray-200';
  }
}

// Obtenir le style du statut de l'affectation
function getAffectationStatusStyle(status) {
  switch (status) {
    case 'En cours':
      return 'bg-gradient-to-r from-blue-50 to-blue-100 text-blue-700 border border-blue-200';
    case 'Acceptée':
      return 'bg-gradient-to-r from-emerald-50 to-emerald-100 text-emerald-700 border border-emerald-200';
    case 'Réaffectée':
      return 'bg-gradient-to-r from-amber-50 to-yellow-100 text-amber-700 border border-amber-200';
    case 'Terminée':
      return 'bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700 border border-gray-200';
    case 'Refusée':
      return 'bg-gradient-to-r from-red-50 to-red-100 text-red-700 border border-red-200';
    default:
      return 'bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700 border border-gray-200';
  }
}

// Obtenir la description de la note
function getGradeDescription(note) {
  if (note >= 18) return 'Excellent';
  if (note >= 16) return 'Très bien';
  if (note >= 14) return 'Bien';
  if (note >= 12) return 'Assez bien';
  if (note >= 10) return 'Passable';
  return 'Insuffisant';
}

// Fonction utilitaire pour afficher le bon statut dans l'historique
function getStatutAffichage(statut) {
  if (statut === 'Acceptée') return 'En cours';
  if (statut === 'Réaffectée') return 'Réaffectée';
  if (statut === 'Terminée') return 'Terminée';
  return statut;
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
  maitreStageForm.post(route('agent.rs.stages.affecter-maitre', props.stage.id), {
    onSuccess: () => {
      closeMaitreStageModal();
      setTimeout(() => {
        router.reload();
      }, 250);
    },
    onError: (errors) => {
      console.error('Erreur lors de l\'affectation du maître de stage:', errors);
    }
  });
}

// Remplace l'appel router.visit par l'ouverture du modal dans le bouton d'affectation :
const affecterMaitreStage = () => {
  openMaitreStageModal();
};

function imprimerAttestation(membre) {
  if (!membre || !membre.stage) return;
  // Redirige vers une route d'impression d'attestation pour le membre
  window.open(route('agent.rs.stages.attestation', membre.stage.id), '_blank');
}
</script>