<script setup lang="ts">
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import ServiceCard from '@/Components/ServicesCard.vue';
// import Pagination from '@/Components/Pagination.vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import ReviewCard from '@/Components/ReviewCard.vue';
const props =  defineProps<{

  service: Object,

}>();

 </script>
<template>
    <MainLayout>
      <div class="flex gap-2 w-full">
        <div class="flex border rounded-lg w-full h-auto flex-col">
          <div class="rounded-lg w-full  font-bold antialiased text-gray-800  flex flex-col    ">
        <img    :src="'/service/'+service.primary_image.image_path"
        class="rounded-lg max-h-[35rem]   m-2 bg-slate-500" />

        <div class="flex  p-5 gap-5  ">
                    <img
                        :src="service.freelancer.avatar || '/storage/user-circle-svgrepo-com.svg'"
                        :alt="service.freelancer.name"
                        class="w-10 rounded-full  "
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

        <p class="p-5">
      {{ service.description }}
    </p>
    </div>
    <div class="p-5">
      <h1 class="text-3xl font-bold p-3">المراجعات</h1>
      <div v-if="service.reviews.length" class="flex gap-5 rounded-lg w-5/6 flex-col font-medium">
          <ReviewCard 
          v-for="review in service.reviews"
                    :key="review.id"
                    :review="review"
          />
        </div>
    </div>
  
        </div>

        <div class="flex  w-2/6 ">
          <div class="w-[21%] flex border flex-col rounded-lg p-5 fixed gap-4  font-medium">

            <div class="flex w-5/6 mx-auto justify-between">
              <div class="flex gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
</svg>

                السعر</div>
              <div>  {{ service.price }} $</div>
            </div>

            <div class="flex w-5/6 mx-auto justify-between">
              <div class="flex gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
</svg>

                مدة التسليم</div>
              <div>{{ service.delivery_time }}</div>
            </div>

            <div class="flex w-5/6 mx-auto justify-between">
              <div class="flex gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="m7.875 14.25 1.214 1.942a2.25 2.25 0 0 0 1.908 1.058h2.006c.776 0 1.497-.4 1.908-1.058l1.214-1.942M2.41 9h4.636a2.25 2.25 0 0 1 1.872 1.002l.164.246a2.25 2.25 0 0 0 1.872 1.002h2.092a2.25 2.25 0 0 0 1.872-1.002l.164-.246A2.25 2.25 0 0 1 16.954 9h4.636M2.41 9a2.25 2.25 0 0 0-.16.832V12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 12V9.832c0-.287-.055-.57-.16-.832M2.41 9a2.25 2.25 0 0 1 .382-.632l3.285-3.832a2.25 2.25 0 0 1 1.708-.786h8.43c.657 0 1.281.287 1.709.786l3.284 3.832c.163.19.291.404.382.632M4.5 20.25h15A2.25 2.25 0 0 0 21.75 18v-2.625c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125V18a2.25 2.25 0 0 0 2.25 2.25Z" />
</svg>

                عدد الطلبات</div>
              <div>{{ service.statistics.total_orders }}</div>
            </div>

            <div class="flex w-5/6 mx-auto justify-between">
              <div class="flex gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
</svg>

                طلبات تحت التنفيذ</div>
              <div>{{ service.statistics.active_orders }}</div>
            </div>

            <div class="flex  mt-5 mx-auto justify-between">
              <button class="bg-[#16423C] text-white p-2 rounded-md " >طلب الخدمة</button>
            </div>

          </div>
        </div>

      </div>
  </MainLayout>
  
</template>
