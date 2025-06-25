<template>
  <section class="club-page club-header">
    <!-- Cover Image -->
    <div class="text-center bg-brand">
      <img
        class="w-full object-cover"
        alt=""
        src="https://smoothcomp.com/pictures/t/1555357-k0oo/santa-maria-judo.png"
      />
    </div>

    <!-- Club Info -->
    <div class="bg-brand py-4 md:py-8 relative">
      <div class="container mx-auto px-4">
        <div class="flex flex-wrap items-center">
          <!-- Logo -->
          <div
            class="w-full sm:w-1/3 flex justify-center mb-4 sm:mb-0 relative"
            style="margin-top: -100px;"
          >
            <div class="logo-wrapper">
              <img
                class="w-40 h-40 sm:w-48 sm:h-48 object-cover rounded-full border-4 border-white"
                alt="Santa Maria Judô"
                src="https://smoothcomp.com/pictures/t/1555358-inws/santa-maria-judo.png"
              />
            </div>
          </div>
          <!-- Club Details -->
          <div class="w-full sm:w-2/3 text-center sm:text-left">
            <h1
              class="text-2xl md:text-3xl text-white font-bold mb-2 sm:ml-4"
            >
              <a href="https://smoothcomp.com/en/club/37263" class="">
                Santa Maria Judô
              </a>
            </h1>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup></script>

<style scoped>
/* Additional styling to handle responsiveness */
.logo-wrapper img {
  transition: transform 0.3s ease;
}

.logo-wrapper img:hover {
  transform: scale(1.1); /* Slight zoom effect on hover */
}

@media (min-width: 640px) {
  h1 {
    margin-left: 0; /* Reset margin on larger screens */
  }
}
</style>
