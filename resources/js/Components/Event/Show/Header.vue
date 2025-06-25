<template>
    <div class="event-header bg-[#171719] hidden-print hidden-tv">
        <div class="container mx-auto px-4 py-6">
            <!-- Event Info -->
            <div class="event-info flex items-center justify-between flex-col sm:flex-row">
                <!-- Event Avatar -->
                <div class="event-avatar mb-4 sm:mb-0">
                    <div class="relative">
                        <img class="w-40 h-15 rounded-sm sm:w-40 sm:h-15 object-cover"
                        :src="event.image ? `/storage/${event.image}` : Placeholderimage" alt="Event Avatar">
                    </div>
                </div>

                <!-- Event Title -->
                <div class="event-title flex-grow ml-0 sm:ml-6 text-center sm:text-left">
                    <a :href="route('event.show', { id: event.id })">
                        <h1 class="text-2xl font-semibold text-white">{{ event.title }}</h1>
                    </a>
                    <div class="sub-header text-gray-500 mt-2">
                        {{ new Date(event.start_date).toLocaleDateString() }} - {{ new Date(event.end_date).toLocaleDateString() }}
                    </div>
                </div>

                <!-- Register Button -->
                <div class="event-btns mt-4 sm:mt-0">
                    <a class="sc-btn sc-btn-primary sc-btn-sm px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
                    :href="`/select-profile?event_id=${event.id}`" v-if="isRegistrationOpen">
                        Register
                    </a>
                    <span v-else class="text-red-500 text-sm">Registration closed</span>
                </div>
            </div>


           
        </div>
    </div>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</template>

<script>
import { InertiaLink } from '@inertiajs/inertia-vue3';
import Placeholderimage from '@/assets/placeholder-image.jpg';
import { computed } from 'vue';

export default {
    name: "EventHeader",
    props: {
        event: {
            type: Object,
            required: true
        }
    },
    setup(props) {
        const isRegistrationOpen = computed(() => {
            if (!props.event.last_date_of_registration) return true;
            const lastRegistrationDate = new Date(props.event.last_date_of_registration);
            const now = new Date();
            return lastRegistrationDate > now;
        });

        return {
            Placeholderimage,
            isRegistrationOpen
        }
    }
};
</script>

<style scoped>
.hidden-print {
    display: none;
}

.hidden-tv {
    display: block;
}

.sc-nav {
    flex-wrap: nowrap;
    /* Ensure tabs don't wrap into a new line */
}

.sc-nav li.active {
    border-bottom: 2px solid white;
}

@media (max-width: 768px) {
    .sc-nav {
        flex-wrap: nowrap;
        /* Prevent wrapping on mobile */
    }
}
</style>
