<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import Map from "@/components/Map.vue";
import { onMounted } from "vue";
import { useLocationStore } from "@/store/useLocationStore";
import EventInfoCard from "@/components/ui/EventInfoCard.vue";
import {useEventHubStore} from "@/store/useEventHubStore";

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Map', href: '/map' },
];

const locationStore = useLocationStore();
const evenHubStore = useEventHubStore();
const page = usePage();

onMounted(() => {
    const url = new URL(page.url, window.location.origin);
    const venue = url.searchParams.get('venue');

    if (venue) {
        locationStore.searchCoordinates(venue);
    } else {
        locationStore.searchCoordinates("Hochschule Niederrhein Krefeld");
    }
});
</script>


<template>
    <Head title="Map" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-screen flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="z-0 relative h-screen flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <Map :coords="[locationStore.coordinates?.latitude, locationStore.coordinates?.longitude]" />
                <EventInfoCard v-if="evenHubStore.displayedEvent" class="z-[1000] absolute top-2 right-2" />
            </div>
        </div>
    </AppLayout>
</template>
