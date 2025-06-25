<template>
    <AdminLayout title="Brackets Management">
        <div class="py-10">
            <main>
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h2 class="text-2xl font-bold mb-4">{{ event.name }} - Divisions</h2>
                            
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Division Name
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Bracket Type
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Start Time
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Mat/Area
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Athletes
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="division in divisions" :key="division.id">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ division.name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ division.bracket_type }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ formatDateTime(division.start_time) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ division.mat_area }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ division.athletes_count }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span :class="[
                                                    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                    division.matches_created
                                                        ? 'bg-green-100 text-green-800'
                                                        : 'bg-yellow-100 text-yellow-800'
                                                ]">
                                                    {{ division.matches_created ? 'Matches Created' : 'No Matches' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <Link
                                                    :href="`/admin/events/${event.id}/brackets/${division.id}/edit`"
                                                    class="text-indigo-600 hover:text-indigo-900"
                                                >
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
            </main>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

defineProps({
    event: {
        type: Object,
        required: true,
    },
    divisions: {
        type: Array,
        required: true,
    },
})

const formatDateTime = (datetime) => {
    return new Date(datetime).toLocaleString()
}
</script> 