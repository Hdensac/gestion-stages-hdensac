<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import AgentDPAF from '@/Layouts/AgentDPAF.vue';
import { ref } from 'vue';
import AdminToast from '@/Components/AdminToast.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    demande: Object,
    structures: Array,
    membres: Array
});

const toast = ref(null);

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('fr-FR');
};

// Fonction améliorée pour les couleurs de statut - plus vives
const getStatusColor = (statut) => {
    if (!statut) return 'text-slate-800 bg-gradient-to-r from-slate-100 to-gray-100 border-2 border-slate-300 shadow-lg shadow-slate-200/50';
    // Normalisation : trim, minuscule, suppression des accents
    const normalize = (str) => str.normalize('NFD').replace(/\p{Diacritic}/gu, '').trim().toLowerCase();
    const cleanStatut = normalize(statut);
    console.log('Statut reçu pour coloration:', statut);
    switch (cleanStatut) {
        case 'en attente':
            return 'text-amber-800 bg-gradient-to-r from-amber-100 to-orange-100 border-2 border-amber-300 shadow-lg shadow-amber-200/50';
        case 'en cours':
            return 'text-blue-800 bg-gradient-to-r from-blue-100 to-sky-100 border-2 border-blue-300 shadow-lg shadow-blue-200/50';
        case 'approuvee':
        case 'acceptée':
        case 'acceptee':
            return 'text-emerald-800 bg-gradient-to-r from-emerald-100 to-green-100 border-2 border-emerald-300 shadow-lg shadow-emerald-200/50';
        case 'refusee':
        case 'refusée':
            return 'text-red-800 bg-gradient-to-r from-red-100 to-rose-100 border-2 border-red-300 shadow-lg shadow-red-200/50';
        case 'a reaffecter':
        case 'a réaffecter':
            return 'text-orange-800 bg-gradient-to-r from-orange-100 to-amber-100 border-2 border-amber-300 shadow-lg shadow-orange-200/50';
        default:
            return 'text-slate-800 bg-gradient-to-r from-slate-100 to-gray-100 border-2 border-slate-300 shadow-lg shadow-slate-200/50';
    }
};

const showAffectationModal = ref(false);
const selectedStructure = ref(props.demande.structure_id || '');

const openAffectationModal = () => {
    showAffectationModal.value = true;
};

const closeAffectationModal = () => {
    showAffectationModal.value = false;
};

const submitAffectation = () => {
    if (!selectedStructure.value) return;

    router.post(route('agent.demandes.affecter', props.demande.id), {
        structure_id: selectedStructure.value
    }, {
        onSuccess: () => {
            closeAffectationModal();
            if (toast.value) {
                toast.value.addToast({
                    type: 'success',
                    title: 'Affectation réussie',
                    message: `La demande '${props.demande.code_suivi}' a été affectée à la structure '${props.structures.find(s => s.id === selectedStructure.value)?.libelle}' avec succès.`
                });
            }
        }
    });
};

const showRefusDefModal = ref(false);
const motifRefusDef = ref('');

const openRefusDefModal = () => {
    motifRefusDef.value = '';
    showRefusDefModal.value = true;
};
const closeRefusDefModal = () => {
    showRefusDefModal.value = false;
};
const submitRefusDef = () => {
    if (!motifRefusDef.value.trim()) {
        alert('Veuillez indiquer un motif de refus définitif.');
        return;
    }
    router.post(route('agent.demandes.refuseDefinitivement', props.demande.id), {
        motif_refus: motifRefusDef.value
    }, {
        onSuccess: () => {
            closeRefusDefModal();
            if (toast.value) {
                toast.value.addToast({
                    type: 'success',
                    title: 'Succès',
                    message: 'La demande a été refusée définitivement.'
                });
            }
        }
    });
};

const submit = (action) => {
    if (!confirm(`Êtes-vous sûr de vouloir ${action === 'approve' ? 'approuver' : 'refuser'} cette demande ?`)) {
        return;
    }

    router.post(route(`agent.demandes.${action}`, props.demande.id));
};

function getInitials(nom, prenom) {
    const n = nom ? nom.charAt(0) : '';
    const p = prenom ? prenom.charAt(0) : '';
    return (n + p).toUpperCase() || '?';
}
</script>

