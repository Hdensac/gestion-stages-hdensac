<template>
  <RSLayout>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center mb-6">
              <h2 class="text-2xl font-semibold text-gray-800">
                Organigramme de la structure
              </h2>
              <div class="flex flex-wrap gap-2">
                <div class="dropdown relative">
                  <button
                    @click="showExpandOptions = !showExpandOptions"
                    class="px-4 py-2 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors flex items-center"
                  >
                    <span>Déplier</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                  </button>
                  <div v-if="showExpandOptions" class="dropdown-menu absolute z-50 mt-1 bg-white shadow-lg rounded-md border border-gray-200 py-1 w-48">
                    <button
                      @click="expandAll(); showExpandOptions = false;"
                      class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                    >
                      Tout déplier
                    </button>
                    <button
                      @click="expandLevel(1); showExpandOptions = false;"
                      class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                    >
                      Niveau 1 uniquement
                    </button>
                    <button
                      @click="expandLevel(2); showExpandOptions = false;"
                      class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                    >
                      Jusqu'au niveau 2
                    </button>
                  </div>
                </div>

                <div class="dropdown relative">
                  <button
                    @click="showCollapseOptions = !showCollapseOptions"
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors flex items-center"
                  >
                    <span>Replier</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                  </button>
                  <div v-if="showCollapseOptions" class="dropdown-menu absolute z-50 mt-1 bg-white shadow-lg rounded-md border border-gray-200 py-1 w-48">
                    <button
                      @click="collapseAll(); showCollapseOptions = false;"
                      class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                    >
                      Tout replier
                    </button>
                    <button
                      @click="collapseLevel(2); showCollapseOptions = false;"
                      class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                    >
                      Replier niveau 2+
                    </button>
                  </div>
                </div>

                <button
                  @click="printOrganigramme"
                  class="px-4 py-2 bg-green-100 text-green-700 rounded-md hover:bg-green-200 transition-colors"
                >
                  Imprimer
                </button>
              </div>
            </div>

            <div class="mb-6">
              <p class="text-gray-600">
                Visualisez et gérez la hiérarchie de votre organisation. Cliquez sur les structures pour voir leurs sous-structures.
              </p>
              <div v-if="error" class="mt-4 p-4 bg-red-50 border border-red-200 rounded-md text-red-700">
                {{ error }}
              </div>
              <div v-if="structureResponsable" class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-md text-blue-700">
                <p><strong>Vous êtes responsable de :</strong> {{ structureResponsable.libelle }} ({{ structureResponsable.sigle }})</p>
              </div>
            </div>

            <!-- Recherche et filtres -->
            <div class="mb-6 flex flex-wrap gap-4">
              <div class="flex-1 min-w-[300px]">
                <input
                  v-model="searchTerm"
                  type="text"
                  placeholder="Rechercher une structure..."
                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
              <div>
                <select
                  v-model="filterType"
                  class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  <option value="all">Toutes les structures</option>
                  <option value="parent">Structures principales</option>
                  <option value="child">Sous-structures</option>
                </select>
              </div>
            </div>

            <!-- Organigramme -->
            <div class="organigramme-container" ref="organigrammeContainer">
              <div v-if="loading" class="flex justify-center items-center py-12">
                <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
              </div>
              <div v-else-if="structures.length === 0" class="text-center py-12">
                <p class="text-gray-500">Aucune structure trouvée.</p>
              </div>
              <div v-else class="organigramme">
                <template v-for="(structure, index) in filteredStructures" :key="structure.id">
                  <!-- N'afficher que les structures principales (parent_id === null) au premier niveau -->
                  <div v-if="structure.parent_id === null" class="structure-node parent-node">
                    <div
                      class="structure-card"
                      :class="{ 'has-children': hasChildren(structure.id), 'expanded': expandedNodes.includes(structure.id) }"
                    >
                      <div class="structure-header" @click.stop="toggleNode(structure.id)">
                        <div class="structure-icon">
                          <svg v-if="hasChildren(structure.id)" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path v-if="expandedNodes.includes(structure.id)" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                          </svg>
                          <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14" />
                          </svg>
                        </div>
                        <div class="structure-title">
                          <h3 class="font-medium">{{ structure.libelle }}</h3>
                          <p class="text-sm text-gray-500">{{ structure.sigle }}</p>
                        </div>
                      </div>

                      <div class="structure-body">
                        <div v-if="structure.responsable" class="responsable-info">
                          <p class="text-sm"><span class="font-medium">Responsable:</span> {{ structure.responsable.user.nom }} {{ structure.responsable.user.prenom }}</p>
                        </div>
                        <div class="structure-actions">
                          <button
                            @click.stop.prevent="addSubStructure(structure.id)"
                            class="action-btn add-btn"
                            title="Ajouter une sous-structure"
                            type="button"
                          >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                          </button>
                          <button
                            @click.stop.prevent="editStructure(structure)"
                            class="action-btn edit-btn"
                            title="Modifier"
                            type="button"
                          >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                          </button>
                          <button
                            @click.stop.prevent="deleteStructure(structure.id)"
                            class="action-btn delete-btn"
                            title="Supprimer"
                            type="button"
                          >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                          </button>
                          <button
                            @click.stop.prevent="affecterAgent(structure.id)"
                            class="action-btn assign-btn"
                            title="Affecter un agent"
                            type="button"
                          >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                          </button>
                        </div>
                      </div>
                    </div>

                    <!-- Formulaire d'ajout de sous-structure -->
                    <div v-if="showAddForm && currentStructureId === structure.id" class="mb-4 mt-2 bg-white border border-green-100 rounded-lg shadow-sm overflow-hidden z-10">
                      <div class="bg-green-50 px-4 py-2 border-b border-green-100 flex justify-between items-center">
                        <h3 class="text-sm font-medium text-green-800">
                          Nouvelle sous-structure
                        </h3>
                        <button
                          @click="showAddForm = false; currentStructureId = null;"
                          class="text-gray-400 hover:text-gray-600"
                          type="button"
                        >
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                          </svg>
                        </button>
                      </div>
                      <div class="p-4 space-y-3">
                        <div class="grid grid-cols-2 gap-3">
                          <div>
                            <label for="sigle" class="block text-xs font-medium text-gray-700 mb-1">
                              Sigle
                            </label>
                            <input
                              id="sigle"
                              v-model="addForm.sigle"
                              type="text"
                              class="w-full rounded-md border border-gray-300 shadow-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 text-sm p-2"
                            />
                            <div v-if="addForm.errors.sigle" class="text-red-500 text-xs mt-1">
                              {{ addForm.errors.sigle }}
                            </div>
                          </div>
                          <div>
                            <label for="libelle" class="block text-xs font-medium text-gray-700 mb-1">
                              Libellé
                            </label>
                            <input
                              id="libelle"
                              v-model="addForm.libelle"
                              type="text"
                              class="w-full rounded-md border border-gray-300 shadow-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 text-sm p-2"
                            />
                            <div v-if="addForm.errors.libelle" class="text-red-500 text-xs mt-1">
                              {{ addForm.errors.libelle }}
                            </div>
                          </div>
                        </div>
                        <div>
                          <label for="description" class="block text-xs font-medium text-gray-700 mb-1">
                            Description
                          </label>
                          <textarea
                            id="description"
                            v-model="addForm.description"
                            rows="2"
                            class="w-full rounded-md border border-gray-300 shadow-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 text-sm p-2"
                          ></textarea>
                        </div>
                        <div>
                          <label for="type_structure" class="block text-xs font-medium text-gray-700 mb-1">
                            Type de structure
                          </label>
                          <input
                            id="type_structure"
                            v-model="addForm.type_structure"
                            type="text"
                            class="w-full rounded-md border border-gray-300 shadow-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 text-sm p-2"
                          />
                        </div>
                        <div class="flex justify-end gap-2 pt-2">
                          <button
                            type="button"
                            class="px-3 py-1.5 bg-white border border-gray-300 text-gray-700 rounded-md text-sm hover:bg-gray-50 transition"
                            @click="showAddForm = false; currentStructureId = null;"
                          >
                            Annuler
                          </button>
                          <button
                            type="button"
                            class="px-3 py-1.5 bg-green-600 text-white rounded-md text-sm hover:bg-green-700 transition flex items-center gap-1"
                            @click="submitAddForm"
                            :disabled="addForm.processing"
                          >
                            <svg v-if="!addForm.processing" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <svg v-else class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Ajouter
                          </button>
                        </div>
                      </div>
                    </div>

                    <!-- Formulaire de modification de structure -->
                    <div v-if="showEditForm && currentStructureId === structure.id" class="mb-4 mt-2 bg-white border border-blue-100 rounded-lg shadow-sm overflow-hidden z-10">
                      <div class="bg-blue-50 px-4 py-2 border-b border-blue-100 flex justify-between items-center">
                        <h3 class="text-sm font-medium text-blue-800">
                          Modifier la structure
                        </h3>
                        <button
                          @click="showEditForm = false; currentStructureId = null;"
                          class="text-gray-400 hover:text-gray-600"
                          type="button"
                        >
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                          </svg>
                        </button>
                      </div>
                      <div class="p-4 space-y-3">
                        <div class="grid grid-cols-2 gap-3">
                          <div>
                            <label for="edit_sigle" class="block text-xs font-medium text-gray-700 mb-1">
                              Sigle
                            </label>
                            <input
                              id="edit_sigle"
                              v-model="editForm.sigle"
                              type="text"
                              class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm p-2"
                            />
                            <div v-if="editForm.errors.sigle" class="text-red-500 text-xs mt-1">
                              {{ editForm.errors.sigle }}
                            </div>
                          </div>
                          <div>
                            <label for="edit_libelle" class="block text-xs font-medium text-gray-700 mb-1">
                              Libellé
                            </label>
                            <input
                              id="edit_libelle"
                              v-model="editForm.libelle"
                              type="text"
                              class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm p-2"
                            />
                            <div v-if="editForm.errors.libelle" class="text-red-500 text-xs mt-1">
                              {{ editForm.errors.libelle }}
                            </div>
                          </div>
                        </div>
                        <div>
                          <label for="edit_description" class="block text-xs font-medium text-gray-700 mb-1">
                            Description
                          </label>
                          <textarea
                            id="edit_description"
                            v-model="editForm.description"
                            rows="2"
                            class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm p-2"
                          ></textarea>
                        </div>
                        <div>
                          <label for="edit_type_structure" class="block text-xs font-medium text-gray-700 mb-1">
                            Type de structure
                          </label>
                          <input
                            id="edit_type_structure"
                            v-model="editForm.type_structure"
                            type="text"
                            class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm p-2"
                          />
                        </div>
                        <div class="flex justify-end gap-2 pt-2">
                          <button
                            type="button"
                            class="px-3 py-1.5 bg-white border border-gray-300 text-gray-700 rounded-md text-sm hover:bg-gray-50 transition"
                            @click="showEditForm = false; currentStructureId = null;"
                          >
                            Annuler
                          </button>
                          <button
                            type="button"
                            class="px-3 py-1.5 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700 transition flex items-center gap-1"
                            @click="submitEditForm"
                            :disabled="editForm.processing"
                          >
                            <svg v-if="!editForm.processing" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <svg v-else class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Enregistrer
                          </button>
                        </div>
                      </div>
                    </div>

                    <!-- Formulaire d'affectation d'agent -->
                    <div v-if="showAffecterForm && currentStructureId === structure.id" class="mb-4 mt-2 bg-white border border-purple-100 rounded-lg shadow-sm overflow-hidden z-10">
                      <div class="bg-purple-50 px-4 py-2 border-b border-purple-100 flex justify-between items-center">
                        <h3 class="text-sm font-medium text-purple-800">
                          Affecter un agent
                        </h3>
                        <button
                          @click="showAffecterForm = false; currentStructureId = null;"
                          class="text-gray-400 hover:text-gray-600"
                          type="button"
                        >
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                          </svg>
                        </button>
                      </div>
                      <div class="p-4 space-y-3">
                        <div>
                          <label for="agent_id" class="block text-xs font-medium text-gray-700 mb-1">
                            Sélectionner un agent
                          </label>
                          <select
                            id="agent_id"
                            v-model="affecterForm.agent_id"
                            class="w-full rounded-md border border-gray-300 shadow-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-500 text-sm p-2"
                          >
                            <option value="">Sélectionner un agent</option>
                            <option v-for="agent in props.agents" :key="agent.id" :value="agent.id">
                              {{ agent.user.nom }} {{ agent.user.prenom }} ({{ agent.role_agent }})
                            </option>
                          </select>
                          <div v-if="affecterForm.errors.agent_id" class="text-red-500 text-xs mt-1">
                            {{ affecterForm.errors.agent_id }}
                          </div>
                        </div>
                        <div class="flex justify-end gap-2 pt-2">
                          <button
                            type="button"
                            class="px-3 py-1.5 bg-white border border-gray-300 text-gray-700 rounded-md text-sm hover:bg-gray-50 transition"
                            @click="showAffecterForm = false; currentStructureId = null;"
                          >
                            Annuler
                          </button>
                          <button
                            type="button"
                            class="px-3 py-1.5 bg-purple-600 text-white rounded-md text-sm hover:bg-purple-700 transition flex items-center gap-1"
                            @click="submitAffecterForm"
                            :disabled="affecterForm.processing"
                          >
                            <svg v-if="!affecterForm.processing" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <svg v-else class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Affecter
                          </button>
                        </div>
                      </div>
                    </div>

                    <!-- Sous-structures -->
                    <div v-if="expandedNodes.includes(structure.id)" class="children-container">
                      <div v-for="child in getChildrenOf(structure.id)" :key="child.id" class="structure-node child-node">
                        <div
                          class="structure-card"
                          :class="{ 'has-children': hasChildren(child.id), 'expanded': expandedNodes.includes(child.id) }"
                        >
                          <div class="structure-header" @click.stop="toggleNode(child.id)">
                            <div class="structure-icon">
                              <svg v-if="hasChildren(child.id)" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path v-if="expandedNodes.includes(child.id)" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                              </svg>
                              <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14" />
                              </svg>
                            </div>
                            <div class="structure-title">
                              <h3 class="font-medium">{{ child.libelle }}</h3>
                              <p class="text-sm text-gray-500">{{ child.sigle }}</p>
                            </div>
                          </div>

                          <div class="structure-body">
                            <div v-if="child.responsable" class="responsable-info">
                              <p class="text-sm"><span class="font-medium">Responsable:</span> {{ child.responsable.user.nom }} {{ child.responsable.user.prenom }}</p>
                            </div>
                            <div class="structure-actions">
                              <button
                                @click.stop.prevent="addSubStructure(child.id)"
                                class="action-btn add-btn"
                                title="Ajouter une sous-structure"
                                type="button"
                              >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                              </button>
                              <button
                                @click.stop.prevent="editStructure(child)"
                                class="action-btn edit-btn"
                                title="Modifier"
                                type="button"
                              >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                              </button>
                              <button
                                @click.stop.prevent="deleteStructure(child.id)"
                                class="action-btn delete-btn"
                                title="Supprimer"
                                type="button"
                              >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                              </button>
                              <button
                                @click.stop.prevent="affecterAgent(child.id)"
                                class="action-btn assign-btn"
                                title="Affecter un agent"
                                type="button"
                              >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                              </button>
                            </div>
                          </div>
                        </div>

                        <!-- Formulaire d'ajout de sous-structure pour niveau 2 -->
                        <div v-if="showAddForm && currentStructureId === child.id" class="mb-4 mt-2 bg-white border border-green-100 rounded-lg shadow-sm overflow-hidden z-10">
                          <div class="bg-green-50 px-4 py-2 border-b border-green-100 flex justify-between items-center">
                            <h3 class="text-sm font-medium text-green-800">
                              Nouvelle sous-structure
                            </h3>
                            <button
                              @click="showAddForm = false; currentStructureId = null;"
                              class="text-gray-400 hover:text-gray-600"
                              type="button"
                            >
                              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                              </svg>
                            </button>
                          </div>
                          <div class="p-4 space-y-3">
                            <div class="grid grid-cols-2 gap-3">
                              <div>
                                <label for="sigle" class="block text-xs font-medium text-gray-700 mb-1">
                                  Sigle
                                </label>
                                <input
                                  id="sigle"
                                  v-model="addForm.sigle"
                                  type="text"
                                  class="w-full rounded-md border border-gray-300 shadow-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 text-sm p-2"
                                />
                                <div v-if="addForm.errors.sigle" class="text-red-500 text-xs mt-1">
                                  {{ addForm.errors.sigle }}
                                </div>
                              </div>
                              <div>
                                <label for="libelle" class="block text-xs font-medium text-gray-700 mb-1">
                                  Libellé
                                </label>
                                <input
                                  id="libelle"
                                  v-model="addForm.libelle"
                                  type="text"
                                  class="w-full rounded-md border border-gray-300 shadow-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 text-sm p-2"
                                />
                                <div v-if="addForm.errors.libelle" class="text-red-500 text-xs mt-1">
                                  {{ addForm.errors.libelle }}
                                </div>
                              </div>
                            </div>
                            <div>
                              <label for="description" class="block text-xs font-medium text-gray-700 mb-1">
                                Description
                              </label>
                              <textarea
                                id="description"
                                v-model="addForm.description"
                                rows="2"
                                class="w-full rounded-md border border-gray-300 shadow-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 text-sm p-2"
                              ></textarea>
                            </div>
                            <div>
                              <label for="type_structure" class="block text-xs font-medium text-gray-700 mb-1">
                                Type de structure
                              </label>
                              <input
                                id="type_structure"
                                v-model="addForm.type_structure"
                                type="text"
                                class="w-full rounded-md border border-gray-300 shadow-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 text-sm p-2"
                              />
                            </div>
                            <div class="flex justify-end gap-2 pt-2">
                              <button
                                type="button"
                                class="px-3 py-1.5 bg-white border border-gray-300 text-gray-700 rounded-md text-sm hover:bg-gray-50 transition"
                                @click="showAddForm = false; currentStructureId = null;"
                              >
                                Annuler
                              </button>
                              <button
                                type="button"
                                class="px-3 py-1.5 bg-green-600 text-white rounded-md text-sm hover:bg-green-700 transition flex items-center gap-1"
                                @click="submitAddForm"
                                :disabled="addForm.processing"
                              >
                                <svg v-if="!addForm.processing" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <svg v-else class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Ajouter
                              </button>
                            </div>
                          </div>
                        </div>

                        <!-- Formulaire de modification de structure pour niveau 2 -->
                        <div v-if="showEditForm && currentStructureId === child.id" class="mb-4 mt-2 bg-white border border-blue-100 rounded-lg shadow-sm overflow-hidden z-10">
                          <div class="bg-blue-50 px-4 py-2 border-b border-blue-100 flex justify-between items-center">
                            <h3 class="text-sm font-medium text-blue-800">
                              Modifier la structure
                            </h3>
                            <button
                              @click="showEditForm = false; currentStructureId = null;"
                              class="text-gray-400 hover:text-gray-600"
                              type="button"
                            >
                              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                              </svg>
                            </button>
                          </div>
                          <div class="p-4 space-y-3">
                            <div class="grid grid-cols-2 gap-3">
                              <div>
                                <label for="edit_sigle" class="block text-xs font-medium text-gray-700 mb-1">
                                  Sigle
                                </label>
                                <input
                                  id="edit_sigle"
                                  v-model="editForm.sigle"
                                  type="text"
                                  class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm p-2"
                                />
                                <div v-if="editForm.errors.sigle" class="text-red-500 text-xs mt-1">
                                  {{ editForm.errors.sigle }}
                                </div>
                              </div>
                              <div>
                                <label for="edit_libelle" class="block text-xs font-medium text-gray-700 mb-1">
                                  Libellé
                                </label>
                                <input
                                  id="edit_libelle"
                                  v-model="editForm.libelle"
                                  type="text"
                                  class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm p-2"
                                />
                                <div v-if="editForm.errors.libelle" class="text-red-500 text-xs mt-1">
                                  {{ editForm.errors.libelle }}
                                </div>
                              </div>
                            </div>
                            <div>
                              <label for="edit_description" class="block text-xs font-medium text-gray-700 mb-1">
                                Description
                              </label>
                              <textarea
                                id="edit_description"
                                v-model="editForm.description"
                                rows="2"
                                class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm p-2"
                              ></textarea>
                            </div>
                            <div>
                              <label for="edit_type_structure" class="block text-xs font-medium text-gray-700 mb-1">
                                Type de structure
                              </label>
                              <input
                                id="edit_type_structure"
                                v-model="editForm.type_structure"
                                type="text"
                                class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm p-2"
                              />
                            </div>
                            <div class="flex justify-end gap-2 pt-2">
                              <button
                                type="button"
                                class="px-3 py-1.5 bg-white border border-gray-300 text-gray-700 rounded-md text-sm hover:bg-gray-50 transition"
                                @click="showEditForm = false; currentStructureId = null;"
                              >
                                Annuler
                              </button>
                              <button
                                type="button"
                                class="px-3 py-1.5 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700 transition flex items-center gap-1"
                                @click="submitEditForm"
                                :disabled="editForm.processing"
                              >
                                <svg v-if="!editForm.processing" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <svg v-else class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Enregistrer
                              </button>
                            </div>
                          </div>
                        </div>

                        <!-- Formulaire d'affectation d'agent pour niveau 2 -->
                        <div v-if="showAffecterForm && currentStructureId === child.id" class="mb-4 mt-2 bg-white border border-purple-100 rounded-lg shadow-sm overflow-hidden z-10">
                          <div class="bg-purple-50 px-4 py-2 border-b border-purple-100 flex justify-between items-center">
                            <h3 class="text-sm font-medium text-purple-800">
                              Affecter un agent
                            </h3>
                            <button
                              @click="showAffecterForm = false; currentStructureId = null;"
                              class="text-gray-400 hover:text-gray-600"
                              type="button"
                            >
                              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                              </svg>
                            </button>
                          </div>
                          <div class="p-4 space-y-3">
                            <div>
                              <label for="agent_id" class="block text-xs font-medium text-gray-700 mb-1">
                                Sélectionner un agent
                              </label>
                              <select
                                id="agent_id"
                                v-model="affecterForm.agent_id"
                                class="w-full rounded-md border border-gray-300 shadow-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-500 text-sm p-2"
                              >
                                <option value="">Sélectionner un agent</option>
                                <option v-for="agent in props.agents" :key="agent.id" :value="agent.id">
                                  {{ agent.user.nom }} {{ agent.user.prenom }} ({{ agent.role_agent }})
                                </option>
                              </select>
                              <div v-if="affecterForm.errors.agent_id" class="text-red-500 text-xs mt-1">
                                {{ affecterForm.errors.agent_id }}
                              </div>
                            </div>
                            <div class="flex justify-end gap-2 pt-2">
                              <button
                                type="button"
                                class="px-3 py-1.5 bg-white border border-gray-300 text-gray-700 rounded-md text-sm hover:bg-gray-50 transition"
                                @click="showAffecterForm = false; currentStructureId = null;"
                              >
                                Annuler
                              </button>
                              <button
                                type="button"
                                class="px-3 py-1.5 bg-purple-600 text-white rounded-md text-sm hover:bg-purple-700 transition flex items-center gap-1"
                                @click="submitAffecterForm"
                                :disabled="affecterForm.processing"
                              >
                                <svg v-if="!affecterForm.processing" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <svg v-else class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Affecter
                              </button>
                            </div>
                          </div>
                        </div>

                        <!-- Sous-sous-structures (niveau 3) -->
                        <div v-if="expandedNodes.includes(child.id)" class="children-container">
                          <div v-for="grandChild in getChildrenOf(child.id)" :key="grandChild.id" class="structure-node child-node">
                            <div class="structure-card">
                              <div class="structure-header">
                                <div class="structure-icon">
                                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14" />
                                  </svg>
                                </div>
                                <div class="structure-title">
                                  <h3 class="font-medium">{{ grandChild.libelle }}</h3>
                                  <p class="text-sm text-gray-500">{{ grandChild.sigle }}</p>
                                </div>
                              </div>

                              <div class="structure-body">
                                <div v-if="grandChild.responsable" class="responsable-info">
                                  <p class="text-sm"><span class="font-medium">Responsable:</span> {{ grandChild.responsable.user.nom }} {{ grandChild.responsable.user.prenom }}</p>
                                </div>
                                <div class="structure-actions">
                                  <button
                                    @click.stop.prevent="addSubStructure(grandChild.id)"
                                    class="action-btn add-btn"
                                    title="Ajouter une sous-structure"
                                    type="button"
                                  >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                  </button>
                                  <button
                                    @click.stop.prevent="editStructure(grandChild)"
                                    class="action-btn edit-btn"
                                    title="Modifier"
                                    type="button"
                                  >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                  </button>
                                  <button
                                    @click.stop.prevent="deleteStructure(grandChild.id)"
                                    class="action-btn delete-btn"
                                    title="Supprimer"
                                    type="button"
                                  >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                  </button>
                                  <button
                                    @click.stop.prevent="affecterAgent(grandChild.id)"
                                    class="action-btn assign-btn"
                                    title="Affecter un agent"
                                    type="button"
                                  >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                  </button>
                                </div>
                              </div>
                            </div>

                            <!-- Formulaire d'ajout de sous-structure pour niveau 3 -->
                            <div v-if="showAddForm && currentStructureId === grandChild.id" class="mb-4 mt-2 bg-white border border-green-100 rounded-lg shadow-sm overflow-hidden z-10">
                              <div class="bg-green-50 px-4 py-2 border-b border-green-100 flex justify-between items-center">
                                <h3 class="text-sm font-medium text-green-800">
                                  Nouvelle sous-structure
                                </h3>
                                <button
                                  @click="showAddForm = false; currentStructureId = null;"
                                  class="text-gray-400 hover:text-gray-600"
                                  type="button"
                                >
                                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                  </svg>
                                </button>
                              </div>
                              <div class="p-4 space-y-3">
                                <div class="grid grid-cols-2 gap-3">
                                  <div>
                                    <label for="sigle" class="block text-xs font-medium text-gray-700 mb-1">
                                      Sigle
                                    </label>
                                    <input
                                      id="sigle"
                                      v-model="addForm.sigle"
                                      type="text"
                                      class="w-full rounded-md border border-gray-300 shadow-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 text-sm p-2"
                                    />
                                    <div v-if="addForm.errors.sigle" class="text-red-500 text-xs mt-1">
                                      {{ addForm.errors.sigle }}
                                    </div>
                                  </div>
                                  <div>
                                    <label for="libelle" class="block text-xs font-medium text-gray-700 mb-1">
                                      Libellé
                                    </label>
                                    <input
                                      id="libelle"
                                      v-model="addForm.libelle"
                                      type="text"
                                      class="w-full rounded-md border border-gray-300 shadow-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 text-sm p-2"
                                    />
                                    <div v-if="addForm.errors.libelle" class="text-red-500 text-xs mt-1">
                                      {{ addForm.errors.libelle }}
                                    </div>
                                  </div>
                                </div>
                                <div>
                                  <label for="description" class="block text-xs font-medium text-gray-700 mb-1">
                                    Description
                                  </label>
                                  <textarea
                                    id="description"
                                    v-model="addForm.description"
                                    rows="2"
                                    class="w-full rounded-md border border-gray-300 shadow-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 text-sm p-2"
                                  ></textarea>
                                </div>
                                <div>
                                  <label for="type_structure" class="block text-xs font-medium text-gray-700 mb-1">
                                    Type de structure
                                  </label>
                                  <input
                                    id="type_structure"
                                    v-model="addForm.type_structure"
                                    type="text"
                                    class="w-full rounded-md border border-gray-300 shadow-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 text-sm p-2"
                                  />
                                </div>
                                <div class="flex justify-end gap-2 pt-2">
                                  <button
                                    type="button"
                                    class="px-3 py-1.5 bg-white border border-gray-300 text-gray-700 rounded-md text-sm hover:bg-gray-50 transition"
                                    @click="showAddForm = false; currentStructureId = null;"
                                  >
                                    Annuler
                                  </button>
                                  <button
                                    type="button"
                                    class="px-3 py-1.5 bg-green-600 text-white rounded-md text-sm hover:bg-green-700 transition flex items-center gap-1"
                                    @click="submitAddForm"
                                    :disabled="addForm.processing"
                                  >
                                    <svg v-if="!addForm.processing" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <svg v-else class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Ajouter
                                  </button>
                                </div>
                              </div>
                            </div>

                            <!-- Formulaire de modification de structure pour niveau 3 -->
                            <div v-if="showEditForm && currentStructureId === grandChild.id" class="mb-4 mt-2 bg-white border border-blue-100 rounded-lg shadow-sm overflow-hidden z-10">
                              <div class="bg-blue-50 px-4 py-2 border-b border-blue-100 flex justify-between items-center">
                                <h3 class="text-sm font-medium text-blue-800">
                                  Modifier la structure
                                </h3>
                                <button
                                  @click="showEditForm = false; currentStructureId = null;"
                                  class="text-gray-400 hover:text-gray-600"
                                  type="button"
                                >
                                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                  </svg>
                                </button>
                              </div>
                              <div class="p-4 space-y-3">
                                <div class="grid grid-cols-2 gap-3">
                                  <div>
                                    <label for="edit_sigle" class="block text-xs font-medium text-gray-700 mb-1">
                                      Sigle
                                    </label>
                                    <input
                                      id="edit_sigle"
                                      v-model="editForm.sigle"
                                      type="text"
                                      class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm p-2"
                                    />
                                    <div v-if="editForm.errors.sigle" class="text-red-500 text-xs mt-1">
                                      {{ editForm.errors.sigle }}
                                    </div>
                                  </div>
                                  <div>
                                    <label for="edit_libelle" class="block text-xs font-medium text-gray-700 mb-1">
                                      Libellé
                                    </label>
                                    <input
                                      id="edit_libelle"
                                      v-model="editForm.libelle"
                                      type="text"
                                      class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm p-2"
                                    />
                                    <div v-if="editForm.errors.libelle" class="text-red-500 text-xs mt-1">
                                      {{ editForm.errors.libelle }}
                                    </div>
                                  </div>
                                </div>
                                <div>
                                  <label for="edit_description" class="block text-xs font-medium text-gray-700 mb-1">
                                    Description
                                  </label>
                                  <textarea
                                    id="edit_description"
                                    v-model="editForm.description"
                                    rows="2"
                                    class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm p-2"
                                  ></textarea>
                                </div>
                                <div>
                                  <label for="edit_type_structure" class="block text-xs font-medium text-gray-700 mb-1">
                                    Type de structure
                                  </label>
                                  <input
                                    id="edit_type_structure"
                                    v-model="editForm.type_structure"
                                    type="text"
                                    class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm p-2"
                                  />
                                </div>
                                <div class="flex justify-end gap-2 pt-2">
                                  <button
                                    type="button"
                                    class="px-3 py-1.5 bg-white border border-gray-300 text-gray-700 rounded-md text-sm hover:bg-gray-50 transition"
                                    @click="showEditForm = false; currentStructureId = null;"
                                  >
                                    Annuler
                                  </button>
                                  <button
                                    type="button"
                                    class="px-3 py-1.5 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700 transition flex items-center gap-1"
                                    @click="submitEditForm"
                                    :disabled="editForm.processing"
                                  >
                                    <svg v-if="!editForm.processing" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <svg v-else class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Enregistrer
                                  </button>
                                </div>
                              </div>
                            </div>

                            <!-- Formulaire d'affectation d'agent pour niveau 3 -->
                            <div v-if="showAffecterForm && currentStructureId === grandChild.id" class="mb-4 mt-2 bg-white border border-purple-100 rounded-lg shadow-sm overflow-hidden z-10">
                              <div class="bg-purple-50 px-4 py-2 border-b border-purple-100 flex justify-between items-center">
                                <h3 class="text-sm font-medium text-purple-800">
                                  Affecter un agent
                                </h3>
                                <button
                                  @click="showAffecterForm = false; currentStructureId = null;"
                                  class="text-gray-400 hover:text-gray-600"
                                  type="button"
                                >
                                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                  </svg>
                                </button>
                              </div>
                              <div class="p-4 space-y-3">
                                <div>
                                  <label for="agent_id" class="block text-xs font-medium text-gray-700 mb-1">
                                    Sélectionner un agent
                                  </label>
                                  <select
                                    id="agent_id"
                                    v-model="affecterForm.agent_id"
                                    class="w-full rounded-md border border-gray-300 shadow-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-500 text-sm p-2"
                                  >
                                    <option value="">Sélectionner un agent</option>
                                    <option v-for="agent in props.agents" :key="agent.id" :value="agent.id">
                                      {{ agent.user.nom }} {{ agent.user.prenom }} ({{ agent.role_agent }})
                                    </option>
                                  </select>
                                  <div v-if="affecterForm.errors.agent_id" class="text-red-500 text-xs mt-1">
                                    {{ affecterForm.errors.agent_id }}
                                  </div>
                                </div>
                                <div class="flex justify-end gap-2 pt-2">
                                  <button
                                    type="button"
                                    class="px-3 py-1.5 bg-white border border-gray-300 text-gray-700 rounded-md text-sm hover:bg-gray-50 transition"
                                    @click="showAffecterForm = false; currentStructureId = null;"
                                  >
                                    Annuler
                                  </button>
                                  <button
                                    type="button"
                                    class="px-3 py-1.5 bg-purple-600 text-white rounded-md text-sm hover:bg-purple-700 transition flex items-center gap-1"
                                    @click="submitAffecterForm"
                                    :disabled="affecterForm.processing"
                                  >
                                    <svg v-if="!affecterForm.processing" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <svg v-else class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Affecter
                                  </button>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </template>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </RSLayout>
