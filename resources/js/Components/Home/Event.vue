
<template>

    <div data-v-3d34d95c="" class="sc-container" bis_skin_checked="1">
        <div data-v-3d34d95c="" class="trial-card" id="createevent" bis_skin_checked="1">
            <div data-v-3d34d95c="" bis_skin_checked="1">
                <h2 data-v-3d34d95c="" id="test2 size-1rem">Create your first event today 👊</h2>
                <InertiaLink :href="route('dashboard') + '#events-container'" class="button sc-btn-secondary sc-btn-large">Get
                    Started</InertiaLink>
            </div>
        </div>
    </div>
</template>

<script setup>
import { InertiaLink } from '@inertiajs/inertia-vue3';
</script>
<style scoped>

@media screen and (min-width: 1000px) {
    .sc-container[data-v-3d34d95c] {
        display: block;
    }
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
    .trial-card[data-v-3d34d95c] {
        background: #2C3EDC;
        border-radius: 1rem;
        display: flex
;
        align-items: center;
        justify-content: center;
        min-height: 25.5rem;
        margin-top: 8rem;
    }
}
.trial-card h2[data-v-3d34d95c] {
    margin-bottom: 3rem;
    max-width: 34rem;
    text-align: center;
    color: #f4f6fb;
}

/* @media screen and (min-width: 1000px) { */
    h2 {
        font-size: 2.2rem !important;
    }
/* } */
h2 {
    font-size: 1.5rem;
    line-height: 1.2;
    font-weight: 500;
    text-transform: none;
    margin: 0;
    color: var(--white-50);
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
    /* text-transform: uppercase; */
}
h2, .h2 {
    font-size: 1.414rem;
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

.trial-card .button[data-v-3d34d95c] {
    color: #2C3EDC;
    background: #F4F6FB;
}
.sc-btn-large {
    font-size: 1.31rem;
    padding: 0.9rem 1.4rem;
    border-radius: 5.3rem;
    cursor: pointer;
}
.sc-btn-secondary {
    background: rgb(244 246 251 / 10%);
    color: rgb(244 246 251 / 90%);
    border: none;
    display: flex
;
    align-items: center;
    justify-content: center;
    -webkit-backdrop-filter: blur(0.5rem);
    backdrop-filter: blur(0.5rem);
}
</style>