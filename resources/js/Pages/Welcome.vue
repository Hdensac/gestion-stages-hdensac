<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

const scrollY = ref(0);
const isLoaded = ref(false);
const heroVisible = ref(false);
const featuresVisible = ref(false);
const statsVisible = ref(false);
const mouseX = ref(0);
const mouseY = ref(0);
const logoUrl = '/images/logoministere.png';

const handleScroll = () => {
    scrollY.value = window.scrollY;
};

const handleMouseMove = (e) => {
    mouseX.value = e.clientX;
    mouseY.value = e.clientY;
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
    window.addEventListener('mousemove', handleMouseMove);

    // Animation sequence with stagger effect
    setTimeout(() => {
        isLoaded.value = true;
        setTimeout(() => {
            heroVisible.value = true;
            setTimeout(() => {
                featuresVisible.value = true;
                setTimeout(() => {
                    statsVisible.value = true;
                }, 400);
            }, 300);
        }, 200);
    }, 100);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
    window.removeEventListener('mousemove', handleMouseMove);
});

const features = [
    {
        title: 'Gestion Simplifiée',
        description: 'Interface intuitive pour gérer facilement vos demandes de stage et suivre leur progression.',
        icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        color: 'from-emerald-500 to-teal-600'
    },
    {
        title: 'Suivi en Temps Réel',
        description: 'Notifications instantanées et tableau de bord pour un suivi optimal de vos stages.',
        icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        color: 'from-blue-500 to-indigo-600'
    },
    {
        title: 'Attestations Automatisées',
        description: 'Génération automatique des attestations avec validation multi-niveaux.',
        icon: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        color: 'from-purple-500 to-pink-600'
    },
    {
        title: 'Collaboration Efficace',
        description: 'Communication fluide entre stagiaires, maîtres de stage et responsables.',
        icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
        color: 'from-orange-500 to-red-600'
    }
];