</template>

<script setup>
import { ref, computed, onMounted, nextTick } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import RSLayout from '@/Layouts/RSLayout.vue';

const props = defineProps({
  structures: Array,
  error: String,
  structureResponsable: Object,
  agents: Array,
});

const loading = ref(false);
const searchTerm = ref('');
const filterType = ref('all');
const expandedNodes = ref([]);
const organigrammeContainer = ref(null);

// États pour les menus déroulants
const showExpandOptions = ref(false);
const showCollapseOptions = ref(false);

// États pour les formulaires inline
const showAddForm = ref(false);
const showEditForm = ref(false);
const showAffecterForm = ref(false);
const currentStructureId = ref(null);
const currentStructure = ref(null);

// Formulaire pour ajouter une structure
const addForm = useForm({
  libelle: '',
  sigle: '',
  description: '',
  parent_id: null,
  type_structure: ''
});

// Formulaire pour modifier une structure
const editForm = useForm({
  libelle: '',
  sigle: '',
  description: '',
  type_structure: ''
});

// Formulaire pour affecter un agent
const affecterForm = useForm({
  agent_id: ''
});

// Filtrer les structures en fonction de la recherche et du type
const filteredStructures = computed(() => {
  if (!props.structures) return [];

  // Créer un Set des IDs de structures déjà traitées pour éviter les doublons
  const processedIds = new Set();

  return props.structures.filter(structure => {
    // Vérifier si cette structure a déjà été traitée (pour éviter les doublons)
    if (processedIds.has(structure.id)) {
      return false;
    }

    // Ajouter l'ID de cette structure au Set des structures traitées
    processedIds.add(structure.id);

    // Filtrer par terme de recherche
    const matchesSearch =
      structure.libelle.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
      structure.sigle.toLowerCase().includes(searchTerm.value.toLowerCase());

    // Filtrer par type
    const matchesType =
      filterType.value === 'all' ||
      (filterType.value === 'parent' && structure.parent_id === null) ||
      (filterType.value === 'child' && structure.parent_id !== null);

    return matchesSearch && matchesType;
  });
});

