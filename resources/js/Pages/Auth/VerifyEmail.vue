<script setup>
import { computed, ref, onMounted } from 'vue';
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
const isLoaded = ref(false);
const logoUrl = '/images/logoministere.png';

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

onMounted(() => {
    setTimeout(() => {
        isLoaded.value = true;
    }, 100);
});
</script>

<template>
    <Head title="Vérification d'email" />

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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold mb-2">Vérification d'email</h1>
                    <p class="text-blue-100">Ministère de l'Économie et des Finances</p>
                </div>
            </div>

            <!-- Formulaire mobile -->
            <div class="flex-1 bg-white p-6">
                <div class="max-w-sm mx-auto">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Vérification requise</h2>
                        <p class="text-gray-600">Confirmez votre adresse email</p>
                    </div>

                    <!-- Message de succès -->
                    <div
                        v-if="verificationLinkSent"
                        class="mb-6 bg-green-50 rounded-lg p-4 border border-green-200"
                    >
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <p class="text-green-800 font-medium text-sm">
                                Un nouveau lien de vérification a été envoyé à votre adresse email.
                            </p>
                        </div>
                    </div>

                    <div class="mb-6 text-gray-600 text-sm">
                        Merci pour votre inscription ! Avant de commencer, veuillez vérifier votre adresse email en cliquant sur le lien que nous venons de vous envoyer.
                    </div>

                    <!-- Formulaire -->
                    <form @submit.prevent="submit" class="space-y-6">
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
                                {{ isLoading ? 'Envoi...' : 'Renvoyer le lien' }}
                            </button>
                        </div>
                    </form>

                    <!-- Lien déconnexion -->
                    <div class="mt-6 text-center">
                        <Link
                            :href="route('logout')"
                            method="post"
                            as="button"
                            class="text-gray-600 hover:text-gray-800 text-sm font-medium"
                        >
                            Se déconnecter
                        </Link>
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-white mb-4">Vérification Sécurisée</h3>
                            <p class="text-blue-100 leading-relaxed">
                                Confirmez votre adresse email pour accéder en toute sécurité à votre espace personnel et soumettre vos demandes de stage.
                            </p>
                        </div>

                        <!-- Étapes -->
                        <div class="space-y-4 text-left">
                            <div class="flex items-center text-blue-100">
                                <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center mr-3 text-xs font-bold text-white">1</div>
                                Vérifiez votre boîte de réception
                            </div>
                            <div class="flex items-center text-blue-100">
                                <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center mr-3 text-xs font-bold text-white">2</div>
                                Cliquez sur le lien de vérification
                            </div>
                            <div class="flex items-center text-blue-100">
                                <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center mr-3 text-xs font-bold text-white">3</div>
                                Accédez à votre espace personnel
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
                            Vérification d'email
                        </h2>
                        <p class="text-gray-600 font-medium">Confirmez votre adresse email pour continuer</p>
                    </div>

                    <!-- Message de statut -->
                    <div v-if="verificationLinkSent" class="mb-6 bg-green-50 rounded-lg p-4 border border-green-200">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <p class="text-green-800 font-medium">Un nouveau lien de vérification a été envoyé à votre adresse email.</p>
                        </div>
                    </div>

                    <div class="mb-6 text-gray-600">
                        Merci pour votre inscription au Programme de Stages ! Avant de commencer, veuillez vérifier votre adresse email en cliquant sur le lien que nous venons de vous envoyer.
                    </div>

                    <!-- Formulaire -->
                    <form @submit.prevent="submit" class="space-y-6">
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
                                {{ isLoading ? 'Envoi en cours...' : 'Renvoyer l\'email de vérification' }}
                            </button>

                            <div class="text-center">
                                <Link :href="route('login')" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    ← Retour à la connexion
                                </Link>
                            </div>
                        </div>
                    </form>

                    <!-- Lien déconnexion -->
                    <div class="mt-8 text-center">
                        <Link
                            :href="route('logout')"
                            method="post"
                            as="button"
                            class="text-gray-600 hover:text-gray-800 text-sm font-medium"
                        >
                            Se déconnecter
                        </Link>
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