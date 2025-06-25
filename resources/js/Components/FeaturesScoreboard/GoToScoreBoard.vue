<template>

    <div data-v-8a64bdee="" class="sc-container" bis_skin_checked="1">
        <picture data-v-8a64bdee="">
            <source srcset="
                    https://download.smoothcomp.com/features/scoreboard/live-image@2x.webp 2x,
                    https://download.smoothcomp.com/features/scoreboard/live-image@1x.webp 1x
                " type="image/webp" data-v-8a64bdee="">
            <source srcset="
                    https://download.smoothcomp.com/features/scoreboard/live-image@2x.png 2x,
                    https://download.smoothcomp.com/features/scoreboard/live-image@1x.png 1x
                " type="image/jpg" data-v-8a64bdee=""><img loading="lazy"
                src="https://download.smoothcomp.com/features/scoreboard/live-image@1x.png" data-v-8a64bdee="">
        </picture>
        <div class="text-container" data-v-8a64bdee="" bis_skin_checked="1">
            <h2 data-v-8a64bdee=""><strong data-v-8a64bdee="">Powered by the bracket system – and you</strong></h2>
            <p class="paragraph-large" data-v-8a64bdee=""> The digital scoreboard instantly connects with your
                competition at Smoothcomp. It’s as easy as clicking “Go to scoreboard” in the bracket. When the match is
                done, click “Go to next match”. </p>
        </div>
    </div>
</template>

<script setup>

</script>

<style scoped>

@media screen and (min-width: 1100px) {
    .sc-container[data-v-8a64bdee] {
        margin-top: 6rem;
        flex-direction: row-reverse;
        justify-content: space-between;
    }
}

.sc-container[data-v-8a64bdee] {
    display: flex
;
    align-items: center;
    /* flex-direction: column; */
}
.sc-container {
    width: 1300px;
}
.sc-container-large, .sc-container {
    max-width: 100%;
    margin-right: auto;
    margin-left: auto;
    padding-left: 32px;
    padding-right: 32px;
}
picture {
    display: flex
;
    justify-content: center;
}
img[data-v-8a64bdee] {
    width: 100%;
    max-width: 640px;
}

img {
    max-width: 100%;
}
img {
    vertical-align: middle;
}
img {
    border: 0;
}
/* @media screen and (min-width: 1100px) { */
    .text-container[data-v-8a64bdee] {
        text-align: left;
        align-items: flex-start;
    }
/* } */

/* .text-container[data-v-8a64bdee] {
    text-align: center;
    display: flex
;
    flex-direction: column;
    align-items: center;
} */
@media screen and (min-width: 1100px) {
    h2[data-v-8a64bdee] {
        margin-bottom: 2rem;
    }
}

h2[data-v-8a64bdee] {
    max-width: 33rem;
    margin-bottom: 1rem;
}
@media screen and (min-width: 1000px) {
    h2 {
        font-size: 2.2rem;
    }
}
h2 {
    /* font-size: 1.5rem; */
    line-height: 1.2;
    /* font-weight: 500; */
    text-transform: none;
    margin: 0;
    color: #f4f6fb;
}
strong {
    color: var(--white);
    font-weight: normal;
}

.paragraph-large[data-v-8a64bdee] {
    max-width: 29rem;
}

/* @media screen and (min-width: 1000px) { */
    .paragraph-large {
        font-size: 1.19rem;
    }
/* } */
/* .paragraph-large {
    font-size: 1rem;
} */
@media screen and (min-width: 1000px) {
    p {
        font-size: 1.1rem;
    }
}
p {
    font-size: 1rem;
    font-weight: 500;
    margin: 0;
    color: rgb(244 246 251 / 50%);
}

@media screen and (max-width: 1099px) {
    .sc-container[data-v-8a64bdee] {
        flex-direction: column; /* Stack items vertically */
    }

    picture[data-v-8a64bdee] {
        order: 1; /* Ensure image comes first */
    }

    .text-container[data-v-8a64bdee] {
        order: 2; /* Ensure text comes after image */
        text-align: center; /* Center text for better mobile aesthetics */
        margin-top: 1rem;
    }
}
</style>