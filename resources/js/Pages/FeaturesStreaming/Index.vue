
<script setup>
import Supported from '@/Components/FeaturesScoreboard/Supported.vue';
import Camera from '@/Components/FeaturesStreaming/Camera.vue';
import FreeStreaming from '@/Components/FeaturesStreaming/FreeStreaming.vue';
import Hero from '@/Components/FeaturesStreaming/Hero.vue';
import PPV from '@/Components/FeaturesStreaming/PPV.vue';
import StartStreaming from '@/Components/FeaturesStreaming/StartStreaming.vue';
import Footer from '@/Components/Footer.vue';
import Navbar from '@/Components/Navbar.vue';


</script>

<template>

    <Head title="Tournament Software For Combat" />

    <!-- Full page container for background color -->
    <div class="min-h-screen bg-black"> <!-- This sets the background color of the entire page -->
      
      <!-- Use Navbar component here -->
      <Navbar />

      <Hero />

      <FreeStreaming />
      
      <StartStreaming />

      <PPV />

      <Camera />

      <Supported />

      <Footer />
      
    </div>


</template>

<style scoped>

</style>