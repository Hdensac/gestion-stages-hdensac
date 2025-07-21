<template>
    <div class="flex h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 relative overflow-hidden">
        <!-- Background Image with Overlay -->
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-5"
             style="background-image: url('/images/bg.png')"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-blue-900/3 via-indigo-900/2 to-slate-900/3"></div>
        <!-- SIDEBAR moderne -->
        <aside
            :class="[
                'sidebar-modern fixed inset-y-0 left-0 z-40 transition-all duration-300 ease-in-out',
                expanded ? 'w-64' : 'w-20'
            ]"
        >
            <!-- Bouton Toggle Modern -->
            <button
                @click="toggleSidebar"
                class="sidebar-toggle absolute -right-3 top-20 bg-white rounded-full p-1.5 shadow-lg border border-blue-200 z-50 hover:bg-blue-50 transition-all duration-200 hover:shadow-xl hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
                :aria-label="expanded ? 'Réduire le menu' : 'Développer le menu'"
            >
                <svg v-if="expanded" class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                <svg v-else class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <!-- Logo Section moderne -->
            <div class="flex items-center h-16 px-4 border-b border-white/20">
                <Link :href="route('agent.dashboard')" class="flex items-center">
                    <div class="logo-modern flex-shrink-0">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                    </div>
                    <h1 v-if="expanded" class="ml-3 text-xl font-bold text-white whitespace-nowrap transition-all duration-300">
                        Gestion Stages
                    </h1>
                </Link>
            </div>

            <!-- Menu Items -->
            <nav class="flex-1 px-2 py-4 space-y-1">
                <Link 
                    :href="route('agent.dashboard')" 
                    class="menu-link flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200"
                    :class="[route().current('agent.dashboard') ? 'active' : 'text-blue-100 hover:bg-blue-700 hover:text-white']"
                    tabindex="0"
                >
                    <span v-if="route().current('agent.dashboard')" class="active-indicator"></span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span v-if="expanded" class="ml-3">Tableau de bord</span>
                    <span v-else class="tooltip">Tableau de bord</span>
                </Link>

                <Link 
                    :href="route('agent.demandes')" 
                    class="menu-link flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200"
                    :class="[route().current('agent.demandes*') ? 'active' : 'text-blue-100 hover:bg-blue-700 hover:text-white']"
                    tabindex="0"
                >
                    <span v-if="route().current('agent.demandes*')" class="active-indicator"></span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span v-if="expanded" class="ml-3">Demandes</span>
                    <span v-else class="tooltip">Demandes</span>
                </Link>

                <Link
                    :href="route('agent.stages-dpaf.index')"
                    class="menu-link flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200"
                    :class="[route().current('agent.stages-dpaf.index') ? 'active' : 'text-blue-100 hover:bg-blue-700 hover:text-white']"
                    tabindex="0"
                >
                    <span v-if="route().current('agent.stages-dpaf.index')" class="active-indicator"></span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 014-4h4m0 0V7a4 4 0 00-8 0v4m8 4v2a4 4 0 01-4 4H7a4 4 0 01-4-4v-2" />
                    </svg>
                    <span v-if="expanded" class="ml-3">Stages</span>
                    <span v-else class="tooltip">Stages</span>
                </Link>

                <Link
                    :href="route('agent.dpaf.attestations.index')"
                    class="menu-link flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200"
                    :class="[route().current('agent.dpaf.attestations.*') ? 'active' : 'text-blue-100 hover:bg-blue-700 hover:text-white']"
                    tabindex="0"
                >
                    <span v-if="route().current('agent.dpaf.attestations.*')" class="active-indicator"></span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span v-if="expanded" class="ml-3">Attestations DPAF</span>
                    <span v-else class="tooltip">Attestations DPAF</span>
                </Link>

                <Link 
                    v-if="isRSAgent"
                    :href="route('agent.rs.dashboard')"
                    class="menu-link flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200"
                    :class="[route().current('agent.rs.*') ? 'active' : 'text-blue-100 hover:bg-blue-700 hover:text-white']"
                    tabindex="0"
                >
                    <span v-if="route().current('agent.rs.*')" class="active-indicator"></span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span v-if="expanded" class="ml-3">Espace RS</span>
                    <span v-else class="tooltip">Espace RS</span>
                </Link>
            </nav>

            <!-- Séparateur visuel -->
            <hr class="separator" />

            <!-- Bottom User Section repensée -->
            <div class="user-section absolute bottom-0 left-0 right-0">
                <div class="flex-shrink-0 relative">
                        <img
                            v-if="$page.props.auth.user?.avatar"
                          :src="'/storage/' + $page.props.auth.user.avatar"
                          alt="Photo de profil"
                        class="user-avatar w-9 h-9 rounded-full object-cover"
                        />
                    <div v-else class="user-avatar w-9 h-9 rounded-full bg-blue-500 text-white flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                    </div>
                <div v-if="expanded" class="user-info flex-1 min-w-0 relative">
                    <p class="user-name truncate flex items-center gap-1">
                            {{ $page.props.auth.user?.nom }}
                            <button @click="toggleUserMenu" class="ml-1 p-1 rounded-full hover:bg-blue-500/30 transition-colors" aria-label="Ouvrir le menu utilisateur">
                                <svg class="w-4 h-4 text-blue-200 transition-transform" :class="{ 'rotate-180': showUserMenu }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                </svg>
                            </button>
                        </p>
                    <!-- Menu contextuel -->
                    <transition name="fade">
                        <div v-if="showUserMenu" class="absolute bottom-full left-0 right-0 mb-2 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                            <Link :href="route('profile.edit')" @click="closeUserMenu" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">Profil</Link>
                            <button @click="showLogoutModal = true; closeUserMenu()" class="block w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-red-50 transition-colors">Déconnexion</button>
                        </div>
                    </transition>
                    <!-- Overlay pour fermer le menu en cliquant ailleurs -->
                    <div v-if="showUserMenu" @click="closeUserMenu" class="fixed inset-0 z-40"></div>
                </div>
            </div>
        </aside>

        <!-- MODAL DE DECONNEXION -->
        <div v-if="showLogoutModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
          <div class="bg-white rounded-xl shadow-xl p-8 max-w-xs w-full text-center">
            <div class="mb-4">
              <svg class="mx-auto w-12 h-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
              </svg>
            </div>
            <h2 class="text-lg font-semibold mb-2">Se déconnecter ?</h2>
            <p class="text-gray-500 mb-6">Voulez-vous vraiment vous déconnecter ?</p>
            <div class="flex justify-center gap-4">
              <button @click="showLogoutModal = false" class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium">Annuler</button>
              <button @click="logout()" type="button" class="px-4 py-2 rounded bg-red-500 hover:bg-red-600 text-white font-medium">Se déconnecter</button>
            </div>
          </div>
        </div>

        <!-- MAIN CONTENT AREA moderne -->
        <div :class="['flex flex-col flex-1 w-full transition-all duration-300 relative z-10', expanded ? 'lg:pl-64' : 'lg:pl-20']">
            <!-- Header moderne -->
            <header class="header-modern bg-white/95 backdrop-blur-sm shadow-sm border-b border-gray-200/50 z-20 transition-all duration-300">
                <div class="flex items-center justify-between px-8 py-6">
                    <div class="flex items-center space-x-6">
                        <!-- Logo Ministère -->
                        <div class="flex-shrink-0">
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-3 rounded-xl border border-blue-200/50 shadow-sm">
                                <img
                                    src="/images/logoministere.png"
                                    alt="Logo du Ministère"
                                    class="h-12 w-auto object-contain"
                                />
                            </div>
                        </div>
                        <div class="hidden md:block w-px h-12 bg-gradient-to-b from-transparent via-gray-300 to-transparent"></div>
                        <div class="hidden md:block space-y-1">
                            <h1 class="text-lg font-semibold text-gray-900 tracking-tight">
                                Gestion des Stages - DPAF
                            </h1>
                            <div class="flex items-center space-x-2">
                                <p class="text-sm font-medium text-blue-600">Ministère de l'Économie et des Finances</p>
                                <span class="text-gray-300">•</span>
                                <p class="text-sm text-gray-500">République du Bénin</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="hidden lg:flex items-center space-x-3">
                            <div class="flex items-center space-x-2 bg-gradient-to-r from-blue-50 to-indigo-50 text-blue-700 px-4 py-2 rounded-lg text-sm font-medium border border-blue-200">
                                <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                                <span>Espace DPAF</span>
                            </div>
                        </div>
                        <div class="lg:hidden bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-lg">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Espace DPAF</span>
                            </div>
                        </div>
                        <div class="hidden xl:block text-xs text-gray-400 font-mono bg-gray-50 px-2 py-1 rounded border">
                            v2.1.0
                        </div>
                        <!-- Cloche de notifications modernisée -->
                        <div class="relative">
                            <button
                                @click="toggleNotifications"
                                @mouseenter="onNotificationHover"
                                @mouseleave="onNotificationLeave"
                                class="relative focus:outline-none group"
                                aria-label="Notifications"
                                :class="{ 'animate-ring': isRinging }"
                            >
                                <div class="bg-white p-3 rounded-full shadow-lg border border-gray-200 group-hover:shadow-xl transition-all duration-200 group-hover:border-blue-300">
                                    <svg class="w-6 h-6 text-blue-600 group-hover:text-blue-700 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                </div>
                                <span v-if="notificationCount > 0" class="badge-notif">{{ notificationCount }}</span>
                            </button>

                            <!-- Panneau de notifications -->
                            <div v-if="showNotifications"
                                class="absolute right-0 mt-4 w-96 bg-white rounded-xl shadow-2xl border border-gray-200 overflow-hidden animate-fade-in z-50">
                                <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-4 border-b border-blue-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM10.07 2.82l3.93 3.93-8.49 8.49a2 2 0 01-1.42.59H1v-3.09a2 2 0 01.59-1.41l8.48-8.5zM15 6l3-3" />
                                            </svg>
                                            <span class="font-semibold text-gray-800">Notifications</span>
                                            <span v-if="notifications.length" class="bg-blue-500 text-white text-xs px-2 py-1 rounded-full">{{ notifications.length }}</span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <button @click="markAllAsRead" v-if="hasUnreadNotifications" class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                                Tout marquer comme lu
                                            </button>
                                            <button @click="closeNotifications" class="text-gray-400 hover:text-gray-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="!notifications.length" class="p-8 text-center">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <p class="text-gray-500 font-medium">Aucune notification</p>
                                    <p class="text-sm text-gray-400 mt-1">Vous êtes à jour !</p>
                                </div>
                                <ul v-else class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
                                    <li v-for="notif in notifications" :key="notif.id" class="p-4 hover:bg-gray-50 transition-colors">
                                        <div class="flex items-start gap-3">
                                            <div class="pt-1">
                                                <div class="w-8 h-8 rounded-full flex items-center justify-center" :class="getNotificationIconClass(notif.data.type)">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center justify-between">
                                                    <p class="text-sm font-medium text-gray-900 truncate">{{ notif.data.title || 'Notification' }}</p>
                                                    <span v-if="!notif.read_at" class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                                </div>
                                                <p class="text-sm text-gray-600 mt-1">{{ notif.data.message }}</p>
                                                <div class="flex items-center justify-between mt-2">
                                                    <span class="text-xs text-gray-400">{{ formatDate(notif.created_at) }}</span>
                                                    <a v-if="notif.data.url" :href="notif.data.url" class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                                        Voir détails
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="h-1 bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700"></div>
            </header>

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
import { ref, computed, onMounted } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import AdminToast from '@/Components/AdminToast.vue';

