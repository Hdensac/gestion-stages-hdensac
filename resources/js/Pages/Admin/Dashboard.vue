<template>
    <AdminLayout>
        <div class="dashboard-container">
            <header class="dashboard-header">
                <h1 class="text-2xl font-semibold">Tableau de bord administrateur</h1>
                <div class="header-actions">
                    <input type="text" placeholder="Rechercher..." class="search-input">
                </div>
            </header>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Utilisateurs</h3>
                        <p class="stat-value">{{ stats.users }}</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Stages</h3>
                        <p class="stat-value">{{ stats.stages }}</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Entreprises</h3>
                        <p class="stat-value">{{ stats.entreprises }}</p>
                    </div>
                </div>
            </div>

            <div class="recent-activity">
                <h2 class="text-xl font-semibold mb-4">Activité récente</h2>
                <div class="activity-list">
                    <div v-for="activity in recentActivities" :key="activity.id" class="activity-item">
                        <div class="activity-icon">
                            <i :class="activity.icon"></i>
                        </div>
                        <div class="activity-content">
                            <p class="activity-text">{{ activity.description }}</p>
                            <span class="activity-time">{{ activity.time }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'

// Données d'exemple - à remplacer par des données réelles de l'API
const stats = ref({
    users: 150,
    stages: 45,
    entreprises: 28
})

const recentActivities = ref([
    {
        id: 1,
        icon: 'fas fa-user-plus',
        description: 'Nouvel utilisateur inscrit',
        time: 'Il y a 5 minutes'
    },
    {
        id: 2,
        icon: 'fas fa-briefcase',
        description: 'Nouveau stage ajouté',
        time: 'Il y a 15 minutes'
    },
    {
        id: 3,
        icon: 'fas fa-building',
        description: 'Nouvelle entreprise partenaire',
        time: 'Il y a 1 heure'
    }
])
</script>

<style scoped>
.dashboard-container {
    max-width: 1200px;
    margin: 0 auto;
}

.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.search-input {
    padding: 0.5rem 1rem;
    border: 1px solid #e2e8f0;
    border-radius: 0.375rem;
    width: 300px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
}

.stat-icon {
    background: #1a237e;
    color: white;
    width: 48px;
    height: 48px;
    border-radius: 0.375rem;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
}

.stat-icon i {
    font-size: 1.5rem;
}

.stat-content h3 {
    color: #64748b;
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1e293b;
}

.recent-activity {
    background: white;
    padding: 1.5rem;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.activity-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.activity-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    border-radius: 0.375rem;
    background: #f8fafc;
}

.activity-icon {
    width: 40px;
    height: 40px;
    background: #1a237e;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
}

.activity-content {
    flex: 1;
}

.activity-text {
    color: #1e293b;
    margin-bottom: 0.25rem;
}

.activity-time {
    color: #64748b;
    font-size: 0.875rem;
}

@media (max-width: 768px) {
    .dashboard-header {
        flex-direction: column;
        gap: 1rem;
    }

    .search-input {
        width: 100%;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }
}
</style> 