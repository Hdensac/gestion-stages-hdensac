<script setup>

import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head, router } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Profile" />

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
        <!-- En-tête avec bouton retour -->
        <div class="bg-white/80 backdrop-blur-sm border-b border-slate-200/50 sticky top-0 z-10">
            <div class="mx-auto max-w-4xl px-6 py-4">
                <div class="flex items-center gap-4">
                    <!-- Bouton retour -->
                    <button @click="router.visit(route('dashboard'))"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-medium transition-all duration-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Retour
                    </button>

                    <!-- Titre et informations -->
                    <div class="flex items-center gap-3 flex-1">
                        <div class="bg-gradient-to-br from-indigo-600 via-purple-700 to-indigo-600 text-white rounded-xl w-12 h-12 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h1 class="text-2xl font-black bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent leading-tight">
                                Mon Profil
                            </h1>
                            <p class="text-sm text-slate-600 mt-1 font-medium">
                                Gérez vos informations personnelles et paramètres de sécurité
                            </p>
                            <div class="flex items-center gap-3 mt-2">
                                <div class="flex items-center gap-2 bg-indigo-100 px-2 py-1 rounded-full">
                                    <div class="w-1.5 h-1.5 bg-indigo-500 rounded-full animate-pulse"></div>
                                    <span class="text-xs font-bold text-indigo-700">Profil actif</span>
                                </div>
                                <div class="text-xs text-slate-500 font-mono">
                                    {{ new Date().toLocaleTimeString('fr-FR') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-8">
            <div class="mx-auto max-w-4xl space-y-8 sm:px-6 lg:px-8">
                <!-- Section Informations personnelles -->
                <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl border-2 border-slate-200/50 overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-600 to-purple-700 py-6 px-8">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-white/20 rounded-xl">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-white">Informations personnelles</h2>
                        </div>
                    </div>
                    <div class="p-8">
                        <UpdateProfileInformationForm
                            :must-verify-email="mustVerifyEmail"
                            :status="status"
                            class="w-full"
                        />
                    </div>
                </div>

                <!-- Section Sécurité -->
                <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl border-2 border-slate-200/50 overflow-hidden">
                    <div class="bg-gradient-to-r from-emerald-600 to-teal-700 py-6 px-8">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-white/20 rounded-xl">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-white">Sécurité du compte</h2>
                        </div>
                    </div>
                    <div class="p-8">
                        <UpdatePasswordForm class="w-full" />
                    </div>
                </div>

                <!-- Section Danger Zone -->
                <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl border-2 border-red-200/50 overflow-hidden">
                    <div class="bg-gradient-to-r from-red-600 to-rose-700 py-6 px-8">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-white/20 rounded-xl">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-white">Zone de danger</h2>
                        </div>
                    </div>
                    <div class="p-8">
                        <DeleteUserForm class="w-full" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
