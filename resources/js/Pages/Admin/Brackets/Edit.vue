<template>
    <AdminLayout :title="`Edit Bracket - ${division.name}`">
        <div class="py-4">
            <div class="max-w-7xl mx-auto">
                <!-- Division Header -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h2 class="text-2xl font-bold">{{ division.name || 'Unnamed Division' }}</h2>
                                <div class="mt-2 space-y-1">
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium">Bracket Type:</span> 
                                        {{ division.bracket_type ? (division.bracket_type.replace('_', ' ').charAt(0).toUpperCase() + division.bracket_type.slice(1)) : 'Not Set' }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium">Weight Class:</span>
                                        {{ division.min_weight != null ? `${division.min_weight}kg` : '?' }} - {{ division.max_weight != null ? `${division.max_weight}kg` : '?' }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium">Age Range:</span>
                                        {{ division.min_age != null ? division.min_age : '?' }} - {{ division.max_age != null ? division.max_age : '?' }} years
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium">Gender:</span>
                                        {{ division.gender ? (division.gender.charAt(0).toUpperCase() + division.gender.slice(1)) : 'Not Set' }}
                                    </p>
                            </div>
                            </div>
                            <div class="text-right">
                                <div class="text-3xl font-bold text-indigo-600">{{ athletes.length }}</div>
                                <div class="text-sm text-gray-500">Athletes Registered</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Athletes List -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold">Athletes</h3>
                            <div class="space-x-2">
                                <button
                                    v-if="matches.length === 0"
                                    @click="generateMatches"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
                                >
                                    Generate Bracket
                                </button>
                                <button
                                    v-else
                                    @click="confirmRegenerate"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700"
                                >
                                    Regenerate Bracket
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
                                                        alt=""
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
                                            {{ athlete.belt_level }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                :class="{
                                                    'bg-green-100 text-green-800': athlete.status === 'checked_in',
                                                    'bg-yellow-100 text-yellow-800': athlete.status === 'registered',
                                                    'bg-red-100 text-red-800': athlete.status === 'no_show'
                                                }">
                                                {{ athlete.status?.replace('_', ' ').toUpperCase() }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Matches Section -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-bold mb-4">Bracket</h3>
                        
                        <div v-if="matches.length === 0" class="text-center py-8 text-gray-500">
                            No matches created yet. Click "Generate Bracket" to create the tournament structure.
                        </div>
                        
                        <div v-else>
                            <!-- Single Elimination Bracket -->
                            <div v-if="division?.bracket_type === 'single_elimination'" class="space-y-4">
                                <div v-if="matchesByRound[4]" class="mb-8">
                                    <h4 class="text-lg font-semibold mb-4">Semi Finals</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div v-for="match in matchesByRound[4]" :key="match.id">
                                            <MatchTemplate 
                                                :match="match"
                                                :athletes="athletes"
                                                :can-edit-match="canEditMatch(match)"
                                                @update="updateMatch"
                                                @walkover="markWalkover"
                                                @winner="updateMatchResult"
                                                @score-update="updateScore"
                                                @start="startMatch"
                                            />
                                        </div>
                                    </div>
                                </div>

                                <div v-if="matchesByRound[2]" class="mb-8">
                                    <h4 class="text-lg font-semibold mb-4">Finals</h4>
                                    <div class="max-w-2xl mx-auto">
                                        <div v-for="match in matchesByRound[2]" :key="match.id">
                                            <MatchTemplate 
                                                :match="match"
                                                :athletes="athletes"
                                                :can-edit-match="canEditMatch(match)"
                                                @update="updateMatch"
                                                @walkover="markWalkover"
                                                @winner="updateMatchResult"
                                                @score-update="updateScore"
                                                @start="startMatch"
                                            />
                                        </div>
                                    </div>
                                </div>

                                <div v-if="matchesByRound[3]" class="mb-8">
                                    <h4 class="text-lg font-semibold mb-4">Bronze Match</h4>
                                    <div class="max-w-2xl mx-auto">
                                        <div v-for="match in matchesByRound[3]" :key="match.id">
                                            <MatchTemplate 
                                                :match="match"
                                                :athletes="athletes"
                                                :can-edit-match="canEditMatch(match)"
                                                @update="updateMatch"
                                                @walkover="markWalkover"
                                                @winner="updateMatchResult"
                                                @score-update="updateScore"
                                                @start="startMatch"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Double Elimination Bracket -->
                            <div v-else-if="division?.bracket_type === 'double_elimination'" class="space-y-8">
                                <!-- Winners Bracket -->
                                <div>
                                    <h4 class="text-lg font-semibold mb-4">Winners Bracket</h4>
                                    <div class="space-y-4">
                                        <div v-for="(matches, round) in matchesByRound" :key="round"
                                            v-if="matches[0]?.bracket === 'winners'" class="mb-8">
                                            <h5 class="text-md font-medium mb-2">Round {{ round }}</h5>
                                            <div class="space-y-2">
                                                <div v-for="match in matches" :key="match.id">
                                                    <MatchTemplate 
                                                        :match="match"
                                                        :athletes="athletes"
                                                        :can-edit-match="canEditMatch(match)"
                                                        @update="updateMatch"
                                                        @walkover="markWalkover"
                                                        @winner="updateMatchResult"
                                                        @score-update="updateScore"
                                                        @start="startMatch"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Losers Bracket -->
                                <div>
                                    <h4 class="text-lg font-semibold mb-4">Losers Bracket</h4>
                                    <div class="space-y-4">
                                        <div v-for="(matches, round) in matchesByRound" :key="round"
                                            v-if="matches[0]?.bracket === 'losers'" class="mb-8">
                                            <h5 class="text-md font-medium mb-2">Round {{ round }}</h5>
                                            <div class="space-y-2">
                                                <div v-for="match in matches" :key="match.id">
                                                    <MatchTemplate 
                                                        :match="match"
                                                        :athletes="athletes"
                                                        :can-edit-match="canEditMatch(match)"
                                                        @update="updateMatch"
                                                        @walkover="markWalkover"
                                                        @winner="updateMatchResult"
                                                        @score-update="updateScore"
                                                        @start="startMatch"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Round Robin -->
                            <div v-else-if="division?.bracket_type === 'round_robin'" class="space-y-8">
                                <!-- Standings Preview -->
                                <RoundRobinStandings 
                                    :matches="matches"
                                    :athletes="athletes"
                                />

                                <!-- Match Grid -->
                                <div v-for="(matches, round) in matchesByRound" :key="round" class="mb-8">
                                    <h4 class="text-lg font-semibold mb-4">Round {{ round }}</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        <div v-for="match in matches" :key="match.id">
                                            <MatchTemplate 
                                                :match="match"
                                                :athletes="athletes"
                                                :can-edit-match="canEditMatch(match)"
                                                @update="updateMatch"
                                                @walkover="markWalkover"
                                                @winner="updateMatchResult"
                                                @score-update="updateScore"
                                                @start="startMatch"
                                            />
                                        </div>
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
import { defineComponent, computed, onMounted, ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import MatchTemplate from '@/Components/Event/Brackets/MatchTemplate.vue'
import RoundRobinStandings from '@/Components/Event/Brackets/RoundRobinStandings.vue'

export default defineComponent({
    components: {
        AdminLayout,
        MatchTemplate,
        RoundRobinStandings,
    },
    props: {
        event: {
            type: Object,
            required: true,
        },
        division: {
            type: Object,
            required: true,
        },
        athletes: {
            type: Array,
            required: true,
        },
        matches: {
            type: Array,
            required: true,
        },
    },
    setup(props) {
        // Debug logging
        console.log('All Props:', props);
        console.log('Division Object:', props.division);
        console.log('Bracket Type:', props.division?.bracket_type);
        console.log('Matches:', props.matches);
        console.log('Athletes:', props.athletes);

        onMounted(() => {
            console.log('Component Mounted - Division:', props.division);
            console.log('Component Mounted - Bracket Type:', props.division?.bracket_type);
        });

        const matchesByRound = computed(() => {
            return props.matches.reduce((acc, match) => {
                if (!acc[match.round]) {
                    acc[match.round] = []
                }
                acc[match.round].push(match)
                return acc
            }, {})
        })

        const formatDateTime = (datetime) => {
            return new Date(datetime).toLocaleString()
        }

        const formatTime = (datetime) => {
            return new Date(datetime).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
        }

        const generateMatches = () => {
            router.post(`/admin/events/${props.event.id}/brackets/${props.division.id}/generate`)
        }

        const confirmRegenerate = () => {
            if (confirm('Are you sure you want to regenerate the bracket? This will reset all matches and results.')) {
                generateMatches()
            }
        }

        const updateMatchResult = async (matchId, winnerId) => {
            try {
                await router.patch(`/admin/events/${props.event.id}/brackets/${props.division.id}/matches/${matchId}`, {
                    winner_id: winnerId
                })
            } catch (error) {
                console.error('Error updating match result:', error)
            }
        }

        const getMatchStatus = (match) => {
            if (match.winner_id) return 'completed'
            if (match.status === 'walkover') return 'walkover'
            if (!match.player1_id || !match.player2_id) return 'pending'
            return match.status
        }

        const markWalkover = async (matchId, walkoverWinnerId) => {
            try {
                await router.patch(`/admin/events/${props.event.id}/brackets/${props.division.id}/matches/${matchId}/walkover`, {
                    winner_id: walkoverWinnerId
                })
            } catch (error) {
                console.error('Error marking walkover:', error)
            }
        }

        const getAthleteById = (athleteId) => {
            return props.athletes.find(a => a.id === athleteId) || null
        }

        const getMatchById = (matchId) => {
            return props.matches.find(m => m.id === matchId) || null
        }

        const canEditMatch = (match) => {
            // Check if match can be edited based on status and dependencies
            if (match.status === 'completed') return false
            if (match.status === 'walkover') return false
            
            // Check if this match's result is used in subsequent matches
            const dependentMatches = props.matches.filter(m => 
                m.parent_match1_id === match.id || m.parent_match2_id === match.id
            )
            
            return dependentMatches.every(m => !m.winner_id && m.status !== 'walkover')
        }

        const updateMatch = async (match) => {
            try {
                await router.patch(`/admin/events/${props.event.id}/brackets/${props.division.id}/matches/${match.id}`, {
                    player1_id: match.player1_id,
                    player2_id: match.player2_id
                })
            } catch (error) {
                console.error('Error updating match:', error)
            }
        }

        const updateScore = async (match) => {
            try {
                await router.patch(`/admin/events/${props.event.id}/brackets/${props.division.id}/matches/${match.id}/score`, {
                    athlete1_score: match.athlete1_score,
                    athlete2_score: match.athlete2_score
                })
            } catch (error) {
                console.error('Error updating score:', error)
            }
        }

        const startMatch = async (match) => {
            try {
                await router.patch(`/admin/events/${props.event.id}/brackets/${props.division.id}/matches/${match.id}/start`)
            } catch (error) {
                console.error('Error starting match:', error)
            }
        }

        return {
            matchesByRound,
            formatDateTime,
            formatTime,
            generateMatches,
            confirmRegenerate,
            updateMatchResult,
            getMatchStatus,
            markWalkover,
            getAthleteById,
            getMatchById,
            canEditMatch,
            updateMatch,
            updateScore,
            startMatch
        }
    },
})
</script> 