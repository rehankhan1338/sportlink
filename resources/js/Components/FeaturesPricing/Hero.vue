<template>
    <div class="relative w-full min-h-[50vh] pb-[10vh] flex flex-col justify-end bg-cover bg-center" :style="{ backgroundImage: `url(${Hero})` }">
      <div class="absolute inset-0 bg-gradient-to-b from-black via-transparent to-black opacity-50"></div>
      <div class="container mx-auto px-8 flex flex-col items-center justify-around pt-[7rem] pb-[4rem]">
        <div class="flex flex-col items-center text-center max-w-xl mb-10">
          <h1 class="text-2xl md:text-3xl font-medium text-white mb-4">
            <strong>Get the <span>#1</span> tournament software for combat sports</strong>
          </h1>
          <a href="#" class="bg-white text-black py-3 px-6 rounded-full text-lg font-semibold">
            Features & Pricing
          </a>
        </div>
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
          <h2 class="text-xl font-semibold text-gray-800 mb-6">Create your first event today 👊</h2>
          <form action="https://smoothcomp.com/en/organizer/create" method="get" class="flex flex-col">
            <input
              type="text"
              name="organizer"
              placeholder="Name of organization"
              class="p-3 mb-4 border border-gray-300 rounded-lg text-gray-700"
            />
            <button type="submit" class="bg-blue-600 text-white py-3 rounded-lg text-lg font-semibold">
              Become an Organizer
            </button>
          </form>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import Hero from '@/assets/hero.webp';
  </script>
  
  <style scoped>
  /* Scoped styling (you can delete if not needed) */
  </style>
  