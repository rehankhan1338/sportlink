<template>
    <div class="bg-white rounded-lg shadow p-4">
        <h3 class="text-lg font-semibold mb-4">Standings</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Rank
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Athlete
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Wins
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Losses
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Points For
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Points Against
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Point Diff
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="(standing, index) in sortedStandings" :key="standing.athlete.id">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ index + 1 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img 
                                        :src="standing.athlete.passport_image_path || '/default-avatar.png'" 
                                        class="h-10 w-10 rounded-full object-cover"
                                        :alt="standing.athlete.name"
                                    >
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ standing.athlete.name }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ standing.wins }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ standing.losses }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ standing.pointsFor }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ standing.pointsAgainst }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ standing.pointsFor - standing.pointsAgainst }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import { defineComponent, computed } from 'vue'

export default defineComponent({
    name: 'RoundRobinStandings',
    
    props: {
        matches: {
            type: Array,
            required: true
        },
        athletes: {
            type: Array,
            required: true
        }
    },

    setup(props) {
        const calculateStandings = () => {
            const standings = props.athletes.map(athlete => ({
                athlete,
                wins: 0,
                losses: 0,
                pointsFor: 0,
                pointsAgainst: 0
            }))

            props.matches.forEach(match => {
                if (match.status !== 'completed' && match.status !== 'walkover') return

                const winner = standings.find(s => s.athlete.id === match.winner_id)
                const loser = standings.find(s => s.athlete.id === match.loser_id)

                if (winner && loser) {
                    winner.wins++
                    loser.losses++

                    // Add points if available
                    if (match.score_details) {
                        const score = JSON.parse(match.score_details)
                        if (match.winner_id === match.player1_id) {
                            winner.pointsFor += score.athlete1_score || 0
                            winner.pointsAgainst += score.athlete2_score || 0
                            loser.pointsFor += score.athlete2_score || 0
                            loser.pointsAgainst += score.athlete1_score || 0
                        } else {
                            winner.pointsFor += score.athlete2_score || 0
                            winner.pointsAgainst += score.athlete1_score || 0
                            loser.pointsFor += score.athlete1_score || 0
                            loser.pointsAgainst += score.athlete2_score || 0
                        }
                    }
                }
            })

            return standings
        }

        const sortedStandings = computed(() => {
            const standings = calculateStandings()
            return standings.sort((a, b) => {
                // Sort by wins first
                if (b.wins !== a.wins) return b.wins - a.wins
                // Then by point differential
                const aPointDiff = a.pointsFor - a.pointsAgainst
                const bPointDiff = b.pointsFor - b.pointsAgainst
                if (bPointDiff !== aPointDiff) return bPointDiff - aPointDiff
                // Then by total points scored
                return b.pointsFor - a.pointsFor
            })
        })

        return {
            sortedStandings
        }
    }
})
</script> 