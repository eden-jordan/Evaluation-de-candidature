<template>
  <div class="p-6 max-w-4xl mx-auto space-y-6">
    <h1 class="text-3xl font-bold text-center">🚀 SpaceX</h1>

    <!-- Prochain lancement -->
    <section class="bg-gray-100 p-4 rounded">
      <h2 class="text-xl font-semibold mb-2">🔜 Prochain lancement</h2>
      <p>
        <strong>{{ nextLaunch?.name }}</strong>
      </p>
      <p>Date : {{ formatDate(nextLaunch?.date_utc) }}</p>
      <p class="text-red-600">Décompte : {{ countdown }}s</p>
    </section>

    <!-- Filtre -->
    <select v-model="filter" @change="fetchLaunches(filter)" class="mt-4 p-2 border rounded">
      <option value="all">Tous les lancements</option>
      <option value="success">Lancements réussis</option>
      <option value="failed">Lancements échoués</option>
    </select>

    <!-- Liste des lancements -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <LaunchCard
        v-for="launch in launches"
        :key="launch.id"
        :launch="launch"
        @click="selectLaunch(launch)"
      />
    </div>

    <LaunchModal v-if="selectedLaunch" :launch="selectedLaunch" @close="selectedLaunch = null" />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useLaunches } from '../composables/useLaunches'
import LaunchCard from '../components/LaunchCard.vue'
import LaunchModal from '../components/LaunchModal.vue'

const { nextLaunch, launches, fetchNextLaunch, fetchLaunches } = useLaunches()

const filter = ref<'all' | 'success' | 'failed'>('all')
const countdown = ref<number>(0)
const selectedLaunch = ref<any>(null)

const updateCountdown = () => {
  if (nextLaunch.value) {
    const now = Date.now()
    const launchTime = new Date(nextLaunch.value.date_utc).getTime()
    countdown.value = Math.max(0, Math.floor((launchTime - now) / 1000))
  }
}

const selectLaunch = (launch: any) => {
  selectedLaunch.value = launch
}

onMounted(() => {
  fetchNextLaunch()
  fetchLaunches(filter.value)
  setInterval(updateCountdown, 1000)
})

function formatDate(dateStr: string): string {
  const date = new Date(dateStr)
  return date.toLocaleDateString('fr-FR')
}
</script>
