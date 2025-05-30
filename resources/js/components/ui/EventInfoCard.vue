<template>
    <div class="max-w-[400px] rounded-2xl shadow-md overflow-hidden bg-white">
        <!-- Image -->
        <img
            :src="eventHubStore.displayedEvent?.image?.source || '/images/default-event-image.jpg'"
            alt="User"
            class="w-full h-[200px] object-cover"
        />

        <!-- Divider -->
        <div class="border-t border-gray-200"></div>

        <!-- Content -->
        <div class="p-4 space-y-3">
            <!-- Title & Subtitle -->
            <div>
                <h3 class="text-xl font-semibold text-black">{{ eventHubStore.displayedEvent?.title }}</h3>
                <p class="text-sm text-gray-500"> {{ eventHubStore.displayedEvent?.tags.join(', ') }}</p>
            </div>

            <!-- Icon Buttons -->
            <div class="flex items-center space-x-4">
                <div class="flex gap-2 items-center">
                    <LucideMapPin class="w-5 h-5"/>
                    {{ eventHubStore.displayedEvent?.organizer }}
                </div>
            </div>

            <!-- Description -->
            <p class="text-sm text-gray-700 overflow-y-auto max-h-64">
                {{ eventHubStore.displayedEvent?.description }}
            </p>
        </div>
    </div>
</template>
<script setup lang="ts">
import { LucideStar, LucideMapPin, LucideMessageCircle } from 'lucide-vue-next';
import {useEventHubStore} from "@/store/useEventHubStore";

const eventHubStore = useEventHubStore();
</script>

