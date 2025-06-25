<template>
  <section
    class="hero flex items-center"
    :style="{ backgroundImage: `url(${backgroundImage})`, backgroundSize: 'cover', backgroundPosition: 'center' }"
  >
    <div class="container mx-auto px-4">
      <div class="flex flex-col gap-4">
        <!-- Dynamic Heading -->
        <h1 class="text-2xl font-bold uppercase text-white mb-4">{{ heading }}</h1>

        <div class="flex gap-4">
        <!-- Filter Form -->
        <form @submit.prevent="handleSubmit" class="flex flex-wrap gap-4 items-end">
          <div class="flex-1 min-w-[200px]">
            <label for="search" class="block text-white mb-2">Search Academy</label>
            <input
              type="text"
              id="search"
              v-model="searchQuery"
              placeholder="Search by name..."
              class="w-full px-4 py-2 rounded-lg bg-white/90 focus:bg-white focus:outline-none"
              @input="handleSearchInput"
            />
          </div>
          <button
            type="submit"
            class="btn-primary px-6 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 transition"
          >
            Filter
          </button>
        </form>

        <!-- Dynamic Button -->
        <Link
          v-if="$page.props.auth.user"
          :href="route('academy.register')"
          style="align-self: end;"
          class="btn-primary px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 transition self-start"
        >
          {{ buttonText }}
        </Link>
        <Link
          v-else
          :href="route('login')"
          style="align-self: end;"
          class="btn-primary px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 transition self-start"
        >
          Login to Register Academy
        </Link>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { defineProps, defineEmits, ref } from "vue";
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  heading: {
    type: String,
    required: true,
  },
  buttonText: {
    type: String,
    required: true,
  },
  buttonLink: {
    type: String,
    required: true,
  },
  backgroundImage: {
    type: String,
    default: '',
  }
});

const emit = defineEmits(['filter']);

const searchQuery = ref('');

const handleSearchInput = () => {
  emit('filter', {
    search: searchQuery.value
  });
};

const handleSubmit = () => {
  emit('filter', {
    search: searchQuery.value
  });
};
</script>

<style scoped>
.hero {
  height: auto;
  min-height: 12rem;
  padding: 2rem 0;
}
</style>
