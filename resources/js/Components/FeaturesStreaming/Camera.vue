<template>
    <div class="container mx-auto p-6 mt-16">
        <!-- Top Section -->
        <div class="top-container flex flex-col md:flex-row-reverse items-center gap-6">
            <!-- Image Section -->
            <picture class="flex-shrink-0 w-full md:w-1/2">
                <source 
                    srcset="
                        https://download.smoothcomp.com/features/streaming/ppv-streaming@2x.webp 2x,
                        https://download.smoothcomp.com/features/streaming/ppv-streaming@1x.webp 1x
                    " 
                    type="image/webp"
                />
                <source 
                    srcset="
                        https://download.smoothcomp.com/features/streaming/ppv-streaming@2x.jpg 2x,
                        https://download.smoothcomp.com/features/streaming/ppv-streaming@1x.jpg 1x
                    " 
                    type="image/jpg"
                />
                <img 
                    loading="lazy" 
                    class="main-img rounded-lg w-full object-cover" 
                    src="https://download.smoothcomp.com/features/streaming/ppv-streaming@1x.jpg" 
                    alt="Streaming setup"
                />
            </picture>

            <!-- Text Section -->
            <div class="title-section w-full md:w-1/2 text-center md:text-left">
                <h2 class="text-2xl md:text-3xl font-bold text-white">
                    <span>Going pro?</span>
                    <span class="text-gray-600 font-normal">Run a multi camera setup with scoreboard overlays.</span>
                </h2>
                <p class="text-white mt-4">
                    We got you covered. Our solution supports most streaming softwares such as Ecamm, OBS, and vMix. Both for PC and Mac.
                </p>

                <!-- Logos Section -->
                <div class="logo-section flex justify-center md:justify-start gap-4 mt-6">
                    <img 
                        loading="lazy" 
                        class="h-10" 
                        src="https://download.smoothcomp.com/features/streaming/open-broadcaster-logo.svg" 
                        alt="OBS Logo"
                    />
                    <img 
                        loading="lazy" 
                        class="h-10" 
                        src="https://download.smoothcomp.com/features/streaming/ecamm-logo.svg" 
                        alt="Ecamm Logo"
                    />
                    <img 
                        loading="lazy" 
                        class="h-10" 
                        src="https://download.smoothcomp.com/features/streaming/vmix-logo.svg" 
                        alt="vMix Logo"
                    />
                </div>
            </div>
        </div>

        <!-- Bottom Section -->
        <div class="bottom-container grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
            <!-- Item 1 -->
            <div class="bottom-item flex flex-col items-center text-center">
                <img 
                    loading="lazy" 
                    class="h-16 mb-4" 
                    src="/build/webpack/b7322580487876919330c06826744493.svg" 
                    alt="Stream anywhere"
                />
                <p class="text-white">
                    <span class="font-bold text-white">Stream anywhere.</span> 
                    Support both RTMP(S) and SRT. Stream to YouTube, Vimeo, Rokfin, Flo, and much more.
                </p>
            </div>

            <!-- Item 2 -->
            <div class="bottom-item flex flex-col items-center text-center">
                <img 
                    loading="lazy" 
                    class="h-16 mb-4" 
                    src="/build/webpack/1308726299f6c84c5b27c3aa1a5d464d.svg" 
                    alt="Record and store"
                />
                <p class="text-white">
                    <span class="font-bold text-white">Record and store.</span> 
                    If you don’t have enough bandwidth to stream the whole event you can always record it and upload later.
                </p>
            </div>

            <!-- Item 3 -->
            <div class="bottom-item flex flex-col items-center text-center">
                <img 
                    loading="lazy" 
                    class="h-16 mb-4" 
                    src="/build/webpack/d74607fe5b4d21377c42415e9f4edebf.svg" 
                    alt="Overlay"
                />
                <p class="text-white">
                    <span class="font-bold text-white">Overlay.</span> 
                    Automatically overlays the scoreboard.
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
</script>

<style scoped>
/* Optional custom styles if Tailwind is unavailable */
.main-img {
    max-height: 400px;
    object-fit: cover;
}

@media (min-width: 768px) {
    .top-container {
        align-items: flex-start;
    }
}
</style>
