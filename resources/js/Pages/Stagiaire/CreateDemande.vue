<template>
    <Head title="Nouvelle Demande de Stage" />
    <Stagiaire :notifications="notifications">
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Nouvelle Demande de Stage</h2>
                        <p class="text-sm text-gray-600">Soumettez votre demande de stage</p>
                    </div>
                </div>
                
                <Link :href="route('mes.demandes')" 
                      class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour
                </Link>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Indicateur de progression -->
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-4">
                            <div :class="[
                                'flex items-center justify-center w-8 h-8 rounded-full text-sm font-medium',
                                step >= 1 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600'
                            ]">
                                1
                            </div>
                            <span :class="step >= 1 ? 'text-blue-600 font-medium' : 'text-gray-500'">
                                Informations personnelles
                            </span>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <div :class="[
                                'flex items-center justify-center w-8 h-8 rounded-full text-sm font-medium',
                                step >= 2 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600'
                            ]">
                                2
                            </div>
                            <span :class="step >= 2 ? 'text-blue-600 font-medium' : 'text-gray-500'">
                                Période et structure
                            </span>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <div :class="[
                                'flex items-center justify-center w-8 h-8 rounded-full text-sm font-medium',
                                step >= 3 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600'
                            ]">
                                3
                            </div>
                            <span :class="step >= 3 ? 'text-blue-600 font-medium' : 'text-gray-500'">
                                Membres et documents
                            </span>
                        </div>
                    </div>
                    
                    <!-- Barre de progression -->
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div 
                            class="bg-blue-600 h-2 rounded-full transition-all duration-300 ease-in-out"
                            :style="{ width: `${(step / 3) * 100}%` }"
                        ></div>
                    </div>
                </div>

                <!-- Formulaire -->
                <form @submit.prevent="submitForm" class="space-y-8">
                    <!-- Étape 1: Informations personnelles -->
                    <div v-show="step === 1" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center space-x-2 mb-6">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900">Informations personnelles</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nom -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nom <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    v-model="form.nom"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                />
                                <div v-if="form.errors.nom" class="mt-1 text-sm text-red-600">{{ form.errors.nom }}</div>
                            </div>

                            <!-- Prénom -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Prénom <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    v-model="form.prenom"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                />
                                <div v-if="form.errors.prenom" class="mt-1 text-sm text-red-600">{{ form.errors.prenom }}</div>
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="email"
                                    v-model="form.email"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                />
                                <div v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</div>
                            </div>

                            <!-- Téléphone -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Téléphone <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="tel"
                                    v-model="form.telephone"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                />
                                <div v-if="form.errors.telephone" class="mt-1 text-sm text-red-600">{{ form.errors.telephone }}</div>
                            </div>

                            <!-- Filière -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Filière <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    v-model="form.filiere"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                />
                                <div v-if="form.errors.filiere" class="mt-1 text-sm text-red-600">{{ form.errors.filiere }}</div>
                            </div>

                            <!-- Niveau d'étude -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Niveau d'étude <span class="text-red-500">*</span>
                                </label>
                                <select
                                    v-model="form.niveau_etude"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                >
                                    <option value="">Sélectionner</option>
                                    <option value="Licence 1">Licence 1</option>
                                    <option value="Licence 2">Licence 2</option>
                                    <option value="Licence 3">Licence 3</option>
                                    <option value="Master 1">Master 1</option>
                                    <option value="Master 2">Master 2</option>
                                    <option value="Doctorat">Doctorat</option>
                                </select>
                                <div v-if="form.errors.niveau_etude" class="mt-1 text-sm text-red-600">{{ form.errors.niveau_etude }}</div>
                            </div>

                            <!-- Type de demande -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Type de demande <span class="text-red-500">*</span>
                                </label>
                                <select
                                    v-model="form.type"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                >
                                    <option value="Académique">Académique</option>
                                    <option value="Professionnelle">Professionnelle</option>
                                </select>
                                <div v-if="form.errors.type" class="mt-1 text-sm text-red-600">{{ form.errors.type }}</div>
                            </div>

                            <!-- Université (conditionnel) -->
                            <div v-if="form.type === 'Académique'">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Université <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    v-model="form.universite"
                                    :required="form.type === 'Académique'"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                />
                                <div v-if="form.errors.universite" class="mt-1 text-sm text-red-600">{{ form.errors.universite }}</div>
                            </div>
                        </div>

                        <!-- Boutons navigation -->
                        <div class="flex justify-end mt-8">
                            <button
                                type="button"
                                @click="nextStep"
                                class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                            >
                                Suivant
                            </button>
                        </div>
                    </div>

                    <!-- Étape 2: Période et structure -->
                    <div v-show="step === 2" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <!-- Sélecteur de période -->
                        <PeriodSelector
                            v-model:date-debut="form.date_debut"
                            v-model:date-fin="form.date_fin"
                            :errors="form.errors"
                            @conflict="handlePeriodConflict"
                            @valid="handlePeriodValid"
                        />

                        <!-- Nature du stage -->
                        <div class="mt-8">
                            <div class="flex items-center space-x-2 mb-4">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.196-2.121M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.196-2.121M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-900">Nature du stage</h3>
                            </div>

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

                        <!-- Structure -->
                        <div class="mt-8">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Structure souhaitée
                            </label>
                            <select
                                v-model="form.structure_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            >
                                <option value="">Aucune préférence</option>
                                <option v-for="structure in structures" :key="structure.id" :value="structure.id">
                                    {{ structure.libelle }}
                                </option>
                            </select>
                            <div v-if="form.errors.structure_id" class="mt-1 text-sm text-red-600">{{ form.errors.structure_id }}</div>
                        </div>

                        <!-- Boutons navigation -->
                        <div class="flex justify-between mt-8">
                            <button
                                type="button"
                                @click="prevStep"
                                class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200"
                            >
                                Précédent
                            </button>
                            <button
                                type="button"
                                @click="nextStep"
                                :disabled="hasPeriodConflict"
                                class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                Suivant
                            </button>
                        </div>
                    </div>

                    <!-- Étape 3: Membres et documents -->
                    <div v-show="step === 3" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <!-- Sélection des membres (si groupe) -->
                        <div v-if="form.nature === 'Groupe'" class="mb-8">
                            <MemberSelector
                                :date-debut="form.date_debut"
                                :date-fin="form.date_fin"
                                v-model:selected-member-ids="form.membres"
                                :errors="form.errors"
                                @members-change="handleMembersChange"
                            />
                        </div>

                        <!-- Documents -->
                        <div class="space-y-6">
                            <div class="flex items-center space-x-2 mb-4">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-900">Documents</h3>
                            </div>

                            <!-- Lettre de motivation / CV -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Lettre de motivation / CV
                                </label>
                                <input
                                    type="file"
                                    @change="handleFileUpload('lettre_cv_path', $event)"
                                    accept=".pdf,.doc,.docx"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                />
                                <p class="mt-1 text-xs text-gray-500">Formats acceptés: PDF, DOC, DOCX (max 2MB)</p>
                                <div v-if="form.errors.lettre_cv_path" class="mt-1 text-sm text-red-600">{{ form.errors.lettre_cv_path }}</div>
                            </div>

                            <!-- Photo -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Photo d'identité
                                </label>
                                <input
                                    type="file"
                                    @change="handleFileUpload('visage_path', $event)"
                                    accept=".jpg,.jpeg,.png"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                />
                                <p class="mt-1 text-xs text-gray-500">Formats acceptés: JPG, JPEG, PNG (max 1MB)</p>
                                <div v-if="form.errors.visage_path" class="mt-1 text-sm text-red-600">{{ form.errors.visage_path }}</div>
                            </div>
                        </div>

                        <!-- Boutons navigation -->
                        <div class="flex justify-between mt-8">
                            <button
                                type="button"
                                @click="prevStep"
                                class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200"
                            >
                                Précédent
                            </button>
                            <button
                                type="submit"
                                :disabled="form.processing || hasPeriodConflict"
                                class="px-8 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ form.processing ? 'Soumission...' : 'Soumettre la demande' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal de succès -->
        <div v-if="showSuccessModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Demande soumise avec succès !</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">
                            Votre demande a été enregistrée avec le code de suivi :
                        </p>
                        <p class="text-lg font-bold text-blue-600 mt-2">{{ codeSuivi }}</p>
                    </div>
                    <div class="items-center px-4 py-3">
                        <Link :href="route('mes.demandes')"
                              class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                            Voir mes demandes
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </Stagiaire>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import { ref, computed, watch } from 'vue'
import Stagiaire from '@/Layouts/Stagiaire.vue'
import PeriodSelector from '@/Components/PeriodSelector.vue'
import MemberSelector from '@/Components/MemberSelector.vue'
import { useToast } from '@/Composables/useToast'

