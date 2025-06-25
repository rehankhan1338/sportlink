<script setup>
import { ref, onMounted } from "vue";
import { Head } from '@inertiajs/vue3';
import Hero from "@/Components/Club/Finder/Hero.vue";
import Footer from "@/Components/Footer.vue";
import Navbar from "@/Components/Navbar.vue";
import LastDays from "@/Components/Affiliation/LastDays.vue";
import SuccessMessage from '@/Components/SuccessMessage.vue';

const lastDaysRef = ref(null);
const showSuccess = ref(false);
const successMessage = ref('');

const handleFilter = (filters) => {
    lastDaysRef.value?.handleFilter(filters);
};

onMounted(() => {
    // Check URL parameters for success message
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('success') === 'true') {
        showSuccess.value = true;
        successMessage.value = urlParams.get('message') || 'Operation completed successfully!';
        
        // Clean up the URL
        const newUrl = window.location.pathname;
        window.history.replaceState({}, document.title, newUrl);
    }
});
</script>

<template>
  <Head title="Academy Rankings - SportLink" />

  <div class="min-h-screen bg-black">
    <!-- Navbar Component -->
    <Navbar />

    <!-- Success Message Component -->
    <SuccessMessage 
        v-if="showSuccess" 
        :message="successMessage" 
        :duration="5000"
    />

    <!-- Hero Component -->
    <Hero
      heading="Academies"
      buttonText="Create new academy"
      buttonLink="/register/academy"
      @filter="handleFilter"
    />

    <!-- Academy Rankings Content -->
    <LastDays ref="lastDaysRef" />

    <!-- Footer Component -->
    <Footer />
  </div>
</template>
