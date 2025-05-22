<template>
    <div class="max-w-[400px] rounded-2xl shadow-md overflow-hidden bg-white">
        <!-- Image -->
        <img
            src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?w=800&q=80"
            alt="User"
            class="w-full h-[200px] object-cover"
        />

        <!-- Divider -->
        <div class="border-t border-gray-200"></div>

        <!-- Content -->
        <div class="p-4 space-y-3">
            <!-- Title & Subtitle -->
            <div>
                <h3 class="text-xl font-semibold text-black">{{ location.name }}</h3>
                <p class="text-sm text-gray-500">{{ location.subtitle }}</p>
            </div>

            <!-- Rating -->
            <div class="flex items-center space-x-1 text-yellow-500">
                <LucideStar class="w-5 h-5" />
                <span class="text-sm text-gray-700">{{ location.score }} (120 reviews)</span>
            </div>

            <!-- Icon Buttons -->
            <div class="flex items-center space-x-4">
                <button
                    type="button"
                    class="text-gray-600 hover:text-blue-600 transition-colors"
                >
                    <LucideMapPin class="w-5 h-5" />
                </button>
                <button
                    type="button"
                    class="text-gray-600 hover:text-blue-600 transition-colors"
                >
                    <LucideMessageCircle class="w-5 h-5" />
                </button>
            </div>

            <!-- Description -->
            <p class="text-sm text-gray-700">
                {{ location.description }}
            </p>
        </div>
    </div>
</template>
<script setup lang="ts">
import { LucideStar, LucideMapPin, LucideMessageCircle } from 'lucide-vue-next';

type Location = {
    image: string | null;
    name: string;
    subtitle: string;
    score: number;
    description: string;
};

const location: Location = {
    image: null,
    name: "Kulturfabrik Krefeld",
    subtitle: "Live music Venue",
    score: 4.8,
    description: "Seit 1903 dient die frühere Verkaufsstätte des Krefelder Schlathofs als Veranstaltungsort."
};
</script>

