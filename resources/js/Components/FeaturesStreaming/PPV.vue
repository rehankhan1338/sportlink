<template>
    <div class="container mx-auto flex flex-col md:flex-row items-center gap-6 p-6">
        <!-- Image Section -->
        <picture class="flex-shrink-0 w-full md:w-1/2">
            <source 
                srcset="
                    https://download.smoothcomp.com/features/streaming/ppv-fighter@2x.webp 2x,
                    https://download.smoothcomp.com/features/streaming/ppv-fighter@1x.webp 1x
                " 
                type="image/webp"
            />
            <source 
                srcset="
                    https://download.smoothcomp.com/features/streaming/ppv-fighter@2x.jpg 2x,
                    https://download.smoothcomp.com/features/streaming/ppv-fighter@1x.jpg 1x
                " 
                type="image/jpg"
            />
            <img 
                loading="lazy" 
                class="rounded-lg w-full object-cover" 
                src="https://download.smoothcomp.com/features/streaming/ppv-fighter@1x.jpg" 
                alt="Pay per view"
            />
        </picture>

        <!-- Text Section -->
        <div class="text-section w-full md:w-1/2 text-center md:text-left">
            <h2 class="text-2xl md:text-3xl font-bold text-white mb-4">Get paid with Pay per view (PPV)</h2>
            <div class="info-text space-y-4 text-white">
                <p>
                    If you want to livestream your event and monetize it as a pay-per-view option, we have a solution available through our partner Staylive and their streaming platform.
                </p>
                <p>
                    Build your own platform and stream or upload your videos, sell subscriptions and PPV´s.
                </p>
            </div>
            <a 
                href="/support" 
                class="inline-block mt-6 text-blue-600 hover:text-blue-800 font-semibold transition-colors"
            >
                Contact us to learn more &nbsp; 
                <span class="icon icon-chevron-right">→</span>
            </a>
        </div>
    </div>
</template>

<script setup>
</script>

<style scoped></style>
