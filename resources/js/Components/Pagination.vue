<template>
    <div v-if="links.length > 3" class="-mb-1 flex flex-wrap">
        <template v-for="(link, key) in links" :key="key">
            <div
                v-if="link.url === null"
                class="mb-1 mr-1 rounded border border-indigo-200 bg-indigo-50/50 px-3 py-2 text-sm font-medium text-indigo-400 cursor-not-allowed"
            >
                <span v-if="link.label === '&laquo; Previous'">« Précédent</span>
                <span v-else-if="link.label === 'Next &raquo;'">Suivant »</span>
                <span v-else>{{ link.label }}</span>
            </div>

            <button
                v-else
                @click="handleClick(link)"
                class="mb-1 mr-1 rounded border px-3 py-2 text-sm font-medium transition-colors duration-200"
                :class="{
                    'border-indigo-500 bg-indigo-100 text-indigo-600 shadow-sm': link.active,
                    'border-indigo-200 bg-white text-indigo-600 hover:bg-indigo-50 shadow-sm': !link.active
                }"
            >
                <span v-if="link.label === '&laquo; Previous'">« Précédent</span>
                <span v-else-if="link.label === 'Next &raquo;'">Suivant »</span>
                <span v-else>{{ link.label }}</span>
            </button>
        </template>
    </div>
</template>

<script setup>
const emit = defineEmits(['change']);

const props = defineProps({
    links: {
        type: Array,
        required: true,
    },
});

function handleClick(link) {
    if (!link.url) return;

    // Extraire le numéro de page de l'URL
    const url = new URL(link.url);
    const page = url.searchParams.get('page') || '1';

    emit('change', page);
}
</script>