<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import AffiliationCard from './AffiliationCard.vue';
import Logo from '@/assets/mainlogowebsite.jpg';
const affiliations = ref([]);
const searchFilter = ref('');

const filteredAffiliations = computed(() => {
    return affiliations.value.filter(affiliation => {
        return !searchFilter.value || 
            affiliation.name.toLowerCase().includes(searchFilter.value.toLowerCase());
    });
});

const handleFilter = (filters) => {
    searchFilter.value = filters.search;
};

const fetchAffiliations = async () => {
    try {
        const response = await axios.get(route('affiliations.list'));
        console.log('Raw affiliations data:', response.data.affiliations);
        affiliations.value = response.data.affiliations.map(affiliation => {
            console.log('Logo path for', affiliation.name, ':', affiliation.logo);
            return affiliation;
        });
    } catch (error) {
        console.error('Error fetching affiliations:', error);
    }
};

onMounted(() => {
    fetchAffiliations();
});

defineExpose({
    handleFilter
});
</script>

<template>
    <section class="boost" id="affiliations-list-list">
        <div class="container">
            <h1 class="text-center text-white text-3xl font-bold uppercase">
                Top Affiliations
            </h1>
            <div class="row pt-8">
                <template v-if="filteredAffiliations.length > 0">
                    <AffiliationCard v-for="affiliation in filteredAffiliations" :key="affiliation.id" :title="affiliation.name"
                        :imgSrc="affiliation.logo ? `${affiliation.logo}` : Logo" :name="affiliation.name"
                        :link="'#'" stats="12719 wins / 13080 losses"
                        athletes="68037 Athletes" />
                </template>
                <div v-else class="w-full text-center py-8">
                    <p class="text-gray-400 text-xl">No affiliations found</p>
                </div>
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
