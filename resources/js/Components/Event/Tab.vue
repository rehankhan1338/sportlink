<template>
  <section class="py-8">
    <div class="container mx-auto px-4">
      <!-- Navigation Tabs -->
      <ul
        class="flex flex-row justify-center shadow-md overflow-hidden"
        style="border-radius: 2.5rem; background-color: rgba(231, 234, 255, 0.15);"
      >
        <li class="w-full">
          <button
            class="w-full block text-center px-6 text-black transition-all py-3 text-sm sm:text-base"
            style="border-radius: 2.5rem;"
            :class="{
              'bg-white text-black': currentTab === 'upcoming',
              'text-white hover:bg-white hover:text-black': currentTab !== 'upcoming'
            }"
            @click="$emit('update-tab', 'upcoming')"
          >
            Upcoming events
          </button>
        </li>
        <li class="w-full">
          <button
            class="w-full block text-center px-6 text-black transition-all py-3 text-sm sm:text-base"
            style="border-radius: 2.5rem;"
            :class="{
              'bg-white text-black': currentTab === 'past',
              'text-white hover:bg-white hover:text-black': currentTab !== 'past'
            }"
            @click="$emit('update-tab', 'past')"
          >
            Past events
          </button>
        </li>
        <li v-if="userEventsCount > 0" class="w-full">
          <button
            class="w-full block text-center px-6 text-black transition-all py-3 text-sm sm:text-base"
            style="border-radius: 2.5rem;"
            :class="{
              'bg-white text-black': currentTab === 'my',
              'text-white hover:bg-white hover:text-black': currentTab !== 'my'
            }"
            @click="$emit('update-tab', 'my')"
          >
            My events
          </button>
        </li>
      </ul>
    </div>
  </section>
</template>

<script setup>
defineProps({
  currentTab: {
    type: String,
    required: true,
  },
  userEventsCount: {
    type: Number,
    default: 0,
  },
});

defineEmits(['update-tab']);
</script>
