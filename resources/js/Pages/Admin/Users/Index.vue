<template>
    <AdminLayout>
        <DataTable
            title="Users"
            :columns="columns"
            :items="users.data"
            :pagination="users.meta"
            @search="handleSearch"
            @sort="handleSort"
            @paginate="handlePaginate"
        >
            <template #actions>
                <Link
                    :href="route('admin.users.create')"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700"
                >
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add User
                </Link>
            </template>

            <template #name="{ item }">
                <div class="text-sm font-medium text-gray-900">
                    {{ item.name }}
                </div>
            </template>

            <template #email="{ item }">
                <div class="text-sm text-gray-500">
                    {{ item.email }}
                </div>
            </template>

            <template #created_at="{ item }">
                <div class="text-sm text-gray-500">
                    {{ new Date(item.created_at).toLocaleDateString() }}
                </div>
            </template>

            <template #row-actions="{ item }">
                <Link
                    :href="route('admin.users.edit', item.id)"
                    class="text-blue-600 hover:text-blue-900 mr-4"
                >
                    Edit
                </Link>
                <button
                    @click="confirmDelete(item)"
                    class="text-red-600 hover:text-red-900"
                >
                    Delete
                </button>
            </template>
        </DataTable>

        <!-- Delete Confirmation Modal -->
        <Modal :show="showDeleteModal" @close="closeDeleteModal">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900">
                    Delete User
                </h3>
                <p class="mt-2 text-sm text-gray-500">
                    Are you sure you want to delete this user? This action cannot be undone.
                </p>
                <div class="mt-4 flex justify-end space-x-3">
                    <button
                        type="button"
                        class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        @click="closeDeleteModal"
                    >
                        Cancel
                    </button>
                    <button
                        type="button"
                        class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                        @click="deleteUser"
                    >
                        Delete
                    </button>
                </div>
            </div>
        </Modal>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DataTable from '@/Components/Admin/DataTable.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    users: {
        type: Object,
        required: true
    },
    filters: {
        type: Object,
        required: true
    }
});

const columns = [
    { key: 'name', label: 'Name' },
    { key: 'email', label: 'Email' },
    { key: 'created_at', label: 'Created At' }
];

const showDeleteModal = ref(false);
const userToDelete = ref(null);

const handleSearch = (search) => {
    router.get(route('admin.users.index'), { search }, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    });
};

const handleSort = ({ key, order }) => {
    router.get(route('admin.users.index'), { sort: key, order }, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    });
};

const handlePaginate = (page) => {
    router.get(route('admin.users.index'), { page }, {
        preserveState: true,
        preserveScroll: true
    });
};

const confirmDelete = (user) => {
    userToDelete.value = user;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    userToDelete.value = null;
};

const deleteUser = () => {
    router.delete(route('admin.users.destroy', userToDelete.value.id), {
        onSuccess: () => {
            closeDeleteModal();
        }
    });
};
</script> 