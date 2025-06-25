<template>
    <div class="max-w-full mx-auto px-8" id="featurecards">
        <!-- Use grid layout to show 4 cards in a row on large screens -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="flex flex-col items-center justify-between bg-[#171719] text-white rounded-xl pt-12">
                <h3 class="text-center mb-8 max-w-[17rem] text-xl font-semibold">
                    <strong>The bracket builder</strong> will do the heavy lifting.
                </h3>
                <img :src="feature1" class="w-full">
            </div>

            <div class="flex flex-col items-center justify-between bg-[#171719] text-white rounded-xl pt-12">
                <h3 class="text-center mb-8 text-xl font-semibold">
                    <strong>Handle registrations,</strong> payments and even SMS notifications.
                </h3>
                <div class="w-11/12 flex items-center justify-center bg-gray-600 rounded-xl p-3 mb-8">
                    <img :src="feature_messages" class="mr-4">
                    <div class="flex flex-col">
                        <div class="flex justify-between">
                            <p class="text-lg font-bold">SC Tournament</p>
                            <p class="text-lg text-opacity-50">1m ago</p>
                        </div>
                        <p class="text-sm text-opacity-75">Hi Anna! We're all really excited that...</p>
                    </div>
                </div>
                <img :src="feature_phone" class="w-full">
            </div>

            <div class="flex flex-col items-center justify-between bg-[#171719] text-white rounded-xl pt-12">
                <h3 class="text-center mb-8 max-w-[17rem] text-xl font-semibold">
                    <strong>Go live with style.</strong> Built-in streaming with digital scoreboards.
                </h3>
                <img :src="feature_comu" alt="Smoothcomp Logo" class="w-full">
            </div>

            <div class="flex flex-col items-center justify-between bg-[#171719] text-white rounded-xl pt-12">
                <h3 class="text-center mb-8 max-w-[17rem] text-xl font-semibold">
                    <strong>Reach our global</strong> community of 1 million players.
                </h3>
                <img :src="feature_comu" class="w-full">
            </div>
        </div>
    </div>
</template>

<script setup>
import feature1 from '@/assets/feature-1.svg';
import feature_comu from '@/assets/feature-community-faces@1x.webp';
import feature_messages from '@/assets/feature-messages.svg';
import feature_phone from '@/assets/feature-phone@1x.webp';
</script>
