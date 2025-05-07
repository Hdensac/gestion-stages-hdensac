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
            <!-- Header avec logo ajusté -->
            <header 
                class="w-full bg-white shadow-md transition-all duration-500"
                :class="{ 'translate-y-0 opacity-100': headerVisible, 'translate-y-[-20px] opacity-0': !headerVisible }"
            >
                <div class="w-full">
                    <div class="flex items-center justify-between py-3 px-4 md:px-6 lg:px-8">
                        <div class="flex items-center">
                            <img src="/images/logoministere.png" alt="Logo du Ministère" class="h-12 w-auto transition-transform duration-300 hover:scale-105" />
                        </div>
                        
                        <nav v-if="canLogin" class="flex items-center">
                            <Link
                                :href="route('login')"
                                class="rounded-md px-5 py-2 bg-white text-gray-700 ring-1 ring-gray-300 transition-all duration-300 hover:bg-gray-100 hover:scale-105 focus:outline-none focus-visible:ring-[#054A31] mr-2"
                            >
                                Se connecter
                            </Link>

                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="rounded-md px-5 py-2 bg-[#054A31] text-white ring-1 ring-[#054A31] transition-all duration-300 hover:bg-[#043824] hover:scale-105 focus:outline-none focus-visible:ring-[#054A31]"
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
                <!-- Hero section optimisée -->
                <section class="bg-white py-12 md:py-16">
                    <div class="w-full mx-auto px-4 md:px-6 lg:px-8">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-center">
                            <div class="transition-all duration-500" :class="{ 'opacity-100 translate-x-0': fadeIn, 'opacity-0 translate-x-[-20px]': !fadeIn }">
                                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                                    Façonnez votre avenir au cœur des finances publiques
                                </h1>
                                
                                <p class="text-lg text-gray-600 mb-4">
                                    Le Programme de Stages vous offre une immersion professionnelle unique 
                                    dans l'élaboration et la gestion des politiques économiques nationales. 
                                    Développez des compétences recherchées et construisez un réseau professionnel de valeur.
                                </p>
                                
                                <p class="text-md text-gray-600 italic mb-6">
                                    "Investir dans la jeunesse aujourd'hui, c'est préparer l'excellence de demain."
                                </p>
                                
                                <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
                                    <button 
                                        @click="openModal('demande')" 
                                        class="w-full sm:w-auto rounded-md px-8 py-3 bg-[#054A31] text-white font-medium hover:bg-[#043824] hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-[#054A31] transition-all duration-300 transform hover:scale-105"
                                    >
                                        Faire une demande
                                    </button>
                                    
                                    <button 
                                        @click="openModal('statut')" 
                                        class="w-full sm:w-auto rounded-md px-8 py-3 bg-white text-gray-700 font-medium border border-gray-300 hover:bg-gray-100 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-[#054A31] transition-all duration-300 transform hover:scale-105"
                                    >
                                        Statut de la demande
                                    </button>
                                </div>
                            </div>
                            
                            <div class="bg-[#054A31] text-white rounded-lg p-6 shadow-xl transition-all duration-500 transform hover:scale-102"
                                :class="{ 'opacity-100 translate-x-0': fadeIn, 'opacity-0 translate-x-[20px]': !fadeIn }">
                                <div class="flex justify-center mb-4">
                                    <svg class="w-14 h-14 md:w-16 md:h-16 animate-pulse" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" />
                                    </svg>
                                </div>
                                
                                <h2 class="text-2xl md:text-3xl font-bold text-center mb-4">
                                    Investissez dans votre avenir professionnel
                                </h2>
                                
                                <p class="text-center mb-6 text-lg">
                                    Rejoignez une institution gouvernementale de premier plan et participez à la 
                                    construction d'un Bénin économiquement prospère
                                </p>
                                
                                <div class="grid grid-cols-3 gap-3 text-center">
                                    <div class="p-2 transition-transform hover:scale-110 duration-300">
                                        <div class="text-xl md:text-2xl font-bold text-yellow-400">+50</div>
                                        <div class="text-sm md:text-base">Opportunités de stages</div>
                                    </div>
                                    <div class="p-2 transition-transform hover:scale-110 duration-300">
                                        <div class="text-xl md:text-2xl font-bold">8</div>
                                        <div class="text-sm md:text-base">Départements spécialisés</div>
                                    </div>
                                    <div class="p-2 transition-transform hover:scale-110 duration-300">
                                        <div class="text-xl md:text-2xl font-bold text-yellow-400">+500</div>
                                        <div class="text-sm md:text-base">Parcours de réussite</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                
                <!-- Section à propos optimisée -->
                <section class="py-12 bg-gray-50">
                    <div class="w-full mx-auto px-4 md:px-6 lg:px-8">
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 border-b-2 border-[#054A31] pb-2 inline-block mb-6">
                            À propos du programme de stages
                        </h2>
                        
                        <div class="mt-4 bg-white p-5 md:p-6 rounded-lg shadow-lg transition-all duration-300 hover:shadow-xl">
                            <p class="text-lg text-gray-700 mb-3">
                                Le programme de stages représente une passerelle exceptionnelle entre vos études et le monde professionnel. Conçu pour les étudiants et jeunes diplômés motivés, ce programme vous immerge dans les rouages économiques et financiers de l'État.
                            </p>
                            <p class="text-lg text-gray-700 mb-3">
                                Nos stages offrent bien plus qu'une simple expérience professionnelle : ils constituent un véritable tremplin pour votre carrière. Vous travaillerez aux côtés d'experts reconnus dans leurs domaines et contribuerez à des projets d'envergure nationale.
                            </p>
                            <p class="text-lg text-gray-700">
                                Que vous soyez intéressé par l'analyse économique, la gestion budgétaire, les finances publiques, la comptabilité nationale ou d'autres domaines spécialisés, notre programme vous permettra de développer des compétences précieuses tout en servant votre pays.
                            </p>
                        </div>
                        
                        <!-- Nos domaines de stage -->
                        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="bg-white p-4 rounded-lg shadow-md transition-transform duration-300 hover:shadow-lg hover:scale-105">
                                <div class="text-[#054A31] text-2xl mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h3 class="font-semibold text-lg mb-1">Analyses économiques</h3>
                                <p class="text-gray-600 text-sm">Participez à l'élaboration des études d'impact et analyses sectorielles.</p>
                            </div>
                            
                            <div class="bg-white p-4 rounded-lg shadow-md transition-transform duration-300 hover:shadow-lg hover:scale-105">
                                <div class="text-[#054A31] text-2xl mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="font-semibold text-lg mb-1">Finances publiques</h3>
                                <p class="text-gray-600 text-sm">Découvrez les mécanismes de gestion des ressources financières de l'État.</p>
                            </div>
                            
                            <div class="bg-white p-4 rounded-lg shadow-md transition-transform duration-300 hover:shadow-lg hover:scale-105">
                                <div class="text-[#054A31] text-2xl mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <h3 class="font-semibold text-lg mb-1">Statistiques nationales</h3>
                                <p class="text-gray-600 text-sm">Contribuez à la collecte et l'analyse des données économiques du pays.</p>
                            </div>
                            
                            <div class="bg-white p-4 rounded-lg shadow-md transition-transform duration-300 hover:shadow-lg hover:scale-105">
                                <div class="text-[#054A31] text-2xl mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h3 class="font-semibold text-lg mb-1">Administration fiscale</h3>
                                <p class="text-gray-600 text-sm">Explorez les politiques fiscales et leur mise en œuvre au service du développement.</p>
                            </div>
                        </div>
                    </div>
                </section>
            </main>

            <footer 
                class="w-full bg-white border-t border-gray-200 py-6 transition-all duration-700"
                :class="{ 'opacity-100': fadeIn, 'opacity-0': !fadeIn }"
            >
                <div class="w-full px-4 md:px-6 lg:px-8 mx-auto">
                    <div class="flex flex-col md:flex-row justify-between items-center mb-4">
                        <div class="mb-3 md:mb-0">
                            <h3 class="font-semibold text-gray-800 mb-1">Programme de stages</h3>
                            <p class="text-sm text-gray-600">Édifice national</p>
                        </div>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-600 hover:text-[#054A31] transition-colors duration-300">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-gray-600 hover:text-[#054A31] transition-colors duration-300">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723 10.054 10.054 0 01-3.127 1.184 4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-gray-600 hover:text-[#054A31] transition-colors duration-300">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm4.441 16.892c-2.102.144-6.784.144-8.883 0C5.282 16.736 5.017 15.622 5 12c.017-3.629.285-4.736 2.558-4.892 2.099-.144 6.782-.144 8.883 0C18.718 7.264 18.982 8.378 19 12c-.018 3.629-.285 4.736-2.559 4.892zM10 9.658l4.917 2.338L10 14.342V9.658z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <p class="text-center text-sm text-gray-600">
                        &copy; {{ new Date().getFullYear() }} - Tous droits réservés
                    </p>
                </div>
            </footer>
        </div>
    </div>

    <!-- Modal optimisée -->
    <div v-if="showModal" 
        class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm flex items-center justify-center z-50 transition-opacity duration-300" 
        @click="closeModal"
    >
        <div 
            class="bg-white rounded-lg shadow-2xl max-w-md w-full mx-2 p-5 transform transition-all duration-300 scale-100" 
            @click.stop
        >
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-900">
                    {{ actionType === 'demande' ? 'Faire une demande de stage' : 'Vérifier le statut de votre demande' }}
                </h3>
                <button @click="closeModal" class="text-gray-400 hover:text-gray-600 focus:outline-none transition-transform duration-300 hover:scale-110">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="mb-5">
                <p class="text-gray-700 mb-2">
                    {{ actionType === 'demande' 
                        ? 'Pour soumettre votre candidature au programme de stages, vous devez disposer d\'un compte sur notre plateforme.' 
                        : 'Pour consulter l\'avancement de votre candidature au programme de stages, veuillez vous connecter à votre espace personnel.' }}
                </p>
                <p class="text-gray-600 text-sm italic">
                    {{ actionType === 'demande' 
                        ? 'Créez un compte en quelques minutes pour accéder à toutes les opportunités de stage.' 
                        : 'Suivez l\'évolution de votre dossier en temps réel.' }}
                </p>
            </div>
            
            <div class="flex flex-col space-y-3">
                <button 
                    @click="navigateTo(route('login'))" 
                    class="w-full rounded-md px-5 py-2.5 bg-[#054A31] text-white font-medium hover:bg-[#043824] focus:outline-none focus:ring-2 focus:ring-[#054A31] transition-all duration-300 transform hover:scale-105"
                >
                    J'ai déjà un compte
                </button>
                
                <button 
                    v-if="canRegister"
                    @click="navigateTo(route('register'))" 
                    class="w-full rounded-md px-5 py-2.5 bg-white text-gray-700 font-medium border border-gray-300 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-[#054A31] transition-all duration-300 transform hover:scale-105"
                >
                    Créer un compte
                </button>
            </div>
        </div>
    </div>
</template>

<style>
/* Ajout d'animations */
@keyframes pulse {
  0%, 100% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.05);
  }
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

.hover\:scale-102:hover {
  transform: scale(1.02);
}
</style>