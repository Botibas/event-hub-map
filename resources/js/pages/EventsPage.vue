<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { router } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import { useEventHubStore } from '@/store/useEventHubStore';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Events', href: '/events' },
];

const eventHubStore = useEventHubStore();

onMounted(() => {
    eventHubStore.getAllEvents('', 9);
});

function formatDate(dateStr: string | null | undefined) {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return date.toLocaleString(undefined, {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
}

function goToMap(event: typeof eventHubStore.events[number]) {
    if (!event.venue?.name) return;
    eventHubStore.setDisplayedEvent(event);  // Event setzen im Store
    router.visit(`/map?venue=${encodeURIComponent(event.venue.name)}`);
}
</script>


<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-7xl mx-auto">
                <div
                    v-for="event in eventHubStore.events"
                    :key="event.title"
                    class="flex flex-col rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4"
                >
                    <h3 class="text-lg font-semibold mb-2">{{ event.title }}</h3>

                    <p
                        class="text-sm text-gray-700 dark:text-gray-300 mb-4 line-clamp-5"
                        style="min-height: 6.5rem;"
                    >
                        {{ event.description }}
                    </p>

                    <img
                        v-if="event.image?.source"
                        :src="event.image.source"
                        alt="Event Bild"
                        class="rounded-md w-full max-h-48 object-cover mb-4"
                    />

                    <div class="mt-auto text-xs text-gray-500 dark:text-gray-400">
                        <p>Ort: {{ event.venue?.name }}</p>
                        <p>
                            Datum: {{ formatDate(event.schedule?.start) }} – {{ formatDate(event.schedule?.end) }}
                        </p>
                        <a
                            :href="event.source"
                            target="_blank"
                            rel="noopener"
                            class="text-blue-600 dark:text-blue-400 hover:underline mt-2 inline-block"
                        >
                            Mehr erfahren
                        </a>
                    </div>

                    <button
                        @click="goToMap(event)"
                        class="mt-4 rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
                    >
                        Auf der Karte zeigen
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
