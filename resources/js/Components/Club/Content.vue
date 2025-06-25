<template>
    <section class="club-page bg-[#141414] py-8">
      <!-- Container -->
      <div class="container mx-auto px-4">
        <div class="flex flex-wrap">
          <!-- Sidebar -->
          <aside class="w-full lg:w-1/4 mb-8 lg:mb-0">
            <!-- Stats -->
            <div class=" p-4 rounded shadow">
              <div class="mb-4 flex items-center text-gray-600">
                <span class="material-icons mr-2">place</span>
                <span class="text-white">Червоноград, Ukraine</span>
              </div>
  
              <!-- Uncomment for members -->
              <!--
              <div class="flex items-center text-gray-600">
                <span class="material-icons mr-2">group</span>
                <span>9 members</span>
              </div>
              -->
            </div>
  
            <!-- Navigation -->
            <ul class="mt-8 space-y-2">
              <li>
                <a href="" class="block py-2 px-4 bg-blue-500 text-white rounded">Home</a>
              </li>
              <li>
                <a href="" class="block py-2 px-4 text-white rounded hover:bg-gray-300 hover:text-black">Statistics</a>
              </li>
            </ul>
  
            <!-- Join Button -->
            <div class="mt-8">
              <button class="w-full py-2 px-4 bg-blue-500 text-white rounded hover:bg-blue-600">Join academy</button>
            </div>
          </aside>
  
          <!-- Main Content -->
          <main class="w-full lg:w-3/4 lg:pl-8">
            <!-- Club Map -->
            <div class="mb-8">
              <div id="clubMap" class="w-full h-72 bg-gray-200">Map Placeholder</div>
            </div>
  
            <!-- Club Info -->
            <div class="bg-white p-6 rounded shadow mb-8">
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                  <p class="text-gray-600">
                    <span class="font-semibold">Location:</span><br>
                    Богдана Хмельницького 85а, Червоноград, Ukraine
                  </p>
                </div>
  
                <div>
                  <p class="text-gray-600">
                    <span class="font-semibold">Phone:</span><br>
                    <a href="tel:+380639587188" class="text-blue-500 hover:underline">+380 63 958 7188</a>
                  </p>
                </div>
  
                <div>
                  <p class="text-gray-600">
                    <span class="font-semibold">Person in charge:</span><br>
                    Олександр
                  </p>
                </div>
              </div>
            </div>
  
            <!-- Contact Persons -->
            <div class="bg-white p-6 rounded shadow mb-8">
              <h3 class="text-lg font-semibold mb-4">Contact Persons</h3>
              <table class="w-full ">
                <tbody>
                  <tr>
                    <td class="p-2">Oleksandr Karabin</td>
                    <td class="p-2 w-24">
                      <span class="text-sm bg-blue-500 text-white px-2 py-1 rounded">Manager</span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
  
         
          </main>
        </div>
      </div>
    </section>
  </template>
  
  <script setup></script>
  
  <style scoped>
/* Import Material Icons */
@import url("https://fonts.googleapis.com/icon?family=Material+Icons");
</style>
  