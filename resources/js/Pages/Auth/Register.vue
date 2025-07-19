<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, onMounted, watch } from 'vue';

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
const passwordStrength = ref(0);

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

// Validation temps réel du mot de passe
const getPasswordStrength = (password) => {
    let strength = 0;
    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;
    return strength;
};

watch(() => form.password, (newPassword) => {
    passwordStrength.value = getPasswordStrength(newPassword);
});

onMounted(() => {
    setTimeout(() => {
        isLoaded.value = true;
    }, 100);
});
</script>

<template>
    <Head title="Inscription" />

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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold mb-2">Inscription</h1>
                    <p class="text-blue-100">Ministère de l'Économie et des Finances</p>
                </div>
            </div>
            
            <!-- Formulaire mobile -->
            <div class="flex-1 bg-white p-6">
                <div class="max-w-sm mx-auto">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Créer un compte</h2>
                        <p class="text-gray-600">Rejoignez la plateforme de stages</p>
                    </div>

                    <!-- Formulaire -->
                    <form @submit.prevent="submit" class="space-y-4">
                        <!-- Nom et Prénom -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nom</label>
                                <input
                                    type="text"
                                    v-model="form.name"
                                    required
                                    placeholder="Nom"
                                    class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200"
                                />
                                <div v-if="form.errors.name" class="mt-1 text-xs text-red-600">{{ form.errors.name }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Prénom</label>
                                <input
                                    type="text"
                                    v-model="form.prenom"
                                    required
                                    placeholder="Prénom"
                                    class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200"
                                />
                                <div v-if="form.errors.prenom" class="mt-1 text-xs text-red-600">{{ form.errors.prenom }}</div>
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Adresse email</label>
                            <input
                                type="email"
                                v-model="form.email"
                                required
                                placeholder="votre@email.com"
                                class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200"
                            />
                            <div v-if="form.errors.email" class="mt-1 text-xs text-red-600">{{ form.errors.email }}</div>
                        </div>

                        <!-- Téléphone -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Téléphone</label>
                            <input
                                type="tel"
                                v-model="form.telephone"
                                required
                                placeholder="+229 XX XX XX XX"
                                class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200"
                            />
                            <div v-if="form.errors.telephone" class="mt-1 text-xs text-red-600">{{ form.errors.telephone }}</div>
                        </div>

                        <!-- Date de naissance et Sexe -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Date de naissance</label>
                                <input
                                    type="date"
                                    v-model="form.date_de_naissance"
                                    required
                                    class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200"
                                />
                                <div v-if="form.errors.date_de_naissance" class="mt-1 text-xs text-red-600">{{ form.errors.date_de_naissance }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Sexe</label>
                                <select
                                    v-model="form.sexe"
                                    required
                                    class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200"
                                >
                                    <option value="">Choisir</option>
                                    <option value="M">Masculin</option>
                                    <option value="F">Féminin</option>
                                </select>
                                <div v-if="form.errors.sexe" class="mt-1 text-xs text-red-600">{{ form.errors.sexe }}</div>
                            </div>
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
                                    class="w-full px-3 py-2 pr-10 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200"
                                />
                                <button
                                    type="button"
                                    @click="togglePasswordVisibility"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                                >
                                    <svg v-if="!passwordVisible" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                    </svg>
                                </button>
                            </div>
                            <!-- Indicateur de force du mot de passe -->
                            <div v-if="form.password" class="mt-2">
                                <div class="flex space-x-1">
                                    <div v-for="i in 5" :key="i" 
                                         :class="[
                                             'h-1 flex-1 rounded-full transition-colors duration-200',
                                             i <= passwordStrength ? 
                                                 (passwordStrength <= 2 ? 'bg-red-500' : 
                                                  passwordStrength <= 3 ? 'bg-yellow-500' : 'bg-green-500') 
                                                 : 'bg-gray-200'
                                         ]">
                                    </div>
                                </div>
                                <p class="text-xs text-gray-600 mt-1">
                                    Force: {{ passwordStrength <= 2 ? 'Faible' : passwordStrength <= 3 ? 'Moyenne' : 'Forte' }}
                                </p>
                            </div>
                            <div v-if="form.errors.password" class="mt-1 text-xs text-red-600">{{ form.errors.password }}</div>
                        </div>

                        <!-- Confirmation mot de passe -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Confirmer le mot de passe</label>
                            <div class="relative">
                                <input
                                    :type="confirmPasswordVisible ? 'text' : 'password'"
                                    v-model="form.password_confirmation"
                                    required
                                    placeholder="••••••••"
                                    class="w-full px-3 py-2 pr-10 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200"
                                />
                                <button
                                    type="button"
                                    @click="toggleConfirmPasswordVisibility"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                                >
                                    <svg v-if="!confirmPasswordVisible" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                    </svg>
                                </button>
                            </div>
                            <div v-if="form.errors.password_confirmation" class="mt-1 text-xs text-red-600">{{ form.errors.password_confirmation }}</div>
                        </div>

                        <!-- Bouton d'inscription -->
                        <button
                            type="submit"
                            :disabled="form.processing || isLoading"
                            class="w-full py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 disabled:opacity-75"
                        >
                            {{ isLoading ? 'Inscription...' : 'S\'inscrire' }}
                        </button>

                        <!-- Lien connexion -->
                        <div class="text-center">
                            <p class="text-gray-600 text-sm">
                                Déjà un compte ?
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-white mb-4">Rejoignez-nous</h3>
                            <p class="text-blue-100 leading-relaxed">
                                Créez votre compte pour accéder à la plateforme de gestion des stages et découvrir les opportunités qui vous attendent.
                            </p>
                        </div>
                        
                        <!-- Avantages -->
                        <div class="space-y-4">
                            <div class="flex items-center text-blue-100">
                                <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Accès aux offres de stage
                            </div>
                            <div class="flex items-center text-blue-100">
                                <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Suivi en temps réel
                            </div>
                            <div class="flex items-center text-blue-100">
                                <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Attestations automatisées
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne droite : Formulaire -->
            <div class="relative flex items-center justify-center p-8 bg-white overflow-y-auto">
                <div class="w-full max-w-md">
                    <!-- En-tête du formulaire -->
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">
                            Créer un compte
                        </h2>
                        <p class="text-gray-600 font-medium">Rejoignez la plateforme de stages</p>
                    </div>

                    <!-- Formulaire -->
                    <form @submit.prevent="submit" class="space-y-4">
                        <!-- Nom et Prénom -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nom</label>
                                <input
                                    type="text"
                                    v-model="form.name"
                                    required
                                    placeholder="Nom"
                                    class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200"
                                />
                                <div v-if="form.errors.name" class="mt-1 text-xs text-red-600">{{ form.errors.name }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Prénom</label>
                                <input
                                    type="text"
                                    v-model="form.prenom"
                                    required
                                    placeholder="Prénom"
                                    class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200"
                                />
                                <div v-if="form.errors.prenom" class="mt-1 text-xs text-red-600">{{ form.errors.prenom }}</div>
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Adresse email</label>
                            <input
                                type="email"
                                v-model="form.email"
                                required
                                placeholder="votre@email.com"
                                class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200"
                            />
                            <div v-if="form.errors.email" class="mt-1 text-xs text-red-600">{{ form.errors.email }}</div>
                        </div>

                        <!-- Téléphone -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Téléphone</label>
                            <input
                                type="tel"
                                v-model="form.telephone"
                                required
                                placeholder="+229 XX XX XX XX"
                                class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200"
                            />
                            <div v-if="form.errors.telephone" class="mt-1 text-xs text-red-600">{{ form.errors.telephone }}</div>
                        </div>

                        <!-- Date de naissance et Sexe -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Date de naissance</label>
                                <input
                                    type="date"
                                    v-model="form.date_de_naissance"
                                    required
                                    class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200"
                                />
                                <div v-if="form.errors.date_de_naissance" class="mt-1 text-xs text-red-600">{{ form.errors.date_de_naissance }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Sexe</label>
                                <select
                                    v-model="form.sexe"
                                    required
                                    class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200"
                                >
                                    <option value="">Choisir</option>
                                    <option value="M">Masculin</option>
                                    <option value="F">Féminin</option>
                                </select>
                                <div v-if="form.errors.sexe" class="mt-1 text-xs text-red-600">{{ form.errors.sexe }}</div>
                            </div>
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
                                    class="w-full px-3 py-2 pr-10 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200"
                                />
                                <button
                                    type="button"
                                    @click="togglePasswordVisibility"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                                >
                                    <svg v-if="!passwordVisible" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                    </svg>
                                </button>
                            </div>
                            <!-- Indicateur de force du mot de passe -->
                            <div v-if="form.password" class="mt-2">
                                <div class="flex space-x-1">
                                    <div v-for="i in 5" :key="i" 
                                         :class="[
                                             'h-1 flex-1 rounded-full transition-colors duration-200',
                                             i <= passwordStrength ? 
                                                 (passwordStrength <= 2 ? 'bg-red-500' : 
                                                  passwordStrength <= 3 ? 'bg-yellow-500' : 'bg-green-500') 
                                                 : 'bg-gray-200'
                                         ]">
                                    </div>
                                </div>
                                <p class="text-xs text-gray-600 mt-1">
                                    Force: {{ passwordStrength <= 2 ? 'Faible' : passwordStrength <= 3 ? 'Moyenne' : 'Forte' }}
                                </p>
                            </div>
                            <div v-if="form.errors.password" class="mt-1 text-xs text-red-600">{{ form.errors.password }}</div>
                        </div>

                        <!-- Confirmation mot de passe -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Confirmer le mot de passe</label>
                            <div class="relative">
                                <input
                                    :type="confirmPasswordVisible ? 'text' : 'password'"
                                    v-model="form.password_confirmation"
                                    required
                                    placeholder="••••••••"
                                    class="w-full px-3 py-2 pr-10 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200"
                                />
                                <button
                                    type="button"
                                    @click="toggleConfirmPasswordVisibility"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                                >
                                    <svg v-if="!confirmPasswordVisible" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                    </svg>
                                </button>
                            </div>
                            <div v-if="form.errors.password_confirmation" class="mt-1 text-xs text-red-600">{{ form.errors.password_confirmation }}</div>
                        </div>

                        <!-- Bouton d'inscription -->
                        <button
                            type="submit"
                            :disabled="form.processing || isLoading"
                            class="w-full py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 disabled:opacity-75 shadow-lg hover:shadow-xl"
                        >
                            {{ isLoading ? 'Inscription...' : 'S\'inscrire' }}
                        </button>

                        <!-- Lien connexion -->
                        <div class="text-center">
                            <p class="text-gray-600 text-sm">
                                Déjà un compte ?
                                <Link :href="route('login')" class="text-blue-600 hover:text-blue-800 font-medium">
                                    Se connecter
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