const page = usePage();
const sidebarOpen = ref(false);
const expanded = ref(true);
const isRSAgent = computed(() => usePage().props.isRSAgent || false);
const toast = ref(null);
const showLogoutModal = ref(false);
const showUserMenu = ref(false);

// Variables pour les notifications
const showNotifications = ref(false);
const notifications = computed(() => page.props.notifications || []);
const isRinging = ref(false);

// Computed properties pour les notifications
const notificationCount = computed(() => {
    return notifications.value.filter(n => !n.read_at).length;
});

const hasUnreadNotifications = computed(() => {
    return notifications.value.some(n => !n.read_at);
});

const hasNewNotifications = computed(() => {
    return notificationCount.value > 0;
});

const toggleSidebar = () => {
  expanded.value = !expanded.value;
};
const toggleUserMenu = () => {
  showUserMenu.value = !showUserMenu.value;
};
const closeUserMenu = () => {
  showUserMenu.value = false;
};

const logout = () => {
    router.post(route('logout'));
};

// Fonctions pour les notifications
const toggleNotifications = () => {
    showNotifications.value = !showNotifications.value;
    if (showNotifications.value) {
        triggerBellRing();
    }
};

const closeNotifications = () => {
    showNotifications.value = false;
};

const onNotificationHover = () => {
    if (!showNotifications.value) {
        triggerBellRing();
    }
};

