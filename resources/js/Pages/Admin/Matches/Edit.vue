<template>
    <AdminLayout title="Edit Match">
        <template #header>
            <div class="flex justify-between items-center w-full">
                <h2 class="font-semibold text-xl text-black-800 dark:text-black-200 leading-tight">Edit Match</h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700">
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-white text-white-900 dark:text-white-100 mb-4">Match Details</h3>
                            
                            <!-- Division Info -->
                            <div class="mb-4">
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <span class="font-medium">Division:</span> 
                                    {{ match.division?.name }} 
                                    ({{ match.division?.gender }} | {{ match.division?.min_age }}-{{ match.division?.max_age }} years | 
                                    {{ match.division?.min_weight }}-{{ match.division?.max_weight }} kg)
                                </p>
                            </div>

                            <!-- Mat and Time -->
                            <div class="mb-4">
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <span class="font-medium">Mat:</span> {{ match.mat_name }}
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <span class="font-medium">Scheduled Time:</span> {{ formatTime(match.scheduled_time) }}
                                </p>
                            </div>

                            <!-- Players -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <!-- Player 1 -->
                                <div class="border p-4 rounded-lg bg-white">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-16 w-16">
                                            <img v-if="match.player1?.passport_image_path" 
                                                :src="match.player1.passport_image_path" 
                                                class="h-16 w-16 rounded-full object-cover" 
                                                :alt="match.player1?.first_name" />
                                            <div v-else class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center">
                                                <span class="text-gray-500">N/A</span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <h4 class="text-lg font-medium">
                                                {{ match.player1?.first_name }} {{ match.player1?.last_name }}
                                            </h4>
                                            <p class="text-sm text-gray-600">{{ match.player1?.nationality }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Player 2 -->
                                <div class="border p-4 rounded-lg bg-white">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-16 w-16">
                                            <img v-if="match.player2?.passport_image_path" 
                                                :src="match.player2.passport_image_path" 
                                                class="h-16 w-16 rounded-full object-cover" 
                                                :alt="match.player2?.first_name" />
                                            <div v-else class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center">
                                                <span class="text-gray-500">N/A</span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <h4 class="text-lg font-medium">
                                                {{ match.player2?.first_name }} {{ match.player2?.last_name }}
                                            </h4>
                                            <p class="text-sm text-gray-600">{{ match.player2?.nationality }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Winner Selection -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Select Winner
                                </label>
                                <select v-model="form.who_won" 
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="">Select a winner</option>
                                    <option :value="match.player1?.id">{{ match.player1?.first_name }} {{ match.player1?.last_name }}</option>
                                    <option :value="match.player2?.id">{{ match.player2?.first_name }} {{ match.player2?.last_name }}</option>
                                </select>
                            </div>

                            <!-- Save Button -->
                            <div class="flex justify-end">
                                <button @click="save" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Save Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    match: {
        type: Object,
        required: true
    }
});

const form = ref({
    who_won: props.match.who_won || ''
});

const formatTime = (time) => {
    if (!time) return 'Not scheduled';
    return new Date(time).toLocaleString();
};

const save = () => {
    router.put(`/admin/matches/${props.match.id}`, form.value, {
        onSuccess: () => {
            // Handle success (e.g., show notification)
        }
    });
};
</script> 