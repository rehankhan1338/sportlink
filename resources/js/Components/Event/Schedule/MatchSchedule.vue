<template>
  <div class="text-white p-4">
    <!-- Scrollable Container -->
    <div class="flex gap-4 overflow-x-auto">
      <!-- Card 1 -->
      <div class="bg-[#171719] rounded-lg shadow-md flex-shrink-0 w-[300px]">
        <!-- Header -->
        <div class="bg-[#171719] px-4 py-2 flex justify-between items-center rounded-t-lg">
          <span class="font-semibold">Mat 1</span>
          <div class="flex gap-2">
            <button
              class="bg-gray-600 hover:bg-gray-500 text-white p-2 rounded-full"
            >
              📌
            </button>
            <button
              class="bg-gray-600 hover:bg-gray-500 text-white p-2 rounded-full"
            >
              👥
            </button>
          </div>
        </div>

        <!-- Content -->
        <div class="h-48 bg-gray-900"></div>

        <!-- Footer -->
        <div class="bg-[#171719] px-4 py-2 flex justify-between text-sm rounded-b-lg">
          <div>
            <p class="text-gray-400">Matches</p>
            <p class="font-semibold">35</p>
          </div>
          <div>
            <p class="text-gray-400">Total est.</p>
            <p class="font-semibold">0h 0m</p>
          </div>
          <div>
            <p class="text-gray-400">Eta end</p>
            <p class="font-semibold">13:26</p>
          </div>
        </div>
      </div>

      <!-- Card 2 -->
      <div class="bg-[#171719] rounded-lg shadow-md flex-shrink-0 w-[300px]">
        <!-- Header -->
        <div class="bg-[#171719] px-4 py-2 flex justify-between items-center rounded-t-lg">
          <span class="font-semibold">Mat 2</span>
          <div class="flex gap-2">
            <button
              class="bg-gray-600 hover:bg-gray-500 text-white p-2 rounded-full"
            >
              📌
            </button>
            <button
              class="bg-gray-600 hover:bg-gray-500 text-white p-2 rounded-full"
            >
              👥
            </button>
            <button
              class="bg-gray-600 hover:bg-gray-500 text-white p-2 rounded-full"
            >
              ➡️
            </button>
          </div>
        </div>

        <!-- Content -->
        <div class="h-48 bg-gray-900"></div>

        <!-- Footer -->
        <div class="bg-[#171719] px-4 py-2 flex justify-between text-sm rounded-b-lg">
          <div>
            <p class="text-gray-400">Matches</p>
            <p class="font-semibold">57</p>
          </div>
          <div>
            <p class="text-gray-400">Total est.</p>
            <p class="font-semibold">0h 0m</p>
          </div>
          <div>
            <p class="text-gray-400">Eta end</p>
            <p class="font-semibold">15:53</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
