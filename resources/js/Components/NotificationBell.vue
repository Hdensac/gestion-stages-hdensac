<template>
  <div class="relative inline-block text-left">
    <button @click="toggleDropdown" class="relative focus:outline-none">
      <svg class="w-7 h-7 text-blue-700 hover:text-blue-900 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
      </svg>
      <span v-if="unreadCount > 0" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full px-1.5 py-0.5">{{ unreadCount }}</span>
    </button>
    <div v-if="showDropdown" class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
      <div class="py-2 max-h-96 overflow-y-auto">
        <div v-if="loading" class="text-center py-4 text-gray-500">Chargement...</div>
        <div v-else-if="notifications.length === 0" class="text-center py-4 text-gray-400">Aucune notification</div>
        <div v-else>
          <div v-for="notif in notifications" :key="notif.id" class="px-4 py-2 border-b last:border-b-0 hover:bg-blue-50 cursor-pointer flex flex-col">
            <span class="font-semibold text-sm text-blue-900 mb-0.5">{{ notif.data.title || 'Notification' }}</span>
            <span class="text-xs text-gray-700">{{ notif.data.message }}</span>
            <span v-if="notif.created_at" class="text-[10px] text-gray-400 mt-1 self-end">{{ formatDate(notif.created_at) }}</span>
          </div>
        </div>
      </div>
      <div class="px-4 py-2 border-t bg-gray-50 text-right">
        <button @click="markAllAsRead" class="text-xs text-blue-600 hover:underline">Tout marquer comme lu</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const notifications = ref([]);
const unreadCount = ref(0);
const showDropdown = ref(false);
const loading = ref(false);

function fetchNotifications() {
  loading.value = true;
  axios.get('/api/agent/notifications?unread=0')
    .then(res => {
      notifications.value = res.data.notifications || [];
      unreadCount.value = notifications.value.filter(n => n.read_at === null).length;
    })
    .finally(() => loading.value = false);
}

function markAllAsRead() {
  axios.post('/api/agent/notifications/mark-all-read')
    .then(() => {
      notifications.value.forEach(n => n.read_at = (new Date()).toISOString());
      unreadCount.value = 0;
    });
}

function toggleDropdown() {
  showDropdown.value = !showDropdown.value;
  if (showDropdown.value) {
    fetchNotifications();
    markAllAsRead();
  }
}

function formatDate(dateStr) {
  const d = new Date(dateStr);
  return d.toLocaleString('fr-FR', { dateStyle: 'short', timeStyle: 'short' });
}

onMounted(() => {
  fetchNotifications();
});
</script>

<style scoped>
/* Ajoutez ici des styles personnalisés si besoin */
</style> 