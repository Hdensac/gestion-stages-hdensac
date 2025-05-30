<script setup>
import { ref, provide } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

const sidebarExpanded = ref(true);
const showUserMenu = ref(false);
const user = usePage().props.auth?.user;
const logoUrl = '/images/logoministere.png';
const showNotifications = ref(false);
provide('openNotifications', () => { showNotifications.value = true });

const toggleSidebar = () => {
    sidebarExpanded.value = !sidebarExpanded.value;
};

const toggleUserMenu = () => {
    showUserMenu.value = !showUserMenu.value;
};

const closeUserMenu = () => {
    showUserMenu.value = false;
};

const menuItems = [
    {
        name: 'Tableau de bord',
        route: 'stagiaire.dashboard',
        icon: 'M3 5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5z',
        active: ['stagiaire.dashboard']
    },
    {
        name: 'Mes Demandes',
        route: 'mes.demandes',
        icon: 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
        active: ['mes.demandes']
    },
    {
        name: 'Mes Stages',
        route: 'stagiaire.stages',
        icon: 'M20.584 9.75A2.253 2.253 0 0018.25 7.5h-1.5V6a3 3 0 00-3-3H7.5a3 3 0 00-3 3v1.5h-1.5c-1.241 0-2.25 1.009-2.25 2.25v7.5A2.253 2.253 0 004.25 19.5h15a2.253 2.253 0 002.25-2.25v-7.5zM16.5 7.5h-9V6a1.5 1.5 0 011.5-1.5h4.5a1.5 1.5 0 011.5 1.5v1.5zm-.919 9.374a1.5 1.5 0 01-2.121 0l-.303-.303a1.5 1.5 0 012.121-2.121l.303.303a1.5 1.5 0 010 2.121zm2.121-2.121a1.5 1.5 0 010 2.121l-.303.303a1.5 1.5 0 01-2.121-2.121l.303-.303a1.5 1.5 0 012.121 0z',
        active: ['stagiaire.stages', 'stagiaire.stages.show']
    },
    {
        name: 'Recherche par code',
        route: 'recherche.code',
        icon: 'M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z',
        active: ['recherche.code']
    }
];

const isActive = (routeNames) => {
    return routeNames.some(routeName => route().current(routeName));
};

const markAsRead = (notificationId) => {
  router.post(route('stagiaire.notifications.markAsRead', notificationId), {}, {
    preserveScroll: true,
  });
};

const props = defineProps({
  notifications: {
    type: Array,
    default: () => []
  }
});
</script>