const stats = [
    { number: '50+', label: 'Stages Gérés', icon: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4' },
    { number: '25+', label: 'Structures Partenaires', icon: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4' },
    { number: '98%', label: 'Taux de Satisfaction', icon: 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z' },
    { number: '24/7', label: 'Support Disponible', icon: 'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 3v6m0 6v6m6-12h-6m-6 0h6' }
];
</script>

<template>
    <Head title="Bienvenue - Gestion des Stages" />

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 relative overflow-hidden">
        <!-- Background Image with Overlay -->
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-20"
             style="background-image: url('/images/bg.png')"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-blue-900/10 via-indigo-900/5 to-slate-900/10"></div>


        <!-- Floating Elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-20 left-10 w-72 h-72 bg-blue-500/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-20 right-10 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-sky-500/10 rounded-full blur-2xl animate-pulse" style="animation-delay: 1s;"></div>
        </div>

        <!-- Header -->
        <header class="relative z-50 bg-white/95 backdrop-blur-sm border-b border-gray-200/50 shadow-sm"
                :class="{
                    'translate-y-0 opacity-100': isLoaded,
                    'translate-y-[-100%] opacity-0': !isLoaded
                }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-20">
                    <div class="flex items-center space-x-4">
                        <div class="relative group">
                            <img :src="logoUrl" alt="Logo du Ministère"
                                class="h-14 w-auto transition-all duration-300 group-hover:scale-105" />
                        </div>
                        <div class="hidden md:block">
                            <h1 class="text-xl font-bold text-gray-900">
                                Ministère de l'Économie et des Finances
                            </h1>
                            <p class="text-sm text-gray-600">République du Bénin</p>
                        </div>
                    </div>

                    <nav v-if="canLogin" class="flex items-center space-x-4">
                        <Link :href="route('login')"
                            class="px-6 py-2.5 text-gray-700 font-medium hover:text-blue-600 transition-colors duration-200">
                            Se connecter
                        </Link>

                        <Link v-if="canRegister" :href="route('register')"
                            class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-medium rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                            S'inscrire
                        </Link>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="relative pt-20 pb-16 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center max-w-4xl mx-auto">
                    <!-- Badge -->
                    <!-- <div class="inline-flex items-center space-x-2 bg-blue-50 px-4 py-2 rounded-full border border-blue-200 mb-8"
                         :class="{
                            'opacity-100 translate-y-0': heroVisible,
                            'opacity-0 translate-y-4': !heroVisible
                         }">
                        <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                        <span class="text-blue-700 font-medium text-sm">Session 2025 • Candidatures ouvertes</span>
                    </div> -->

                    <!-- Main Title -->
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 mb-6 transition-all duration-1000"
                        :class="{
                            'opacity-100 translate-y-0': heroVisible,
                            'opacity-0 translate-y-8': !heroVisible
                        }">
                        <span class="block">Plateforme de Gestion</span>
                        <span class="block bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                            des Stages
                        </span>
                    </h1>

                    <!-- Description -->
                    <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto leading-relaxed transition-all duration-1000 delay-200"
                       :class="{
                            'opacity-100 translate-y-0': heroVisible,
                            'opacity-0 translate-y-8': !heroVisible
                        }">
                        Une solution moderne et intuitive pour gérer efficacement les demandes de stage,
                        le suivi des stagiaires et la génération d'attestations au sein du
                        <strong>Ministère de l'Économie et des Finances</strong>.
                    </p>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center transition-all duration-1000 delay-400"
                         :class="{
                            'opacity-100 translate-y-0': heroVisible,
                            'opacity-0 translate-y-8': !heroVisible
                         }">
                        <Link :href="route('login')"
                            class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            Accéder à la plateforme
                        </Link>

                        <button class="inline-flex items-center px-8 py-4 bg-white text-gray-700 font-semibold rounded-lg border border-gray-300 hover:bg-gray-50 transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            En savoir plus
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-16 bg-white relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4 transition-all duration-1000"
                        :class="{
                            'opacity-100 translate-y-0': featuresVisible,
                            'opacity-0 translate-y-8': !featuresVisible
                        }">
                        Fonctionnalités Principales
                    </h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto transition-all duration-1000 delay-200"
                       :class="{
                            'opacity-100 translate-y-0': featuresVisible,
                            'opacity-0 translate-y-8': !featuresVisible
                        }">
                        Une plateforme complète pour simplifier la gestion des stages
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div v-for="(feature, index) in features" :key="index"
                         class="group relative bg-white rounded-xl p-6 border border-gray-200 shadow-sm hover:shadow-lg transition-all duration-300 hover:scale-105 transform"
                         :class="{
                            'opacity-100 translate-y-0': featuresVisible,
                            'opacity-0 translate-y-8': !featuresVisible
                         }"
                         :style="`animation-delay: ${index * 100}ms`">

                        <!-- Icon -->
                        <div :class="`w-12 h-12 bg-gradient-to-r ${feature.color} rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300`">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="feature.icon" />
                            </svg>
                        </div>

                        <!-- Content -->
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ feature.title }}</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">{{ feature.description }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-16 bg-gradient-to-r from-blue-600 to-indigo-600 relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4 transition-all duration-1000"
                        :class="{
                            'opacity-100 translate-y-0': statsVisible,
                            'opacity-0 translate-y-8': !statsVisible
                        }">
                        Notre Impact en Chiffres
                    </h2>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div v-for="(stat, index) in stats" :key="index"
                         class="text-center transition-all duration-1000"
                         :class="{
                            'opacity-100 translate-y-0': statsVisible,
                            'opacity-0 translate-y-8': !statsVisible
                         }"
                         :style="`animation-delay: ${index * 100}ms`">

                        <!-- Icon -->
                        <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="stat.icon" />
                            </svg>
                        </div>

                        <!-- Number -->
                        <div class="text-3xl sm:text-4xl font-bold text-white mb-2">{{ stat.number }}</div>

                        <!-- Label -->
                        <div class="text-blue-100 text-sm font-medium">{{ stat.label }}</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-4xl mx-auto text-center">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-6">
                        À propos du Programme de Stages
                    </h2>
                    <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                        Le Ministère de l'Économie et des Finances de la République du Bénin offre aux étudiants
                        et jeunes diplômés une opportunité unique d'acquérir une expérience professionnelle
                        enrichissante au cœur de l'administration publique.
                    </p>
                    <p class="text-lg text-gray-600 leading-relaxed">
                        Notre programme de stages vous permet de contribuer activement aux projets stratégiques
                        du ministère tout en développant vos compétences dans un environnement professionnel
                        stimulant et formateur.
                    </p>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Logo et Description -->
                    <div class="md:col-span-2">
                        <div class="flex items-center space-x-4 mb-4">
                            <img :src="logoUrl" alt="Logo du Ministère" class="h-12 w-auto" />
                            <div>
                                <h3 class="text-lg font-semibold">Ministère de l'Économie et des Finances</h3>
                                <p class="text-gray-400 text-sm">République du Bénin</p>
                            </div>
                        </div>
                        <p class="text-gray-300 leading-relaxed max-w-2xl">
                            Plateforme officielle de gestion des stages du Ministère de l'Économie et des Finances.
                            Une solution moderne pour faciliter les démarches des stagiaires et optimiser
                            le suivi administratif.
                        </p>
                    </div>

                    <!-- Contact -->
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Contact</h4>
                        <div class="space-y-2 text-gray-300">
                            <p class="text-sm">368, Avenue Pape Jean Paul II 01BP 302 , COTONOU  </p>
                            <p class="text-sm">Email: secretariatgeneral@finances.bj</p>
                            <p class="text-sm">Tél: 00 229 21 30 10 20</p>
							<p class="text-sm">Site Web : www.finances.bj</p>
                        </div>
                    </div>
                </div>

                <!-- Copyright -->
                <div class="border-t border-gray-800 mt-8 pt-8 text-center">
                    <p class="text-gray-400 text-sm">
                        © {{ new Date().getFullYear() }} Ministère de l'Économie et des Finances - République du Bénin.
                        Tous droits réservés.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
/* Smooth animations */
.transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}

/* Scroll behavior smooth */
html {
    scroll-behavior: smooth;
}
</style>