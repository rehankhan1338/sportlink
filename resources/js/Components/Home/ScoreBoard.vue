<template>

    <div data-v-2edd5f43="" class="hide-on-mobile sc-container">
        <div data-v-2edd5f43="" style="display: flex; flex-direction: column; align-items: center; width: 100%;">
            <div data-v-2edd5f43="" class="video-container">
                <div data-v-2edd5f43="" class="tv-screen tv-visible tv-overlay-off"><video data-v-2edd5f43=""
                        src="https://download.smoothcomp.com/features/jiu-jitsu//spectator.mp4" playsinline=""
                        loop=""></video></div>
                <div data-v-2edd5f43="" class="laptop-screen">
                    <div data-v-2edd5f43="" class="video-wrapper"><video data-v-2edd5f43=""
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
            <div data-v-2edd5f43="" style="display: none;"><button data-v-2edd5f43="" class="btn-primary"> Play
                </button><button data-v-2edd5f43="" class="btn-secondary"> Pause </button><button data-v-2edd5f43=""
                    class="btn-danger"> Stop </button></div>
            <!-- <div data-v-2edd5f43="" class="button-container">
                <div data-v-36894412="" data-v-2edd5f43="" class="dropdown selector"><select data-v-36894412=""
                        id="sport-select" name="sports">
                        <option data-v-36894412="" value="10">Boxing</option>
                        <option data-v-36894412="" value="1">Jiu Jitsu</option>
                        <option data-v-36894412="" value="8">JJIF Ju-Jutsu</option>
                        <option data-v-36894412="" value="4">Judo</option>
                        <option data-v-36894412="" value="5">Karate</option>
                        <option data-v-36894412="" value="12">Kickboxing</option>
                        <option data-v-36894412="" value="11">MMA</option>
                        <option data-v-36894412="" value="13">Sambo</option>
                        <option data-v-36894412="" value="9">Taekwondo</option>
                        <option data-v-36894412="" value="6">Thaiboxing</option>
                        <option data-v-36894412="" value="17">UWW Grappling</option>
                        <option data-v-36894412="" value="7">Wrestling</option>
                        <option data-v-36894412="" value="18">Wrestling - Folkstyle</option>
                        <option data-v-36894412="" value="14">Wushu Sanda</option>
                        <option data-v-36894412="" value="19">Wushu Taolo</option>
                    </select></div>
                <div data-v-59d9219a="" data-v-2edd5f43="" class="slider-switch switch">
                    <div data-v-59d9219a="" class="slider-right inactive"><svg data-v-59d9219a="" width="24" height="25"
                            viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g data-v-59d9219a="" opacity="0.5">
                                <path data-v-59d9219a="" fill-rule="evenodd" clip-rule="evenodd"
                                    d="M4 9.16016C4 8.05559 4.89543 7.16016 6 7.16016H18C19.1046 7.16016 20 8.05559 20 9.16016V15.1602C20 16.2647 19.1046 17.1602 18 17.1602H6C4.89543 17.1602 4 16.2647 4 15.1602V9.16016ZM18 9.16016H6V15.1602H18V9.16016Z"
                                    fill="white"></path>
                            </g>
                        </svg>
                        <p data-v-59d9219a="">Single display</p>
                    </div>
                    <div data-v-59d9219a="" class="slider-left active"><svg data-v-59d9219a="" width="24" height="25"
                            viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path data-v-59d9219a="" fill-rule="evenodd" clip-rule="evenodd"
                                d="M10 4.16016C8.89543 4.16016 8 5.05559 8 6.16016V8.16016H10V6.16016H22V12.1602H18V14.1602H22C23.1046 14.1602 24 13.2647 24 12.1602V6.16016C24 5.05559 23.1046 4.16016 22 4.16016H10ZM2 10.1602C0.895431 10.1602 0 11.0556 0 12.1602V18.1602C0 19.2647 0.895432 20.1602 2 20.1602H14C15.1046 20.1602 16 19.2647 16 18.1602V12.1602C16 11.0556 15.1046 10.1602 14 10.1602H2ZM2 18.1602V12.1602H14V18.1602H2Z"
                                fill="black"></path>
                        </svg>
                        <p data-v-59d9219a="">Dual display</p>
                    </div>
                </div><button data-v-2edd5f43="" class="sc-btn-text sc-btn-medium sc-btn-muted enlarge-btn"
                    style="margin-left: 1rem;"><svg data-v-2edd5f43="" width="24" height="11" viewBox="0 0 24 11"
                        fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path data-v-2edd5f43="" fill-rule="evenodd" clip-rule="evenodd"
                            d="M20.1716 6.29148L15.084 6.29144L15.084 4.29143L20.1716 4.29148L17.6492 1.76897L19.0634 0.354752L24 5.29148L19.0423 10.25L17.6281 8.83579L20.1716 6.29148Z"
                            fill="white"></path>
                        <path data-v-2edd5f43="" fill-rule="evenodd" clip-rule="evenodd"
                            d="M3.8284 4.3135L8.91596 4.31355L8.91596 6.31355L3.8284 6.3135L6.35078 8.83601L4.93657 10.2502L-2.95048e-05 5.3135L4.95766 0.354981L6.37187 1.76919L3.8284 4.3135Z"
                            fill="white"></path>
                    </svg> &nbsp; Enlarge</button>
            </div> -->
        </div>
        <div data-v-2edd5f43="" class="heading-container" style="margin-top: -15rem;">
            <div data-v-2edd5f43="">
                <h2 data-v-2edd5f43="" class="h1"><strong>The scoreboard, </strong>powered by the bracket system (and
                    you).</h2>
                <div data-v-2edd5f43="" class="link-container"><a data-v-2edd5f43="" href="/features/scoreboard"
                        class="sc-btn-text sc-btn-medium">Learn more &nbsp; <span data-v-2edd5f43=""
                            class="icon icon-chevron-right"></span></a><a data-v-2edd5f43=""
                        href="/features/scoreboard#download" class="sc-btn-text sc-btn-medium">Try for free &nbsp; <span
                            data-v-2edd5f43="" class="icon icon-chevron-right"></span></a></div>
            </div>
        </div>
    </div>

