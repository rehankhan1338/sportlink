<script setup>
import { Head } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';  // Import Navbar component
import Hero from '@/Components/Organization/Hero.vue';
import Form from '@/Components/Organization/Createorganizationform.vue';
import Footer from '@/Components/Footer.vue';

</script>


<template>
    <Head title="Create a new organization" />
  
    <!-- Full page container for background color -->
    <div class="min-h-screen bg-black"> <!-- This sets the background color of the entire page -->
      
      <!-- Use Navbar component here -->
      <Navbar />

      <Hero />

      <Form />
 
  
      <Footer />
      
    </div>
  </template>
