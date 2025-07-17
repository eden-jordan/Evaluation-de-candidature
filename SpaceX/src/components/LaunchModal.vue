<template>
  <div
    @click.self="$emit('close')"
    class="fixed inset-0 bg-black/60 flex justify-center items-center z-50"
  >
    <div
      class="bg-white p-6 rounded w-full max-w-lg relative shadow-lg overflow-y-auto max-h-[90vh]"
    >
      <button
        @click="$emit('close')"
        class="absolute top-2 right-2 text-xl font-bold text-gray-600 hover:text-black"
      >
        ✖
      </button>

      <h2 class="text-2xl font-bold mb-1">{{ launch.name }}</h2>
      <p class="text-gray-500 mb-4">{{ formatDate(launch.date_utc) }}</p>

      <p v-if="launch.details" class="mb-4">{{ launch.details }}</p>
      <p v-else class="mb-4 text-gray-400 italic">Pas de détails fournis.</p>

      <img
        v-if="launch.links.patch.small"
        :src="launch.links.patch.small"
        class="my-4 w-32 mx-auto"
        alt="Patch mission"
      />

      <a
        v-if="launch.links.article"
        :href="launch.links.article"
        target="_blank"
        class="text-blue-600 underline block text-center mb-4"
      >
        🔗 Lire l'article de présentation
      </a>

      <label class="block mt-4">
        <input type="checkbox" v-model="showVideo" class="mr-2" /> Voir la vidéo
      </label>
      <iframe
        v-if="showVideo && launch.links.youtube_id"
        class="w-full mt-2 aspect-video"
        :src="`https://www.youtube.com/embed/${launch.links.youtube_id}`"
        frameborder="0"
        allowfullscreen
      ></iframe>

      <div class="mt-6 space-y-2 text-sm">
        <p><strong>🛰️ Lieu :</strong> {{ launchpadName }}</p>
        <p><strong>📦 Payloads :</strong> {{ payloadNames.join(', ') || 'Aucun' }}</p>
        <p><strong>👥 Clients :</strong> {{ customers.join(', ') || 'Non spécifiés' }}</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'

const props = defineProps<{ launch: any }>()
const emit = defineEmits(['close'])

const showVideo = ref(false)
const launchpadName = ref('Chargement...')
const payloadNames = ref<string[]>([])
const customers = ref<string[]>([])

function formatDate(dateStr: string): string {
  return new Date(dateStr).toLocaleDateString('fr-FR')
}

onMounted(async () => {
  // 🛰️ Fetch du nom du launchpad
  if (props.launch.launchpad) {
    try {
      const res = await axios.get(
        `https://api.spacexdata.com/v4/launchpads/${props.launch.launchpad}`,
      )
      launchpadName.value = res.data.name || 'Inconnu'
    } catch (err) {
      launchpadName.value = 'Inconnu'
    }
  }

  //  Fetch des payloads
  if (props.launch.payloads?.length) {
    try {
      const res = await Promise.all(
        props.launch.payloads.map((id: string) =>
          axios.get(`https://api.spacexdata.com/v4/payloads/${id}`),
        ),
      )
      payloadNames.value = res.map((r) => r.data.name || 'Nom inconnu')
      customers.value = [...new Set(res.flatMap((r) => r.data.customers || []))]
    } catch (err) {
      payloadNames.value = []
      customers.value = []
    }
  }
})
</script>
