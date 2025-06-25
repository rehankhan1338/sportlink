<template>
    <AdminLayout title="Dashboard">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="text-2xl font-bold mb-6">Admin Dashboard</h2>

                        <!-- Quick Stats -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div class="bg-indigo-50 p-6 rounded-lg">
                                <div class="text-2xl font-bold text-indigo-600">{{ stats.events }}</div>
                                <div class="text-sm text-gray-600">Active Events</div>
                            </div>
                            <div class="bg-green-50 p-6 rounded-lg">
                                <div class="text-2xl font-bold text-green-600">{{ stats.divisions }}</div>
                                <div class="text-sm text-gray-600">Total Divisions</div>
                            </div>
                            <div class="bg-purple-50 p-6 rounded-lg">
                                <div class="text-2xl font-bold text-purple-600">{{ stats.athletes }}</div>
                                <div class="text-sm text-gray-600">Registered Athletes</div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold">Quick Actions</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <Link
                                    v-for="action in quickActions"
                                    :key="action.name"
                                    :href="action.route"
                                    class="flex items-center p-4 bg-white border rounded-lg hover:bg-gray-50"
                                >
                                    <div class="flex-1">
                                        <div class="font-medium">{{ action.name }}</div>
                                        <div class="text-sm text-gray-500">{{ action.description }}</div>
                                    </div>
                                    <div class="text-gray-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script>
import { defineComponent } from 'vue'
import { Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

export default defineComponent({
    components: {
        AdminLayout,
        Link,
    },

    props: {
        stats: {
            type: Object,
            required: true,
            default: () => ({
                events: 0,
                divisions: 0,
                athletes: 0,
            }),
        },
    },

    setup() {
        const quickActions = [
            {
                name: 'Manage Events',
                description: 'Create and manage tournament events',
                route: route('admin.events.index'),
            },
            {
                name: 'Manage Divisions',
                description: 'Configure weight classes and brackets',
                route: route('admin.divisions.index'),
            },
            {
                name: 'View Athletes',
                description: 'Review registered participants',
                route: route('admin.athletes.index'),
            },
            {
                name: 'Manage Brackets',
                description: 'Generate and update tournament brackets',
                route: route('admin.brackets.index'),
            },
            {
                name: 'System Settings',
                description: 'Configure tournament settings',
                route: route('admin.settings.index'),
            },
        ]

        return {
            quickActions,
        }
    },
})
</script> 