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
            class="w-full block text-center px-6 text-black transition-all"
            style="border-radius: 2.5rem; padding-top: 0.6rem; padding-bottom: 0.6rem;"
            :class="{
              'bg-white text-black': currentTab === 'finder',
              'text-white hover:bg-white hover:text-black': currentTab !== 'finder'
            }"
            @click="$emit('update-tab', 'finder')"
          >
            Academy Finder
          </button>
        </li>
        <li class="w-full">
          <button
            class="w-full block text-center px-6 text-black transition-all"
            style="border-radius: 2.5rem; padding-top: 0.6rem; padding-bottom: 0.6rem;"
            :class="{
              'bg-white text-black': currentTab === 'rankings',
              'text-white hover:bg-white hover:text-black': currentTab !== 'rankings'
            }"
            @click="$emit('update-tab', 'rankings')"
          >
            Academy Rankings
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
});
</script>

<style scoped>

</style>
