<template>
    <Teleport to="body">
        <div class="fixed top-4 right-4 z-50 space-y-2">
            <TransitionGroup
                name="toast"
                tag="div"
                class="space-y-2"
            >
                <div
                    v-for="toast in toasts"
                    :key="toast.id"
                    :class="[
                        'max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden',
                        'transform transition-all duration-300 ease-in-out'
                    ]"
                >
                    <div class="p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <!-- Icône de succès -->
                                <svg
                                    v-if="toast.type === 'success'"
                                    class="h-6 w-6 text-green-400"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                
                                <!-- Icône d'erreur -->
                                <svg
                                    v-else-if="toast.type === 'error'"
                                    class="h-6 w-6 text-red-400"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                                
                                <!-- Icône d'avertissement -->
                                <svg
                                    v-else-if="toast.type === 'warning'"
                                    class="h-6 w-6 text-yellow-400"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                                
                                <!-- Icône d'information -->
                                <svg
                                    v-else
                                    class="h-6 w-6 text-blue-400"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            
                            <div class="ml-3 w-0 flex-1 pt-0.5">
                                <p v-if="toast.title" class="text-sm font-medium text-gray-900">
                                    {{ toast.title }}
                                </p>
                                <p class="text-sm text-gray-500" :class="{ 'mt-1': toast.title }">
                                    {{ toast.message }}
                                </p>
                            </div>
                            
                            <div class="ml-4 flex-shrink-0 flex">
                                <button
                                    @click="removeToast(toast.id)"
                                    class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    <span class="sr-only">Fermer</span>
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Barre de progression -->
                    <div
                        v-if="toast.duration > 0"
                        class="h-1 bg-gray-200"
                    >
                        <div
                            :class="[
                                'h-full transition-all ease-linear',
                                toast.type === 'success' ? 'bg-green-400' :
                                toast.type === 'error' ? 'bg-red-400' :
                                toast.type === 'warning' ? 'bg-yellow-400' : 'bg-blue-400'
                            ]"
                            :style="{ 
                                width: `${(toast.remainingTime / toast.duration) * 100}%`,
                                transitionDuration: '100ms'
                            }"
                        ></div>
                    </div>
                </div>
            </TransitionGroup>
        </div>
    </Teleport>
</template>

<script setup>
import { useToast } from '@/Composables/useToast'

// Utiliser le composable global
const { toasts, removeToast } = useToast()
</script>

<style scoped>
/* Animations pour les toasts */
.toast-enter-active {
    transition: all 0.3s ease-out;
}

.toast-leave-active {
    transition: all 0.3s ease-in;
}

.toast-enter-from {
    transform: translateX(100%);
    opacity: 0;
}

.toast-leave-to {
    transform: translateX(100%);
    opacity: 0;
}

.toast-move {
    transition: transform 0.3s ease;
}
</style>
