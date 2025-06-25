<script setup>
import { ref } from "vue";
import Footer from "@/Components/Footer.vue";
import Navbar from "@/Components/Navbar.vue";
import Header from "@/Components/Event/Show/Header.vue";
import Schedule from "@/Components/Event/Schedule/ScheduleView.vue";
import MatchSchedule from "@/Components/Event/Schedule/MatchSchedule.vue";

// State to manage the active tab
const currentTab = ref("upcoming");

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

    <Schedule />
    <MatchSchedule />
    <!-- Footer Component -->
    <Footer />
  </div>
</template>
