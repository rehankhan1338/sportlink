<template>
  <div class="bg-gray-900 text-white">
    <div class="container mx-auto p-4">
      <!-- Header -->
      <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-4">
          <h2 class="text-xl font-semibold">Group</h2>
          <div class="flex items-center gap-2">
            <span class="text-gray-400">Eta. start</span>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
          </div>
          <span class="text-gray-400">Where</span>
        </div>
      </div>

      <!-- Bracket List -->
      <div class="space-y-2">
        <div v-for="bracket in brackets" :key="bracket.id" 
             class="flex items-center justify-between bg-gray-800 p-4 rounded-lg cursor-pointer hover:bg-gray-700"
             @click="selectBracket(bracket)">
          <div class="flex flex-col">
            <div class="text-white font-medium">{{ bracket.name }}</div>
            <div class="text-gray-400 text-sm">{{ bracket.participants_count }} participants</div>
          </div>
          <div class="flex items-center gap-8">
            <div class="text-white">{{ bracket.start_time }}</div>
            <div class="text-white">Mat {{ bracket.mat_number }}</div>
            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, defineEmits } from 'vue';

const emit = defineEmits(['bracketSelected']);

const props = defineProps({
  brackets: {
    type: Array,
    required: true,
    default: () => []
  }
});

const selectBracket = (bracket) => {
  emit('bracketSelected', bracket);
};
</script>

<style scoped>
.container {
  max-width: 1200px;
}
</style> 