<template>
    <Head title="Détails de la demande" />
    <AgentDPAF>
        <template #header>
            <h2 class="text-xl font-semibold text-slate-800">Détails de la demande</h2>
        </template>

        <!-- Arrière-plan grisé -->
        <div class="min-h-screen bg-gradient-to-br from-gray-100 via-slate-100 to-gray-200">
            <div class="py-12">
                <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div class="overflow-hidden bg-white rounded-3xl shadow-xl border-2 border-slate-200/50">
                        <div class="p-8">
                            <!-- Header amélioré avec plus de couleurs -->
                            <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="p-4 bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-600 rounded-2xl shadow-xl shadow-blue-500/30">
                                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h1 class="text-3xl font-black bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Détails de la demande #{{ demande.code_suivi }}</h1>
                                        <p class="text-slate-600 font-mono text-base mt-1 bg-slate-200/70 px-3 py-1 rounded-lg inline-block">{{ demande.code_suivi }}</p>
                                    </div>
                                </div>
                                <Link :href="route('agent.demandes')" 
                                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-slate-600 to-slate-700 text-white rounded-xl hover:from-slate-700 hover:to-slate-800 transition-all duration-300 shadow-lg hover:shadow-xl font-medium text-base transform hover:scale-105">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                                    </svg>
                                    Retour
                                </Link>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <!-- Informations sur la demande - plus coloré -->
                                <div class="bg-gradient-to-br from-blue-50 via-indigo-50 to-blue-50 p-8 rounded-3xl shadow-xl border-2 border-blue-200/50">
                                    <div class="flex items-center gap-3 mb-6">
                                        <div class="p-2 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <h2 class="text-xl font-bold text-blue-900">Informations de la demande</h2>
                                    </div>
                                    
                                    <div class="space-y-5">
                                        <div class="bg-white/80 p-4 rounded-2xl border border-blue-200/50 shadow-sm">
                                            <span class="text-sm text-blue-700 font-semibold block mb-2">Statut</span>
                                            <span class="px-4 py-2 text-sm font-bold rounded-2xl inline-block" :class="getStatusColor(demande.statut)">
                                                {{ demande.statut }}
                                            </span>
                                        </div>
                                        
                                        <div class="bg-white/80 p-4 rounded-2xl border border-blue-200/50 shadow-sm">
                                            <span class="text-sm text-blue-700 font-semibold block mb-2">Structure</span>
                                            <div class="flex flex-col gap-3">
                                                <div class="font-bold text-slate-800 text-lg">
                                                    {{ props.demande.structure ? props.demande.structure.libelle : 'Non spécifiée' }}
                                                </div>
                                                <button 
                                                    v-if="demande.statut === 'En attente'"
                                                    @click="openAffectationModal"
                                                    class="px-5 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl font-bold flex items-center gap-2 transform hover:scale-105"
                                                >
                                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"/>
                                                    </svg>
                                                    Affecter une structure
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div class="bg-white/80 p-4 rounded-2xl border border-blue-200/50 shadow-sm">
                                            <span class="text-sm text-blue-700 font-semibold block mb-1">Type de stage</span>
                                            <span class="font-bold text-slate-800">{{ demande.type }}</span>
                                        </div>
                                        
                                        <div class="bg-white/80 p-4 rounded-2xl border border-blue-200/50 shadow-sm">
                                            <span class="text-sm text-blue-700 font-semibold block mb-1">Nature</span>
                                            <span class="font-bold text-slate-800">{{ demande.nature }}</span>
                                        </div>
                                        
                                        <div class="bg-white/80 p-4 rounded-2xl border border-blue-200/50 shadow-sm">
                                            <span class="text-sm text-blue-700 font-semibold block mb-1">Période</span>
                                            <span class="font-bold text-slate-800">Du {{ formatDate(demande.date_debut) }} au {{ formatDate(demande.date_fin) }}</span>
                                        </div>
                                        
                                        <div class="bg-white/80 p-4 rounded-2xl border border-blue-200/50 shadow-sm">
                                            <span class="text-sm text-blue-700 font-semibold block mb-1">Date de soumission</span>
                                            <span class="font-bold text-slate-800">{{ formatDate(demande.created_at) }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Informations du stagiaire - plus coloré -->
                                <div class="bg-gradient-to-br from-purple-50 via-pink-50 to-purple-50 p-8 rounded-3xl shadow-xl border-2 border-purple-200/50">
                                    <div class="flex items-center gap-3 mb-6">
                                        <div class="p-2 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl shadow-lg">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <h2 class="text-xl font-bold text-purple-900">Informations du stagiaire</h2>
                                    </div>
                                    
                                    <div class="space-y-4">
                                        <div class="bg-white/80 p-4 rounded-2xl border border-purple-200/50 shadow-sm">
                                            <span class="text-sm text-purple-700 font-semibold block mb-1">Nom complet</span>
                                            <div class="mb-6 flex items-center">
                                                <!-- Condition pour afficher l'image si visage_path existe, sinon afficher les initiales -->
                                                <div class="h-16 w-16 rounded-full flex items-center justify-center text-xl font-bold shadow-md mr-4 overflow-hidden bg-blue-600 text-white">
                                                    <img v-if="demande.visage_path"
                                                         :src="'/storage/' + demande.visage_path"
                                                         alt="Photo du stagiaire"
                                                         class="object-cover w-full h-full">
                                                    <div v-else class="w-full h-full flex items-center justify-center">
                                                        {{ getInitials(demande.stagiaire?.user?.nom, demande.stagiaire?.user?.prenom) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <h4 class="text-lg font-semibold text-slate-800">{{ demande.stagiaire?.user?.nom }} {{ demande.stagiaire?.user?.prenom }}</h4>
                                                    <p class="text-sm text-slate-600">{{ demande.stagiaire?.user?.email }}</p>
                                                </div>
                                        </div>
                                        </div>
                                        
                                        <div class="bg-white/80 p-4 rounded-2xl border border-purple-200/50 shadow-sm">
                                            <span class="text-sm text-purple-700 font-semibold block mb-1">Téléphone</span>
                                            <span class="font-bold text-slate-800">{{ demande.stagiaire?.user?.telephone }}</span>
                                        </div>
                                        
                                        <div class="bg-white/80 p-4 rounded-2xl border border-purple-200/50 shadow-sm">
                                            <span class="text-sm text-purple-700 font-semibold block mb-1">Université</span>
                                            <span class="font-bold text-slate-800">{{ demande.stagiaire?.universite }}</span>
                                        </div>
                                        
                                        <div class="bg-white/80 p-4 rounded-2xl border border-purple-200/50 shadow-sm">
                                            <span class="text-sm text-purple-700 font-semibold block mb-1">Filière</span>
                                            <span class="font-bold text-slate-800">{{ demande.stagiaire?.filiere }}</span>
                                        </div>
                                        
                                        <div class="bg-white/80 p-4 rounded-2xl border border-purple-200/50 shadow-sm">
                                            <span class="text-sm text-purple-700 font-semibold block mb-1">Niveau d'étude</span>
                                            <span class="font-bold text-slate-800">{{ demande.stagiaire?.niveau_etude }}</span>
                                        </div>
                                    </div>

                                    <!-- Membres du groupe - coloré -->
                                    <div v-if="demande.nature === 'Groupe' && membres && membres.length > 0" class="mt-8">
                                        <div class="flex items-center gap-3 mb-4">
                                            <div class="p-2 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl shadow-lg">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                            </div>
                                            <h3 class="text-lg font-bold text-emerald-800">Autres membres du groupe</h3>
                                        </div>
                                        <ul class="space-y-4">
                                            <li v-for="membre in membres" :key="membre.id" 
                                                class="p-5 bg-gradient-to-r from-emerald-100 to-teal-100 rounded-2xl border-2 border-emerald-200/50 shadow-lg flex flex-col md:flex-row md:items-center gap-4">
                                                <div class="font-bold text-emerald-800 text-lg">{{ membre.user?.nom }} {{ membre.user?.prenom }}</div>
                                                <div class="text-sm text-emerald-700 font-medium">Email : {{ membre.user?.email }}</div>
                                                <div class="text-sm text-emerald-700 font-medium">Téléphone : {{ membre.user?.telephone }}</div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Documents attachés - plus coloré -->
                            <div class="mt-8 bg-gradient-to-br from-green-50 via-emerald-50 to-green-50 p-8 rounded-3xl shadow-xl border-2 border-green-200/50">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="p-2 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl shadow-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <h2 class="text-xl font-bold text-green-900">Documents attachés</h2>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <!-- Document principal (CV ou Lettre de recommandation) -->
                                    <div v-if="demande.lettre_cv_path" class="bg-white/80 p-6 rounded-2xl border-2 border-green-200/50 shadow-lg">
                                        <span class="text-sm text-green-700 font-semibold block mb-4">{{ demande.type === 'Académique' ? 'Lettre de recommandation' : 'CV' }}</span>
                                        <a :href="'/storage/' + demande.lettre_cv_path"
                                            target="_blank"
                                            class="flex items-center justify-center gap-2 px-5 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 shadow-lg hover:shadow-xl font-bold transform hover:scale-105">
                                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                            Télécharger le {{ demande.type === 'Académique' ? 'document' : 'CV' }}
                                        </a>
                                    </div>
                                </div>

                                <!-- Message si aucun document -->
                                <div v-if="!demande.lettre_cv_path" 
                                    class="text-center bg-white/80 p-8 rounded-2xl border-2 border-green-200/50 shadow-lg">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="p-3 bg-gradient-to-br from-gray-400 to-gray-500 rounded-xl shadow-lg">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <span class="text-gray-600 font-medium text-lg">Aucun document n'a été attaché à cette demande</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions sur la demande - commenté mais amélioré visuellement -->
                            <div v-if="demande.statut === 'A réaffecter'" class="flex gap-4 mt-8">
                                <button @click="openAffectationModal" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-bold flex items-center gap-2 shadow-lg hover:scale-105">
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"/></svg>
                                    Réaffecter à une structure
                                    </button>
                                <button @click="openRefusDefModal" class="px-6 py-3 bg-gradient-to-r from-red-600 to-rose-600 text-white rounded-xl font-bold flex items-center gap-2 shadow-lg hover:scale-105">
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                    Refuser définitivement
                                    </button>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal d'affectation - amélioré -->
        <div v-if="showAffectationModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
            <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full mx-4 border-2 border-slate-200/50">
                <div class="px-6 py-5 border-b-2 border-slate-100 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-t-3xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-blue-900">
                                Affecter une structure
                            </h3>
                        </div>
                        <button @click="closeAffectationModal" class="text-gray-400 hover:text-gray-600 p-2 rounded-xl hover:bg-gray-100 transition-all duration-200">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="px-6 py-6">
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-slate-700 mb-3">Structure</label>
                        <select 
                            v-model="selectedStructure"
                            class="w-full px-4 py-3 text-base border-2 border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 rounded-xl font-medium bg-white shadow-sm"
                        >
                            <option value="">Sélectionner une structure</option>
                            <option v-for="structure in structures" :key="structure.id" :value="structure.id">
                                {{ structure.libelle }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="px-6 py-5 bg-gradient-to-r from-slate-50 to-gray-50 flex justify-end gap-3 rounded-b-3xl">
                    <button 
                        @click="closeAffectationModal"
                        class="px-5 py-3 bg-gradient-to-r from-gray-200 to-gray-300 text-gray-700 rounded-xl hover:from-gray-300 hover:to-gray-400 transition-all duration-300 font-bold transform hover:scale-105"
                    >
                        Annuler
                    </button>
                    <button 
                        @click="submitAffectation"
                        :disabled="!selectedStructure"
                        :class="[
                            'px-5 py-3 rounded-xl transition-all duration-300 flex items-center gap-2 font-bold transform',
                            selectedStructure 
                                ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white hover:from-blue-700 hover:to-indigo-700 shadow-lg hover:shadow-xl hover:scale-105' 
                                : 'bg-gray-300 text-gray-500 cursor-not-allowed'
                        ]"
                    >
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 00-1 1v5H4a1 1 0 100 2h5v5a1 1 0 102 0v-5h5a1 1 0 100-2h-5V4a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        Affecter
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal refus définitif -->
        <Modal :show="showRefusDefModal" @close="closeRefusDefModal">
            <div class="p-8 bg-white/95 rounded-3xl shadow-2xl border-2 border-slate-200/50 max-w-md mx-auto">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                    <div class="p-3 bg-gradient-to-br from-red-500 to-rose-600 rounded-2xl shadow-lg">
                        <svg class="h-6 w-6 text-white" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </div>
                    Refuser définitivement la demande
                </h2>
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-3 uppercase tracking-wide">Motif du refus définitif</label>
                    <textarea v-model="motifRefusDef" class="w-full rounded-2xl border-2 border-gray-200 shadow-sm focus:border-red-500 focus:ring-2 focus:ring-red-200 focus:ring-opacity-50 text-gray-900 font-medium p-4" rows="4" placeholder="Saisir le motif du refus définitif..."></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button @click="closeRefusDefModal" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">Annuler</button>
                    <button @click="submitRefusDef" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Confirmer le refus</button>
                </div>
            </div>
        </Modal>
    </AgentDPAF>
    
    <!-- Composant Toast pour les notifications -->
    <AdminToast ref="toast" />
</template>