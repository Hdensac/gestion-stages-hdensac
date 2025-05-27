<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

const logoUrl = '/images/logoministere.png';

const form = useForm({
    name: '',
    prenom: '',
    date_de_naissance: '',
    sexe: '',
    email: '',
    password: '',
    password_confirmation: '',
    telephone: '',
});

const passwordVisible = ref(false);
const confirmPasswordVisible = ref(false);
const isLoading = ref(false);
const isLoaded = ref(false);

const togglePasswordVisibility = () => {
    passwordVisible.value = !passwordVisible.value;
};

const toggleConfirmPasswordVisibility = () => {
    confirmPasswordVisible.value = !confirmPasswordVisible.value;
};

const submit = () => {
    isLoading.value = true;
    form.post(route('register'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
            isLoading.value = false;
        },
    });
};

onMounted(() => {
    setTimeout(() => {
        isLoaded.value = true;
    }, 200);
});

// Validation temps réel du mot de passe
const passwordStrength = ref(0);
const getPasswordStrength = (password) => {
    let strength = 0;
    if (password.length >= 8) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/\d/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;
    return strength;
};

const updatePasswordStrength = () => {
    passwordStrength.value = getPasswordStrength(form.password);
};
</script>

<template>
    <Head title="Inscription" />
    
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold mb-2">Rejoignez-nous</h1>
                    <p class="text-blue-100">Programme de Stages 2025</p>
                </div>
            </div>
            
            <!-- Formulaire mobile -->
            <div class="flex-1 p-6 overflow-y-auto">
                <div 
                    class="max-w-sm mx-auto transition-all duration-1000"
                    :class="{ 'opacity-100 translate-y-0': isLoaded, 'opacity-0 translate-y-8': !isLoaded }"
                >
                    <!-- Formulaire condensé mobile -->
                    <form @submit.prevent="submit" class="space-y-4">
                        <!-- Nom et Prénom sur la même ligne -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Nom</label>
                                <input
                                    type="text"
                                    v-model="form.name"
                                    required
                                    placeholder="Nom"
                                    class="w-full px-3 py-3 bg-white/80 border border-gray-200/50 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-300"
                                />
                                <div v-if="form.errors.name" class="mt-1 text-xs text-red-600">{{ form.errors.name }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Prénom</label>
                                <input
                                    type="text"
                                    v-model="form.prenom"
                                    required
                                    placeholder="Prénom"
                                    class="w-full px-3 py-3 bg-white/80 border border-gray-200/50 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-300"
                                />
                                <div v-if="form.errors.prenom" class="mt-1 text-xs text-red-600">{{ form.errors.prenom }}</div>
                            </div>
                        </div>

                        <!-- Date et Sexe -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Date de naissance</label>
                                <input
                                    type="date"
                                    v-model="form.date_de_naissance"
                                    required
                                    class="w-full px-3 py-3 bg-white/80 border border-gray-200/50 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-300"
                                />
                                <div v-if="form.errors.date_de_naissance" class="mt-1 text-xs text-red-600">{{ form.errors.date_de_naissance }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Sexe</label>
                                <select
                                    v-model="form.sexe"
                                    required
                                    class="w-full px-3 py-3 bg-white/80 border border-gray-200/50 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-300"
                                >
                                    <option value="">--</option>
                                    <option value="Homme">Homme</option>
                                    <option value="Femme">Femme</option>
                                </select>
                                <div v-if="form.errors.sexe" class="mt-1 text-xs text-red-600">{{ form.errors.sexe }}</div>
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
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
                                placeholder="votre@email.com"
                                    class="w-full pl-12 pr-4 py-3 bg-white/80 border border-gray-200/50 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-300 hover:shadow-md"
                            />
                            </div>
                            <div v-if="form.errors.email" class="mt-1 text-xs text-red-600">{{ form.errors.email }}</div>
                        </div>

                        <!-- Téléphone -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Téléphone</label>
                            <input
                                type="tel"
                                v-model="form.telephone"
                                required
                                placeholder="+243 XXX XXX XXX"
                                class="w-full px-3 py-3 bg-white/80 border border-gray-200/50 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-300"
                            />
                            <div v-if="form.errors.telephone" class="mt-1 text-xs text-red-600">{{ form.errors.telephone }}</div>
                        </div>

                        <!-- Mots de passe -->
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Mot de passe</label>
                                <div class="relative">
                                    <input
                                        :type="passwordVisible ? 'text' : 'password'"
                                        v-model="form.password"
                                        @input="updatePasswordStrength"
                                        required
                                        placeholder="••••••••"
                                        class="w-full px-3 pr-10 py-3 bg-white/80 border border-gray-200/50 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-300"
                                    />
                                    <button
                                        type="button"
                                        @click="togglePasswordVisibility"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400"
                                    >
                                        <svg v-if="passwordVisible" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                        </svg>
                                        <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                </div>
                                <div v-if="form.errors.password" class="mt-1 text-xs text-red-600">{{ form.errors.password }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Confirmer</label>
                                <input
                                    :type="confirmPasswordVisible ? 'text' : 'password'"
                                    v-model="form.password_confirmation"
                                    required
                                    placeholder="••••••••"
                                    class="w-full px-3 py-3 bg-white/80 border border-gray-200/50 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-300"
                                />
                                <div v-if="form.errors.password_confirmation" class="mt-1 text-xs text-red-600">{{ form.errors.password_confirmation }}</div>
                            </div>
                        </div>

                        <!-- Bouton inscription -->
                        <button
                            type="submit"
                            :disabled="form.processing || isLoading"
                            class="w-full py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl hover:shadow-lg transition-all duration-300 hover:scale-105 disabled:opacity-75"
                        >
                            {{ isLoading ? 'Création...' : 'Créer mon compte' }}
                        </button>

                        <!-- Lien connexion -->
                        <div class="text-center pt-2">
                            <p class="text-gray-600 text-sm">
                                Déjà membre ?
                                <Link :href="route('login')" class="text-blue-600 hover:text-blue-800 font-medium">
                                    Se connecter
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
                            <h1 class="text-4xl font-black mb-3 bg-gradient-to-r from-white to-pink-100 bg-clip-text text-transparent">
                                Rejoignez l'Excellence
                            </h1>
                            <p class="text-indigo-100 text-lg">Programme de Stages 2025</p>
                        </div>
                    </div>

                    <!-- Illustration SVG pour inscription -->
                    <div class="flex-1 flex items-center justify-center mb-8">
                        <div class="relative max-w-md w-full">
                            <svg viewBox="0 0 400 300" class="w-full h-auto">
                                <!-- Groupe de personnes -->
                                <circle cx="120" cy="140" r="30" fill="rgba(59,130,246,0.3)" stroke="rgba(59,130,246,0.6)" stroke-width="2"/>
                                <circle cx="180" cy="120" r="35" fill="rgba(99,102,241,0.3)" stroke="rgba(99,102,241,0.6)" stroke-width="2"/>
                                <circle cx="240" cy="145" r="32" fill="rgba(59,130,246,0.3)" stroke="rgba(59,130,246,0.6)" stroke-width="2"/>
                                
                                <!-- Connexions entre personnes -->
                                <path d="M145 150 Q160 135 155 120" stroke="rgba(255,255,255,0.3)" stroke-width="3" fill="none"/>
                                <path d="M210 135 Q225 140 225 155" stroke="rgba(255,255,255,0.3)" stroke-width="3" fill="none"/>
                                
                                <!-- Étoiles de réussite -->
                                <g transform="translate(300,80)">
                                    <path d="M0,-20 L6,-6 L20,-6 L10,2 L16,16 L0,8 L-16,16 L-10,2 L-20,-6 L-6,-6 Z" fill="rgba(255,215,0,0.4)" class="animate-float">
                                        <animate attributeName="opacity" values="0.4;0.8;0.4" dur="2s" repeatCount="indefinite"/>
                                    </path>
                                </g>
                                
                                <g transform="translate(320,200)">
                                    <path d="M0,-15 L4.5,-4.5 L15,-4.5 L7.5,1.5 L12,12 L0,6 L-12,12 L-7.5,1.5 L-15,-4.5 L-4.5,-4.5 Z" fill="rgba(255,215,0,0.3)" class="animate-float">
                                        <animate attributeName="opacity" values="0.3;0.7;0.3" dur="3s" repeatCount="indefinite"/>
                                    </path>
                                </g>
                                
                                <!-- Documents/certificats -->
                                <rect x="50" y="200" width="80" height="60" rx="8" fill="rgba(255,255,255,0.1)" stroke="rgba(255,255,255,0.3)" stroke-width="2"/>
                                <rect x="60" y="210" width="60" height="4" rx="2" fill="rgba(255,255,255,0.4)"/>
                                <rect x="60" y="220" width="45" height="4" rx="2" fill="rgba(255,255,255,0.3)"/>
                                <rect x="60" y="230" width="55" height="4" rx="2" fill="rgba(255,255,255,0.4)"/>
                                
                                <!-- Flèches de progression -->
                                <path d="M150 200 Q200 180 250 200" stroke="rgba(255,255,255,0.4)" stroke-width="3" fill="none" marker-end="url(#arrowhead)"/>
                                
                                <defs>
                                    <marker id="arrowhead" markerWidth="10" markerHeight="7" refX="0" refY="3.5" orient="auto">
                                        <polygon points="0 0, 10 3.5, 0 7" fill="rgba(255,255,255,0.4)"/>
                                    </marker>
                                </defs>
                            </svg>
                        </div>
                    </div>

                    <!-- Avantages du programme -->
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="text-white/90">Formation professionnelle de qualité</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="text-white/90">Encadrement par des experts</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="text-white/90">Opportunités de carrière</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne droite : Formulaire -->
            <div class="relative flex items-center justify-center p-8 bg-gradient-to-br from-gray-50 to-blue-50/30 overflow-y-auto">
                <!-- Éléments décoratifs de fond -->
                <div class="absolute inset-0 overflow-hidden pointer-events-none">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-indigo-400/10 to-transparent rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 w-48 h-48 bg-gradient-to-br from-blue-400/10 to-transparent rounded-full blur-2xl"></div>
                </div>

                <div 
                    class="relative z-10 w-full max-w-md transition-all duration-1000"
                    :class="{ 'opacity-100 translate-y-0': isLoaded, 'opacity-0 translate-y-8': !isLoaded }"
                >
                    <!-- En-tête du formulaire -->
                    <div class="text-center mb-6">
                        <h2 class="text-3xl font-black bg-gradient-to-r from-gray-900 via-indigo-800 to-purple-700 bg-clip-text text-transparent mb-2">
                            Créez votre compte
                        </h2>
                        <p class="text-gray-600 font-medium">Rejoignez le programme de stages</p>
                        <p class="text-blue-600 text-sm font-semibold">Session 2025</p>
                    </div>

                    <!-- Carte du formulaire -->
                    <div class="bg-white/90 backdrop-blur-xl rounded-3xl p-6 border border-white/20 shadow-2xl">
                        <form @submit.prevent="submit" class="space-y-4">
                            <!-- Nom et Prénom -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nom</label>
                                    <input
                                        type="text"
                                        v-model="form.name"
                                        required
                                        autofocus
                                        placeholder="Votre nom"
                                        class="w-full px-4 py-3 bg-white/80 border border-gray-200/50 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 hover:shadow-md"
                                    />
                                    <div v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Prénom</label>
                                    <input
                                        type="text"
                                        v-model="form.prenom"
                                        required
                                        placeholder="Votre prénom"
                                        class="w-full px-4 py-3 bg-white/80 border border-gray-200/50 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 hover:shadow-md"
                                    />
                                    <div v-if="form.errors.prenom" class="mt-1 text-sm text-red-600">{{ form.errors.prenom }}</div>
                                </div>
                            </div>

                            <!-- Date de naissance et Sexe -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Date de naissance</label>
                                    <input
                                        type="date"
                                        v-model="form.date_de_naissance"
                                        required
                                        class="w-full px-4 py-3 bg-white/80 border border-gray-200/50 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 hover:shadow-md"
                                    />
                                    <div v-if="form.errors.date_de_naissance" class="mt-1 text-sm text-red-600">{{ form.errors.date_de_naissance }}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Sexe</label>
                                    <select
                                        v-model="form.sexe"
                                        required
                                        class="w-full px-4 py-3 bg-white/80 border border-gray-200/50 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 hover:shadow-md"
                                    >
                                        <option value="">-- Sélectionnez --</option>
                                        <option value="Homme">Homme</option>
                                        <option value="Femme">Femme</option>
                                    </select>
                                    <div v-if="form.errors.sexe" class="mt-1 text-sm text-red-600">{{ form.errors.sexe }}</div>
                                </div>
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Adresse email</label>
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
                                        placeholder="votre@email.com"
                                        class="w-full pl-12 pr-4 py-3 bg-white/80 border border-gray-200/50 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 hover:shadow-md"
                                    />
                                </div>
                                <div v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</div>
                            </div>

                            <!-- Téléphone -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Téléphone</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                    </div>
                                    <input
                                        type="tel"
                                        v-model="form.telephone"
                                        required
                                        placeholder="+243 XXX XXX XXX"
                                        class="w-full pl-12 pr-4 py-3 bg-white/80 border border-gray-200/50 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 hover:shadow-md"
                                    />
                                </div>
                                <div v-if="form.errors.telephone" class="mt-1 text-sm text-red-600">{{ form.errors.telephone }}</div>
                            </div>

                            <!-- Mot de passe -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Mot de passe</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                    </div>
                                    <input
                                        :type="passwordVisible ? 'text' : 'password'"
                                        v-model="form.password"
                                        @input="updatePasswordStrength"
                                        required
                                        placeholder="Minimum 8 caractères"
                                        class="w-full pl-12 pr-12 py-3 bg-white/80 border border-gray-200/50 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 hover:shadow-md"
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
                                
                                <!-- Indicateur de force du mot de passe -->
                                <div v-if="form.password" class="mt-2">
                                    <div class="flex space-x-1">
                                        <div 
                                            v-for="i in 5" 
                                            :key="i"
                                            class="h-1 flex-1 rounded-full transition-all duration-300"
                                            :class="{
                                                'bg-red-500': i <= passwordStrength && passwordStrength <= 2,
                                                'bg-yellow-500': i <= passwordStrength && passwordStrength === 3,
                                                'bg-green-500': i <= passwordStrength && passwordStrength >= 4,
                                                'bg-gray-200': i > passwordStrength
                                            }"
                                        ></div>
                                    </div>
                                </div>
                                
                                <div v-if="form.errors.password" class="mt-2 text-sm text-red-600">{{ form.errors.password }}</div>
                            </div>

                            <!-- Confirmation du mot de passe -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Confirmer le mot de passe</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                    </div>
                                    <input
                                        :type="confirmPasswordVisible ? 'text' : 'password'"
                                        v-model="form.password_confirmation"
                                        required
                                        placeholder="Retapez votre mot de passe"
                                        class="w-full pl-12 pr-12 py-3 bg-white/80 border border-gray-200/50 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 hover:shadow-md"
                                    />
                                    <button
                                        type="button"
                                        @click="toggleConfirmPasswordVisibility"
                                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors duration-200"
                                    >
                                        <svg v-if="confirmPasswordVisible" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                        </svg>
                                        <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                </div>
                                <div v-if="form.errors.password_confirmation" class="mt-2 text-sm text-red-600">{{ form.errors.password_confirmation }}</div>
                            </div>

                            <!-- Bouton d'inscription -->
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                    </svg>
                                    {{ isLoading ? 'Création du compte...' : 'Créer mon compte' }}
                                </span>
                                <div class="absolute inset-0 bg-gradient-to-r from-blue-700 to-indigo-700 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
                                <div class="absolute inset-0 bg-white/20 transform scale-0 group-hover:scale-100 transition-transform duration-500 rounded-2xl"></div>
                            </button>
                        </form>
                    </div>

                    <!-- Lien de connexion -->
                    <div class="mt-6 text-center">
                        <div class="bg-white/60 backdrop-blur-sm rounded-2xl p-4 border border-white/20">
                            <p class="text-gray-600 text-sm mb-2">
                                Vous avez déjà un compte ?
                            </p>
                            <Link 
                                :href="route('login')" 
                                class="inline-flex items-center font-medium text-blue-600 hover:text-blue-800 transition-colors duration-300 hover:scale-105 transform"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                </svg>
                                Se connecter maintenant
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
input:focus, select:focus {
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