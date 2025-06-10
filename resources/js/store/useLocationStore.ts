import { defineStore } from 'pinia'
import axios from "axios";

export const useLocationStore = defineStore('location', {
    state: () => ({
        coordinates: null as { latitude: number; longitude: number; formattedAddress: string } | null,
        coordinatesArray: [] as { events: any[]; latitude: number; longitude: number; formattedAddress: string }[],
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
        async searchFoundEventsCoordinates(events: object[]) {
            try {
                // 1. Group events by venue name
                const venueMap: { [venueName: string]: any[] } = {};
                events.forEach((event: any) => {
                    if (event.venue?.name) {
                        const name = event.venue.name;
                        if (!venueMap[name]) venueMap[name] = [];
                        venueMap[name].push(event);
                    }
                });

                // 2. Get unique venue names
                const uniqueVenueNames = Object.keys(venueMap);

                // 3. Send unique venues to the geocode endpoint
                const response = await axios.post('/geocodes', { locations: uniqueVenueNames });
                const data = response.data;

                // 4. Map results back to events
                this.coordinatesArray = [];
                uniqueVenueNames.forEach((venueName, index) => {
                    const venueEvents = venueMap[venueName];
                    const coords = data[index];

                    if (coords) {
                        this.coordinatesArray.push({
                            events: venueEvents, // grouped events
                            latitude: coords.latitude,
                            longitude: coords.longitude,
                            formattedAddress: coords.formattedAddress,
                        });
                    }
                });

                console.log(this.coordinatesArray);
            } catch (err) {
                this.error = 'Error fetching data';
                console.error(err);
            } finally {
                this.loading = false;
            }
        }
    },
})
