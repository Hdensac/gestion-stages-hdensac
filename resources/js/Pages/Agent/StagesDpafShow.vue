<template>
  <AgentDPAF>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-slate-50 to-gray-100">
      <!-- Header Section -->
      <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
          <div class="flex items-center gap-4 mb-4">
            <div class="bg-gradient-to-br from-blue-600 to-blue-700 text-white rounded-xl w-16 h-16 flex items-center justify-center shadow-lg">
              <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6" />
              </svg>
            </div>
            <div>
              <h1 class="text-2xl font-bold text-gray-800 leading-tight md:text-3xl tracking-tight">Détails du Stage</h1>
              <p class="text-sm text-gray-600 mt-1 font-medium">Vue DPAF – Informations détaillées du stage</p>
            </div>
          </div>
          
          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex flex-wrap items-center gap-4">
              <div class="bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 px-4 py-2 rounded-xl font-bold text-sm shadow-sm border border-blue-200">
                🔖 Stage #{{ stage.id }}
              </div>
              <span class="px-4 py-2 text-sm font-semibold rounded-xl inline-flex items-center gap-2 shadow-sm" :class="getStatusStyle(stage.statut)">
                {{ stage.statut }}
              </span>
            </div>
            <Link :href="route('agent.stages-dpaf.index')" class="px-6 py-3 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-200 shadow-sm flex items-center gap-2 font-medium">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
              <span>Retour à la liste</span>
            </Link>
          </div>
        </div>
      </div>

      <!-- Main Content -->
      <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          
          <!-- LIGNE 1: Informations du stage + Informations du stagiaire -->
          <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-8">
            <!-- Bloc infos stage -->
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 transform-gpu">
              <div class="bg-gradient-to-r from-blue-600 via-blue-700 to-blue-800 px-8 py-6">
                <h2 class="text-xl font-bold text-white flex items-center gap-3">
                  <div class="bg-white/20 p-2 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6m9 4a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h7l5 5v10z" />
                    </svg>
                  </div>
                  Informations du stage
                </h2>
              </div>
              <div class="p-8 space-y-6">
                <div class="flex items-center gap-4 p-6 bg-gradient-to-r from-blue-50 to-blue-100/50 rounded-2xl border border-blue-200">
                  <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-3 rounded-xl shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                  </div>
                  <div>
                    <div class="text-sm font-bold text-blue-700 uppercase tracking-wide">Période du stage</div>
                    <div class="font-bold text-gray-800 text-lg">Du {{ formatDate(stage.date_debut) }}</div>
                    <div class="font-bold text-gray-800 text-lg">au {{ formatDate(stage.date_fin) }}</div>
                  </div>
                </div>
                <div class="border-t border-gray-100 pt-6">
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3">
                      <div class="text-sm font-bold text-gray-600 uppercase tracking-wider flex items-center gap-2">
                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                        Structure
                      </div>
                      <div class="font-bold text-gray-800 text-base bg-white/80 px-4 py-3 rounded-xl border border-gray-100 shadow-sm">
                        {{ stage.structure ? stage.structure.libelle : 'Non spécifiée' }}
                      </div>
                    </div>
                    <div class="space-y-3">
                      <div class="text-sm font-bold text-gray-600 uppercase tracking-wider flex items-center gap-2">
                        <div class="w-3 h-3 bg-emerald-500 rounded-full"></div>
                        Type de stage
                      </div>
                      <div class="font-bold text-gray-800 text-base bg-white/80 px-4 py-3 rounded-xl border border-gray-100 shadow-sm">
                        {{ stage.type === 'professionnel' ? 'Professionnel' : stage.type === 'academique' ? 'Académique' : stage.type }}
                      </div>
                    </div>
                    <div class="space-y-3">
                      <div class="text-sm font-bold text-gray-600 uppercase tracking-wider flex items-center gap-2">
                        <div class="w-3 h-3 bg-purple-500 rounded-full"></div>
                        Thème
                      </div>
                      <div class="font-bold text-gray-800 text-base bg-white/80 px-4 py-3 rounded-xl border border-gray-100 shadow-sm">
                        {{ stage.theme_stage ? stage.theme_stage.intitule : 'Non défini' }}
                      </div>
                    </div>
                    <div class="space-y-3">
                      <div class="text-sm font-bold text-gray-600 uppercase tracking-wider flex items-center gap-2">
                        <div class="w-3 h-3 bg-pink-500 rounded-full"></div>
                        Nature
                      </div>
                      <div class="font-bold text-gray-800 text-base bg-white/80 px-4 py-3 rounded-xl border border-gray-100 shadow-sm">
                        {{ stage.demande_stage?.nature ? stage.demande_stage.nature : 'Non renseignée' }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Bloc infos stagiaire -->
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 transform-gpu">
              <div class="bg-gradient-to-r from-emerald-600 via-emerald-700 to-emerald-800 px-8 py-6">
                <h2 class="text-xl font-bold text-white flex items-center gap-3">
                  <div class="bg-white/20 p-2 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                  </div>
                  Informations du stagiaire
                </h2>
              </div>
              <div v-if="stage.stagiaire && stage.stagiaire.user" class="p-8 space-y-6">
                <div class="flex items-center gap-4 p-6 bg-gradient-to-r from-emerald-50 to-emerald-100/50 rounded-2xl border border-emerald-200">
                  <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 p-3 rounded-xl shadow-lg">
                    <span class="text-white text-xl font-bold uppercase">
                      {{ stage.stagiaire.user.nom.charAt(0) + stage.stagiaire.user.prenom.charAt(0) }}
                    </span>
                  </div>
                  <div>
                    <div class="font-bold text-gray-800 text-xl">{{ stage.stagiaire.user.nom }} {{ stage.stagiaire.user.prenom }}</div>
                    <div class="text-sm text-emerald-700 font-medium">{{ stage.stagiaire.user.email }}</div>
                  </div>
                </div>
                <div class="border-t border-gray-100 pt-6">
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3">
                      <div class="text-sm font-bold text-gray-600 uppercase tracking-wider flex items-center gap-2">
                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                        Téléphone
                      </div>
                      <div class="font-bold text-gray-800 text-base bg-white/80 px-4 py-3 rounded-xl border border-gray-100 shadow-sm">
                        {{ stage.stagiaire.user.telephone || 'Non renseigné' }}
                      </div>
                    </div>
                    <div class="space-y-3">
                      <div class="text-sm font-bold text-gray-600 uppercase tracking-wider flex items-center gap-2">
                        <div class="w-3 h-3 bg-purple-500 rounded-full"></div>
                        Filière
                      </div>
                      <div class="font-bold text-gray-800 text-base bg-white/80 px-4 py-3 rounded-xl border border-gray-100 shadow-sm">
                        {{ stage.stagiaire.filiere || 'Non renseignée' }}
                      </div>
                    </div>
                    <div class="space-y-3">
                      <div class="text-sm font-bold text-gray-600 uppercase tracking-wider flex items-center gap-2">
                        <div class="w-3 h-3 bg-amber-500 rounded-full"></div>
                        Niveau d'étude
                      </div>
                      <div class="font-bold text-gray-800 text-base bg-white/80 px-4 py-3 rounded-xl border border-gray-100 shadow-sm">
                        {{ stage.stagiaire.niveau_etude || 'Non renseigné' }}
                      </div>
                    </div>
                    <div class="space-y-3">
                      <div class="text-sm font-bold text-gray-600 uppercase tracking-wider flex items-center gap-2">
                        <div class="w-3 h-3 bg-pink-500 rounded-full"></div>
                        Université
                      </div>
                      <div class="font-bold text-gray-800 text-base bg-white/80 px-4 py-3 rounded-xl border border-gray-100 shadow-sm">
                        {{ stage.stagiaire.universite || 'Non renseignée' }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div v-else class="p-8 text-center">
                <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                  <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                  </svg>
                </div>
                <p class="text-gray-500 font-medium">Informations du stagiaire non disponibles</p>
              </div>
            </div>
          </div>

          <!-- LIGNE 2: Maître de Stage + Suivi du Stage -->
          <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
            <!-- Bloc Maître de Stage -->
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 transform-gpu">
              <div class="bg-gradient-to-r from-purple-600 via-purple-700 to-purple-800 px-8 py-6">
                <h2 class="text-xl font-bold text-white flex items-center gap-3">
                  <div class="bg-white/20 p-2 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                  </div>
                  Maître de Stage
                </h2>
              </div>
              <div class="p-8">
                <div v-if="maitre_stage" class="space-y-6">
                  <div class="flex items-center gap-4 p-6 bg-gradient-to-r from-purple-50 to-purple-100/50 rounded-2xl border border-purple-200">
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-3 rounded-xl shadow-lg">
                      <span class="text-white text-xl font-bold uppercase">
                        {{ maitre_stage.nom.charAt(0) + maitre_stage.prenom.charAt(0) }}
                      </span>
                    </div>
                    <div>
                      <div class="font-bold text-gray-800 text-xl">{{ maitre_stage.nom }} {{ maitre_stage.prenom }}</div>
                      <div class="text-sm text-purple-700 font-medium">{{ maitre_stage.email }}</div>
                    </div>
                  </div>
                  <div class="border-t border-gray-100 pt-6">
                    <div class="grid grid-cols-1 gap-6">
                      <div class="space-y-3">
                        <div class="text-sm font-bold text-gray-600 uppercase tracking-wider flex items-center gap-2">
                          <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                          Téléphone
                        </div>
                        <div class="font-bold text-gray-800 text-base bg-white/80 px-4 py-3 rounded-xl border border-gray-100 shadow-sm">
                          {{ maitre_stage.telephone || 'Non renseigné' }}
                        </div>
                      </div>
                      <div class="space-y-3">
                        <div class="text-sm font-bold text-gray-600 uppercase tracking-wider flex items-center gap-2">
                          <div class="w-3 h-3 bg-emerald-500 rounded-full"></div>
                          Fonction
                        </div>
                        <div class="font-bold text-gray-800 text-base bg-white/80 px-4 py-3 rounded-xl border border-gray-100 shadow-sm">
                          <template v-if="maitre_stage.structure && maitre_stage.structure.libelle">
                            Chef de service {{ maitre_stage.structure.libelle }}
                          </template>
                          <template v-else>
                            Non renseignée
                          </template>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div v-else class="text-center py-12">
                  <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                  </div>
                  <p class="text-gray-500 font-medium">Maître de stage non assigné</p>
                </div>
              </div>
            </div>

            <!-- Bloc Suivi du Stage -->
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 transform-gpu">
              <div class="bg-gradient-to-r from-amber-600 via-amber-700 to-orange-700 px-8 py-6">
                <h2 class="text-xl font-bold text-white flex items-center gap-3">
                  <div class="bg-white/20 p-2 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                  </div>
                  Suivi du Stage
                </h2>
              </div>
              <div class="p-6 space-y-6">
                <!-- Barre de progression -->
                <div class="space-y-3">
                  <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">Progression</span>
                    <span class="text-sm font-bold text-amber-600">{{ getProgressPercentage() }}%</span>
                  </div>
                  <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                    <div class="bg-gradient-to-r from-amber-500 to-orange-600 h-3 rounded-full transition-all duration-500 ease-out"
                      :style="{ width: getProgressPercentage() + '%' }"></div>
                  </div>
                </div>

                <!-- Informations temporelles -->
                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-100">
                  <div class="text-center p-3 bg-gray-50 rounded-xl">
                    <div class="text-xs text-gray-500 font-medium uppercase">Durée totale</div>
                    <div class="text-lg font-bold text-gray-800">{{ getDureeStage() }} jours</div>
                  </div>
                  <div class="text-center p-3 bg-gray-50 rounded-xl">
                    <div class="text-xs text-gray-500 font-medium uppercase">Temps restant</div>
                    <div class="text-lg font-bold text-gray-800">{{ getTempsRestant() }} jours</div>
                  </div>
                </div>

                <!-- Statut avec évaluation -->
                <!-- Pour les stages individuels -->
                <div v-if="stage.evaluation && stage.demande_stage?.nature !== 'Groupe'"
                     class="mt-4 p-4 bg-gradient-to-r from-emerald-50 to-emerald-100 rounded-xl border border-emerald-200">
                  <div class="flex items-center justify-between">
                    <div>
                      <div class="text-xs font-bold text-emerald-700 uppercase">Évaluation</div>
                      <div class="text-lg font-bold text-emerald-800">{{ stage.evaluation.note_totale }}/20</div>
                    </div>
                    <div class="text-2xl">⭐</div>
                  </div>
                </div>

                <!-- Pour les stages en groupe -->
                <div v-if="stage.demande_stage?.nature === 'Groupe' && membres_groupe?.some(m => m.evaluation)"
                     class="mt-4 p-4 bg-gradient-to-r from-emerald-50 to-emerald-100 rounded-xl border border-emerald-200">
                  <div class="flex items-center justify-between mb-3">
                    <div>
                      <div class="text-xs font-bold text-emerald-700 uppercase">Évaluations du groupe</div>
                      <div class="text-sm text-emerald-600">
                        Moyenne: {{ calculateGroupAverage() }}/20
                      </div>
                    </div>
                    <div class="text-2xl">⭐</div>
                  </div>

                  <!-- Liste des notes individuelles en grille 2 colonnes -->
                  <div class="grid grid-cols-1 gap-2">
                    <div v-for="membre in membres_groupe.filter(m => m.evaluation)"
                         :key="membre.user.id"
                         class="flex items-center justify-between text-sm bg-white/50 rounded-lg px-3 py-2">
                      <span class="font-medium text-emerald-700 truncate mr-2">
                        {{ membre.user.prenom }} {{ membre.user.nom }}
                      </span>
                      <span class="font-bold text-emerald-800">
                        {{ membre.evaluation.note_totale }}/20
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Bloc Membres du groupe EN DEHORS de la grille -->
        <div v-if="stage.demande_stage?.nature === 'Groupe' && membres_groupe?.length"
          class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100/50 mt-8 max-w-2xl mx-auto">
          <!-- En-tête -->
          <div class="bg-gradient-to-r from-pink-600 via-pink-700 to-pink-800 px-6 py-4">
            <h2 class="text-lg font-semibold text-white flex items-center gap-3">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M16 3.13a4 4 0 010 7.75M8 3.13a4 4 0 000 7.75" />
              </svg>
              Membres du groupe ({{ membres_groupe.length }} {{ membres_groupe.length > 1 ? 'stagiaires' : 'stagiaire' }})
            </h2>
          </div>

          <!-- Contenu -->
          <div class="p-6">
            <div class="space-y-4">
              <div v-for="membre in membres_groupe" :key="membre.user.id"
                class="bg-gradient-to-r from-pink-50 to-pink-100/50 p-4 rounded-xl border border-pink-200/50 hover:shadow-md transition-all duration-200">
                <div class="flex items-center gap-3">
                  <div class="bg-gradient-to-br from-pink-500 to-pink-600 p-2 rounded-lg flex-shrink-0">
                    <span class="text-white text-sm font-bold uppercase">
                      {{ membre.user.prenom.charAt(0) + membre.user.nom.charAt(0) }}
                    </span>
                  </div>
                  <div class="flex-1 min-w-0">
                    <div class="font-bold text-gray-800 text-sm truncate">
                      {{ membre.user.prenom }} {{ membre.user.nom }}
                    </div>
                    <div class="text-xs text-pink-600 font-medium truncate">
                      {{ membre.user.email }}
                    </div>
                    <div v-if="membre.evaluation" class="text-xs text-emerald-600 font-bold mt-1">
                      Note: {{ membre.evaluation.note_totale }}/20
                    </div>
                    <div v-else class="text-xs text-gray-500 mt-1">
                      Non évalué
                    </div>
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

<script>
import AgentDPAF from '@/Layouts/AgentDPAF.vue';

export default {
  components: {
    AgentDPAF
  },
  props: {
    stage: Object,
    maitre_stage: Object,
    suivis: Array,
    membres_groupe: Array
  },
  methods: {
    formatDate(date) {
      if (!date) return 'Non définie';
      return new Date(date).toLocaleDateString('fr-FR', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      });
    },
    getStatusStyle(status) {
      const styles = {
        'actif': 'bg-green-100 text-green-800 border border-green-200',
        'terminé': 'bg-gray-100 text-gray-800 border border-gray-200',
        'en_cours': 'bg-blue-100 text-blue-800 border border-blue-200',
        'suspendu': 'bg-yellow-100 text-yellow-800 border border-yellow-200',
        'annulé': 'bg-red-100 text-red-800 border border-red-200'
      };
      return styles[status] || 'bg-gray-100 text-gray-800 border border-gray-200';
    },
    getProgressPercentage() {
      if (!this.stage.date_debut || !this.stage.date_fin) return 0;

      const now = new Date();
      const debut = new Date(this.stage.date_debut);
      const fin = new Date(this.stage.date_fin);

      if (now < debut) return 0;
      if (now > fin) return 100;

      const totalDuration = fin - debut;
      const elapsed = now - debut;

      return Math.round((elapsed / totalDuration) * 100);
    },
    getDureeStage() {
      if (!this.stage.date_debut || !this.stage.date_fin) return 0;

      const debut = new Date(this.stage.date_debut);
      const fin = new Date(this.stage.date_fin);
      const diffTime = Math.abs(fin - debut);
      const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

      return diffDays;
    },
    getTempsRestant() {
      if (!this.stage.date_fin) return 0;

      const now = new Date();
      const fin = new Date(this.stage.date_fin);

      if (now > fin) return 0;

      const diffTime = Math.abs(fin - now);
      const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

      return diffDays;
    },
    calculateGroupAverage() {
      if (!this.membres_groupe || this.membres_groupe.length === 0) return 0;

      const evaluatedMembers = this.membres_groupe.filter(m => m.evaluation && m.evaluation.note_totale);
      if (evaluatedMembers.length === 0) return 0;

      const total = evaluatedMembers.reduce((sum, membre) => sum + parseFloat(membre.evaluation.note_totale), 0);
      return (total / evaluatedMembers.length).toFixed(1);
    }
  }
}
</script>

<style scoped>
/* Stabilisation des éléments lors du scroll */
.transform-gpu {
  transform: translateZ(0);
  backface-visibility: hidden;
}

/* Amélioration des transitions */
.transition-all {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 200ms;
}

/* Amélioration des performances de rendu */
* {
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

/* Responsive amélioré */
@media (max-width: 640px) {
  .px-8 {
    padding-left: 1.5rem;
    padding-right: 1.5rem;
  }
  
  .py-8 {
    padding-top: 1.5rem;
    padding-bottom: 1.5rem;
  }
}
</style>