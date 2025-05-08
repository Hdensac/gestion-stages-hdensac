<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const passwordVisible = ref(false);
const confirmPasswordVisible = ref(false);
const isLoading = ref(false);

const togglePasswordVisibility = () => {
    passwordVisible.value = !passwordVisible.value;
};

const toggleConfirmPasswordVisibility = () => {
    confirmPasswordVisible.value = !confirmPasswordVisible.value;
};

const submit = () => {
    isLoading.value = true;
    form.post(route('password.store'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
            isLoading.value = false;
        },
    });
};
</script>

<template>
    <div class="min-h-screen flex flex-col justify-center items-center px-4 bg-gray-50">
        <div class="max-w-md w-full">
            <Head title="Réinitialisation du mot de passe" />

            <!-- Logo et titre -->
            <div class="mb-6 text-center">
                <div class="flex justify-center mb-3">
                    <img src="/images/logoministere.png" alt="MINISTERE DE L'ECONOMIE ET DES FINANCES" class="h-20 w-auto transition-transform duration-300 hover:scale-105" />
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Réinitialisation du mot de passe</h1>
                <p class="text-gray-600 mt-1">Programme de stages - Ministère des Finances</p>
                <p class="text-gray-500 text-sm mt-1">RÉPUBLIQUE DU BÉNIN</p>
            </div>

            <!-- Carte de réinitialisation avec effet d'ombre -->
            <div class="bg-white rounded-xl shadow-xl p-6 transition-all duration-300 hover:shadow-2xl">
                <form @submit.prevent="submit">
                    <!-- Email (désactivé car prérempli) -->
                    <div class="mb-5">
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
                                class="pl-10 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm focus:border-[#2962B4] focus:ring focus:ring-[#2962B4] focus:ring-opacity-20"
                                v-model="form.email"
                                required
                                readonly
                                autocomplete="username"
                            />
                        </div>
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <!-- Nouveau mot de passe -->
                    <div class="mb-5">
                        <InputLabel for="password" value="Nouveau mot de passe" class="text-gray-700 font-medium" />
                        
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <TextInput
                                id="password"
                                :type="passwordVisible ? 'text' : 'password'"
                                class="pl-10 pr-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2962B4] focus:ring focus:ring-[#2962B4] focus:ring-opacity-20"
                                v-model="form.password"
                                required
                                autocomplete="new-password"
                                placeholder="Minimum 8 caractères"
                            />
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <button type="button" @click="togglePasswordVisibility" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <svg v-if="passwordVisible" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                    <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <InputError class="mt-2" :message="form.errors.password" />
                        <p class="mt-1 text-sm text-gray-500">Utilisez au moins 8 caractères avec des lettres et des chiffres.</p>
                    </div>

                    <!-- Confirmation du mot de passe -->
                    <div class="mb-6">
                        <InputLabel for="password_confirmation" value="Confirmer le mot de passe" class="text-gray-700 font-medium" />
                        
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <TextInput
                                id="password_confirmation"
                                :type="confirmPasswordVisible ? 'text' : 'password'"
                                class="pl-10 pr-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2962B4] focus:ring focus:ring-[#2962B4] focus:ring-opacity-20"
                                v-model="form.password_confirmation"
                                required
                                autocomplete="new-password"
                                placeholder="Retapez votre mot de passe"
                            />
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <button type="button" @click="toggleConfirmPasswordVisibility" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <svg v-if="confirmPasswordVisible" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                    <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <InputError class="mt-2" :message="form.errors.password_confirmation" />
                    </div>

                    <!-- Bouton de réinitialisation -->
                    <div>
                        <button
                            type="submit"
                            class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-md shadow-sm text-white bg-[#2962B4] hover:bg-[#1E4B8F] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2962B4] transition-all duration-300 transform hover:scale-105"
                            :class="{ 'opacity-75 cursor-not-allowed': form.processing || isLoading }"
                            :disabled="form.processing || isLoading"
                        >
                            <svg v-if="isLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Réinitialiser le mot de passe</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Lien de retour -->
            <div class="mt-6 text-center">
                <a href="/login" class="font-medium text-[#2962B4] hover:text-[#1E4B8F] hover:underline transition duration-200 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Retour à la page de connexion
                </a>
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