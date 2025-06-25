<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import Navbar from "@/Components/Navbar.vue";
import Footer from "@/Components/Footer.vue";

const form = useForm({
    first_name: '',
    last_name: ''
});

const submit = () => {
    form.post(route('profile.store'), {
        onSuccess: () => {
            window.location.href = route('select-profile');
        }
    });
};
</script>

<template>
    <Head title="Create Profile" />
    <Navbar />
    
    <div style="background-color: rgb(28, 28, 28);" class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full px-6 py-8 bg-[#212121] rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-white text-center mb-8">Create New Profile</h2>
            
            <form @submit.prevent="submit" class="space-y-6">
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-300">First Name</label>
                    <input
                        id="first_name"
                        v-model="form.first_name"
                        type="text"
                        class="mt-1 block w-full rounded-md bg-[#2a2a2a] border-gray-600 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        required
                    >
                </div>

                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-300">Last Name</label>
                    <input
                        id="last_name"
                        v-model="form.last_name"
                        type="text"
                        class="mt-1 block w-full rounded-md bg-[#2a2a2a] border-gray-600 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        required
                    >
                </div>

                <div>
                    <button
                        type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        :disabled="form.processing"
                    >
                        Create Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <Footer />
</template> 