<template>
    <div class="space-y-4">
        <!-- Titre de la section -->
        <div class="flex items-center space-x-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900">Période du stage</h3>
        </div>

        <!-- Champs de dates -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Date de début -->
            <div>
                <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-2">
                    Date de début <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input
                        id="date_debut"
                        type="date"
                        v-model="localDateDebut"
                        :min="minDate"
                        :class="[
                            'w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200',
                            hasConflict ? 'border-red-300 bg-red-50' : 'border-gray-300'
                        ]"
                        @change="handleDateChange"
                        required
                    />
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <div v-if="errors.date_debut" class="mt-1 text-sm text-red-600">
                    {{ errors.date_debut }}
                </div>
            </div>

            <!-- Date de fin -->
            <div>
                <label for="date_fin" class="block text-sm font-medium text-gray-700 mb-2">
                    Date de fin <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input
                        id="date_fin"
                        type="date"
                        v-model="localDateFin"
                        :min="localDateDebut || minDate"
                        :class="[
                            'w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200',
                            hasConflict ? 'border-red-300 bg-red-50' : 'border-gray-300'
                        ]"
                        @change="handleDateChange"
                        required
                    />
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <div v-if="errors.date_fin" class="mt-1 text-sm text-red-600">
                    {{ errors.date_fin }}
                </div>
            </div>
        </div>

        <!-- Indicateur de vérification -->
        <div v-if="isCheckingPeriod" class="flex items-center space-x-2 text-blue-600">
            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-sm">Vérification de la disponibilité...</span>
        </div>

        <!-- Message de conflit -->
        <div v-if="hasConflict && !isCheckingPeriod" class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-start space-x-3">
                <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <div class="flex-1">
                    <h4 class="text-sm font-medium text-red-800 mb-1">Conflit de période détecté</h4>
                    <p class="text-sm text-red-700 mb-3">{{ conflictMessage }}</p>
                    
                    <!-- Détails du conflit -->
                    <div v-if="periodConflict" class="bg-white rounded-md p-3 border border-red-200">
                        <div class="text-xs text-red-600 space-y-1">
                            <div><strong>Type :</strong> {{ periodConflict.conflictType === 'demande' ? 'Demande de stage' : 'Stage' }}</div>
                            <div v-if="periodConflict.conflictData.code_suivi">
                                <strong>Code :</strong> {{ periodConflict.conflictData.code_suivi }}
                            </div>
                            <div><strong>Statut :</strong> {{ periodConflict.conflictData.statut }}</div>
                            <div><strong>Structure :</strong> {{ periodConflict.conflictData.structure }}</div>
                        </div>
                    </div>

                    <!-- Suggestion -->
                    <div class="mt-3 text-sm text-red-700">
                        <strong>Suggestion :</strong> {{ getSuggestion() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Message de succès -->
        <div v-if="!hasConflict && !isCheckingPeriod && localDateDebut && localDateFin" 
             class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h4 class="text-sm font-medium text-green-800">Période disponible</h4>
                    <p class="text-sm text-green-700">
                        Du {{ formatDate(localDateDebut) }} au {{ formatDate(localDateFin) }}
                        ({{ calculateDuration() }} jours)
                    </p>
                </div>
            </div>
        </div>

        <!-- Informations sur la durée -->
        <div v-if="localDateDebut && localDateFin && !hasConflict" class="text-sm text-gray-600">
            <div class="flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Durée du stage : {{ calculateDuration() }} jours</span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { useAvailability } from '@/Composables/useAvailability'

// Props
const props = defineProps({
    dateDebut: {
        type: String,
        default: ''
    },
    dateFin: {
        type: String,
        default: ''
    },
    errors: {
        type: Object,
        default: () => ({})
    },
    demandeId: {
        type: Number,
        default: null
    }
})

// Emits
const emit = defineEmits(['update:dateDebut', 'update:dateFin', 'conflict', 'valid'])

// Composable
const {
    isCheckingPeriod,
    hasConflict,
    conflictMessage,
    periodConflict,
    checkPeriodAvailability,
    formatDate,
    getSuggestion
} = useAvailability()

// État local
const localDateDebut = ref(props.dateDebut)
const localDateFin = ref(props.dateFin)

// Date minimum (aujourd'hui)
const minDate = computed(() => {
    const today = new Date()
    return today.toISOString().split('T')[0]
})

// Gestion des changements de dates
const handleDateChange = async () => {
    // Émettre les changements vers le parent
    emit('update:dateDebut', localDateDebut.value)
    emit('update:dateFin', localDateFin.value)

    // Vérifier la disponibilité si les deux dates sont définies
    if (localDateDebut.value && localDateFin.value) {
        const result = await checkPeriodAvailability(
            localDateDebut.value,
            localDateFin.value,
            props.demandeId
        )

        // Émettre l'état du conflit
        emit('conflict', !result.available)
        emit('valid', result.available)
    }
}

// Calculer la durée en jours
const calculateDuration = () => {
    if (!localDateDebut.value || !localDateFin.value) return 0
    
    const debut = new Date(localDateDebut.value)
    const fin = new Date(localDateFin.value)
    const diffTime = Math.abs(fin - debut)
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1
    
    return diffDays
}

// Watchers pour synchroniser avec les props
watch(() => props.dateDebut, (newVal) => {
    localDateDebut.value = newVal
})

watch(() => props.dateFin, (newVal) => {
    localDateFin.value = newVal
})

// Vérification initiale si les dates sont déjà définies
onMounted(() => {
    if (localDateDebut.value && localDateFin.value) {
        handleDateChange()
    }
})
</script>

<style scoped>
/* Animations pour les transitions */
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from, .fade-leave-to {
    opacity: 0;
}

/* Style pour les champs en erreur */
input.border-red-300:focus {
    border-color: #fca5a5;
    box-shadow: 0 0 0 3px rgba(252, 165, 165, 0.1);
}
</style>
