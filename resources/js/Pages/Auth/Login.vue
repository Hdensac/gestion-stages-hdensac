<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const passwordVisible = ref(false);
const isLoading = ref(false);

const togglePasswordVisibility = () => {
    passwordVisible.value = !passwordVisible.value;
};

const submit = () => {
    isLoading.value = true;
    form.post(route('login'), {
        onFinish: () => {
            form.reset('password');
            isLoading.value = false;
        },
    });
};
</script>

<template>
    <div class="min-h-screen flex flex-col justify-center items-center px-4 bg-gray-50">
        <div class="max-w-md w-full">
        <Head title="Connexion" />

        <!-- Logo et titre -->
        <div class="mb-6 text-center">
            <div class="flex justify-center mb-3">
                <img src="/images/logoministere.png" alt="MINISTERE DE L'ECONOMIE ET DES FINANCES" class="h-20 w-auto transition-transform duration-300 hover:scale-105" />
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Bienvenue sur votre espace</h1>
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

        <!-- Carte de connexion avec effet d'ombre -->
        <div class="bg-white rounded-xl shadow-xl p-6 transition-all duration-300 hover:shadow-2xl">
            <form @submit.prevent="submit">
                <!-- Email -->
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

                <!-- Mot de passe -->
                <div class="mb-5">
                    <InputLabel for="password" value="Mot de passe" class="text-gray-700 font-medium" />
                    
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <TextInput
                            id="password"
                            :type="passwordVisible ? 'text' : 'password'"
                            class="pl-10 pr-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#00513A] focus:ring focus:ring-[#00513A] focus:ring-opacity-20"
                            v-model="form.password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
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
                </div>

                <!-- Se souvenir de moi -->
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center text-sm">
                        <Checkbox name="remember" v-model:checked="form.remember" class="rounded border-gray-300 text-[#00513A] shadow-sm focus:border-[#00513A] focus:ring focus:ring-[#00513A] focus:ring-opacity-20" />
                        <span class="ml-2 text-gray-600 hover:text-gray-800">Se souvenir de moi</span>
                    </label>
                    
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-sm text-[#00513A] hover:text-[#003E2C] hover:underline transition duration-200"
                    >
                        Mot de passe oublié ?
                    </Link>
                </div>

                <!-- Bouton de connexion -->
                <div>
                    <button
                        type="submit"
                        class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-md shadow-sm text-white bg-[#00513A] hover:bg-[#003E2C] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#00513A] transition-all duration-300 transform hover:scale-[1.02]"
                        :class="{ 'opacity-75 cursor-not-allowed': form.processing || isLoading }"
                        :disabled="form.processing || isLoading"
                    >
                        <svg v-if="isLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Se connecter</span>
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