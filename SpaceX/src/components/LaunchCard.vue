<template>
  <div @click="$emit('click')" class="cursor-pointer border p-3 rounded hover:bg-gray-100">
    <h3 class="font-bold">{{ launch.name }}</h3>
    <p>{{ formatDate(launch.date_utc) }}</p>
  </div>
</template>

<script setup lang="ts">
defineProps<{ launch: any }>()

function formatDate(dateStr: string): string {
  return new Date(dateStr).toLocaleDateString('fr-FR')
}
</script>
