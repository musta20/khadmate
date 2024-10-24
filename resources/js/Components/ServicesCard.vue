
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
            <Link   :href="route('serviceShow', service.id)"   >

    <div class="rounded-lg font-bold antialiased text-gray-800 border flex flex-col  min-h-60 hover:shadow-lg">
        <img                     :src="'/service/'+service.primary_image.image_path"
        class="rounded-lg max-h-52   m-2 bg-slate-500" />

        <div class="flex place-content-center gap-5  ">
                    <img
                        :src="service.freelancer.avatar || '/storage/user-circle-svgrepo-com.svg'"
                        :alt="service.freelancer.name"
                        class="w-10 rounded-full "
                    />
                    <span class="text-lg my-auto text-gray-600">{{ service.freelancer.name }}</span>
                </div>
        <div class="flex justify-between p-3">
            <div>{{ service.title }}</div>

            <div class=" flex   w-2/12  gap-1      ">          
                 <div class="my-auto ">{{ service?.average_rating >0 ? service.average_rating : "new" }}</div>
     
                <svg v-if="service?.average_rating || false" xmlns="http://www.w3.org/2000/svg" fill="#facc15" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6  mb-1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                </svg>
            
            </div>
        </div>

        <div class="p-3">
            تبدأ من :  {{ service.price }}$
        </div>
    </div>
    </Link>
</template>