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
        displayedEvents: null as Event[] | null,
        coordinates: null as { latitude: number; longitude: number; formattedAddress: string } | null,
        error: null as string | null,
        loading: false,
    }),
    actions: {
        async getNextEvents(query: string, start?: string | null, end?: string | null) {
            const locationStore = useLocationStore();
            try {
                await this.getAllEvents(query, start, end)
                locationStore.searchFoundEventsCoordinates(this.events);
                const event = this.events[0] as Event;
                if (event && event.venue && event.venue.name) {
                    this.displayedEvents = [event];
                }
            } catch (error) {
                this.error = 'Error fetching next events';
                console.error('Error fetching next events:', error);
            }
        },
        async getAllEvents(query: string, start?: string | null, end?: string | null, limit: number = 9) {
            this.loading = true;
            this.error = null;

            try {
                const response = await axios.get('/eventhub/upcoming', {
                    params: { query, start, end, limit }
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
