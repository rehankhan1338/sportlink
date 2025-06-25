

<script setup>
import Footer from '@/Components/Footer.vue';
import Navbar from '@/Components/Navbar.vue';
import Support from '@/Components/Support/Support.vue';


</script>

<template>

<Head title="Tournament Software For Combat" />

    <!-- Full page container for background color -->
    <div class="min-h-screen bg-black"> <!-- This sets the background color of the entire page -->
      
      <!-- Use Navbar component here -->
      <Navbar />

     <Support />

      <Footer />
      
    </div>

</template>



<style scoped>

</style>