<template>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- En-tête -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    🎯 Système de Validation des Conflits de Période
                </h1>
                <p class="text-lg text-gray-600">
                    Démonstration des composants modernes de validation en temps réel
                </p>
            </div>

            <!-- Statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Validation</p>
                            <p class="text-2xl font-semibold text-gray-900">Temps Réel</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.196-2.121M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.196-2.121M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Membres</p>
                            <p class="text-2xl font-semibold text-gray-900">Dynamiques</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-100 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Performance</p>
                            <p class="text-2xl font-semibold text-gray-900">Optimisée</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulaire de démonstration -->
            <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">
                    Testez la Validation en Temps Réel
                </h2>

                <form @submit.prevent="handleSubmit" class="space-y-8">
                    <!-- Sélecteur de période -->
                    <PeriodSelector
                        v-model:date-debut="form.dateDebut"
                        v-model:date-fin="form.dateFin"
                        :errors="errors"
                        @conflict="handlePeriodConflict"
                        @valid="handlePeriodValid"
                    />

                    <!-- Nature du stage -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Nature du stage</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative">
                                <input
                                    type="radio"
                                    v-model="form.nature"
                                    value="Individuel"
                                    class="sr-only"
                                />
                                <div :class="[
                                    'p-4 border-2 rounded-lg cursor-pointer transition-all duration-200',
                                    form.nature === 'Individuel' 
                                        ? 'border-blue-500 bg-blue-50' 
                                        : 'border-gray-200 hover:border-gray-300'
                                ]">
                                    <div class="flex items-center space-x-3">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        <div>
                                            <h4 class="font-medium text-gray-900">Individuel</h4>
                                            <p class="text-sm text-gray-600">Stage en solo</p>
                                        </div>
                                    </div>
                                </div>
                            </label>

                            <label class="relative">
                                <input
                                    type="radio"
                                    v-model="form.nature"
                                    value="Groupe"
                                    class="sr-only"
                                />
                                <div :class="[
                                    'p-4 border-2 rounded-lg cursor-pointer transition-all duration-200',
                                    form.nature === 'Groupe' 
                                        ? 'border-blue-500 bg-blue-50' 
                                        : 'border-gray-200 hover:border-gray-300'
                                ]">
                                    <div class="flex items-center space-x-3">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.196-2.121M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.196-2.121M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        <div>
                                            <h4 class="font-medium text-gray-900">Groupe</h4>
                                            <p class="text-sm text-gray-600">Stage en équipe</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Sélecteur de membres (si groupe) -->
                    <div v-if="form.nature === 'Groupe'">
                        <MemberSelector
                            :date-debut="form.dateDebut"
                            :date-fin="form.dateFin"
                            v-model:selected-member-ids="form.membres"
                            :errors="errors"
                            @members-change="handleMembersChange"
                        />
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                        <div class="flex space-x-4">
                            <button
                                type="button"
                                @click="testSuccess"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200"
                            >
                                Test Succès
                            </button>
                            <button
                                type="button"
                                @click="testError"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200"
                            >
                                Test Erreur
                            </button>
                            <button
                                type="button"
                                @click="testWarning"
                                class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors duration-200"
                            >
                                Test Avertissement
                            </button>
                        </div>

                        <button
                            type="submit"
                            :disabled="hasPeriodConflict || isSubmitting"
                            class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            {{ isSubmitting ? 'Validation...' : 'Valider la Démonstration' }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Informations sur l'état -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- État de la période -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">État de la Période</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Date de début:</span>
                            <span class="text-sm font-medium">{{ form.dateDebut || 'Non définie' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Date de fin:</span>
                            <span class="text-sm font-medium">{{ form.dateFin || 'Non définie' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Conflit détecté:</span>
                            <span :class="[
                                'text-sm font-medium',
                                hasPeriodConflict ? 'text-red-600' : 'text-green-600'
                            ]">
                                {{ hasPeriodConflict ? 'Oui' : 'Non' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- État des membres -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">État des Membres</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Nature:</span>
                            <span class="text-sm font-medium">{{ form.nature }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Membres sélectionnés:</span>
                            <span class="text-sm font-medium">{{ form.membres.length }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Validation:</span>
                            <span :class="[
                                'text-sm font-medium',
                                form.nature === 'Groupe' && form.membres.length === 0 ? 'text-yellow-600' : 'text-green-600'
                            ]">
                                {{ form.nature === 'Groupe' && form.membres.length === 0 ? 'En attente' : 'Valide' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Container de toasts -->
        <ToastContainer />
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import PeriodSelector from '@/Components/PeriodSelector.vue'
import MemberSelector from '@/Components/MemberSelector.vue'
import ToastContainer from '@/Components/ToastContainer.vue'
import { useToast } from '@/Composables/useToast'

// Composables
const { success, error, warning, info } = useToast()

// État réactif
const form = ref({
    dateDebut: '',
    dateFin: '',
    nature: 'Individuel',
    membres: []
})

const errors = ref({})
const hasPeriodConflict = ref(false)
const isSubmitting = ref(false)

// Méthodes
const handlePeriodConflict = (hasConflict) => {
    hasPeriodConflict.value = hasConflict
}

const handlePeriodValid = (isValid) => {
    hasPeriodConflict.value = !isValid
}

const handleMembersChange = (members) => {
    form.value.membres = members.map(m => m.id)
}

const handleSubmit = () => {
    if (hasPeriodConflict.value) {
        error('Impossible de valider : conflit de période détecté')
        return
    }

    isSubmitting.value = true
    
    // Simulation d'une soumission
    setTimeout(() => {
        isSubmitting.value = false
        success('Validation réussie !', 'Tous les contrôles sont passés avec succès')
    }, 2000)
}

// Tests des notifications
const testSuccess = () => {
    success('Opération réussie !', 'Ceci est un message de succès')
}

const testError = () => {
    error('Une erreur est survenue', 'Ceci est un message d\'erreur')
}

const testWarning = () => {
    warning('Attention !', 'Ceci est un message d\'avertissement')
}
</script>

<style scoped>
/* Styles personnalisés pour la démo */
.demo-card {
    transition: transform 0.2s ease-in-out;
}

.demo-card:hover {
    transform: translateY(-2px);
}
</style>
