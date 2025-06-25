<script setup>
import { ref } from "vue";
import Footer from "@/Components/Footer.vue";
import Navbar from "@/Components/Navbar.vue";
import Header from "@/Components/Event/Show/Header.vue";
import Filter from "@/Components/Event/Athletes/Filter.vue";

// State to manage the active tab
const currentTab = ref("upcoming");

// Function to update the current tab
const setTab = (tab) => {
  currentTab.value = tab;
};
</script>

<template>
  <Head title="Athletes - SportLink Tournament Management" />

  <div class="min-h-screen bg-black">
    <!-- Navbar Component -->
    <Navbar />
    
    <Header />

    <Filter />


    <!-- Footer Component -->
    <Footer />
  </div>
</template>
