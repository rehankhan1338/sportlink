<template>
    <AdminLayout title="Matches">
        <template #header>
            <div class="flex justify-between items-center w-full">
                <h2 class="font-semibold text-xl text-black-800 dark:text-black-200 leading-tight">Matches</h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-medium text-white-900 text-white text-white-100">Matches List</h3>
                        </div>

                        <!-- Error Message -->
                        <div v-if="error" class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            {{ error }}
                        </div>

                        <!-- Matches Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Player 1
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Player 2
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Division
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Mat
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Time
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Round
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-800">
                                    <tr v-for="match in matches" :key="match.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <!-- Player 1 -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img v-if="match.player1?.passport_image_path" :src="match.player1.passport_image_path" class="h-10 w-10 rounded-full object-cover" />
                                                    <div v-else class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                        <span class="text-gray-500">N/A</span>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ match.player1?.first_name }} {{ match.player1?.last_name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ match.player1?.nationality }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Player 2 -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img v-if="match.player2?.passport_image_path" :src="match.player2.passport_image_path" class="h-10 w-10 rounded-full object-cover" />
                                                    <div v-else class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                        <span class="text-gray-500">N/A</span>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ match.player2?.first_name }} {{ match.player2?.last_name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ match.player2?.nationality }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Division -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-gray-100">{{ match.division?.name }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ match.division?.gender }} | {{ match.division?.min_age }}-{{ match.division?.max_age }} years |
                                                {{ match.division?.min_weight }}-{{ match.division?.max_weight }} kg
                                            </div>
                                        </td>

                                        <!-- Mat -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-gray-100">{{ match.mat_name || 'Not assigned' }}</div>
                                        </td>

                                        <!-- Time -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-gray-100">{{ formatTime(match.scheduled_time) }}</div>
                                        </td>


                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full text-white text-capitalize capitalize"
                                               >
                                                {{ match.round_type }}
                                            </span>
                                        </td>

                                        <!-- Status -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                :class="{
                                                    'bg-green-100 text-green-800': match.status === 'completed',
                                                    'bg-yellow-100 text-yellow-800': match.status === 'in_progress',
                                                    'bg-gray-100 text-gray-800': match.status === 'pending'
                                                }">
                                                {{ match.status }}
                                            </span>
                                        </td>

                                        <!-- Actions -->
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <Link :href="`/admin/matches/${match.id}/edit`" 
                                                class="text-white-600 text-white hover:text-white-900 hover:underline">
                                                Edit
                                            </Link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    matches: {
        type: Array,
        default: () => []
    },
    error: {
        type: String,
        default: null
    }
});

const formatTime = (time) => {
    if (!time) return 'Not scheduled';
    return new Date(time).toLocaleString();
};
</script>