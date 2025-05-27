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
            form.reset('password');
            isLoading.value = false;
        },
    });
};

onMounted(() => {
    setTimeout(() => {
        isLoaded.value = true;
    }, 200);
});
</script>

<template>
    <Head title="Connexion" />
    
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50/50 to-purple-50">
        <!-- Layout mobile : stack vertical -->
        <div class="lg:hidden min-h-screen flex flex-col">
            <!-- Header mobile avec illustration -->
            <div class="relative bg-gradient-to-br from-blue-600 to-indigo-700 text-white p-8 overflow-hidden">
                <!-- Éléments décoratifs -->
                <div class="absolute inset-0 overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
                    <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full translate-y-12 -translate-x-12"></div>
                </div>
                
                <div class="relative z-10 text-center">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H9m0 0H5m0 0h2M7 7h10M7 11h6"/>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold mb-2">Espace Digital</h1>
                    <p class="text-blue-100">Ministère des Finances</p>
                </div>
            </div>
            
            <!-- Formulaire mobile -->
            <div class="flex-1 p-6">
                <div 
                    class="max-w-sm mx-auto transition-all duration-1000"
                    :class="{ 'opacity-100 translate-y-0': isLoaded, 'opacity-0 translate-y-8': !isLoaded }"
                >
                    <!-- Message de statut -->
                    <div v-if="status" class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 backdrop-blur-xl rounded-2xl p-4 border border-green-200/50">
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
                                    class="w-full pl-4 pr-4 py-4 bg-white/80 border border-gray-200/50 rounded-2xl focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300"
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
                                    class="w-full pl-4 pr-12 py-4 bg-white/80 border border-gray-200/50 rounded-2xl focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300"
                                />
                                <button
                                    type="button"
                                    @click="togglePasswordVisibility"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600"
                                >
                                    <svg v-if="passwordVisible" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                    </svg>
                                    <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                            <div v-if="form.errors.password" class="mt-2 text-sm text-red-600">{{ form.errors.password }}</div>
                        </div>

                        <!-- Options -->
                        <div class="flex items-center justify-between text-sm">
                            <label class="flex items-center">
                                <input type="checkbox" v-model="form.remember" class="rounded text-blue-600 mr-2">
                                <span class="text-gray-600">Se souvenir</span>
                            </label>
                            <Link v-if="canResetPassword" :href="route('password.request')" class="text-blue-600 hover:text-blue-800">
                                Mot de passe oublié ?
                            </Link>
                        </div>

                        <!-- Bouton de connexion -->
                        <button
                            type="submit"
                            :disabled="form.processing || isLoading"
                            class="w-full py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-2xl hover:shadow-xl transition-all duration-300 hover:scale-105 disabled:opacity-75"
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
        <div class="hidden lg:grid lg:grid-cols-2 lg:min-h-screen">
            <!-- Colonne gauche : Illustration -->
            <div class="relative bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 text-white overflow-hidden">
                <!-- Éléments de fond animés -->
                <div class="absolute inset-0 overflow-hidden">
                    <div class="absolute -top-40 -left-40 w-96 h-96 bg-gradient-to-br from-white/10 to-transparent rounded-full blur-3xl animate-pulse"></div>
                    <div class="absolute top-1/3 -right-32 w-80 h-80 bg-gradient-to-br from-indigo-400/20 to-transparent rounded-full blur-3xl animate-pulse"></div>
                    <div class="absolute bottom-1/4 left-1/4 w-64 h-64 bg-gradient-to-br from-blue-400/15 to-transparent rounded-full blur-2xl animate-pulse"></div>
                </div>

                <div class="relative z-10 h-full flex flex-col justify-center p-12">
                    <!-- Logo ministère -->
                    <div class="mb-8">
                        <img :src="logoUrl" alt="Ministère" class="max-h-32 w-auto mx-auto mb-6" />
                        <div class="text-center">
                            <h1 class="text-4xl font-black mb-3 bg-gradient-to-r from-white to-blue-100 bg-clip-text text-transparent">
                                Ministère des Finances
                            </h1>
                            <p class="text-blue-100 text-lg">République du Bénin </p>
                        </div>
                    </div>

                    <!-- Illustration SVG de digitalisation -->
                    <div class="flex-1 flex items-center justify-center mb-8">
                        <div class="relative max-w-md w-full">
                            <!-- Illustration SVG moderne -->
                            <svg viewBox="0 0 400 300" class="w-full h-auto">
                                <!-- Écran principal -->
                                <rect x="50" y="80" width="200" height="140" rx="12" fill="rgba(255,255,255,0.1)" stroke="rgba(255,255,255,0.3)" stroke-width="2"/>
                                
                                <!-- Écran de code -->
                                <rect x="60" y="95" width="180" height="110" rx="6" fill="rgba(59,130,246,0.2)"/>
                                
                                <!-- Lignes de code -->
                                <rect x="70" y="105" width="100" height="4" rx="2" fill="rgba(255,255,255,0.6)"/>
                                <rect x="70" y="115" width="80" height="4" rx="2" fill="rgba(255,255,255,0.4)"/>
                                <rect x="70" y="125" width="120" height="4" rx="2" fill="rgba(255,255,255,0.5)"/>
                                <rect x="70" y="135" width="90" height="4" rx="2" fill="rgba(255,255,255,0.3)"/>
                                
                                <!-- Graphiques -->
                                <circle cx="200" cy="160" r="25" fill="rgba(34,197,94,0.3)" stroke="rgba(34,197,94,0.6)" stroke-width="2"/>
                                <rect x="180" y="175" width="40" height="20" rx="4" fill="rgba(168,85,247,0.3)"/>
                                
                                <!-- Éléments flottants -->
                                <circle cx="300" cy="100" r="15" fill="rgba(255,255,255,0.2)" class="animate-float">
                                    <animate attributeName="cy" values="100;90;100" dur="3s" repeatCount="indefinite"/>
                                </circle>
                                
                                <rect x="320" y="140" width="30" height="30" rx="6" fill="rgba(59,130,246,0.3)" class="animate-float">
                                    <animate attributeName="y" values="140;130;140" dur="4s" repeatCount="indefinite"/>
                                </rect>
                                
                                <circle cx="280" cy="220" r="12" fill="rgba(34,197,94,0.3)" class="animate-float">
                                    <animate attributeName="cy" values="220;210;220" dur="3.5s" repeatCount="indefinite"/>
                                </circle>
                                
                                <!-- Connexions -->
                                <path d="M250 120 Q300 130 320 155" stroke="rgba(255,255,255,0.2)" stroke-width="2" fill="none" stroke-dasharray="5,5">
                                    <animate attributeName="stroke-dashoffset" values="0;10" dur="2s" repeatCount="indefinite"/>
                                </path>
                            </svg>
                        </div>
                    </div>

                    <!-- Statistiques animées -->
                    <div class="grid grid-cols-3 gap-6 text-center">
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20">
                            <div class="text-2xl font-bold text-white mb-1">50+</div>
                            <div class="text-blue-100 text-sm">Services Digitaux</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20">
                            <div class="text-2xl font-bold text-white mb-1">24/7</div>
                            <div class="text-blue-100 text-sm">Disponibilité</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20">
                            <div class="text-2xl font-bold text-white mb-1">99%</div>
                            <div class="text-blue-100 text-sm">Sécurité</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne droite : Formulaire -->
            <div class="relative flex items-center justify-center p-12 bg-gradient-to-br from-gray-50 to-blue-50/30">
                <!-- Éléments décoratifs de fond -->
                <div class="absolute inset-0 overflow-hidden pointer-events-none">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-blue-400/10 to-transparent rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 w-48 h-48 bg-gradient-to-br from-indigo-400/10 to-transparent rounded-full blur-2xl"></div>
                </div>

                <div 
                    class="relative z-10 w-full max-w-md transition-all duration-1000"
                    :class="{ 'opacity-100 translate-y-0': isLoaded, 'opacity-0 translate-y-8': !isLoaded }"
                >
                    <!-- En-tête du formulaire -->
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-black bg-gradient-to-r from-gray-900 via-blue-800 to-indigo-700 bg-clip-text text-transparent mb-2">
                            Bienvenue !
                        </h2>
                        <p class="text-gray-600 font-medium">Connectez-vous à votre espace</p>
                        <p class="text-blue-600 text-sm font-semibold">Programme de Stages</p>
                    </div>

                    <!-- Message de statut -->
                    <div v-if="status" class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 backdrop-blur-xl rounded-2xl p-4 border border-green-200/50 shadow-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <p class="text-green-800 font-medium">{{ status }}</p>
                        </div>
                    </div>

                    <!-- Carte du formulaire -->
                    <div class="bg-white/90 rounded-3xl p-8 border border-white/20 shadow-2xl">
                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Adresse email
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                        </svg>
                                    </div>
                                    <input
                                        type="email"
                                        v-model="form.email"
                                        required
                                        autofocus
                                        placeholder="votre@email.com"
                                        class="w-full pl-12 pr-4 py-4 bg-white/80 border border-gray-200/50 rounded-2xl focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 hover:shadow-md"
                                    />
                                </div>
                                <div v-if="form.errors.email" class="mt-2 text-sm text-red-600">
                                    {{ form.errors.email }}
                                </div>
                            </div>

                            <!-- Mot de passe -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Mot de passe
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                    </div>
                                    <input
                                        :type="passwordVisible ? 'text' : 'password'"
                                        v-model="form.password"
                                        required
                                        placeholder="••••••••"
                                        class="w-full pl-12 pr-12 py-4 bg-white/80 border border-gray-200/50 rounded-2xl focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 hover:shadow-md"
                                    />
                                    <button
                                        type="button"
                                        @click="togglePasswordVisibility"
                                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors duration-200"
                                    >
                                        <svg v-if="passwordVisible" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                        </svg>
                                        <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                </div>
                                <div v-if="form.errors.password" class="mt-2 text-sm text-red-600">
                                    {{ form.errors.password }}
                                </div>
                            </div>

                            <!-- Options -->
                            <div class="flex items-center justify-between">
                                <label class="flex items-center text-sm">
                                    <input 
                                        type="checkbox" 
                                        v-model="form.remember" 
                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500/20 mr-2"
                                    />
                                    <span class="text-gray-600 font-medium">Se souvenir de moi</span>
                                </label>
                                
                                <Link
                                    v-if="canResetPassword"
                                    :href="route('password.request')"
                                    class="text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors duration-200 hover:underline"
                                >
                                    Mot de passe oublié ?
                                </Link>
                            </div>

                            <!-- Bouton de connexion -->
                            <button
                                type="submit"
                                :disabled="form.processing || isLoading"
                                class="group relative w-full py-4 px-6 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-2xl hover:scale-105 transform disabled:opacity-75 disabled:cursor-not-allowed"
                            >
                                <span class="relative z-10 flex items-center justify-center">
                                    <svg v-if="isLoading" class="animate-spin -ml-1 mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <svg v-else class="w-5 h-5 mr-3 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                    </svg>
                                    {{ isLoading ? 'Connexion en cours...' : 'Se connecter' }}
                                </span>
                                <div class="absolute inset-0 bg-gradient-to-r from-blue-700 to-indigo-700 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
                                <div class="absolute inset-0 bg-white/20 transform scale-0 group-hover:scale-100 transition-transform duration-500 rounded-2xl"></div>
                            </button>
                        </form>
                    </div>

                    <!-- Lien d'inscription -->
                    <div class="mt-6 text-center">
                        <div class="bg-white/60 backdrop-blur-sm rounded-2xl p-4 border border-white/20">
                            <p class="text-gray-600 text-sm mb-2">
                                Pas encore de compte ?
                            </p>
                            <Link 
                                :href="route('register')" 
                                class="inline-flex items-center font-medium text-blue-600 hover:text-blue-800 transition-colors duration-300 hover:scale-105 transform"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                                Créer un compte maintenant
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Animations personnalisées */
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

.animate-float {
    animation: float 6s ease-in-out infinite;
}

/* Effet de focus sur les champs */
input:focus {
    transform: scale(1.02);
}

/* Animation des éléments SVG */
.animate-svg-float {
    animation: float 4s ease-in-out infinite;
}

/* Effet de pulsation douce */
@keyframes pulse-soft {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.8; }
}

.animate-pulse-soft {
    animation: pulse-soft 3s ease-in-out infinite;
}
</style>