<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import Stagiaire from '@/Layouts/Stagiaire.vue';

const props = defineProps({
  errors: Object,
  notifications: {
    type: Array,
    default: () => []
  }
});

const form = useForm({
  code_suivi: '',
});

const isSubmitting = ref(false);

const submit = () => {
  isSubmitting.value = true;
  form.post(route('demandes.search'), {
    preserveScroll: true,
    onFinish: () => {
      isSubmitting.value = false;
    },
  });
};

const formatCodeSuivi = (value) => {
  // Formater le code en majuscules et ajouter des espaces pour la lisibilité
  return value.toUpperCase().replace(/(.{2})/g, '$1 ').trim();
};

const handleInputChange = (event) => {
  const value = event.target.value.replace(/\s/g, '').toUpperCase();
  form.code_suivi = value;
  event.target.value = formatCodeSuivi(value);
};
</script>

<template>
  <Head title="Recherche de demande" />
  <Stagiaire :notifications="notifications">
    <template #header>
      <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-4 py-2">
        <div class="max-w-7xl mx-auto">
          <div class="flex items-center space-x-2">
            <div class="flex-shrink-0">
              <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
              </svg>
            </div>
            <div>
              <h2 class="text-2xl font-bold text-white">Recherche de demande</h2>
              <p class="text-indigo-100 mt-1 text-base font-medium">Retrouvez votre demande grâce à votre code de suivi</p>
            </div>
          </div>
        </div>
      </div>
    </template>

    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-indigo-50/30 py-12">
      <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        <!-- Card principale -->
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden border-0 ring-1 ring-black/5">
          <!-- En-tête de la card -->
          <div class="bg-gradient-to-r from-indigo-500 to-blue-500 px-8 py-6">
            <div class="flex items-center space-x-3">
              <div class="bg-white/20 rounded-full p-2">
                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
              </div>
              <div>
                <h1 class="text-xl font-bold text-white">Rechercher votre demande</h1>
                <p class="text-indigo-100 text-sm">Saisissez votre code de suivi pour accéder aux détails</p>
              </div>
            </div>
          </div>

          <!-- Contenu du formulaire -->
          <div class="p-8">
            <form @submit.prevent="submit" class="space-y-8">
              <!-- Champ de saisie -->
              <div class="space-y-2">
                <label for="code_suivi" class="block text-sm font-semibold text-gray-900">
                  Code de suivi
                  <span class="text-red-500">*</span>
                </label>
                
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                  </div>
                  <input
                    id="code_suivi"
                    type="text"
                    required
                    :disabled="isSubmitting"
                    @input="handleInputChange"
                    placeholder="Ex: AB 12 CD 34"
                    class="block w-full pl-12 pr-4 py-4 text-lg font-mono rounded-xl border-2 border-gray-200 shadow-sm transition-all duration-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20 disabled:bg-gray-50 disabled:text-gray-500 hover:border-gray-300"
                  />
                </div>

                <!-- Messages d'erreur et d'aide -->
                <div class="mt-3">
                  <p v-if="errors && errors.code_suivi" class="flex items-center text-sm text-red-600 bg-red-50 px-3 py-2 rounded-lg">
                    <svg class="h-4 w-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ errors.code_suivi }}
                  </p>
                  <p v-else class="flex items-center text-sm text-gray-600 bg-blue-50 px-3 py-2 rounded-lg">
                    <svg class="h-4 w-4 mr-2 flex-shrink-0 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    Saisissez le code de suivi reçu par email (8 caractères alphanumériques)
                  </p>
                </div>
              </div>

              <!-- Bouton de soumission -->
              <div>
                <button
                  type="submit"
                  :disabled="isSubmitting || !form.code_suivi"
                  class="group relative w-full flex justify-center items-center py-4 px-6 text-base font-semibold rounded-xl text-white bg-gradient-to-r from-indigo-600 to-blue-600 shadow-lg transition-all duration-200 hover:from-indigo-700 hover:to-blue-700 hover:shadow-xl hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-indigo-500/50 disabled:from-gray-400 disabled:to-gray-500 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-md"
                >
                  <span class="absolute left-0 inset-y-0 flex items-center pl-6">
                    <svg v-if="isSubmitting" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <svg v-else class="h-5 w-5 text-white group-hover:text-white transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                  </span>
                  {{ isSubmitting ? 'Recherche en cours...' : 'Rechercher ma demande' }}
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Section d'aide -->
        <div class="mt-8 bg-white shadow-lg rounded-2xl overflow-hidden border-0 ring-1 ring-black/5">
          <div class="bg-gradient-to-r from-slate-50 to-indigo-50 px-8 py-6 border-b border-gray-100">
            <div class="flex items-center space-x-3">
              <div class="bg-indigo-100 rounded-full p-2">
                <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
              <div>
                <h2 class="text-lg font-bold text-gray-900">Centre d'aide</h2>
                <p class="text-sm text-gray-600">Tout ce que vous devez savoir sur le code de suivi</p>
              </div>
            </div>
          </div>

          <div class="p-8 space-y-6">
            <!-- Étapes explicatives -->
            <div class="grid md:grid-cols-2 gap-6">
              <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
                <div class="flex items-start space-x-4">
                  <div class="bg-blue-500 text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold flex-shrink-0">1</div>
                  <div>
                    <h3 class="font-semibold text-gray-900 mb-2 text-lg">Qu'est-ce que le code de suivi ?</h3>
                    <p class="text-base text-gray-700">
                      C'est un identifiant unique de 8 caractères (lettres et chiffres) généré automatiquement lors de votre demande.
                    </p>
                  </div>
                </div>
              </div>

              <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-6 border border-green-100">
                <div class="flex items-start space-x-4">
                  <div class="bg-green-500 text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold flex-shrink-0">2</div>
                  <div>
                    <h3 class="font-semibold text-gray-900 mb-2 text-lg">Où le trouver ?</h3>
                    <p class="text-base text-gray-700">
                      Vérifiez votre boîte email (y compris les spams) pour l'email de confirmation envoyé après votre demande.
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Contact -->
            <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl p-6 border border-amber-200">
              <div class="flex items-start space-x-4">
                <div class="bg-amber-500 text-white rounded-full p-2 flex-shrink-0">
                  <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                  </svg>
                </div>
                <div class="flex-1">
                  <h3 class="font-semibold text-gray-900 mb-2 text-lg">Code de suivi perdu ?</h3>
                  <p class="text-base text-gray-700 mb-3">
                    Pas de panique ! Contactez notre service des stages qui pourra vous aider à retrouver votre demande.
                  </p>
                  <div class="flex items-center space-x-2">
                    <svg class="h-4 w-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <a href="mailto:stages@example.com" class="text-indigo-600 hover:text-indigo-700 font-medium text-base transition-colors duration-200">
                      stages@example.com
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Stagiaire>
</template>

<style scoped>
/* Animations personnalisées */
@keyframes slideInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.group:hover .group-hover\:animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Amélioration du focus pour l'accessibilité */
input:focus {
  outline: none;
}

/* Animation douce pour les transitions */
* {
  transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}
</style>