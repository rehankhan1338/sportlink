<script setup>
import { ref, onMounted } from "vue";
import { Head } from '@inertiajs/vue3';
import Footer from "@/Components/Footer.vue";
import Navbar from "@/Components/Navbar.vue";
import Header from "@/Components/Event/Show/Header.vue";
import SubMenu from "@/Components/Event/Show/SubMenu.vue";
import InfoView from "@/Components/Event/Show/Information/InfoView.vue";
import SuccessMessage from '@/Components/SuccessMessage.vue';

// Get the event data from props
const props = defineProps({
    event: {
        type: Object,
        required: true
    },
    organization: {
        type: Object,
        default: null
    },
    success: String,
    showSuccessMessage: Boolean
});

// State to manage the active tab and success message
const activeTab = ref("info");
const showSuccess = ref(false);
const successMessage = ref('');

// Function to update the current tab
const handleTabChange = (tab) => {
    activeTab.value = tab;
};

// Check URL parameters for payment success
onMounted(() => {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('success') === 'true' && urlParams.get('payment_status') === 'completed') {
        showSuccess.value = true;
        successMessage.value = 'Payment completed successfully! Your registration is now complete.';
    }
});
</script>

<template>
  <Head>
    <title>{{ event?.title ? `${event.title} - SportLink Tournament` : 'Event - SportLink Tournament' }}</title>
  </Head>

  <div class="min-h-screen bg-black">
    <!-- Navbar Component -->
    <Navbar />
    
    <Header :event="event" />


    <!-- Success Message -->
    <SuccessMessage 
        v-if="showSuccess || showSuccessMessage" 
        :message="successMessage || success" 
        :duration="5000"
    />

    <InfoView :event="event" :organization="organization" :active-tab="activeTab" />

    <!-- Footer Component -->
    <Footer />
  </div>
</template>