// Vérifier si une structure a des enfants
const hasChildren = (structureId) => {
  return props.structures.some(s => s.parent_id === structureId);
};

// Obtenir les enfants d'une structure
const getChildrenOf = (parentId) => {
  // Créer un Set des IDs de structures déjà traitées pour éviter les doublons
  const processedIds = new Set();

  return props.structures.filter(s => {
    // Vérifier si cette structure a déjà été traitée (pour éviter les doublons)
    if (processedIds.has(s.id)) {
      return false;
    }

    // Ajouter l'ID de cette structure au Set des structures traitées
    processedIds.add(s.id);

    // Vérifier si c'est un enfant direct de la structure parente
    return s.parent_id === parentId;
  });
};

// Déplier/replier un nœud
const toggleNode = (structureId) => {
  const index = expandedNodes.value.indexOf(structureId);
  if (index === -1) {
    expandedNodes.value.push(structureId);
  } else {
    expandedNodes.value.splice(index, 1);
  }
};

// Déplier tous les nœuds
const expandAll = () => {
  // Récupérer tous les IDs des structures qui ont des enfants
  expandedNodes.value = props.structures
    .filter(s => hasChildren(s.id))
    .map(s => s.id);
};

// Déplier jusqu'à un certain niveau
const expandLevel = (level) => {
  // D'abord replier tout
  collapseAll();

  // Ensuite déplier jusqu'au niveau spécifié
  if (level >= 1) {
    // Déplier les structures de niveau 1 (structures principales)
    const level1Structures = props.structures
      .filter(s => s.parent_id === null)
      .map(s => s.id);

    expandedNodes.value = [...level1Structures];

    if (level >= 2) {
      // Déplier les structures de niveau 2 (sous-structures directes)
      const level2ParentIds = level1Structures;
      const level2Structures = props.structures
        .filter(s => s.parent_id !== null && level2ParentIds.includes(s.parent_id))
        .map(s => s.id);

      expandedNodes.value = [...expandedNodes.value, ...level2Structures];
    }
  }
};