// Props
const props = defineProps({
    auth: Object,
    structures: Array,
    stagiaire: Object,
    notifications: {
        type: Array,
        default: () => []
    }
})

// Composables
const { success, error } = useToast()

// État réactif
const step = ref(1)
const hasPeriodConflict = ref(false)
const showSuccessModal = ref(false)
const codeSuivi = ref('')

// Formulaire
const form = useForm({
    nom: props.auth.user.nom || '',
    prenom: props.auth.user.prenom || '',
    email: props.auth.user.email || '',
    telephone: props.auth.user.telephone || '',
    universite: props.stagiaire?.universite || '',
    filiere: props.stagiaire?.filiere || '',
    niveau_etude: props.stagiaire?.niveau_etude || '',
    date_debut: '',
    date_fin: '',
    structure_id: '',
    nature: 'Individuel',
    type: 'Académique',
    lettre_cv_path: null,
    visage_path: null,
    membres: []
})

// Méthodes de navigation
const nextStep = () => {
    if (step.value < 3) {
        step.value++
    }
}

const prevStep = () => {
    if (step.value > 1) {
        step.value--
    }
}

// Gestion des conflits de période
const handlePeriodConflict = (hasConflict) => {
    hasPeriodConflict.value = hasConflict
}

const handlePeriodValid = (isValid) => {
    hasPeriodConflict.value = !isValid
}

