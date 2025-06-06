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

const showModal = ref(false);
const actionType = ref('');
const scrollY = ref(0);
const isLoaded = ref(false);
const heroVisible = ref(false);
const cardsVisible = ref(false);
const domainsVisible = ref(false);
const mouseX = ref(0);
const mouseY = ref(0);
const logoUrl = '/images/logoministere.png';

const openModal = (action) => {
    actionType.value = action;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

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
                cardsVisible.value = true;
                setTimeout(() => {
                    domainsVisible.value = true;
                }, 400);
            }, 300);
        }, 200);
    }, 100);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
    window.removeEventListener('mousemove', handleMouseMove);
});

const domains = [
    {
        title: 'Finances & Économie',
        description: 'Finance publique, analyse économique, comptabilité nationale et statistiques.',
        icon: 'M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z',
        color: 'from-blue-500 via-blue-600 to-indigo-600',
        neonColor: 'shadow-blue-500/50',
        delay: '0ms'
    },
    {
        title: 'Informatique & Numérique',
        description: 'Développement logiciel, cybersécurité, gestion de projets IT et transformation digitale.',
        icon: 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
        color: 'from-sky-500 via-blue-500 to-blue-600',
        neonColor: 'shadow-sky-500/50',
        delay: '100ms'
    },
    {
        title: 'Droit & Administration',
        description: 'Droit public, gestion administrative, marchés publics et contentieux de l\'État.',
        icon: 'M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3',
        color: 'from-indigo-500 via-blue-600 to-blue-700',
        neonColor: 'shadow-indigo-500/50',
        delay: '200ms'
    },
    {
        title: 'Ressources Humaines',
        description: 'Gestion des talents, formation des fonctionnaires, administration du personnel.',
        icon: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
        color: 'from-blue-400 via-blue-500 to-sky-600',
        neonColor: 'shadow-blue-500/50',
        delay: '300ms'
    },
    {
        title: 'Communication',
        description: 'Relations publiques, médias numériques, marketing gouvernemental et stratégie digitale.',
        icon: 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
        color: 'from-cyan-500 via-blue-500 to-blue-600',
        neonColor: 'shadow-cyan-500/50',
        delay: '400ms'
    },
    {
        title: 'Santé & Social',
        description: 'Santé publique, assistance sociale, gestion hospitalière et programmes de santé.',
        icon: 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
        color: 'from-blue-600 via-indigo-600 to-blue-700',
        neonColor: 'shadow-blue-500/50',
        delay: '500ms'
    },
    {
        title: 'Ingénierie & Technique',
        description: 'Génie civil, électronique, télécommunications, énergétique et maintenance industrielle.',
        icon: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07',
        color: 'from-slate-500 via-blue-600 to-indigo-600',
        neonColor: 'shadow-slate-500/50',
        delay: '600ms'
    },
    {
        title: 'Environnement',
        description: 'Développement durable, protection environnementale, gestion des ressources naturelles.',
        icon: 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        color: 'from-teal-500 via-blue-500 to-blue-600',
        neonColor: 'shadow-teal-500/50',
        delay: '700ms'
    }
];
</script>

