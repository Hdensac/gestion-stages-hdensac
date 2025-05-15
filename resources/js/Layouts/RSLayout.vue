<template>
    <div class="flex h-screen bg-gray-100">
        <!-- SIDEBAR -->
        <aside 
            class="fixed inset-y-0 left-0 z-40 bg-white border-r border-gray-200 shadow-lg lg:sticky w-20 hover:w-64 transition-all duration-300 ease-in-out group"
        >
            <!-- Logo Section -->
            <div class="flex items-center h-16 px-4 border-b border-gray-200">
                <Link :href="route('dashboard')" class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 bg-blue-600 rounded-lg shadow-md flex items-center justify-center text-white font-extrabold">
                        GS
                    </div>
                    <h1 class="ml-3 text-xl font-bold text-gray-800 whitespace-nowrap hidden group-hover:block transition-all duration-300">
                        GestionStages
                    </h1>
                </Link>
            </div>

            <!-- Menu Items -->
            <nav class="flex-1 px-2 py-4 space-y-1">
                <NavLink 
                    :href="route('agent.rs.dashboard')"
                    :active="route().current('agent.rs.dashboard')"
                    class="grid grid-cols-[24px_1fr] items-center gap-3 px-3 py-3 rounded-lg hover:bg-blue-50"
                    :class="{ 'bg-blue-50 text-blue-700': route().current('agent.rs.dashboard') }"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    <span class="hidden group-hover:block whitespace-nowrap transition-all duration-300">
                        Tableau de bord
                    </span>
                </NavLink>

                <NavLink 
                    :href="route('agent.rs.demandes')"
                    :active="route().current('agent.rs.demandes')"
                    class="grid grid-cols-[24px_1fr] items-center gap-3 px-3 py-3 rounded-lg hover:bg-blue-50"
                    :class="{ 'bg-blue-50 text-blue-700': route().current('agent.rs.demandes') }"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                        <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                        <path d="M9 14h6"></path>
                        <path d="M9 10h6"></path>
                        <path d="M9 18h6"></path>
                    </svg>
                    <span class="hidden group-hover:block whitespace-nowrap transition-all duration-300">
                        Demandes
                    </span>
                </NavLink>

                <NavLink 
                    :href="route('agent.rs.stages')"
                    :active="route().current('agent.rs.stages')"
                    class="grid grid-cols-[24px_1fr] items-center gap-3 px-3 py-3 rounded-lg hover:bg-blue-50"
                    :class="{ 'bg-blue-50 text-blue-700': route().current('agent.rs.stages') }"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                    </svg>
                    <span class="hidden group-hover:block whitespace-nowrap transition-all duration-300">
                        Stages
                    </span>
                </NavLink>

                <NavLink 
                    :href="route('agent.rs.agents.index')"
                    :active="route().current('agent.rs.agents.index')"
                    class="grid grid-cols-[24px_1fr] items-center gap-3 px-3 py-3 rounded-lg hover:bg-blue-50"
                    :class="{ 'bg-blue-50 text-blue-700': route().current('agent.rs.agents.index') }"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <span class="hidden group-hover:block whitespace-nowrap transition-all duration-300">
                        Agents
                    </span>
                </NavLink>

                <NavLink 
                    :href="route('agent.rs.organigramme.index')"
                    :active="route().current('agent.rs.organigramme.index')"
                    class="grid grid-cols-[24px_1fr] items-center gap-3 px-3 py-3 rounded-lg hover:bg-blue-50"
                    :class="{ 'bg-blue-50 text-blue-700': route().current('agent.rs.organigramme.index') }"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="16" y="16" width="6" height="6" rx="1"></rect>
                        <rect x="2" y="16" width="6" height="6" rx="1"></rect>
                        <rect x="9" y="2" width="6" height="6" rx="1"></rect>
                        <path d="M5 16v-3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3"></path>
                        <path d="M12 12V8"></path>
                    </svg>
                    <span class="hidden group-hover:block whitespace-nowrap transition-all duration-300">
                        Organigramme
                    </span>
                </NavLink>

                <NavLink 
                    v-if="isDpafResponsable"
                    :href="route('agent.dashboard')"
                    :active="route().current('agent.dashboard')"
                    class="grid grid-cols-[24px_1fr] items-center gap-3 px-3 py-3 rounded-lg hover:bg-blue-50"
                    :class="{ 'bg-blue-50 text-blue-700': route().current('agent.dashboard') }"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7"></path>
                        <line x1="16" y1="5" x2="22" y2="5"></line>
                        <line x1="19" y1="2" x2="19" y2="8"></line>
                        <circle cx="9" cy="9" r="2"></circle>
                        <path d="M13 15c0-1.105-1.12-2-2.5-2s-2.5.895-2.5 2v1h5v-1z"></path>
                    </svg>
                    <span class="hidden group-hover:block whitespace-nowrap transition-all duration-300">
                        Tableau DPAF
                    </span>
                </NavLink>
            </nav>

            <!-- Bottom User Section -->
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <img
                            v-if="user?.avatar"
                            :src="'/storage/' + user.avatar"
                            alt="Photo de profil"
                            class="w-9 h-9 rounded-full object-cover border border-gray-300"
                        />
                        <div v-else class="w-9 h-9 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center border border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0 hidden group-hover:block">
                        <p class="text-sm font-medium text-gray-900 truncate">
                            {{ user?.nom }}
                        </p>
                        <div class="flex mt-1 space-x-2">
                            <Link :href="route('profile.edit')" class="text-xs text-gray-600 hover:text-blue-600">
                                Profil
                            </Link>
                            <Link :href="route('logout')" method="post" as="button" class="text-xs text-gray-600 hover:text-blue-600">
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
                            <span v-if="route().current('agent.rs.dashboard')">Tableau de bord</span>
                            <span v-else-if="route().current('agent.rs.demandes')">Demandes</span>
                            <span v-else-if="route().current('agent.rs.stages')">Stages</span>
                            <span v-else-if="route().current('agent.rs.agents.index')">Agents</span>
                            <span v-else-if="route().current('agent.rs.organigramme.index')">Organigramme</span>
                            <span v-else-if="route().current('agent.dashboard')">Tableau DPAF</span>
                            <span v-else>Gestion des Stages</span>
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
                <slot />
            </main>

            <!-- Pied de page -->
            <footer class="py-4 bg-white border-t border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center text-sm text-gray-500">
                        © {{ new Date().getFullYear() }} Gestion des Stages - Espace Responsable de Structure
                    </div>
                </div>
            </footer>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, usePage } from '@inertiajs/vue3';

const user = usePage().props.auth?.user;
const isDpafResponsable = ref(usePage().props.isDpafResponsable || false);
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