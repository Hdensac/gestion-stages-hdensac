<template>
    <div class="container mx-auto px-4 py-8">
        <!-- Section des actions -->
        <div class="mt-8 bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Actions</h2>
            
            <!-- Bouton de confirmation de fin de stage -->
            <div v-if="stage.statut === 'En cours' && new Date(stage.date_fin) <= new Date() && !stage.termine_par_ms" class="mt-4">
                <button
                    @click="confirmerFinStage"
                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md"
                >
                    Confirmer la fin du stage
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useToast } from 'vue-toast-notification';
import axios from 'axios';
import { useRouter } from 'vue-router';

const router = useRouter();
const toast = useToast();
const stage = ref(props.stage);

const confirmerFinStage = async () => {
    if (!confirm('Êtes-vous sûr de vouloir confirmer la fin de ce stage ?')) {
        return;
    }

    try {
        const response = await axios.post(route('ms.stages.confirmer-fin', stage.value.id));
        if (response.data.success) {
            toast.success(response.data.message);
            // Recharger les données du stage
            await fetchStage();
        }
    } catch (error) {
        toast.error(error.response?.data?.message || 'Une erreur est survenue');
    }
};

const fetchStage = async () => {
    try {
        const response = await axios.get(route('ms.stages.show', stage.value.id));
        stage.value = response.data.stage;
    } catch (error) {
        toast.error('Erreur lors du chargement des données du stage');
    }
};
</script> 