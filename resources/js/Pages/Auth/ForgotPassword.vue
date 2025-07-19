<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

const logoUrl = '/images/logoministere.png';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const isLoading = ref(false);
const isLoaded = ref(false);

const submit = () => {
    isLoading.value = true;
    form.post(route('password.email'), {
        onFinish: () => {
            isLoading.value = false;
        },
    });
};

onMounted(() => {
    setTimeout(() => {
        isLoaded.value = true;
    }, 100);
});
</script>

<template>
    <Head title="Mot de passe oublié" />

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 relative overflow-hidden">
        <!-- Background Image with Overlay -->
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-10"
             style="background-image: url('/images/bg.png')"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-blue-900/5 via-indigo-900/3 to-slate-900/5"></div>

        <!-- Floating Elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-20 left-10 w-32 h-32 bg-blue-500/10 rounded-full blur-2xl animate-pulse"></div>
            <div class="absolute bottom-20 right-10 w-40 h-40 bg-indigo-500/10 rounded-full blur-2xl animate-pulse" style="animation-delay: 2s;"></div>
        </div>

        <!-- Layout mobile : stack vertical -->
        <div class="lg:hidden min-h-screen flex flex-col justify-center py-4 relative z-10">
            <!-- Header mobile -->
            <div class="relative bg-gradient-to-r from-blue-600 to-indigo-600 text-white p-6 overflow-hidden">
                <div class="text-center">
                    <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold mb-2">Mot de passe oublié</h1>
                    <p class="text-blue-100">Ministère de l'Économie et des Finances</p>
                </div>
            </div>

            <!-- Formulaire mobile -->
            <div class="flex-1 bg-white p-6">
                <div class="max-w-sm mx-auto">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Récupération</h2>
                        <p class="text-gray-600">Réinitialisez votre mot de passe</p>
                    </div>

                    <!-- Message de statut -->
                    <div v-if="status" class="mb-6 bg-green-50 rounded-lg p-4 border border-green-200">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <p class="text-green-800 font-medium text-sm">{{ status }}</p>
                        </div>
                    </div>

                    <div class="mb-6 text-gray-600 text-sm">
                        Vous avez oublié votre mot de passe ? Indiquez-nous votre adresse email et nous vous enverrons un lien de réinitialisation.
                    </div>

                    <!-- Formulaire -->
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Adresse email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                    </svg>
                                </div>
                                <input
                                    type="email"
                                    v-model="form.email"
                                    required
                                    autofocus
                                    placeholder="votre@email.com"
                                    class="w-full pl-10 pr-4 py-3 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200"
                                />
                            </div>
                            <div v-if="form.errors.email" class="mt-2 text-sm text-red-600">{{ form.errors.email }}</div>
                        </div>

                        <!-- Boutons -->
                        <div class="flex items-center justify-between">
                            <Link :href="route('login')" class="text-blue-600 hover:text-blue-800 text-sm">
                                ← Retour à la connexion
                            </Link>

                            <button
                                type="submit"
                                :disabled="form.processing || isLoading"
                                class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 disabled:opacity-75"
                            >
                                <svg v-if="isLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ isLoading ? 'Envoi...' : 'Envoyer le lien' }}
                            </button>
                        </div>
                    </form>

                    <!-- Lien inscription -->
                    <div class="mt-6 text-center">
                        <p class="text-gray-600 text-sm">
                            Pas encore de compte ?
                            <Link :href="route('register')" class="text-blue-600 hover:text-blue-800 font-medium">
                                S'inscrire
                            </Link>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Layout desktop : deux colonnes -->
        <div class="hidden lg:grid lg:grid-cols-2 lg:min-h-screen relative z-10">
            <!-- Colonne gauche : Illustration -->
            <div class="relative bg-gradient-to-br from-blue-600 to-indigo-600 text-white overflow-hidden min-h-full">
                <!-- Background Image with Overlay -->
                <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-20"
                     style="background-image: url('/images/bg.png')"></div>
                <div class="absolute inset-0 bg-gradient-to-br from-blue-900/20 via-indigo-900/10 to-slate-900/20"></div>

                <!-- Floating Elements -->
                <div class="absolute inset-0 overflow-hidden pointer-events-none">
                    <div class="absolute top-20 left-10 w-32 h-32 bg-white/10 rounded-full blur-2xl animate-pulse"></div>
                    <div class="absolute bottom-20 right-10 w-40 h-40 bg-white/10 rounded-full blur-2xl animate-pulse" style="animation-delay: 2s;"></div>
                </div>

                <div class="relative z-10 h-full flex flex-col justify-center p-12">
                    <!-- Logo et titre -->
                    <div class="text-center mb-12">
                        <div class="mb-8">
                            <img :src="logoUrl" alt="Ministère" class="max-h-20 w-auto mx-auto" />
                        </div>

                        <h1 class="text-3xl font-bold mb-4 text-white">
                            Ministère de l'Économie<br>et des Finances
                        </h1>
                        <p class="text-blue-100 text-lg">République du Bénin</p>
                        <div class="w-16 h-1 bg-white/30 rounded-full mx-auto mt-4"></div>
                    </div>

                    <!-- Contenu principal -->
                    <div class="text-center mb-12">
                        <div class="mb-8">
                            <div class="w-20 h-20 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-6">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-white mb-4">Récupération Sécurisée</h3>
                            <p class="text-blue-100 leading-relaxed">
                                Réinitialisez votre mot de passe en toute sécurité. Un lien de récupération sera envoyé à votre adresse email.
                            </p>
                        </div>

                        <!-- Étapes -->
                        <div class="space-y-4 text-left">
                            <div class="flex items-center text-blue-100">
                                <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center mr-3 text-xs font-bold text-white">1</div>
                                Saisissez votre adresse email
                            </div>
                            <div class="flex items-center text-blue-100">
                                <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center mr-3 text-xs font-bold text-white">2</div>
                                Vérifiez votre boîte de réception
                            </div>
                            <div class="flex items-center text-blue-100">
                                <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center mr-3 text-xs font-bold text-white">3</div>
                                Cliquez sur le lien reçu
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne droite : Formulaire -->
            <div class="relative flex items-center justify-center p-12 bg-white">
                <div class="w-full max-w-md">
                    <!-- En-tête du formulaire -->
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">
                            Mot de passe oublié ?
                        </h2>
                        <p class="text-gray-600 font-medium">Pas de problème, nous allons vous aider</p>
                    </div>

                    <!-- Message de statut -->
                    <div v-if="status" class="mb-6 bg-green-50 rounded-lg p-4 border border-green-200">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <p class="text-green-800 font-medium">{{ status }}</p>
                        </div>
                    </div>

                    <div class="mb-6 text-gray-600">
                        Indiquez votre adresse email et nous vous enverrons un lien pour réinitialiser votre mot de passe.
                    </div>

                    <!-- Formulaire -->
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Adresse email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                    </svg>
                                </div>
                                <input
                                    type="email"
                                    v-model="form.email"
                                    required
                                    autofocus
                                    placeholder="votre@email.com"
                                    class="w-full pl-10 pr-4 py-3 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200"
                                />
                            </div>
                            <div v-if="form.errors.email" class="mt-2 text-sm text-red-600">{{ form.errors.email }}</div>
                        </div>

                        <!-- Boutons -->
                        <div class="space-y-4">
                            <button
                                type="submit"
                                :disabled="form.processing || isLoading"
                                class="w-full py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 disabled:opacity-75 shadow-lg hover:shadow-xl"
                            >
                                <svg v-if="isLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ isLoading ? 'Envoi en cours...' : 'Envoyer le lien de réinitialisation' }}
                            </button>

                            <div class="text-center">
                                <Link :href="route('login')" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    ← Retour à la connexion
                                </Link>
                            </div>
                        </div>
                    </form>

                    <!-- Lien inscription -->
                    <div class="mt-8 text-center">
                        <p class="text-gray-600 text-sm">
                            Pas encore de compte ?
                            <Link :href="route('register')" class="text-blue-600 hover:text-blue-800 font-medium">
                                S'inscrire maintenant
                            </Link>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Smooth animations */
.transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}
</style>