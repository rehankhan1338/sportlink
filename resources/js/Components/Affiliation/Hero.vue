<template>
  <section class="hero h-96 flex items-center"
  :style="{ backgroundImage: `url(${wrestling})`, backgroundSize: 'cover', backgroundPosition: 'center' }">
  <div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold uppercase text-white mb-8">
      Affiliations
      <span class="mute">Community</span>
    </h1>
    <div class="flex flex-wrap items-center gap-4">
      <!-- Form Section -->
      <form @submit.prevent="handleSearch"
        class="flex items-center gap-3 w-full sm:w-auto">
        <input v-model="searchQuery" 
               @input="handleInput"
               class="form-control p-2 border border-gray-300 rounded-lg w-full sm:w-auto" 
               size="30"
               placeholder="Search..." 
               name="search" 
               type="text" />
      </form>

      <!-- Buttons Section -->
      <div class="flex flex-wrap items-center gap-4 w-full sm:w-auto">
        <button type="submit" @click="handleSearch"
          class="btn btn-secondary px-4 py-2 rounded-lg text-white hover:bg-blue-600 transition"
          style="background-color: #3b82f6 !important;">
          Filter
        </button>
        <a href="/affiliation/register"
          class="btn-primary px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 transition">
          Create New Affiliation
        </a>
      </div>
    </div>
  </div>
</section>

</template>

<script setup>
import { ref } from 'vue';
import wrestling from "@/assets/affiliation.jpg";

const searchQuery = ref('');

const emit = defineEmits(['search']);

const handleSearch = () => {
    emit('search', { search: searchQuery.value });
};

const handleInput = () => {
    emit('search', { search: searchQuery.value });
};
</script>

<style scoped>
.hero {
  height: 12rem;
  /* 96 Tailwind size */
}

.mute {
  opacity: 0.4;
}
</style>
