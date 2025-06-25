<template>

    <div data-v-40a8e5aa="" class="sc-container">
        <div data-v-40a8e5aa="" class="title-container">
           
            <img :src="bracket" class="sc-background-img" width="1297" height="568" >
            <div data-v-40a8e5aa="" class="background-shadow"></div>
            <h2 data-v-40a8e5aa="" class="h1"><strong data-v-40a8e5aa="" class="!text-red-600">Go big. <span class="text-white"> Our bracket builder can handle
                    it.</span></strong></h2>
        </div>
        <div data-v-40a8e5aa="" class="card-container-all">
            <div class="sc-cards card-container-small" data-v-40a8e5aa="">
                <div class="sc-card-wrapper" data-v-40a8e5aa="">
                    <div class="sc-card" data-v-40a8e5aa="">
                        <img
                            :src="single_elimination" loading="lazy"
                            data-v-40a8e5aa="">
                        <p data-v-40a8e5aa="">Single elimination</p>
                    </div>
                </div>
                <div class="sc-card-wrapper" data-v-40a8e5aa="">
                    <div class="sc-card" data-v-40a8e5aa=""><img
                            :src="dobule_elimination" loading="lazy"
                            data-v-40a8e5aa="">
                        <p data-v-40a8e5aa="">Double elimination</p>
                    </div>
                </div>
                <div class="sc-card-wrapper" data-v-40a8e5aa="">
                    <div class="sc-card" data-v-40a8e5aa=""><img
                            :src="round_robin" loading="lazy"
                            data-v-40a8e5aa="">
                        <p data-v-40a8e5aa="">Round robin</p>
                    </div>
                </div>
                <div class="sc-card-wrapper" data-v-40a8e5aa="">
                    <div class="sc-card" data-v-40a8e5aa=""><img
                            :src="three_players" loading="lazy"
                            data-v-40a8e5aa="">
                        <p data-v-40a8e5aa="">3 players with comeback</p>
                    </div>
                </div>
                <div class="sc-card-wrapper" data-v-40a8e5aa="">
                    <div class="sc-card" data-v-40a8e5aa=""><img
                            :src="multistage" loading="lazy"
                            data-v-40a8e5aa="">
                        <p data-v-40a8e5aa="">Multistage</p>
                    </div>
                </div>
                <div class="sc-card-wrapper" data-v-40a8e5aa="">
                    <div class="sc-card" data-v-40a8e5aa=""><img
                            :src="judges_score" loading="lazy"
                            data-v-40a8e5aa="">
                        <p data-v-40a8e5aa="">Voting with judges score</p>
                    </div>
                </div>
                <div class="sc-card-wrapper" data-v-40a8e5aa="">
                    <div class="sc-card" data-v-40a8e5aa=""><img
                            :src="placement_bracket" loading="lazy"
                            data-v-40a8e5aa="">
                        <p data-v-40a8e5aa="">Placement bracket</p>
                    </div>
                </div>
                <div class="sc-card-wrapper" data-v-40a8e5aa="">
                    <div class="sc-card" data-v-40a8e5aa=""><img
                            :src="two_players" loading="lazy"
                            data-v-40a8e5aa="">
                        <p data-v-40a8e5aa="">2 players - best of three</p>
                    </div>
                </div>
            </div>
            <div data-v-40a8e5aa="" class="sc-cards">
                <div data-v-40a8e5aa="" class="sc-card-wrapper community-card-wrapper">
                    <div data-v-40a8e5aa="" class="sc-card">
                        <div data-v-40a8e5aa="" class="relative community-card">
                            <h2 data-v-40a8e5aa=""><strong>Missing something?</strong><br>Let's talk!</h2><img
                                data-v-40a8e5aa=""
                                src="https://download.smoothcomp.com/features/misc/illustration-connection.svg"
                                class="community-card-img" loading="lazy">
                            <p data-v-40a8e5aa="">Smoothcomp has grown thanks to our community. How can we make your
                                sport better?</p>
                            <div data-v-40a8e5aa="" style="width: 100%; justify-content: flex-start; margin-top: 16px;">
                                <a data-v-40a8e5aa="" href="/support" class="sc-btn-text sc-btn-large"
                                    style="padding-left: 0px;">Support &nbsp; <img
                                        src="https://download.smoothcomp.com/features/misc/arrow-up-right.svg"
                                        loading="lazy"></a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import bracket from '@/assets/bracket.webp';
import single_elimination from '@/assets/single-elimination.svg';
import dobule_elimination from '@/assets/double-elimination.svg';
import round_robin from '@/assets/double-elimination.svg';
import three_players from '@/assets/3-players.svg';
import multistage from '@/assets/multistage.svg';
import judges_score from '@/assets/judges-score.svg';
import placement_bracket from '@/assets/placement-bracket.svg';
import two_players from '@/assets/2-players.svg';
</script>

<style scoped>

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
    .title-container[data-v-40a8e5aa] {
        align-items: flex-start;
        min-height: 35.5rem;
    }
}

