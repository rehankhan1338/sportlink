<template>
    <div class="min-h-screen px-6 md:px-28 text-white p-6">
        <h1 class="text-2xl font-bold mb-6">RESULTS</h1>
        <div class="bg-[rgba(231,234,255,0.1)] mb-8 p-6 rounded-lg w-full mx-auto">
            <form class="flex flex-wrap gap-4">
                <!-- Athlete Name and Academy Name Fields -->
                <div class="flex flex-col md:flex-row md:gap-4 w-full">
                    <input type="text"
                        class="flex-1 p-3 rounded-lg bg-white text-black placeholder-gray-400 border border-black focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Search Athlete Name" />

                    <input type="text"
                        class="flex-1 p-3 rounded-lg bg-white text-black placeholder-gray-400 border border-black focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Search Academy Name" />
                </div>

                <!-- Group Name and Team Name Fields -->
                <div class="flex flex-col md:flex-row md:gap-4 w-full">
                    <input type="text"
                        class="flex-1 p-3 rounded-lg bg-white text-black placeholder-gray-400 border border-black focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Search Group Name" />

                    <input type="text"
                        class="flex-1 p-3 rounded-lg bg-white text-black placeholder-gray-400 border border-black focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Search Team Name" />
                </div>

                <!-- Country Dropdown and Category Dropdown -->
                <div class="flex flex-col md:flex-row md:gap-4 w-full">
                    <div class="flex-1 relative">
                        <select
                            class="w-full p-3 rounded-lg bg-white text-black placeholder-gray-400 border border-black focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none">
                            <option value="" disabled selected>Choose a country</option>
                            <option value="usa">🇺🇸 United States</option>
                            <option value="canada">🇨🇦 Canada</option>
                            <option value="uk">🇬🇧 United Kingdom</option>
                            <option value="india">🇮🇳 India</option>
                            <option value="australia">🇦🇺 Australia</option>
                        </select>

                        <div
                            class="absolute top-0 right-0 h-full w-10 flex items-center justify-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.23 7.21a.75.75 0 011.06-.02L10 10.94l3.72-3.75a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.23 8.27a.75.75 0 01-.02-1.06z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>

                    <div class="flex-1 relative">
                        <select
                            class="w-full p-3 rounded-lg bg-white text-black placeholder-gray-400 border border-black focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none">
                            <option value="" disabled selected>Choose a category</option>
                            <option value="category1">Category 1</option>
                            <option value="category2">Category 2</option>
                            <option value="category3">Category 3</option>
                            <option value="category4">Category 4</option>
                        </select>

                        <div
                            class="absolute top-0 right-0 h-full w-10 flex items-center justify-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.23 7.21a.75.75 0 011.06-.02L10 10.94l3.72-3.75a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.23 8.27a.75.75 0 01-.02-1.06z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="bg-[rgba(231,234,255,0.1)] mb-8 p-6 rounded-lg w-full mx-auto">
            <!-- Heading -->
            <h1 class="text-2xl text-center font-bold mb-6">TOTAL MEDALS</h1>

            <!-- Medals Row -->
            <div class="flex flex-wrap justify-center gap-8">
                <!-- Gold Medal -->
                <div class="text-center">
                    <p class="text-lg font-semibold">0</p>
                    <p class="text-sm font-bold text-yellow-500">GOLD</p>
                </div>

                <!-- Silver Medal -->
                <div class="text-center">
                    <p class="text-lg font-semibold">0</p>
                    <p class="text-sm font-bold text-gray-500">SILVER</p>
                </div>

                <!-- Bronze Medal -->
                <div class="text-center">
                    <p class="text-lg font-semibold">0</p>
                    <p class="text-sm font-bold text-orange-500">BRONZE</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
</script>

<style>
</style>
