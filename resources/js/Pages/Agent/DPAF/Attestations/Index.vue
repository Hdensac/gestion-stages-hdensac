<template>
    <AgentDPAF>
        <div class="p-6">
            <!-- En-tête -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">
                            Attestations de stages 
                        </h1>
                        <p class="text-gray-600">
                            Gestion des attestations de validation de stages
                        </p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <!-- Bouton d'export -->
                        <button
                            @click="exportData"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Exporter ({{ filteredStages.length }})
                        </button>

                        <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-4 py-2 rounded-lg">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="font-semibold">Validation DPAF</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">En attente</p>
                            <p class="text-2xl font-bold text-gray-900">{{ filteredStages.filter(s => !s.attestation_dpaf_generee).length }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Validées</p>
                            <p class="text-2xl font-bold text-gray-900">{{ filteredStages.filter(s => s.attestation_dpaf_generee).length }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-100 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Ce mois</p>
                            <p class="text-2xl font-bold text-gray-900">{{ filteredStages.filter(s => isThisMonth(s.attestation_structure_date)).length }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-orange-100 rounded-lg">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total</p>
                            <p class="text-2xl font-bold text-gray-900">{{ filteredStages.length }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtres -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex flex-wrap items-center gap-4">
                    <!-- Recherche par nom -->
                    <div class="flex-1 min-w-64">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rechercher un stagiaire</label>
                        <div class="relative">
                            <input
                                v-model="filters.search"
                                type="text"
                                placeholder="Nom, prénom ou email..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                @input="applyFilters"
                            />
                            <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Filtre par statut attestation DPAF -->
                    <div class="min-w-48">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Statut attestation DPAF</label>
                        <select
                            v-model="filters.statutDpaf"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            @change="applyFilters"
                        >
                            <option value="">Tous les statuts</option>
                            <option value="en_attente">En attente</option>
                            <option value="generee">Générée</option>
                        </select>
                    </div>

                    <!-- Filtre par structure -->
                    <div class="min-w-48">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Structure</label>
                        <select
                            v-model="filters.structure"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            @change="applyFilters"
                        >
                            <option value="">Toutes les structures</option>
                            <option v-for="structure in uniqueStructures" :key="structure.id" :value="structure.id">
                                {{ structure.libelle }} ({{ structure.sigle }})
                            </option>
                        </select>
                    </div>

                    <!-- Filtre par période -->
                    <div class="min-w-48">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Période</label>
                        <select
                            v-model="filters.periode"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            @change="applyFilters"
                        >
                            <option value="">Toutes les périodes</option>
                            <option value="ce_mois">Ce mois</option>
                            <option value="mois_dernier">Mois dernier</option>
                            <option value="3_derniers_mois">3 derniers mois</option>
                            <option value="cette_annee">Cette année</option>
                        </select>
                    </div>

                    <!-- Bouton reset -->
                    <div class="flex items-end">
                        <button
                            @click="resetFilters"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                        >
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Réinitialiser
                        </button>
                    </div>
                </div>

                <!-- Résumé des filtres actifs -->
                <div v-if="hasActiveFilters" class="mt-4 flex flex-wrap gap-2">
                    <span class="text-sm text-gray-600">Filtres actifs :</span>
                    <span v-if="filters.search" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        Recherche: "{{ filters.search }}"
                        <button @click="filters.search = ''; applyFilters()" class="ml-1.5 inline-flex items-center justify-center w-4 h-4 rounded-full text-blue-400 hover:bg-blue-200 hover:text-blue-600">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </span>
                    <span v-if="filters.statutDpaf" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Statut: {{ filters.statutDpaf === 'en_attente' ? 'En attente' : 'Générée' }}
                        <button @click="filters.statutDpaf = ''; applyFilters()" class="ml-1.5 inline-flex items-center justify-center w-4 h-4 rounded-full text-green-400 hover:bg-green-200 hover:text-green-600">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </span>
                    <span v-if="filters.structure" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        Structure: {{ getStructureName(filters.structure) }}
                        <button @click="filters.structure = ''; applyFilters()" class="ml-1.5 inline-flex items-center justify-center w-4 h-4 rounded-full text-purple-400 hover:bg-purple-200 hover:text-purple-600">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </span>
                    <span v-if="filters.periode" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                        Période: {{ getPeriodeName(filters.periode) }}
                        <button @click="filters.periode = ''; applyFilters()" class="ml-1.5 inline-flex items-center justify-center w-4 h-4 rounded-full text-orange-400 hover:bg-orange-200 hover:text-orange-600">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </span>
                </div>
            </div>

            <!-- Tableau des stages -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Stages éligibles pour attestation DPAF</h3>
                    <div class="text-sm text-gray-500">
                        {{ filteredStages.length }} résultat{{ filteredStages.length > 1 ? 's' : '' }}
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stagiaire</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Structure</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Période</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attestation Structure</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attestation DPAF</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Message si aucun résultat -->
                            <tr v-if="filteredStages.length === 0">
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun stage trouvé</h3>
                                        <p class="text-gray-500 mb-4">
                                            {{ hasActiveFilters ? 'Aucun stage ne correspond aux critères de recherche.' : 'Aucun stage éligible pour une attestation DPAF.' }}
                                        </p>
                                        <button v-if="hasActiveFilters" @click="resetFilters" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                            Réinitialiser les filtres
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-for="stage in filteredStages" :key="stage.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                                <span class="text-sm font-medium text-white">
                                                    {{ stage.stagiaire && stage.stagiaire.user ? getInitials(stage.stagiaire.user.prenom, stage.stagiaire.user.nom) : '' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                <template v-if="stage.stagiaire && stage.stagiaire.user">
                                                    {{ stage.stagiaire.user.prenom }} {{ stage.stagiaire.user.nom }}
                                                </template>
                                                <template v-else>
                                                    <span class="text-gray-400 italic">Non renseigné</span>
                                                </template>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                <template v-if="stage.stagiaire && stage.stagiaire.user">
                                                    {{ stage.stagiaire.user.email }}
                                                </template>
                                                <template v-else>
                                                    <span class="text-gray-400 italic">Non renseigné</span>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ stage.structure.libelle }}</div>
                                    <div class="text-sm text-gray-500">{{ stage.structure.sigle }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div>{{ formatDate(stage.date_debut) }}</div>
                                    <div class="text-gray-500">au {{ formatDate(stage.date_fin) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Générée
                                    </span>
                                    <div class="text-xs text-gray-500 mt-1">{{ formatDate(stage.attestation_structure_date) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span v-if="stage.attestation_dpaf_generee" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Validée
                                    </span>
                                    <span v-else class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        En attente
                                    </span>
                                    <div v-if="stage.attestation_dpaf_generee" class="text-xs text-gray-500 mt-1">
                                        {{ formatDate(stage.attestation_dpaf_date) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button v-if="!stage.attestation_dpaf_generee"
                                                @click="generateAttestation(stage.id)"
                                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                            </svg>
                                            Générer & Imprimer
                                        </button>
                                        
                                        <button v-if="stage.attestation_dpaf_generee"
                                                @click="viewAttestation(stage.id)"
                                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Voir
                                        </button>
                                        
                                        <button v-if="stage.attestation_dpaf_generee"
                                                @click="revokeAttestation(stage.id)"
                                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Révoquer
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AgentDPAF>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import AgentDPAF from '@/Layouts/AgentDPAF.vue'

const props = defineProps({
    stages: Object
})

// État des filtres
const filters = ref({
    search: '',
    statutDpaf: '',
    structure: '',
    periode: ''
})

// Debounce pour la recherche
let searchTimeout = null

// Computed properties
const uniqueStructures = computed(() => {
    const structures = props.stages.data.map(stage => stage.structure)
    const unique = structures.filter((structure, index, self) =>
        index === self.findIndex(s => s.id === structure.id)
    )
    return unique.sort((a, b) => a.libelle.localeCompare(b.libelle))
})

const filteredStages = computed(() => {
    let result = [...props.stages.data]

    // Filtre par recherche (nom, prénom, email)
    if (filters.value.search) {
        const searchTerm = filters.value.search.toLowerCase()
        result = result.filter(stage => {
            const user = stage.stagiaire && stage.stagiaire.user ? stage.stagiaire.user : null
            return user && (
                (user.nom && user.nom.toLowerCase().includes(searchTerm)) ||
                (user.prenom && user.prenom.toLowerCase().includes(searchTerm)) ||
                (user.email && user.email.toLowerCase().includes(searchTerm))
            )
        })
    }

    // Filtre par statut attestation DPAF
    if (filters.value.statutDpaf) {
        if (filters.value.statutDpaf === 'en_attente') {
            result = result.filter(stage => !stage.attestation_dpaf_generee)
        } else if (filters.value.statutDpaf === 'generee') {
            result = result.filter(stage => stage.attestation_dpaf_generee)
        }
    }

    // Filtre par structure
    if (filters.value.structure) {
        result = result.filter(stage => stage.structure.id == filters.value.structure)
    }

    // Filtre par période
    if (filters.value.periode) {
        const now = new Date()
        result = result.filter(stage => {
            const stageDate = new Date(stage.attestation_structure_date)

            switch (filters.value.periode) {
                case 'ce_mois':
                    return stageDate.getMonth() === now.getMonth() &&
                           stageDate.getFullYear() === now.getFullYear()
                case 'mois_dernier':
                    const lastMonth = new Date(now.getFullYear(), now.getMonth() - 1, 1)
                    return stageDate.getMonth() === lastMonth.getMonth() &&
                           stageDate.getFullYear() === lastMonth.getFullYear()
                case '3_derniers_mois':
                    const threeMonthsAgo = new Date(now.getFullYear(), now.getMonth() - 3, 1)
                    return stageDate >= threeMonthsAgo
                case 'cette_annee':
                    return stageDate.getFullYear() === now.getFullYear()
                default:
                    return true
            }
        })
    }

    return result
})

const hasActiveFilters = computed(() => {
    return filters.value.search ||
           filters.value.statutDpaf ||
           filters.value.structure ||
           filters.value.periode
})

// Méthodes
const applyFilters = () => {
    // Debounce pour la recherche
    if (searchTimeout) {
        clearTimeout(searchTimeout)
    }
    searchTimeout = setTimeout(() => {
        // Les filtres sont appliqués automatiquement via les computed properties
        // Ici on pourrait ajouter une logique pour persister les filtres dans l'URL
    }, 300)
}

const resetFilters = () => {
    filters.value = {
        search: '',
        statutDpaf: '',
        structure: '',
        periode: ''
    }
}

const getStructureName = (structureId) => {
    const structure = uniqueStructures.value.find(s => s.id == structureId)
    return structure ? `${structure.libelle} (${structure.sigle})` : ''
}

const getPeriodeName = (periode) => {
    const periods = {
        'ce_mois': 'Ce mois',
        'mois_dernier': 'Mois dernier',
        '3_derniers_mois': '3 derniers mois',
        'cette_annee': 'Cette année'
    }
    return periods[periode] || ''
}

const exportData = () => {
    // Préparer les données pour l'export
    const dataToExport = filteredStages.value.map(stage => ({
        'Nom': stage.stagiaire && stage.stagiaire.user ? stage.stagiaire.user.nom : '',
        'Prénom': stage.stagiaire && stage.stagiaire.user ? stage.stagiaire.user.prenom : '',
        'Email': stage.stagiaire && stage.stagiaire.user ? stage.stagiaire.user.email : '',
        'Structure': stage.structure.libelle,
        'Sigle': stage.structure.sigle,
        'Date début': formatDate(stage.date_debut),
        'Date fin': formatDate(stage.date_fin),
        'Attestation structure': formatDate(stage.attestation_structure_date),
        'Attestation DPAF': stage.attestation_dpaf_generee ? 'Générée' : 'En attente',
        'Date attestation DPAF': stage.attestation_dpaf_generee ? formatDate(stage.attestation_dpaf_date) : '-',
        'Numéro attestation': stage.numero_attestation_dpaf || '-'
    }))

    // Convertir en CSV
    const headers = Object.keys(dataToExport[0] || {})
    const csvContent = [
        headers.join(','),
        ...dataToExport.map(row =>
            headers.map(header => `"${row[header] || ''}"`).join(',')
        )
    ].join('\n')

    // Télécharger le fichier
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' })
    const link = document.createElement('a')
    const url = URL.createObjectURL(blob)
    link.setAttribute('href', url)
    link.setAttribute('download', `attestations-dpaf-${new Date().toISOString().split('T')[0]}.csv`)
    link.style.visibility = 'hidden'
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
}

const generateAttestation = (stageId) => {
    // Générer l'attestation DPAF puis l'ouvrir directement
    router.post(route('agent.dpaf.attestations.generate', stageId), {}, {
        onSuccess: (response) => {
            console.log('Génération réussie:', response)
            // Recharger la page pour voir les changements
            router.reload({
                onSuccess: () => {
                    // Après le rechargement, ouvrir l'attestation
                    setTimeout(() => {
                        window.open(route('agent.dpaf.attestations.show', stageId), '_blank')
                    }, 1000)
                }
            })
        },
        onError: (errors) => {
            console.error('Erreur lors de la génération de l\'attestation DPAF:', errors)
            alert('Erreur lors de la génération de l\'attestation DPAF: ' + JSON.stringify(errors))
        }
    })
}

const viewAttestation = (stageId) => {
    window.open(route('agent.dpaf.attestations.show', stageId), '_blank')
}

const revokeAttestation = (stageId) => {
    if (confirm('Êtes-vous sûr de vouloir révoquer cette attestation DPAF ?')) {
        router.delete(route('agent.dpaf.attestations.revoke', stageId), {
            onSuccess: () => {
                router.reload()
            }
        })
    }
}

const getInitials = (prenom, nom) => {
    return (prenom?.charAt(0) || '') + (nom?.charAt(0) || '')
}

const formatDate = (date) => {
    if (!date) return '-'
    return new Date(date).toLocaleDateString('fr-FR')
}

const isThisMonth = (date) => {
    if (!date) return false
    const now = new Date()
    const dateObj = new Date(date)
    return dateObj.getMonth() === now.getMonth() && dateObj.getFullYear() === now.getFullYear()
}
</script>
