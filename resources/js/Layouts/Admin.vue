<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, usePage } from '@inertiajs/vue3';

const user = usePage().props.auth?.user;
const showingNavigationDropdown = ref(false);
const showSidebar = ref(true);

const toggleSidebar = () => {
    showSidebar.value = !showSidebar.value;
};
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Sidebar -->
        <aside :class="['fixed inset-y-0 left-0 z-50 w-72 bg-gradient-to-b from-[#2962B4] to-[#1E4B8F] border-r border-blue-700', 
                      { '-translate-x-full': !showSidebar }]">
            <!-- Logo section -->
            <div class="flex flex-col h-full">
                <div class="p-6">
                    <div class="flex items-center gap-2">
                        <div class="text-2xl font-bold text-white">
                            Gestion des Stages
                        </div>
                        <span class="text-white/80 text-lg">|</span>
                        <div class="text-lg text-white/80">
                            Administration
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-6 space-y-2 overflow-y-auto">
                    <NavLink :href="route('admin.dashboard')" 
                            :active="route().current('admin.dashboard')"
                            class="flex items-center px-4 py-3.5 text-white/80 transition-colors rounded-lg hover:bg-white/10 hover:text-white text-base">
                        <font-awesome-icon icon="chart-bar" class="w-6 h-6 mr-4" />
                        <span>Tableau de bord</span>
                    </NavLink>

                    <NavLink :href="route('admin.users.index')"
                            :active="route().current('admin.users.index')"
                            class="flex items-center px-4 py-3.5 text-white/80 transition-colors rounded-lg hover:bg-white/10 hover:text-white text-base">
                        <font-awesome-icon icon="users" class="w-6 h-6 mr-4" />
                        <span>Utilisateurs</span>
                    </NavLink>

                    <NavLink :href="route('admin.structures.index')"
                            :active="route().current('admin.structures.index')"
                            class="flex items-center px-4 py-3.5 text-white/80 transition-colors rounded-lg hover:bg-white/10 hover:text-white text-base">
                        <font-awesome-icon icon="building" class="w-6 h-6 mr-4" />
                        <span>Structures</span>
                    </NavLink>

                    <NavLink :href="route('admin.stagiaires.index')"
                            :active="route().current('admin.stagiaires.index')"
                            class="flex items-center px-4 py-3.5 text-white/80 transition-colors rounded-lg hover:bg-white/10 hover:text-white text-base">
                        <font-awesome-icon icon="user-graduate" class="w-6 h-6 mr-4" />
                        <span>Stagiaires</span>
                    </NavLink>

                    <NavLink :href="route('admin.agents.index')"
                            :active="route().current('admin.agents.index')"
                            class="flex items-center px-4 py-3.5 text-white/80 transition-colors rounded-lg hover:bg-white/10 hover:text-white text-base">
                        <font-awesome-icon icon="briefcase" class="w-6 h-6 mr-4" />
                        <span>Agents</span>
                    </NavLink>
                </nav>

                <!-- User Profile Section - Fixed at bottom -->
                <div class="mt-auto border-t border-blue-700/30">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0">
                                <img v-if="user?.avatar" :src="'/storage/' + user.avatar"
                                    class="w-12 h-12 rounded-full object-cover border-2 border-white/20"
                                    alt="Photo de profil" />
                                <div v-else class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center text-white text-lg font-semibold">
                                    {{ user?.nom?.charAt(0).toUpperCase() }}
                    </div>
                            </div>
                            <div class="ml-4 min-w-0">
                                <p class="text-base font-medium text-white truncate">
                                    {{ user?.nom }}
                                </p>
                                <p class="text-sm text-white/70 truncate">
                                    Administrateur
                                </p>
                            </div>
                        </div>

                        <!-- Profile Actions -->
                        <div class="space-y-2">
                            <Link :href="route('profile.edit')" 
                                class="flex items-center w-full px-4 py-3 text-base text-white/80 rounded-lg hover:bg-white/10 hover:text-white transition-colors">
                                <font-awesome-icon icon="user" class="w-5 h-5 mr-3" />
                                Profile
                            </Link>
                            <Link :href="route('logout')" method="post" as="button"
                                class="flex items-center w-full px-4 py-3 text-base text-white/80 rounded-lg hover:bg-red-500/20 hover:text-white transition-colors">
                                <font-awesome-icon icon="sign-out-alt" class="w-5 h-5 mr-3" />
                                Déconnexion
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div :class="['min-h-screen transition-all duration-300 ease-in-out', showSidebar ? 'lg:ml-72' : '']">
            <!-- Top Bar -->
            <header class="fixed top-0 right-0 z-40 flex items-center w-full h-16 bg-white border-b border-gray-200"
                    :class="[showSidebar ? 'lg:ml-72' : '']">
                <div class="flex items-center justify-between flex-1 px-6">
                    <button @click="toggleSidebar" class="p-2 text-gray-400 rounded-md hover:bg-gray-100 lg:hidden">
                        <font-awesome-icon icon="bars" class="w-6 h-6" />
                    </button>

                    <div class="flex items-center space-x-4">
                        <button class="p-2 text-gray-400 rounded-md hover:bg-gray-100">
                            <font-awesome-icon icon="bell" class="w-6 h-6" />
                        </button>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="pt-16">
                <!-- Page Heading -->
                <header v-if="$slots.header" class="bg-white shadow">
                    <div class="px-6 py-2">
                        <slot name="header" />
                    </div>
                </header>

                <!-- Main Content -->
                <div class="p-4">
                <slot />
                </div>
            </main>
        </div>
    </div>
</template>

<style scoped>
.router-link-active {
    @apply bg-white/20 text-white;
}

/* Smooth transitions */
.transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
}

/* Custom scrollbar for sidebar */
nav::-webkit-scrollbar {
    width: 4px;
}

nav::-webkit-scrollbar-track {
    background: transparent;
}

nav::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 2px;
}

nav::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.3);
}
</style>
