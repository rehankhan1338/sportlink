<template>
    <Transition
        enter-active-class="transition ease-out duration-300"
        enter-from-class="transform translate-y-2 opacity-0"
        enter-to-class="transform translate-y-0 opacity-100"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="transform translate-y-0 opacity-100"
        leave-to-class="transform translate-y-2 opacity-0"
    >
        <div v-if="show" class="fixed top-4 right-4 z-50">
            <div class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>{{ message }}</span>
            </div>
        </div>
    </Transition>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';

const props = defineProps({
    message: {
        type: String,
        required: true
    },
    duration: {
        type: Number,
        default: 5000
    }
});

const show = ref(true);

onMounted(() => {
    if (props.duration > 0) {
        setTimeout(() => {
            show.value = false;
        }, props.duration);
    }
});

watch(() => props.message, () => {
    show.value = true;
    if (props.duration > 0) {
        setTimeout(() => {
            show.value = false;
        }, props.duration);
    }
});
</script> 