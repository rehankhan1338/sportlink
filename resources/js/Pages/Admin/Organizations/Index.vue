<template>
    <AdminLayout title="Organizations">
        <template #header>
            <div class="flex justify-between items-center w-full">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Organizations</h2>
                <Link :href="route('admin.organizations.create')" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Create Organization
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <DataTable 
                            title="Organizations"
                            :items="organizations.data" 
                            :columns="columns"
                            :pagination="organizations.meta"
                        >
                            <template #row-actions="{ item }">
                                <div class="flex space-x-2">
                                    <Link :href="route('admin.organizations.edit', item.id)" class="inline-flex items-center px-3 py-1 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500">
                                        Edit
                                    </Link>
                                </div>
                            </template>
                        </DataTable>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link } from '@inertiajs/vue3';
import DataTable from '@/Components/Admin/DataTable.vue';

defineProps({
    organizations: {
        type: Object,
        required: true
    }
});

const columns = [
    { key: 'name', label: 'Name' },
    { key: 'company', label: 'Company' },
    { key: 'first_name', label: 'First Name' },
    { key: 'last_name', label: 'Last Name' },
    { key: 'email', label: 'Email' },
    { key: 'phone', label: 'Phone' },
    { key: 'city', label: 'City' },
    { key: 'country', label: 'Country' }
];
</script> 