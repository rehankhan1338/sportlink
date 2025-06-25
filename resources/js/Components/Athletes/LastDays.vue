<script setup lang="ts">
import AffiliationCard from '../Affiliation/AffiliationCard.vue';
</script>

<template>
    <section class="boost" id="affiliations-list-list">
        <div class="container">
            <h1 class="text-center text-white text-3xl font-bold uppercase">
                Top Affiliations
                <span class="text-gray-500 text-3xl font-bold">Last 100 days</span>
            </h1>
            <div class="row pt-8">
                <AffiliationCard title="Largest affiliation"
                    imgSrc="https://smoothcomp.com/pictures/t/59329-jpqk/gracie-barra.png" name="Gracie Barra"
                    link="https://smoothcomp.com/en/affiliation/34" stats="12719 wins / 13080 losses"
                    athletes="68037 Athletes" />
                <AffiliationCard title="Most gold medals"
                    imgSrc="https://smoothcomp.com/pictures/t/59329-jpqk/gracie-barra.png" name="Gracie Barra"
                    link="https://smoothcomp.com/en/affiliation/34" stats="12719 wins / 13080 losses" medals="4491"
                    athletes="68037 Athletes" />
                <AffiliationCard title="Best win/loss difference"
                    imgSrc="https://smoothcomp.com/pictures/t/115189-pzzm/atos.jpg" name="Atos"
                    link="https://smoothcomp.com/en/affiliation/7" stats="2590 wins / 2063 losses" medals="527"
                    athletes="8800 Athletes" />
            </div>
        </div>
    </section>
</template>

<style scoped>
.boost {
    padding-top: 50px;
    padding-bottom: 50px;
}

.container {
    width: 90%;
    /* Set width to 90% with some space on the sides */
    margin: 0 auto;
    /* Center the container */
}

.full-width {
    font-size: 2rem;
    /* Adjust font size for better visibility */
    line-height: 1.5;
}

.text-center {
    text-align: center;
}

.mute {
    color: #6c757d;
    font-size: 1.1rem;
}

.row {
    display: flex;
    flex-wrap: wrap; /* Allow wrapping of items */
    margin-left: -15px;
    margin-right: -15px;
}

.col-md-4 {
    flex: 0 0 33.3333%;
    max-width: 33.3333%;
    padding-left: 15px;
    padding-right: 15px;
    box-sizing: border-box;
}

@media (max-width: 768px) {
    .row {
        flex-direction: column; /* Stack items vertically on smaller screens */
    }

    .col-md-4 {
        flex: 0 0 100%;
        max-width: 100%; /* Ensure each card takes up full width */
    }
}

@media (max-width: 576px) {
    .full-width {
        font-size: 1.5rem;
    }

    .container {
        width: 95%;
    }

    .row {
        gap: 20px;
        /* Reduce gap on smaller screens */
    }
}
</style>

