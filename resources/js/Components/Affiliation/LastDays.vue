<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import AffiliationCard from './AffiliationCard.vue';
import Logo from '@/assets/mainlogowebsite.jpg';
const academies = ref([]);
const searchFilter = ref('');

const filteredAcademies = computed(() => {
    return academies.value.filter(academy => {
        return !searchFilter.value || 
            academy.name.toLowerCase().includes(searchFilter.value.toLowerCase());
    });
});

const handleFilter = (filters) => {
    searchFilter.value = filters.search;
};

const fetchAcademies = async () => {
    try {
        console.log('Fetching academies...');
        const response = await axios.get(route('academies.list'));
        console.log('API Response:', response);
        
        if (!response.data || !response.data.academies) {
            console.error('Invalid response format:', response.data);
            return;
        }
        
        if (response.data.error) {
            console.error('API Error:', response.data.error);
            return;
        }

        academies.value = response.data.academies;
        console.log('Academies loaded:', academies.value);
        
        // Log individual academy data
        academies.value.forEach(academy => {
            console.log('Academy:', {
                id: academy.id,
                name: academy.name,
                logo: academy.logo,
                fullLogoPath: academy.logo ? `/storage/${academy.logo}` : null
            });
        });
    } catch (error) {
        console.error('Error fetching academies:', error);
        if (error.response) {
            console.error('Response data:', error.response.data);
            console.error('Response status:', error.response.status);
        }
    }
};

onMounted(() => {
    console.log('Component mounted, fetching academies...');
    fetchAcademies();
});

defineExpose({
    handleFilter
});
</script>

<template>
    <section class="boost" id="affiliations-list-list">
        <div class="container">
            <h1 class="text-center text-white text-3xl font-bold uppercase">
                Top Academies
            </h1>
            <div class="row pt-8">
                <template v-if="filteredAcademies.length > 0">
                    <AffiliationCard v-for="academy in filteredAcademies" :key="academy.id" :title="academy.name"
                        :imgSrc="academy.logo ? `/storage/${academy.logo}` : Logo" :name="academy.name"
                        :link="`/academy/${academy.id}`" stats="12719 wins / 13080 losses"
                        athletes="68037 Athletes" />
                </template>
                <div v-else class="col-12 text-center">
                    <p class="text-gray-500 text-lg">No academies found</p>
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
