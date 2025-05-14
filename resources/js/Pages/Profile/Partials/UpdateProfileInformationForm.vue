<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import axios from 'axios';

const props = defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;
const showSuccessMessage = ref(false);
const showAvatarInfo = ref(false);
const hasChanges = ref(false);
const modifiedFields = ref(new Set());

const initialData = {
    nom: user.nom,
    prenom: user.prenom,
    email: user.email,
    telephone: user.telephone,
    date_de_naissance: user.date_de_naissance,
};

const form = useForm({
    nom: user.nom,
    prenom: user.prenom,
    email: user.email,
    telephone: user.telephone,
    date_de_naissance: user.date_de_naissance,
    avatar: null,
});

const avatarPreview = ref(user.avatar ? `/storage/${user.avatar}` : null);
const isUploading = ref(false);

const selectNewAvatar = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.avatar = file;
        avatarPreview.value = URL.createObjectURL(file);
        modifiedFields.value.add('avatar');
        hasChanges.value = true;
    }
};

const updateField = (fieldName, value) => {
    form[fieldName] = value;
    if (value !== initialData[fieldName]) {
        modifiedFields.value.add(fieldName);
        hasChanges.value = true;
    } else {
        modifiedFields.value.delete(fieldName);
        hasChanges.value = modifiedFields.value.size > 0;
    }
};

const saveChanges = () => {
    if (!hasChanges.value) return;

    // Créer un nouvel objet FormData avec uniquement les champs modifiés
        const formData = new FormData();
    modifiedFields.value.forEach(field => {
        if (field === 'avatar' && form[field] instanceof File) {
            formData.append(field, form[field]);
        } else if (form[field] !== undefined && form[field] !== null) {
            formData.append(field, form[field]);
        }
    });

    // Utiliser Axios directement pour plus de contrôle sur la requête
    axios.post(route('profile.update'), formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
            'X-HTTP-Method-Override': 'PATCH'
        }
    }).then(response => {
        // Mettre à jour les données initiales avec les nouvelles valeurs
        modifiedFields.value.forEach(field => {
            if (field !== 'avatar') {
                initialData[field] = form[field];
            }
        });
            
            showSuccessMessage.value = true;
        hasChanges.value = false;
        modifiedFields.value.clear();
        
            setTimeout(() => {
                showSuccessMessage.value = false;
        }, 2000);
    }).catch(error => {
            if (error.response?.data?.errors) {
            Object.keys(error.response.data.errors).forEach(key => {
                form.setError(key, error.response.data.errors[key]);
            });
        }
    }).finally(() => {
        isUploading.value = false;
    });
};
</script>

