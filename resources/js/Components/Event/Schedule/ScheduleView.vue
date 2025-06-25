<template>
  <div class="bg-[#171719] text-white px-4 py-2 flex flex-col sm:flex-row items-center gap-4">
    <!-- Day and Date -->
    <div class="w-full sm:w-auto text-center sm:text-left">
      <span class="font-semibold block sm:inline">Day 2</span>
      <span class="text-sm text-gray-400 block sm:inline">29 Dec, 10:00</span>
    </div>

    <!-- Search Bar -->
    <div class="flex-grow w-full sm:w-auto">
      <input
        type="text"
        placeholder="Search..."
        class="w-full bg-gray-800 text-gray-200 px-4 py-2 rounded-md outline-none"
      />
    </div>

    <!-- Dropdowns (Academy/Club and Country) -->
    <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
      <select
        class="bg-gray-800 text-gray-200 px-4 py-2 rounded-md w-full sm:w-auto"
      >
        <option>Academy/Club</option>
      </select>
      <select
        class="bg-gray-800 text-gray-200 px-4 py-2 rounded-md w-full sm:w-auto"
      >
        <option>Select country</option>
      </select>
    </div>

    <!-- Filtered Count and Reset Button -->
    <div class="flex flex-col sm:flex-row items-center gap-2 w-full sm:w-auto">
      <span class="text-gray-400 text-sm sm:text-base">0 filtered:</span>
      <button
        class="bg-gray-700 hover:bg-gray-600 text-gray-200 px-4 py-2 rounded-md"
      >
        Reset
      </button>
    </div>

    <!-- Expand Icon -->
    <div class="w-full sm:w-auto flex justify-between sm:justify-center gap-2">
      <button
        class="bg-gray-800 hover:bg-gray-700 text-gray-400 px-2 py-1 rounded-md"
      >
        ⛶
      </button>

      <!-- Options Button -->
      <button
        class="bg-gray-800 hover:bg-gray-700 text-gray-400 px-2 py-1 rounded-md"
      >
        ⋮
      </button>
    </div>
  </div>
</template>
