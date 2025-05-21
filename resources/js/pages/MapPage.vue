<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import Map from "@/components/Map.vue";
import { computed, onMounted } from "vue";
import { useLocationStore } from "@/store/useLocationStore";
import EventInfoCard from "@/components/ui/EventInfoCard.vue";

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Map', href: '/map' },
];

const locationStore = useLocationStore();

onMounted(() => {
    locationStore.searchCoordinates("EventHub Krefeld");
});
</script>

<template>
    <Head title="Map" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-screen flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="z-0 relative h-screen flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <Map :coords="[locationStore.coordinates?.latitude, locationStore.coordinates?.longitude]" />
                <EventInfoCard class="ml-auto mt-5 mr-5" />
            </div>
        </div>
    </AppLayout>
</template>
