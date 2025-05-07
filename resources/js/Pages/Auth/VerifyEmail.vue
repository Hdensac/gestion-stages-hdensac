<script setup>
import { computed, ref } from 'vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
    email: {
        type: String,
    }
});

const form = useForm({});
const isLoading = ref(false);

const submit = () => {
    isLoading.value = true;
    form.post(route('verification.send'), {
        onFinish: () => {
            isLoading.value = false;
        },
    });
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <div class="min-h-screen flex flex-col justify-center items-center px-4 bg-gray-50">
        <div class="max-w-md w-full">
            <Head title="Vérification d'email" />

            <!-- Logo et titre -->
            <div class="mb-6 text-center">
                <div class="flex justify-center mb-3">
                    <img src="/images/logoministere.png" alt="MINISTERE DE L'ECONOMIE ET DES FINANCES" class="h-20 w-auto transition-transform duration-300 hover:scale-105" />
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Vérification d'email</h1>
                <p class="text-gray-600 mt-1">Programme de Stages - Ministère des Finances</p>
                <p class="text-gray-500 text-sm mt-1">RÉPUBLIQUE DU BÉNIN</p>
            </div>

            <!-- Carte principale avec effet d'ombre -->
            <div class="bg-white rounded-xl shadow-xl p-6 transition-all duration-300 hover:shadow-2xl">
                <!-- Icône d'email -->
                <div class="flex justify-center mb-4">
                    <div class="p-3 rounded-full bg-[#054A31]/10 text-[#054A31]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>

                <!-- Message principal -->
                <div class="mb-6 text-center">
                    <p class="text-gray-700 mb-3">
                        Merci pour votre inscription au Programme de Stages !
                    </p>
                    <p class="text-gray-600">
                        Avant de commencer, veuillez vérifier votre adresse email en cliquant sur le lien que nous venons de vous envoyer.
                    </p>
                    <p class="text-gray-600 mt-2">
                        Si vous n'avez pas reçu l'email, cliquez sur le bouton ci-dessous pour recevoir un nouveau lien.
                    </p>
                </div>

                <!-- Message de succès -->
                <div 
                    v-if="verificationLinkSent" 
                    class="mb-4 p-4 rounded-lg bg-green-50 border-l-4 border-green-500 flex items-start"
                >
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-700">
                            Un nouveau lien de vérification a été envoyé à votre adresse email.
                        </p>
                    </div>
                </div>

                <!-- Formulaire -->
                <form @submit.prevent="submit">
                    <div class="mt-6 flex flex-col space-y-4">
                        <button
                            type="submit"
                            class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-md shadow-sm text-white bg-[#054A31] hover:bg-[#043824] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#054A31] transition-all duration-300 transform hover:scale-[1.02]"
                            :class="{ 'opacity-75 cursor-not-allowed': form.processing || isLoading }"
                            :disabled="form.processing || isLoading"
                        >
                            <svg v-if="isLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Renvoyer l'email de vérification</span>
                        </button>
                        
                        <Link
                            :href="route('logout')"
                            method="post"
                            as="button"
                            class="text-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#054A31] transition-all duration-300"
                        >
                            Se déconnecter
                        </Link>
                    </div>
                </form>
            </div>

            <!-- Aide supplémentaire -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Besoin d'aide ? 
                    <a href="#" class="font-medium text-[#054A31] hover:text-[#043824] hover:underline transition duration-200">
                        Contactez-nous
                    </a>
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Animation pour le bouton */
@keyframes pulse {
  0%, 100% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.02);
  }
}

/* Animation pour le loading */
@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

.animate-spin {
  animation: spin 1s linear infinite;
}
</style>