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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold mb-2">Candidature de Stage</h1>
                    <p class="text-blue-100">Ministère des Finances</p>
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
        <!-- Particules flottantes animées -->
        <div class="absolute inset-0 overflow-hidden">
            <!-- Cercles flottants avec animations décalées -->
            <div class="absolute top-20 left-12 w-2 h-2 bg-white/30 rounded-full animate-bounce" style="animation-delay: 0s; animation-duration: 3s;"></div>
            <div class="absolute top-1/3 right-20 w-1 h-1 bg-blue-200/40 rounded-full animate-bounce" style="animation-delay: 1s; animation-duration: 4s;"></div>
            <div class="absolute bottom-1/3 left-16 w-3 h-3 bg-indigo-200/30 rounded-full animate-bounce" style="animation-delay: 2s; animation-duration: 3.5s;"></div>
            <div class="absolute top-2/3 right-12 w-1.5 h-1.5 bg-white/40 rounded-full animate-bounce" style="animation-delay: 0.5s; animation-duration: 2.5s;"></div>
            
            <!-- Formes géométriques modernes -->
            <div class="absolute top-1/4 -left-20 w-40 h-40 bg-gradient-to-br from-blue-400/20 to-transparent rounded-full blur-2xl animate-pulse-slow"></div>
            <div class="absolute bottom-1/4 -right-32 w-60 h-60 bg-gradient-to-tl from-indigo-400/15 to-transparent rounded-full blur-3xl animate-pulse-slow" style="animation-delay: 2s;"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-32 h-32 border border-white/10 rounded-xl rotate-45 animate-spin-slow"></div>
        </div>

        <div class="relative z-10 h-full flex flex-col justify-center p-12">
            <!-- Logo modernisé comme sur la page de login -->
            <div class="mb-12">
                <div class="relative mb-8">
                    <div class="absolute inset-0 bg-white/10 backdrop-blur-xl rounded-3xl blur-sm"></div>
                    <div class="relative bg-white/5 backdrop-blur-xl rounded-3xl p-8 border border-white/20">
                        <img :src="logoUrl" alt="Ministère" class="max-h-24 w-auto mx-auto filter drop-shadow-2xl" />
                    </div>
                </div>
            </div>

            <!-- Logo et titre modernisés -->
            <div class="mb-12 transform transition-all duration-1000 hover:scale-105">
                <div class="text-center mb-8">
                    
                    <div class="flex items-center justify-center space-x-2 mb-2">
                    <div class="w-8 h-0.5 bg-gradient-to-r from-transparent to-cyan-400 animate-pulse"></div>
                    <p class="text-cyan-100 text-lg font-medium">République du Bénin</p>
                    <div class="w-8 h-0.5 bg-gradient-to-l from-transparent to-cyan-400 animate-pulse"></div>
                </div>
                <p class="text-blue-200/80 text-sm font-light">Transformation Digitale</p>
                    <div class="w-24 h-1 bg-gradient-to-r from-transparent via-white/50 to-transparent mx-auto rounded-full"></div>
                </div>
            </div>

            <!-- Illustration SVG spécialisée pour l'inscription aux stages -->
            <div class="flex-1 flex items-center justify-center mb-12">
                <div class="relative max-w-lg w-full">
                    <svg viewBox="0 0 500 350" class="w-full h-auto">
                        <!-- Fond avec grille moderne -->
                        <defs>
                            <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                                <path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/>
                            </pattern>
                            <linearGradient id="cardGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:rgba(255,255,255,0.2);stop-opacity:1" />
                                <stop offset="100%" style="stop-color:rgba(255,255,255,0.05);stop-opacity:1" />
                            </linearGradient>
                            <linearGradient id="internGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:rgba(59,130,246,0.8);stop-opacity:1" />
                                <stop offset="100%" style="stop-color:rgba(99,102,241,0.6);stop-opacity:1" />
                            </linearGradient>
                            <linearGradient id="stageGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:rgba(34,197,94,0.8);stop-opacity:1" />
                                <stop offset="100%" style="stop-color:rgba(16,185,129,0.6);stop-opacity:1" />
                            </linearGradient>
                        </defs>

                        <!-- Grille de fond -->
                        <rect width="500" height="350" fill="url(#grid)" opacity="0.3"/>

                        <!-- Stagiaires en formation - Design moderne -->
                        <g class="animate-float-1">
                            <!-- Stagiaire 1 -->
                            <circle cx="120" cy="140" r="35" fill="url(#cardGradient)" stroke="rgba(59,130,246,0.4)" stroke-width="2"/>
                            <circle cx="120" cy="130" r="12" fill="rgba(59,130,246,0.8)"/>
                            <path d="M105 150 Q120 145 135 150 Q135 160 120 165 Q105 160 105 150" fill="rgba(59,130,246,0.6)"/>
                            <!-- Badge stagiaire -->
                            <rect x="135" y="125" width="15" height="10" rx="2" fill="rgba(59,130,246,0.6)"/>
                            <text x="142" y="132" text-anchor="middle" fill="white" font-size="4" font-weight="bold">S</text>
                        </g>

                        <g class="animate-float-2">
                            <!-- Maître de stage (central) -->
                            <circle cx="250" cy="120" r="40" fill="url(#cardGradient)" stroke="rgba(34,197,94,0.4)" stroke-width="3"/>
                            <circle cx="250" cy="108" r="15" fill="rgba(34,197,94,0.8)"/>
                            <path d="M230 140 Q250 135 270 140 Q270 155 250 160 Q230 155 230 140" fill="rgba(34,197,94,0.6)"/>
                            <!-- Badge maître de stage -->
                            <rect x="235" y="95" width="30" height="8" rx="4" fill="rgba(34,197,94,0.8)"/>
                            <text x="250" y="101" text-anchor="middle" fill="white" font-size="4" font-weight="bold">MAÎTRE DE STAGE</text>
                        </g>

                        <g class="animate-float-3">
                            <!-- Stagiaire 2 -->
                            <circle cx="380" cy="145" r="32" fill="url(#cardGradient)" stroke="rgba(99,102,241,0.4)" stroke-width="2"/>
                            <circle cx="380" cy="135" r="11" fill="rgba(99,102,241,0.8)"/>
                            <path d="M368 155 Q380 150 392 155 Q392 165 380 170 Q368 165 368 155" fill="rgba(99,102,241,0.6)"/>
                            <!-- Badge stagiaire -->
                            <rect x="395" y="130" width="15" height="10" rx="2" fill="rgba(99,102,241,0.6)"/>
                            <text x="402" y="137" text-anchor="middle" fill="white" font-size="4" font-weight="bold">S</text>
                        </g>

                        <!-- Connexions de formation avec animation -->
                        <g stroke="rgba(34,197,94,0.4)" stroke-width="3" fill="none">
                            <path d="M155 140 Q200 130 215 120" class="animate-draw"/>
                            <path d="M285 125 Q330 130 348 140" class="animate-draw" style="animation-delay: 1s;"/>
                        </g>

                        <!-- Documents de stage -->
                        <g class="animate-pulse-tech">
                            <!-- Dossier de candidature -->
                            <rect x="80" y="220" width="100" height="70" rx="12" fill="url(#cardGradient)" stroke="rgba(59,130,246,0.3)" stroke-width="2"/>
                            <rect x="90" y="235" width="80" height="4" rx="2" fill="rgba(59,130,246,0.6)"/>
                            <rect x="90" y="245" width="60" height="4" rx="2" fill="rgba(59,130,246,0.4)"/>
                            <rect x="90" y="255" width="70" height="4" rx="2" fill="rgba(59,130,246,0.5)"/>
                            <!-- Icônes de documents -->
                            <rect x="85" y="265" width="12" height="15" rx="2" fill="rgba(59,130,246,0.6)"/>
                            <line x1="87" y1="270" x2="95" y2="270" stroke="white" stroke-width="0.8"/>
                            <line x1="87" y1="273" x2="95" y2="273" stroke="white" stroke-width="0.8"/>
                            <line x1="87" y1="276" x2="93" y2="276" stroke="white" stroke-width="0.8"/>
                            <rect x="105" y="265" width="12" height="15" rx="2" fill="rgba(34,197,94,0.6)"/>
                            <line x1="107" y1="270" x2="115" y2="270" stroke="white" stroke-width="0.8"/>
                            <line x1="107" y1="273" x2="115" y2="273" stroke="white" stroke-width="0.8"/>
                            <line x1="107" y1="276" x2="113" y2="276" stroke="white" stroke-width="0.8"/>
                            <text x="130" y="280" text-anchor="middle" fill="rgba(255,255,255,0.8)" font-size="6" font-weight="bold">CANDIDATURE</text>
                        </g>

                        <!-- Graphique de progression des stages -->
                        <g class="animate-chart">
                            <rect x="320" y="200" width="120" height="80" rx="12" fill="url(#cardGradient)" stroke="rgba(34,197,94,0.3)" stroke-width="2"/>
                            <!-- Barres du graphique représentant les étapes du stage -->
                            <rect x="335" y="250" width="12" height="20" rx="2" fill="rgba(59,130,246,0.6)"/>
                            <rect x="355" y="240" width="12" height="30" rx="2" fill="rgba(34,197,94,0.6)"/>
                            <rect x="375" y="230" width="12" height="40" rx="2" fill="rgba(16,185,129,0.6)"/>
                            <rect x="395" y="220" width="12" height="50" rx="2" fill="rgba(34,197,94,0.8)"/>
                            <!-- Labels des étapes -->
                            <text x="341" y="275" text-anchor="middle" fill="rgba(255,255,255,0.6)" font-size="4">CANDIDAT</text>
                            <text x="361" y="275" text-anchor="middle" fill="rgba(255,255,255,0.6)" font-size="4">ACCEPTÉ</text>
                            <text x="381" y="275" text-anchor="middle" fill="rgba(255,255,255,0.6)" font-size="4">EN COURS</text>
                            <text x="401" y="275" text-anchor="middle" fill="rgba(255,255,255,0.6)" font-size="4">VALIDÉ</text>
                            <text x="370" y="215" text-anchor="middle" fill="rgba(255,255,255,0.8)" font-size="6" font-weight="bold">PROGRESSION</text>
                        </g>

                        <!-- Notifications de validation de stage -->
                        <g class="animate-notification">
                            <circle cx="450" cy="80" r="25" fill="rgba(34,197,94,0.2)" stroke="rgba(34,197,94,0.6)" stroke-width="2"/>
                            <path d="M440 80 L448 88 L462 72" stroke="rgba(34,197,94,0.8)" stroke-width="3" fill="none" stroke-linecap="round"/>
                            <text x="450" y="110" text-anchor="middle" fill="rgba(34,197,94,0.8)" font-size="6" font-weight="bold">STAGE VALIDÉ</text>
                        </g>

                        <g class="animate-notification" style="animation-delay: 2s;">
                            <circle cx="50" cy="90" r="20" fill="rgba(59,130,246,0.2)" stroke="rgba(59,130,246,0.6)" stroke-width="2"/>
                            <circle cx="50" cy="90" r="6" fill="rgba(59,130,246,0.8)"/>
                            <path d="M44 96 Q50 94 56 96" stroke="rgba(59,130,246,0.8)" stroke-width="1" fill="none"/>
                            <text x="50" y="115" text-anchor="middle" fill="rgba(59,130,246,0.8)" font-size="5" font-weight="bold">NOUVEAU STAGIAIRE</text>
                        </g>

                        <!-- Badge d'évaluation -->
                        <g class="animate-notification" style="animation-delay: 4s;">
                            <circle cx="450" cy="300" r="22" fill="rgba(168,85,247,0.2)" stroke="rgba(168,85,247,0.6)" stroke-width="2"/>
                            <rect x="445" y="295" width="10" height="8" rx="1" fill="rgba(168,85,247,0.6)"/>
                            <line x1="447" y1="298" x2="453" y2="298" stroke="white" stroke-width="0.8"/>
                            <line x1="447" y1="300" x2="453" y2="300" stroke="white" stroke-width="0.8"/>
                            <text x="450" y="325" text-anchor="middle" fill="rgba(168,85,247,0.8)" font-size="5" font-weight="bold">ÉVALUATION</text>
                        </g>

                        <!-- Flèches de flux de candidature -->
                        <g stroke="rgba(34,197,94,0.5)" stroke-width="2" fill="none" marker-end="url(#modernArrow)">
                            <path d="M180 230 Q220 210 280 220" class="animate-flow"/>
                            <path d="M200 150 Q240 170 300 160" class="animate-flow" style="animation-delay: 1.5s;"/>
                        </g>

                        <defs>
                            <marker id="modernArrow" markerWidth="12" markerHeight="8" refX="10" refY="4" orient="auto" markerUnits="strokeWidth">
                                <path d="M0,0 L0,8 L12,4 z" fill="rgba(34,197,94,0.5)"/>
                            </marker>
                        </defs>
                    </svg>
                </div>
            </div>

            <!-- Avantages des stages avec icônes et animations -->
            <div class="space-y-6">
                <div class="flex items-center space-x-4 group hover:translate-x-2 transition-transform duration-300">
                    <div class="w-12 h-12 bg-gradient-to-br from-white/20 to-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 border border-white/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-white font-semibold text-lg">Formation Pratique</span>
                        <p class="text-blue-100 text-sm">Expérience professionnelle concrète</p>
                    </div>
                </div>

                <div class="flex items-center space-x-4 group hover:translate-x-2 transition-transform duration-300">
                    <div class="w-12 h-12 bg-gradient-to-br from-white/20 to-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 border border-white/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-white font-semibold text-lg">Maître de Stage</span>
                        <p class="text-blue-100 text-sm">Encadrement professionnel expert</p>
                    </div>
                </div>

                <div class="flex items-center space-x-4 group hover:translate-x-2 transition-transform duration-300">
                    <div class="w-12 h-12 bg-gradient-to-br from-white/20 to-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 border border-white/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-white font-semibold text-lg">Validation Officielle</span>
                        <p class="text-blue-100 text-sm">Attestation de stage reconnue</p>
                    </div>
                </div>

                <div class="flex items-center space-x-4 group hover:translate-x-2 transition-transform duration-300">
                    <div class="w-12 h-12 bg-gradient-to-br from-white/20 to-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 border border-white/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-white font-semibold text-lg">Suivi Personnalisé</span>
                        <p class="text-blue-100 text-sm">Évaluation continue et feedback</p>
                    </div>
                </div>
            </div>

            <!-- Call-to-action subtil -->
            <div class="mt-8 text-center opacity-70">
                <p class="text-blue-100 text-sm italic">
                    "Un stage réussi est le premier pas vers une carrière épanouie."
                </p>
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
                            Candidature de Stage
                        </h2>
                        <p class="text-gray-600 font-medium">Rejoignez le programme de stages du Ministère</p>
                        <p class="text-blue-600 text-sm font-semibold">Première étape vers votre avenir professionnel</p>
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
/* Animations modernisées */
@keyframes float-1 {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-8px) rotate(1deg); }
}

