<template>
    <div class=" min-h-screen py-8">
        <!-- Centered Filter Form -->
        <div class="flex justify-center items-center mb-8">
            <form
                class="w-full max-w-7xl flex flex-col sm:flex-row items-center gap-4 bg-[#2E2E2E] p-6 rounded-lg shadow-md">
                <!-- Search Academy Field -->
                <div class="w-full">
                    <input type="text" placeholder="Search Academy"
                        class="w-full px-4 py-2 border border-gray-600 bg-[#3C3C3C] text-black rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
                <!-- Affiliation Dropdown -->
                <div class="w-full">
                    <select
                        class="w-full px-4 py-2 border border-gray-600 bg-[#3C3C3C] text-black rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Affiliation</option>
                        <option value="aff1">Affiliation 1</option>
                        <option value="aff2">Affiliation 2</option>
                        <option value="aff3">Affiliation 3</option>
                    </select>
                </div>
                <!-- Filter Button -->
                <div class="w-full">
                    <button type="submit"
                        class="w-full px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Main Content Section -->
        <div class="flex flex-col sm:flex-row gap-4 max-w-7xl mx-auto p-6 rounded-lg shadow-lg">
            <!-- Academy List -->
            <div class="w-full sm:w-1/3 bg-[#3C3C3C] p-4 rounded-lg shadow-md flex flex-col">
                <h2 class="text-lg font-bold mb-4 text-white">Academy List</h2>
                <ul class="space-y-2 overflow-y-auto flex-1">
                    <li class="p-2 bg-[#4A4A4A] rounded-lg flex items-center gap-4 text-white">
                        <img src="https://smoothcomp.com/pictures/t/5188700-re4/aiga-tashkent-open-2024.png"
                            alt="Academy 1" class="w-10 h-10 rounded-full" />
                        <span>
                            <InertiaLink :href="route('club')">
                                Academy 1
                            </InertiaLink>
                        </span>
                    </li>
                    <li class="p-2 bg-[#4A4A4A] rounded-lg flex items-center gap-4 text-white">
                        <img src="https://smoothcomp.com/pictures/t/5188700-re4/aiga-tashkent-open-2024.png"
                            alt="Academy 2" class="w-10 h-10 rounded-full" />
                        <span>Academy 2</span>
                    </li>
                    <li class="p-2 bg-[#4A4A4A] rounded-lg flex items-center gap-4 text-white">
                        <img src="https://smoothcomp.com/pictures/t/5188700-re4/aiga-tashkent-open-2024.png"
                            alt="Academy 3" class="w-10 h-10 rounded-full" />
                        <span>Academy 3</span>
                    </li>
                    <li class="p-2 bg-[#4A4A4A] rounded-lg flex items-center gap-4 text-white">
                        <img src="https://smoothcomp.com/pictures/t/5188700-re4/aiga-tashkent-open-2024.png"
                            alt="Academy 4" class="w-10 h-10 rounded-full" />
                        <span>Academy 4</span>
                    </li>
                    <li class="p-2 bg-[#4A4A4A] rounded-lg flex items-center gap-4 text-white">
                        <img src="https://smoothcomp.com/pictures/t/5188700-re4/aiga-tashkent-open-2024.png"
                            alt="Academy 5" class="w-10 h-10 rounded-full" />
                        <span>Academy 5</span>
                    </li>
                </ul>
            </div>

            <!-- Map Section -->
            <div class="w-full sm:w-2/3 bg-[#3C3C3C] p-4 rounded-lg shadow-md flex flex-col">
                <h2 class="text-lg font-bold mb-4 text-white">Map</h2>

                <!-- Search by Location inside the map -->
                <div class="relative mb-4">
                    <input id="location-search" type="text" placeholder="Search by Location"
                        class="w-full p-3 bg-transparent border-2 border-white text-white rounded-lg focus:outline-none absolute top-4 z-10" />
                </div>

                <div class="w-full flex-1 bg-[#4A4A4A] rounded-lg">
                    <!-- Google Map Embed or Placeholder -->
                    <iframe class="w-full h-full rounded-lg"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.835434508616!2d-122.47820688468168!3d37.81992977975186!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8085810c5a1e2b3b%3A0x2a749b7c8f77c68b!2sGolden%20Gate%20Bridge!5e0!3m2!1sen!2sus!4v1686355699892!5m2!1sen!2sus"
                        allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>


    </div>
</template>

<script setup>
// No additional logic is necessary for static content
import { InertiaLink } from '@inertiajs/inertia-vue3'; // Import InertiaLink
</script>

<style scoped>
/* Custom styles can be added if needed */
</style>