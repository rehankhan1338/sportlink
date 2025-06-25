<script setup>
import { Head } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';  // Import Navbar component
import Hero from '@/Components/Home/Hero.vue';  // Import Hero component
import TournamentHeading from '@/Components/Home/TournamentHeading.vue';  // Import TournamentHeading component
import TournamentCard from '@/Components/Home/TournamentCard.vue';  // Import TournamentHeading component
import FeatureCard from '@/Components/Home/FeatureCard.vue';  // Import TournamentHeading component
import BracketBuilder from '@/Components/Home/BracketBuilder.vue';  // Import TournamentHeading component
import ScoreBoard from '@/Components/Home/ScoreBoard.vue';  // Import TournamentHeading component
import GoLive from '@/Components/Home/GoLive.vue';  // Import TournamentHeading component
import Match from '@/Components/Home/Match.vue';
import CheckIn from '@/Components/Home/CheckIn.vue';
import Slider from '@/Components/Home/Slider.vue';
import PricingPlan from '@/Components/Home/PricingPlan.vue';
import Event from '@/Components/Home/Event.vue';
import Package from '@/Components/Home/Package.vue';
import Platform from '@/Components/Home/Platform.vue';
import LogoSlider from '@/Components/Home/LogoSlider.vue';
import Footer from '@/Components/Footer.vue';
</script>

<template>
  <Head title="SportLink - Tournament Management Software for Combat Sports" />

  <!-- Full page container for background color -->
  <div class="min-h-screen bg-black"> <!-- This sets the background color of the entire page -->
    
    <!-- Use Navbar component here -->
    <Navbar />

    <!-- Hero section -->
    <Hero />
    
    <!-- Tournament Heading section -->
    <TournamentHeading />
    
    <!-- Tournament Card -->
    <TournamentCard />

    <!-- FeatureCard Section -->
    <FeatureCard />

    <!-- BracketBuilder Section -->
    <BracketBuilder />
    
    <!-- ScoreBoard Section -->
    <ScoreBoard />
    
    <!-- GoLive Section -->
    <GoLive />

    <!-- Match Section -->
    <Match />
    
    <!-- Match Section -->
    <CheckIn />
    
    <!-- Slider Section -->
    <!-- <Slider /> -->


    <!-- Pricing Plan Section -->
    <PricingPlan />

    <!-- Event  Section -->
    <Event />

    <!-- Package  Section -->
    <Package />

    <!-- Platform  Section -->
    <!-- <Platform /> -->

    <LogoSlider />

    <Footer />
    
  </div>
</template>

<style scoped>
/* Page-specific styles */
</style>
