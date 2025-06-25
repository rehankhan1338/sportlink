<template>
  <div class="relative h-[75vh] max-h-[75vh] pb-[15vh] bg-cover bg-center"
       :style="{ backgroundImage: `url(${Hero})` }">
    <!-- Black Overlay -->
    <div class="absolute inset-0 bg-black bg-opacity-70"></div>

    <!-- Content -->
    <div class="relative z-10 flex flex-col items-start justify-center h-full text-left text-white p-6 sm:p-20 w-full sm:w-4/5">
      <!-- Heading -->
      <h1 class="text-3xl sm:text-5xl font-medium mb-6 leading-tight">
        <strong>
          We empower the world's greatest tournaments and athletes
        </strong>
      </h1>

      <!-- Buttons with gap -->
      <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4 mt-6 w-full">
        <a href="/event"
           class="text-lg sm:text-[1.31rem] px-6 py-3 bg-red-600 hover:bg-red-500 text-white rounded-full cursor-pointer flex items-center justify-center">
          Find events
        </a>

      <InertiaLink
      :href="route('dashboard') + '#events-container'"
      class="text-lg sm:text-[1.31rem] px-6 py-3 rounded-full cursor-pointer bg-opacity-10 bg-white text-opacity-90 text-white flex items-center justify-center backdrop-blur-sm hover:text-red-600 hover:bg-transparent"
      >
      Create event
      </InertiaLink>

      </div>
    </div>
  </div>
</template>

<script setup>
import { InertiaLink } from '@inertiajs/inertia-vue3';

import Hero from '@/assets/hero.webp';
</script>

<style scoped>
/* Additional custom styles (if necessary) */
</style>