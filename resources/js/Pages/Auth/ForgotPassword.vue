<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const isLoading = ref(false);

const submit = () => {
    isLoading.value = true;
    form.post(route('password.email'), {
        onFinish: () => {
            isLoading.value = false;
        },
    });
};
</script>

<template>
    <div class="min-h-screen flex flex-col justify-center items-center px-4 bg-gray-50">
        <div class="max-w-md w-full">
            <Head title="Mot de passe oublié" />

            <!-- Logo et titre -->
            <div class="mb-6 text-center">
                <div class="flex justify-center mb-3">
                    <img src="/images/logoministere.png" alt="MINISTERE DE L'ECONOMIE ET DES FINANCES" class="h-20 w-auto transition-transform duration-300 hover:scale-105" />
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Récupération de mot de passe</h1>
                <p class="text-gray-600 mt-1">Programme d'étapes - Ministère des Finances</p>
                <p class="text-gray-500 text-sm mt-1">RÉPUBLIQUE DU BÉNIN</p>
            </div>

            <!-- Message de statut -->
            <div v-if="status" class="mb-6 rounded-lg bg-green-50 p-4 border-l-4 border-green-500 text-green-700">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">{{ status }}</p>
                    </div>
                </div>
            </div>

            <!-- Carte de récupération avec effet d'ombre -->
            <div class="bg-white rounded-xl shadow-xl p-6 transition-all duration-300 hover:shadow-2xl">
                <div class="mb-5 text-gray-600 text-sm">
                    Vous avez oublié votre mot de passe ? Indiquez-nous votre adresse email et nous vous enverrons un lien de réinitialisation qui vous permettra d'en choisir un nouveau.
                </div>

                <form @submit.prevent="submit">
                    <!-- Email -->
                    <div class="mb-6">
                        <InputLabel for="email" value="Adresse email" class="text-gray-700 font-medium" />
                        
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <TextInput
                                id="email"
                                type="email"
                                class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#00513A] focus:ring focus:ring-[#00513A] focus:ring-opacity-20"
                                v-model="form.email"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="votre@email.com"
                            />
                        </div>
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <!-- Bouton d'envoi -->
                    <div class="flex items-center justify-between">
                        <Link
                            :href="route('login')"
                            class="text-sm text-[#00513A] hover:text-[#003E2C] hover:underline transition duration-200"
                        >
                            Retour à la connexion
                        </Link>
                        
                        <button
                            type="submit"
                            class="flex justify-center items-center py-2.5 px-5 border border-transparent rounded-md shadow-sm text-white bg-[#00513A] hover:bg-[#003E2C] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#00513A] transition-all duration-300 transform hover:scale-[1.02]"
                            :class="{ 'opacity-75 cursor-not-allowed': form.processing || isLoading }"
                            :disabled="form.processing || isLoading"
                        >
                            <svg v-if="isLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Envoyer le lien de réinitialisation</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Lien d'inscription -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Vous n'avez pas encore de compte ? 
                    <Link :href="route('register')" class="font-medium text-[#00513A] hover:text-[#003E2C] hover:underline transition duration-200">
                        S'inscrire maintenant
                    </Link>
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