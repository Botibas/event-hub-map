<template>
    <div v-if="!locationStore.loading && isValidCenter(center)" class="w-full h-[calc(100vh-100px)]">
        <l-map
            :zoom="13"
            :center="center"
            class="w-full h-full rounded-xl"
            :zoom-control="false"
            @ready="onMapReady"
        >
            <l-tile-layer
                url="https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png"
                attribution='&copy; OpenStreetMap &copy; CARTO'
            />
            <l-marker
                v-for="(coords, i) in locationStore.coordinatesArray"
                :key="i"
                ref="markerRef"
                :lat-lng="[coords.latitude, coords.longitude]"
                :icon="isSelected(coords) ? highlightIcon : customIcon"
                @click="handleMarkerClick(coords)"
            />
        </l-map>
    </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue';
import { LMarker } from '@vue-leaflet/vue-leaflet';
import L from 'leaflet';
import { useLocationStore } from '@/store/useLocationStore';
import { useEventHubStore } from '@/store/useEventHubStore';

const locationStore = useLocationStore();
const eventHubStore = useEventHubStore();

const isValidCenter = (val: unknown): val is [number, number] => {
    return Array.isArray(val)
        && val.length === 2
        && typeof val[0] === 'number'
        && typeof val[1] === 'number'
        && val[0] !== null
        && val[1] !== null;
};

const isSelected = (coords: any) => {
    return (
        eventHubStore.coordinates &&
        coords.latitude === eventHubStore.coordinates.latitude &&
        coords.longitude === eventHubStore.coordinates.longitude
    );
};


const center = computed<[number, number]>(() => {
    const coordinates = locationStore.coordinatesArray[0];
    return coordinates ? [coordinates.latitude, coordinates.longitude] : [51.3344, 6.5857];
});

const handleMarkerClick = (coords: any) => {
    eventHubStore.displayedEvents = coords.events;
    eventHubStore.coordinates = {
        latitude: coords.latitude,
        longitude: coords.longitude,
        formattedAddress: '',
    };
};


// Marker Referenz
const markerRef = ref<typeof LMarker | null>(null);

// Custom Marker Icon
const customIcon = L.divIcon({
    html: `
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="black" class="w-8 h-8">
      <path fill-rule="evenodd" d="m11.54 22.351.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 0 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742ZM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" clip-rule="evenodd" />
    </svg>
  `,
    iconSize: [24, 24],
    className: '',
});
const highlightIcon = L.divIcon({
    html: `
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#0ead4b" class="w-8 h-8">
      <path fill-rule="evenodd" d="m11.54 22.351.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 0 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742ZM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" clip-rule="evenodd" />
    </svg>
  `,
    iconSize: [24, 24],
    className: '',
});

// Map-Ready Callback
function onMapReady(map: L.Map) {
    map.zoomControl?.remove();
    L.control.zoom({ position: 'bottomright' }).addTo(map);
}
</script>
