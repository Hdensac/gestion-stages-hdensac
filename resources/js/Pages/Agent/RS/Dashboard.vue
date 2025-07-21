<template>
  <Head title="Dashboard RS" />

  <RSLayout>
    <template #header>
      <div class="flex items-center gap-3 mb-2">
        <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-600 text-white rounded-xl w-12 h-12 flex items-center justify-center shadow-lg shadow-blue-500/20">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z" />
          </svg>
        </div>
        <div class="flex-1">
          <h1 class="text-2xl font-black bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent leading-tight">
            Tableau de bord RS
          </h1>
          <p class="text-sm text-slate-600 mt-1 font-medium">
            {{ structure ? `${structure.sigle} - ${structure.libelle}` : 'Gestion et suivi des demandes de stage' }}
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
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-xs font-bold">{{ stats.demandesEnAttente }} en attente</span>
          </div>
        </div>
      </div>
    </template>

    <!-- Arrière-plan grisé -->
    <div class="min-h-screen bg-gradient-to-br from-gray-100 via-slate-100 to-gray-200">
      <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
          <!-- Message d'erreur amélioré -->
          <div v-if="error" class="bg-gradient-to-r from-red-100 to-rose-100 border-2 border-red-300/50 text-red-800 p-6 rounded-3xl shadow-xl backdrop-blur-sm" role="alert">
            <div class="flex items-center gap-4">
              <div class="flex-shrink-0 p-3 bg-gradient-to-br from-red-500 to-rose-600 rounded-2xl shadow-lg">
                <svg class="h-6 w-6 text-white" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
              </div>
              <div>
                <h3 class="text-lg font-bold text-red-900">Erreur détectée</h3>
                <p class="text-base mt-1 font-medium">{{ error }}</p>
              </div>
            </div>
          </div>


          <!-- Statistiques améliorées avec plus de couleurs -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Demandes en attente - thème orange/amber -->
            <div class="group hover:scale-105 hover:-translate-y-2 transition-all duration-300">
              <div class="bg-gradient-to-br from-amber-50 via-orange-50 to-amber-50 rounded-3xl shadow-2xl hover:shadow-3xl border-2 border-amber-200/50 overflow-hidden relative">
                <div class="absolute inset-0 bg-gradient-to-br from-amber-500/5 to-orange-500/5"></div>
                <div class="relative p-8">
                  <div class="flex items-center justify-between mb-6">
                    <div class="p-4 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 text-white shadow-xl shadow-amber-500/30">
                      <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                    </div>
                    <div class="text-right">
                      <p class="text-4xl font-black text-slate-800 mb-2">{{ stats?.demandesEnAttente || 0 }}</p>
                      <div class="flex items-center gap-2 text-amber-700 text-base font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        En cours
                      </div>
                    </div>
                  </div>
                  <div>
                    <h4 class="text-xl font-bold text-amber-900 mb-2">En attente</h4>
                    <p class="text-base text-amber-700 font-medium">Demandes à traiter</p>
                  </div>
                </div>
                <div class="bg-gradient-to-r from-amber-100 to-orange-100 py-4 px-8 border-t-2 border-amber-200/50">
                  <span class="text-sm font-bold text-amber-800 flex items-center gap-3">
                    <div class="w-3 h-3 bg-amber-500 rounded-full animate-pulse shadow-lg"></div>
                    Requêtes en cours de traitement
                  </span>
                </div>
              </div>
            </div>
            
            <!-- Demandes acceptées - thème vert/emerald -->
            <div class="group hover:scale-105 hover:-translate-y-2 transition-all duration-300">
              <div class="bg-gradient-to-br from-emerald-50 via-green-50 to-emerald-50 rounded-3xl shadow-2xl hover:shadow-3xl border-2 border-emerald-200/50 overflow-hidden relative">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-green-500/5"></div>
                <div class="relative p-8">
                  <div class="flex items-center justify-between mb-6">
                    <div class="p-4 rounded-2xl bg-gradient-to-br from-emerald-500 to-green-600 text-white shadow-xl shadow-emerald-500/30">
                      <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                    </div>
                    <div class="text-right">
                      <p class="text-4xl font-black text-slate-800 mb-2">{{ stats?.demandesAcceptees || 0 }}</p>
                      <div class="flex items-center gap-2 text-emerald-700 text-base font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        Validées
                      </div>
                    </div>
                  </div>
                  <div>
                    <h4 class="text-xl font-bold text-emerald-900 mb-2">Acceptées</h4>
                    <p class="text-base text-emerald-700 font-medium">Demandes approuvées</p>
                  </div>
                </div>
                <div class="bg-gradient-to-r from-emerald-100 to-green-100 py-4 px-8 border-t-2 border-emerald-200/50">
                  <span class="text-sm font-bold text-emerald-800 flex items-center gap-3">
                    <div class="w-3 h-3 bg-emerald-500 rounded-full shadow-lg"></div>
                    Requêtes validées avec succès
                  </span>
                </div>
              </div>
            </div>
            
            <!-- Demandes rejetées - thème rouge/rose -->
            <div class="group hover:scale-105 hover:-translate-y-2 transition-all duration-300">
              <div class="bg-gradient-to-br from-red-50 via-rose-50 to-red-50 rounded-3xl shadow-2xl hover:shadow-3xl border-2 border-red-200/50 overflow-hidden relative">
                <div class="absolute inset-0 bg-gradient-to-br from-red-500/5 to-rose-500/5"></div>
                <div class="relative p-8">
                  <div class="flex items-center justify-between mb-6">
                    <div class="p-4 rounded-2xl bg-gradient-to-br from-red-500 to-rose-600 text-white shadow-xl shadow-red-500/30">
                      <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                    </div>
                    <div class="text-right">
                      <p class="text-4xl font-black text-slate-800 mb-2">{{ stats?.demandesRejetees || 0 }}</p>
                      <div class="flex items-center gap-2 text-red-700 text-base font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                        </svg>
                        Refusées
                      </div>
                    </div>
                  </div>
                  <div>
                    <h4 class="text-xl font-bold text-red-900 mb-2">Rejetées</h4>
                    <p class="text-base text-red-700 font-medium">Demandes refusées</p>
                  </div>
                </div>
                <div class="bg-gradient-to-r from-red-100 to-rose-100 py-4 px-8 border-t-2 border-red-200/50">
                  <span class="text-sm font-bold text-red-800 flex items-center gap-3">
                    <div class="w-3 h-3 bg-red-500 rounded-full shadow-lg"></div>
                    Requêtes non conformes
                  </span>
                </div>
              </div>
            </div>
          </div>



          <!-- Actions rapides -->
          <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl border-2 border-slate-200/50 overflow-hidden">
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
                <Link :href="route('agent.rs.demandes')"
                      class="group p-6 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl border-2 border-blue-200/50 hover:border-blue-300/50 hover:shadow-xl transition-all duration-300 hover:scale-105">
                  <div class="flex flex-col items-center text-center">
                    <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl text-white mb-4 group-hover:scale-110 transition-transform duration-300">
                      <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                      </svg>
                    </div>
                    <h4 class="font-bold text-blue-900 mb-1">Demandes</h4>
                    <p class="text-sm text-blue-700">Gérer les demandes</p>
                  </div>
                </Link>

                <Link :href="route('agent.rs.stages')"
                      class="group p-6 bg-gradient-to-br from-emerald-50 to-green-50 rounded-2xl border-2 border-emerald-200/50 hover:border-emerald-300/50 hover:shadow-xl transition-all duration-300 hover:scale-105">
                  <div class="flex flex-col items-center text-center">
                    <div class="p-3 bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl text-white mb-4 group-hover:scale-110 transition-transform duration-300">
                      <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                      </svg>
                    </div>
                    <h4 class="font-bold text-emerald-900 mb-1">Stages</h4>
                    <p class="text-sm text-emerald-700">Suivre les stages</p>
                  </div>
                </Link>

                <Link :href="route('agent.rs.attestations.index')"
                      class="group p-6 bg-gradient-to-br from-purple-50 to-indigo-50 rounded-2xl border-2 border-purple-200/50 hover:border-purple-300/50 hover:shadow-xl transition-all duration-300 hover:scale-105">
                  <div class="flex flex-col items-center text-center">
                    <div class="p-3 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl text-white mb-4 group-hover:scale-110 transition-transform duration-300">
                      <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                      </svg>
                    </div>
                    <h4 class="font-bold text-purple-900 mb-1">Attestations</h4>
                    <p class="text-sm text-purple-700">Générer attestations</p>
                  </div>
                </Link>

                <Link :href="route('agent.rs.organigramme.index')"
                      class="group p-6 bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl border-2 border-amber-200/50 hover:border-amber-300/50 hover:shadow-xl transition-all duration-300 hover:scale-105">
                  <div class="flex flex-col items-center text-center">
                    <div class="p-3 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl text-white mb-4 group-hover:scale-110 transition-transform duration-300">
                      <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                      </svg>
                    </div>
                    <h4 class="font-bold text-amber-900 mb-1">Structure</h4>
                    <p class="text-sm text-amber-700">Organigramme</p>
                  </div>
                </Link>
              </div>
            </div>
          </div>

          <!-- Dernières demandes - thème purple/indigo -->
          <div class="bg-gradient-to-br from-purple-50 via-indigo-50 to-purple-50 rounded-3xl shadow-2xl overflow-hidden border-2 border-purple-200/50 backdrop-blur-sm">
            <div class="bg-gradient-to-r from-purple-600 via-purple-700 to-indigo-700 py-8 px-10 relative overflow-hidden">
              <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
              <div class="absolute inset-0 opacity-20">
                <div class="w-full h-full" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.05&quot;%3E%3Ccircle cx=&quot;30&quot; cy=&quot;30&quot; r=&quot;4&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')"></div>
              </div>
              <div class="relative flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <h3 class="text-2xl font-bold text-white flex items-center gap-4">
                  <div class="p-3 bg-white/20 rounded-2xl backdrop-blur-sm shadow-lg">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                      <polyline points="14 2 14 8 20 8"></polyline>
                      <line x1="16" y1="13" x2="8" y2="13"></line>
                      <line x1="16" y1="17" x2="8" y2="17"></line>
                      <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                  </div>
                  Dernières demandes
                </h3>
                <Link 
                  :href="route('agent.rs.demandes')" 
                  class="group px-8 py-4 bg-white/15 hover:bg-white/25 text-white rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 font-bold text-base flex items-center gap-3 backdrop-blur-sm border-2 border-white/20 hover:border-white/30 transform hover:scale-105"
                >
                  <span>Voir toutes</span>
                  <svg class="h-5 w-5 transition-transform group-hover:translate-x-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                  </svg>
                </Link>
              </div>
            </div>

            <div v-if="dernieresDemandes.length === 0" class="py-20 px-10">
              <div class="max-w-lg mx-auto text-center">
                <div class="bg-white/80 rounded-3xl p-16 relative overflow-hidden border-2 border-purple-200/50 shadow-xl">
                  <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg%20width%3D%2260%22%20height%3D%2260%22%20viewBox%3D%220%200%2060%2060%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cg%20fill%3D%22none%22%20fill-rule%3D%22evenodd%22%3E%3Cg%20fill%3D%22%236366f1%22%20fill-opacity%3D%220.05%22%3E%3Ccircle%20cx%3D%2230%22%20cy%3D%2230%22%20r%3D%224%22/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
                  <div class="relative">
                    <div class="mx-auto h-20 w-20 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-3xl flex items-center justify-center mb-8 shadow-2xl shadow-purple-500/30">
                      <svg class="h-10 w-10 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                      </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-purple-900 mb-4">Aucune demande récente</h3>
                    <p class="text-purple-700 leading-relaxed text-lg font-medium">Les nouvelles demandes de stage apparaîtront ici dès qu'elles seront soumises</p>
                  </div>
                </div>
              </div>
            </div>

            <div v-else class="overflow-x-auto">
              <table class="min-w-full divide-y-2 divide-purple-200/50">
                <thead class="bg-gradient-to-r from-purple-100/80 to-indigo-100/80">
                  <tr>
                    <th scope="col" class="px-10 py-6 text-left text-sm font-black text-purple-800 uppercase tracking-wider">Stagiaire</th>
                    <th scope="col" class="px-10 py-6 text-left text-sm font-black text-purple-800 uppercase tracking-wider">Date de soumission</th>
                    <th scope="col" class="px-10 py-6 text-left text-sm font-black text-purple-800 uppercase tracking-wider">Date d'affectation</th>
                    <th scope="col" class="px-10 py-6 text-left text-sm font-black text-purple-800 uppercase tracking-wider">Statut</th>
                  </tr>
                </thead>
                <tbody class="bg-white/80 divide-y-2 divide-purple-100/50">
                  <tr v-for="demande in dernieresDemandes" :key="demande.id" class="hover:bg-gradient-to-r hover:from-purple-50 hover:to-indigo-50 transition-all duration-300 group">
                    <td class="px-10 py-8 whitespace-nowrap">
                      <div class="flex items-center">
                        <div class="h-16 w-16 rounded-2xl bg-gradient-to-br from-purple-600 to-indigo-700 text-white flex items-center justify-center font-bold shadow-xl group-hover:shadow-2xl transition-shadow duration-300 text-lg">
                          {{ getInitials(demande.stagiaire.user.nom, demande.stagiaire.user.prenom) }}
                        </div>
                        <div class="ml-6">
                          <div class="text-lg font-bold text-purple-900">
                            {{ demande.stagiaire.user.nom }} {{ demande.stagiaire.user.prenom }}
                          </div>
                          <div class="text-sm text-purple-600 font-medium">{{ demande.stagiaire.user.email }}</div>
                        </div>
                      </div>
                    </td>
                    <td class="px-10 py-8 whitespace-nowrap">
                      <div class="text-base font-bold text-slate-800">
                        {{ formatDate(demande.created_at) }}
                      </div>
                    </td>
                    <td class="px-10 py-8 whitespace-nowrap">
                      <div v-if="demande.date_affectation" class="text-base font-bold text-slate-800">
                        {{ formatDate(demande.date_affectation) }}
                      </div>
                      <div v-else class="text-base text-gray-400 italic font-medium">
                        Non affectée
                      </div>
                    </td>
                    <td class="px-10 py-8 whitespace-nowrap">
                      <span :class="getEnhancedStatusClass(demande.statut) + ' px-5 py-3 rounded-2xl text-sm font-bold inline-flex items-center shadow-lg'">
                        <span class="h-3 w-3 rounded-full mr-3 animate-pulse" :class="getStatusDotClass(demande.statut)"></span>
                        {{ demande.statut }}
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Section conseils et raccourcis -->
          <div class="bg-gradient-to-br from-indigo-50 via-blue-50 to-indigo-50 rounded-3xl shadow-2xl border-2 border-indigo-200/50 overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-600 to-blue-700 py-4 px-6">
              <h3 class="text-lg font-bold text-white flex items-center gap-3">
                <div class="p-2 bg-white/20 rounded-xl">
                  <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                </div>
                Conseils et bonnes pratiques
              </h3>
            </div>
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white/80 rounded-xl p-4 border border-indigo-200/50 hover:shadow-lg transition-all duration-300">
                  <div class="flex items-start gap-3">
                    <div class="p-2 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg text-white flex-shrink-0">
                      <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                      </svg>
                    </div>
                    <div>
                      <h4 class="font-bold text-indigo-900 mb-1 text-sm">Traitement rapide</h4>
                      <p class="text-xs text-indigo-700">Traitez les demandes en attente dans les 48h pour optimiser le processus.</p>
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
                      <h4 class="font-bold text-indigo-900 mb-1 text-sm">Affectation MS</h4>
                      <p class="text-xs text-indigo-700">N'oubliez pas d'affecter un maître de stage après acceptation de la demande.</p>
                    </div>
                  </div>
                </div>

                <div class="bg-white/80 rounded-xl p-4 border border-indigo-200/50 hover:shadow-lg transition-all duration-300">
                  <div class="flex items-start gap-3">
                    <div class="p-2 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-lg text-white flex-shrink-0">
                      <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                      </svg>
                    </div>
                    <div>
                      <h4 class="font-bold text-indigo-900 mb-1 text-sm">Attestations</h4>
                      <p class="text-xs text-indigo-700">Générez les attestations dès la fin du stage pour faciliter les démarches.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </RSLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import RSLayout from '@/Layouts/RSLayout.vue';

