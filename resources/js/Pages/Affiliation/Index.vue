<script setup>
import Hero from '@/Components/Affiliation/Hero.vue';
import AffiliationList from '@/Components/Affiliation/AffiliationList.vue';
import Footer from '@/Components/Footer.vue';
import Navbar from '@/Components/Navbar.vue';
import { ref } from 'vue';

const affiliationListRef = ref(null);

const handleFilter = (filters) => {
    affiliationListRef.value?.handleFilter(filters);
};
</script>

<template>

<Head title="Tournament Softwares For Combat" />

    <!-- Full page container for background color -->
    <div class="min-h-screen bg-black"> <!-- This sets the background color of the entire page -->
      
      <!-- Use Navbar component here -->
      <Navbar />

      <Hero @search="handleFilter" />
      
      <AffiliationList ref="affiliationListRef" />

      <Footer />
      
    </div>

</template>



<style scoped>

</style>