const onNotificationLeave = () => {
    isRinging.value = false;
};

const triggerBellRing = () => {
    isRinging.value = true;
    setTimeout(() => {
        isRinging.value = false;
    }, 600);
};

const markAllAsRead = () => {
    // Marquer toutes les notifications comme lues localement
    notifications.value.forEach(n => n.read_at = new Date().toISOString());

    // Optionnel : Envoyer une requête au serveur pour persister le changement
    // router.post(route('agent.notifications.mark-all-read'), {}, { preserveState: true });
};

const getNotificationIconClass = (type) => {
    switch (type) {
        case 'attestation':
            return 'bg-green-100 text-green-600';
        case 'stage':
            return 'bg-blue-100 text-blue-600';
        case 'evaluation':
            return 'bg-orange-100 text-orange-600';
        case 'system':
            return 'bg-gray-100 text-gray-600';
        default:
            return 'bg-gray-100 text-gray-600';
    }
};

const formatDate = (dateString) => {
    const date = new Date(dateString);
    const now = new Date();
    const diffInHours = Math.floor((now - date) / (1000 * 60 * 60));

    if (diffInHours < 1) {
        return 'À l\'instant';
    } else if (diffInHours < 24) {
        return `Il y a ${diffInHours}h`;
    } else {
        return date.toLocaleDateString('fr-FR', {
            day: 'numeric',
            month: 'short'
        });
    }
};

