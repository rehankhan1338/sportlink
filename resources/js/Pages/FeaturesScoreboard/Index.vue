

<script setup>
import Dedicated from '@/Components/FeaturesScoreboard/Dedicated.vue';
import GoToScoreBoard from '@/Components/FeaturesScoreboard/GoToScoreBoard.vue';
import Hero from '@/Components/FeaturesScoreboard/Hero.vue';
import ScoreBoard from '@/Components/FeaturesScoreboard/ScoreBoard.vue';
import Footer from '@/Components/Footer.vue';
import Navbar from '@/Components/Navbar.vue';
import GoLive from '@/Components/Home/GoLive.vue';
import Supported from '@/Components/FeaturesScoreboard/Supported.vue';


</script>
<template>
    <Head title="Tournament Software For Combat" />
  
    <!-- Full page container for background color -->
    <div class="min-h-screen bg-black"> <!-- This sets the background color of the entire page -->
      
      <!-- Use Navbar component here -->
      <Navbar />
  
      <!-- Hero section -->
      <Hero />
      
      <!-- <ScoreBoard /> -->

      <!-- GoToScoreBoard -->
      <GoToScoreBoard />

      <!-- Dedicated -->
      <Dedicated />

      <GoLive />

      <Supported />

      <Footer />
      
    </div>
  </template>



<style scoped>

</style>