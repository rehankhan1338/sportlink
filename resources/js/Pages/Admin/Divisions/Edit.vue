<template>
    <AdminLayout :title="`Edit Division - ${division.name}`">
        <div class="py-4">
            <div class="max-w-7xl mx-auto">
                <!-- Division Header -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h2 class="text-2xl font-bold">{{ division.name }}</h2>
                                <div class="mt-2 space-y-1">
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium">Bracket Type:</span> 
                                        {{ formatBracketType(division.bracket_type) }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium">Weight Class:</span>
                                        {{ division.weight_range }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium">Age Range:</span>
                                        {{ division.age_range }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium">Gender:</span>
                                        {{ division.gender ? (division.gender.charAt(0).toUpperCase() + division.gender.slice(1)) : 'Not Set' }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium">Belt Level:</span>
                                        {{ formatBeltLevel(division.belt_level) }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-3xl font-bold text-indigo-600">{{ athletes.length }}</div>
                                <div class="text-sm text-gray-500">Athletes Registered</div>
                            </div>
                        </div>

                        <!-- Edit Form -->
                        <form @submit.prevent="updateDivision" class="mt-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Division Name</label>
                                    <input type="text" v-model="form.name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Belt Level</label>
                                    <select v-model="form.belt_level" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Any Belt</option>
                                        <option value="white">White</option>
                                        <option value="blue">Blue</option>
                                        <option value="purple">Purple</option>
                                        <option value="brown">Brown</option>
                                        <option value="black">Black</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Weight Range (kg)</label>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <input type="number" v-model="form.min_weight" step="0.1" placeholder="Min" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                        <div>
                                            <input type="number" v-model="form.max_weight" step="0.1" placeholder="Max" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Age Range</label>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <input type="number" v-model="form.min_age" placeholder="Min" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                        <div>
                                            <input type="number" v-model="form.max_age" placeholder="Max" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Gender</label>
                                    <select v-model="form.gender" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Any Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end space-x-3">
                                <button type="button" @click="resetForm" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Reset
                                </button>
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Athletes List -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold">Athletes</h3>
                            <div class="space-x-2">
                                <button
                                    v-if="canGenerateBrackets"
                                    @click="generateBrackets"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
                                >
                                    Generate Bracket
                                </button>
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            ID
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Athlete
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Weight
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Age
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Belt Level
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
                                    <tr v-for="athlete in athletes" :key="athlete.id">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ athlete.id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img 
                                                        :src="athlete.passport_image_path || '/default-avatar.png'" 
                                                        class="h-10 w-10 rounded-full object-cover"
                                                        :alt="athlete.name"
                                                    >
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ athlete.name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ athlete.email }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ athlete.weight }}kg
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ athlete.age }} years
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ formatBeltLevel(athlete.belt_level) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                :class="{
                                                    'bg-green-100 text-green-800': athlete.pivot.status === 'checked_in',
                                                    'bg-yellow-100 text-yellow-800': athlete.pivot.status === 'registered',
                                                    'bg-red-100 text-red-800': athlete.pivot.status === 'no_show'
                                                }">
                                                {{ athlete.pivot.status?.replace('_', ' ').toUpperCase() }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <button
                                                v-if="athlete.pivot.status === 'registered'"
                                                @click="updateAthleteStatus(athlete.id, 'checked_in')"
                                                class="text-indigo-600 hover:text-indigo-900"
                                            >
                                                Check In
                                            </button>
                                            <button
                                                v-if="athlete.pivot.status === 'registered'"
                                                @click="updateAthleteStatus(athlete.id, 'no_show')"
                                                class="ml-2 text-red-600 hover:text-red-900"
                                            >
                                                Mark No Show
                                            </button>
                                            <button
                                                v-if="athlete.pivot.status !== 'registered'"
                                                @click="updateAthleteStatus(athlete.id, 'registered')"
                                                class="text-gray-600 hover:text-gray-900"
                                            >
                                                Reset Status
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script>
import { defineComponent, ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

export default defineComponent({
    components: {
        AdminLayout,
    },

    props: {
        division: {
            type: Object,
            required: true,
        },
        athletes: {
            type: Array,
            required: true,
        },
    },

    setup(props) {
        const form = ref({
            name: props.division.name,
            min_weight: props.division.min_weight,
            max_weight: props.division.max_weight,
            min_age: props.division.min_age,
            max_age: props.division.max_age,
            gender: props.division.gender,
            belt_level: props.division.belt_level,
        })

        const resetForm = () => {
            form.value = {
                name: props.division.name,
                min_weight: props.division.min_weight,
                max_weight: props.division.max_weight,
                min_age: props.division.min_age,
                max_age: props.division.max_age,
                gender: props.division.gender,
                belt_level: props.division.belt_level,
            }
        }

        const updateDivision = () => {
            router.patch(`/admin/events/${props.division.event_id}/divisions/${props.division.id}`, form.value)
        }

        const updateAthleteStatus = (athleteId, status) => {
            router.patch(`/admin/events/${props.division.event_id}/divisions/${props.division.id}/athletes/${athleteId}`, {
                status
            })
        }

        const formatBracketType = (type) => {
            if (!type) return 'Not Set'
            return type.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')
        }

        const formatBeltLevel = (level) => {
            if (!level) return 'Any Belt'
            return level.charAt(0).toUpperCase() + level.slice(1)
        }

        const canGenerateBrackets = computed(() => {
            const checkedInCount = props.athletes.filter(a => a.pivot.status === 'checked_in').length

            if (checkedInCount < 2) return false

            if (props.division.bracket_type === 'round_robin') {
                return checkedInCount >= 3
            }

            return true
        })

        const generateBrackets = () => {
            router.post(`/admin/events/${props.division.event_id}/divisions/${props.division.id}/brackets/generate`)
        }

        return {
            form,
            resetForm,
            updateDivision,
            updateAthleteStatus,
            formatBracketType,
            formatBeltLevel,
            canGenerateBrackets,
            generateBrackets,
        }
    },
})
</script> 