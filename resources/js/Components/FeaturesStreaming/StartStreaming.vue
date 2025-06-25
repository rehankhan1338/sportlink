<template>
    <div class="container mx-auto mt-40 px-8">
      <div class="flex flex-wrap justify-center items-center gap-8">
        <div class="w-full text-center">
          <h3 class="text-white text-2xl md:text-3xl font-medium">Start streaming in seconds</h3>
        </div>
  
        <div class="w-full md:w-1/3 flex flex-col items-center gap-4">
          <div class="card bg-gray-100 rounded-lg shadow-md p-4">
            <picture>
              <source
                srcset="https://download.smoothcomp.com/features/streaming/add-stream@2x.webp 2x, https://download.smoothcomp.com/features/streaming/add-stream@1x.webp 1x"
                type="image/webp"
              />
              <source
                srcset="https://download.smoothcomp.com/features/streaming/add-stream@2x.png 2x, https://download.smoothcomp.com/features/streaming/add-stream@1x.png 1x"
                type="image/jpg"
              />
              <img
                loading="lazy"
                class="w-full"
                src="https://download.smoothcomp.com/features/streaming/add-stream@1x.png"
                alt="Create a new stream"
              />
            </picture>
          </div>
          <p class="text-white text-base md:text-lg">
            <span class="text-yellow-500 font-bold">1</span> Create a new stream
          </p>
        </div>
  
        <div class="w-full md:w-1/3 flex flex-col items-center gap-4">
          <div class="card bg-gray-100 rounded-lg shadow-md p-4">
            <picture>
              <source
                srcset="https://download.smoothcomp.com/features/streaming/qr-scan@2x.webp 2x, https://download.smoothcomp.com/features/streaming/qr-scan@1x.webp 1x"
                type="image/webp"
              />
              <source
                srcset="https://download.smoothcomp.com/features/streaming/qr-scan@2x.png 2x, https://download.smoothcomp.com/features/streaming/qr-scan@1x.png 1x"
                type="image/jpg"
              />
              <img
                loading="lazy"
                class="w-full"
                src="https://download.smoothcomp.com/features/streaming/qr-scan@1x.png"
                alt="Scan the QR"
              />
            </picture>
          </div>
          <p class="text-white text-base md:text-lg">
            <span class="text-yellow-500 font-bold">2</span> Scan the QR
          </p>
        </div>
  
        <div class="w-full md:w-1/3 flex flex-col items-center gap-4">
          <div class="card bg-gray-100 rounded-lg shadow-md p-4">
            <picture>
              <source
                srcset="https://download.smoothcomp.com/features/streaming/phone-rig@2x.webp 2x, https://download.smoothcomp.com/features/streaming/phone-rig@1x.webp 1x"
                type="image/webp"
              />
              <source
                srcset="https://download.smoothcomp.com/features/streaming/phone-rig@2x.png 2x, https://download.smoothcomp.com/features/streaming/phone-rig@1x.png 1x"
                type="image/jpg"
              />
              <img
                loading="lazy"
                class="w-full"
                src="https://download.smoothcomp.com/features/streaming/phone-rig@1x.png"
                alt="Start streaming"
              />
            </picture>
          </div>
          <p class="text-white text-base md:text-lg">
            <span class="text-yellow-500 font-bold">3</span> Start streaming
          </p>
        </div>
      </div>
    </div>
  </template>
  
  <script setup></script>
  
  <style scoped>
  .card {
    transition: transform 0.3s ease;
  }
  .card:hover {
    transform: translateY(-10px);
  }
  </style>
  