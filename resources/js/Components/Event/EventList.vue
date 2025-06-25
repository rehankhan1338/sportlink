<template>
  <div>
    <EventFilter
      :current-tab="currentTab"
      @filter-change="handleFilterChange"
    />
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
      <div v-for="event in filteredEvents" :key="event.id" class="event-card">
        <!-- Event card content -->
        <div class="bg-white rounded-lg shadow-md p-6">
          <h3 class="text-xl font-semibold mb-2">{{ event.name }}</h3>
          <p class="text-gray-600 mb-4">{{ event.description }}</p>
          <div class="flex justify-between items-center">
            <span class="text-sm text-gray-500">{{ formatDate(event.startDate) }}</span>
            <span class="text-sm text-gray-500">{{ event.country }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import EventFilter from './Filter.vue';

const props = defineProps({
  currentTab: {
    type: String,
    required: true,
  },
  events: {
    type: Array,
    required: true,
  },
});

const filters = ref({
  search: '',
  startDate: '',
  endDate: '',
  country: '',
});

// Handle filter changes from the Filter component
const handleFilterChange = (filterData) => {
  if (filterData.tab === props.currentTab) {
    filters.value = filterData.filters;
  }
};

// Filter events based on current tab and filters
const filteredEvents = computed(() => {
  return props.events.filter(event => {
    // First filter by tab
    if (props.currentTab === 'upcoming' && new Date(event.startDate) < new Date()) {
      return false;
    }
    if (props.currentTab === 'past' && new Date(event.startDate) >= new Date()) {
      return false;
    }

    // Then apply other filters
    if (filters.value.search && !event.name.toLowerCase().includes(filters.value.search.toLowerCase())) {
      return false;
    }

    if (filters.value.startDate && new Date(event.startDate) < new Date(filters.value.startDate)) {
      return false;
    }

    if (filters.value.endDate && new Date(event.startDate) > new Date(filters.value.endDate)) {
      return false;
    }

    if (filters.value.country && event.country !== filters.value.country) {
      return false;
    }

    return true;
  });
});

// Helper function to format dates
const formatDate = (date) => {
  return new Date(date).toLocaleDateString();
};
</script>

<style scoped>
.event-card {
  transition: transform 0.2s;
}

.event-card:hover {
  transform: translateY(-5px);
}
</style> 