<template>
    <Head title="Bienvenue - Programme de Stages" />

    <!-- Curseur personnalisé avec effet néon bleu -->
    <div class="fixed w-8 h-8 pointer-events-none z-50 mix-blend-difference transition-transform duration-200 ease-out hidden md:hidden"
        :style="`transform: translate(${mouseX - 16}px, ${mouseY - 16}px)`">
        <div class="w-full h-full bg-gradient-to-r from-blue-400 to-blue-600 rounded-full opacity-70 blur-sm animate-pulse"></div>
        <div class="absolute inset-2 bg-white rounded-full"></div>
    </div>

    <!-- Arrière-plan dynamique bleu -->
    <div class="min-h-screen relative overflow-hidden">
        <!-- Gradient de base bleu -->
        <div class="fixed inset-0 bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900"></div>
        <div class="fixed inset-0 bg-gradient-to-tr from-blue-600/20 via-transparent to-indigo-600/20"></div>
        <div class="fixed inset-0 bg-gradient-to-bl from-sky-500/10 via-transparent to-blue-500/10"></div>

        <!-- Formes géométriques animées bleues -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <!-- Mesh gradient animé bleu -->
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-gradient-to-br from-blue-400/25 via-blue-500/15 to-indigo-500/25 rounded-full blur-3xl animate-pulse"
                :style="`transform: translate(${scrollY * -0.5}px, ${scrollY * 0.3}px) rotate(${scrollY * 0.1}deg)`"></div>
            
            <!-- Particules flottantes bleues -->
            <div class="absolute top-1/3 -left-32 w-80 h-80 bg-gradient-to-br from-sky-400/20 via-blue-400/15 to-blue-600/20 rounded-full blur-3xl animate-pulse"
                :style="`transform: translate(${scrollY * 0.3}px, ${scrollY * -0.2}px) rotate(${-scrollY * 0.05}deg)`"></div>
            
            <div class="absolute bottom-1/4 right-1/4 w-64 h-64 bg-gradient-to-br from-indigo-400/25 via-blue-500/15 to-blue-600/25 rounded-full blur-2xl animate-pulse"
                :style="`transform: translate(${scrollY * -0.2}px, ${scrollY * 0.4}px) rotate(${scrollY * 0.08}deg)`"></div>

            <!-- Nouvelles formes géométriques bleues -->
            <div class="absolute top-1/2 left-1/2 w-96 h-96 bg-gradient-to-r from-cyan-400/15 via-blue-500/10 to-indigo-500/15 rounded-full blur-3xl animate-float"
                :style="`transform: translate(-50%, -50%) translate(${mouseX * 0.02}px, ${mouseY * 0.02}px)`"></div>
            
            <!-- Effet de profondeur avec layers bleus -->
            <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-b from-transparent via-blue-500/5 to-indigo-500/10"></div>
            <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-blue-500/5 via-transparent to-sky-500/5"></div>

            <!-- Particules volantes bleues -->
            <div class="absolute top-1/4 left-1/4 w-2 h-2 bg-blue-400 rounded-full animate-ping opacity-60"></div>
            <div class="absolute top-1/3 right-1/3 w-1 h-1 bg-sky-400 rounded-full animate-pulse opacity-80"></div>
            <div class="absolute bottom-1/4 left-1/3 w-3 h-3 bg-indigo-400 rounded-full animate-bounce opacity-70"></div>
            <div class="absolute top-2/3 right-1/4 w-2 h-2 bg-cyan-400 rounded-full animate-ping opacity-50"></div>
            <div class="absolute bottom-1/3 right-2/3 w-1 h-1 bg-blue-300 rounded-full animate-pulse opacity-60"></div>
        </div>

        <!-- Header avec glassmorphism bleu -->
        <header
            class="fixed top-0 w-full z-40 transition-all duration-500 backdrop-blur-2xl bg-blue-900/30 border-b border-blue-400/20 shadow-lg shadow-blue-500/10"
            :class="{ 
                'translate-y-0 opacity-100': isLoaded, 
                'translate-y-[-100%] opacity-0': !isLoaded 
            }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-20">
                    <div class="flex items-center space-x-4">
                        <div class="relative group">
                            <img :src="logoUrl" alt="Logo du Ministère"
                                class="h-16 w-auto transition-all duration-500 group-hover:scale-110 group-hover:drop-shadow-2xl" />
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-500/30 to-indigo-500/30 rounded-lg blur opacity-0 group-hover:opacity-100 transition-opacity duration-300 animate-pulse"></div>
                            <div class="absolute inset-0 border-2 border-blue-400/50 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>
                        <div class="hidden md:block">
                            <h1 class="text-xl font-bold bg-gradient-to-r from-blue-200 via-sky-200 to-blue-300 bg-clip-text text-transparent">
                                Ministère des Finances
                            </h1>
                            <p class="text-sm text-blue-200/80">République du Bénin</p>
                        </div>
                    </div>

                    <nav v-if="canLogin" class="flex items-center space-x-4">
                        <button class="relative px-6 py-3 text-white/90 font-medium overflow-hidden group transition-all duration-300 hover:text-blue-300">
                            <span class="relative z-10">Nous contacter</span>
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-500/20 to-sky-500/20 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left rounded-lg"></div>
                            <div class="absolute inset-0 border border-blue-400/0 group-hover:border-blue-400/50 transition-all duration-300 rounded-lg"></div>
                        </button>

                        <Link :href="route('login')"
                            class="relative px-8 py-3 bg-white/10 backdrop-blur-xl text-white/90 font-medium border border-blue-300/30 rounded-xl overflow-hidden group transition-all duration-300 hover:shadow-lg hover:shadow-blue-500/25 hover:scale-105 hover:border-blue-400/50">
                            <span class="relative z-10">Se connecter</span>
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-500/10 to-sky-500/10 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
                        </Link>

                        <Link v-if="canRegister" :href="route('register')"
                            class="relative px-8 py-3 bg-gradient-to-r from-blue-600 via-blue-500 to-indigo-600 text-white font-medium rounded-xl overflow-hidden group transition-all duration-300 hover:shadow-xl hover:shadow-blue-500/50 hover:scale-105 neon-border-blue">
                            <span class="relative z-10">S'inscrire</span>
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-700 to-indigo-700 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
                            <div class="absolute inset-0 bg-white/20 transform scale-0 group-hover:scale-100 transition-transform duration-500 rounded-xl"></div>
                        </Link>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Hero section avec parallax et effets bleus -->
        <section class="relative min-h-screen flex items-center justify-center pt-20 overflow-hidden">
            <!-- Parallax background elements avec effets bleus -->
            <div class="absolute inset-0 opacity-40" :style="`transform: translateY(${scrollY * 0.5}px)`">
                <div class="absolute top-1/4 left-1/4 w-3 h-3 bg-gradient-to-r from-blue-400 to-sky-500 rounded-full animate-ping"></div>
                <div class="absolute top-1/3 right-1/3 w-2 h-2 bg-gradient-to-r from-indigo-400 to-blue-500 rounded-full animate-pulse"></div>
                <div class="absolute bottom-1/4 left-1/3 w-4 h-4 bg-gradient-to-r from-sky-400 to-blue-500 rounded-full animate-bounce"></div>
                <div class="absolute top-2/3 right-1/5 w-2 h-2 bg-gradient-to-r from-cyan-400 to-blue-500 rounded-full animate-ping"></div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <!-- Contenu principal avec effets glassmorphisme bleu -->
                    <div class="space-y-8 transition-all duration-1000" :class="{ 
                            'opacity-100 translate-y-0': heroVisible, 
                            'opacity-0 translate-y-8': !heroVisible 
                        }">
                        <!-- Badge animé avec bordure néon bleue -->
                        <div class="inline-flex items-center space-x-2 bg-gradient-to-r from-blue-500/20 via-sky-500/20 to-blue-600/20 backdrop-blur-xl px-6 py-3 rounded-full border border-blue-400/30 neon-glow-blue">
                            <div class="w-2 h-2 bg-gradient-to-r from-blue-400 to-sky-500 rounded-full animate-pulse"></div>
                            <span class="text-blue-200 font-semibold text-sm">Session 2025 • Candidatures ouvertes</span>
                        </div>

                        <!-- Titre avec effet de gradient bleu -->
                        <h1 class="text-5xl sm:text-6xl lg:text-7xl font-black leading-tight">
                            <span class="block bg-gradient-to-r from-blue-200 via-sky-200 to-blue-300 bg-clip-text text-transparent animate-gradient">
                                Façonnez
                            </span>
                            <span class="block bg-gradient-to-r from-sky-200 via-blue-200 to-indigo-300 bg-clip-text text-transparent animate-gradient">
                                votre avenir
                            </span>
                            <span class="block text-white/90 text-3xl sm:text-4xl lg:text-5xl font-bold mt-2">
                                au cœur des finances publiques
                            </span>
                        </h1>

                        <!-- Description avec typographie améliorée -->
                        <p class="text-xl text-blue-100/90 leading-relaxed max-w-2xl">
                            Une immersion professionnelle unique dans l'élaboration et la gestion
                            des politiques économiques nationales. Développez des compétences recherchées
                            et construisez un réseau professionnel de valeur.
                        </p>

                        <!-- Citation avec design premium et glassmorphisme bleu -->
                        <blockquote class="relative pl-8 py-6 bg-gradient-to-r from-blue-500/10 via-sky-500/10 to-blue-600/10 backdrop-blur-xl rounded-2xl border border-blue-400/20 neon-glow-blue-soft">
                            <div class="absolute left-3 top-6 w-1 h-16 bg-gradient-to-b from-blue-400 to-indigo-500 rounded-full shadow-lg shadow-blue-500/50"></div>
                            <p class="text-lg text-white/90 italic leading-relaxed">
                                "Investir dans la jeunesse aujourd'hui, c'est préparer l'excellence de demain."
                            </p>
                            <cite class="block mt-3 text-sm text-blue-300 font-semibold">— Vision Ministérielle 2025</cite>
                        </blockquote>

                        <!-- Boutons avec effet ripple et bordures néon bleues -->
                        <div class="flex flex-col sm:flex-row gap-4">
                            <button @click="openModal('demande')"
                                class="group relative px-8 py-4 bg-gradient-to-r from-blue-600 via-blue-500 to-indigo-600 text-white font-semibold rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-2xl hover:shadow-blue-500/50 hover:scale-105 transform neon-border-blue">
                                <span class="relative z-10 flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-3 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                                    </svg>
                                    Faire une demande
                                </span>
                                <div class="absolute inset-0 bg-gradient-to-r from-blue-700 to-indigo-700 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
                                <div class="absolute inset-0 bg-white/20 transform scale-0 group-hover:scale-100 transition-transform duration-500 rounded-2xl"></div>
                            </button>

                            <button @click="openModal('statut')"
                                class="group relative px-8 py-4 bg-white/10 backdrop-blur-xl text-white/90 font-semibold border border-blue-400/30 rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-xl hover:shadow-blue-500/30 hover:scale-105 transform neon-border-blue-soft">
                                <span class="relative z-10 flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-3 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                    Statut de la demande
                                </span>
                                <div class="absolute inset-0 bg-gradient-to-r from-blue-500/20 to-sky-500/20 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
                            </button>
                        </div>
                    </div>

                    <!-- Cartes statistiques avec effet 3D et glassmorphisme bleu -->
                    <div class="grid grid-cols-2 gap-6 transition-all duration-1000 delay-300" :class="{ 
                            'opacity-100 translate-y-0': cardsVisible, 
                            'opacity-0 translate-y-8': !cardsVisible 
                        }">
                        <!-- Carte 1 avec effet néon bleu -->
                        <div class="group relative bg-gradient-to-br from-blue-500/20 via-sky-500/20 to-blue-600/20 backdrop-blur-xl p-8 rounded-3xl border border-blue-400/30 text-white overflow-hidden transition-all duration-500 hover:shadow-2xl hover:shadow-blue-500/50 hover:scale-105 transform cursor-pointer neon-glow-blue">
                            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <div class="relative z-10">
                                <div class="w-16 h-16 bg-gradient-to-br from-blue-400/30 to-sky-500/30 backdrop-blur-sm rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 border border-blue-400/50">
                                    <svg class="w-8 h-8 text-blue-200" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold mb-2 text-blue-100">Excellence</h3>
                                <p class="text-blue-200/80 text-sm">Institution de premier plan</p>
                            </div>
                            <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-gradient-to-r from-blue-500/20 to-sky-500/20 rounded-full blur-xl"></div>
                        </div>

                        <!-- Carte 2 avec glassmorphisme bleu -->
                        <div class="group relative bg-white/10 backdrop-blur-xl p-8 rounded-3xl border border-sky-400/30 overflow-hidden transition-all duration-500 hover:shadow-2xl hover:shadow-sky-500/30 hover:scale-105 transform cursor-pointer neon-glow-blue-soft">
                            <div class="absolute inset-0 bg-gradient-to-br from-sky-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <div class="relative z-10">
                                <div class="text-4xl font-black bg-gradient-to-r from-sky-200 to-blue-300 bg-clip-text text-transparent mb-2">+50</div>
                                <p class="text-sky-200/90 text-sm font-medium">Opportunités de stages</p>
                            </div>
                        </div>

                        <!-- Carte 3 avec effet indigo -->
                        <div class="group relative bg-white/10 backdrop-blur-xl p-8 rounded-3xl border border-indigo-400/30 overflow-hidden transition-all duration-500 hover:shadow-2xl hover:shadow-indigo-500/30 hover:scale-105 transform cursor-pointer neon-glow-blue-soft">
                            <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <div class="relative z-10">
                                <div class="text-4xl font-black bg-gradient-to-r from-indigo-200 to-blue-300 bg-clip-text text-transparent mb-2">8</div>
                                <p class="text-indigo-200/90 text-sm font-medium">Départements spécialisés</p>
                            </div>
                        </div>

                        <!-- Carte 4 avec effet cyan -->
                        <div class="group relative bg-white/10 backdrop-blur-xl p-8 rounded-3xl border border-cyan-400/30 overflow-hidden transition-all duration-500 hover:shadow-2xl hover:shadow-cyan-500/30 hover:scale-105 transform cursor-pointer neon-glow-blue-soft">
                            <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <div class="relative z-10">
                                <div class="text-4xl font-black bg-gradient-to-r from-cyan-200 to-blue-300 bg-clip-text text-transparent mb-2">+500</div>
                                <p class="text-cyan-200/90 text-sm font-medium">Parcours de réussite</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section À propos avec glassmorphisme bleu avancé -->
        <section class="relative py-24 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl sm:text-5xl font-black mb-6">
                        <span class="bg-gradient-to-r from-blue-200 via-sky-200 to-blue-300 bg-clip-text text-transparent animate-gradient">
                            À propos du programme
                        </span>
                    </h2>
                    <div class="w-24 h-1 bg-gradient-to-r from-blue-500 to-sky-500 rounded-full mx-auto shadow-lg shadow-blue-500/50"></div>
                </div>

                <div class="bg-white/10 backdrop-blur-2xl rounded-3xl p-12 border border-blue-400/20 shadow-2xl shadow-blue-500/10 neon-glow-blue-soft">
                    <div class="prose prose-lg prose-blue max-w-none">
                        <p class="text-xl text-white/90 leading-relaxed mb-8">
                            Le programme de stages représente une passerelle exceptionnelle entre vos études et le monde
                            professionnel. Conçu pour les étudiants et jeunes diplômés motivés, ce programme vous immerge dans les
                            rouages économiques et financiers de l'État.
                        </p>
                        <p class="text-xl text-white/90 leading-relaxed mb-8">
                            Nos stages offrent bien plus qu'une simple expérience professionnelle : ils constituent un
                            véritable tremplin pour votre carrière. Vous travaillerez aux côtés d'experts reconnus dans leurs domaines et
                            contribuerez à des projets d'envergure nationale.
                        </p>
                        <p class="text-xl text-white/90 leading-relaxed">
                            Que vous soyez intéressé par l'analyse économique, l'informatique, la gestion des ressources
                            humaines, le droit, la communication ou de nombreux autres domaines, notre programme vous permettra de
                            développer des compétences précieuses tout en servant votre pays.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section domaines avec grille asymétrique et effets bleus -->
        <section class="relative py-24 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h3 class="text-4xl sm:text-5xl font-black mb-6">
                        <span class="bg-gradient-to-r from-sky-200 via-blue-200 to-indigo-300 bg-clip-text text-transparent animate-gradient">
                            Domaines d'excellence
                        </span>
                    </h3>
                    <p class="text-xl text-blue-100/80 max-w-3xl mx-auto">
                        Découvrez nos domaines de spécialisation et trouvez celui qui correspond à vos aspirations
                        professionnelles
                    </p>
                </div>

                <!-- Grille asymétrique avec animations stagger -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 transition-all duration-1000" :class="{ 
                        'opacity-100 translate-y-0': domainsVisible, 
                        'opacity-0 translate-y-12': !domainsVisible 
                    }">
                    <div v-for="(domain, index) in domains" :key="index"
                        class="group relative bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-blue-400/20 shadow-xl overflow-hidden transition-all duration-500 hover:shadow-2xl hover:scale-105 transform cursor-pointer neon-glow-blue-soft"
                        :class="index === 0 ? 'md:col-span-2 lg:col-span-2' : ''"
                        :style="`animation-delay: ${domain.delay}`">
                        <!-- Effet de fond au hover -->
                        <div
                            :class="`absolute inset-0 bg-gradient-to-br ${domain.color} opacity-0 group-hover:opacity-10 transition-opacity duration-300`">
                        </div>

                        <!-- Icône avec animation -->
                        <div
                            :class="`w-16 h-16 bg-gradient-to-br ${domain.color} rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 border border-blue-400/30`">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    :d="domain.icon" />
                            </svg>
                        </div>

                        <!-- Contenu -->
                        <div class="relative z-10">
                            <h4
                                class="text-xl font-bold text-white/90 mb-4 group-hover:text-blue-200 transition-colors duration-300">
                                {{ domain.title }}
                            </h4>
                            <p class="text-blue-100/80 leading-relaxed">
                                {{ domain.description }}
                            </p>
                        </div>

                        <!-- Badge décoratif -->
                        <div
                            class="absolute top-4 right-4 w-3 h-3 bg-blue-400 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300 animate-pulse">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Call to action final avec effet premium bleu -->
        <section class="relative py-24 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-700 via-blue-600 to-indigo-700"></div>
            <div class="absolute inset-0 bg-black/20"></div>

            <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-4xl sm:text-5xl font-black text-white mb-8 leading-tight">
                    Prêt à transformer votre avenir ?
                </h2>
                <p class="text-xl text-blue-100 mb-12 leading-relaxed">
                    Rejoignez-nous dès aujourd'hui et commencez votre parcours vers l'excellence professionnelle
                </p>

                <div class="flex flex-col sm:flex-row gap-6 justify-center">
                    <button @click="openModal('demande')"
                        class="group relative px-12 py-4 bg-white text-blue-700 font-bold rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-2xl hover:scale-105 transform neon-border-blue">
                        <span class="relative z-10">Commencer maintenant</span>
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-blue-50 to-sky-50 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left">
                        </div>
                    </button>

                    <button @click="openModal('statut')"
                        class="group relative px-12 py-4 bg-white/20 backdrop-blur-sm text-white font-bold border border-white/30 rounded-2xl overflow-hidden transition-all duration-300 hover:bg-white/30 hover:scale-105 transform neon-border-blue-soft">
                        <span class="relative z-10">Suivre ma candidature</span>
                    </button>
                </div>
            </div>
        </section>

        <!-- Footer moderne et élégant bleu -->
        <footer class="relative bg-gradient-to-r from-blue-900 via-blue-800 to-indigo-900 text-white overflow-hidden">
            <!-- Éléments décoratifs de fond -->
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 right-1/4 w-80 h-80 bg-indigo-500/10 rounded-full blur-3xl"></div>
            </div>

            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
                    <!-- Section Logo et Description -->
                    <div class="lg:col-span-2">
                        <div class="flex items-center space-x-4 mb-6">
                            <img :src="logoUrl" alt="Logo du Ministère" class="h-16 w-auto" />
                            <div>
                                <h3 class="text-xl font-bold text-white">Ministère des Finances</h3>
                                <p class="text-blue-200 text-sm">République du Bénin</p>
                            </div>
                        </div>
                        <p class="text-blue-100 leading-relaxed max-w-lg">
                            Le Programme de Stages du Ministère des Finances offre aux jeunes talents une opportunité
                            unique de contribuer au développement économique de notre nation tout en développant leurs
                            compétences professionnelles.
                        </p>
                        <div class="flex space-x-4 mt-6">
                            <a href="#"
                                class="w-10 h-10 bg-white/10 backdrop-blur-sm rounded-lg flex items-center justify-center hover:bg-blue-500/20 transition-colors duration-300 border border-blue-400/30">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.14 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                </svg>
                            </a>
                            <a href="#"
                                class="w-10 h-10 bg-white/10 backdrop-blur-sm rounded-lg flex items-center justify-center hover:bg-blue-500/20 transition-colors duration-300 border border-blue-400/30">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19"/>
                                </svg>
                            </a>
                            <a href="#"
                                class="w-10 h-10 bg-white/10 backdrop-blur-sm rounded-lg flex items-center justify-center hover:bg-blue-500/20 transition-colors duration-300 border border-blue-400/30">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Liens rapides -->
                    <div>
                        <h4 class="text-lg font-semibold text-white mb-6">Liens rapides</h4>
                        <ul class="space-y-3">
                            <li><a href="#"
                                    class="text-blue-200 hover:text-white transition-colors duration-300 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                    À propos du programme
                                </a></li>
                            <li><a href="#"
                                    class="text-blue-200 hover:text-white transition-colors duration-300 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                    Domaines de stage
                                </a></li>
                            <li><a href="#"
                                    class="text-blue-200 hover:text-white transition-colors duration-300 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                    Témoignages
                                </a></li>
                            <li><a href="#"
                                    class="text-blue-200 hover:text-white transition-colors duration-300 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                    FAQ
                                </a></li>
                        </ul>
                    </div>

                    <!-- Contact -->
                    <div>
                        <h4 class="text-lg font-semibold text-white mb-6">Contact</h4>
                        <ul class="space-y-3">
                            <li class="text-blue-200 flex items-start">
                                <svg class="w-5 h-5 mr-3 mt-0.5 text-blue-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>Boulevard du 30 Juin<br />Cotonou, Bénin</span>
                            </li>
                            <li class="text-blue-200 flex items-center">
                                <svg class="w-5 h-5 mr-3 text-blue-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                stages@ministere-finances.gov.bj
                            </li>
                            <li class="text-blue-200 flex items-center">
                                <svg class="w-5 h-5 mr-3 text-blue-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                +243 (0) 12 345 6789
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Ligne de séparation -->
                <div class="border-t border-blue-400/20 pt-8">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <div class="text-blue-200 text-sm mb-4 md:mb-0">
                            © 2025 Ministère des Finances - République du Bénin. Tous droits réservés.
                        </div>
                        <div class="flex space-x-6 text-sm">
                            <a href="#" class="text-blue-200 hover:text-white transition-colors duration-300">Politique
                                de confidentialité</a>
                            <a href="#" class="text-blue-200 hover:text-white transition-colors duration-300">Conditions
                                d'utilisation</a>
                            <a href="#" class="text-blue-200 hover:text-white transition-colors duration-300">Mentions
                                légales</a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Modal avec glassmorphism bleu -->
    <Teleport to="body">
        <div v-if="showModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
            @click.self="closeModal">
            <div
                class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 max-w-md w-full border border-blue-400/30 shadow-2xl shadow-blue-500/20 transform transition-all duration-300 scale-100 neon-glow-blue-soft">
                <h3 class="text-2xl font-bold text-white mb-6">
                    {{ actionType === 'demande' ? 'Faire une demande de stage' : 'Consulter le statut' }}
                </h3>

                <p class="text-blue-100/90 mb-8">
                    {{ actionType === 'demande'
                    ? 'Vous devez d\'abord créer un compte ou vous connecter pour faire une demande de stage.'
                    : 'Vous devez vous connecter pour consulter le statut de votre demande.'
                    }}
                </p>

                <div class="flex gap-4">
                    <Link :href="route('login')"
                        class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl text-center transition-all duration-300 hover:shadow-lg hover:shadow-blue-500/30 hover:scale-105 neon-border-blue">
                    Se connecter
                    </Link>
                    <Link v-if="canRegister" :href="route('register')"
                        class="flex-1 px-6 py-3 bg-white/10 text-blue-200 font-semibold border border-blue-400/50 rounded-xl text-center transition-all duration-300 hover:bg-blue-500/10 hover:scale-105">
                    S'inscrire
                    </Link>
                </div>

                <button @click="closeModal"
                    class="absolute top-4 right-4 p-2 text-blue-300/70 hover:text-white transition-colors duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </Teleport>
