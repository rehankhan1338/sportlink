<script setup>
import Hero from '@/Components/Athletes/Hero.vue';
import LastDays from '@/Components/Athletes/LastDays.vue';
import Footer from '@/Components/Footer.vue';
import Navbar from '@/Components/Navbar.vue';

</script>

<template>

<Head title="Athletes - SportLink Tournament Management" />

    <!-- Full page container for background color -->
    <div class="min-h-screen bg-black"> <!-- This sets the background color of the entire page -->
      
      <!-- Use Navbar component here -->
      <Navbar />

      <Hero />

      <LastDays />
      
      <Footer />
      
    </div>

</template>



<style scoped>

</style>