<template>
    <div class="flex h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 relative overflow-hidden">
        <!-- Background Image with Overlay -->
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-5"
             style="background-image: url('/images/bg.png')"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-blue-900/3 via-indigo-900/2 to-slate-900/3"></div>
        <!-- SIDEBAR moderne -->
        <aside :class="[
                'sidebar-modern fixed inset-y-0 left-0 z-40 transition-all duration-300 ease-in-out',
                expanded ? 'w-64' : 'w-20'
            ]">
            <!-- Bouton Toggle Modern -->
            <button @click="toggleSidebar"
                class="sidebar-toggle absolute -right-3 top-20 bg-white rounded-full p-1.5 shadow-lg border border-blue-200 z-50 hover:bg-blue-50 transition-all duration-200 hover:shadow-xl hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
                :aria-label="expanded ? 'Réduire le menu' : 'Développer le menu'">
                <svg v-if="expanded" class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                <svg v-else class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <!-- Logo Section moderne -->
            <div class="flex items-center h-16 px-4 border-b border-white/20">
                <Link :href="route('agent.ms.dashboard')" class="flex items-center">
                    <div class="logo-modern flex-shrink-0">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <h1 v-if="expanded"
                        class="ml-3 text-xl font-bold text-white whitespace-nowrap transition-all duration-300">
                        MS Stages
                    </h1>
                </Link>
            </div>

            <!-- Menu Items - Only keeping MS specific links -->
            <nav class="flex-1 px-2 py-4 space-y-1">
                <!-- Tableau de bord MS -->
                <Link :href="route('agent.ms.dashboard')"
                    class="menu-link flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200"
                    :class="[route().current('agent.ms.dashboard') ? 'active' : 'text-blue-100 hover:bg-blue-700 hover:text-white']"
                    tabindex="0">
                <span v-if="route().current('agent.ms.dashboard')" class="active-indicator"></span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span v-if="expanded" class="ml-3">Tableau de bord</span>
                <span v-else class="tooltip">Tableau de bord</span>
                </Link>

                <!-- Mes Stages MS -->
                <Link :href="route('agent.ms.stages')"
                    class="menu-link flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200"
                    :class="[route().current('agent.ms.stages*') ? 'active' : 'text-blue-100 hover:bg-blue-700 hover:text-white']"
                    tabindex="0">
                <span v-if="route().current('agent.ms.stages*')" class="active-indicator"></span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span v-if="expanded" class="ml-3">Mes Stages</span>
                <span v-else class="tooltip">Mes Stages</span>
                </Link>

            </nav>

            <!-- Séparateur visuel -->
            <hr class="separator" />

            <!-- Bottom User Section repensée -->
            <div class="user-section absolute bottom-0 left-0 right-0">
                <div class="flex-shrink-0 relative">
                    <img v-if="$page.props.auth.user?.avatar" :src="'/storage/' + $page.props.auth.user.avatar"
                        alt="Photo de profil" class="user-avatar w-9 h-9 rounded-full object-cover" />
                    <div v-else
                        class="user-avatar w-9 h-9 rounded-full bg-blue-500 text-white flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                </div>
                <div v-if="expanded" class="user-info flex-1 min-w-0 relative">
                    <p class="user-name truncate flex items-center gap-1">
                        {{ $page.props.auth.user?.nom }}
                        <button @click="toggleUserMenu"
                            class="ml-1 p-1 rounded-full hover:bg-blue-500/30 transition-colors"
                            aria-label="Ouvrir le menu utilisateur">
                            <svg class="w-4 h-4 text-blue-200 transition-transform"
                                :class="{ 'rotate-180': showUserMenu }" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 15l7-7 7 7" />
                            </svg>
                        </button>
                    </p>
                    <!-- Menu contextuel -->
                    <transition name="fade">
                        <div v-if="showUserMenu"
                            class="absolute bottom-full left-0 right-0 mb-2 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                            <Link :href="route('profile.edit')" @click="closeUserMenu"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">Profil
                            </Link>
                            <button @click="showLogoutModal = true; closeUserMenu()"
                                class="block w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-red-50 transition-colors">Déconnexion</button>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </div>
                <h2 class="text-lg font-semibold mb-2">Se déconnecter ?</h2>
                <p class="text-gray-500 mb-6">Voulez-vous vraiment vous déconnecter ?</p>
                <div class="flex justify-center gap-4">
                    <button @click="showLogoutModal = false"
                        class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium">Annuler</button>
                    <button @click="logout()" type="button"
                        class="px-4 py-2 rounded bg-red-500 hover:bg-red-600 text-white font-medium">Se
                        déconnecter</button>
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
                            <div
                                class="bg-gradient-to-br from-blue-50 to-blue-100 p-3 rounded-xl border border-blue-200/50 shadow-sm">
                                <img src="/images/logoministere.png" alt="Logo du Ministère"
                                    class="h-12 w-auto object-contain" />
                            </div>
                        </div>
                        <div
                            class="hidden md:block w-px h-12 bg-gradient-to-b from-transparent via-gray-300 to-transparent">
                        </div>
                        <div class="hidden md:block space-y-1">
                            <h1 class="text-lg font-semibold text-gray-900 tracking-tight">
                                Gestion des Stages - MS
                            </h1>
                            <div class="flex items-center space-x-2">
                                <p class="text-sm font-medium text-blue-600">Ministère de l'Économie et des Finances</p>
                                <span class="text-gray-300"> • </span>
                                <p class="text-sm text-gray-500">République du Bénin</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="hidden lg:flex items-center space-x-3">
                            <div class="flex items-center space-x-2 bg-gradient-to-r from-green-50 to-emerald-50 text-green-700 px-4 py-2 rounded-lg text-sm font-medium border border-green-200">
                                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                <span>Espace MS</span>
                            </div>
                        </div>
                        <div class="lg:hidden bg-gradient-to-r from-green-600 to-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-lg">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                <span>Espace MS</span>
                            </div>
                        </div>
                        <div
                            class="hidden xl:block text-xs text-gray-400 font-mono bg-gray-50 px-2 py-1 rounded border">
                            v2.1.0
                        </div>
                        <!-- Cloche de notifications modernisée -->
                        <div class="relative">
                            <button @click="toggleNotifications"
                                class="notification-button bg-gradient-to-br from-blue-50 to-indigo-50 p-3 rounded-xl border border-blue-200/50 shadow-sm hover:shadow-md transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                <!-- Icône cloche -->
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z" fill="none"/>
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 8a6 6 0 10-12 0c0 7-3 9-3 9h18s-3-2-3-9" />
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.73 21a2 2 0 01-3.46 0" />
                                </svg>
                            </button>
                            <div
                                class="notification-badge absolute -top-1 -right-1 w-5 h-5 bg-gradient-to-r from-red-500 to-red-600 rounded-full flex items-center justify-center">
                                <span class="text-xs font-bold text-white">{{ notifications.length }}</span>
                            </div>

                            <!-- Panneau de notifications -->
                            <div v-if="showNotifications"
                                class="absolute right-0 mt-4 w-96 bg-white rounded-xl shadow-2xl border border-gray-200 overflow-hidden animate-fade-in z-50">
                                <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-4 border-b border-blue-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 17h5l-5 5v-5zM10.07 2.82l3.93 3.93-8.49 8.49a2 2 0 01-1.42.59H1v-3.09a2 2 0 01.59-1.41l8.48-8.5zM15 6l3-3" />
                                            </svg>
                                            <span class="font-semibold text-gray-800">Notifications</span>
                                            <span v-if="notifications.length"
                                                class="bg-blue-600 text-white text-xs px-2 py-1 rounded-full">{{
                                                notifications.length }}</span>
                                        </div>
                                        <button @click="showNotifications = false"
                                            class="text-gray-400 hover:text-gray-600 p-1 rounded-full hover:bg-white/50 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div v-if="notifications.length === 0" class="p-8 text-center">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <p class="text-gray-500 font-medium">Aucune notification</p>
                                    <p class="text-sm text-gray-400 mt-1">Vous êtes à jour !</p>
                                </div>
                                <ul v-else class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
                                    <li v-for="notif in notifications" :key="notif.id"
                                        class="p-4 hover:bg-gray-50 transition-colors">
                                        <div class="flex items-start gap-3">
                                            <div class="pt-1">
                                                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" />
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-sm text-gray-800 font-medium">
                                                    {{ notif.data.message }}
                                                </div>
                                                <div v-if="notif.data.url" class="mt-1">
                                                    <a :href="notif.data.url"
                                                        class="text-xs text-blue-600 hover:underline">Voir le stage</a>
                                                </div>
                                                <div class="text-xs text-gray-400 mt-1">
                                                    {{ formatDate(notif.created_at) }}
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- En-tête de la page -->
            <header v-if="$slots.header"
                class="page-header bg-gradient-to-r from-blue-50/50 to-indigo-50/30 border-b border-blue-100/50">
                <div class="px-8 py-6 mx-auto max-w-7xl">
                    <slot name="header" />
                </div>
            </header>

            <!-- Contenu principal -->
            <main class="main-content flex-1 p-8 overflow-auto bg-gradient-to-br from-gray-50/30 to-blue-50/20">
                <slot />
            </main>

            <!-- Footer glassmorphism modernisé -->
            <footer class="footer-glass border-t border-gray-200/50 backdrop-blur-sm">
                <div class="max-w-7xl mx-auto px-8 py-4">
                    <div class="flex flex-col md:flex-row items-center justify-between space-y-2 md:space-y-0">
                        <div class="text-center md:text-left">
                            <p class="text-sm font-medium text-gray-700">
                                © {{ new Date().getFullYear() }} Gestion des Stages
                            </p>
                            <p class="text-xs text-gray-500">
                                Espace Maître de Stage
                            </p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="hidden md:flex items-center space-x-2 text-xs text-gray-500">
                                <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                                <span>Système opérationnel</span>
                            </div>
                            <div class="text-xs text-gray-400 font-mono bg-gray-100 px-2 py-1 rounded">
                                Build 2024.11.28
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'

