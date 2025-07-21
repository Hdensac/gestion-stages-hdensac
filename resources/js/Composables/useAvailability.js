import { ref, computed, watch } from 'vue'
import axios from 'axios'

export function useAvailability() {
    // État réactif
    const isCheckingPeriod = ref(false)
    const isLoadingMembers = ref(false)
    const periodConflict = ref(null)
    const availableMembers = ref([])
    const selectedMembers = ref([])
    const memberConflicts = ref({})

    // Validation de période pour le stagiaire principal
    const checkPeriodAvailability = async (dateDebut, dateFin, demandeId = null) => {
        if (!dateDebut || !dateFin) {
            periodConflict.value = null
            return { available: true }
        }

        isCheckingPeriod.value = true
        periodConflict.value = null

        try {
            const response = await axios.post('/api/availability/check-period', {
                date_debut: dateDebut,
                date_fin: dateFin,
                demande_id: demandeId
            })

            if (response.data.success && response.data.available) {
                return { available: true }
            } else {
                periodConflict.value = response.data.conflict
                return {
                    available: false,
                    message: response.data.message,
                    conflict: response.data.conflict
                }
            }
        } catch (error) {
            console.error('Erreur lors de la vérification de période:', error)
            const message = error.response?.data?.message || 'Erreur lors de la vérification de disponibilité'
            
            if (error.response?.data?.conflict) {
                periodConflict.value = error.response.data.conflict
            }
            
            return {
                available: false,
                message: message,
                error: true
            }
        } finally {
            isCheckingPeriod.value = false
        }
    }

    // Récupération des membres disponibles
    const loadAvailableMembers = async (dateDebut, dateFin) => {
        if (!dateDebut || !dateFin) {
            availableMembers.value = []
            return
        }

        isLoadingMembers.value = true

        try {
            const response = await axios.post('/api/availability/available-members', {
                date_debut: dateDebut,
                date_fin: dateFin
            })

            if (response.data.success) {
                availableMembers.value = response.data.members
            } else {
                availableMembers.value = []
                console.error('Erreur lors du chargement des membres:', response.data.message)
            }
        } catch (error) {
            console.error('Erreur lors du chargement des membres:', error)
            availableMembers.value = []
        } finally {
            isLoadingMembers.value = false
        }
    }

    // Vérification de disponibilité d'un membre spécifique
    const checkMemberAvailability = async (userId, dateDebut, dateFin) => {
        try {
            const response = await axios.post('/api/availability/check-member', {
                user_id: userId,
                date_debut: dateDebut,
                date_fin: dateFin
            })

            return response.data
        } catch (error) {
            console.error('Erreur lors de la vérification du membre:', error)
            return {
                success: false,
                available: false,
                reason: 'Erreur de vérification'
            }
        }
    }

    // Vérification de plusieurs membres en une fois
    const checkMultipleMembers = async (userIds, dateDebut, dateFin) => {
        if (!userIds.length || !dateDebut || !dateFin) {
            return { success: true, all_available: true, results: [] }
        }

        try {
            const response = await axios.post('/api/availability/check-multiple-members', {
                user_ids: userIds,
                date_debut: dateDebut,
                date_fin: dateFin
            })

            if (response.data.success) {
                // Mettre à jour les conflits des membres
                const conflicts = {}
                response.data.results.forEach(result => {
                    if (!result.available) {
                        conflicts[result.user_id] = {
                            reason: result.reason,
                            conflict_data: result.conflict_data
                        }
                    }
                })
                memberConflicts.value = conflicts
            }

            return response.data
        } catch (error) {
            console.error('Erreur lors de la vérification des membres:', error)
            return {
                success: false,
                all_available: false,
                results: []
            }
        }
    }

    // Ajouter un membre à la sélection
    const addMember = async (member, dateDebut, dateFin) => {
        // Vérifier si le membre n'est pas déjà sélectionné
        if (selectedMembers.value.find(m => m.id === member.id)) {
            return { success: false, message: 'Ce membre est déjà sélectionné' }
        }

        // Vérifier la disponibilité si les dates sont définies
        if (dateDebut && dateFin) {
            const availability = await checkMemberAvailability(member.id, dateDebut, dateFin)
            
            if (!availability.available) {
                return {
                    success: false,
                    message: `${member.name} ${member.prenom} n'est pas disponible : ${availability.reason}`
                }
            }
        }

        selectedMembers.value.push(member)
        return { success: true }
    }

    // Supprimer un membre de la sélection
    const removeMember = (memberId) => {
        selectedMembers.value = selectedMembers.value.filter(m => m.id !== memberId)
        // Supprimer le conflit s'il existe
        if (memberConflicts.value[memberId]) {
            delete memberConflicts.value[memberId]
        }
    }

    // Réinitialiser la sélection de membres
    const clearMembers = () => {
        selectedMembers.value = []
        memberConflicts.value = {}
    }

    // Computed properties
    const hasConflict = computed(() => periodConflict.value !== null)
    
    const conflictMessage = computed(() => {
        if (!periodConflict.value) return null
        
        const data = periodConflict.value.conflictData
        const type = periodConflict.value.conflictType
        
        if (type === 'demande') {
            return `Vous avez déjà une demande ${data.statut} (${data.code_suivi}) du ${data.date_debut} au ${data.date_fin} dans "${data.structure}"`
        } else {
            return `Vous avez déjà un stage ${data.statut} du ${data.date_debut} au ${data.date_fin} dans "${data.structure}"`
        }
    })

    const hasSelectedMembers = computed(() => selectedMembers.value.length > 0)
    
    const selectedMemberIds = computed(() => selectedMembers.value.map(m => m.id))

    const hasMemberConflicts = computed(() => Object.keys(memberConflicts.value).length > 0)

    // Fonction utilitaire pour formater les dates
    const formatDate = (date) => {
        if (!date) return ''
        return new Date(date).toLocaleDateString('fr-FR')
    }

    // Fonction pour générer un message de suggestion
    const getSuggestion = () => {
        if (hasConflict.value) {
            return "Modifiez les dates de votre demande ou attendez la fin de votre stage/demande actuel(le)."
        }
        return null
    }

    return {
        // État
        isCheckingPeriod,
        isLoadingMembers,
        periodConflict,
        availableMembers,
        selectedMembers,
        memberConflicts,

        // Méthodes
        checkPeriodAvailability,
        loadAvailableMembers,
        checkMemberAvailability,
        checkMultipleMembers,
        addMember,
        removeMember,
        clearMembers,

        // Computed
        hasConflict,
        conflictMessage,
        hasSelectedMembers,
        selectedMemberIds,
        hasMemberConflicts,

        // Utilitaires
        formatDate,
        getSuggestion
    }
}
