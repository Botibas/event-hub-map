<template>
    <div :class="['w-full', 'h-[calc(100vh-100px)]']">
        <l-map :zoom="13" :center="center" class="w-full h-full rounded-xl">
            <l-tile-layer
                url="https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png"
                attribution='&copy; OpenStreetMap &copy; CARTO'
            />
            <l-marker :lat-lng="center" />
        </l-map>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { LMarker } from '@vue-leaflet/vue-leaflet'

const center = ref([51.3388, 6.5853]); // Krefeld
</script>