// Replier tous les nœuds
const collapseAll = () => {
  // Vider complètement le tableau des nœuds dépliés
  expandedNodes.value = [];

  // Forcer la mise à jour du DOM
  nextTick(() => {
    // Assurez-vous que toutes les structures sont bien repliées
    const structureCards = document.querySelectorAll('.structure-card.expanded');
    structureCards.forEach(card => {
      card.classList.remove('expanded');
    });

    // Assurez-vous que tous les conteneurs d'enfants sont masqués
    const childrenContainers = document.querySelectorAll('.children-container');
    childrenContainers.forEach(container => {
      container.style.display = 'none';
    });
  });
};

// Replier à partir d'un certain niveau
const collapseLevel = (level) => {
  if (level <= 1) {
    // Si on replie le niveau 1, c'est comme tout replier
    collapseAll();
    return;
  }

  // Récupérer les structures jusqu'au niveau spécifié
  const level1Structures = props.structures
    .filter(s => s.parent_id === null)
    .map(s => s.id);

  if (level <= 2) {
    // Garder seulement les structures de niveau 1 dépliées
    expandedNodes.value = [...level1Structures];
  } else {
    // Pour les niveaux supérieurs, on peut ajouter la logique ici
    // ...
  }
};

// Ajouter une sous-structure
const addSubStructure = (parentId) => {
  console.log('Ajout d\'une sous-structure pour le parent ID:', parentId);
  // Réinitialiser le formulaire
  addForm.reset();
  addForm.parent_id = parentId;

  // Fermer les autres formulaires
  showEditForm.value = false;
  showAffecterForm.value = false;

  // Ouvrir le formulaire d'ajout
  currentStructureId.value = parentId;
  showAddForm.value = true;

  // S'assurer que le nœud parent est déplié pour voir le formulaire
  if (!expandedNodes.value.includes(parentId)) {
    expandedNodes.value.push(parentId);
  }
};

