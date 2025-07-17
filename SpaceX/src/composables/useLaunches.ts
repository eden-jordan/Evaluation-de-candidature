import { ref } from 'vue'
import axios from 'axios'

export function useLaunches() {
  const nextLaunch = ref<any>(null)
  const launches = ref<any[]>([])

  const fetchNextLaunch = async () => {
    const query = {
      query: { upcoming: true },
      options: {
        sort: { date_unix: 'asc' },
        limit: 100,
      },
    }

    const res = await axios.post('https://api.spacexdata.com/latest/launches/query', query)

    const now = Date.now() / 1000 // timestamp en secondes
    const futureLaunches = res.data.docs.filter((launch: any) => launch.date_unix > now)

    nextLaunch.value = futureLaunches.length > 0 ? futureLaunches[0] : null
  }

  const fetchLaunches = async (filter: 'all' | 'success' | 'failed') => {
    const query: any = {
      query: {},
      options: {
        limit: 10,
        sort: { date_unix: 'desc' },
      },
    }

    if (filter === 'success') query.query.success = true
    if (filter === 'failed') query.query.success = false

    const res = await axios.post('https://api.spacexdata.com/latest/launches/query', query)
    launches.value = res.data.docs
  }

  return {
    nextLaunch,
    launches,
    fetchNextLaunch,
    fetchLaunches,
  }
}
