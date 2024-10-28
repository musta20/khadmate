<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import MainDashboardMenu from '@/Components/MainDashboardMenu.vue';
import { Head, Link } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';
import { ref } from 'vue';

const props = defineProps({
    services: Object,
});

const headers = [
    { text: 'عنوان الخدمة', value: 'title' },
    { text: 'الوصف', value: 'description' },
    { text: 'السعر', value: 'price' },
    { text: 'الاجراء', value: 'actions' },
];

const currentPage = ref(1);
</script>

<template>
    <Head title="My Services" />
    <MainLayout>
        <div class="flex gap-5">
            <MainDashboardMenu />

            <div class="border rounded-lg w-4/6 p-6">
                <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold mb-4">خدماتي</h1>
                <Link :href="route('dashboard.service.create')" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">إضافة خدمة</Link>
            </div>
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
                                 <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-300">{{ service.title }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-300">{{ service.description }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-300">${{ service.price }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-300">
                                    <Link :href="route('dashboard.service.edit',service.id)"  class="text-indigo-600 hover:text-indigo-900 mr-2">تعديل</Link>
                                    <button @click="deleteService(service.id)" class="text-red-600 hover:text-red-900">حذف</button>
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