// Soumettre le formulaire d'ajout
const submitAddForm = () => {
  console.log('Soumission du formulaire d\'ajout:', addForm.data());

  // Vérifier que les champs obligatoires sont remplis
  if (!addForm.sigle || !addForm.libelle) {
    alert('Veuillez remplir tous les champs obligatoires (sigle et libellé)');
    return;
  }

  addForm.post(route('agent.rs.structures.store'), {
    preserveScroll: true,
    onSuccess: () => {
      showAddForm.value = false;
      currentStructureId.value = null;
      // Recharger la page pour voir les changements
      router.reload();
    },
    onError: (errors) => {
      console.error('Erreurs lors de l\'ajout:', errors);
      alert('Erreur lors de l\'ajout de la structure: ' + Object.values(errors).join(', '));
    }
  });
};

// Modifier une structure
const editStructure = (structure) => {
  console.log('Modification de la structure ID:', structure.id);

  // Remplir le formulaire avec les données de la structure
  editForm.libelle = structure.libelle;
  editForm.sigle = structure.sigle;
  editForm.description = structure.description || '';
  editForm.type_structure = structure.type_structure || '';

  // Fermer les autres formulaires
  showAddForm.value = false;
  showAffecterForm.value = false;

  // Ouvrir le formulaire de modification
  currentStructureId.value = structure.id;
  currentStructure.value = structure;
  showEditForm.value = true;

  // S'assurer que le nœud parent est déplié pour voir le formulaire
  if (structure.parent_id && !expandedNodes.value.includes(structure.parent_id)) {
    expandedNodes.value.push(structure.parent_id);
  }
};

