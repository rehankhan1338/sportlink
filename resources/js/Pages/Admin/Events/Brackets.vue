<template>
    <AdminLayout title="Event Brackets">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-medium text-gray-900">Event Brackets</h3>
                        </div>

                        <div v-if="events.data && events.data.length > 0" class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Name</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Divisions</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Brackets Generated</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="event in events.data" :key="event.id">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ event.name || 'Untitled Event' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(event.start_date) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ event.divisions_count || 0 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ event.brackets_generated || 0 }} / {{ event.divisions_count || 0 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span :class="{
                                                'px-2 py-1 text-xs font-medium rounded-full': true,
                                                'bg-green-100 text-green-800': event.status === 'published',
                                                'bg-yellow-100 text-yellow-800': event.status === 'draft',
                                                'bg-red-100 text-red-800': !event.status
                                            }">
                                                {{ event.status || 'Unknown' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <Link :href="route('admin.brackets.show', event.id)" class="text-indigo-600 hover:text-indigo-900">
                                                View Brackets
                                            </Link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div v-else class="text-center py-8">
                            <p class="text-gray-500">No events found with divisions. Create an event and add divisions to get started.</p>
                        </div>

                        <!-- Pagination -->
                        <div v-if="events.links && events.links.length > 3" class="mt-4">
                            <Pagination :links="events.links" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';

export default {
    components: {
        AdminLayout,
        Link,
        Pagination
    },
    props: {
        events: {
            type: Object,
            required: true,
            default: () => ({
                data: [],
                links: []
            })
        }
    },
    methods: {
        formatDate(date) {
            if (!date) return 'No date set';
            try {
                return new Date(date).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            } catch (e) {
                return 'Invalid date';
            }
        }
    }
};
</script> 