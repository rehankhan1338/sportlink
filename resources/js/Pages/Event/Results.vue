<script setup>
import { ref } from "vue";
import Footer from "@/Components/Footer.vue";
import Navbar from "@/Components/Navbar.vue";
import Header from "@/Components/Event/Show/Header.vue";
import ResultForm from "@/Components/Event/Results/ResultForm.vue";

// State to manage the active tab
const currentTab = ref("upcoming");

// Function to update the current tab
const setTab = (tab) => {
  currentTab.value = tab;
};
</script>

<template>
  <Head title="Results - SportLink Tournament Management" />

  <div class="min-h-screen bg-black">
    <!-- Navbar Component -->
    <Navbar />
    
    <Header />

    <ResultForm />


    <!-- Footer Component -->
    <Footer />
  </div>
</template>
