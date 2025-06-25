<template>
    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl mt-5 text-gray-800 leading-tight">
                Event Brackets: {{ event.name }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <!-- Division Tabs -->
                        <div class="mb-6">
                            <div class="border-b border-gray-200">
                                <nav class="-mb-px flex space-x-8" aria-label="Divisions">
                                    <button
                                        v-for="division in event.divisions"
                                        :key="division.id"
                                        @click="selectedDivision = division.id"
                                        :class="[
                                            selectedDivision === division.id
                                                ? 'border-indigo-500 text-indigo-600'
                                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                                            'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm'
                                        ]"
                                    >
                                        {{ division.name }}
                                    </button>
                                </nav>
                            </div>
                        </div>

                        <!-- Divisions Table -->
                        <div class="mb-6">
                            <table class="min-w-full text-left text-sm">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="px-4 py-2">Group</th>
                                        <th class="px-4 py-2">ETA Start</th>
                                        <th class="px-4 py-2">Mat</th>
                                        <th class="px-4 py-2">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="division in filteredDivisions" 
                                        :key="division.id" 
                                        class="border-b hover:bg-gray-50">
                                        <td class="px-4 py-2">
                                            {{ division.name }} / Age: {{ division.min_age }} - {{ division.max_age }} / 
                                            {{ division.belt_level }} / Weight: {{ division.min_weight }} - {{ division.max_weight }}
                                            <br/>
                                            <span class="text-sm text-gray-500">{{ division.participants_count }} participants</span>
                                        </td>
                                        <td class="px-4 py-2">{{ formatTime(division.start_time) }}</td>
                                        <td class="px-4 py-2">{{ division.mat_number }}</td>
                                        <td class="px-4 py-2">
                                            <Link
                                                :href="route('admin.brackets.division.show', { eventId: event.id, divisionId: division.id })"
                                                class="bg-indigo-600 text-white px-3 py-1 rounded hover:bg-indigo-700"
                                            >
                                                View Division
                                            </Link>
                                        </td>
                                    </tr>
                                    <tr v-if="!filteredDivisions.length" class="text-center">
                                        <td colspan="4" class="px-4 py-4">No divisions available</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Matches for Selected Division -->
                        <div v-if="selectedDivisionMatches.length > 0" class="mt-6">
                            <h3 class="text-lg font-semibold mb-4">Division Matches</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div v-for="match in selectedDivisionMatches" :key="match.id" class="border rounded-lg p-4">
                                    <div class="mb-2">
                                        <span class="text-sm font-medium text-gray-500">Round {{ match.round_number }} - Match {{ match.match_number }}</span>
                                        <span class="ml-2 text-sm text-gray-500">{{ match.mat_name }}</span>
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <div class="flex justify-between items-center p-2" :class="{'bg-green-50': match.winner_id === match.player1?.id}">
                                            <span class="text-sm">{{ match.player1?.name || 'TBD' }}</span>
                                            <span v-if="match.winner_id === match.player1?.id" class="text-green-600">Winner</span>
                                        </div>
                                        <div class="flex justify-between items-center p-2" :class="{'bg-green-50': match.winner_id === match.player2?.id}">
                                            <span class="text-sm">{{ match.player2?.name || 'TBD' }}</span>
                                            <span v-if="match.winner_id === match.player2?.id" class="text-green-600">Winner</span>
                                        </div>
                                    </div>

                                    <div class="mt-2 text-sm text-gray-500">
                                        <div>Status: {{ match.status }}</div>
                                        <div v-if="match.scheduled_time">Scheduled: {{ formatDateTime(match.scheduled_time) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

export default {
    components: {
        AdminLayout,
        Link
    },
    props: {
        event: {
            type: Object,
            required: true
        },
        matches: {
            type: Object,
            required: true
        }
    },
    setup(props) {
        const selectedDivision = ref(props.event.divisions[0]?.id)

        const filteredDivisions = computed(() => {
            return props.event.divisions
        })

        const selectedDivisionMatches = computed(() => {
            return props.matches[selectedDivision.value] || []
        })

        const formatDateTime = (dateTimeString) => {
            if (!dateTimeString) return ''
            return new Date(dateTimeString).toLocaleString()
        }

        const formatTime = (timeString) => {
            if (!timeString) return 'Not scheduled'
            return new Date(timeString).toLocaleTimeString()
        }

        return {
            selectedDivision,
            selectedDivisionMatches,
            formatDateTime,
            formatTime,
            filteredDivisions
        }
    }
}
</script> 