// Fermer les notifications quand on clique ailleurs
onMounted(() => {
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.relative')) {
            showNotifications.value = false;
        }
    });
});
</script>

<style scoped>
@media (min-width: 1024px) {
    .lg\:pl-64 {
        padding-left: 16rem;
    }
    .lg\:pl-20 {
        padding-left: 5rem;
    }
}

/* Transitions fluides */
aside {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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

/* Sidebar moderne */
.sidebar-modern {
    background: linear-gradient(135deg,
        rgba(59, 130, 246, 0.96) 0%,
        rgba(79, 70, 229, 0.98) 50%,
        rgba(67, 56, 202, 0.96) 100%
    );
    backdrop-filter: blur(16px);
    border-right: 1px solid rgba(255, 255, 255, 0.15);
    box-shadow:
        0 20px 40px rgba(0, 0, 0, 0.08),
        inset 0 1px 0 rgba(255, 255, 255, 0.15);
}

/* Header moderne */
.header-modern {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(8px);
    border-bottom: 1px solid rgba(229, 231, 235, 0.5);
}

/* Logo moderne */
.logo-modern {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0.1) 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.logo-modern:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
}

/* Styles pour les notifications */
.badge-notif {
    position: absolute;
    top: -8px;
    right: -8px;
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: bold;
    border: 2px solid white;
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
}

/* Animation de la cloche */
@keyframes ring {
    0%, 100% { transform: rotate(0deg); }
    10%, 30%, 50%, 70%, 90% { transform: rotate(-10deg); }
    20%, 40%, 60%, 80% { transform: rotate(10deg); }
}

.animate-ring {
    animation: ring 0.6s ease-in-out;
}

/* Animation d'apparition du panneau */
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

