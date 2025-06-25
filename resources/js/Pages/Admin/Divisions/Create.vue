<template>
    <AdminLayout :title="`${event.name} - Create Division`">
        <div class="py-4">
            <div class="max-w-7xl mx-auto">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="text-2xl font-bold mb-6">Create Division</h2>

                        <form @submit.prevent="createDivision">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Division Name</label>
                                    <input 
                                        type="text" 
                                        v-model="form.name"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        :class="{ 'border-red-500': form.errors.name }"
                                    >
                                    <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Bracket Type</label>
                                    <select 
                                        v-model="form.bracket_type"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        :class="{ 'border-red-500': form.errors.bracket_type }"
                                    >
                                        <option value="">Select Type</option>
                                        <option value="single_elimination">Single Elimination</option>
                                        <option value="double_elimination">Double Elimination</option>
                                        <option value="round_robin">Round Robin</option>
                                    </select>
                                    <p v-if="form.errors.bracket_type" class="mt-1 text-sm text-red-600">{{ form.errors.bracket_type }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Weight Range (kg)</label>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <input 
                                                type="number" 
                                                v-model="form.min_weight" 
                                                step="0.1" 
                                                placeholder="Min"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                :class="{ 'border-red-500': form.errors.min_weight }"
                                            >
                                            <p v-if="form.errors.min_weight" class="mt-1 text-sm text-red-600">{{ form.errors.min_weight }}</p>
                                        </div>
                                        <div>
                                            <input 
                                                type="number" 
                                                v-model="form.max_weight" 
                                                step="0.1" 
                                                placeholder="Max"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                :class="{ 'border-red-500': form.errors.max_weight }"
                                            >
                                            <p v-if="form.errors.max_weight" class="mt-1 text-sm text-red-600">{{ form.errors.max_weight }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Age Range</label>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <input 
                                                type="number" 
                                                v-model="form.min_age" 
                                                placeholder="Min"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                :class="{ 'border-red-500': form.errors.min_age }"
                                            >
                                            <p v-if="form.errors.min_age" class="mt-1 text-sm text-red-600">{{ form.errors.min_age }}</p>
                                        </div>
                                        <div>
                                            <input 
                                                type="number" 
                                                v-model="form.max_age" 
                                                placeholder="Max"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                :class="{ 'border-red-500': form.errors.max_age }"
                                            >
                                            <p v-if="form.errors.max_age" class="mt-1 text-sm text-red-600">{{ form.errors.max_age }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Gender</label>
                                    <select 
                                        v-model="form.gender"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        :class="{ 'border-red-500': form.errors.gender }"
                                    >
                                        <option value="">Any Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                    <p v-if="form.errors.gender" class="mt-1 text-sm text-red-600">{{ form.errors.gender }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Belt Level</label>
                                    <select 
                                        v-model="form.belt_level"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        :class="{ 'border-red-500': form.errors.belt_level }"
                                    >
                                        <option value="">Any Belt</option>
                                        <option value="white">White</option>
                                        <option value="blue">Blue</option>
                                        <option value="purple">Purple</option>
                                        <option value="brown">Brown</option>
                                        <option value="black">Black</option>
                                    </select>
                                    <p v-if="form.errors.belt_level" class="mt-1 text-sm text-red-600">{{ form.errors.belt_level }}</p>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end space-x-3">
                                <Link
                                    :href="route('admin.events.divisions.index', event.id)"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                                >
                                    Cancel
                                </Link>
                                <button
                                    type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
                                    :disabled="form.processing"
                                >
                                    Create Division
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script>
import { defineComponent } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

export default defineComponent({
    components: {
        AdminLayout,
        Link,
    },

    props: {
        event: {
            type: Object,
            required: true,
        },
    },

    setup(props) {
        const form = useForm({
            name: '',
            bracket_type: '',
            min_weight: null,
            max_weight: null,
            min_age: null,
            max_age: null,
            gender: '',
            belt_level: '',
        })

        const createDivision = () => {
            form.post(route('admin.events.divisions.store', props.event.id))
        }

        return {
            form,
            createDivision,
        }
    },
})
</script> 