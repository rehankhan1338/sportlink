<template>
    <AdminLayout :title="`${event.name} - Divisions`">
        <div class="py-4">
            <div class="max-w-7xl mx-auto">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Divisions</h2>
                    <Link
                        :href="route('admin.events.divisions.create', event.id)"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
                    >
                        Create Division
                    </Link>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Name
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Bracket Type
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Weight Class
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Age Range
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Athletes
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="division in divisions" :key="division.id">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ division.name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ division.belt_level ? formatBeltLevel(division.belt_level) : 'Any Belt' }}
                                                {{ division.gender ? `• ${formatGender(division.gender)}` : '' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ formatBracketType(division.bracket_type) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ division.weight_range }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ division.age_range }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ division.athletes_count }}</div>
                                            <div class="text-sm text-gray-500">Athletes</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                :class="{
                                                    'bg-gray-100 text-gray-800': division.status === 'draft',
                                                    'bg-green-100 text-green-800': division.status === 'published',
                                                    'bg-blue-100 text-blue-800': division.status === 'in_progress',
                                                    'bg-purple-100 text-purple-800': division.status === 'completed'
                                                }">
                                                {{ formatStatus(division.status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div class="flex space-x-3">
                                                <Link
                                                    :href="route('admin.events.divisions.edit', [event.id, division.id])"
                                                    class="text-indigo-600 hover:text-indigo-900"
                                                >
                                                    Edit
                                                </Link>
                                                <button
                                                    v-if="division.status === 'draft'"
                                                    @click="confirmDelete(division)"
                                                    class="text-red-600 hover:text-red-900"
                                                >
                                                    Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <Modal :show="showDeleteModal" @close="closeDeleteModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Delete Division
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Are you sure you want to delete this division? This action cannot be undone.
                </p>

                <div class="mt-6 flex justify-end space-x-3">
                    <button
                        type="button"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                        @click="closeDeleteModal"
                    >
                        Cancel
                    </button>
                    <button
                        type="button"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700"
                        @click="deleteDivision"
                    >
                        Delete
                    </button>
                </div>
            </div>
        </Modal>
    </AdminLayout>
</template>

<script>
import { defineComponent, ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Modal from '@/Components/Modal.vue'

export default defineComponent({
    components: {
        AdminLayout,
        Link,
        Modal,
    },

    props: {
        event: {
            type: Object,
            required: true,
        },
        divisions: {
            type: Array,
            required: true,
        },
    },

    setup() {
        const showDeleteModal = ref(false)
        const divisionToDelete = ref(null)

        const formatBracketType = (type) => {
            if (!type) return 'Not Set'
            return type.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')
        }

        const formatBeltLevel = (level) => {
            if (!level) return 'Any Belt'
            return level.charAt(0).toUpperCase() + level.slice(1)
        }

        const formatGender = (gender) => {
            if (!gender) return ''
            return gender.charAt(0).toUpperCase() + gender.slice(1)
        }

        const formatStatus = (status) => {
            if (!status) return 'Unknown'
            return status.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')
        }

        const confirmDelete = (division) => {
            divisionToDelete.value = division
            showDeleteModal.value = true
        }

        const closeDeleteModal = () => {
            showDeleteModal.value = false
            divisionToDelete.value = null
        }

        const deleteDivision = () => {
            if (!divisionToDelete.value) return

            router.delete(route('admin.events.divisions.destroy', [
                divisionToDelete.value.event_id,
                divisionToDelete.value.id
            ]), {
                onSuccess: () => {
                    closeDeleteModal()
                },
            })
        }

        return {
            showDeleteModal,
            formatBracketType,
            formatBeltLevel,
            formatGender,
            formatStatus,
            confirmDelete,
            closeDeleteModal,
            deleteDivision,
        }
    },
})
</script> 