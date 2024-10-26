<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import workerImage from '@/assets/worker.png';
import { computed } from 'vue';

const currentRoute = computed(() => usePage().url);
const isHomePage = computed(() => currentRoute.value === '/');
</script>
<template>
    <header class="my-5 rounded-lg bg-[#6A9C89] " >
        <div class="w-full text-white text-md flex p-3 justify-between transition-all duration-150" >
            <div class="font-extrabold p-3">خدماتي</div>
            <form dir="ltr" v-if="!isHomePage" class="w-2/6 mx-auto">   
                    <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-3 h-3 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                        </div>
                        <input type="search" id="default-search" class="block w-full p-3 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500  " placeholder="ابحث في الخدمات..." required />
                        <button type="submit" class="text-white absolute end-2.5 bottom-1.5 bg-[#16423C] hover:bg-[#6A9C89]  font-medium rounded-lg text-sm px-4 py-2 ">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                        </button>
                    </div>
                </form>

            <nav class="flex font-semibold ">
                <Link class="hover:bg-[#5da785] p-3 rounded-lg transition-all duration-150"  href="/" >الرئيسية</Link>
                <Link class="hover:bg-[#5da785] p-3 rounded-lg transition-all duration-150" href="/">الخدمات</Link>
                <Link class="hover:bg-[#5da785] p-3 rounded-lg transition-all duration-150"  href="/login">تسجيل الدخول</Link>
                <Link class="hover:bg-[#5da785] p-3 rounded-lg transition-all duration-150" href="/">بع خدماتك</Link>
                <Link class="hover:bg-[#5da785] p-3 rounded-lg transition-all duration-150" href="/">جميع التصنيفات</Link>
            </nav>

 
        </div>


        <div v-if="isHomePage" class="w-4/6 mx-auto flex flex-col  p-5 items-center justify-between">
            <div class="text-6xl font-extrabold text-white [text-shadow:_0px_10px_10px_rgb(0_0_0_/_60%)]">
                اطلقي العنان لإبداعاتكي الرقمية
            </div>
            <img :src="workerImage" alt="Worker" class="-mt-4 mr-32 h-auto">

                <form dir="ltr" class="w-4/6 mx-auto">   
                    <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                        </div>
                        <input type="search" id="default-search" class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500  " placeholder="ابحث في الخدمات..." required />
                        <button type="submit" class="text-white absolute end-2.5 bottom-2.5 bg-[#16423C] hover:bg-[#6A9C89]  font-medium rounded-lg text-sm px-4 py-2 ">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                        </button>
                    </div>
                </form>
        </div>



    </header>
</template>