<template>
    <div class="flex h-screen stagiaire-bg">
        <!-- SIDEBAR -->
        <aside 
            :class="[
                'bg-gradient-to-b from-blue-600 to-blue-700 text-white transition-all duration-300 ease-in-out flex flex-col shadow-lg z-30',
                sidebarExpanded ? 'w-64' : 'w-20'
            ]"
        >
            <!-- Header du Sidebar -->
            <div class="p-4 border-b border-blue-500/30">
                <div class="flex items-center justify-between">
                    <Link :href="route('dashboard')" class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center shadow-md">
                            <span class="text-blue-600 font-bold text-lg">GS</span>
                        </div>
                        <transition name='fade' mode='out-in'>
                          <div v-if="sidebarExpanded" key="logo-text" class="transition-all duration-300">
                            <h1 class="text-sm font-bold text-white">GestionStages</h1>
                            <p class="text-xs text-blue-200">Espace Stagiaire</p>
                        </div>
                        </transition>
                    </Link>
                    <button 
                        @click="toggleSidebar"
                        class="p-2 rounded-lg hover:bg-blue-500/30 transition-colors duration-200"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 p-4 space-y-1">
                <div v-if="sidebarExpanded" class="text-xs font-semibold text-blue-200 uppercase tracking-wider mb-3 px-3">
                    Navigation
                </div>
                
                <Link
                    v-for="item in menuItems"
                    :key="item.route"
                    :href="route(item.route)"
                    :class="[
                        'flex items-center rounded-lg text-sm font-medium transition-all duration-200 group',
                        sidebarExpanded ? 'px-3 py-3' : 'justify-center py-3',
                        isActive(item.active)
                            ? 'bg-blue-500 text-white shadow-md'
                            : 'text-blue-100 hover:bg-blue-500/30 hover:text-white'
                    ]"
                >
                    <svg class="w-6 h-6 flex-shrink-0 transition-colors duration-200" 
                         :class="isActive(item.active) ? 'text-white' : 'text-blue-300 group-hover:text-white'" 
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.icon" />
                    </svg>
                    <transition name='fade' mode='out-in'>
                    <span 
                        v-if="sidebarExpanded" 
                        class="ml-3 transition-all duration-300"
                          :key="`label-${item.route}`"
                    >
                        {{ item.name }}
                    </span>
                    </transition>
                    <div 
                        v-if="isActive(item.active) && sidebarExpanded"
                        class="ml-auto w-2 h-2 bg-white rounded-full"
                    ></div>
                </Link>
            </nav>

            <!-- User Profile Section avec menu personnalisé -->
            <div class="p-4 border-t border-blue-500/30 relative">
                <div class="relative">
                    <!-- Bouton du profil utilisateur -->
                    <button 
                        @click="toggleUserMenu"
                        :class="[
                            'w-full flex items-center rounded-lg text-sm font-medium text-blue-100 bg-blue-500/20 transition-colors hover:bg-blue-500/30',
                            sidebarExpanded ? 'px-3 py-3' : 'justify-center py-3'
                        ]"
                    >
                    <div class="relative flex-shrink-0">
                        <img
                            v-if="user && user.avatar"
                            :src="'/storage/' + user.avatar"
                            alt="Photo de profil"
                            class="w-8 h-8 rounded-full object-cover border-2 border-blue-300"
                        />
                            <div v-else class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-sm font-semibold border-2 border-blue-300">
                            {{ user?.nom?.charAt(0) || 'U' }}
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-400 border-2 border-blue-600 rounded-full"></div>
                    </div>
                        <transition name='fade' mode='out-in'>
                          <div v-if="sidebarExpanded" class="ml-3 text-left flex-1" key="user-info">
                        <div class="font-medium text-white text-sm">{{ user?.nom || 'Utilisateur' }}</div>
                        <div class="text-xs text-blue-200 truncate">Stagiaire</div>
                    </div>
                        </transition>
                        <transition name='fade' mode='out-in'>
                          <svg v-if="sidebarExpanded" class="w-4 h-4 text-blue-200 ml-2 transition-transform" :class="{ 'rotate-180': showUserMenu }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                          </svg>
                        </transition>
                    </button>

                    <!-- Menu contextuel personnalisé -->
                    <transition name="menu-fade">
                        <div 
                            v-if="showUserMenu" 
                            class="absolute bottom-full left-0 right-0 mb-2 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50"
                        >
                            <!-- Info utilisateur -->
                            <div class="px-4 py-3 border-b border-gray-100">
                                <div class="font-medium text-base text-gray-800">{{ user?.nom || 'Utilisateur' }}</div>
                                <div class="font-medium text-sm text-gray-500">{{ user?.email || 'Email non disponible' }}</div>
                            </div>
                            
                            <!-- Options du menu -->
                            <div class="py-1">
                                <Link
                                    :href="route('profile.edit')"
                                    @click="closeUserMenu"
                                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors"
                                >
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Mon Profil
                                </Link>
                                <Link
                                    :href="route('logout')"
                                    method="post"
                                    as="button"
                                    @click="closeUserMenu"
                                    class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors"
                                >
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Se Déconnecter
                                </Link>
                            </div>
                        </div>
                    </transition>

                    <!-- Overlay pour fermer le menu en cliquant ailleurs -->
                    <div 
                        v-if="showUserMenu" 
                        @click="closeUserMenu"
                        class="fixed inset-0 z-40"
                    ></div>
                </div>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <div class="flex-1 flex flex-col">
            <!-- Top Header modernisé et professionnel -->
            <header class="bg-white shadow-sm border-b border-gray-200 z-20">
                <div class="flex items-center justify-between px-8 py-6">
                    <div class="flex items-center space-x-6">
                        <!-- Logo avec container moderne -->
                        <div class="flex-shrink-0">
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-3 rounded-xl border border-blue-200/50 shadow-sm">
                        <img
                            :src="logoUrl"
                            alt="Logo du Ministère"
                            class="h-12 w-auto object-contain"
                        />
                            </div>
                        </div>
                        
                        <!-- Séparateur visuel -->
                        <div class="hidden md:block w-px h-12 bg-gradient-to-b from-transparent via-gray-300 to-transparent"></div>
                        
                        <!-- Informations textuelles avec hiérarchie claire -->
                        <div class="hidden md:block space-y-1">
                            <h1 class="text-xl font-semibold text-gray-900 tracking-tight">
                                Programme de Stages
                            </h1>
                            <div class="flex items-center space-x-2">
                                <p class="text-sm font-medium text-blue-600">Ministère des Finances</p>
                                <span class="text-gray-300">•</span>
                                <p class="text-sm text-gray-500">République du Bénin</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Section droite avec indicateurs -->
                    <div class="flex items-center space-x-4">
                        <!-- Badge statut pour desktop -->
                        <div class="hidden lg:flex items-center space-x-3">
                            <div class="flex items-center space-x-2 bg-green-50 text-green-700 px-3 py-2 rounded-full text-sm font-medium border border-green-200">
                                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                <span>Plateforme Active</span>
                            </div>
                        </div>
                        
                        <!-- Badge mobile/tablet -->
                        <div class="lg:hidden bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-2 rounded-full text-sm font-medium shadow-md">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>Espace Stagiaire</span>
                            </div>
                        </div>
                        
                        <!-- Indicateur de version/environnement (optionnel) -->
                        <div class="hidden xl:block text-xs text-gray-400 font-mono bg-gray-50 px-2 py-1 rounded border">
                            v2.1.0
                        </div>
                    </div>
                </div>
                
                <!-- Barre de progression ou indicateur supplémentaire (optionnel) -->
                <div class="h-1 bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700"></div>
            </header>

            <!-- Cloche de notifications globale avec positionnement ajusté -->
            <div class="fixed top-8 right-8 z-50">
                                    <div class="relative">
                    <button @click="showNotifications = !showNotifications" class="relative focus:outline-none group">
                        <div class="bg-white p-3 rounded-full shadow-lg border border-gray-200 group-hover:shadow-xl transition-all duration-200 group-hover:border-blue-300">
                            <svg class="w-6 h-6 text-blue-600 group-hover:text-blue-700 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </div>
                        <span v-if="notifications.length" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full px-2 py-1 font-medium shadow-md min-w-[1.25rem] text-center">
                            {{ notifications.length > 99 ? '99+' : notifications.length }}
                        </span>
                    </button>
                    
                    <!-- Panel de notifications avec design modernisé -->
                    <div v-if="showNotifications" class="absolute right-0 mt-4 w-96 bg-white rounded-xl shadow-2xl border border-gray-200 overflow-hidden animate-fade-in z-50">
                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-4 border-b border-blue-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    <span class="font-semibold text-gray-800">Notifications</span>
                                    <span v-if="notifications.length" class="bg-blue-600 text-white text-xs px-2 py-1 rounded-full">{{ notifications.length }}</span>
                                </div>
                                <button @click="showNotifications = false" class="text-gray-400 hover:text-gray-600 p-1 rounded-full hover:bg-white/50 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <div v-if="notifications.length === 0" class="p-8 text-center">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p class="text-gray-500 font-medium">Aucune notification</p>
                            <p class="text-sm text-gray-400 mt-1">Vous êtes à jour !</p>
                        </div>
                        
                        <ul v-else class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
                            <li v-for="notif in notifications" :key="notif.id" class="p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-start gap-3">
                                    <div class="pt-1 flex-shrink-0">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center" :class="{
                                            'bg-green-100': notif.data.message.includes('acceptée'),
                                            'bg-red-100': notif.data.message.includes('refusée'),
                                            'bg-blue-100': notif.data.message.includes('maître de stage'),
                                            'bg-gray-100': !notif.data.message.includes('acceptée') && !notif.data.message.includes('refusée') && !notif.data.message.includes('maître de stage')
                                        }">
                                            <svg v-if="notif.data.message.includes('acceptée')" class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            <svg v-else-if="notif.data.message.includes('refusée')" class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            <svg v-else-if="notif.data.message.includes('maître de stage')" class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <svg v-else class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm font-medium text-gray-900 mb-1 leading-relaxed" v-html="notif.data.message"></div>
                                        <div v-if="notif.data.url" class="mb-2">
                                            <a :href="notif.data.url" class="inline-flex items-center text-xs text-blue-600 hover:text-blue-700 font-medium hover:underline">
                                                Voir les détails
                                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </a>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <div class="text-xs text-gray-500">{{ new Date(notif.created_at).toLocaleString('fr-FR') }}</div>
                                            <button @click="markAsRead(notif.id)" class="text-xs text-gray-400 hover:text-green-600 font-medium hover:bg-green-50 px-2 py-1 rounded transition-colors" title="Marquer comme lue">
                                                Marquer comme lue
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Page Heading (si présent) -->
            <header
                v-if="$slots.header"
                class="bg-white border-b border-gray-200"
            >
                <div class="px-6 py-6">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <slot />
            </main>
        </div>
    </div>
</template>

<style>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}

.menu-fade-enter-active, .menu-fade-leave-active {
  transition: all 0.2s ease;
}
.menu-fade-enter-from, .menu-fade-leave-to {
  opacity: 0;
  transform: translateY(10px);
}

.stagiaire-bg {
  position: relative;
  min-height: 100vh;
}
.stagiaire-bg::before {
  content: '';
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url('/images/bg-stagiaire.jpg') center/cover no-repeat;
  opacity: 0.12;
  z-index: -1;
  pointer-events: none;
}

/* Animation pour le panel de notifications */
@keyframes fade-in {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  animation: fade-in 0.2s ease-out;
}
</style>