<template>
    <AdminLayout title="Affiliations">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-medium text-gray-900">Affiliations List</h3>
                            <Link
                                :href="route('admin.affiliations.create')"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"
                            >
                                Create Affiliation
                            </Link>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Logo</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cover</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="affiliation in affiliations.data" :key="affiliation.id">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ affiliation.name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <img v-if="affiliation.logo" :src="affiliation.logo" class="h-10 w-10 rounded-full" :alt="affiliation.name + ' logo'" />
                                            <span v-else class="text-gray-400">No logo</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <img v-if="affiliation.cover" :src="affiliation.cover" class="h-10 w-16 rounded object-cover" :alt="affiliation.name + ' cover'" />
                                            <span v-else class="text-gray-400">No cover</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ new Date(affiliation.created_at).toLocaleDateString() }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <Link :href="route('admin.affiliations.edit', { affiliation: affiliation.id })" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                Edit
                                            </Link>
                                            <button @click="deleteAffiliation(affiliation.id)" class="text-red-600 hover:text-red-900">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            <Pagination :links="affiliations.links" />
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
        affiliations: Object
    },
    methods: {
        deleteAffiliation(id) {
            if (confirm('Are you sure you want to delete this affiliation?')) {
                this.$inertia.delete(route('admin.affiliations.destroy', id));
            }
        }
    }
};
</script> 