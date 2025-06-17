<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import Map from "@/components/Map.vue";
import { onMounted } from "vue";
import { useLocationStore } from "@/store/useLocationStore";
import {useEventHubStore} from "@/store/useEventHubStore";
import EventsInfoCard from '@/components/ui/EventsInfoCard.vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Map', href: '/map' },
];

const locationStore = useLocationStore();
const eventHubStore = useEventHubStore();
const page = usePage();

onMounted(async () => {
    const url = new URL(page.url, window.location.origin);
    const venue = url.searchParams.get('venue');
    await eventHubStore.getAllEvents()
    if (venue) {
        locationStore.searchCoordinates(venue);
    } else {
        locationStore.searchFoundEventsCoordinates(eventHubStore.events);
    }
});
</script>


<template>
    <Head title="Map" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-screen flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="z-0 relative h-screen flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <Map />
                <EventsInfoCard v-if="eventHubStore.displayedEvents" class="z-[1000] absolute top-2 right-2" />
            </div>
        </div>
    </AppLayout>
</template>