// Gestion des membres
const handleMembersChange = (members) => {
    form.membres = members.map(m => m.id)
}

// Gestion des fichiers
const handleFileUpload = (field, event) => {
    const file = event.target.files[0]
    if (file) {
        form[field] = file
    }
}

// Soumission du formulaire
const submitForm = () => {
    if (hasPeriodConflict.value) {
        error('Impossible de soumettre la demande : conflit de période détecté')
        return
    }

    form.post(route('demande_stages.store'), {
        onSuccess: (response) => {
            // Extraire le code de suivi de la réponse
            const code = response?.props?.flash?.code_suivi ||
                        response?.props?.code_suivi ||
                        'N/A'

            codeSuivi.value = code
            showSuccessModal.value = true

            success('Demande soumise avec succès !', `Code de suivi: ${code}`)
        },
        onError: (errors) => {
            console.error('Erreurs de validation:', errors)
            error('Erreur lors de la soumission de la demande')
        }
    })
}

// Watcher pour réinitialiser l'université quand le type change
watch(() => form.type, (newType) => {
    if (newType === 'Professionnelle') {
        form.universite = ''
    }
})

// Watcher pour réinitialiser les membres quand la nature change
watch(() => form.nature, (newNature) => {
    if (newNature === 'Individuel') {
        form.membres = []
    }
})
</script>

<style scoped>
/* Animations pour les transitions d'étapes */
.step-enter-active, .step-leave-active {
    transition: all 0.3s ease;
}

.step-enter-from {
    opacity: 0;
    transform: translateX(30px);
}

.step-leave-to {
    opacity: 0;
    transform: translateX(-30px);
}

/* Style pour les boutons radio personnalisés */
input[type="radio"]:checked + div {
    border-color: #3B82F6;
    background-color: #EFF6FF;
}

/* Animation pour la barre de progression */
.progress-bar {
    transition: width 0.3s ease-in-out;
}
</style>
