<template>
    <div class="max-w-[400px] max-h-[600px] rounded-2xl shadow-md overflow-hidden bg-white overflow-y-scroll">
        <!-- Divider -->
        <div class="border-t border-gray-200"></div>

        <!-- Content -->
        <div class="p-4 space-y-4">
            <!-- Venue Info -->
            <div class="flex justify-around">
                <h3 class="text-xl font-semibold text-black">
                    {{ eventHubStore.displayedEvents?.[0]?.venue?.name || 'Events' }}
                </h3>
            </div>

            <!-- Events List -->
            <div class="space-y-4">
                <div
                    v-for="(event, i) in eventHubStore.displayedEvents"
                    :key="i"
                    class="border border-gray-200 rounded-lg"
                >
                    <img
                        :src="event.image?.source"
                        alt="Event Image"
                        class="w-full h-[200px] object-cover rounded-t-lg"
                    />
                    <div class="px-3 pb-3 mt-2">
                        <h4 class="text-md font-medium text-black">{{ event.title }}</h4>
                        <p v-if="isSameDay(new Date(event.schedule.start), new Date(event.schedule.end))" class="text-xs text-gray-500 mb-1">
                            {{ formatDay(new Date(event.schedule.start)) }}<br>
                            {{ formatTime(new Date(event.schedule.start)) + '-' + formatTime(new Date(event.schedule.end)) }} Uhr
                        </p>
                        <p v-else class="text-xs text-gray-500 mb-1">
                            {{ formatFull(new Date(event.schedule.start)) + '-' + formatFull(new Date(event.schedule.end)) }}
                        </p>
                        <p class="text-xs text-gray-500 mb-1 flex">
                            Tags: {{ event.tags?.join(', ') }}
                            <div v-html="getGoogleMapsIconHTML(event.venue.name)" class="ml-1"/>
                        </p>
                        <p class="text-xs text-gray-600 italic">
                            Organizer: {{ event.organizer }}
                        </p>
                        <p class="text-sm text-gray-700 mt-1">
                            {{ event.description }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import {useEventHubStore} from "@/store/useEventHubStore";
const eventHubStore = useEventHubStore();

const isSameDay = (a: Date, b: Date) =>
    a.getFullYear() === b.getFullYear() &&
    a.getMonth() === b.getMonth() &&
    a.getDate() === b.getDate();

const formatTime = (date: Date) =>
    date.toLocaleTimeString('de-DE', { hour: '2-digit', minute: '2-digit' });

const formatDay = (date: Date) =>
    date.toLocaleDateString('de-DE', { day: 'numeric', month: 'long' });

const formatFull = (date: Date) =>
    date.toLocaleDateString('de-DE', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });

function getGoogleMapsIconHTML (venueName: string)  {
    const url = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(venueName)}`;
    return `
    <a href="${url}" target="_blank" rel="noopener noreferrer" title="Open in Google Maps">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="black" width="18" height="18" style="cursor: pointer;">
        <path fill-rule="evenodd" d="m11.54 22.351.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 0 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742ZM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" clip-rule="evenodd" />
      </svg>
    </a>
  `;
}

</script>
