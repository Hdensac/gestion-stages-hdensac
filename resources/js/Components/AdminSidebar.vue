<template>
    <div class="admin-sidebar" :class="{ 'collapsed': isCollapsed }">
        <div class="sidebar-header">
            <ApplicationLogo class="logo" />
            <button @click="toggleSidebar" class="collapse-btn">
                <i :class="isCollapsed ? 'fas fa-chevron-right' : 'fas fa-chevron-left'"></i>
            </button>
        </div>

        <nav class="sidebar-nav">
            <router-link to="/admin/dashboard" class="nav-item" active-class="active">
                <i class="fas fa-home"></i>
                <span v-show="!isCollapsed">Tableau de bord</span>
            </router-link>

            <router-link to="/admin/users" class="nav-item" active-class="active">
                <i class="fas fa-users"></i>
                <span v-show="!isCollapsed">Utilisateurs</span>
            </router-link>

            <router-link to="/admin/stages" class="nav-item" active-class="active">
                <i class="fas fa-briefcase"></i>
                <span v-show="!isCollapsed">Stages</span>
            </router-link>

            <router-link to="/admin/entreprises" class="nav-item" active-class="active">
                <i class="fas fa-building"></i>
                <span v-show="!isCollapsed">Entreprises</span>
            </router-link>

            <router-link to="/admin/settings" class="nav-item" active-class="active">
                <i class="fas fa-cog"></i>
                <span v-show="!isCollapsed">Paramètres</span>
            </router-link>
        </nav>

        <div class="sidebar-footer">
            <div class="user-info" v-show="!isCollapsed">
                <img :src="userAvatar" alt="Avatar" class="avatar">
                <div class="user-details">
                    <span class="user-name">{{ userName }}</span>
                    <span class="user-role">Administrateur</span>
                </div>
            </div>
            <button @click="logout" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span v-show="!isCollapsed">Déconnexion</span>
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import ApplicationLogo from './ApplicationLogo.vue'

const isCollapsed = ref(false)
const userName = ref('')
const userAvatar = ref('/default-avatar.png')

onMounted(() => {
    // Récupérer les informations de l'utilisateur depuis les props Inertia ou le state
    const user = usePage().props.auth.user
    if (user) {
        userName.value = `${user.name}`
        if (user.avatar) {
            userAvatar.value = user.avatar
        }
    }
})

const toggleSidebar = () => {
    isCollapsed.value = !isCollapsed.value
    // Émettre un événement pour informer le layout
    emit('sidebar-toggle', isCollapsed.value)
}

const logout = () => {
    router.post(route('logout'))
}
</script>

<style scoped>
.admin-sidebar {
    width: 260px;
    height: 100vh;
    background: #1a237e;
    color: white;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    position: fixed;
    left: 0;
    top: 0;
}

.admin-sidebar.collapsed {
    width: 70px;
}

.sidebar-header {
    padding: 1rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.logo {
    height: 40px;
    width: auto;
}

.collapse-btn {
    background: transparent;
    border: none;
    color: white;
    cursor: pointer;
    padding: 0.5rem;
}

.sidebar-nav {
    flex: 1;
    padding: 1rem 0;
}

.nav-item {
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem;
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    transition: all 0.3s ease;
}

.nav-item:hover {
    background: rgba(255, 255, 255, 0.1);
    color: white;
}

.nav-item.active {
    background: rgba(255, 255, 255, 0.2);
    color: white;
}

.nav-item i {
    width: 24px;
    text-align: center;
    margin-right: 1rem;
}

.sidebar-footer {
    padding: 1rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.user-info {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
}

.avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    margin-right: 0.75rem;
}

.user-details {
    display: flex;
    flex-direction: column;
}

.user-name {
    font-weight: 600;
    font-size: 0.9rem;
}

.user-role {
    font-size: 0.8rem;
    opacity: 0.8;
}

.logout-btn {
    width: 100%;
    padding: 0.75rem;
    display: flex;
    align-items: center;
    background: rgba(255, 255, 255, 0.1);
    border: none;
    color: white;
    cursor: pointer;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.logout-btn:hover {
    background: rgba(255, 255, 255, 0.2);
}

.logout-btn i {
    margin-right: 0.75rem;
}

@media (max-width: 768px) {
    .admin-sidebar {
        transform: translateX(-100%);
    }

    .admin-sidebar.visible {
        transform: translateX(0);
    }
}
</style> 