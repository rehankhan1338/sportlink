<template>
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <!-- Header -->
            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-xl font-semibold text-gray-900">{{ title }}</h1>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                    <slot name="actions"></slot>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="mt-4">
                <div class="flex space-x-4">
                    <div class="flex-1">
                        <label for="search" class="sr-only">Search</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input
                                type="search"
                                name="search"
                                id="search"
                                class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md"
                                placeholder="Search"
                                v-model="searchQuery"
                                @input="handleSearch"
                            >
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="mt-8 flex flex-col">
                <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            v-for="column in columns"
                                            :key="column.key"
                                            scope="col"
                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                        >
                                            <a
                                                href="#"
                                                class="group inline-flex"
                                                @click.prevent="sort(column.key)"
                                            >
                                                {{ column.label }}
                                                <span class="ml-2 flex-none rounded text-gray-400">
                                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </a>
                                        </th>
                                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                            <span class="sr-only">Actions</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr v-for="item in items" :key="item.id">
                                        <td
                                            v-for="column in columns"
                                            :key="column.key"
                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                        >
                                            <slot :name="column.key" :item="item">
                                                {{ item[column.key] }}
                                            </slot>
                                        </td>
                                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                            <slot name="row-actions" :item="item"></slot>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="pagination" class="mt-4 flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
                <div class="flex flex-1 justify-between sm:hidden">
                    <a
                        href="#"
                        class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                        :class="{ 'opacity-50 cursor-not-allowed': !pagination.prev_page_url }"
                        @click.prevent="handlePaginate(pagination.current_page - 1)"
                    >
                        Previous
                    </a>
                    <a
                        href="#"
                        class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                        :class="{ 'opacity-50 cursor-not-allowed': !pagination.next_page_url }"
                        @click.prevent="handlePaginate(pagination.current_page + 1)"
                    >
                        Next
                    </a>
                </div>
                <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing
                            <span class="font-medium">{{ pagination.from }}</span>
                            to
                            <span class="font-medium">{{ pagination.to }}</span>
                            of
                            <span class="font-medium">{{ pagination.total }}</span>
                            results
                        </p>
                    </div>
                    <div>
                        <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                            <a
                                href="#"
                                class="relative inline-flex items-center rounded-l-md border border-gray-300 bg-white px-2 py-2 text-sm font-medium text-gray-500 hover:bg-gray-50 focus:z-20"
                                :class="{ 'opacity-50 cursor-not-allowed': !pagination.prev_page_url }"
                                @click.prevent="handlePaginate(pagination.current_page - 1)"
                            >
                                <span class="sr-only">Previous</span>
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <template v-for="page in pagination.last_page" :key="page">
                                <a
                                    href="#"
                                    class="relative inline-flex items-center border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-50 focus:z-20"
                                    :class="{ 'bg-blue-50 border-blue-500 text-blue-600': page === pagination.current_page }"
                                    @click.prevent="handlePaginate(page)"
                                >
                                    {{ page }}
                                </a>
                            </template>
                            <a
                                href="#"
                                class="relative inline-flex items-center rounded-r-md border border-gray-300 bg-white px-2 py-2 text-sm font-medium text-gray-500 hover:bg-gray-50 focus:z-20"
                                :class="{ 'opacity-50 cursor-not-allowed': !pagination.next_page_url }"
                                @click.prevent="handlePaginate(pagination.current_page + 1)"
                            >
                                <span class="sr-only">Next</span>
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import debounce from 'lodash/debounce';

const props = defineProps({
    title: {
        type: String,
        required: true
    },
    columns: {
        type: Array,
        required: true
    },
    items: {
        type: Array,
        required: true
    },
    pagination: {
        type: Object,
        default: null
    }
});

const emit = defineEmits(['search', 'sort', 'paginate']);

const searchQuery = ref('');
const sortKey = ref('');
const sortOrder = ref('asc');

const handleSearch = debounce(() => {
    emit('search', searchQuery.value);
}, 300);

const sort = (key) => {
    if (sortKey.value === key) {
        sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortKey.value = key;
        sortOrder.value = 'asc';
    }
    emit('sort', { key: sortKey.value, order: sortOrder.value });
};

const handlePaginate = (page) => {
    if (page > 0 && page <= props.pagination.last_page) {
        emit('paginate', page);
    }
};
</script> 