import { defineStore } from 'pinia'
import axios from "axios";

export const useLocationStore = defineStore('location', {
    state: () => ({
        coordinates: null as { latitude: number; longitude: number; formattedAddress: string } | null,
        error: null as string | null,
        loading: false,
    }),
    actions: {
        async searchCoordinates(location: string) {
            try {
                const response = await axios.get('/geocode', { params: { location } })
                const data = response.data
                if (data?.latitude && data?.longitude) {
                    this.coordinates = {
                        latitude: data.latitude,
                        longitude: data.longitude,
                        formattedAddress: data.formattedAddress,
                    }
                } else {
                    this.error = 'Location not found'
                }
            } catch (err) {
                this.error = 'Error fetching data'
                console.error(err)
            } finally {
                this.loading = false
            }
        },
    },
})
