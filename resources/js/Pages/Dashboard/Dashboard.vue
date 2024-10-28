<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import MainDashboardMenu from '@/Components/MainDashboardMenu.vue'
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />
    <MainLayout>
        <div class="flex gap-5">
            <MainDashboardMenu />

                <div class="border rounded-lg w-4/6"></div>

                </div>
    </MainLayout>
</template>
