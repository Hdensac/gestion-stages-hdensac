<template>
    <div class="flex h-screen bg-gray-100">
        <!-- SIDEBAR -->
        <aside 
            class="fixed inset-y-0 left-0 z-40 bg-gradient-to-b from-blue-600 to-blue-800 border-r border-blue-700 shadow-lg lg:sticky w-20 hover:w-64 transition-all duration-300 ease-in-out group"
        >
            <!-- Logo Section -->
            <div class="flex items-center h-16 px-4 border-b border-blue-700">
                <Link :href="route('dashboard')" class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 bg-white rounded-lg shadow-md flex items-center justify-center text-blue-600 font-extrabold">
                        GS
                    </div>
                    <h1 class="ml-3 text-xl font-bold text-white whitespace-nowrap hidden group-hover:block transition-all duration-300">
                        GestionStages
                    </h1>
                </Link>
            </div>

            <!-- Menu Items -->
            <nav class="flex-1 px-2 py-4 space-y-1">
                <Link 
                    :href="route('agent.ms.dashboard')" 
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200"
                    :class="[
                        route().current('agent.ms.dashboard') 
                            ? 'bg-blue-700 text-white' 
                            : 'text-blue-100 hover:bg-blue-700 hover:text-white'
                    ]"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="ml-3 hidden group-hover:block">Tableau de bord</span>
                </Link>

                <Link 
                    :href="route('agent.ms.stages')" 
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200"
                    :class="[
                        route().current('agent.ms.stages*') 
                            ? 'bg-blue-700 text-white' 
                            : 'text-blue-100 hover:bg-blue-700 hover:text-white'
                    ]"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span class="ml-3 hidden group-hover:block">Mes Stages</span>
                </Link>

               <!--  <Link 
                    :href="route('agent.ms.evaluations')" 
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200"
                    :class="[
                        route().current('agent.ms.evaluations*') 
                            ? 'bg-blue-700 text-white' 
                            : 'text-blue-100 hover:bg-blue-700 hover:text-white'
                    ]"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="ml-3 hidden group-hover:block">Évaluations</span>
                </Link>

                <Link 
                    :href="route('agent.ms.stagiaires')" 
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200"
                    :class="[
                        route().current('agent.ms.stagiaires*') 
                            ? 'bg-blue-700 text-white' 
                            : 'text-blue-100 hover:bg-blue-700 hover:text-white'
                    ]"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="ml-3 hidden group-hover:block">Stagiaires</span>
                </Link> -->
            </nav>

            <!-- Bottom User Section -->
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-blue-700">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <img
                            v-if="$page.props.auth.user.avatar"
                            :src="'/storage/' + $page.props.auth.user.avatar"
                            alt="Photo de profil"
                            class="w-9 h-9 rounded-full object-cover border-2 border-blue-500"
                        />
                        <div v-else class="w-9 h-9 rounded-full bg-blue-500 text-white flex items-center justify-center border-2 border-blue-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0 hidden group-hover:block">
                        <p class="text-sm font-medium text-white truncate">
                            {{ getUserName() }}
                        </p>
                        <div class="flex mt-1 space-x-2">
                            <Link :href="route('profile.edit')" class="text-xs text-blue-200 hover:text-white transition-colors">
                                Profil
                            </Link>
                            <Link :href="route('logout')" method="post" as="button" class="text-xs text-blue-200 hover:text-white transition-colors">
                                Déconnexion
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <div class="flex flex-col flex-1 w-full lg:pl-20">
            <!-- Top Nav -->
            <nav class="sticky top-0 z-30 bg-white border-b border-gray-200 shadow-sm">
                <div class="px-4 py-3 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between">
                        <!-- Mobile menu button -->
                        <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-md lg:hidden focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        
                        <!-- Page title -->
                        <div class="text-lg font-bold text-gray-800 lg:hidden">
                            <!-- Dynamically show current page title -->
                            <span v-if="route().current('agent.ms.dashboard')">Tableau de bord</span>
                            <span v-else-if="route().current('agent.ms.stages')">Mes Stages</span>
                           <!--  <span v-else-if="route().current('agent.ms.evaluer')">Évaluations</span>
                            <span v-else-if="route().current('agent.ms.stagiaires')">Stagiaires</span> -->
                            <span v-else>Maître de Stage</span>
                        </div>
                        
                        <!-- User dropdown on desktop -->
                        <!-- <div class="flex items-center gap-4">
                            <span class="hidden md:flex px-3 py-1 text-xs bg-green-100 text-green-800 font-medium rounded-full">
                                Maître de Stage
                            </span>
                            
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <button class="flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none transition duration-150 ease-in-out">
                                        <div>{{ getUserName() }}</div>
                                        <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </template>

                                <template #content>
                                    <DropdownLink :href="route('profile.edit')">
                                        Profil
                                    </DropdownLink>
                                    <DropdownLink :href="route('logout')" method="post" as="button">
                                        Déconnexion
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                        </div> -->
                    </div>
                </div>
            </nav>

            <!-- En-tête de la page -->
            <header v-if="$slots.header" class="bg-white shadow-sm">
                <div class="px-4 py-5 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Contenu principal -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-auto">
                <slot />
            </main>

            <!-- Pied de page -->
            <footer class="py-4 bg-white border-t border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center text-sm text-gray-500">
                        © {{ new Date().getFullYear() }} Gestion des Stages - Espace Maître de Stage
                    </div>
                </div>
            </footer>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

const sidebarOpen = ref(false);

// Fonction pour récupérer le nom de l'utilisateur
function getUserName() {
    const user = usePage().props.auth.user;
    
    if (user.agent && user.agent.user) {
        return `${user.agent.user.prenom} ${user.agent.user.nom}`;
    }
    
    return `${user.prenom} ${user.nom}`;
}
</script>

<style scoped>
@media (min-width: 1024px) {
    .lg\:ml-20 {
        margin-left: 5rem;
    }
}

/* Animation fluide pour le hover */
aside {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.group:hover .group-hover\:block {
    animation: fadeIn 0.3s ease-in-out forwards;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateX(-10px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}
</style>