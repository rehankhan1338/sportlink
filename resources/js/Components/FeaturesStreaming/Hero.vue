<template>

    <div data-v-2b31bd26="" class="sc-container-fullscreen" bis_skin_checked="1">
        <div data-v-2b31bd26="" class="sc-container-large" bis_skin_checked="1">
            <div class="heading-container" data-v-2b31bd26="" bis_skin_checked="1">
                <div class="heading-icon" data-v-2b31bd26="" bis_skin_checked="1">
                    <div class="sc-badge" data-v-2b31bd26="" bis_skin_checked="1"><img
                            src="/build/webpack/b7322580487876919330c06826744493.svg" data-v-2b31bd26=""></div>
                    <p data-v-2b31bd26="">Livestream</p>
                </div>
                <h1 data-v-2b31bd26=""><span class="mute-less" data-v-2b31bd26="">Share the moments.</span><br
                        data-v-2b31bd26="">Stream and record your event. </h1>
            </div>
            <div data-v-2b31bd26="" class="button-container" bis_skin_checked="1"><a data-v-2b31bd26=""
                    href="https://smoothcomp.com/en/organizer/create" class="sc-btn-primary sc-btn-medium">Get
                    Started</a></div>
        </div>
        <div data-v-2b31bd26="" bis_skin_checked="1">
            <div class="hero-img" data-v-2b31bd26="" bis_skin_checked="1">
                <picture data-v-2b31bd26="">
                    <source srcset="
                            https://download.smoothcomp.com/features/streaming/hero-cover@2x.webp 2x,
                            https://download.smoothcomp.com/features/streaming/hero-cover@1x.webp 1x
                        " type="image/webp" data-v-2b31bd26="">
                    <source srcset="
                            https://download.smoothcomp.com/features/streaming/hero-cover@2x.png 2x,
                            https://download.smoothcomp.com/features/streaming/hero-cover@1x.png 1x
                        " type="image/jpg" data-v-2b31bd26=""><img loading="lazy"
                        src="https://download.smoothcomp.com/features/streaming/hero-cover@1x.png" data-v-2b31bd26="">
                </picture>
                <div class="hero-gradient" data-v-2b31bd26="" bis_skin_checked="1"></div>
            </div>
        </div>
    </div>
</template>

<script setup>

</script>

<style scoped>

.sc-container-fullscreen {
    width: 100%;
}
.sc-container-large[data-v-2b31bd26] {
    display: flex;
    flex-direction: column;
    align-items: center;
    /* margin-top: 5rem; */
}

.sc-container-large {
    width: 1700px;
}
.sc-container-large, .sc-container {
    max-width: 100%;
    margin-right: auto;
    margin-left: auto;
    padding-left: 32px;
    padding-right: 32px;
}

.heading-container[data-v-2b31bd26] {
    display: flex;
    flex-direction: column;
    text-align: center;
    align-items: center;
    margin-top: 5rem;
    margin-bottom: 2rem;
}
.heading-icon[data-v-2b31bd26] {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.sc-badge {
    background: linear-gradient(0deg, rgba(255, 255, 255, .05) 0%, rgba(255, 255, 255, .15) 100%);
    border-radius: 0.5rem;
    height: 36px;
    width: 36px;
    display: flex;
    justify-content: center;
    align-items: center;
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

.sc-container-large h1[data-v-2b31bd26], .sc-container-large p[data-v-2b31bd26] {
    color: rgb(244 246 251 / 90%);
}

@media screen and (min-width: 1000px) {
    p {
        font-size: 1.1rem;
    }
}
p {
    /* font-size: 1rem; */
    font-weight: 500;
    margin: 0;
    color: var(--white-50);
}

.sc-container-large h1[data-v-2b31bd26], .sc-container-large p[data-v-2b31bd26] {
    color: rgb(244 246 251 / 90%);
}
@media screen and (min-width: 1000px) {
    h1, .h1 {
        font-size: 2.9rem;
    }
}
h1, .h1 {
    /* font-size: 2.2rem; */
    font-weight: 500;
    text-transform: none;
    margin: 0;
    color: var(--white-50);
}
.muted-less, .mute-less {
    opacity: 0.6;
}

.button-container[data-v-2b31bd26] {
    display: flex
;
    flex-direction: column;
}

.sc-btn-medium {
    font-size: 1.06rem;
    padding: 0.56rem 1.25rem;
    border-radius: 6.25rem;
    cursor: pointer;
}

.sc-btn-primary {
    background: #2C3EDC;
    color: #f4f6fb;
    border: none;
    display: flex
;
    align-items: center;
    justify-content: center;
}
.hero-img[data-v-2b31bd26] {
    position: relative;
    width: 100%;
    overflow: hidden;
}
/* @media screen and (min-width: 1000px) { */
    .hero-img picture[data-v-2b31bd26] {
        width: 100% !important;
        margin-left: unset;
    }
/* } */

.hero-img picture[data-v-2b31bd26] {
    width: 160%;
    max-width: unset;
    /* margin-left: -30%; */
}
picture {
    display: flex
;
    justify-content: center;
}
.hero-gradient[data-v-2b31bd26] {
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
    background: linear-gradient(180deg, rgba(0, 0, 0, 0.85) 0%, rgba(0, 0, 0, 0.15) 20%, rgba(0, 0, 0, 0) 50%);
}

</style>