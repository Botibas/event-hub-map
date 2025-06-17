<script setup lang="ts">
import { ref, watch } from 'vue';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Search } from 'lucide-vue-next';
import { TimeFieldInput, TimeFieldRoot } from 'reka-ui';

const emit = defineEmits<{
    (e: 'search', value: string): void;
}>();

const showSearch = ref(true);
const searchQuery = ref('');
const start = ref(null);
const end = ref(null);
const debouncedQuery = ref('');
const searchTimeout = ref<ReturnType<typeof setTimeout> | null>(null);

const { noDebounce } = defineProps<{
    noDebounce?: boolean;
}>();

const triggerSearch = () => {
    emit('search', [searchQuery.value, start.value, end.value]);
};

watch(searchQuery, (val) => {
    if(noDebounce) return
    if (searchTimeout.value) clearTimeout(searchTimeout.value);
    searchTimeout.value = setTimeout(() => {
        debouncedQuery.value = val;
        emit('search', val);
    }, 400);
});
</script>

<template>
    <div class="relative flex items-center space-x-2 transition-all duration-300 ease-in-out">
        <Transition name="fade" mode="out-in">
            <div :key="showSearch ? 'search' : 'button'">
                <div v-if="showSearch" class="flex items-center space-x-2">
                    <Input
                        v-model="start"
                        type="date"
                        placeholder="Start Date"
                        class="h-9 w-40"
                        @update:model-value="triggerSearch"
                    />
                    <Input
                        v-model="end"
                        type="date"
                        placeholder="End Date"
                        class="h-9 w-40"
                        @update:model-value="triggerSearch"
                    />
                    <Input
                        v-model="searchQuery"
                        placeholder="Search..."
                        class="h-9 w-48"
                        @keydown.enter="triggerSearch"
                        autofocus
                    />
                    <Button variant="ghost" size="icon" class="h-9 w-9" @click="showSearch = false">
                        ✕
                    </Button>
                </div>

                <div v-else>
                    <Button variant="ghost" size="icon" class="group h-9 w-9" @click="showSearch = true">
                        <Search class="size-5 opacity-80 group-hover:opacity-100" />
                    </Button>
                </div>
            </div>
        </Transition>
    </div>
</template>


<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease, transform 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateY(-4px);
}
</style>
