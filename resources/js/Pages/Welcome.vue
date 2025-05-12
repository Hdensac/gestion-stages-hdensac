<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

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
const fadeIn = ref(false);
const headerVisible = ref(false);
const contentVisible = ref(false);

const openModal = (action) => {
    actionType.value = action;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

const navigateTo = (route) => {
    window.location.href = route;
};

onMounted(() => {
    // Animation séquentielle des éléments
    setTimeout(() => {
        headerVisible.value = true;
        setTimeout(() => {
            contentVisible.value = true;
            setTimeout(() => {
                fadeIn.value = true;
            }, 300);
        }, 300);
    }, 100);
});
</script>

<template>
    <Head title="Bienvenue - Programme de Stages" />
    <div class="min-h-screen w-full bg-gray-50 text-black/50 dark:bg-white dark:text-black/70">
        <div class="flex min-h-screen flex-col">
            <!-- Header avec logo ajusté (plus grand) -->
            <header 
                class="w-full bg-white shadow-md transition-all duration-500"
                :class="{ 'translate-y-0 opacity-100': headerVisible, 'translate-y-[-20px] opacity-0': !headerVisible }"
            >
                <div class="w-full">
                    <div class="flex items-center justify-between py-3 px-4 md:px-6 lg:px-8">
                        <div class="flex items-center">
                            <!-- Logo plus grand sans texte à côté -->
                            <img src="/images/logoministere.png" alt="Logo du Ministère" class="h-20 w-auto transition-transform duration-300 hover:scale-105" />
                        </div>
                        
                        <nav v-if="canLogin" class="flex items-center space-x-4">
                            <!-- Bouton Nous contacter ajouté -->
                            <button 
                                class="rounded-md px-6 py-3 bg-white text-gray-700 ring-1 ring-gray-300 transition-all duration-300 hover:bg-gray-100 hover:scale-105 focus:outline-none focus-visible:ring-[#2962B4]"
                            >
                                Nous contacter
                            </button>
                            
                            <Link
                                :href="route('login')"
                                class="rounded-md px-8 py-3 bg-white text-gray-700 ring-1 ring-gray-300 transition-all duration-300 hover:bg-gray-100 hover:scale-105 focus:outline-none focus-visible:ring-[#2962B4]"
                            >
                                Se connecter
                            </Link>

                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="rounded-md px-8 py-3 bg-[#2962B4] text-white ring-1 ring-[#2962B4] transition-all duration-300 hover:bg-[#1E4B8F] hover:scale-105 focus:outline-none focus-visible:ring-[#2962B4]"
                            >
                                S'inscrire
                            </Link>
                        </nav>
                    </div>
                </div>
            </header>

            <!-- Contenu principal avec animations -->
            <main 
                class="flex-grow transition-all duration-500"
                :class="{ 'opacity-100 translate-y-0': contentVisible, 'opacity-0 translate-y-[20px]': !contentVisible }"
            >
                <!-- Hero section avec design circulaire et texte ajusté -->
                <section class="bg-white py-16 md:py-20 relative overflow-hidden">
                    <!-- Éléments décoratifs de fond améliorés -->
                    <div class="absolute inset-0 overflow-hidden">
                        <div class="absolute top-0 right-1/2 transform translate-x-1/2 w-full h-96 bg-[#2962B4]/5 rounded-full opacity-70 blur-3xl"></div>
                        <div class="absolute bottom-0 left-1/4 w-[30rem] h-[30rem] bg-[#2962B4]/10 rounded-full opacity-40 blur-3xl"></div>
                        <div class="absolute top-1/3 right-1/4 w-64 h-64 bg-[#2962B4]/5 rounded-full opacity-60 blur-xl"></div>
                    </div>
                    
                    <div class="w-full mx-auto px-4 md:px-6 lg:px-8 relative z-10">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                            <!-- Section de gauche avec les boutons et texte ajusté -->
                            <div class="lg:col-span-6 transition-all duration-500" :class="{ 'opacity-100 translate-x-0': fadeIn, 'opacity-0 translate-x-[-20px]': !fadeIn }">
                                <!-- Badge Session 2025 -->
                                <div class="inline-block bg-[#2962B4]/10 text-[#2962B4] px-4 py-1 rounded-full text-sm font-semibold mb-6">
                                    Session 2025
                                    <span class="inline-block w-2 h-2 bg-[#2962B4] rounded-full ml-2 animate-pulse"></span>
                                </div>
                                
                                <!-- Titre principal ajusté et plus haut -->
                                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 mb-5 leading-tight">
                                    <span class="relative">
                                        Façonnez 
                                        <span class="absolute -bottom-1 left-0 w-full h-3 bg-[#2962B4]/10"></span>
                                    </span>
                                    <br class="hidden md:block" />
                                    votre 
                                    <span class="text-[#2962B4]">avenir</span> 
                                    au cœur des finances publiques
                                </h1>
                                
                                <p class="text-lg text-gray-600 mb-5 max-w-lg">
                                    Le Programme de Stages vous offre une immersion professionnelle unique 
                                    dans l'élaboration et la gestion des politiques économiques nationales. 
                                    Développez des compétences recherchées et construisez un réseau professionnel de valeur.
                                </p>
                                
                                <blockquote class="pl-4 border-l-4 border-[#2962B4] italic text-gray-600 mb-8">
                                    "Investir dans la jeunesse aujourd'hui, c'est préparer l'excellence de demain."
                                </blockquote>
                                
                                <!-- Boutons avec la logique demandée et design amélioré -->
                                <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
                                    <button 
                                        @click="openModal('demande')" 
                                        class="w-full sm:w-auto rounded-md px-8 py-4 bg-[#2962B4] text-white font-medium hover:bg-[#1E4B8F] hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-[#2962B4] transition-all duration-300 transform hover:scale-105 shadow-md"
                                    >
                                        <span class="flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                                            </svg>
                                            Faire une demande
                                        </span>
                                    </button>
                                    
                                    <button 
                                        @click="openModal('statut')" 
                                        class="w-full sm:w-auto rounded-md px-8 py-4 bg-white text-gray-700 font-medium border border-gray-300 hover:bg-gray-100 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-[#2962B4] transition-all duration-300 transform hover:scale-105 shadow-sm"
                                    >
                                        <span class="flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                            </svg>
                                            Statut de la demande
                                        </span>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Section de droite avec design circulaire amélioré -->
                            <div class="lg:col-span-6 transition-all duration-500" :class="{ 'opacity-100 translate-x-0': fadeIn, 'opacity-0 translate-x-[20px]': !fadeIn }">
                                <div class="relative">
                                    <!-- Design organique avec formes circulaires interconnectées améliorées -->
                                    <div class="grid grid-cols-2 gap-6">
                                        <!-- Premier cercle -->
                                        <div class="aspect-square rounded-full bg-gradient-to-br from-[#2962B4] to-[#1E4B8F] p-6 flex flex-col items-center justify-center text-white shadow-lg transform transition-transform duration-500 hover:scale-105 hover:shadow-xl">
                                            <div class="mb-3 flex justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                                                </svg>
                                            </div>
                                            <h2 class="text-xl font-bold text-center">
                                                Investissez dans votre avenir
                                            </h2>
                                            <p class="text-sm text-center text-white/90 mt-2">
                                                Rejoignez une institution de premier plan
                                            </p>
                                        </div>
                                        
                                        <!-- Deuxième cercle (opportunities) -->
                                        <div class="aspect-square rounded-full bg-white p-6 flex flex-col items-center justify-center shadow-lg border-2 border-[#2962B4]/10 transform transition-transform duration-500 hover:scale-105 hover:border-[#2962B4]/30 hover:shadow-xl">
                                            <div class="text-3xl font-bold text-[#2962B4] mb-1">+50</div>
                                            <div class="text-gray-600 text-center text-sm">Opportunités de stages</div>
                                        </div>
                                        
                                        <!-- Troisième cercle (departments) -->
                                        <div class="aspect-square rounded-full bg-white p-6 flex flex-col items-center justify-center shadow-lg border-2 border-[#2962B4]/10 transform transition-transform duration-500 hover:scale-105 hover:border-[#2962B4]/30 hover:shadow-xl">
                                            <div class="text-3xl font-bold text-[#2962B4] mb-1">8</div>
                                            <div class="text-gray-600 text-center text-sm">Départements spécialisés</div>
                                        </div>
                                        
                                        <!-- Quatrième cercle (success) -->
                                        <div class="aspect-square rounded-full bg-white p-6 flex flex-col items-center justify-center shadow-lg border-2 border-[#2962B4]/10 transform transition-transform duration-500 hover:scale-105 hover:border-[#2962B4]/30 hover:shadow-xl">
                                            <div class="text-3xl font-bold text-yellow-400 mb-1">+500</div>
                                            <div class="text-gray-600 text-center text-sm">Parcours de réussite</div>
                                        </div>
                                    </div>
                                    
                                    <!-- Lignes de connexion améliorées -->
                                    <div class="absolute top-1/2 left-1/2 w-0.5 h-[calc(100%-2rem)] bg-gradient-to-b from-[#2962B4]/5 via-[#2962B4]/40 to-[#2962B4]/5 transform -translate-x-1/2 -translate-y-1/2"></div>
                                    <div class="absolute top-1/2 left-0 w-full h-0.5 bg-gradient-to-r from-[#2962B4]/5 via-[#2962B4]/40 to-[#2962B4]/5 transform -translate-y-1/2"></div>
                                    
                                    <!-- Badge décoratif amélioré -->
                                    <div class="absolute top-0 right-0 -mt-3 -mr-3 bg-[#2962B4] text-white px-4 py-1 rounded-full text-sm font-bold shadow-lg transform rotate-3">
                                        Session 2025
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                
                <!-- Section à propos optimisée et plus jolie -->
                <section class="py-16 bg-gray-50">
                    <div class="w-full mx-auto px-4 md:px-6 lg:px-8">
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 border-b-2 border-[#2962B4] pb-2 inline-block mb-8">
                            À propos du programme de stages
                        </h2>
                        
                        <div class="mt-6 bg-white p-7 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl border border-gray-100">
                            <p class="text-lg text-gray-700 mb-4 leading-relaxed">
                                Le programme de stages représente une passerelle exceptionnelle entre vos études et le monde professionnel. Conçu pour les étudiants et jeunes diplômés motivés, ce programme vous immerge dans les rouages économiques et financiers de l'État.
                            </p>
                            <p class="text-lg text-gray-700 mb-4 leading-relaxed">
                                Nos stages offrent bien plus qu'une simple expérience professionnelle : ils constituent un véritable tremplin pour votre carrière. Vous travaillerez aux côtés d'experts reconnus dans leurs domaines et contribuerez à des projets d'envergure nationale.
                            </p>
                            <p class="text-lg text-gray-700 leading-relaxed">
                                Que vous soyez intéressé par l'analyse économique, l'informatique, la gestion des ressources humaines, le droit, la communication ou de nombreux autres domaines, notre programme vous permettra de développer des compétences précieuses tout en servant votre pays.
                            </p>
                        </div>
                        
                        <!-- Nos domaines de stage diversifiés avec design amélioré -->
                        <div class="mt-12">
                            <h3 class="text-xl md:text-2xl font-semibold text-gray-800 text-center mb-10">
                                Découvrez nos domaines de stages diversifiés
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                <!-- Finances & Économie -->
                                <div class="bg-white p-5 rounded-xl shadow-md transition-all duration-300 hover:shadow-xl hover:-translate-y-1 border-t-4 border-[#2962B4]">
                                    <div class="text-[#2962B4] text-2xl mb-3 bg-[#2962B4]/10 p-3 rounded-lg inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                    </div>
                                    <h3 class="font-semibold text-lg mb-2 text-gray-800">Finances & Économie</h3>
                                    <p class="text-gray-600">Finance publique, analyse économique, comptabilité nationale et statistiques.</p>
                                </div>
                                
                                <!-- Informatique & Numérique -->
                                <div class="bg-white p-5 rounded-xl shadow-md transition-all duration-300 hover:shadow-xl hover:-translate-y-1 border-t-4 border-[#2962B4]">
                                    <div class="text-[#2962B4] text-2xl mb-3 bg-[#2962B4]/10 p-3 rounded-lg inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <h3 class="font-semibold text-lg mb-2 text-gray-800">Informatique & Numérique</h3>
                                    <p class="text-gray-600">Développement logiciel, cybersécurité, gestion de projets IT et transformation digitale.</p>
                                </div>
                                
                                <!-- Droit & Administration -->
                                <div class="bg-white p-5 rounded-xl shadow-md transition-all duration-300 hover:shadow-xl hover:-translate-y-1 border-t-4 border-[#2962B4]">
                                    <div class="text-[#2962B4] text-2xl mb-3 bg-[#2962B4]/10 p-3 rounded-lg inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                                        </svg>
                                    </div>
                                    <h3 class="font-semibold text-lg mb-2 text-gray-800">Droit & Administration</h3>
                                    <p class="text-gray-600">Droit public, gestion administrative, marchés publics et contentieux de l'État.</p>
                                </div>
                                
                                <!-- Ressources Humaines -->
                                <div class="bg-white p-5 rounded-xl shadow-md transition-all duration-300 hover:shadow-xl hover:-translate-y-1 border-t-4 border-[#2962B4]">
                                    <div class="text-[#2962B4] text-2xl mb-3 bg-[#2962B4]/10 p-3 rounded-lg inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <h3 class="font-semibold text-lg mb-2 text-gray-800">Ressources Humaines</h3>
                                    <p class="text-gray-600">Gestion des talents, formation des fonctionnaires, administration du personnel et psychologie du travail.</p>
                                </div>
                            </div>

                            <!-- Deuxième rangée de domaines -->
                            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                <!-- Communication & Relations Publiques -->
                                <div class="bg-white p-5 rounded-xl shadow-md transition-all duration-300 hover:shadow-xl hover:-translate-y-1 border-t-4 border-[#2962B4]">
                                    <div class="text-[#2962B4] text-2xl mb-3 bg-[#2962B4]/10 p-3 rounded-lg inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>
                                    </div>
                                    <h3 class="font-semibold text-lg mb-2 text-gray-800">Communication</h3>
                                    <p class="text-gray-600">Relations publiques, médias numériques, marketing gouvernemental et stratégie digitale.</p>
                                </div>
                                
                                <!-- Santé & Social -->
                                <div class="bg-white p-5 rounded-xl shadow-md transition-all duration-300 hover:shadow-xl hover:-translate-y-1 border-t-4 border-[#2962B4]">
                                    <div class="text-[#2962B4] text-2xl mb-3 bg-[#2962B4]/10 p-3 rounded-lg inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="font-semibold text-lg mb-2 text-gray-800">Santé & Social</h3>
                                    <p class="text-gray-600">Santé publique, assistance sociale, gestion hospitalière et programmes de santé.</p>
                                </div>
                                
                                <!-- Ingénierie & Technique -->
                                <div class="bg-white p-5 rounded-xl shadow-md transition-all duration-300 hover:shadow-xl hover:-translate-y-1 border-t-4 border-[#2962B4]">
                                    <div class="text-[#2962B4] text-2xl mb-3 bg-[#2962B4]/10 p-3 rounded-lg inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="font-semibold text-lg mb-2 text-gray-800">Ingénierie & Technique</h3>
                                    <p class="text-gray-600">Génie civil, électronique, télécommunications, énergétique et maintenance industrielle.</p>
                                </div>
                                
                                <!-- Environnement & Développement durable -->
                                <div class="bg-white p-5 rounded-xl shadow-md transition-all duration-300 hover:shadow-xl hover:-translate-y-1 border-t-4 border-[#2962B4]">
                                    <div class="text-[#2962B4] text-2xl mb-3 bg-[#2962B4]/10 p-3 rounded-lg inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="font-semibold text-lg mb-2 text-gray-800">Environnement</h3>
                                    <p class="text-gray-600">Développement durable, protection environnementale, gestion des ressources naturelles.</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Section CTA -->
                        <div class="mt-16 bg-gradient-to-r from-[#2962B4] to-[#1E4B8F] rounded-xl p-8 text-white shadow-lg relative overflow-hidden">
                            <div class="absolute top-0 left-0 w-full h-full opacity-10">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-64 w-64 transform -rotate-12 absolute -top-20 -left-20">
                                    <path d="M21.721 12.752a9.711 9.711 0 00-.945-5.003 12.754 12.754 0 01-4.339 2.708 18.991 18.991 0 01-.214 4.772 17.165 17.165 0 005.498-2.477zM14.634 15.55a17.324 17.324 0 00.332-4.647c-.952.227-1.945.347-2.966.347-1.021 0-2.014-.12-2.966-.347a17.515 17.515 0 00.332 4.647 17.385 17.385 0 005.268 0zM9.366 15.55a17.524 17.524 0 01-.332-4.647c.952.227 1.945.347 2.966.347 1.021 0 2.014-.12 2.966-.347a17.515 17.515 0 01-.332 4.647 17.385 17.385 0 01-5.268 0zM2.279 7.752a9.711 9.711 0 01.945-5.003 12.754 12.754 0 014.339 2.708 18.991 18.991 0 01.214 4.772A17.165 17.165 0 012.279 7.752zM21.133 3.2a9.65 9.65 0 00-1.388-.933A12.88 12.88 0 0112 0C8.654 0 5.606 1.176 3.255 3.2c.493.307.985.609 1.482.903.012-.018.024-.036.037-.054l.013-.016A10.876 10.876 0 0112 2a10.876 10.876 0 017.213 2.033c.425.262.839.542 1.241.833.028.042.054.083.08.125zm-18.866 0a12.88 12.88 0 017.745-3.2A9.65 9.65 0 0011.4 2.267a10.876 10.876 0 00-7.213 2.033 13.079 13.079 0 00-1.92 1.8zm7.5 16.667c0 .491-.051.971-.145 1.438l-.012.031a9.722 9.722 0 01-5.5 2.245 9.711 9.711 0 01-5.344-1.614 16.611 16.611 0 0011.001-2.1zM10.232 7.753a18.991 18.991 0 00-.214-4.772A12.881 12.881 0 0123.766 7.69a16.611 16.611 0 01-13.534.063z" />
                                </svg>
                            </div>
                            <div class="relative z-10 max-w-xl">
                                <h2 class="text-2xl md:text-3xl font-bold mb-4">Prêt à faire le premier pas vers votre avenir professionnel ?</h2>
                                <p class="text-white/90 mb-6">
                                    Soumettez votre candidature dès aujourd'hui et rejoignez une institution qui valorise l'excellence, l'innovation et le service à la nation.
                                </p>
                                <button 
                                    @click="openModal('demande')" 
                                    class="px-8 py-3 bg-white text-[#2962B4] font-medium rounded-md shadow hover:shadow-lg transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-white"
                                >
                                    Postuler maintenant
                                </button>
                            </div>
                        </div>
                    </div>
                </section>
                
                <!-- Footer -->
                <footer class="bg-gray-900 text-white py-12">
                    <div class="w-full mx-auto px-4 md:px-6 lg:px-8">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                            <div class="md:col-span-1">
                                <img src="/images/logoministere.png" alt="Logo du Ministère" class="h-14 w-auto mb-4 brightness-0 invert" />
                                <p class="text-gray-400 text-sm">
                                    Le Ministère de l'Économie et des Finances s'engage pour l'avenir des jeunes talents.
                                </p>
                            </div>
                            
                            <div class="md:col-span-3 grid grid-cols-1 sm:grid-cols-3 gap-8">
                                <div>
                                    <h3 class="font-semibold text-lg mb-4 border-b border-[#2962B4] pb-2">Programme de Stages</h3>
                                    <ul class="space-y-2">
                                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">Conditions d'éligibilité</a></li>
                                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">Processus de sélection</a></li>
                                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">Questions fréquentes</a></li>
                                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">Témoignages</a></li>
                                    </ul>
                                </div>
                                
                                <div>
                                    <h3 class="font-semibold text-lg mb-4 border-b border-[#2962B4] pb-2">Contact</h3>
                                    <ul class="space-y-2">
                                        <li class="flex items-start">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span class="text-gray-400">Route de l'Aéroport, Cotonou, Bénin</span>
                                        </li>
                                        <li class="flex items-start">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            <span class="text-gray-400">contact@finances.bj</span>
                                        </li>
                                        <li class="flex items-start">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            <span class="text-gray-400">+229 21 30 10 20</span>
                                        </li>
                                    </ul>
                                </div>
                                
                                <div>
                                    <h3 class="font-semibold text-lg mb-4 border-b border-[#2962B4] pb-2">Réseaux sociaux</h3>
                                    <div class="flex space-x-4">
                                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z" />
                                            </svg>
                                        </a>
                                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm6.066 9.645c.183 4.04-2.83 8.544-8.164 8.544-1.622 0-3.131-.476-4.402-1.291 1.524.18 3.045-.244 4.252-1.189-1.256-.023-2.317-.854-2.684-1.995.451.086.895.061 1.298-.049-1.381-.278-2.335-1.522-2.304-2.853.388.215.83.344 1.301.359-1.279-.855-1.641-2.544-.889-3.835 1.416 1.738 3.533 2.881 5.92 3.001-.419-1.796.944-3.527 2.799-3.527.825 0 1.572.349 2.096.907.654-.128 1.27-.368 1.824-.697-.215.671-.67 1.233-1.263 1.589.581-.07 1.135-.224 1.649-.453-.384.578-.87 1.084-1.433 1.489z" />
                                            </svg>
                                        </a>
                                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm-2 16h-2v-6h2v6zm-1-6.891c-.607 0-1.1-.496-1.1-1.109 0-.612.492-1.109 1.1-1.109s1.1.497 1.1 1.109c0 .613-.493 1.109-1.1 1.109zm8 6.891h-1.998v-2.861c0-1.881-2.002-1.722-2.002 0v2.861h-2v-6h2v1.093c.872-1.616 4-1.736 4 1.548v3.359z" />
                                            </svg>
                                        </a>
                                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm4.441 16.892c-2.102.144-6.784.144-8.883 0-2.276-.156-2.541-1.27-2.558-4.892.017-3.629.285-4.736 2.558-4.892 2.099-.144 6.782-.144 8.883 0 2.277.156 2.541 1.27 2.559 4.892-.018 3.629-.285 4.736-2.559 4.892zm-6.441-7.234l4.917 2.338-4.917 2.346v-4.684z" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-8 pt-8 border-t border-gray-800 text-center text-gray-400 text-sm">
                            <p>&copy; {{ new Date().getFullYear() }} Ministère de l'Économie et des Finances - République du Bénin. Tous droits réservés.</p>
                        </div>
                    </div>
                </footer>
            </main>
        </div>

        <!-- Modal pour "Faire une demande" et "Statut de la demande" avec options de connexion/inscription -->
        <div v-if="showModal" class="fixed inset-0 flex items-center justify-center z-50">
            <div class="absolute inset-0 bg-black/50" @click="closeModal"></div>
            <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full mx-4 overflow-hidden">
                <div class="bg-[#2962B4] py-4 px-6">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-semibold text-white">
                            {{ actionType === 'demande' ? 'Faire une demande de stage' : 'Vérifier le statut de votre demande' }}
                        </h3>
                        <button @click="closeModal" class="text-white hover:text-gray-200 focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <p class="text-gray-600 mb-6">
                        {{ actionType === 'demande' ? 
                            'Pour soumettre votre demande de stage, veuillez vous connecter à votre compte ou créer un nouveau compte.' : 
                            'Pour vérifier le statut de votre demande, veuillez vous connecter à votre compte.' }}
                    </p>
                    
                    <div class="flex flex-col space-y-4">
                        <Link
                            :href="route('login')"
                            class="w-full bg-[#2962B4] text-white py-3 px-4 rounded-md text-center hover:bg-[#1E4B8F] transition-colors duration-300 font-medium"
                        >
                            Se connecter
                        </Link>
                        
                        <Link
                            v-if="canRegister && actionType === 'demande'"
                            :href="route('register')"
                            class="w-full bg-white text-gray-700 border border-gray-300 py-3 px-4 rounded-md text-center hover:bg-gray-100 transition-colors duration-300 font-medium"
                        >
                            Créer un compte
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>