.title-container[data-v-40a8e5aa] {
    /* display: flex; */
    flex-direction: column-reverse;
    align-items: center;
    /* padding-top: 10rem; */
    padding-bottom: 2rem;
    margin-top: 3rem;
    margin-bottom: .8rem;
    position: relative;
    overflow: hidden;
}
.sc-25 picture {
    display: flex;
    justify-content: center;
}
.sc-25 .sc-background-img {
    width: 100%;
    height: 100%;
    -o-object-fit: cover;
    object-fit: cover;
    position: absolute;
    top: 0;
    left: 0;
    z-index: -1;
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

.background-shadow[data-v-40a8e5aa] {
    display: block;
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    right: 0;
    /* z-index: -1; */
    background: linear-gradient(0deg, rgba(0, 0, 0, .9) 0%, rgba(0, 0, 0, 0) 50%);
}

@media screen and (min-width: 1000px) {
    .title-container .h1[data-v-40a8e5aa] {
        text-align: left;
        max-width: 33rem;
        margin-right: 0;
        margin-left: 2rem;
    }
}

.title-container .h1[data-v-40a8e5aa] {
    /* text-align: center;
    margin-right: 3.5rem;
    margin-left: 3.5rem; */
}
@media screen and (min-width: 1000px) {
    .sc-25 h1, .sc-25 .h1 {
        font-size: 2.9rem;
    }
}

@media screen and (min-width: 1000px) {
    .sc-25 h2 {
        font-size: 2.2rem;
    }
}
.sc-25 h2 {
    font-size: 1.5rem;
    line-height: 1.2;
    font-weight: 500;
    text-transform: none;
    margin: 0;
    color: var(--white-50);
}
h1, .h1 {
    font-size: 2.9rem;
    line-height: 1.1;
    margin-top: 0;
    font-weight: 500;
    margin-bottom: 28px;
    margin-bottom: 1.86666667rem;
    /* text-transform: uppercase; */
}
strong {
    color: white;
    font-weight: normal !important;
}

b, strong {
    font-weight: bold;
}

@media screen and (min-width: 1150px) {
    .card-container-all[data-v-40a8e5aa] {
        grid-template-rows: 1fr;
        grid-template-columns: 2fr 1fr !important;
    }
}

@media screen and (min-width: 1150px) {
    .card-container-all[data-v-40a8e5aa] {
        flex-direction: row;
    }
}
.card-container-all[data-v-40a8e5aa] {
    display: grid;
    grid-template-rows: repeat(2);
    grid-template-columns: 1fr;
    margin-left: -.2rem;
    margin-right: -.2rem;
}
@media screen and (min-width: 1150px) {
    .card-container-small[data-v-40a8e5aa] {
        grid-template-rows: repeat(2);
        grid-template-columns: repeat(4, 1fr);
    }
}

@media screen and (min-width: 800px) {
    .card-container-small[data-v-40a8e5aa] {
        grid-template-rows: repeat(2);
        grid-template-columns: repeat(4, 1fr) !important;
    }
}
.card-container-small[data-v-40a8e5aa] {
    display: grid;
    grid-template-rows: repeat(4);
    grid-template-columns: repeat(2, 1fr);
}
.sc-cards {
    display: flex;
    align-items: center;
    justify-content: center;
}
.sc-card-wrapper[data-v-40a8e5aa] {
    padding: .2rem;
}

.sc-card-wrapper {
    height: 100%;
}
.sc-card[data-v-40a8e5aa] {
    border-radius: .3rem;
    display: flex
;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 1rem;
    height: 100%;
}

.sc-card {
    background: #171719;
}

 p[data-v-40a8e5aa] {
    margin-top: 0rem;
    margin-bottom: 0;
}

@media screen and (min-width: 1000px) {
    .sc-25 p {
        font-size: 1.1rem;
    }
}
.sc-25 p {
    font-size: 1.1rem;
    font-weight: 500;
    margin: 0;
    color: red;
}
p {
    margin-bottom: 20px;
    margin-bottom: 1.33333333rem;
}
p {
    margin: 0 0 0.75rem;
}
.community-card-wrapper[data-v-40a8e5aa] {
    width: 100%;
}
.sc-card-wrapper[data-v-40a8e5aa] {
    padding: .2rem;
}
.sc-card-wrapper {
    height: 100%;
}
.sc-card[data-v-40a8e5aa] {
    border-radius: .3rem;
    display: flex
;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 1rem;
    height: 100%;
}

@media screen and (min-width: 1150px) {
    .community-card div[data-v-40a8e5aa] {
        justify-content: flex-end !important;
    }
}

.community-card div[data-v-40a8e5aa] {
    display: flex
;
}

.community-card[data-v-40a8e5aa] {
    width: 100%;
    margin: 0;
    display: flex
;
    flex-direction: column;
    align-items: flex-start;
    justify-content: space-between;
    padding-top: 2rem;
    padding-bottom: 1rem;
    text-align: left;
}

.relative {
    position: relative;
}
.community-card h2[data-v-40a8e5aa] {
    width: 50%;
    margin-bottom: 30px;
    position: relative;
    z-index: 1;
}

@media screen and (min-width: 1000px) {
    h2 {
        font-size: 2.2rem;
    }
}
h2 {
    font-size: 2.2rem;
    line-height: 1.2;
    font-weight: 500;
    text-transform: none;
    margin: 0;
    color: rgb(244 246 251 / 50%);
}

.community-card img.community-card-img[data-v-40a8e5aa] {
    position: absolute;
    top: 0;
    right: 0;
}
.community-card p[data-v-40a8e5aa] {
    text-align: left;
}

.community-card p[data-v-40a8e5aa] {
    width: 70%;
}
.sc-card p[data-v-40a8e5aa] {
    margin-top: 0rem;
    margin-bottom: 0;
}
@media screen and (min-width: 1000px) {
    .sc-25 p {
        font-size: 1.1rem;
    }
}
 p {
    font-size: 1.1rem;
    font-weight: 500;
    margin: 0;
    color: rgb(244 246 251 / 50%);
}
 .sc-btn-large {
    font-size: 1.31rem;
    padding: 0.9rem 1.4rem;
    border-radius: 5.3rem;
    cursor: pointer;
}

 .sc-btn-text {
    background: none;
    color: rgb(244 246 251 / 70%);
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
