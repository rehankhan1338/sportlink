<template>
    <div class="border rounded-lg p-4 bg-gray-50">
        <div class="flex justify-between items-center">
            <span class="text-sm text-gray-500">Match {{ match.match_number }}</span>
            <span class="text-sm text-gray-500">{{ formatTime(match.scheduled_time) }}</span>
        </div>
        <div class="mt-2 space-y-2">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <select v-if="canEditMatch" v-model="match.player1_id" 
                        class="form-select text-sm" @change="$emit('update', match)">
                        <option value="">Select Athlete</option>
                        <option v-for="athlete in athletes" :key="athlete.id" :value="athlete.id">
                            {{ athlete.name }}
                        </option>
                    </select>
                    <span v-else>{{ getAthleteById(match.player1_id)?.name || 'TBD' }}</span>
                    <button v-if="canEditMatch && match.player1_id" 
                        @click="$emit('walkover', match.id, match.player2_id)"
                        class="ml-2 text-xs text-red-600 hover:text-red-800">
                        No Show
                    </button>
                </div>
                <div class="flex items-center space-x-2">
                    <input v-if="match.status === 'in_progress'" 
                        v-model="match.athlete1_score" type="number" min="0"
                        class="form-input w-16 text-sm" @change="$emit('score-update', match)">
                    <span v-else class="font-semibold">{{ match.athlete1_score || '-' }}</span>
                    <button v-if="match.status === 'in_progress'" 
                        @click="$emit('winner', match.id, match.player1_id)"
                        class="text-xs text-green-600 hover:text-green-800">
                        Win
                    </button>
                </div>
            </div>
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <select v-if="canEditMatch" v-model="match.player2_id" 
                        class="form-select text-sm" @change="$emit('update', match)">
                        <option value="">Select Athlete</option>
                        <option v-for="athlete in athletes" :key="athlete.id" :value="athlete.id">
                            {{ athlete.name }}
                        </option>
                    </select>
                    <span v-else>{{ getAthleteById(match.player2_id)?.name || 'TBD' }}</span>
                    <button v-if="canEditMatch && match.player2_id" 
                        @click="$emit('walkover', match.id, match.player1_id)"
                        class="ml-2 text-xs text-red-600 hover:text-red-800">
                        No Show
                    </button>
                </div>
                <div class="flex items-center space-x-2">
                    <input v-if="match.status === 'in_progress'" 
                        v-model="match.athlete2_score" type="number" min="0"
                        class="form-input w-16 text-sm" @change="$emit('score-update', match)">
                    <span v-else class="font-semibold">{{ match.athlete2_score || '-' }}</span>
                    <button v-if="match.status === 'in_progress'" 
                        @click="$emit('winner', match.id, match.player2_id)"
                        class="text-xs text-green-600 hover:text-green-800">
                        Win
                    </button>
                </div>
            </div>
            <div class="mt-2 text-sm">
                <span :class="{
                    'text-yellow-600': match.status === 'pending',
                    'text-blue-600': match.status === 'in_progress',
                    'text-green-600': match.status === 'completed',
                    'text-red-600': match.status === 'walkover'
                }">
                    {{ match.status.toUpperCase() }}
                </span>
                <button v-if="match.status === 'pending' && match.player1_id && match.player2_id"
                    @click="$emit('start', match)"
                    class="ml-2 text-xs text-blue-600 hover:text-blue-800">
                    Start Match
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        match: {
            type: Object,
            required: true
        },
        athletes: {
            type: Array,
            required: true
        },
        canEditMatch: {
            type: Boolean,
            required: true
        }
    },
    methods: {
        formatTime(datetime) {
            return new Date(datetime).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
        },
        getAthleteById(athleteId) {
            return this.athletes.find(a => a.id === athleteId) || null
        }
    }
}
</script> 