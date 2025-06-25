<template>
  <section class="hero p-6 flex items-center"
    :style="{ backgroundImage: `url(${wrestling})`, backgroundSize: 'cover', backgroundPosition: 'center' }">
    <div class="container mx-auto px-4">
      <h1 class="text-2xl font-bold uppercase text-white mb-8">
        Athletes
        <span class="mute">Community</span>
      </h1>
      <div class="flex justify-between items-center flex-wrap gap-4">
        <!-- Form Section -->
        <form method="GET" action="https://smoothcomp.com/en/affiliation" class="flex flex-wrap gap-3 w-full">
          <!-- Search Input -->
          <input class="form-control p-2 border border-gray-300 rounded-lg w-full md:w-1/5" placeholder="Search..."
            name="search" type="text" />

          <!-- Country Dropdown -->
          <select name="country" class="form-control p-2 border border-gray-300 rounded-lg w-full md:w-1/5">
            <option value="" disabled selected>Select Country</option>
            <option value="USA">USA</option>
            <option value="Brazil">Brazil</option>
            <option value="UK">UK</option>
            <option value="Canada">Canada</option>
          </select>

          <!-- Continent Dropdown -->
          <select name="continent" class="form-control p-2 border border-gray-300 rounded-lg w-full md:w-1/5">
            <option value="" disabled selected>Select Continent</option>
            <option value="North America">North America</option>
            <option value="South America">South America</option>
            <option value="Europe">Europe</option>
            <option value="Asia">Asia</option>
            <option value="Africa">Africa</option>
            <option value="Oceania">Oceania</option>
          </select>

          <!-- Academy Dropdown -->
          <select name="academy" class="form-control p-2 border border-gray-300 rounded-lg w-full md:w-1/5">
            <option value="" disabled selected>Select Academy</option>
            <option value="Gracie Barra">Gracie Barra</option>
            <option value="Atos">Atos</option>
            <option value="Checkmat">Checkmat</option>
            <option value="Alliance">Alliance</option>
          </select>

          <!-- Affiliation Dropdown -->
          <select name="affiliation" class="form-control p-2 border border-gray-300 rounded-lg w-full md:w-1/5">
            <option value="" disabled selected>Select Affiliation</option>
            <option value="Affiliation 1">Affiliation 1</option>
            <option value="Affiliation 2">Affiliation 2</option>
            <option value="Affiliation 3">Affiliation 3</option>
          </select>

          <!-- Gender Dropdown -->
          <select name="gender" class="form-control p-2 border border-gray-300 rounded-lg w-full md:w-1/5">
            <option value="" disabled selected>Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
          </select>

          <!-- Buttons Section -->
          <div class="flex gap-3 mt-4 w-full">
            <button type="submit" class="btn btn-secondary px-4 py-2 rounded-lg text-white hover:bg-blue-600 transition"
              style="background-color: #3b82f6 !important;">
              Filter
            </button>
            <button type="button"
              class="btn btn-secondary px-4 py-2 rounded-lg text-white hover:bg-green-600 transition"
              style="background-color: #10b981 !important;">
              Share Toplist
            </button>
          </div>
        </form>
      </div>


    </div>
  </section>
</template>

<script setup>
import wrestling from "@/assets/affiliation.jpg";
</script>

<style scoped>
.hero {
  height: 24rem;
  /* 96 Tailwind size */
}

.mute {
  opacity: 0.4;
}
</style>