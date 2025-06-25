<template>
  <div class="fixed right-0 top-0 h-full w-96 bg-gray-800 text-white shadow-lg transform transition-transform duration-300"
       :class="{ 'translate-x-0': show, 'translate-x-full': !show }">
    <!-- Header -->
    <div class="p-4 border-b border-gray-700">
      <div class="flex justify-between items-center">
        <h3 class="text-lg font-semibold">{{ bracket?.name }}</h3>
        <button @click="close" class="text-gray-400 hover:text-white">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Content -->
    <div class="p-4">
      <!-- View Bracket Button -->
      <button @click="viewBracket" 
              class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg mb-6 hover:bg-blue-700 transition-colors">
        View bracket
      </button>

      <!-- Search -->
      <div class="mb-6">
        <input type="text" 
               placeholder="Search athletes" 
               class="w-full bg-gray-700 text-white px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>

      <!-- Display All Matches Toggle -->
      <div class="flex items-center gap-2 mb-6">
        <label class="relative inline-flex items-center cursor-pointer">
          <input type="checkbox" v-model="showAllMatches" class="sr-only peer">
          <div class="w-11 h-6 bg-gray-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
        </label>
        <span class="text-sm text-gray-300">Display all bracket matches</span>
      </div>

      <!-- Athletes -->
      <div class="mb-6">
        <h4 class="text-gray-400 text-sm mb-2">
          Registered Athletes ({{ divisionAthletes.length }})
        </h4>
        <div v-if="isLoading" class="text-center py-4">
          <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-white mx-auto"></div>
        </div>
        <div v-else-if="divisionAthletes.length" class="space-y-2">
          <div v-for="athlete in divisionAthletes" :key="athlete.id" class="bg-gray-700 rounded-lg p-2">
            <div class="flex items-center gap-2">
              <img :src="athlete.avatar" 
                   :alt="`${athlete.first_name} ${athlete.last_name}`"
                   class="w-8 h-8 rounded-full object-cover bg-gray-600"
                   @error="e => e.target.src = '/images/default-avatar.png'">
              <div>
                <div class="text-sm">{{ athlete.first_name }} {{ athlete.last_name }}</div>
                <div class="text-xs text-gray-400">{{ athlete.academy_name }}</div>
                <div class="text-xs text-gray-400">{{ athlete.weight }}kg • {{ athlete.country_of_residence }}</div>
              </div>
            </div>
          </div>
        </div>
        <div v-else class="text-gray-400 text-sm text-center py-4">
          No athletes registered for this division yet
        </div>
      </div>

      <!-- Matches -->
      <div class="space-y-6">
        <!-- Semifinals -->
        <div v-if="bracket?.semifinals?.length">
          <h4 class="text-gray-400 text-sm font-medium mb-3">Semifinals</h4>
          <div class="space-y-4">
            <div v-for="match in bracket.semifinals" :key="match.id" class="bg-gray-700 rounded-lg overflow-hidden">
              <!-- Player 1 -->
              <div class="p-3 border-b border-gray-600">
                <div class="flex flex-col">
                  <div class="text-sm font-medium">{{ match.player1.name }}</div>
                  <div class="text-xs text-gray-400">{{ match.player1.team }}</div>
                </div>
              </div>
              <!-- Player 2 -->
              <div class="p-3 border-b border-gray-600">
                <div class="flex flex-col">
                  <div class="text-sm font-medium">{{ match.player2.name }}</div>
                  <div class="text-xs text-gray-400">{{ match.player2.team }}</div>
                </div>
              </div>
              <!-- Match Info -->
              <div class="px-3 py-2 bg-gray-600/50 flex justify-between items-center">
                <div class="text-sm">MAT{{ match.mat }}</div>
                <div class="text-sm">{{ match.time }}</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Bronze Match -->
        <div v-if="bracket?.bronze_match">
          <h4 class="text-gray-400 text-sm font-medium mb-3">Bronze match</h4>
          <div class="bg-gray-700 rounded-lg overflow-hidden">
            <!-- Player 1 -->
            <div class="p-3 border-b border-gray-600">
              <div class="flex flex-col">
                <div class="text-sm font-medium">{{ bracket.bronze_match.player1.name }}</div>
                <div class="text-xs text-gray-400">{{ bracket.bronze_match.player1.team }}</div>
              </div>
            </div>
            <!-- Player 2 -->
            <div class="p-3 border-b border-gray-600">
              <div class="flex flex-col">
                <div class="text-sm font-medium">{{ bracket.bronze_match.player2.name }}</div>
                <div class="text-xs text-gray-400">{{ bracket.bronze_match.player2.team }}</div>
              </div>
            </div>
            <!-- Match Info -->
            <div class="px-3 py-2 bg-gray-600/50 flex justify-between items-center">
              <div class="text-sm">MAT{{ bracket.bronze_match.mat }}</div>
              <div class="text-sm">{{ bracket.bronze_match.time }}</div>
            </div>
          </div>
        </div>

        <!-- Finals -->
        <div v-if="bracket?.finals?.length">
          <h4 class="text-gray-400 text-sm font-medium mb-3">Final</h4>
          <div class="space-y-4">
            <div v-for="match in bracket.finals" :key="match.id" class="bg-gray-700 rounded-lg overflow-hidden">
              <!-- Player 1 -->
              <div class="p-3 border-b border-gray-600">
                <div class="flex flex-col">
                  <div class="text-sm font-medium">{{ match.player1.name }}</div>
                  <div class="text-xs text-gray-400">{{ match.player1.team }}</div>
                </div>
              </div>
              <!-- Player 2 -->
              <div class="p-3 border-b border-gray-600">
                <div class="flex flex-col">
                  <div class="text-sm font-medium">{{ match.player2.name }}</div>
                  <div class="text-xs text-gray-400">{{ match.player2.team }}</div>
                </div>
              </div>
              <!-- Match Info -->
              <div class="px-3 py-2 bg-gray-600/50 flex justify-between items-center">
                <div class="text-sm">MAT{{ match.mat }}</div>
                <div class="text-sm">{{ match.time }}</div>
              </div>
            </div>
          </div>
        </div>

        <!-- No Matches -->
        <div v-if="!bracket?.semifinals?.length && !bracket?.finals?.length" class="text-center py-4 text-gray-400">
          No matches scheduled yet
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, defineProps, defineEmits, onMounted, watch } from 'vue';
import axios from 'axios';

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  bracket: {
    type: Object,
    default: null
  }
});

const emit = defineEmits(['close', 'viewBracket']);
const showAllMatches = ref(false);
const divisionAthletes = ref([]);
const isLoading = ref(false);

const fetchDivisionAthletes = async () => {
  if (!props.bracket?.id) return;
  
  isLoading.value = true;
  try {
    const response = await axios.get(route('events.division.athletes', { 
      division: props.bracket.id 
    }));
    divisionAthletes.value = response.data.athletes;
  } catch (error) {
    console.error('Error fetching division athletes:', error);
    divisionAthletes.value = [];
  } finally {
    isLoading.value = false;
  }
};

watch(() => props.bracket?.id, (newId) => {
  if (newId) {
    fetchDivisionAthletes();
  }
});

onMounted(() => {
  if (props.bracket?.id) {
    fetchDivisionAthletes();
  }
});

const close = () => {
  emit('close');
};

const viewBracket = () => {
  emit('viewBracket', props.bracket);
};
</script>

<style scoped>
.translate-x-full {
  transform: translateX(100%);
}
</style> 