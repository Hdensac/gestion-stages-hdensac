<script setup>
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

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
    <Head title="Mon Profil" />

    <ProfileLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-900 border-b-2 border-[#2962B4] pb-2 inline-block">
                    Mon Profil
            </h2>
            </div>
        </template>

        <div class="py-12 bg-gray-50">
            <div class="mx-auto max-w-4xl space-y-6 sm:px-6 lg:px-8">
                <!-- Section d'informations du profil avec style amélioré -->
                <div class="bg-white p-8 shadow-lg sm:rounded-lg transition-all duration-300 hover:shadow-xl border border-gray-100">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="w-full"
                    />
                </div>

                <!-- Section de mise à jour du mot de passe -->
                <div class="bg-white p-8 shadow-lg sm:rounded-lg transition-all duration-300 hover:shadow-xl border border-gray-100">
                    <UpdatePasswordForm class="w-full" />
                </div>

                <!-- Section de suppression du compte -->
                <div class="bg-white p-8 shadow-lg sm:rounded-lg transition-all duration-300 hover:shadow-xl border border-gray-100">
                    <DeleteUserForm class="w-full" />
                </div>
            </div>
        </div>
    </ProfileLayout>
</template>

<style scoped>
.bg-gradient {
    background: linear-gradient(to bottom right, #2962B4, #1E4B8F);
}
</style>
