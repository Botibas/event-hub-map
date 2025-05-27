import { defineStore } from 'pinia'
import axios from 'axios'
import { useLocationStore } from './useLocationStore' // adjust path if needed

interface Event {
    title: string;
    description: string;
    source: string;
    tags: string[];
    organizer: string;
    image: {
        source: string;
    };
    schedule: {
        start: string;
        end: string;
        days: string[] | null;
    };
    venue: {
        name: string;
    };
}

export const useEventHubStore = defineStore('eventHub', {
    state: () => ({
        events: [] as Event[],
        displayedEvent: null as Event | null,
        coordinates: null as { latitude: number; longitude: number; formattedAddress: string } | null,
        error: null as string | null,
        loading: false,
    }),
    actions: {
        async getNextEvent(query: string) {
            const locationStore = useLocationStore();
            this.loading = true;
            this.error = null;

            try {
                const response = await axios.get('/eventhub/next', {
                    params: { query },
                });

                const event = response.data as Event;
                this.displayedEvent = event;
                console.log('Next event:', event.venue.name);
                await locationStore.searchCoordinates(event.venue.name);
                this.coordinates = locationStore.coordinates;

            } catch (error) {
                this.error = 'Error fetching data';
                console.error('Error fetching event hub:', error);
            } finally {
                this.loading = false;
            }
        },
        async getAllEvents(query?: string, limit: number = 5) {
            this.loading = true;
            this.error = null;

            try {
                const response = await axios.get('/eventhub/upcoming', {
                    params: { query, limit }
                });

                this.events = response.data as Event[];

                return response.data as Event[];
            } catch (error) {
                this.error = 'Error fetching all events';
                console.error('Error fetching all events:', error);
                return [];
            } finally {
                this.loading = false;
            }
        },
        setDisplayedEvent(event: Event) {
            this.displayedEvent = event;
        },
    },
})
