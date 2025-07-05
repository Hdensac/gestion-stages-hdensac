<template>
    <div class="flex h-screen bg-gray-100">
        <!-- SIDEBAR avec glassmorphism et micro-interactions -->
        <aside 
            :class="[
                'sidebar-glass fixed inset-y-0 left-0 z-40 transition-all duration-300 ease-in-out',
                expanded ? 'w-64' : 'w-20'
            ]"
        >
            <!-- Bouton Toggle Modern -->
            <button 
                @click="toggleSidebar" 
                class="sidebar-toggle absolute -right-3 top-20 bg-white rounded-full p-1.5 shadow-md border border-gray-200 z-50 hover:bg-blue-50 transition-colors duration-200 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
                :aria-label="expanded ? 'Réduire le menu' : 'Développer le menu'"
            >
                <svg v-if="expanded" class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                <svg v-else class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <!-- Logo Section avec effet gradient -->
            <div class="flex items-center h-16 px-4 border-b border-blue-700">
                <Link :href="route('dashboard')" class="flex items-center">
                    <div class="logo-gradient flex-shrink-0">
                        GS
                </div>
                    <h1 v-if="expanded" class="ml-3 text-xl font-bold text-white whitespace-nowrap transition-all duration-300">
                        GestionStages
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

        <!-- MAIN CONTENT AREA -->
        <div :class="['flex flex-col flex-1 w-full transition-all duration-300', expanded ? 'lg:pl-64' : 'lg:pl-20']">
            <!-- Header glassmorphism modernisé -->
            <header class="header-glass shadow-sm border-b border-gray-200 z-20 transition-all duration-300">
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
                    <div class="flex items-center space-x-4">
                        <div class="hidden lg:flex items-center space-x-3">
                            <div class="flex items-center space-x-2 bg-green-50 text-green-700 px-3 py-2 rounded-full text-sm font-medium border border-green-200">
                                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                <span>Espace DPAF</span>
                            </div>
                        </div>
                        <div class="lg:hidden bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-2 rounded-full text-sm font-medium shadow-md">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>Espace Agent</span>
                            </div>
                        </div>
                        <div class="hidden xl:block text-xs text-gray-400 font-mono bg-gray-50 px-2 py-1 rounded border">
                            v2.1.0
                        </div>
                        <!-- Cloche de notifications modernisée -->
                        <div class="relative">
                            <button class="relative focus:outline-none group" aria-label="Notifications">
                                <div class="bg-white p-3 rounded-full shadow-lg border border-gray-200 group-hover:shadow-xl transition-all duration-200 group-hover:border-blue-300">
                                    <svg class="w-6 h-6 text-blue-600 group-hover:text-blue-700 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                </div>
                                <span class="badge-notif">1</span>
                            </button>
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
import { ref, computed } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import AdminToast from '@/Components/AdminToast.vue';

const sidebarOpen = ref(false);
const expanded = ref(true);
const isRSAgent = computed(() => usePage().props.isRSAgent || false);
const toast = ref(null);
const showLogoutModal = ref(false);
const showUserMenu = ref(false);

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

/**** GLASSMORPHISM & DEPTH ****/
.sidebar-glass {
  background: rgba(30, 64, 175, 0.7);
  backdrop-filter: blur(12px) saturate(1.2);
  box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.18);
  border-right: 1.5px solid rgba(255,255,255,0.12);
}
.header-glass {
  background: rgba(255,255,255,0.85);
  backdrop-filter: blur(8px) saturate(1.1);
  box-shadow: 0 4px 24px 0 rgba(31, 38, 135, 0.10);
}

/**** MICRO-INTERACTIONS ****/
.menu-link {
  transition: background 0.18s, color 0.18s, box-shadow 0.18s;
  position: relative;
}
.menu-link.active {
  background: linear-gradient(90deg, #2563eb 60%, #1e40af 100%);
  color: #fff;
  box-shadow: 0 2px 12px 0 rgba(37,99,235,0.10);
}
.menu-link .active-indicator {
  position: absolute;
  left: 0; top: 50%; transform: translateY(-50%);
  width: 4px; height: 60%; border-radius: 6px;
  background: linear-gradient(180deg, #38bdf8 0%, #6366f1 100%);
  transition: height 0.2s;
}
.menu-link:hover:not(.active) {
  background: rgba(59,130,246,0.10);
  color: #fff;
}
.menu-link:focus {
  outline: 2px solid #2563eb;
  outline-offset: 2px;
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