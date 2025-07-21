import { ref } from 'vue'

// État global des toasts
const toasts = ref([])
let toastId = 0
const intervals = new Map()

export function useToast() {
    // Ajouter un toast
    const addToast = (options) => {
        const id = ++toastId
        const duration = options.duration || 5000
        
        const toast = {
            id,
            type: options.type || 'info',
            title: options.title,
            message: options.message,
            duration,
            remainingTime: duration
        }
        
        toasts.value.push(toast)
        
        // Démarrer le timer si une durée est définie
        if (duration > 0) {
            startTimer(toast)
        }
        
        return id
    }

    // Supprimer un toast
    const removeToast = (id) => {
        const index = toasts.value.findIndex(toast => toast.id === id)
        if (index > -1) {
            // Arrêter le timer
            if (intervals.has(id)) {
                clearInterval(intervals.get(id))
                intervals.delete(id)
            }
            
            toasts.value.splice(index, 1)
        }
    }

    // Démarrer le timer pour un toast
    const startTimer = (toast) => {
        const interval = setInterval(() => {
            toast.remainingTime -= 100
            
            if (toast.remainingTime <= 0) {
                removeToast(toast.id)
            }
        }, 100)
        
        intervals.set(toast.id, interval)
    }

    // Méthodes de convenance
    const success = (message, title = null, duration = 5000) => {
        return addToast({ type: 'success', message, title, duration })
    }

    const error = (message, title = null, duration = 7000) => {
        return addToast({ type: 'error', message, title, duration })
    }

    const warning = (message, title = null, duration = 6000) => {
        return addToast({ type: 'warning', message, title, duration })
    }

    const info = (message, title = null, duration = 5000) => {
        return addToast({ type: 'info', message, title, duration })
    }

    // Supprimer tous les toasts
    const clearAll = () => {
        intervals.forEach(interval => clearInterval(interval))
        intervals.clear()
        toasts.value = []
    }

    return {
        toasts,
        addToast,
        removeToast,
        success,
        error,
        warning,
        info,
        clearAll
    }
}
