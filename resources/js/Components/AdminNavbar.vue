<template>
    <nav class="admin-navbar">
        <div class="navbar-left">
            <button @click="$emit('toggle-mobile-menu')" class="mobile-menu-btn">
                <i class="fas fa-bars"></i>
            </button>
            <h1 class="platform-title">GESTION DES STAGES</h1>
        </div>
        
        <div class="navbar-center">
            <h2 class="dashboard-title">
                <i class="fas fa-chart-line"></i>
                Tableau de bord administrateur
            </h2>
        </div>
        
        <div class="navbar-right">
            <div class="navbar-actions">
                <button class="navbar-btn">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </button>
                <button class="navbar-btn">
                    <i class="fas fa-cog"></i>
                </button>
                <div class="navbar-profile">
                    <img :src="avatar" :alt="username" class="navbar-avatar">
                    <span class="navbar-username">{{ username }}</span>
                </div>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const username = computed(() => page.props.auth?.user?.name || '');
const avatar = computed(() => page.props.auth?.user?.avatar || '/default-avatar.png');
</script>

<style scoped>
.admin-navbar {
    height: 70px;
    background-color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 2rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    position: sticky;
    top: 0;
    z-index: 20;
}

.navbar-left {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.mobile-menu-btn {
    display: none;
    background: none;
    border: none;
    color: #1a237e;
    font-size: 1.25rem;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 0.375rem;
    transition: all 0.2s;
}

.mobile-menu-btn:hover {
    background-color: #f3f4f6;
}

.platform-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1a237e;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    letter-spacing: 0.5px;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    background: linear-gradient(to right, #f3f4f6, transparent);
}

.navbar-center {
    flex: 1;
    display: flex;
    justify-content: center;
}

.dashboard-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    background-color: #f8fafc;
}

.navbar-right {
    display: flex;
    align-items: center;
}

.navbar-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.navbar-btn {
    position: relative;
    background: none;
    border: none;
    color: #64748b;
    padding: 0.5rem;
    border-radius: 0.375rem;
    cursor: pointer;
    transition: all 0.2s;
}

.navbar-btn:hover {
    background-color: #f3f4f6;
    color: #1a237e;
}

.notification-badge {
    position: absolute;
    top: 0;
    right: 0;
    background-color: #ef4444;
    color: white;
    font-size: 0.75rem;
    padding: 0.125rem 0.375rem;
    border-radius: 9999px;
    transform: translate(25%, -25%);
}

.navbar-profile {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    background-color: #f8fafc;
    cursor: pointer;
    transition: all 0.2s;
}

.navbar-profile:hover {
    background-color: #f3f4f6;
}

.navbar-avatar {
    width: 2rem;
    height: 2rem;
    border-radius: 9999px;
    object-fit: cover;
    border: 2px solid #e2e8f0;
}

.navbar-username {
    font-size: 0.875rem;
    font-weight: 500;
    color: #64748b;
}

@media (max-width: 768px) {
    .admin-navbar {
        padding: 0 1rem;
    }

    .mobile-menu-btn {
        display: block;
    }

    .navbar-center {
        display: none;
    }

    .navbar-profile {
        padding: 0.5rem;
    }

    .navbar-username {
        display: none;
    }

    .navbar-actions {
        gap: 0.5rem;
    }

    .platform-title {
        font-size: 1.2rem;
    }
}
</style> 