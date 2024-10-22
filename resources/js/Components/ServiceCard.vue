
<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    service: {
        type: Object,
        required: true
    }
});
</script>

<template>
    <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition-shadow">
        <Link 
        >
                <!-- :href="route('services.show', service.id)" -->

            <!-- Service Image Placeholder -->
            <div class="h-48 bg-gray-200 flex items-center justify-center">
                <span class="text-gray-400">Service Image</span>
            </div>

            <!-- Service Details -->
            <div class="p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                    {{ service.title }}
                </h3>

                <div class="flex items-center mb-2">
                    <img
                        :src="service.freelancer.avatar || '/default-avatar.png'"
                        :alt="service.freelancer.name"
                        class="w-6 h-6 rounded-full mr-2"
                    />
                    <span class="text-sm text-gray-600">{{ service.freelancer.name }}</span>
                </div>

                <div class="text-sm text-gray-500 mb-3">
                    {{ service.category.name }}
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <span class="text-sm text-gray-600">
                            {{ service.average_rating || 'New' }}
                        </span>
                    </div>
                    <div class="text-lg font-bold text-gray-900">
                        ${{ service.price }}
                    </div>
                </div>
            </div>
        </Link>
    </div>
</template>