/* Menu items avec indicateur actif */
.menu-link {
    position: relative;
    border-radius: 12px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.menu-link:hover {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    transform: translateX(4px);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.menu-link.active {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.1));
    color: white;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    backdrop-filter: blur(10px);
}

.active-indicator {
    position: absolute;
    left: -8px;
    top: 50%;
    transform: translateY(-50%);
    width: 4px;
    height: 24px;
    background: linear-gradient(to bottom, #ffffff, #f1f5f9);
    border-radius: 2px;
    box-shadow: 0 2px 8px rgba(255, 255, 255, 0.3);
}

/**** SIDEBAR TOGGLE ANIMATION ****/
.sidebar-toggle {
  transition: transform 0.25s cubic-bezier(.4,0,.2,1), box-shadow 0.18s;
}
.sidebar-toggle:active {
  transform: scale(0.92) rotate(-8deg);
  box-shadow: 0 2px 8px 0 #2563eb33;
}

/**** BADGE NOTIF ****/
.badge-notif {
  background: linear-gradient(90deg, #f43f5e 60%, #f59e42 100%);
  color: #fff;
  font-weight: 700;
  box-shadow: 0 2px 8px 0 #f43f5e33;
  border-radius: 1rem;
  padding: 0.1rem 0.6rem;
  font-size: 0.75rem;
  position: absolute;
  top: -8px; right: -8px;
  min-width: 1.5rem;
  text-align: center;
  border: 2px solid #fff;
}

/**** USER SECTION ****/
.user-section {
  background: rgba(30, 64, 175, 0.18);
  border-top: 1.5px solid rgba(255,255,255,0.10);
  box-shadow: 0 -2px 12px 0 rgba(30,64,175,0.08);
  padding: 1.2rem 1rem 1rem 1rem;
  display: flex; align-items: center; gap: 0.75rem;
}
.user-avatar {
  border: 2.5px solid #38bdf8;
  box-shadow: 0 2px 8px 0 #38bdf833;
  transition: box-shadow 0.18s;
}
.user-avatar:hover {
  box-shadow: 0 4px 16px 0 #38bdf866;
}
.user-info {
  min-width: 0;
}
.user-name {
  font-weight: 600;
  color: #fff;
  font-size: 1rem;
  line-height: 1.1;
}
.user-links a {
  color: #bae6fd;
  font-size: 0.85rem;
  margin-right: 0.7rem;
  transition: color 0.18s;
}
.user-links a:hover {
  color: #fff;
}

/**** TOOLTIP ****/
.tooltip {
  position: absolute;
  left: 110%;
  top: 50%;
  transform: translateY(-50%);
  background: #fff;
  color: #1e40af;
  font-size: 0.85rem;
  padding: 0.3rem 0.7rem;
  border-radius: 0.5rem;
  box-shadow: 0 2px 8px 0 #2563eb22;
  white-space: nowrap;
  opacity: 0;
  pointer-events: none;
  transition: opacity 0.18s;
  z-index: 50;
}
.menu-link:focus .tooltip, .menu-link:hover .tooltip {
  opacity: 1;
}

/**** LOGO GRADIENT ****/
.logo-gradient {
  background: linear-gradient(135deg, #38bdf8 0%, #6366f1 100%);
  color: #fff;
  font-weight: 900;
  font-size: 1.5rem;
  border-radius: 0.75rem;
  box-shadow: 0 2px 12px 0 #38bdf833;
  padding: 0.5rem 1rem;
  display: flex; align-items: center; justify-content: center;
}

/**** SEPARATORS ****/
.separator {
  height: 1px;
  background: linear-gradient(90deg, #2563eb 0%, #38bdf8 100%);
  opacity: 0.18;
  margin: 0.5rem 0;
  border: none;
}

/**** ACCESSIBILITÉ ****/
.menu-link:focus-visible {
  outline: 2px solid #38bdf8;
  outline-offset: 2px;
}

/**** LOADER ****/
.loader {
  border: 3px solid #e0e7ef;
  border-top: 3px solid #38bdf8;
  border-radius: 50%;
  width: 1.5rem;
  height: 1.5rem;
  animation: spin 0.8s linear infinite;
  margin: auto;
}
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>