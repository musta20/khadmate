<script setup>
import MainDashboardMenu from '@/Components/MainDashboardMenu.vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

// Define props
const props = defineProps({
    categories: {
        type: Array,
        required: true
    }
});

const form = useForm({
    title: '',
    description: '',
    price: '',
    category_id: '', // Add category_id to form
    image: null
});

const handleImageUpload = (event) => {
    form.image = event.target.files[0];
};

const submit = () => {
    form.post(route('dashboard.service.store'), {
        onSuccess: () => {
            form.reset();
        }
    });
};
</script>

<template>
    <MainLayout>
        <Head title="Create Service" />
        
        <div class="flex gap-5">
            <MainDashboardMenu />

            <div class="p-6 bg-white rounded-lg w-5/6 mx-auto">
                <h2 class="text-2xl font-bold mb-6">إنشاء خدمة</h2>
                
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Add category selection field -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">التصنيفات</label>
                        <select
                            id="category"
                            v-model="form.category_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required
                        >
                            <option value="">اختر تصنيف</option>
                            <option 
                                v-for="category in categories" 
                                :key="category.id" 
                                :value="category.id"
                            >
                                {{ category.name }}
                            </option>
                        </select>
                    </div>

                    <!-- Existing fields -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">العنوان</label>
                        <input 
                            type="text" 
                            id="title" 
                            v-model="form.title"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required
                        >
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">الوصف</label>
                        <textarea 
                            id="description" 
                            v-model="form.description"
                            rows="4"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required
                        ></textarea>
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700">السعر</label>
                        <input 
                            type="number" 
                            id="price" 
                            v-model="form.price"
                            step="0.01"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required
                        >
                    </div>

                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700">الصور</label>
                        <input 
                            type="file" 
                            id="image" 
                            @change="handleImageUpload"
                            accept="image/*"
                            class="mt-1 block w-full"
                            required
                        >
                    </div>

                    <div class="flex justify-end">
                        <button 
                            type="submit" 
                            class="px-4 py-2 bg-[#16423C] text-white rounded-md hover:bg-[#16423C] focus:outline-none focus:ring-2 focus:ring-[#16423C]"
                            :disabled="form.processing"
                        >
                            إنشاء الخدمة
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </MainLayout>
</template>
