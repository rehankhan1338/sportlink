<script setup>
import Company from '@/Components/About/Company.vue';
import DevAndDesing from '@/Components/About/DevAndDesing.vue';
import Hero from '@/Components/About/Hero.vue';
import Inqueries from '@/Components/About/Inqueries.vue';
import SalesAndSupport from '@/Components/About/SalesAndSupport.vue';
import Footer from '@/Components/Footer.vue';
import Navbar from '@/Components/Navbar.vue';


</script>

<template>

<Head title="About SportLink - Tournament Management Software" />

    <!-- Full page container for background color -->
    <div class="min-h-screen bg-black"> <!-- This sets the background color of the entire page -->
      
      <!-- Use Navbar component here -->
      <Navbar />

      <Hero />

      <SalesAndSupport />

      <DevAndDesing />

      <Company />

      <Inqueries />
      

      <Footer />
      
    </div>

</template>



<style scoped>

</style>