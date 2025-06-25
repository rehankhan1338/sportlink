<script setup>
import { ref, computed } from "vue";
import Tab from "@/Components/Event/Tab.vue";
import Footer from "@/Components/Footer.vue";
import Navbar from "@/Components/Navbar.vue";
import Filter from "@/Components/Event/Filter.vue";
import Map from "@/Components/Event/Map.vue";
import MoreEvents from "@/Components/Event/MoreEvents.vue";

// State to manage the active tab
const currentTab = ref("upcoming");

// Function to update the current tab
const setTab = (tab) => {
  currentTab.value = tab;
};

const props = defineProps({
  myEvents: Array,
  userId: [String, Number, null],
  events: Array,
});

// Compute the number of events created by the user
const userEventsCount = computed(() => props.myEvents?.length || 0);
</script>

<template>
  <Head title="Events - SportLink Tournament Management" />

  <div class="min-h-screen bg-black">
    <!-- Navbar Component -->
    <Navbar />
    
    <Tab :current-tab="currentTab" :user-events-count="userEventsCount" @update-tab="setTab" />

   

    <Map :current-tab="currentTab" />

    <MoreEvents :my-events="myEvents" :user-id="userId" :current-tab="currentTab" :events="events" />

    <!-- Footer Component -->
    <Footer />
  </div>
</template>
