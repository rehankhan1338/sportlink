<template>
    <div data-v-3b49203e="" class="sc-container">
        <div data-v-3b49203e="" class="content-wrapper">
            <div class="bg-image" data-v-3b49203e="">
                <picture data-v-3b49203e="" class="desktop-image">
                    <img loading="lazy"
                        :src="MatchBoard" width="520"
                        height="407" data-v-3b49203e="">
                </picture>
                <div class="background-shadow" data-v-3b49203e=""></div>
            </div>
            <picture data-v-3b49203e="">
                <img class="phone-image"
                    loading="lazy" :src="MatchPhone"
                    width="259" height="395" data-v-3b49203e="">
            </picture>
            <div data-v-3b49203e="" class="heading">
                <h2 data-v-3b49203e="" class="h1">When's my next match?</h2>
                <h3 data-v-3b49203e="">said no one ever again</h3>
            </div>
        </div>
    </div>
</template>

<script setup>
import MatchBoard from '@/assets/match-board.webp';
import MatchPhone from '@/assets/next-match-phone.webp';
</script>

<style scoped>
/* Mobile view (max-width: 768px) */
@media screen and (max-width: 768px) {
    /* Ensure that the content-wrapper is using flex layout */
    .content-wrapper[data-v-3b49203e] {
        display: flex !important; /* Flex container */
        flex-direction: column !important; /* Stack elements vertically */
        align-items: center;
    }

    /* Hide desktop image */
    .desktop-image {
        display: none !important;
    }

    /* Phone image should come first */
    .phone-image {
        order: -1 !important;
    }

    /* Ensure heading comes after the phone image */
    .heading[data-v-3b49203e] {
        order: 1 !important;
    }
}

/* Desktop view (min-width: 1000px) */
@media screen and (min-width: 1000px) {
    .content-wrapper[data-v-3b49203e] {
        display: flex;  /* Flex container for desktop */
        flex-direction: row; /* Row direction for desktop */
        align-items: center;
        margin-top: 6rem;
        margin-bottom: 8rem;
    }

    .bg-image[data-v-3b49203e] {
        display: block;
        max-width: 32.5rem;
        margin-bottom: 18rem;
        position: relative;
    }

    .desktop-image {
        display: block !important; /* Ensure desktop image is visible */
    }

    /* Heading adjustments */
    .heading[data-v-3b49203e] {
        margin-left: 10vw;
    }

    /* Shadow effect for the background image */
    .background-shadow[data-v-3b49203e] {
        display: block;
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        right: 0;
        z-index: 0;
        background: linear-gradient(0deg, rgba(0, 0, 0, .9) 0%, rgba(0, 0, 0, 0) 50%);
    }
}

.sc-container {
    margin-right: auto;
    margin-left: auto;
    padding-left: 32px;
    padding-right: 32px;
}

.sc-container div[data-v-3b49203e] {
    text-align: center;
}

picture {
    display: flex;
    justify-content: center;
}

.sc-container .h1[data-v-3b49203e] {
    margin-top: -4rem;
    margin-bottom: 1rem;
    max-width: 15rem;
    color: #f4f6fb;
}

/* Heading Styles */
h1, .h1 {
    font-size: 2rem;
    font-weight: 500;
    text-transform: none;
    margin: 0;
    color: var(--white-50);
}

h2 {
    font-size: 1.5rem;
    line-height: 1.2;
    font-weight: 500;
    text-transform: none;
    margin: 0;
    color: var(--white-50);
}

h3 {
    font-size: 1.3rem;
    font-weight: 500;
    text-transform: none;
    margin: 0;
    color: rgb(244 246 251 / 50%);
}


</style>