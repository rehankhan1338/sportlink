<script setup>
import { Head } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import AcademyForm from './Form.vue'

defineProps({
    academy: {
        type: Object,
        required: true
    }
})
</script>

<template>
    <Head title="Edit Academy" />

    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Academy
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <AcademyForm :academy="academy" />
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template> 