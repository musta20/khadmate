<template>
    <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
        <div v-for="link in links" :key="link.label" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            <Link
                v-if="link.url"
                :href="link.url"
                v-html="link.label"
                :class="{ 'font-bold': link.active }"
                @click="$emit('page-changed', extractPageNumber(link.url))"
            />
            <span v-else v-html="link.label"></span>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: Array,
    currentPage: Number,
});

defineEmits(['page-changed']);

function extractPageNumber(url) {
    const match = url.match(/page=(\d+)/);
    return match ? parseInt(match[1]) : 1;
}
</script>

