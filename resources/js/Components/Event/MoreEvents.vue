<template>
  <section class="py-4">
    <div class="container mx-auto">
      <!-- Header Section -->
      <div class="mb-8">
        <!-- <div class="flex items-center justify-between p-2 flex-wrap mb-4">
          <h2 class="text-xl text-white font-bold">{{ getTabTitle }}</h2>
          <div class="relative">
            <div class="flex items-center border border-gray-300 rounded-md px-4 py-2">
              <span class="mr-2 text-white">Sort by distance</span>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20"
                fill="currentColor">
                <path fill-rule="evenodd"
                  d="M5.23 7.21a.75.75 0 011.06.02L10 11.44l3.71-4.21a.75.75 0 111.12.98l-4.25 4.82a.75.75 0 01-1.12 0L5.21 8.21a.75.75 0 01.02-1.06z"
                  clip-rule="evenodd" />
              </svg>
            </div>
          </div>
        </div> -->

        <!-- Filter Component -->
        <EventFilter
          :current-tab="currentTab"
          @filter-change="handleFilterChange"
        />
      </div>

      <!-- Event Cards -->
      <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 p-2 gap-6">
        <!-- Event Card Template -->
        <div v-for="event in filteredEvents" :key="event.id" class="bg-white border border-gray-200 rounded-lg shadow hover:shadow-lg transition-shadow duration-300">
          <a :href="route('event.show', { id: event.id })" class="block">
            <img 
              :src="event.image_url || Placeholderimage" 
              :alt="event.title" 
              class="w-full h-32 sm:h-40 object-cover rounded-t-lg"
            />
          </a>
          <div class="p-2 sm:p-4 border-t border-[#d8d8d8]">
            <h3 class="text-lg font-semibold mb-2 truncate">
              <a :href="route('event.show', { id: event.id })" class="hover:underline text-gray-800">
                {{ event.title }}
              </a>
            </h3>
            <p class="text-sm text-gray-600 truncate mb-2">
              {{ event.location }}, {{ event.country }}
            </p>
            <div class="flex justify-between items-center text-sm text-gray-500">
              <span>{{ formatDate(event.start_date) }}</span>
              <span v-if="isToday(event.start_date)" class="text-green-600 font-medium">Today</span>
            </div>
          </div>
        </div>

        <!-- No Events Message -->
        <div v-if="filteredEvents.length === 0" class="col-span-full text-center text-white py-8">
          <p class="text-lg">No events found</p>
          <p class="text-sm text-gray-400 mt-2">Try adjusting your filters or check back later</p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed } from 'vue';
import EventFilter from './Filter.vue';
import Placeholderimage from '@/assets/placeholder-image.jpg';

const props = defineProps({
  currentTab: {
    type: String,
    required: true,
  },
  events: {
    type: Array,
    default: () => [],
  },
  myEvents: {
    type: Array,
    default: () => [],
  },
  userId: {
    type: [String, Number, null],
    default: null,
  },
});

// Filter state
const filters = ref({
  search: '',
  startDate: '',
  endDate: '',
  country: '',
});

// Computed properties
const getTabTitle = computed(() => {
  const titles = {
    upcoming: 'Upcoming Events',
    past: 'Past Events',
    my: 'My Events',
  };
  return titles[props.currentTab] || 'Events';
});

// Handle filter changes from the Filter component
const handleFilterChange = (filterData) => {
  if (filterData.tab === props.currentTab) {
    filters.value = filterData.filters;
  }
};

// Filter events based on current tab and filters
const filteredEvents = computed(() => {
  const now = new Date();
  const eventsToFilter = props.currentTab === 'my' ? props.myEvents : props.events;
  
  return eventsToFilter.filter(event => {
    const startDate = new Date(event.start_date);
    const endDate = new Date(event.end_date);
    
    // Check for published status and public visibility only for upcoming events tab
    if (props.currentTab === 'upcoming' && (event.status !== 'published' || event.visibility !== 'public')) {
      return false;
    }

    // Tab-specific filtering
    if (props.currentTab === 'upcoming' && endDate < now) return false;
    if (props.currentTab === 'past' && endDate >= now) return false;

    // Apply search filter
    if (filters.value.search && !event.title.toLowerCase().includes(filters.value.search.toLowerCase())) {
      return false;
    }

    // Apply date filters
    if (filters.value.startDate && startDate < new Date(filters.value.startDate)) {
      return false;
    }
    if (filters.value.endDate && startDate > new Date(filters.value.endDate)) {
      return false;
    }

    // Apply country filter
    if (filters.value.country && event.country !== filters.value.country) {
      return false;
    }

    return true;
  });
});

// Helper functions
function formatDate(dateStr) {
  if (!dateStr) return '';
  const d = new Date(dateStr);
  return d.toLocaleString('default', { month: 'short', day: 'numeric' });
}

function isToday(dateStr) {
  const today = new Date();
  const d = new Date(dateStr);
  return (
    d.getDate() === today.getDate() &&
    d.getMonth() === today.getMonth() &&
    d.getFullYear() === today.getFullYear()
  );
}
</script>

<style scoped>
.event-card {
  transition: all 0.3s ease;
}

.event-card:hover {
  transform: translateY(-2px);
}
</style>
