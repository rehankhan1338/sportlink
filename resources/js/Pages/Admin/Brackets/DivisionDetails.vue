<template>
    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl mt-5 text-gray-800 leading-tight">
                Division Details: {{ division.name }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Division Information -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold mb-4">Division Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-600">Age Range</p>
                                <p class="font-medium">{{ division.min_age }} - {{ division.max_age }} years</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Weight Range</p>
                                <p class="font-medium">{{ division.min_weight }} - {{ division.max_weight }} kg</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Belt Level</p>
                                <p class="font-medium">{{ division.belt_level }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Gender</p>
                                <p class="font-medium">{{ division.gender }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Mat Number</p>
                                <p class="font-medium">{{ division.mat_number || 'Not assigned' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Start Time</p>
                                <p class="font-medium">{{ formatTime(division.start_time) }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Bracket Type</p>
                                <p class="font-medium">{{ formatBracketType(division.bracket_type) }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Total Participants</p>
                                <p class="font-medium">{{ division.participants_count }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Registered Athletes -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold mb-4">Registered Athletes</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Weight</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Age</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Academy</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="athlete in athletes" :key="athlete.id">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full" :src="athlete.avatar ? `/storage/${athlete.avatar}` : '/images/default-avatar.png'" alt="">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ athlete.name }}</div>
                                                    <div class="text-sm text-gray-500">{{ athlete.email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ athlete.weight }} kg</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ athlete.age }} years</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ athlete.academy }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Registered
                                            </span>
                                        </td>
                                    </tr>
                                    <tr v-if="!athletes.length">
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                            No athletes registered in this division
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Matches -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Division Matches</h3>
                            <button @click="showCreateMatchModal = true" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                Create New Match
                            </button>
                        </div>

                        <!-- Match Creation Modal -->
                        <div v-if="showCreateMatchModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                            <div class="bg-white p-6 rounded-lg w-full max-w-lg">
                                <h4 class="text-lg font-semibold mb-4">Create New Match</h4>
                                <form @submit.prevent="createMatch">
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Round Type</label>
                                        <select v-model="newMatch.round_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option value="">Select Round Type</option>
                                            <!-- Single Elimination Options -->
                                            <template v-if="division.bracket_type === 'single_elimination'">
                                                <option value="semifinal">Semifinal</option>
                                                <option value="bronze">Bronze Match</option>
                                                <option value="final">Final</option>
                                            </template>
                                            <!-- Double Elimination Options -->
                                            <template v-if="division.bracket_type === 'double_elimination'">
                                                <option value="semifinal">Semifinal</option>
                                                <option value="final">Final</option>
                                            </template>
                                            <!-- Round Robin Options -->
                                            <template v-if="division.bracket_type === 'round_robin'">
                                                <option value="final">Final</option>
                                            </template>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Mat Name</label>
                                        <input v-model="newMatch.mat_name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Athlete 1</label>
                                        <select v-model="newMatch.athlete1_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option value="">Select Athlete</option>
                                            <option v-for="athlete in athletes" :key="athlete.id" :value="athlete.id">
                                                {{ athlete.name }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Athlete 2</label>
                                        <select v-model="newMatch.athlete2_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option value="">Select Athlete</option>
                                            <option v-for="athlete in athletes" :key="athlete.id" :value="athlete.id">
                                                {{ athlete.name }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Scheduled Time</label>
                                        <input v-model="newMatch.scheduled_time" type="datetime-local" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    <div class="flex justify-end gap-2">
                                        <button type="button" @click="showCreateMatchModal = false" class="px-4 py-2 text-gray-700 bg-gray-100 rounded hover:bg-gray-200">
                                            Cancel
                                        </button>
                                        <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">
                                            Create Match
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Match List -->
                        <div class="space-y-8">
                            <!-- Semifinals -->
                            <div v-if="semifinalMatches.length > 0" class="space-y-4">
                                <h3 class="text-lg font-semibold">Semifinals</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    <div v-for="match in semifinalMatches" :key="match.id" class="bg-gray-50 p-4 rounded-lg shadow">
                                        <div class="flex justify-between items-start mb-4">
                                            <h4 class="font-semibold">Match {{ match.id }}</h4>
                                            <div class="flex gap-2">
                                                <button @click="editMatch(match)" class="text-blue-500 hover:text-blue-600">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button @click="deleteMatch(match.id)" class="text-red-500 hover:text-red-600">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div class="text-sm space-y-2">
                                            <p><span class="font-medium">Mat:</span> {{ match.mat_name }}</p>
                                            <p><span class="font-medium">Time:</span> {{ formatDateTime(match.scheduled_time) }}</p>
                                            
                                            <!-- Athletes -->
                                            <div class="mt-3 space-y-2">
                                                <div class="flex items-center justify-between">
                                                    <span class="font-medium">{{ match.player1?.name || 'TBD' }}</span>
                                                    <span :class="{'text-green-600 font-bold': match.winner_id === match.player1?.id}">
                                                        {{ match.winner_id === match.player1?.id ? 'Winner' : '' }}
                                                    </span>
                                                </div>
                                                <div class="flex items-center justify-between">
                                                    <span class="font-medium">{{ match.player2?.name || 'TBD' }}</span>
                                                    <span :class="{'text-green-600 font-bold': match.winner_id === match.player2?.id}">
                                                        {{ match.winner_id === match.player2?.id ? 'Winner' : '' }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Match Status -->
                                            <div class="mt-3">
                                                <p class="font-medium">Status: 
                                                    <span :class="{
                                                        'text-yellow-600': match.status === 'pending',
                                                        'text-green-600': match.status === 'completed',
                                                        'text-blue-600': match.status === 'in_progress'
                                                    }">
                                                        {{ match.status }}
                                                    </span>
                                                </p>
                                            </div>

                                            <!-- Match Actions -->
                                            <div class="mt-3 flex gap-2" v-if="match.status !== 'completed'">
                                                <button 
                                                    @click="updateMatchStatus(match.id, 'in_progress')" 
                                                    class="px-2 py-1 text-xs bg-blue-500 text-white rounded hover:bg-blue-600"
                                                    v-if="match.status === 'pending'">
                                                    Start Match
                                                </button>
                                                <button 
                                                    @click="showMatchResultModal(match)" 
                                                    class="px-2 py-1 text-xs bg-green-500 text-white rounded hover:bg-green-600"
                                                    v-if="match.status === 'in_progress'">
                                                    Record Result
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bronze Match -->
                            <div v-if="bronzeMatches.length > 0" class="space-y-4">
                                <h3 class="text-lg font-semibold">Bronze Match</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    <div v-for="match in bronzeMatches" :key="match.id" class="bg-gray-50 p-4 rounded-lg shadow">
                                        <div class="flex justify-between items-start mb-4">
                                            <h4 class="font-semibold">Match {{ match.id }}</h4>
                                            <div class="flex gap-2">
                                                <button @click="editMatch(match)" class="text-blue-500 hover:text-blue-600">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button @click="deleteMatch(match.id)" class="text-red-500 hover:text-red-600">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div class="text-sm space-y-2">
                                            <p><span class="font-medium">Mat:</span> {{ match.mat_name }}</p>
                                            <p><span class="font-medium">Time:</span> {{ formatDateTime(match.scheduled_time) }}</p>
                                            
                                            <!-- Athletes -->
                                            <div class="mt-3 space-y-2">
                                                <div class="flex items-center justify-between">
                                                    <span class="font-medium">{{ match.player1?.name || 'TBD' }}</span>
                                                    <span :class="{'text-green-600 font-bold': match.winner_id === match.player1?.id}">
                                                        {{ match.winner_id === match.player1?.id ? 'Winner' : '' }}
                                                    </span>
                                                </div>
                                                <div class="flex items-center justify-between">
                                                    <span class="font-medium">{{ match.player2?.name || 'TBD' }}</span>
                                                    <span :class="{'text-green-600 font-bold': match.winner_id === match.player2?.id}">
                                                        {{ match.winner_id === match.player2?.id ? 'Winner' : '' }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Match Status -->
                                            <div class="mt-3">
                                                <p class="font-medium">Status: 
                                                    <span :class="{
                                                        'text-yellow-600': match.status === 'pending',
                                                        'text-green-600': match.status === 'completed',
                                                        'text-blue-600': match.status === 'in_progress'
                                                    }">
                                                        {{ match.status }}
                                                    </span>
                                                </p>
                                            </div>

                                            <!-- Match Actions -->
                                            <div class="mt-3 flex gap-2" v-if="match.status !== 'completed'">
                                                <button 
                                                    @click="updateMatchStatus(match.id, 'in_progress')" 
                                                    class="px-2 py-1 text-xs bg-blue-500 text-white rounded hover:bg-blue-600"
                                                    v-if="match.status === 'pending'">
                                                    Start Match
                                                </button>
                                                <button 
                                                    @click="showMatchResultModal(match)" 
                                                    class="px-2 py-1 text-xs bg-green-500 text-white rounded hover:bg-green-600"
                                                    v-if="match.status === 'in_progress'">
                                                    Record Result
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Finals -->
                            <div v-if="finalMatches.length > 0" class="space-y-4">
                                <h3 class="text-lg font-semibold">Finals</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    <div v-for="match in finalMatches" :key="match.id" class="bg-gray-50 p-4 rounded-lg shadow">
                                        <div class="flex justify-between items-start mb-4">
                                            <h4 class="font-semibold">Match {{ match.id }}</h4>
                                            <div class="flex gap-2">
                                                <button @click="editMatch(match)" class="text-blue-500 hover:text-blue-600">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button @click="deleteMatch(match.id)" class="text-red-500 hover:text-red-600">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div class="text-sm space-y-2">
                                            <p><span class="font-medium">Mat:</span> {{ match.mat_name }}</p>
                                            <p><span class="font-medium">Time:</span> {{ formatDateTime(match.scheduled_time) }}</p>
                                            
                                            <!-- Athletes -->
                                            <div class="mt-3 space-y-2">
                                                <div class="flex items-center justify-between">
                                                    <span class="font-medium">{{ match.player1?.name || 'TBD' }}</span>
                                                    <span :class="{'text-green-600 font-bold': match.winner_id === match.player1?.id}">
                                                        {{ match.winner_id === match.player1?.id ? 'Winner' : '' }}
                                                    </span>
                                                </div>
                                                <div class="flex items-center justify-between">
                                                    <span class="font-medium">{{ match.player2?.name || 'TBD' }}</span>
                                                    <span :class="{'text-green-600 font-bold': match.winner_id === match.player2?.id}">
                                                        {{ match.winner_id === match.player2?.id ? 'Winner' : '' }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Match Status -->
                                            <div class="mt-3">
                                                <p class="font-medium">Status: 
                                                    <span :class="{
                                                        'text-yellow-600': match.status === 'pending',
                                                        'text-green-600': match.status === 'completed',
                                                        'text-blue-600': match.status === 'in_progress'
                                                    }">
                                                        {{ match.status }}
                                                    </span>
                                                </p>
                                            </div>

                                            <!-- Match Actions -->
                                            <div class="mt-3 flex gap-2" v-if="match.status !== 'completed'">
                                                <button 
                                                    @click="updateMatchStatus(match.id, 'in_progress')" 
                                                    class="px-2 py-1 text-xs bg-blue-500 text-white rounded hover:bg-blue-600"
                                                    v-if="match.status === 'pending'">
                                                    Start Match
                                                </button>
                                                <button 
                                                    @click="showMatchResultModal(match)" 
                                                    class="px-2 py-1 text-xs bg-green-500 text-white rounded hover:bg-green-600"
                                                    v-if="match.status === 'in_progress'">
                                                    Record Result
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Match Result Modal -->
                        <div v-if="showResultModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                            <div class="bg-white p-6 rounded-lg w-full max-w-lg">
                                <h4 class="text-lg font-semibold mb-4">Record Match Result</h4>
                                <form @submit.prevent="submitMatchResult">
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Winner</label>
                                        <select v-model="matchResult.winner_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option :value="selectedMatch?.player1?.id">{{ selectedMatch?.player1?.name }}</option>
                                            <option :value="selectedMatch?.player2?.id">{{ selectedMatch?.player2?.name }}</option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Score Details (optional)</label>
                                        <textarea 
                                            v-model="matchResult.score_details" 
                                            placeholder="Enter score details..."
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            rows="3">
                                        </textarea>
                                    </div>
                                    <div class="flex justify-end gap-2">
                                        <button type="button" @click="showResultModal = false" class="px-4 py-2 text-gray-700 bg-gray-100 rounded hover:bg-gray-200">
                                            Cancel
                                        </button>
                                        <button type="submit" class="px-4 py-2 text-white bg-green-500 rounded hover:bg-green-600">
                                            Submit Result
                                        </button>
                                    </div>
                                </form>
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
import { ref } from 'vue'

export default {
    components: {
        AdminLayout,
        Link
    },
    props: {
        division: {
            type: Object,
            required: true
        },
        athletes: {
            type: Array,
            default: () => []
        },
        matches: {
            type: Array,
            default: () => []
        }
    },
    setup() {
        const showCreateMatchModal = ref(false)
        const showResultModal = ref(false)
        const selectedMatch = ref(null)
        const newMatch = ref({
            round_type: '',
            mat_name: '',
            athlete1_id: '',
            athlete2_id: '',
            scheduled_time: ''
        })
        const matchResult = ref({
            winner_id: '',
            score_details: ''
        })

        return {
            showCreateMatchModal,
            showResultModal,
            selectedMatch,
            newMatch,
            matchResult
        }
    },
    computed: {
        semifinalMatches() {
            return this.matches.filter(match => match.round_type === 'semifinal');
        },
        bronzeMatches() {
            return this.matches.filter(match => match.round_type === 'bronze');
        },
        finalMatches() {
            return this.matches.filter(match => match.round_type === 'final');
        }
    },
    methods: {
        formatTime(timeString) {
            if (!timeString) return 'Not scheduled'
            return new Date(timeString).toLocaleTimeString()
        },
        formatDateTime(dateTimeString) {
            if (!dateTimeString) return ''
            return new Date(dateTimeString).toLocaleString()
        },
        formatBracketType(type) {
            if (!type) return ''
            return type.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')
        },
        formatRoundType(round_number) {
            if (this.division.bracket_type === 'single_elimination') {
                switch (round_number) {
                    case 1: return 'Semifinal'
                    case 2: return 'Bronze Match'
                    case 3: return 'Final'
                    default: return `Round ${round_number}`
                }
            } else if (this.division.bracket_type === 'double_elimination') {
                switch (round_number) {
                    case 1: return 'Semifinal'
                    case 2: return 'Final'
                    default: return `Round ${round_number}`
                }
            } else {
                return round_number === 1 ? 'Final' : `Round ${round_number}`
            }
        },
        async createMatch() {
            try {
                // Convert round_type to round_number for backend compatibility
                const roundNumbers = {
                    'semifinal': 1,
                    'bronze': 2,
                    'final': this.division.bracket_type === 'single_elimination' ? 3 : 2
                }

                const matchData = {
                    ...this.newMatch,
                    event_id: this.division.event_id,
                    round_number: roundNumbers[this.newMatch.round_type]
                }

                await this.$inertia.post(`/admin/events/${this.division.event_id}/divisions/${this.division.id}/matches`, matchData)
                this.showCreateMatchModal = false
                this.newMatch = {
                    round_type: '',
                    mat_name: '',
                    athlete1_id: '',
                    athlete2_id: '',
                    scheduled_time: ''
                }
            } catch (error) {
                console.error('Error creating match:', error)
            }
        },
        editMatch(match) {
            this.newMatch = { ...match }
            this.showCreateMatchModal = true
        },
        async deleteMatch(matchId) {
            if (!confirm('Are you sure you want to delete this match?')) return
            
            try {
                await this.$inertia.delete(`/admin/events/${this.division.event_id}/divisions/${this.division.id}/matches/${matchId}`)
            } catch (error) {
                console.error('Error deleting match:', error)
            }
        },
        async updateMatchStatus(matchId, status) {
            try {
                await this.$inertia.patch(`/admin/events/${this.division.event_id}/divisions/${this.division.id}/matches/${matchId}/status`, {
                    status: status
                })
            } catch (error) {
                console.error('Error updating match status:', error)
            }
        },
        showMatchResultModal(match) {
            this.selectedMatch = match
            this.matchResult.winner_id = ''
            this.matchResult.score_details = ''
            this.showResultModal = true
        },
        async submitMatchResult() {
            try {
                await this.$inertia.patch(`/admin/events/${this.division.event_id}/divisions/${this.division.id}/matches/${this.selectedMatch.id}/result`, this.matchResult)
                this.showResultModal = false
                this.selectedMatch = null
                this.matchResult = {
                    winner_id: '',
                    score_details: ''
                }
            } catch (error) {
                console.error('Error submitting match result:', error)
            }
        }
    }
}
</script> 