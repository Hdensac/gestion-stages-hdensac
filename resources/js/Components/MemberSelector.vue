<template>
    <div class="space-y-4">
        <!-- Titre de la section -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.196-2.121M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.196-2.121M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900">Membres du groupe</h3>
                <span v-if="hasSelectedMembers" class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                    {{ selectedMembers.length }} membre{{ selectedMembers.length > 1 ? 's' : '' }}
                </span>
            </div>
            
            <!-- Bouton pour actualiser la liste -->
            <button
                type="button"
                @click="refreshMembers"
                :disabled="!dateDebut || !dateFin || isLoadingMembers"
                class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <svg class="w-3 h-3 mr-1" :class="{ 'animate-spin': isLoadingMembers }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Actualiser
            </button>
        </div>

        <!-- Message d'information -->
        <div v-if="!dateDebut || !dateFin" class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <p class="text-sm text-yellow-800">
                    Veuillez d'abord sélectionner les dates du stage pour voir les membres disponibles.
                </p>
            </div>
        </div>

        <!-- Membres sélectionnés -->
        <div v-if="hasSelectedMembers" class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h4 class="text-sm font-medium text-blue-900 mb-3">Membres sélectionnés</h4>
            <div class="space-y-2">
                <div
                    v-for="member in selectedMembers"
                    :key="member.id"
                    class="flex items-center justify-between bg-white rounded-lg p-3 border border-blue-200"
                >
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-sm font-medium text-blue-600">
                                {{ member.name.charAt(0) }}{{ member.prenom.charAt(0) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">
                                {{ member.name }} {{ member.prenom }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ member.niveau_etude }} • {{ member.filiere }}
                            </p>
                        </div>
                    </div>
                    
                    <!-- Indicateur de conflit -->
                    <div class="flex items-center space-x-2">
                        <div v-if="memberConflicts[member.id]" class="text-red-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <button
                            type="button"
                            @click="handleRemoveMemberCorrect(member.id)"
                            class="text-red-500 hover:text-red-700 transition-colors duration-200"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Messages de conflit des membres -->
            <div v-if="hasMemberConflicts" class="mt-3 space-y-2">
                <div
                    v-for="(conflict, memberId) in memberConflicts"
                    :key="memberId"
                    class="bg-red-50 border border-red-200 rounded-md p-2"
                >
                    <p class="text-xs text-red-700">
                        <strong>{{ getMemberName(memberId) }} :</strong> {{ conflict.reason }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Liste des membres disponibles -->
        <div v-if="dateDebut && dateFin" class="space-y-3">
            <div class="flex items-center justify-between">
                <h4 class="text-sm font-medium text-gray-900">Membres disponibles</h4>
                <div v-if="isLoadingMembers" class="flex items-center space-x-2 text-blue-600">
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-sm">Chargement...</span>
                </div>
            </div>

            <!-- Champ de recherche -->
            <div class="relative">
                <input
                    type="text"
                    v-model="searchQuery"
                    placeholder="Rechercher un membre..."
                    class="w-full px-3 py-2 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                />
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Liste filtrée -->
            <div class="max-h-64 overflow-y-auto border border-gray-200 rounded-lg">
                <div v-if="filteredMembers.length === 0 && !isLoadingMembers" class="p-4 text-center text-gray-500">
                    <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.196-2.121M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.196-2.121M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <p class="text-sm">Aucun membre disponible pour cette période</p>
                </div>

                <div
                    v-for="member in filteredMembers"
                    :key="member.id"
                    class="flex items-center justify-between p-3 hover:bg-gray-50 border-b border-gray-100 last:border-b-0 cursor-pointer"
                    @click="handleAddMember(member)"
                >
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                            <span class="text-sm font-medium text-gray-600">
                                {{ member.name.charAt(0) }}{{ member.prenom.charAt(0) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">
                                {{ member.name }} {{ member.prenom }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ member.niveau_etude }} • {{ member.filiere }}
                            </p>
                        </div>
                    </div>
                    
                    <button
                        type="button"
                        class="text-blue-600 hover:text-blue-800 transition-colors duration-200"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Messages d'erreur -->
        <div v-if="errors.membres" class="text-sm text-red-600">
            {{ errors.membres }}
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { useAvailability } from '@/Composables/useAvailability'
import { useToast } from '@/Composables/useToast'

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
    selectedMemberIds: {
        type: Array,
        default: () => []
    },
    errors: {
        type: Object,
        default: () => ({})
    }
})

// Emits
const emit = defineEmits(['update:selectedMemberIds', 'membersChange'])

// Composables
const {
    isLoadingMembers,
    availableMembers,
    selectedMembers,
    memberConflicts,
    hasSelectedMembers,
    hasMemberConflicts,
    loadAvailableMembers,
    addMember,
    removeMember,
    checkMultipleMembers
} = useAvailability()

const { success, error } = useToast()

// État local
const searchQuery = ref('')

// Computed
const filteredMembers = computed(() => {
    if (!searchQuery.value) return availableMembers.value
    
    const query = searchQuery.value.toLowerCase()
    return availableMembers.value.filter(member => 
        member.name.toLowerCase().includes(query) ||
        member.prenom.toLowerCase().includes(query) ||
        member.filiere.toLowerCase().includes(query) ||
        member.niveau_etude.toLowerCase().includes(query)
    )
})

// Méthodes
const refreshMembers = async () => {
    if (props.dateDebut && props.dateFin) {
        await loadAvailableMembers(props.dateDebut, props.dateFin)
        
        // Vérifier les membres déjà sélectionnés
        if (hasSelectedMembers.value) {
            const memberIds = selectedMembers.value.map(m => m.id)
            await checkMultipleMembers(memberIds, props.dateDebut, props.dateFin)
        }
    }
}

const handleAddMember = async (member) => {
    const result = await addMember(member, props.dateDebut, props.dateFin)

    if (result.success) {
        // Émettre les changements
        const memberIds = selectedMembers.value.map(m => m.id)
        emit('update:selectedMemberIds', memberIds)
        emit('membersChange', selectedMembers.value)

        // Notification de succès
        success(`${member.name} ${member.prenom} a été ajouté au groupe`)
    } else {
        // Notification d'erreur
        error(result.message, 'Impossible d\'ajouter le membre')
    }
}

const handleRemoveMember = (memberId) => {
    removeMember(memberId)

    // Émettre les changements
    const memberIds = selectedMembers.value.map(m => m.id)
    emit('update:selectedMemberIds', memberIds)
    emit('membersChange', selectedMembers.value)
}

// Corriger la référence à la méthode
const { removeMember: removeSelectedMember } = useAvailability()

const handleRemoveMemberCorrect = (memberId) => {
    removeSelectedMember(memberId)

    // Émettre les changements
    const memberIds = selectedMembers.value.map(m => m.id)
    emit('update:selectedMemberIds', memberIds)
    emit('membersChange', selectedMembers.value)
}

const getMemberName = (memberId) => {
    const member = selectedMembers.value.find(m => m.id === parseInt(memberId))
    return member ? `${member.name} ${member.prenom}` : 'Membre inconnu'
}

// Watchers
watch([() => props.dateDebut, () => props.dateFin], () => {
    refreshMembers()
}, { immediate: true })

// Synchroniser les membres sélectionnés avec les props
watch(() => props.selectedMemberIds, (newIds) => {
    // Cette logique peut être ajoutée si nécessaire pour synchroniser
    // les membres sélectionnés depuis le parent
}, { immediate: true })

onMounted(() => {
    if (props.dateDebut && props.dateFin) {
        refreshMembers()
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

/* Scrollbar personnalisée */
.max-h-64::-webkit-scrollbar {
    width: 6px;
}

.max-h-64::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.max-h-64::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.max-h-64::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
