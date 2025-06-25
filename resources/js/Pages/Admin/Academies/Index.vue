<template>
    <Head title="Academies" />

    <AdminLayout title="Academies">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-medium text-gray-900">Academies List</h3>
                            <Link
                                :href="route('admin.academies.create')"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"
                            >
                                Create Academy
                            </Link>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Logo</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cover</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="academy in academies.data" :key="academy.id">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ academy.name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <img v-if="academy.logo" :src="academy.logo" class="h-10 w-10 rounded-full" :alt="academy.name + ' logo'" />
                                            <span v-else class="text-gray-400">No logo</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <img v-if="academy.cover" :src="academy.cover" class="h-10 w-16 rounded object-cover" :alt="academy.name + ' cover'" />
                                            <span v-else class="text-gray-400">No cover</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ academy.location }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ new Date(academy.created_at).toLocaleDateString() }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <Link :href="route('admin.academies.edit', academy.id)" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                Edit
                                            </Link>
                                            <button @click="deleteAcademy(academy.id)" class="text-red-600 hover:text-red-900">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            <Pagination :links="academies.links" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { router } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    academies: Object,
    filters: Object
});

const showDeleteModal = ref(false);
const academyToDelete = ref(null);

const columns = [
    { key: 'name', label: 'Name', sortable: true },
    { key: 'country', label: 'Country', sortable: true },
    { key: 'city', label: 'City', sortable: true },
    { key: 'email', label: 'Email', sortable: true },
    { key: 'person_in_charge', label: 'Person in Charge', sortable: true },
    { key: 'phone', label: 'Phone' },
    { 
        key: 'logo_path',
        label: 'Logo',
        render: (value) => value ? `<img src="/storage/${value}" class="w-10 h-10 rounded-full object-cover">` : '<span class="text-gray-400">No logo</span>'
    },
    { key: 'created_at', label: 'Created At', sortable: true }
];

const confirmDelete = (academy) => {
    academyToDelete.value = academy;
    showDeleteModal.value = true;
};

const handleDelete = () => {
    if (academyToDelete.value) {
        router.delete(route('admin.academies.destroy', academyToDelete.value.id), {
            onSuccess: () => {
                showDeleteModal.value = false;
                academyToDelete.value = null;
            }
        });
    }
};

const deleteAcademy = (id) => {
    if (confirm('Are you sure you want to delete this academy?')) {
        router.delete(route('admin.academies.destroy', id));
    }
};
</script> 