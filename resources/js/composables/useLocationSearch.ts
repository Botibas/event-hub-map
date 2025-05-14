import { ref } from 'vue';
import axios from 'axios';

interface GeoLocation {
    latitude: number;
    longitude: number;
    formattedAddress: string;
}

export function useGeoLocation() {
    const coordinates = ref<GeoLocation | null>(null);
    const error = ref<string | null>(null);
    const loading = ref<boolean>(false);

    // This API key can now be kept on the backend for better security
    // API_KEY is no longer needed in the frontend
    const BASE_URL = '/geocode';  // Backend route

    // Search for coordinates by location string
    const searchCoordinates = async (location: string) => {
        loading.value = true;
        error.value = null;
        coordinates.value = null;

        try {
            const response = await axios.get(BASE_URL, {
                params: {
                    location: location, // Pass location to backend
                },
            });

            const data = response.data;

            if (data && data.latitude && data.longitude) {
                console.log('Coordinates:', data.latitude, data.longitude);
                coordinates.value = {
                    latitude: data.latitude,
                    longitude: data.longitude,
                    formattedAddress: data.formattedAddress,
                };
            } else {
                error.value = 'Location not found';
            }
        } catch (err) {
            error.value = 'Error fetching data';
            console.error(err);
        } finally {
            loading.value = false;
        }
    };

    return {
        coordinates,
        error,
        loading,
        searchCoordinates,
    };
}