const props = defineProps({
  stats: {
    type: Object,
    default: () => ({
      demandesEnAttente: 0,
      demandesAcceptees: 0,
      demandesRejetees: 0
    })
  },
  structure: Object,
  dernieresDemandes: {
    type: Array,
    default: () => []
  },
  error: String,
});

function formatDate(date) {
  return new Date(date).toLocaleString('fr-FR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
}

// Fonction améliorée pour les couleurs de statut
function getEnhancedStatusClass(status) {
  switch (status) {
    case 'En attente':
      return 'bg-gradient-to-r from-amber-100 to-orange-100 text-amber-800 border-2 border-amber-300/50 shadow-amber-200/50';
    case 'En cours':
      return 'bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 border-2 border-blue-300/50 shadow-blue-200/50';
    case 'Acceptée':
      return 'bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-800 border-2 border-emerald-300/50 shadow-emerald-200/50';
    case 'Refusée':
      return 'bg-gradient-to-r from-red-100 to-rose-100 text-red-800 border-2 border-red-300/50 shadow-red-200/50';
    default:
      return 'bg-gradient-to-r from-gray-100 to-slate-100 text-gray-800 border-2 border-gray-300/50 shadow-gray-200/50';
  }
}



function getStatusDotClass(status) {
  switch (status) {
    case 'En attente':
      return 'bg-amber-500';
    case 'En cours':
      return 'bg-blue-500';
    case 'Acceptée':
      return 'bg-emerald-500';
    case 'Refusée':
      return 'bg-red-500';
    default:
      return 'bg-gray-500';
  }
}

function getInitials(nom, prenom) {
  return `${prenom.charAt(0)}${nom.charAt(0)}`.toUpperCase();
}


</script>