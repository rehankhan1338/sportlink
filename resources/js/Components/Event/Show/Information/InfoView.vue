<template>
    <section class="pt-[27px] pb-[30px]">
        <div class="container mx-auto">
            <!-- Tab Navigation -->
            <div class="mb-6">
                <nav class="flex space-x-4 border-b border-gray-700">
                    <button 
                        v-for="tab in tabs" 
                        :key="tab.id"
                        @click="activeTab = tab.id"
                        :class="[
                            'px-4 py-2 text-sm font-medium rounded-t-lg flex items-center',
                            activeTab === tab.id 
                                ? 'bg-gray-800 text-white border-b-2 border-blue-500' 
                                : 'text-gray-400 hover:text-white hover:bg-gray-700'
                        ]"
                    >
                        {{ tab.name }}
                        <span v-if="tab.id === 'athletes'" class="ml-2 bg-blue-500 text-white px-2 py-0.5 rounded-full text-xs">
                            {{ athletesCount || 0 }}
                        </span>
                    </button>
                </nav>
            </div>

            <!-- Information View -->
            <div v-if="activeTab === 'info'">
                <section class="pt-0 sm:pt-0 pb-8 hidden-print hidden-tv">
                    <div id="event-hero" class="card mx-auto bg-gray-800 text-white rounded-lg p-6">
                        <div class="card-body flex flex-col sm:flex-row items-center">
                            <!-- Image section -->
                            <div class="cover-image w-full sm:w-4/5 mb-4 sm:mb-0">
                                <img class="w-full h-auto rounded-lg" alt="Event cover image"
                                    :src="event.image ? `/storage/${event.image}` : Placeholderimage"
                                    loading="lazy">
                            </div>

                            <!-- Schedule section -->
                            <div class="schedule w-full sm:w-1/3 sm:pl-6">
                                <div class="schedule-item py-2">
                                    <span class="title">Event starting data</span>
                                    <div class="info">
                                        <strong>{{ new Date(event.start_date).toLocaleDateString() }}</strong>
                                    </div>
                                </div>
                                <div class="schedule-item py-2">
                                    <span class="title">Event ending data</span>
                                    <div class="info">
                                        <strong>{{ new Date(event.end_date).toLocaleDateString() }}</strong>
                                    </div>
                                </div>
                                <div class="schedule-item py-2">
                                    <span class="title">Event dates: </span>
                                    <strong class="info">{{ new Date(event.start_date).toLocaleDateString() }} - {{ new Date(event.end_date).toLocaleDateString() }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Text -->
                <div class="flex">
                    <!-- Left Column -->
                    <div class="w-full lg:w-8/12">
                        <div class="flex flex-col gap-4">
                            <div class="mb-4 pl-4 sm:pl-0">
                                <h2 class="text-lg sm:text-xl text-white font-bold">{{ event.title }}</h2>
                            </div>
                            <div class="event-description" v-html="event.description"></div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="w-full lg:w-4/12">
                        <!-- Organizer Section -->
                        <div class="bg-gray-800 text-white rounded-lg mb-6 p-4">
                            <h3 class="text-base sm:text-lg font-semibold mb-2">Organizer & Merchant</h3>
                            <div class="mb-4">
                                <span class="text-gray-500 cursor-pointer">ℹ️ </span>
                                <a href="#" class="text-gray-300 hover:text-white">{{ organization?.name || 'No organization' }}</a>
                            </div>
                        </div>

                        <!-- Contact Section -->
                        <div class="bg-gray-800 text-white rounded-lg mb-6 p-4">
                            <h3 class="text-base sm:text-lg font-semibold mb-2">Contact</h3>
                            <ul class="divide-y divide-gray-700">
                                <li class="py-2">
                                    <a :href="eventCreator?.phone ? `tel:${eventCreator.phone}` : '#'" target="_blank" rel="noopener"
                                        class="flex items-center gap-2 text-gray-300 hover:text-white">
                                        <span class="icon icon-call-phone-square"></span>
                                        <span>Phone: {{ event.creator?.phone || 'Not available' }}</span>
                                        <svg class="w-2 h-3 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 8 12">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M2.09043 11.5898L0.911921 10.4113L5.32266 6.00059L0.911921 1.58984L2.09043 0.411333L7.67969 6.00059L2.09043 11.5898Z"
                                                fill="#F4F6FB" fill-opacity="0.7"></path>
                                        </svg>
                                    </a>
                                </li>
                                <li class="py-2">
                                    <a :href="eventCreator?.email ? `mailto:${eventCreator.email}` : '#'" class="flex items-center gap-2 text-gray-300 hover:text-white">
                                        <span class="icon icon-envelope"></span>
                                        <span>Email: {{ event.creator?.email || 'Not available' }}</span>
                                        <svg class="w-2 h-3 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 8 12">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M2.09043 11.5898L0.911921 10.4113L5.32266 6.00059L0.911921 1.58984L2.09043 0.411333L7.67969 6.00059L2.09043 11.5898Z"
                                                fill="#F4F6FB" fill-opacity="0.7"></path>
                                        </svg>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Location Section -->
                        <div class="bg-gray-800 text-white rounded-lg mb-6 p-4">
                            <h3 class="text-base sm:text-lg font-semibold mb-2">Location</h3>
                            <a href="https://maps.google.com/?q=43.2585188,76.8150714" target="_blank" rel="noopener"
                                class="flex justify-between items-center text-gray-300 hover:text-white">
                                <div>
                                    <div class="mb-1">
                                        <span>{{ event.location }}</span>, <span>{{ event.country }}</span>
                                    </div>
                                    <small class="text-gray-400">Timezone: {{ event.timezone }}</small>
                                </div>
                                <svg class="w-2 h-3 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 8 12">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M2.09043 11.5898L0.911921 10.4113L5.32266 6.00059L0.911921 1.58984L2.09043 0.411333L7.67969 6.00059L2.09043 11.5898Z"
                                        fill="#F4F6FB" fill-opacity="0.7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Athletes View -->
            <div v-else-if="activeTab === 'athletes'" class="bg-gray-800 text-white rounded-lg p-6">
                <h2 class="text-xl font-bold mb-4">Athletes</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead>
                            <tr class="bg-gray-700 text-gray-300">
                                <th class="px-4 py-2">Athlete</th>
                                <th class="px-4 py-2">Birth</th>
                                <th class="px-4 py-2">Academy & Affiliation</th>
                                <th class="px-4 py-2">Registration</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="athlete in athletes" :key="athlete.id" class="border-b border-gray-600">
                                <td class="flex items-center gap-3 px-4 py-2">
                                    <img :src="athlete.image || Placeholderimage" class="w-12 h-12 rounded" />
                                    <div>
                                        <div class="font-semibold text-white">{{ athlete.name }}</div>
                                        <div class="flex items-center gap-1 text-xs">
                                            <span class="text-gray-400">{{ athlete.nationality }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-2">
                                    <div class="font-semibold text-white">{{ athlete.birth_year }}</div>
                                    <div class="text-xs text-gray-400">{{ athlete.age }} years</div>
                                </td>
                                <td class="px-4 py-2">
                                    <a :href="athlete.academy_url" class="text-blue-400 hover:underline">{{ athlete.academy }}</a>
                                </td>
                                <td class="px-4 py-2">
                                    <div>{{ athlete.gender }}</div>
                                    <div>Weight: {{ athlete.weight }} kg</div>
                                    <div>Country: {{ athlete.country_of_residence }}</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div v-if="athletes.length === 0" class="text-gray-400 p-4">No athletes found.</div>
                </div>
            </div>

            <!-- Brackets View -->
            <div v-else-if="activeTab === 'brackets'" class="bg-gray-800 text-white rounded-lg p-6">
                <h2 class="text-xl font-bold mb-4">Brackets</h2>
                <div v-if="isRegistrationClosed" class="space-y-2">
                    <table class="min-w-full text-left text-sm">
                        <thead>
                            <tr class="bg-gray-700 text-gray-300">
                                <th class="px-4 py-2">Group</th>
                                <th class="px-4 py-2">ETA Start</th>
                                <th class="px-4 py-2">Mat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="division in filteredDivisions" 
                                :key="division.id" 
                                class="border-b border-gray-600 hover:bg-gray-700 cursor-pointer"
                                @click="viewBracketDetails(division.id)">
                                <td class="px-4 py-2">
                                    {{ division.name }} / Age: {{ division.min_age }} - {{ division.max_age }} / 
                                    {{ division.belt_level }} / Weight: {{ division.min_weight }} - {{ division.max_weight }}
                                    <br/>
                                    <span>{{ division.participants_count }} participants</span>
                                </td>
                                <td class="px-4 py-2">{{ formatTime(division.start_time) }}</td>
                                <td class="px-4 py-2">{{ division.mat_number }}</td>
                            </tr>
                            <tr v-if="!filteredDivisions.length" class="text-center">
                                <td colspan="3" class="px-4 py-4">No divisions with 2 or more participants available</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else class="text-gray-400 text-center py-8">
                    Brackets are not published yet
                </div>

                <!-- Bracket Details Sidebar -->
                <BracketDetails
                    :show="showBracketSidebar"
                    :event-id="props.event.id"
                    :division-id="selectedDivisionId"
                    :bracket-type="selectedDivision?.bracket_type || 'single_elimination'"
                    @close="closeBracketSidebar"
                />

                <!-- Full Bracket View -->
                <div v-if="showFullBracket" 
                     class="fixed inset-0 bg-gray-900 z-50 overflow-auto">
                    <div class="sticky top-0 bg-gray-800 p-4 flex justify-between items-center">
                        <h2 class="text-white text-xl font-bold">{{ selectedBracket?.name }}</h2>
                        <button @click="closeFullBracketView" 
                                class="text-gray-400 hover:text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <BracketView :bracket="selectedBracket" />
                </div>
            </div>

            <!-- Matches View -->
            <div v-else-if="activeTab === 'matches'" class="bg-gray-800 text-white rounded-lg p-6">
                <h2 class="text-xl font-bold mb-4">Matches</h2>
                <div v-if="isRegistrationClosed" class="space-y-4">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-sm">
                            <thead>
                                <tr class="bg-gray-700 text-gray-300">
                                    <th class="px-4 py-2">Athletes</th>
                                    <th class="px-4 py-2">Mat</th>
                                    <th class="px-4 py-2">Time</th>
                                    <th class="px-4 py-2">Division</th>
                                    <th class="px-4 py-2">Round</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="match in matches" :key="match.id" class="border-b border-gray-600">
                                    <td class="px-4 py-2">
                                        <!-- Player 1 -->
                                        <div class="flex items-center mb-2">
                                            <img :src="match.player1?.image" class="w-8 h-8 rounded-full mr-2" :alt="match.player1?.name">
                                            <span :class="{ 'text-green-500 font-bold': match.winner_id === match.player1?.id }">
                                                {{ match.player1?.name || 'TBD' }}
                                                <span v-if="match.winner_id === match.player1?.id" class="ml-1">(Winner)</span>
                                            </span>
                                        </div>
                                        <!-- VS -->
                                        <div class="text-gray-500 text-xs my-1">vs</div>
                                        <!-- Player 2 -->
                                        <div class="flex items-center">
                                            <img :src="match.player2?.image" class="w-8 h-8 rounded-full mr-2" :alt="match.player2?.name">
                                            <span :class="{ 'text-green-500 font-bold': match.winner_id === match.player2?.id }">
                                                {{ match.player2?.name || 'TBD' }}
                                                <span v-if="match.winner_id === match.player2?.id" class="ml-1">(Winner)</span>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2">{{ match.mat_name }}</td>
                                    <td class="px-4 py-2">{{ formatTime(match.scheduled_time) }}</td>
                                    <td class="px-4 py-2">{{ match.division }}</td>
                                    <td class="px-4 py-2 capitalize">{{ match.round_type?.replace('_', ' ') }}</td>
                                    
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div v-else class="text-gray-400 text-center py-8">
                    Matches are not published yet
                </div>
            </div>

            <!-- Schedule View -->
            <!-- <div v-else-if="activeTab === 'schedule'" class="bg-gray-800 text-white rounded-lg p-6">
                <h2 class="text-xl font-bold mb-4">Schedule</h2>
                <div v-if="isRegistrationClosed" class="bg-gray-700 p-4 rounded-lg">
                    <p class="text-gray-400">Detailed event schedule will be displayed here</p>
                </div>
                <div v-else class="text-gray-400 text-center py-8">
                    Schedule is not published yet
                </div>
            </div> -->

            <!-- Results View -->
            <div v-else-if="activeTab === 'results'" class="bg-gray-800 text-white rounded-lg p-6">
                <h2 class="text-xl font-bold mb-4">Results</h2>
                <div v-if="isRegistrationClosed" class="bg-gray-700 p-4 rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-sm">
                            <thead>
                                <tr class="bg-gray-700 text-gray-300">
                                    <th class="px-4 py-2">Athletes</th>
                                    <th class="px-4 py-2">Mat</th>
                                    <th class="px-4 py-2">Time</th>
                                    <th class="px-4 py-2">Division</th>
                                    <th class="px-4 py-2">Round</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="match in matches.filter(m => m.status === 'completed')" 
                                    :key="match.id" 
                                    class="border-b border-gray-600">
                                    <td class="px-4 py-2">
                                        <!-- Player 1 -->
                                        <div class="flex items-center mb-2">
                                            <img :src="match.player1?.image" class="w-8 h-8 rounded-full mr-2" :alt="match.player1?.name">
                                            <span :class="{ 'text-green-500 font-bold': match.winner_id === match.player1?.id }">
                                                {{ match.player1?.name || 'TBD' }}
                                                <span v-if="match.winner_id === match.player1?.id" class="ml-1">(Winner)</span>
                                            </span>
                                        </div>
                                        <!-- VS -->
                                        <div class="text-gray-500 text-xs my-1">vs</div>
                                        <!-- Player 2 -->
                                        <div class="flex items-center">
                                            <img :src="match.player2?.image" class="w-8 h-8 rounded-full mr-2" :alt="match.player2?.name">
                                            <span :class="{ 'text-green-500 font-bold': match.winner_id === match.player2?.id }">
                                                {{ match.player2?.name || 'TBD' }}
                                                <span v-if="match.winner_id === match.player2?.id" class="ml-1">(Winner)</span>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2">{{ match.mat_name }}</td>
                                    <td class="px-4 py-2">{{ formatTime(match.scheduled_time) }}</td>
                                    <td class="px-4 py-2">{{ match.division }}</td>
                                    <td class="px-4 py-2 capitalize">{{ match.round_type?.replace('_', ' ') }}</td>
                                    
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div v-else class="text-gray-400 text-center py-8">
                    Results are not published yet
                </div>
            </div>

            <!-- Location View -->
            <div v-else-if="activeTab === 'location'" class="w-full h-[600px] bg-gray-800 rounded-lg overflow-hidden">
                <iframe
                    width="100%"
                    height="100%"
                    frameborder="0"
                    style="border:0"
                    :src="`https://www.google.com/maps/embed/v1/place?key=AIzaSyDLMAeXDeS3U_fDPme-UAD_k5RycKzpJBs&q=${encodeURIComponent(event.location + ', ' + event.country)}`"
                    allowfullscreen>
                </iframe>
            </div>
        </div>
    </section>
</template>

<script setup>
import Placeholderimage from '@/assets/placeholder-image.jpg';
import { ref, onMounted, watch, computed } from 'vue';
import axios from 'axios';
import BracketDetails from './BracketDetails.vue';
import BracketView from '@/Components/Event/Brackets/BracketView.vue';

const tabs = [
    { id: 'info', name: 'Information' },
    { id: 'location', name: 'Location' },
    { id: 'athletes', name: 'Athletes' },
    { id: 'brackets', name: 'Brackets' },
    { id: 'matches', name: 'Matches' },
    // { id: 'schedule', name: 'Schedule' },
    { id: 'results', name: 'Results' }
];

const activeTab = ref('info');
const athletesCount = ref(0);
const athletes = ref([]);
const matches = ref([]);

const props = defineProps({
    event: {
        type: Object,
        required: true
    },
    organization: {
        type: Object,
        default: null
    },
    eventCreator: {
        type: Object,
        default: null
    }
});

// Add computed property for registration status
const isRegistrationClosed = computed(() => {
    if (!props.event.last_date_of_registration) return false;
    const lastRegistrationDate = new Date(props.event.last_date_of_registration);
    const now = new Date();
    return lastRegistrationDate < now;
});

const fetchAthletesCount = async () => {
    try {
        console.log('Fetching athletes count for event:', props.event.id);
        const response = await axios.get(route('events.athletes.count', { id: props.event.id }));
        console.log('Athletes count response:', response.data);
        if (response.data && typeof response.data.count !== 'undefined') {
            athletesCount.value = parseInt(response.data.count);
            console.log('Updated athletes count:', athletesCount.value);
        } else {
            console.warn('Invalid response format:', response.data);
            athletesCount.value = 0;
        }
    } catch (error) {
        console.error('Error fetching athletes count:', {
            error: error.message,
            response: error.response?.data,
            status: error.response?.status
        });
        athletesCount.value = 0;
    }
};

const fetchAthletes = async () => {
    try {
        const response = await axios.get(route('events.athletes.list', { id: props.event.id }));
        athletes.value = response.data.athletes || [];
    } catch (error) {
        console.error('Error fetching athletes:', error);
        athletes.value = [];
    }
};

// New refs for bracket functionality
const showBracketSidebar = ref(false);
const selectedDivisionId = ref(null);

const viewBracketDetails = (divisionId) => {
    console.log('viewBracketDetails called with divisionId:', divisionId);
    console.log('Current divisionsList:', divisionsList.value);
    selectedDivisionId.value = divisionId;
    console.log('selectedDivision computed:', selectedDivision.value);
    showBracketSidebar.value = true;
};

const closeBracketSidebar = () => {
    showBracketSidebar.value = false;
    selectedDivisionId.value = null;
};

// Add this new function in the script section
const formatWeight = (minWeight, maxWeight) => {
    if (minWeight === maxWeight) {
        return `${Math.round(minWeight)}кг`;
    }
    if (maxWeight === 0 || maxWeight === null) {
        return `+${Math.round(minWeight)}кг`;
    }
    if (minWeight === 0 || minWeight === null) {
        return `-${Math.round(maxWeight)}кг`;
    }
    return `${Math.round(minWeight)}кг`;
};

const getAgeCategory = (minAge, maxAge) => {
    if (minAge <= 4 && maxAge <= 6) return 'Kid 1';
    if (minAge <= 7 && maxAge <= 9) return 'Kid 2';
    if (minAge <= 10 && maxAge <= 12) return 'Kid 3';
    if (minAge <= 13 && maxAge <= 15) return 'Infant';
    if (minAge <= 16 && maxAge <= 17) return 'Teen';
    if (minAge <= 18 && maxAge <= 20) return 'Junior';
    return 'Adult';
};

// Add formatTime function
const formatTime = (time) => {
    if (!time) return 'Not scheduled';
    return new Date(time).toLocaleTimeString();
};

// Add selectedDivision computed property in the script section
const selectedDivision = computed(() => {
    if (!selectedDivisionId.value) return null;
    return divisionsList.value.find(d => d.id === selectedDivisionId.value);
});

// Update divisionsList computed property to include bracket_type
const divisionsList = computed(() => {
    if (!props.event?.divisions) return [];
    
    // Get unique divisions from checkout_details
    const uniqueDivisions = new Set();
    const divisions = props.event.divisions.filter(division => {
        const key = `${division.id}`;
        if (!uniqueDivisions.has(key)) {
            uniqueDivisions.add(key);
            return true;
        }
        return false;
    });

    return divisions.map(division => ({
        id: division.id,
        name: division.name,
        min_age: division.min_age,
        max_age: division.max_age,
        belt_level: division.belt_level,
        min_weight: division.min_weight,
        max_weight: division.max_weight,
        start_time: division.start_time,
        mat_number: division.mat_number,
        bracket_type: division.bracket_type,
        participants_count: 0 // Initialize count
    }));
});

// Add this new function to fetch participants count
const fetchParticipantsCount = async () => {
    try {
        for (const division of divisionsList.value) {
            const response = await axios.get(route('division.participants.count.alt', {
                event: props.event.id,
                division: division.id
            }));
            division.participants_count = response.data.count;
        }
    } catch (error) {
        console.error('Error fetching participants count:', error);
    }
};

const showFullBracket = ref(false);
const selectedBracket = ref(null);

const openFullBracketView = (bracket) => {
    showFullBracket.value = true;
};

const closeFullBracketView = () => {
    showFullBracket.value = false;
};

// Add filteredDivisions computed property
const filteredDivisions = computed(() => {
    return divisionsList.value.filter(division => division.participants_count >= 2);
});

onMounted(async () => {
    if (props.event?.id) {
        console.log('Component mounted, event ID:', props.event.id);
        fetchAthletesCount();
        fetchParticipantsCount();
    } else {
        console.warn('No event ID available');
    }
});

watch(activeTab, (newTab) => {
    if (newTab === 'athletes' && props.event?.id) {
        fetchAthletes();
    }
});

// Fetch matches when the matches tab is active
watch(() => activeTab.value, async (newTab) => {
    if ((newTab === 'matches' || newTab === 'results') && isRegistrationClosed.value) {
        try {
            const response = await axios.get(route('events.matches', props.event.id));
            console.log('Matches response:', response.data);
            matches.value = response.data.matches;
            console.log('Matches after assignment:', matches.value);
            // Log each match's winner information
            matches.value.forEach(match => {
                console.log('Match winner info:', {
                    matchId: match.id,
                    winnerId: match.winner_id,
                    whoWon: match.who_won
                });
            });
        } catch (error) {
            console.error('Error fetching matches:', error);
        }
    }
});

console.log('Event Creator Data:', props.eventCreator);
</script>

<style scoped>
.event-description {
    color: white;
}

.event-description :deep(h1) {
    font-size: 2em;
    margin: 0.67em 0;
    color: white;
}

.event-description :deep(h2) {
    font-size: 1.5em;
    margin: 0.83em 0;
    color: white;
}

.event-description :deep(h3) {
    font-size: 1.17em;
    margin: 1em 0;
    color: white;
}

.event-description :deep(p) {
    margin: 1em 0;
    color: white;
}

.event-description :deep(ul),
.event-description :deep(ol) {
    padding-left: 2em;
    margin: 1em 0;
    color: white;
}

.event-description :deep(li) {
    margin: 0.5em 0;
    color: white;
}

.event-description :deep(strong) {
    font-weight: bold;
    color: white;
}

.event-description :deep(em) {
    font-style: italic;
    color: white;
}
</style>
