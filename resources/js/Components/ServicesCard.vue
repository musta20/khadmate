
<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    service: {
        type: Object,
        required: true
    }
});

// const imageUrl = computed(() => {
//     if (!props.service.primary_image) {
//         return '/images/default-service-image.jpg';
//     }
//     return props.service.primary_image.startsWith('http') 
//         ? props.service.primary_image 
//         : `/storage/${props.service.primary_image}`;
// });
  </script>

<template>
    <div class="rounded-lg font-bold antialiased text-gray-800 border flex flex-col  min-h-60">
        <img                     :src="'/service/'+service.primary_image.image_path"
        class="rounded-lg max-h-52   m-2 bg-slate-500" />

        <div class="mx-auto p-1" >
             {{ service.freelancer.name }}
        </div>

        <div class="flex justify-between p-3">
            <div>{{ service.title }}</div>

            <div>{{ service.average_rating || 'New' }}</div>
        </div>

        <div class="p-3">
            تبدأ من : ${{ service.price }}$
        </div>
    </div>
</template>