@keyframes float-2 {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-12px) rotate(-1deg); }
}

@keyframes float-3 {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-6px) rotate(0.5deg); }
}

@keyframes pulse-slow {
    0%, 100% { opacity: 0.8; transform: scale(1); }
    50% { opacity: 0.4; transform: scale(1.1); }
}

@keyframes spin-slow {
    from { transform: translate(-50%, -50%) rotate(45deg); }
    to { transform: translate(-50%, -50%) rotate(405deg); }
}

@keyframes draw {
    0% { stroke-dasharray: 0 100; }
    100% { stroke-dasharray: 100 0; }
}

@keyframes pulse-tech {
    0%, 100% { opacity: 1; transform: translateY(0px); }
    50% { opacity: 0.7; transform: translateY(-2px); }
}

@keyframes chart {
    0% { transform: scaleY(0); }
    100% { transform: scaleY(1); }
}

@keyframes notification {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.1); opacity: 0.8; }
}

@keyframes flow {
    0% { stroke-dasharray: 0 20; }
    100% { stroke-dasharray: 20 0; }
}

.animate-float-1 {
    animation: float-1 6s ease-in-out infinite;
}

.animate-float-2 {
    animation: float-2 7s ease-in-out infinite;
}

.animate-float-3 {
    animation: float-3 5s ease-in-out infinite;
}

.animate-pulse-slow {
    animation: pulse-slow 4s ease-in-out infinite;
}

.animate-spin-slow {
    animation: spin-slow 20s linear infinite;
}

.animate-draw {
    animation: draw 3s ease-in-out infinite;
}

.animate-pulse-tech {
    animation: pulse-tech 3s ease-in-out infinite;
}

.animate-chart {
    animation: chart 2s ease-in-out;
    transform-origin: bottom;
}

.animate-notification {
    animation: notification 4s ease-in-out infinite;
}

.animate-flow {
    animation: flow 2s ease-in-out infinite;
}

/* Effet de hover sur l'ensemble de la section */
.group:hover .w-12 {
    box-shadow: 0 10px 25px rgba(255,255,255,0.1);
}
</style>