const expanded = ref(true)
const showUserMenu = ref(false)
const showLogoutModal = ref(false)

// États pour les notifications
const showNotifications = ref(false)
const isRinging = ref(false)
const hasNewNotifications = ref(true) // Example initial state
const notificationCount = ref(3) // Example initial count
const hasUnreadNotifications = ref(true) // Example initial state

const page = usePage()
const notifications = computed(() => page.props.notifications || [])

const getUserName = () => {
    const user = page.props.auth.user;
    if (user.agent && user.agent.maitre_de_stage && user.agent.maitre_de_stage.user) {
        return `${user.agent.maitre_de_stage.user.prenom} ${user.agent.maitre_de_stage.user.nom}`;
    }
    if (user.agent && user.agent.user) {
        return `${user.agent.user.prenom} ${user.agent.user.nom}`;
    }
    return `${user.prenom} ${user.nom}`;
};

const toggleSidebar = () => {
    expanded.value = !expanded.value
}

const toggleUserMenu = () => {
    showUserMenu.value = !showUserMenu.value
}

const closeUserMenu = () => {
    showUserMenu.value = false
}

const logout = () => {
    showLogoutModal.value = false
    router.post(route('logout'))
}

// Fonctions pour les notifications
const toggleNotifications = () => {
    showNotifications.value = !showNotifications.value
    if (showNotifications.value) {
        triggerBellRing()
    }
}

