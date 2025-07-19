<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

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
const isLoaded = ref(false);
const logoUrl = '/images/logoministere.png';

const togglePasswordVisibility = () => {
    passwordVisible.value = !passwordVisible.value;
};

const submit = () => {
    isLoading.value = true;
    form.post(route('login'), {
        onFinish: () => {
            isLoading.value = false;
            form.reset('password');
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
    <Head title="Connexion" />

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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold mb-2">Connexion</h1>
                    <p class="text-blue-100">Ministère de l'Économie et des Finances</p>
                </div>
            </div>
            
            <!-- Formulaire mobile -->
            <div class="flex-1 bg-white p-6">
                <div class="max-w-sm mx-auto">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Bienvenue</h2>
                        <p class="text-gray-600">Connectez-vous à votre espace</p>
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

                    <!-- Formulaire -->
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Adresse email</label>
                            <div class="relative">
                                <input
                                    type="email"
                                    v-model="form.email"
                                    required
                                    autofocus
                                    placeholder="votre@email.com"
                                    class="w-full pl-4 pr-4 py-3 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200"
                                />
                            </div>
                            <div v-if="form.errors.email" class="mt-2 text-sm text-red-600">{{ form.errors.email }}</div>
                        </div>

                        <!-- Mot de passe -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Mot de passe</label>
                            <div class="relative">
                                <input
                                    :type="passwordVisible ? 'text' : 'password'"
                                    v-model="form.password"
                                    required
                                    placeholder="••••••••"
                                    class="w-full pl-4 pr-12 py-3 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200"
                                />
                                <button
                                    type="button"
                                    @click="togglePasswordVisibility"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600"
                                >
                                    <svg v-if="!passwordVisible" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                    </svg>
                                </button>
                            </div>
                            <div v-if="form.errors.password" class="mt-2 text-sm text-red-600">{{ form.errors.password }}</div>
                        </div>

                        <!-- Se souvenir de moi et mot de passe oublié -->
                        <div class="flex items-center justify-between">
                            <label class="flex items-center">
                                <input type="checkbox" v-model="form.remember" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-600">Se souvenir de moi</span>
                            </label>
                            <Link v-if="canResetPassword" :href="route('password.request')" class="text-blue-600 hover:text-blue-800">
                                Mot de passe oublié ?
                            </Link>
                        </div>

                        <!-- Bouton de connexion -->
                        <button
                            type="submit"
                            :disabled="form.processing || isLoading"
                            class="w-full py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 disabled:opacity-75"
                        >
                            {{ isLoading ? 'Connexion...' : 'Se connecter' }}
                        </button>

                        <!-- Lien inscription -->
                        <div class="text-center">
                            <p class="text-gray-600 text-sm">
                                Pas encore de compte ?
                                <Link :href="route('register')" class="text-blue-600 hover:text-blue-800 font-medium">
                                    S'inscrire
                                </Link>
                            </p>
                        </div>
                    </form>
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H9m0 0H5m0 0h2M7 7h10M7 11h6"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-white mb-4">Gestion des Stages</h3>
                            <p class="text-blue-100 leading-relaxed">
                                Plateforme moderne pour la gestion et le suivi des stages au sein du ministère.
                            </p>
                        </div>
                        
                        <!-- Statistiques simples -->
                        <div class="grid grid-cols-3 gap-4 mb-8">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-white">50+</div>
                                <div class="text-blue-100 text-sm">Stages</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-white">25+</div>
                                <div class="text-blue-100 text-sm">Structures</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-white">98%</div>
                                <div class="text-blue-100 text-sm">Satisfaction</div>
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
                            Bienvenue !
                        </h2>
                        <p class="text-gray-600 font-medium">Connectez-vous à votre espace</p>
                        <p class="text-blue-600 text-sm font-semibold">Gestion des Stages</p>
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

                    <!-- Formulaire -->
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Adresse email</label>
                            <input
                                type="email"
                                v-model="form.email"
                                required
                                autofocus
                                placeholder="votre@email.com"
                                class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200"
                            />
                            <div v-if="form.errors.email" class="mt-2 text-sm text-red-600">{{ form.errors.email }}</div>
                        </div>

                        <!-- Mot de passe -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Mot de passe</label>
                            <div class="relative">
                                <input
                                    :type="passwordVisible ? 'text' : 'password'"
                                    v-model="form.password"
                                    required
                                    placeholder="••••••••"
                                    class="w-full px-4 py-3 pr-12 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200"
                                />
                                <button
                                    type="button"
                                    @click="togglePasswordVisibility"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600"
                                >
                                    <svg v-if="!passwordVisible" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                    </svg>
                                </button>
                            </div>
                            <div v-if="form.errors.password" class="mt-2 text-sm text-red-600">{{ form.errors.password }}</div>
                        </div>

                        <!-- Se souvenir de moi et mot de passe oublié -->
                        <div class="flex items-center justify-between">
                            <label class="flex items-center">
                                <input type="checkbox" v-model="form.remember" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-600">Se souvenir de moi</span>
                            </label>
                            <Link v-if="canResetPassword" :href="route('password.request')" class="text-blue-600 hover:text-blue-800 text-sm">
                                Mot de passe oublié ?
                            </Link>
                        </div>

                        <!-- Bouton de connexion -->
                        <button
                            type="submit"
                            :disabled="form.processing || isLoading"
                            class="w-full py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 disabled:opacity-75 shadow-lg hover:shadow-xl"
                        >
                            {{ isLoading ? 'Connexion...' : 'Se connecter' }}
                        </button>

                        <!-- Lien inscription -->
                        <div class="text-center">
                            <p class="text-gray-600 text-sm">
                                Pas encore de compte ?
                                <Link :href="route('register')" class="text-blue-600 hover:text-blue-800 font-medium">
                                    S'inscrire
                                </Link>
                            </p>
                        </div>
                    </form>
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
