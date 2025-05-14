<template>
    <div class="admin-layout">
        <AdminSidebar @sidebar-toggle="toggleSidebar" />
        <div class="admin-content" :class="{ 'sidebar-collapsed': isCollapsed }">
            <AdminNavbar @toggle-mobile-menu="toggleMobileSidebar" />
            <main class="admin-main">
                <slot></slot>
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import AdminSidebar from '@/Components/AdminSidebar.vue'
import AdminNavbar from '@/Components/AdminNavbar.vue'

const isCollapsed = ref(false)
const isMobileSidebarOpen = ref(false)

const toggleSidebar = (value) => {
    isCollapsed.value = value
}

const toggleMobileSidebar = () => {
    isMobileSidebarOpen.value = !isMobileSidebarOpen.value
}

// Récupérer les informations de l'utilisateur
const page = usePage()
const userName = computed(() => page.props.auth?.user?.name || '')
const userAvatar = computed(() => page.props.auth?.user?.avatar || '/default-avatar.png')
</script>

<style scoped>
.admin-layout {
    display: flex;
    min-height: 100vh;
    background-color: #f5f6fa;
}

.admin-content {
    flex: 1;
    margin-left: 260px;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
}

.admin-content.sidebar-collapsed {
    margin-left: 70px;
}

.admin-main {
    flex: 1;
    padding: 2rem;
    transition: all 0.3s ease;
}

@media (max-width: 768px) {
    .admin-content {
        margin-left: 0;
    }

    .admin-main {
        padding: 1rem;
    }
}
</style> 