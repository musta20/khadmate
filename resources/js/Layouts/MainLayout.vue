<script setup lang="ts">
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Header from '@/Components/Header.vue'
import Footer from '@/Components/Footer.vue'
import { Head , Link } from '@inertiajs/vue3';
</script>

<template>
        <Head title="Welcome" />

    <div class="w-5/6 mx-auto">
        <Header />

        <slot />

    </div>
            
     <Footer />
</template>
