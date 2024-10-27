<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import MainDashboardMenu from '@/Components/MainDashbiardMenu.vue';
import { Head, Link } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';
import { ref } from 'vue';

const props = defineProps({
    services: Object,
});

const headers = [
    { text: 'ID', value: 'id' },
    { text: 'Title', value: 'title' },
    { text: 'Description', value: 'description' },
    { text: 'Price', value: 'price' },
    { text: 'Actions', value: 'actions' },
];

const currentPage = ref(1);
</script>

<template>
    <Head title="My Services" />
    <MainLayout>
        <div class="flex gap-5">
            <MainDashboardMenu />

            <div class="border rounded-lg w-4/6 p-6">
                <h1 class="text-2xl font-bold mb-4">My Services</h1>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th v-for="header in headers" :key="header.value" class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    {{ header.text }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="service in services.data" :key="service.id">
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-300">{{ service.id }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-300">{{ service.title }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-300">{{ service.description }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-300">${{ service.price }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-300">
                                    <Link :href="`/services/${service.id}/edit`" class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</Link>
                                    <button @click="deleteService(service.id)" class="text-red-600 hover:text-red-900">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <Pagination 
                    :links="services.links" 
                    :current-page="currentPage"
                    @page-changed="currentPage = $event"
                />
            </div>
        </div>    
    </MainLayout>
</template>

<style scoped>
/* Add any additional styles here */
</style>