// Soumettre le formulaire de modification
const submitEditForm = () => {
  console.log('Soumission du formulaire de modification:', editForm.data(), 'pour la structure ID:', currentStructureId.value);

  // Vérifier que les champs obligatoires sont remplis
  if (!editForm.sigle || !editForm.libelle) {
    alert('Veuillez remplir tous les champs obligatoires (sigle et libellé)');
    return;
  }

  editForm.put(route('agent.rs.structures.update', currentStructureId.value), {
    preserveScroll: true,
    onSuccess: () => {
      showEditForm.value = false;
      currentStructureId.value = null;
      currentStructure.value = null;
      // Recharger la page pour voir les changements
      router.reload();
    },
    onError: (errors) => {
      console.error('Erreurs lors de la modification:', errors);
      alert('Erreur lors de la modification de la structure: ' + Object.values(errors).join(', '));
    }
  });
};

// Supprimer une structure
const deleteStructure = (structureId) => {
  console.log('Suppression de la structure ID:', structureId);
  if (confirm('Êtes-vous sûr de vouloir supprimer cette structure ?')) {
    router.delete(route('agent.rs.structures.destroy', structureId), {
      preserveScroll: true,
      onSuccess: () => {
        // Recharger la page pour voir les changements
        router.reload();
      }
    });
  }
};

