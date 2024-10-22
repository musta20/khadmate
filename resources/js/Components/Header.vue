<script setup lang="ts">
import { Head , Link } from '@inertiajs/vue3';
</script>
<template>
    <header class="my-5 rounded-lg bg-[#6A9C89] " >
        <div class="w-full text-white text-md flex p-5 justify-between transition-all duration-150" >
            <div class="font-extrabold p-3">خدماتي</div>

            <nav class="flex font-semibold ">
                <Link class="hover:bg-[#5da785] p-3 rounded-lg transition-all duration-150"  href="/" >الرئيسية</Link>
                <Link class="hover:bg-[#5da785] p-3 rounded-lg transition-all duration-150" href="/">الخدمات</Link>
                <Link class="hover:bg-[#5da785] p-3 rounded-lg transition-all duration-150" href="/">تسجيل الدخول</Link>
                <Link class="hover:bg-[#5da785] p-3 rounded-lg transition-all duration-150" href="/">بع خدماتك</Link>
                <Link class="hover:bg-[#5da785] p-3 rounded-lg transition-all duration-150" href="/">جميع التصنيفات</Link>
            </nav>

        </div>
        <div class="w-3/6 mx-auto">
          <div class="text-6xl font-extrabold text-white [text-shadow:_0px_10px_10px_rgb(0_0_0_/_60%)]	"> اطلقي العنان لإبداعاتكي الرقمية
            </div>
            <img src="" >

        </div>
    </header>
</template>