const closeNotifications = () => {
    showNotifications.value = false
}

const onNotificationHover = () => {
    if (!showNotifications.value) {
        triggerBellRing()
    }
}

const onNotificationLeave = () => {
    isRinging.value = false
}

const triggerBellRing = () => {
    isRinging.value = true
    setTimeout(() => {
        isRinging.value = false
    }, 600)
}

const markAllAsRead = () => {
    notifications.value.forEach(n => n.read = true)
    // Ici, vous devrez probablement faire une requête API pour marquer les notifications comme lues en base de données
    // hasNewNotifications et hasUnreadNotifications devraient être calculés en fonction de l'état réel des notifications après l'API call
    hasNewNotifications.value = false // Exemple, à adapter
    hasUnreadNotifications.value = false // Exemple, à adapter
    notificationCount.value = 0 // Exemple, à adapter
}

const getNotificationIconClass = (type) => {
    switch (type) {
        case 'stage':
            return 'bg-blue-100 text-blue-600'
        case 'evaluation':
            return 'bg-orange-100 text-orange-600'
        case 'system':
            return 'bg-gray-100 text-gray-600'
        default:
            return 'bg-gray-100 text-gray-600'
    }
}

const getNotificationIconPath = (type) => {
    // Chemins d'icônes SVG pour chaque type
    switch (type) {
        case 'stage':
            return 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'; // Icône de document/fichier
        case 'evaluation':
            return 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'; // Icône de coche/succès
        case 'system':
            return 'M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'; // Icône d'ordinateur/système
        default:
            return 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'; // Icône d'information
    }
}

