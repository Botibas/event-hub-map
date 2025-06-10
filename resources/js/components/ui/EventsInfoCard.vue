<template>
    <div class="max-w-[400px] max-h-[700px] rounded-2xl shadow-md overflow-hidden bg-white overflow-y-scroll">
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
                        <p class="text-xs text-gray-500 mb-1">
                            Tags: {{ event.tags?.join(', ') }}
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
import { LIcon } from '@vue-leaflet/vue-leaflet';
const eventHubStore = useEventHubStore();
</script>
