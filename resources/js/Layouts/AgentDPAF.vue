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
                    :href="route('agent.dashboard')" 
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200"
                    :class="[
                        route().current('agent.dashboard') 
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
                    :href="route('agent.demandes')" 
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200"
                    :class="[
                        route().current('agent.demandes*') 
                            ? 'bg-blue-700 text-white' 
                            : 'text-blue-100 hover:bg-blue-700 hover:text-white'
                    ]"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span class="ml-3 hidden group-hover:block">Demandes</span>
                </Link>

                <!-- <Link 
                    :href="route('agent.affectations')" 
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200"
                    :class="[
                        route().current('agent.affectations*') 
                            ? 'bg-blue-700 text-white' 
                            : 'text-blue-100 hover:bg-blue-700 hover:text-white'
                    ]"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="ml-3 hidden group-hover:block">Affectations</span>
                </Link>

                <Link 
                    :href="route('agent.structures')" 
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200"
                    :class="[
                        route().current('agent.structures*') 
                            ? 'bg-blue-700 text-white' 
                            : 'text-blue-100 hover:bg-blue-700 hover:text-white'
                    ]"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span class="ml-3 hidden group-hover:block">Structures</span>
                </Link>

                <Link 
                    :href="route('agent.statistiques')" 
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200"
                    :class="[
                        route().current('agent.statistiques*') 
                            ? 'bg-blue-700 text-white' 
                            : 'text-blue-100 hover:bg-blue-700 hover:text-white'
                    ]"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span class="ml-3 hidden group-hover:block">Statistiques</span>
                </Link> -->

                <Link 
                    v-if="isRSAgent"
                    :href="route('agent.rs.dashboard')"
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200"
                    :class="[
                        route().current('agent.rs.*') 
                            ? 'bg-blue-700 text-white' 
                            : 'text-blue-100 hover:bg-blue-700 hover:text-white'
                    ]"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="ml-3 hidden group-hover:block">Espace RS</span>
                </Link>
            </nav>

            <!-- Bottom User Section -->
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-blue-700">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <img
                            v-if="$page.props.auth.user?.avatar"
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
                            {{ $page.props.auth.user?.nom }}
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
                        
                        <!-- Page title or search bar can go here -->
                        <div class="text-lg font-bold text-gray-800 lg:hidden">
                            <!-- Dynamically show current page title -->
                            <span v-if="route().current('agent.dashboard')">Tableau de bord DPAF</span>
                            <span v-else-if="route().current('agent.demandes')">Demandes de stage</span>
                            <!-- <span v-else-if="route().current('agent.affectations')">Affectations</span>
                            <span v-else-if="route().current('agent.structures')">Structures</span>
                            <span v-else-if="route().current('agent.statistiques')">Statistiques</span> -->
                            <span v-else-if="route().current('agent.rs.*')">Espace RS</span>
                            <span v-else>DPAF</span>
          </div>
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
        <AdminToast ref="toast" />
                <slot />
      </main>

            <!-- Pied de page -->
            <footer class="py-4 bg-white border-t border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center text-sm text-gray-500">
                        © {{ new Date().getFullYear() }} Gestion des Stages - Département Placement et Affectation
                    </div>
                </div>
            </footer>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import AdminToast from '@/Components/AdminToast.vue';

const sidebarOpen = ref(false);
const isRSAgent = computed(() => usePage().props.isRSAgent || false);
const toast = ref(null);
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