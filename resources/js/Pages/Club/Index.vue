<script setup>
import { ref } from "vue";
import Footer from "@/Components/Footer.vue";
import Navbar from "@/Components/Navbar.vue";
import Header from "@/Components/Club/Header.vue";
import Content from "@/Components/Club/Content.vue";

// State to manage the active tab
const currentTab = ref("finder");

// Function to update the current tab
const setTab = (tab) => {
  currentTab.value = tab;
};
</script>

<template>
  <Head title="Tournament Software For Combat" />

  <div class="min-h-screen bg-black">
    <!-- Navbar Component -->
    <Navbar />

 <Header />

 <Content />

    <!-- Footer Component -->
    <Footer />
  </div>
</template>
