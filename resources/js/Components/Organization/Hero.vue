<template>

    <div data-v-32346a9c="" class="sc-container relative overflow-hidden" bis_skin_checked="1">
        <picture data-v-32346a9c="">
            <source data-v-32346a9c="" srcset="
                   https://download.smoothcomp.com/features/misc/spotlightsdark@2x.webp,
                    https://download.smoothcomp.com/features/scoreboard/product-hero@1x.webp 1x
                " type="image/webp">
            <source data-v-32346a9c="" srcset="
                    https://download.smoothcomp.com/features/scoreboard/product-hero@2x.png 2x,
                    https://download.smoothcomp.com/features/scoreboard/product-hero@1x.png 1x
                " type="image/jpg"><img data-v-32346a9c="" loading="lazy"
                src="https://download.smoothcomp.com/features/scoreboard/product-hero@1x.png" class="hero-image">
        </picture>
        <h1 data-v-32346a9c="" class="absolute w-full h-full top-0 flex items-center justify-center"><strong data-v-32346a9c="">Exciting times! <br />
            Let's get <span class="yellow-text">started</span> on <br/> SportLink</strong></h1>
       
    </div>
</template>

<script setup>

</script>

<style scoped>
.yellow-text{
    color:yellow;
}
.sc-container {
    width: 100%;
    max-width: 1300px;
    margin: 0 auto;
    padding: 16px;
}

picture {
    display: flex;
    justify-content: center;
}

.hero-image[data-v-32346a9c] {
    margin-bottom: -4rem;
}

img {
    max-width: 100%;
    vertical-align: middle;
    border: 0;
}

h1,
.h1 {
    font-size: 2rem;
    font-weight: 500;
    text-transform: none;
    margin: 0;
    color: rgba(244, 246, 251, 0.5);
    text-align: center;
}

strong {
    color: #f4f6fb;
    font-weight: normal;
}

.button-container[data-v-453f1426] {
    display: flex;
    flex-direction: column; /* Stack buttons in a column */
    justify-content: center; /* Center vertically */
    align-items: center; /* Center horizontally */
    margin-top: 2rem;
    gap: 1rem;
}

.button-container a[data-v-453f1426] {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.06rem;
    padding: 0.56rem 1.25rem;
    border-radius: 6.25rem;
    cursor: pointer;
    width: 100%; /* Ensure buttons are responsive */
    max-width: 300px; /* Optional: Limit button width */
    text-align: center;
}

.sc-btn-secondary {
    background: rgba(244, 246, 251, 0.1);
    color: rgba(244, 246, 251, 0.9);
    border: none;
    -webkit-backdrop-filter: blur(0.5rem);
    backdrop-filter: blur(0.5rem);
}

.sc-btn-primary {
    background: #2c3edc;
    color: #f4f6fb;
    border: none;
}

@media screen and (min-width: 768px) {
    .button-container[data-v-453f1426] {
        flex-direction: row; /* Align buttons horizontally on larger screens */
    }

    .button-container a[data-v-453f1426]:not(:last-child) {
        margin-right: 0.5rem;
    }
}

</style>