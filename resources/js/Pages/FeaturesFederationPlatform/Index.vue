
<script setup>
import Hero from '@/Components/FeaturesFederationPlatform/Hero.vue';
import ManageEvent from '@/Components/FeaturesFederationPlatform/ManageEvent.vue';
import PricingPlan from '@/Components/FeaturesFederationPlatform/PricingPlan.vue';
import PricingPlanTable from '@/Components/FeaturesFederationPlatform/PricingPlanTable.vue';
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

     <ManageEvent />

     <PricingPlan />

     <PricingPlanTable />

      <Footer />
      
    </div>


</template>

<style scoped>

</style>