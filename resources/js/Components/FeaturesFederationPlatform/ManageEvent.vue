<template>
    <section class="py-16">
      <div class="container mx-auto px-4">
        <div class="flex flex-wrap items-center">
  
          <!-- Left Section: Panels -->
          <div class="w-full lg:w-1/2">
            <div class="mb-8">
              <div class="bg-gray-900 text-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold mb-4">Manage Events</h3>
                <ul class="space-y-2">
                  <li class="flex items-start">
                    <span class="text-green-500 mr-2">✔</span>
                    <p>All events arranged by your company or federation in one place</p>
                  </li>
                  <li class="flex items-start">
                    <span class="text-green-500 mr-2">✔</span>
                    <p>Ranking for your academies and athletes</p>
                  </li>
                </ul>
              </div>
            </div>
  
            <div class="mb-8">
              <div class="bg-gray-900 text-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold mb-4">Manage Academies</h3>
                <ul class="space-y-2">
                  <li class="flex items-start">
                    <span class="text-green-500 mr-2">✔</span>
                    <p>Manage all your member academies from one interface</p>
                  </li>
                  <li class="flex items-start">
                    <span class="text-green-500 mr-2">✔</span>
                    <p>Review and approve applications to your federation</p>
                  </li>
                  <li class="flex items-start">
                    <span class="text-green-500 mr-2">✔</span>
                    <p>Manage payments for your memberships</p>
                  </li>
                </ul>
              </div>
            </div>
  
            <div>
              <div class="bg-gray-900 text-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold mb-4">Manage Athletes</h3>
                <ul class="space-y-2">
                  <li class="flex items-start">
                    <span class="text-green-500 mr-2">✔</span>
                    <p>Manage and approve athletes to be a part of each club of your federation</p>
                  </li>
                  <li class="flex items-start">
                    <span class="text-green-500 mr-2">✔</span>
                    <p>Review and approve belts and promotions for athletes</p>
                  </li>
                </ul>
              </div>
            </div>
          </div>
  
          <!-- Right Section: Image -->
          <div class="w-full lg:w-1/2 flex justify-center mt-10 lg:mt-0">
            <img
              src="/build/webpack/img/federation-platform/federation-platform-map.b41c36141152ad7984c32c4106bbdde9.png"
              alt="Federation Platform Map"
              class="max-w-full h-auto rounded-lg shadow-lg"
            />
          </div>
  
        </div>
      </div>
    </section>
  </template>
  
  <script setup>
  </script>
  
  <style scoped>
  /* Adjusting paddings and layout for better responsiveness */
  .container {
    max-width: 1200px;
  }
  @media (max-width: 1024px) {
    .py-16 {
      padding: 2rem 0;
    }
  }
  @media (max-width: 768px) {
    .flex-wrap {
      flex-direction: column;
    }
    .w-full {
      width: 100%;
    }
  }
  </style>
  