// Affecter un agent à une structure
const affecterAgent = (structureId) => {
  console.log('Affectation d\'agents à la structure ID:', structureId);

  // Réinitialiser le formulaire
  affecterForm.reset();

  // Fermer les autres formulaires
  showAddForm.value = false;
  showEditForm.value = false;

  // Ouvrir le formulaire d'affectation
  currentStructureId.value = structureId;
  showAffecterForm.value = true;

  // Trouver la structure pour vérifier si elle a un parent
  const structure = props.structures.find(s => s.id === structureId);

  // S'assurer que le nœud parent est déplié pour voir le formulaire
  if (structure && structure.parent_id && !expandedNodes.value.includes(structure.parent_id)) {
    expandedNodes.value.push(structure.parent_id);
  }
};

// Soumettre le formulaire d'affectation
const submitAffecterForm = () => {
  console.log('Soumission du formulaire d\'affectation:', affecterForm.data(), 'pour la structure ID:', currentStructureId.value);

  // Vérifier qu'un agent est sélectionné
  if (!affecterForm.agent_id) {
    alert('Veuillez sélectionner un agent');
    return;
  }

  affecterForm.post(route('agent.rs.organigramme.affecter-agent', currentStructureId.value), {
    preserveScroll: true,
    onSuccess: () => {
      showAffecterForm.value = false;
      currentStructureId.value = null;
      // Recharger la page pour voir les changements
      router.reload();
    },
    onError: (errors) => {
      console.error('Erreurs lors de l\'affectation:', errors);
      alert('Erreur lors de l\'affectation de l\'agent: ' + Object.values(errors).join(', '));
    }
  });
};

