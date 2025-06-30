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
        <div class="lg:hidden min-h-screen flex flex-col justify-center py-4">
            <!-- Header mobile avec illustration -->
            <div class="relative bg-gradient-to-br from-blue-600 to-indigo-700 text-white p-4 overflow-hidden">
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
            <div class="flex-1 p-4">
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
        <div class="hidden lg:grid lg:grid-cols-2 lg:min-h-screen" style="min-height: 80vh;">
            <!-- Colonne gauche : Illustration -->
            <!-- Colonne gauche : Illustration modernisée -->
<div class="relative bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 text-white overflow-hidden min-h-full">
    <!-- Éléments de fond animés avec effets modernes -->
    <div class="absolute inset-0 overflow-hidden">
        <!-- Grille de fond subtile -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.1\'%3E%3Ccircle cx=\'30\' cy=\'30\' r=\'1\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>
        
        <!-- Bulles flottantes animées -->
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-gradient-to-br from-cyan-400/20 to-blue-400/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute top-1/3 -right-32 w-80 h-80 bg-gradient-to-br from-indigo-400/20 to-purple-400/10 rounded-full blur-3xl animate-pulse delay-1000"></div>
        <div class="absolute bottom-1/4 left-1/4 w-64 h-64 bg-gradient-to-br from-blue-400/15 to-cyan-400/10 rounded-full blur-2xl animate-pulse delay-2000"></div>
        
        <!-- Formes géométriques flottantes -->
        <div class="absolute top-20 right-20 w-4 h-4 bg-white/20 rounded-full animate-bounce delay-500"></div>
        <div class="absolute bottom-40 left-16 w-6 h-6 bg-cyan-400/30 rotate-45 animate-pulse delay-700"></div>
        <div class="absolute top-1/2 right-1/3 w-8 h-1 bg-white/30 rounded-full animate-pulse delay-1500"></div>
    </div>

    <div class="relative z-10 h-full flex flex-col justify-center p-8">
        <!-- Header avec logo modernisé -->
        <div class="mb-12">
            <!-- Container du logo avec effet glassmorphism -->
            <div class="relative mb-8">
                <div class="absolute inset-0 bg-white/10 backdrop-blur-xl rounded-3xl blur-sm"></div>
                <div class="relative bg-white/5 backdrop-blur-xl rounded-3xl p-8 border border-white/20">
                    <img :src="logoUrl" alt="Ministère" class="max-h-24 w-auto mx-auto filter drop-shadow-2xl" />
                </div>
            </div>
            
            <!-- Titre avec animation de typing -->
            <div class="text-center">
                <h1 class="text-4xl font-black mb-4 bg-gradient-to-r from-white via-cyan-100 to-blue-100 bg-clip-text text-transparent">
                    <span class="inline-block animate-pulse">Ministère</span>
                    <span class="inline-block animate-pulse delay-200">des</span>
                    <span class="inline-block animate-pulse delay-400">Finances</span>
                </h1>
                <div class="flex items-center justify-center space-x-2 mb-2">
                    <div class="w-8 h-0.5 bg-gradient-to-r from-transparent to-cyan-400 animate-pulse"></div>
                    <p class="text-cyan-100 text-lg font-medium">République du Bénin</p>
                    <div class="w-8 h-0.5 bg-gradient-to-l from-transparent to-cyan-400 animate-pulse"></div>
                </div>
                <p class="text-blue-200/80 text-sm font-light">Transformation Digitale</p>
            </div>
        </div>

        <!-- Illustration SVG modernisée avec animations -->
        <div class="flex-1 flex items-center justify-center mb-12">
            <div class="relative max-w-lg w-full">
                <!-- Dashboard moderne -->
                <svg viewBox="0 0 500 350" class="w-full h-auto drop-shadow-2xl">
                    <!-- Écran principal avec effet glassmorphism -->
                    <defs>
                        <linearGradient id="screenGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:rgba(255,255,255,0.15);stop-opacity:1" />
                            <stop offset="100%" style="stop-color:rgba(255,255,255,0.05);stop-opacity:1" />
                        </linearGradient>
                        <linearGradient id="blueGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:rgba(59,130,246,0.8);stop-opacity:1" />
                            <stop offset="100%" style="stop-color:rgba(99,102,241,0.6);stop-opacity:1" />
                        </linearGradient>
                        <filter id="glow" x="-50%" y="-50%" width="200%" height="200%">
                            <feGaussianBlur stdDeviation="3" result="coloredBlur"/>
                            <feMerge> 
                                <feMergeNode in="coloredBlur"/>
                                <feMergeNode in="SourceGraphic"/>
                            </feMerge>
                        </filter>
                    </defs>
                    
                    <!-- Écran principal -->
                    <rect x="60" y="60" width="280" height="180" rx="20" fill="url(#screenGradient)" stroke="rgba(255,255,255,0.3)" stroke-width="2" filter="url(#glow)"/>
                    
                    <!-- Barre de navigation -->
                    <rect x="75" y="75" width="250" height="30" rx="15" fill="rgba(59,130,246,0.3)"/>
                    <circle cx="90" cy="90" r="4" fill="rgba(255,255,255,0.8)"/>
                    <circle cx="105" cy="90" r="4" fill="rgba(255,255,255,0.6)"/>
                    <circle cx="120" cy="90" r="4" fill="rgba(255,255,255,0.4)"/>
                    
                    <!-- Dashboard Cards -->
                    <rect x="85" y="120" width="70" height="50" rx="8" fill="url(#blueGradient)">
                        <animate attributeName="opacity" values="0.6;1;0.6" dur="3s" repeatCount="indefinite"/>
                    </rect>
                    <rect x="170" y="120" width="70" height="50" rx="8" fill="rgba(34,197,94,0.4)">
                        <animate attributeName="opacity" values="0.4;0.8;0.4" dur="2.5s" repeatCount="indefinite"/>
                    </rect>
                    <rect x="255" y="120" width="70" height="50" rx="8" fill="rgba(168,85,247,0.4)">
                        <animate attributeName="opacity" values="0.5;0.9;0.5" dur="3.5s" repeatCount="indefinite"/>
                    </rect>
                    
                    <!-- Graphique en temps réel -->
                    <path d="M85 190 Q120 180 155 185 Q190 195 225 180 Q260 170 295 175" 
                          stroke="rgba(34,197,94,0.8)" stroke-width="3" fill="none" filter="url(#glow)">
                        <animate attributeName="d" 
                                 values="M85 190 Q120 180 155 185 Q190 195 225 180 Q260 170 295 175;
                                         M85 195 Q120 185 155 180 Q190 190 225 175 Q260 165 295 170;
                                         M85 190 Q120 180 155 185 Q190 195 225 180 Q260 170 295 175" 
                                 dur="4s" repeatCount="indefinite"/>
                    </path>
                    
                    <!-- Indicateurs en temps réel -->
                    <circle cx="380" cy="100" r="20" fill="rgba(255,255,255,0.15)" stroke="rgba(59,130,246,0.6)" stroke-width="2">
                        <animate attributeName="r" values="20;25;20" dur="2s" repeatCount="indefinite"/>
                    </circle>
                    <text x="380" y="105" text-anchor="middle" fill="rgba(255,255,255,0.9)" font-size="10" font-weight="bold">€</text>
                    
                    <!-- Notifications flottantes -->
                    <rect x="350" y="160" width="80" height="25" rx="12" fill="rgba(59,130,246,0.6)" opacity="0">
                        <animate attributeName="opacity" values="0;1;0" dur="3s" repeatCount="indefinite" begin="1s"/>
                        <animate attributeName="x" values="350;340;350" dur="3s" repeatCount="indefinite" begin="1s"/>
                    </rect>
                    
                    <rect x="360" y="200" width="60" height="20" rx="10" fill="rgba(34,197,94,0.6)" opacity="0">
                        <animate attributeName="opacity" values="0;1;0" dur="2.5s" repeatCount="indefinite" begin="2s"/>
                        <animate attributeName="x" values="360;350;360" dur="2.5s" repeatCount="indefinite" begin="2s"/>
                    </rect>
                    
                    <!-- Particules connectées -->
                    <g opacity="0.6">
                        <circle cx="150" cy="280" r="3" fill="rgba(255,255,255,0.8)">
                            <animate attributeName="cy" values="280;270;280" dur="4s" repeatCount="indefinite"/>
                        </circle>
                        <circle cx="200" cy="290" r="4" fill="rgba(59,130,246,0.6)">
                            <animate attributeName="cy" values="290;275;290" dur="3s" repeatCount="indefinite"/>
                        </circle>
                        <circle cx="250" cy="285" r="2" fill="rgba(34,197,94,0.8)">
                            <animate attributeName="cy" values="285;275;285" dur="3.5s" repeatCount="indefinite"/>
                        </circle>
                        
                        <!-- Connexions animées -->
                        <path d="M150 280 L200 290 L250 285" stroke="rgba(255,255,255,0.2)" stroke-width="1" fill="none" opacity="0.5">
                            <animate attributeName="opacity" values="0.5;1;0.5" dur="2s" repeatCount="indefinite"/>
                        </path>
                    </g>
                    
                    <!-- Effet de scan moderne -->
                    <rect x="60" y="60" width="280" height="2" fill="rgba(59,130,246,0.8)" opacity="0.8">
                        <animate attributeName="y" values="60;240;60" dur="4s" repeatCount="indefinite"/>
                        <animate attributeName="opacity" values="0.8;0.3;0.8" dur="4s" repeatCount="indefinite"/>
                    </rect>
                </svg>
            </div>
        </div>

        <!-- Statistiques modernisées avec animations -->
        <div class="grid grid-cols-3 gap-4">
            <div class="group relative bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300 hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-br from-cyan-400/20 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-3xl font-black text-white">50+</div>
                        <div class="w-8 h-8 bg-cyan-400/30 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-cyan-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="text-cyan-100 text-sm font-medium">Services Digitaux</div>
                    <div class="w-full bg-white/20 rounded-full h-1 mt-2">
                        <div class="bg-gradient-to-r from-cyan-400 to-blue-400 h-1 rounded-full animate-pulse" style="width: 85%"></div>
                    </div>
                </div>
            </div>
            
            <div class="group relative bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300 hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-br from-green-400/20 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-3xl font-black text-white">24/7</div>
                        <div class="w-8 h-8 bg-green-400/30 rounded-full flex items-center justify-center">
                            <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                        </div>
                    </div>
                    <div class="text-green-100 text-sm font-medium">Disponibilité</div>
                    <div class="w-full bg-white/20 rounded-full h-1 mt-2">
                        <div class="bg-gradient-to-r from-green-400 to-emerald-400 h-1 rounded-full animate-pulse" style="width: 100%"></div>
                    </div>
                </div>
            </div>
            
            <div class="group relative bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300 hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-br from-purple-400/20 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-3xl font-black text-white">99%</div>
                        <div class="w-8 h-8 bg-purple-400/30 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="text-purple-100 text-sm font-medium">Sécurité</div>
                    <div class="w-full bg-white/20 rounded-full h-1 mt-2">
                        <div class="bg-gradient-to-r from-purple-400 to-indigo-400 h-1 rounded-full animate-pulse" style="width: 99%"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Badge "Powered by" moderne -->
        <div class="mt-8 flex justify-center">
            <div class="bg-white/5 backdrop-blur-xl rounded-full px-6 py-2 border border-white/20 flex items-center space-x-2">
                <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                <span class="text-white/80 text-xs font-medium">Plateforme Sécurisée</span>
                <div class="w-2 h-2 bg-blue-400 rounded-full animate-pulse delay-500"></div>
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