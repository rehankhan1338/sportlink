<template>
    <div v-if="show" 
         class="fixed inset-y-0 right-0 w-96 bg-gray-900 shadow-lg transform transition-transform duration-300"
         :class="{ 'translate-x-0': show, 'translate-x-full': !show }">
        <div class="p-6 overflow-y-scroll h-full">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-white">Bracket Details{{ divisionInfo ? ` - ${divisionInfo.name}` : '' }}</h3>
                <button @click="$emit('close')" class="text-gray-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Loading State -->
            <div v-if="loading" class="flex justify-center items-center h-64">
                <div class="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-blue-500"></div>
            </div>

            <!-- Content -->
            <div v-else class="space-y-6">
                <!-- Division Info -->
                <div v-if="divisionInfo" class="bg-gray-800 rounded-lg p-4">
                    <h4 class="text-white font-semibold mb-3">Division Information</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Type:</span>
                            <span class="capitalize">{{ divisionInfo.bracket_type?.replace('_', ' ') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Start Time:</span>
                            <span>{{ formatTime(divisionInfo.start_time) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Mat:</span>
                            <span>{{ divisionInfo.mat_number }}</span>
                        </div>
                    </div>
                </div>

                <!-- Matches Section -->
                <div class="space-y-6">
                    <!-- Semifinals -->
                    <div v-if="shouldShowSemifinals" class="bg-gray-800 rounded-lg p-4">
                        <h4 class="text-white font-semibold mb-3">Semifinals</h4>
                        <div class="space-y-4">
                            <div v-for="match in semifinalMatches" :key="match.id" class="border border-gray-700 rounded p-3">
                                <div class="flex justify-between text-sm text-gray-400 mb-2">
                                    <span>{{ match.mat_name }}</span>
                                    <span>{{ formatTime(match.scheduled_time) }}</span>
                                </div>
                                <!-- Athlete 1 -->
                                <div class="flex items-center mb-2" :class="{ 'opacity-50': match.status === 'walkover' && match.winner_id !== match.player1_id }">
                                    <img :src="match.player1?.image || '/images/default-avatar.png'" 
                                         class="w-10 h-10 rounded-full mr-3 object-cover" 
                                         :alt="match.player1?.name || 'TBD'">
                                    <div class="flex-1">
                                        <div class="text-white">{{ match.player1?.name || 'TBD' }}</div>
                                        <div class="text-sm text-gray-400">{{ match.player1?.academy || '' }}</div>
                                    </div>
                                    <div v-if="match.winner_id === match.player1_id" class="text-green-500 text-sm font-medium">
                                        Winner
                                    </div>
                                </div>
                                <!-- Athlete 2 -->
                                <div class="flex items-center" :class="{ 'opacity-50': match.status === 'walkover' && match.winner_id !== match.player2_id }">
                                    <img :src="match.player2?.image || '/images/default-avatar.png'" 
                                         class="w-10 h-10 rounded-full mr-3 object-cover" 
                                         :alt="match.player2?.name || 'TBD'">
                                    <div class="flex-1">
                                        <div class="text-white">{{ match.player2?.name || 'TBD' }}</div>
                                        <div class="text-sm text-gray-400">{{ match.player2?.academy || '' }}</div>
                                    </div>
                                    <div v-if="match.winner_id === match.player2_id" class="text-green-500 text-sm font-medium">
                                        Winner
                                    </div>
                                </div>
                                <!-- Match Status and Score -->
                                <div v-if="match.status !== 'pending'" class="mt-3 pt-3 border-t border-gray-700">
                                    <div class="text-sm text-gray-400">
                                        <span class="capitalize">{{ match.status }}</span>
                                        <span v-if="match.score_details" class="ml-2">
                                            Score: {{ match.score_details.athlete1_score }} - {{ match.score_details.athlete2_score }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bronze Match -->
                    <div v-if="shouldShowBronzeMatch" class="bg-gray-800 rounded-lg p-4">
                        <h4 class="text-white font-semibold mb-3">Bronze Match</h4>
                        <div class="border border-gray-700 rounded p-3">
                            <div class="flex justify-between text-sm text-gray-400 mb-2">
                                <span>{{ bronzeMatchData.mat_name }}</span>
                                <span>{{ formatTime(bronzeMatchData.scheduled_time) }}</span>
                            </div>
                            <!-- Athlete 1 -->
                            <div class="flex items-center mb-2" :class="{ 'opacity-50': bronzeMatchData.status === 'walkover' && bronzeMatchData.winner_id !== bronzeMatchData.player1_id }">
                                <img :src="bronzeMatchData.player1?.image || '/images/default-avatar.png'" 
                                     class="w-10 h-10 rounded-full mr-3 object-cover" 
                                     :alt="bronzeMatchData.player1?.name || 'TBD'">
                                <div class="flex-1">
                                    <div class="text-white">{{ bronzeMatchData.player1?.name || 'TBD' }}</div>
                                    <div class="text-sm text-gray-400">{{ bronzeMatchData.player1?.academy || '' }}</div>
                                </div>
                                <div v-if="bronzeMatchData.winner_id === bronzeMatchData.player1_id" class="text-green-500 text-sm font-medium">
                                    Winner
                                </div>
                            </div>
                            <!-- Athlete 2 -->
                            <div class="flex items-center" :class="{ 'opacity-50': bronzeMatchData.status === 'walkover' && bronzeMatchData.winner_id !== bronzeMatchData.player2_id }">
                                <img :src="bronzeMatchData.player2?.image || '/images/default-avatar.png'" 
                                     class="w-10 h-10 rounded-full mr-3 object-cover" 
                                     :alt="bronzeMatchData.player2?.name || 'TBD'">
                                <div class="flex-1">
                                    <div class="text-white">{{ bronzeMatchData.player2?.name || 'TBD' }}</div>
                                    <div class="text-sm text-gray-400">{{ bronzeMatchData.player2?.academy || '' }}</div>
                                </div>
                                <div v-if="bronzeMatchData.winner_id === bronzeMatchData.player2_id" class="text-green-500 text-sm font-medium">
                                    Winner
                                </div>
                            </div>
                            <!-- Match Status and Score -->
                            <div v-if="bronzeMatchData.status !== 'pending'" class="mt-3 pt-3 border-t border-gray-700">
                                <div class="text-sm text-gray-400">
                                    <span class="capitalize">{{ bronzeMatchData.status }}</span>
                                    <span v-if="bronzeMatchData.score_details" class="ml-2">
                                        Score: {{ bronzeMatchData.score_details.athlete1_score }} - {{ bronzeMatchData.score_details.athlete2_score }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Final Match -->
                    <div v-if="shouldShowFinal" class="bg-gray-800 rounded-lg p-4">
                        <h4 class="text-white font-semibold mb-3">Final Match</h4>
                        <div class="border border-gray-700 rounded p-3">
                            <div class="flex justify-between text-sm text-gray-400 mb-2">
                                <span>{{ finalMatchData.mat_name }}</span>
                                <span>{{ formatTime(finalMatchData.scheduled_time) }}</span>
                            </div>
                            <!-- Athlete 1 -->
                            <div class="flex items-center mb-2" :class="{ 'opacity-50': finalMatchData.status === 'walkover' && finalMatchData.winner_id !== finalMatchData.player1_id }">
                                <img :src="finalMatchData.player1?.image || '/images/default-avatar.png'" 
                                     class="w-10 h-10 rounded-full mr-3 object-cover" 
                                     :alt="finalMatchData.player1?.name || 'TBD'">
                                <div class="flex-1">
                                    <div class="text-white">{{ finalMatchData.player1?.name || 'TBD' }}</div>
                                    <div class="text-sm text-gray-400">{{ finalMatchData.player1?.academy || '' }}</div>
                                </div>
                                <div v-if="finalMatchData.winner_id === finalMatchData.player1_id" class="text-green-500 text-sm font-medium">
                                    Winner
                                </div>
                            </div>
                            <!-- Athlete 2 -->
                            <div class="flex items-center" :class="{ 'opacity-50': finalMatchData.status === 'walkover' && finalMatchData.winner_id !== finalMatchData.player2_id }">
                                <img :src="finalMatchData.player2?.image || '/images/default-avatar.png'" 
                                     class="w-10 h-10 rounded-full mr-3 object-cover" 
                                     :alt="finalMatchData.player2?.name || 'TBD'">
                                <div class="flex-1">
                                    <div class="text-white">{{ finalMatchData.player2?.name || 'TBD' }}</div>
                                    <div class="text-sm text-gray-400">{{ finalMatchData.player2?.academy || '' }}</div>
                                </div>
                                <div v-if="finalMatchData.winner_id === finalMatchData.player2_id" class="text-green-500 text-sm font-medium">
                                    Winner
                                </div>
                            </div>
                            <!-- Match Status and Score -->
                            <div v-if="finalMatchData.status !== 'pending'" class="mt-3 pt-3 border-t border-gray-700">
                                <div class="text-sm text-gray-400">
                                    <span class="capitalize">{{ finalMatchData.status }}</span>
                                    <span v-if="finalMatchData.score_details" class="ml-2">
                                        Score: {{ finalMatchData.score_details.athlete1_score }} - {{ finalMatchData.score_details.athlete2_score }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- No Matches -->
                    <div v-if="!shouldShowSemifinals && !shouldShowBronzeMatch && !shouldShowFinal" 
                         class="text-center py-4 text-gray-400">
                        No matches scheduled yet
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, computed, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({
    show: {
        type: Boolean,
        required: true
    },
    eventId: {
        type: Number,
        required: true
    },
    divisionId: {
        type: Number,
        required: true
    },
    bracketType: {
        type: String,
        required: true
    }
});

const emit = defineEmits(['close']);

const loading = ref(true);
const matches = ref([]);
const divisionInfo = ref(null);
const registeredAthletes = ref([]);

// Computed properties for filtered matches
const semifinalMatches = computed(() => {
    return matches.value.filter(m => m.round_type === 'semifinal' || 
        (m.division_type === 'single_elimination' && m.round_number === 1));
});

const bronzeMatchData = computed(() => {
    return matches.value.find(m => m.round_type === 'bronze' || 
        (m.division_type === 'single_elimination' && m.round_number === 2 && m.bracket_type === 'bronze'));
});

const finalMatchData = computed(() => {
    return matches.value.find(m => m.round_type === 'final' || 
        (m.division_type === 'single_elimination' && m.round_number === 3) ||
        (m.division_type === 'double_elimination' && m.bracket_type === 'final'));
});



// Computed properties for conditional rendering
const shouldShowSemifinals = computed(() => {
    return semifinalMatches.value.length > 0;
});

const shouldShowBronzeMatch = computed(() => {
    return !!bronzeMatchData.value;
});

const shouldShowFinal = computed(() => {
    return !!finalMatchData.value;
});

// Fetch division details and matches
const fetchDivisionDetails = async () => {
    try {
        loading.value = true;
        const response = await axios.get(route('events.divisions.brackets', {
            event: props.eventId,
            division: props.divisionId
        }));
        
        // Log player1 data from first match
        if (response.data.matches && response.data.matches.length > 0) {
            console.log('Player 1 data:', response.data.matches[0].player1);
            console.log('Full match data:', response.data.matches[0]);
        }

        matches.value = response.data.matches;
        divisionInfo.value = response.data.division;
        registeredAthletes.value = response.data.athletes;
    } catch (error) {
        console.error('Error fetching division details:', error);
    } finally {
        loading.value = false;
    }
};

// Format time helper
const formatTime = (time) => {
    if (!time) return 'Not scheduled';
    return new Date(time).toLocaleTimeString();
};

// Watch for show prop changes to fetch data
watch(() => props.show, (newValue) => {
    if (newValue) {
        fetchDivisionDetails();
    }
});

// Initial fetch if component is shown
onMounted(() => {
    if (props.show) {
        fetchDivisionDetails();
    }
});
</script> 