// Imprimer l'organigramme
const printOrganigramme = () => {
  // Étendre tous les nœuds avant l'impression
  const previousExpandedState = [...expandedNodes.value];
  expandAll();

  // Attendre que le DOM soit mis à jour
  setTimeout(() => {
    window.print();

    // Restaurer l'état précédent après l'impression
    expandedNodes.value = previousExpandedState;
  }, 100);
};

onMounted(() => {
  // Déplier automatiquement les structures de premier niveau
  props.structures
    .filter(s => s.parent_id === null)
    .forEach(s => {
      if (hasChildren(s.id)) {
        expandedNodes.value.push(s.id);
      }
    });

  // Fermer les menus déroulants quand on clique ailleurs sur la page
  document.addEventListener('click', (e) => {
    const dropdownButtons = document.querySelectorAll('.dropdown button');
    let clickedOnDropdown = false;

    dropdownButtons.forEach(button => {
      if (button.contains(e.target)) {
        clickedOnDropdown = true;
      }
    });

    if (!clickedOnDropdown) {
      showExpandOptions.value = false;
      showCollapseOptions.value = false;
    }
  });
});
</script>

<style scoped>
.organigramme-container {
  overflow-x: auto;
  max-height: 80vh;
  overflow-y: auto;
}

.organigramme {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

/* Style pour les menus déroulants */
.dropdown-menu {
  min-width: 160px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

/* Animations pour les formulaires */
.fade-enter-active, .fade-leave-active {
  transition: all 0.3s cubic-bezier(.4,2,.6,1);
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
  max-height: 0;
  transform: translateY(-10px);
}
.fade-enter-to, .fade-leave-from {
  opacity: 1;
  max-height: 1000px;
  transform: translateY(0);
}

/* Styles pour les formulaires */
.mb-4.mt-2.bg-white {
  position: relative;
  z-index: 50;
}

/* Assurer que les formulaires sont visibles */
.children-container .mb-4.mt-2.bg-white {
  position: absolute;
  width: 100%;
  max-width: 500px;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.structure-node {
  position: relative;
}

.parent-node {
  margin-bottom: 0.5rem;
}

.structure-card {
  background-color: white;
  border: 1px solid #e5e7eb;
  border-radius: 0.5rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  transition: all 0.2s ease;
}

.structure-card.has-children {
  border-left: 4px solid #3b82f6;
}

.structure-card.expanded {
  border-left: 4px solid #2563eb;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.structure-header {
  display: flex;
  align-items: center;
  padding: 1rem;
  cursor: pointer;
  background-color: #f9fafb;
  border-bottom: 1px solid #e5e7eb;
}

.structure-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 2rem;
  height: 2rem;
  margin-right: 0.75rem;
  color: #4b5563;
}

.structure-title {
  flex: 1;
}

.structure-body {
  padding: 1rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.responsable-info {
  flex: 1;
}

.structure-actions {
  display: flex;
  gap: 0.5rem;
}

.action-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 2rem;
  height: 2rem;
  border-radius: 0.375rem;
  transition: all 0.2s ease;
}

.add-btn {
  color: #047857;
  background-color: #ecfdf5;
}

.add-btn:hover {
  background-color: #d1fae5;
}

.edit-btn {
  color: #1d4ed8;
  background-color: #eff6ff;
}

.edit-btn:hover {
  background-color: #dbeafe;
}

.delete-btn {
  color: #b91c1c;
  background-color: #fef2f2;
}

.delete-btn:hover {
  background-color: #fee2e2;
}

.assign-btn {
  color: #7c3aed;
  background-color: #f5f3ff;
}

.assign-btn:hover {
  background-color: #ede9fe;
}

.children-container {
  margin-left: 2rem;
  margin-top: 1rem;
  padding-left: 1rem;
  border-left: 2px dashed #d1d5db;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.child-node {
  position: relative;
}

.child-node::before {
  content: '';
  position: absolute;
  top: 1.5rem;
  left: -1rem;
  width: 0.75rem;
  height: 2px;
  background-color: #d1d5db;
}

@media print {
  .structure-actions,
  button {
    display: none !important;
  }

  .children-container {
    display: flex !important;
    flex-direction: column !important;
  }

  .structure-card {
    break-inside: avoid;
    page-break-inside: avoid;
  }
}
</style>
