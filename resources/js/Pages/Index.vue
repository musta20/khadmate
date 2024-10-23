<script setup lang="ts">
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import ServiceCard from '@/Components/ServicesCard.vue';
// import Pagination from '@/Components/Pagination.vue';
import MainLayout from '@/Layouts/MainLayout.vue';
const props =  defineProps<{

    services: Object,

}>();




 </script>

<template>
    <MainLayout>
       <span>
        اكثر الخدمات طلبا
       </span>

            <!-- Services Grid -->
            <div v-if="services.data.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <ServiceCard
                    v-for="service in services.data"
                    :key="service.id"
                    :service="service"
                />
            </div>
            <div v-else class="text-center py-12">
                <p class="text-gray-500 text-lg">No services found matching your criteria.</p>
            </div>

  </MainLayout>
  
</template>
