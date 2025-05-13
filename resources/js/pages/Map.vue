<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import Leaflet from "@/components/Leaflet.vue";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Map',
        href: '/map',
    },
];
</script>

<template>
    <Head title="Map" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-screen flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="z-0 relative h-screen flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <Leaflet />
            </div>
        </div>
    </AppLayout>
</template>
