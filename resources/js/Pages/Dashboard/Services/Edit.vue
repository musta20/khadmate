<script setup>
import MainDashboardMenu from '@/Components/MainDashboardMenu.vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

// Define props
const props = defineProps({
    categories: {
        type: Array,
        required: true
    },
    service: {
        type: Object,
        required: true
    }
});

const form = useForm({
    title: props.service.title,
    description: props.service.description,
    price: props.service.price,
    category_id: props.service.category_id,
    image: null,
    _method: 'PUT' // For Laravel method spoofing
});

const handleImageUpload = (event) => {
    form.image = event.target.files[0];
};

const submit = () => {
    form.post(route('dashboard.service.update', props.service.id), {
        onSuccess: () => {
            // Don't reset form after update
        }
    });
};
</script>

<template>
    <MainLayout>
        <Head title="تعديل الخدمة" />
        
        <div class="flex gap-5">
            <MainDashboardMenu />

            <div class="p-6 bg-white rounded-lg w-5/6 mx-auto">
                <h2 class="text-2xl font-bold mb-6">تعديل الخدمة</h2>
                
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Category selection field -->
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

                    <!-- Title field -->
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

                    <!-- Description field -->
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

                    <!-- Price field -->
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

                    <!-- Current image preview -->
                    <div v-if="service.images && service.images.length">
                        <label class="block text-sm font-medium text-gray-700 mb-2">الصورة الحالية</label>
                        <div class="flex gap-2 flex-wrap">
                            <div v-for="image in service.images" :key="image.id" class="relative">
                                <img 
                                    :src="`/storage/${image.image_path}`" 
                                    class="w-24 h-24 object-cover rounded"
                                    alt="Service image"
                                >
                            </div>
                        </div>
                    </div>

                    <!-- New image upload -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700">تحديث الصور</label>
                        <input 
                            type="file" 
                            id="image" 
                            @change="handleImageUpload"
                            accept="image/*"
                            class="mt-1 block w-full"
                        >
                        <p class="mt-1 text-sm text-gray-500">
                            اترك هذا الحقل فارغًا إذا كنت لا تريد تغيير الصورة
                        </p>
                    </div>

                    <!-- Submit button -->
                    <div class="flex justify-end">
                        <button 
                            type="submit" 
                            class="px-4 py-2 bg-[#16423C] text-white rounded-md hover:bg-[#16423C] focus:outline-none focus:ring-2 focus:ring-[#16423C]"
                            :disabled="form.processing"
                        >
                            تحديث الخدمة
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </MainLayout>
</template>
