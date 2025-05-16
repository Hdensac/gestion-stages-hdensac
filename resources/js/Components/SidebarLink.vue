<template>
  <Link
    :href="href"
    :method="method"
    :as="as"
    :class="[
      'group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors',
      active 
        ? 'text-blue-700 bg-blue-50' 
        : 'text-gray-700 hover:text-blue-700 hover:bg-gray-100'
    ]"
  >
    <div 
      :class="[
        'mr-3 flex-shrink-0',
        active ? 'text-blue-700' : 'text-gray-500 group-hover:text-blue-700'
      ]"
    >
      <slot name="icon"></slot>
    </div>
    <slot></slot>
  </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  href: {
    type: String,
    required: true
  },
  active: {
    type: Boolean,
    default: false
  },
  method: {
    type: String,
    default: 'get'
  },
  as: {
    type: String,
    default: 'a'
  }
});
</script>