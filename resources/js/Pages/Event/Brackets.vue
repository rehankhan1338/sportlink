<template>
  <Head title="Brackets - SportLink Tournament Management" />

  <div class="min-h-screen bg-gray-900">
    <!-- Navbar Component -->
    <Navbar />
    
    <Header />

    <!-- Main Content -->
    <div class="relative">
      <!-- Bracket List -->
      <BracketList 
        :brackets="brackets"
        @bracketSelected="handleBracketSelect"
      />

      <!-- Bracket Details Sidebar -->
      <BracketDetails
        :show="showBracketDetails"
        :bracket="selectedBracket"
        @close="closeBracketDetails"
        @viewBracket="viewBracket"
      />

      <!-- Full Bracket View -->
      <div v-if="showFullBracket" class="fixed inset-0 bg-gray-900 z-50 overflow-auto">
        <div class="sticky top-0 bg-gray-800 p-4 flex justify-between items-center">
          <h2 class="text-white text-xl font-bold">{{ selectedBracket?.name }}</h2>
          <button @click="closeFullBracket" class="text-gray-400 hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        <BracketView :bracket="selectedBracket" />
      </div>
    </div>

    <!-- Footer Component -->
    <Footer />
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { Head } from '@inertiajs/vue3';
import Footer from "@/Components/Footer.vue";
import Navbar from "@/Components/Navbar.vue";
import Header from "@/Components/Event/Show/Header.vue";
import BracketList from "@/Components/Event/Brackets/BracketList.vue";
import BracketDetails from "@/Components/Event/Brackets/BracketDetails.vue";
import BracketView from "@/Components/Event/Brackets/BracketView.vue";
import axios from 'axios';

const props = defineProps({
  id: {
    type: String,
    required: true
  }
});

// State
const brackets = ref([]);
const selectedBracket = ref(null);
const showBracketDetails = ref(false);
const showFullBracket = ref(false);

// Fetch brackets data
const fetchBrackets = async () => {
  try {
    const response = await axios.get(route('events.brackets.list', { id: props.id }));
    brackets.value = response.data.brackets.map(bracket => ({
      ...bracket,
      participants_count: bracket.participants?.length || 0,
      matches_count: (bracket.semifinals?.length || 0) + (bracket.finals?.length || 0),
      time_per_match: '2:00'
    }));
  } catch (error) {
    console.error('Error fetching brackets:', error);
  }
};

// Handlers
const handleBracketSelect = (bracket) => {
  selectedBracket.value = bracket;
  showBracketDetails.value = true;
  showFullBracket.value = false;
};

const closeBracketDetails = () => {
  showBracketDetails.value = false;
};

const viewBracket = (bracket) => {
  showFullBracket.value = true;
  showBracketDetails.value = false;
};

const closeFullBracket = () => {
  showFullBracket.value = false;
};

onMounted(() => {
  fetchBrackets();
});
</script>