const formatTime = (dateString) => {
    const date = new Date(dateString);
    // Formate l'heure et les minutes
    const hours = date.getHours().toString().padStart(2, '0');
    const minutes = date.getMinutes().toString().padStart(2, '0');
    // Formate le jour, le mois et l'année
    const day = date.getDate().toString().padStart(2, '0');
    const month = (date.getMonth() + 1).toString().padStart(2, '0'); // Les mois sont de 0 à 11
    const year = date.getFullYear();

    return `${day}/${month}/${year} ${hours}:${minutes}`;
}

function formatDate(date) {
    const d = new Date(date);
    const now = new Date();
    const diff = Math.floor((now - d) / 1000);
    if (diff < 60) return 'À l\'instant';
    if (diff < 3600) return `Il y a ${Math.floor(diff/60)} min`;
    if (diff < 86400) return `Il y a ${Math.floor(diff/3600)} h`;
    return d.toLocaleDateString('fr-FR', { day: '2-digit', month: 'short', year: 'numeric' });
}

// Vous devrez probablement charger les notifications réelles depuis votre backend au montage du composant
// Exemple (pseudocode - à adapter à votre API) :
/*
onMounted(async () => {
    try {
        const response = await axios.get(route('agent.ms.notifications')); // Adaptez la route
        notifications.value = response.data.notifications; // Adaptez la structure de la réponse
        notificationCount.value = notifications.value.filter(n => !n.read).length;
        hasNewNotifications.value = notificationCount.value > 0; // Ou selon un autre critère 'new' si différent de 'unread'
        hasUnreadNotifications.value = notificationCount.value > 0;
    } catch (error) {
        console.error('Erreur lors du chargement des notifications:', error);
    }
});
*/

const isDpafResponsable = computed(() => {
    return page.props.auth.user?.role === 'dpaf' || page.props.auth.user?.is_dpaf_responsable
})
</script>

<style scoped>
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

.logo-gradient::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transform: rotate(45deg);
    transition: all 0.6s ease;
    opacity: 0;
}

.logo-gradient:hover::before {
    opacity: 1;
    animation: shine 0.6s ease-in-out;
}

@keyframes shine {
    0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
    100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
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

/* Tooltips pour sidebar réduite */
.tooltip {
    position: absolute;
    left: 60px;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 12px;
    white-space: nowrap;
    opacity: 0;
    pointer-events: none;
    transition: all 0.3s ease;
    z-index: 1000;
}

.menu-link:hover .tooltip {
    opacity: 1;
    left: 65px;
}

/* Séparateur élégant */
.separator {
    margin: 16px;
    border: none;
    height: 1px;
    background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.3), transparent);
}

/* Section utilisateur repensée */
.user-section {
    padding: 16px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    gap: 12px;
}

.user-avatar {
    border: 2px solid rgba(255, 255, 255, 0.3);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.user-avatar:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
}

.user-name {
    color: white;
    font-weight: 600;
    font-size: 14px;
}

/* Header glassmorphism */
.header-glass {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(0, 0, 0, 0.08);
}

/* Bouton notifications */
.notification-button {
    position: relative;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.notification-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(59, 130, 246, 0.15);
}

.notification-badge {
    animation: pulse 2s infinite;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
}

/* Page header */
.page-header {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.05), rgba(99, 102, 241, 0.03));
    backdrop-filter: blur(10px);
}

/* Main content */
.main-content {
    background: linear-gradient(135deg, 
        rgba(248, 250, 252, 0.8) 0%, 
        rgba(239, 246, 255, 0.6) 50%, 
        rgba(224, 242, 254, 0.4) 100%
    );
}

/* Footer glassmorphism */
.footer-glass {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(20px);
}

/* Sidebar toggle */
.sidebar-toggle {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.sidebar-toggle:hover {
    transform: scale(1.1);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.15);
}

/* Transitions */
.fade-enter-active, .fade-leave-active {
    transition: all 0.3s ease;
}

.fade-enter-from, .fade-leave-to {
        opacity: 0;
    transform: translateY(-10px);
}

/* Animations */
@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

/* Responsive */
@media (max-width: 1024px) {
    .sidebar-glass {
        transform: translateX(-100%);
    }
    
    .sidebar-glass.expanded {
        transform: translateX(0);
    }
}
</style>