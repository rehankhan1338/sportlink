<template>

    <div data-v-2edd5f43="" class="sc-container in-scoreboard-page" bis_skin_checked="1">
        <div data-v-2edd5f43="" bis_skin_checked="1"
            style="display: flex; flex-direction: column; align-items: center; width: 100%;">
            <div data-v-2edd5f43="" class="video-container" bis_skin_checked="1">
                <div data-v-2edd5f43="" class="tv-screen tv-visible tv-overlay-off" bis_skin_checked="1"><video
                        data-v-2edd5f43="" src="https://download.smoothcomp.com/features/jiu-jitsu//spectator.mp4"
                        playsinline="" loop=""></video></div>
                <div data-v-2edd5f43="" class="laptop-screen" bis_skin_checked="1">
                    <div data-v-2edd5f43="" class="video-wrapper" bis_skin_checked="1"><video data-v-2edd5f43=""
                            src="https://download.smoothcomp.com/features/jiu-jitsu//moderator.mp4" playsinline=""
                            loop=""></video></div>
                    <picture data-v-2edd5f43="">
                        <source data-v-2edd5f43="" srcset="
                                https://download.smoothcomp.com/features/misc/laptop@2x.webp 2x,
                                https://download.smoothcomp.com/features/misc/laptop@1x.webp 1x
                            " type="image/webp">
                        <source data-v-2edd5f43="" srcset="
                                https://download.smoothcomp.com/features/misc/laptop@2x.png 2x,
                                https://download.smoothcomp.com/features/misc/laptop@1x.png 1x
                            " type="image/png"><img data-v-2edd5f43="" loading="lazy"
                            src="https://download.smoothcomp.com/features/misc/laptop@1x.png">
                    </picture><svg data-v-2edd5f43="" class="cable cable-visible" width="67" height="144"
                        viewBox="0 0 67 144" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path data-v-2edd5f43=""
                            d="M0.935059 141.16H24.0001C46.0915 141.16 64.0001 123.252 64.0001 101.16V31.614C64.0001 15.8994 51.2609 3.16016 35.5463 3.16016V3.16016"
                            stroke="#2E2F33" stroke-width="5"></path>
                    </svg>
                </div>
            </div>
            
        </div><!---->
    </div>

</template>

<script setup>

</script>

<style scoped>

/* @media screen and (min-width: 1000px) { */
    .sc-container[data-v-2edd5f43] {
        flex-direction: row;
        align-items: center;
        justify-content: center;
        padding-top: 18rem;
        padding-bottom: 8rem;
    }
/* } */

@media screen and (min-width: 500px) {
    .in-scoreboard-page[data-v-2edd5f43] {
        margin-top: 4rem;
    }
}
/* .sc-container[data-v-2edd5f43] {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding-top: 10rem;
    padding-bottom: 6rem;
} */
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

@media screen and (min-width: 1000px) {
    .video-container[data-v-2edd5f43] {
        width: 80%;
    }
}

.video-container[data-v-2edd5f43] {
    position: relative;
    width: 95%;
}
.tv-screen.tv-visible[data-v-2edd5f43] {
    opacity: 1;
}
.tv-screen[data-v-2edd5f43] {
    transition: all .8s ease;
    width: 100%;
    border-radius: 2px;
    background: #000;
    border: 1px solid #4b4c51;
    padding: 1%;
    bottom: 22%;
    position: absolute;
}

.tv-screen video[data-v-2edd5f43] {
    width: 100%;
    display: block;
    border-radius: 2px;
    border: 1px solid #000;
}

audio, canvas, progress, video {
    display: inline-block;
    vertical-align: baseline;
}

.laptop-screen[data-v-2edd5f43] {
    position: relative;
    width: 92.6%;
    margin-left: auto;
    margin-right: auto;
    z-index: 1;
    position: relative;
}
.laptop-screen .video-wrapper[data-v-2edd5f43] {
    position: absolute;
    width: 100%;
    top: 3%;
    left: 0;
    padding: 0 12%;
    z-index: 3;
}
.laptop-screen video[data-v-2edd5f43] {
    width: 100%;
    border-radius: 4px;
}

audio, canvas, progress, video {
    display: inline-block;
    vertical-align: baseline;
}
picture {
    display: flex;
    justify-content: center;
}
svg.cable.cable-visible[data-v-2edd5f43] {
    opacity: 1;
    transition: all .6s ease-in;
    transition-delay: .8s;
}

svg.cable[data-v-2edd5f43] {
    position: absolute;
    right: -9%;
    bottom: 4%;
    height: auto;
    width: 9%;
}
svg:not(:root) {
    overflow: hidden;
}
</style>