</template>

<script>

</script>

<style scoped>

@media (max-width: 768px) {
    .hide-on-mobile {
        display: none !important;
    }
}

@media screen and (min-width: 1000px) {
    .sc-container[data-v-2edd5f43] {
        flex-direction: row;
        align-items: center;
        justify-content: center;
        padding-top: 18rem;
        padding-bottom: 8rem;
    }
}

.sc-container[data-v-2edd5f43] {
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding-top: 18rem;
    padding-bottom: 8rem;
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

@media screen and (min-width: 1000px) {
    .video-container[data-v-2edd5f43] {
        width: 80% !important;
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
.laptop-screen img[data-v-2edd5f43] {
    width: 100%;
    position: relative;
    z-index: 2;
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

.btn-primary {
    color: #fff;
    background-color: #78B329;
    border-color: #78B329;
}

input, button, select, textarea {
    font-family: inherit;
    font-size: inherit;
    line-height: inherit;
}
button, html input[type="button"], input[type="reset"], input[type="submit"] {
    -webkit-appearance: button;
    cursor: pointer;
}
button, select {
    text-transform: none;
}
button {
    overflow: visible;
}
button, input, optgroup, select, textarea {
    color: inherit;
    font: inherit;
    margin: 0;
}
.btn-secondary, .btn-edit {
    color: #fff;
    background-color: #179de0;
    border-color: #179de0;
}
.btn-danger {
    color: #fff;
    background-color: #C24444;
    border-color: #C24444;
}
@media screen and (min-width: 1000px) {
    .button-container[data-v-2edd5f43] {
        flex-direction: row;
    }
}

.button-container[data-v-2edd5f43] {
    display: flex
;
    flex-direction: column;
    margin-top: 2rem;
    position: relative;
    z-index: 3;
}

@media screen and (min-width: 1000px) {
    .selector[data-v-2edd5f43] {
        margin-right: .5rem;
    }
}

.dropup, .dropdown {
    position: relative;
}

.dropdown select[data-v-36894412] {
    padding: .5rem 1rem;
    border-radius: 6.25rem;
    background: var(--gray-2);
    color: var(--white-70);
    border: none;
    font-size: 1.1rem;
    height: 100%;
}

select {
    color: #262525;
}
input, button, select, textarea {
    font-family: inherit;
    font-size: inherit;
    line-height: inherit;
}
@media screen and (min-width: 1000px) {
    .switch[data-v-2edd5f43] {
        display: flex
;
        margin-left: .5rem;
        margin-top: 0;
        margin-bottom: 0;
    }
}

.switch[data-v-2edd5f43] {
    margin-top: 2rem;
    margin-bottom: 2rem;
}
.slider-switch[data-v-59d9219a] {
    background: var(--gray-1);
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6.25rem;
}

.slider-switch div[data-v-59d9219a] {
    display: flex
;
    align-items: center;
    justify-content: center;
    padding: .5rem 1rem;
    border-radius: 6.25rem;
    transition: .2s all ease;
    cursor: pointer;
}

.inactive[data-v-59d9219a] {
    background: none;
    color: var(--gray-6);
}
.disabled, .inactive {
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

svg:not(:root) {
    overflow: hidden;
}
.inactive path[data-v-59d9219a] {
    fill: var(--gray-6);
}
.slider-switch p[data-v-59d9219a] {
    margin-left: .5rem;
    color: unset;
}

@media screen and (min-width: 1000px) {
     p {
        font-size: 1.1rem;
    }
}
p {
    font-size: 1rem;
    font-weight: 500;
    margin: 0;
    color: var(--white-50);
}
p {
    margin-bottom: 20px;
    margin-bottom: 1.33333333rem;
}
p {
    margin: 0 0 0.75rem;
}

.slider-switch div[data-v-59d9219a] {
    display: flex
;
    align-items: center;
    justify-content: center;
    padding: .5rem 1rem;
    border-radius: 6.25rem;
    transition: .2s all ease;
    cursor: pointer;
}

.active[data-v-59d9219a] {
    background: var(--gray-7);
    color: var(--black);
    pointer-events: none;
}
.active path[data-v-59d9219a] {
    fill: var(--black);
}
.slider-switch p[data-v-59d9219a] {
    margin-left: .5rem;
    color: unset;
}

@media screen and (min-width: 1000px) {
     p {
        font-size: 1.1rem;
    }
}
@media screen and (min-width: 1000px) {
    .enlarge-btn[data-v-2edd5f43] {
        display: block;
    }
}

<style>
.enlarge-btn[data-v-2edd5f43] {
    display: none;
}
<style>
.sc-btn-muted[data-v-2edd5f43], .sc-btn-muted svg path[data-v-2edd5f43] {
    color: var(--white-30);
    fill: var(--white-30);
}
 .sc-btn-medium {
    font-size: 1.06rem;
    padding: 0.56rem 1.25rem;
    border-radius: 6.25rem;
    cursor: pointer;
}
.sc-btn-text {
    background: none;
    color: var(--white-70);
    border: none;
    display: flex
;
    align-items: center;
    justify-content: center;
}
input, button, select, textarea {
    font-family: inherit;
    font-size: inherit;
    line-height: inherit;
}
button, html input[type="button"], input[type="reset"], input[type="submit"] {
    -webkit-appearance: button;
    cursor: pointer;
}
button, select {
    text-transform: none;
}
button {
    overflow: visible;
}
button, input, optgroup, select, textarea {
    color: inherit;
    font: inherit;
    margin: 0;
}
.sc-btn-muted[data-v-2edd5f43], .sc-btn-muted svg path[data-v-2edd5f43] {
    color: var(--white-30);
    fill: var(--white-30);
}

/* right side */

@media screen and (min-width: 1000px) {
    .heading-container[data-v-2edd5f43] {
        width: 50%;
        display: block;
    }
}

.heading-container[data-v-2edd5f43] {
    /* display: flex; */
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    transition: all .8s;
    opacity: 1;
}

@media screen and (min-width: 1000px) {
    .heading-container .h1[data-v-2edd5f43] {
        max-width: 34rem !important;
        margin-top: 0 !important;
        margin-bottom: 4rem !important;
    }
}

.heading-container .h1[data-v-2edd5f43] {
    max-width: 34rem;
    margin-top: 0;
    font-size: 2.2rem;
    font-weight: 500;
    margin-bottom: 4rem;
    text-transform: none;
    font-size: 2.9rem;
    margin: 0;
    color: rgb(244 246 251 / 50%);
}
/* .heading-container .h1[data-v-2edd5f43] {
    max-width: 20rem;
    margin-top: 2rem;
    font-size: 2.2rem;
    font-weight: 500;
    text-transform: none;
    margin: 0;
    color: white;
} */

@media screen and (min-width: 1000px) {
     .h1 {
        font-size: 2.9rem;
    }
}
.h1 {
    font-size: 2.2rem;
    font-weight: 500;
    text-transform: none;
    margin: 0;
    color: var(--white-50);
}
@media screen and (min-width: 1000px) {
     h2 {
        font-size: 2.2rem;
    }
}
h2 {
    font-size: 1.5rem;
    line-height: 1.2;
    font-weight: 500;
    text-transform: none;
    margin: 0;
    color: var(--white-50);
}
h1, .h1 {
    font-size: 32px;
    font-size: 2rem;
    line-height: 1.1;
    margin-top: 0;
    font-weight: 700;
    margin-bottom: 28px;
    margin-bottom: 1.86666667rem;
    text-transform: uppercase;
}
h1, .h1 {
    font-size: 2rem;
}
h1, .h1, h2, .h2, h3, .h3 {
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
}
h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
    font-family: inherit;
    font-weight: 500;
    line-height: 1.1;
    color: inherit;
}
h1, h2, h3, h4 {
    color: #fff;
}
h2, .h2 {
    font-size: 20px;
    font-size: 1.25rem;
    line-height: 1.3;
    font-weight: 700;
    margin-top: 30px;
    margin-top: 2rem;
    text-transform: uppercase;
}
h2, .h2 {
    font-size: 1.414rem;
}

 strong {
    color: #f4f6fb;
    font-weight: normal !important;
}

b, strong {
    font-weight: bold;
}
.link-container[data-v-2edd5f43] {
    display: flex
;
    justify-content: center;
    flex-wrap: wrap;
    max-width: 34rem;
    margin-top: 1rem;
}
.sc-btn-medium {
    font-size: 1.06rem;
    padding: 0.56rem 1.25rem;
    border-radius: 6.25rem;
    cursor: pointer;
}

.sc-btn-text {
    background: none;
    color: var(--white-70);
    border: none;
    display: flex
;
    align-items: center;
    justify-content: center;
}
a {
    color: #179de0;
    text-decoration: none;
}
a {
    background-color: transparent;
}
</style>