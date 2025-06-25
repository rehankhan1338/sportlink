<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import Footer from "@/Components/Footer.vue";
import Navbar from "@/Components/Navbar.vue";

const props = defineProps({
    profiles: {
        type: Array,
        required: true
    },
    selectedProfile: {
        type: Object,
        required: false,
        default: null
    },
    event_id: {
        type: String,
        required: false,
        default: null
    }
});

const switchProfile = (profileId) => {
    if (props.event_id) {
        // If coming from event page, redirect to event registration after profile switch
        window.location.href = route('profile.switch', { 
            profile: profileId,
            redirect_to: `/event/${props.event_id}/register`
        });
    } else {
        // Normal profile switch
        window.location.href = route('profile.switch', { profile: profileId });
    }
};

const unlinkProfile = (profileId) => {
    if (confirm('Are you sure you want to unlink this profile?')) {
        window.location.href = route('profile.unlink', { profile: profileId });
    }
};
</script>

<template>
    <Head title="Select Profile" />
    <Navbar />
    <div style="background-color: rgb(28, 28, 28);" class="min-h-screen flex items-center justify-center">
        <div class="max-w-4xl w-full px-4">
            <h1 class="text-white text-[33px] font-bold text-center mb-12">SELECT PROFILE</h1>
            
            <div class="text-center mb-8">
                <p class="text-gray-400 text-lg">
                    A default profile has been created for you using your registration information. 
                    You can create additional profiles (up to 5) or modify your existing ones.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Existing Profiles -->
                <div v-for="profile in profiles" :key="profile.id" 
                     class="bg-[#212121] rounded-lg p-6 flex flex-col items-center cursor-pointer hover:bg-[#2a2a2a] transition"
                     @click="switchProfile(profile.id)">
                    <div class="w-32 h-32 rounded-full bg-gray-600 mb-4 overflow-hidden">
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-20 h-20 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                            </svg>
                        </div>
                    </div>
                    <span class="text-white text-lg">{{ profile.first_name }} {{ profile.last_name }}</span>
                    <span v-if="selectedProfile && selectedProfile.id === profile.id" 
                          class="mt-2 text-green-500 text-sm">Active</span>
                </div>

                <!-- Add New Profile Button -->
                <Link v-if="profiles.length < 5"
                      :href="route('profile.create')"
                      class="bg-[#212121] rounded-lg p-6 flex flex-col items-center cursor-pointer hover:bg-[#2a2a2a] transition">
                    <div class="w-32 h-32 rounded-full bg-gray-600 mb-4 flex items-center justify-center">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <span class="text-gray-400 text-lg">Add a new profile</span>
                </Link>

                <!-- Unlink Profile Button -->
                <div v-if="selectedProfile && profiles.length > 1"
                     @click="unlinkProfile(selectedProfile.id)"
                     class="bg-[#212121] rounded-lg p-6 flex flex-col items-center cursor-pointer hover:bg-[#2a2a2a] transition">
                    <div class="w-32 h-32 rounded-full bg-gray-600 mb-4 flex items-center justify-center">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <span class="text-gray-400 text-lg">Unlink profile</span>
                </div>
            </div>
        </div>
    </div>
    <Footer />
</template>