<template>
    <section>
        <header class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Informations du profil</h2>
            <p class="text-gray-600 max-w-lg mx-auto">
                Gérez vos informations personnelles et votre photo de profil.
            </p>
        </header>

        <form @submit.prevent="saveChanges" class="space-y-8">
            <!-- Avatar section -->
            <div class="flex flex-col items-center space-y-4">
                <div class="relative group">
                    <div class="w-40 h-40 rounded-full overflow-hidden bg-gradient-to-b from-[#2962B4] to-[#1E4B8F] flex items-center justify-center border-4 border-white shadow-lg relative">
                        <img v-if="avatarPreview" :src="avatarPreview" class="w-full h-full object-cover" alt="Avatar" />
                        <div v-else class="text-white text-5xl font-bold">
                            {{ user?.nom?.charAt(0).toUpperCase() }}
                        </div>
                        
                        <!-- Overlay de chargement -->
                        <div v-if="isUploading" class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                            <svg class="animate-spin h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>

                        <!-- Overlay au survol avec info format -->
                        <div 
                            class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex flex-col items-center justify-center space-y-2"
                            @mouseenter="showAvatarInfo = true"
                            @mouseleave="showAvatarInfo = false"
                        >
                            <span class="text-white text-sm">Modifier la photo</span>
                            <span v-if="showAvatarInfo" class="text-white text-xs text-center px-2">
                                Format recommandé : JPG, PNG<br>Taille max : 1MB
                            </span>
                        </div>
                    </div>
                    <label for="avatar" class="absolute bottom-0 right-0 bg-[#2962B4] text-white rounded-full p-2 cursor-pointer shadow-lg hover:bg-[#1E4B8F] transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                        <input
                            type="file"
                            id="avatar"
                            class="hidden"
                            accept="image/*"
                            @change="selectNewAvatar"
                        />
                    </label>
                </div>
            </div>

            <!-- Message de succès -->
            <div v-if="showSuccessMessage" 
                 class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50"
                 role="alert">
                <div class="flex items-center">
                    <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>Modifications enregistrées avec succès</span>
                </div>
            </div>

            <!-- Grille d'informations personnelles -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <!-- Nom -->
                    <div>
                        <InputLabel for="nom" value="Nom" class="text-gray-700 font-medium" />
                        <div class="relative mt-1">
                <TextInput
                            id="nom"
                    type="text"
                            v-model="form.nom"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2962B4] focus:border-[#2962B4] transition-all duration-200"
                    required
                            placeholder="Votre nom"
                                @input="updateField('nom', $event.target.value)"
                        />
                    </div>
                    <InputError :message="form.errors.nom" class="mt-1" />
                </div>

                    <!-- Email -->
                    <div>
                        <InputLabel for="email" value="Email" class="text-gray-700 font-medium" />
                        <div class="relative mt-1">
                <TextInput
                    id="email"
                    type="email"
                    v-model="form.email"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2962B4] focus:border-[#2962B4] transition-all duration-200"
                        required
                        placeholder="votre@email.com"
                                @input="updateField('email', $event.target.value)"
                    />
                </div>
                <InputError :message="form.errors.email" class="mt-1" />
            </div>

                    <!-- Date de naissance -->
                    <div>
                        <InputLabel for="date_de_naissance" value="Date de naissance" class="text-gray-700 font-medium" />
                        <div class="relative mt-1">
                    <TextInput
                        id="date_de_naissance"
                        type="date"
                        v-model="form.date_de_naissance"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2962B4] focus:border-[#2962B4] transition-all duration-200"
                        required
                                @input="updateField('date_de_naissance', $event.target.value)"
                    />
                </div>
                <InputError :message="form.errors.date_de_naissance" class="mt-1" />
                    </div>
            </div>

                <div class="space-y-6">
                    <!-- Prénom -->
                    <div>
                        <InputLabel for="prenom" value="Prénom" class="text-gray-700 font-medium" />
                        <div class="relative mt-1">
                            <TextInput
                                id="prenom"
                                type="text"
                                v-model="form.prenom"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2962B4] focus:border-[#2962B4] transition-all duration-200"
                                required
                                placeholder="Votre prénom"
                                @input="updateField('prenom', $event.target.value)"
                            />
                        </div>
                        <InputError :message="form.errors.prenom" class="mt-1" />
                    </div>

                    <!-- Téléphone -->
                    <div>
                        <InputLabel for="telephone" value="Téléphone" class="text-gray-700 font-medium" />
                        <div class="relative mt-1">
                            <TextInput
                                id="telephone"
                                type="tel"
                                v-model="form.telephone"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2962B4] focus:border-[#2962B4] transition-all duration-200"
                                required
                                placeholder="Ex: +229 01-23-45-67"
                                @input="updateField('telephone', $event.target.value)"
                            />
                        </div>
                        <InputError :message="form.errors.telephone" class="mt-1" />
                    </div>
                </div>
            </div>

            <!-- Bouton de sauvegarde -->
            <div class="flex justify-end mt-6">
                <PrimaryButton
                    type="submit"
                    :disabled="!hasChanges || form.processing"
                    :class="{ 
                        'opacity-50 cursor-not-allowed': !hasChanges || form.processing,
                        'bg-[#2962B4] hover:bg-[#1E4B8F]': hasChanges && !form.processing 
                    }"
                >
                    <span v-if="form.processing" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Enregistrement...
                    </span>
                    <span v-else>Enregistrer les modifications</span>
                </PrimaryButton>
            </div>
        </form>
    </section>
</template>

<style scoped>
.form-input:focus {
    box-shadow: 0 0 0 2px rgba(41, 98, 180, 0.5);
}
</style>
