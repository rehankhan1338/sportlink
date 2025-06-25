<template>
    <section class="hero-lines bg-dark py-12">
        <div class="container mx-auto px-4">
            <div class="row flex flex-col-reverse md:flex-row items-center">

                <!-- Text Section -->
                <div class="col-sm-8 col-md-6 text-white">
                    <h1 class="text-3xl md:text-5xl font-bold mb-4">SportLink <br> Federation Platform</h1>
                    <p class="preamble text-lg md:text-xl leading-relaxed">
                        For federations and companies running several tournaments we can provide you with
                        <a href="#" class="text-blue-400 underline"><strong>your-name</strong>.Sportlink.com</a>. On this customizable page you can gather
                        all administration for your academies and tournaments in one place.
                    </p>
                </div>

                <!-- Image Section -->
                <div class="col-sm-4 md:col-md-offset-2 hidden md:block">
                    <img 
                        class="max-w-full h-auto rounded-lg" 
                        :src="Hero" 
                        alt="Federation Platform"
                    >
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import Hero from '@/assets/federation.webp';
</script>

<style scoped>
.hero-lines {
    background-color: #1a202c; /* Dark background */
    color: white; /* Ensures text is white */
}

h1 {
    font-family: 'Arial', sans-serif;
    margin-bottom: 1rem;
}

.preamble a {
    color: #63b3ed; /* Tailwind's blue-400 */
    text-decoration: underline;
}

.preamble a:hover {
    color: #4299e1; /* Tailwind's blue-500 */
}

img {
    border-radius: 0.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

@media (max-width: 768px) {
    .row {
        flex-direction: column-reverse;
    }

    .col-sm-4 {
        display: none; /* Hide image on small screens */
    }
}
</style>