</template>

<style scoped>
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

@keyframes gradient {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}

.animate-float {
    animation: float 6s ease-in-out infinite;
}

.animate-gradient {
    background-size: 200% 200%;
    animation: gradient 3s ease infinite;
}

/* Effets néon bleus */
.neon-border-blue {
    box-shadow: 0 0 20px rgba(59, 130, 246, 0.5), 0 0 40px rgba(59, 130, 246, 0.3), 0 0 60px rgba(59, 130, 246, 0.1);
}

.neon-border-blue-soft {
    box-shadow: 0 0 15px rgba(59, 130, 246, 0.3), 0 0 30px rgba(59, 130, 246, 0.2);
}

.neon-glow-blue {
    box-shadow: 0 0 30px rgba(59, 130, 246, 0.4), inset 0 0 30px rgba(59, 130, 246, 0.1);
}

.neon-glow-blue-soft {
    box-shadow: 0 0 20px rgba(59, 130, 246, 0.2), inset 0 0 20px rgba(59, 130, 246, 0.05);
}

/* Scroll behavior smooth */
html {
    scroll-behavior: smooth;
}

/* Custom scrollbar bleu */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: rgba(30, 64, 175, 0.1);
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(180deg, #3b82f6, #1e40af);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(180deg, #2563eb, #1e3a8a);
}

/* Ripple effect bleu */
@keyframes ripple {
    to {
        transform: scale(4);
        opacity: 0;
    }
}

.ripple {
    position: relative;
    overflow: hidden;
}

.ripple::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(59, 130, 246, 0.5);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.ripple:active::before {
    width: 300px;
    height: 300px;
}
</style>