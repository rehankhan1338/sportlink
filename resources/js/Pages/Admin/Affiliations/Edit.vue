<template>
    <AdminLayout title="Edit Affiliation">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                <input 
                                    type="text" 
                                    id="name" 
                                    v-model="form.name" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    required
                                />
                                <div v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</div>
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    v-model="form.email" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    required
                                />
                                <div v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email }}</div>
                            </div>

                            <!-- Country -->
                            <div>
                                <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                                <input 
                                    type="text" 
                                    id="country" 
                                    v-model="form.country" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    required
                                />
                                <div v-if="errors.country" class="mt-1 text-sm text-red-600">{{ errors.country }}</div>
                            </div>

                            <!-- City -->
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                                <input 
                                    type="text" 
                                    id="city" 
                                    v-model="form.city" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    required
                                />
                                <div v-if="errors.city" class="mt-1 text-sm text-red-600">{{ errors.city }}</div>
                            </div>

                            <!-- Address -->
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                                <textarea 
                                    id="address" 
                                    v-model="form.address" 
                                    rows="2"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    required
                                ></textarea>
                                <div v-if="errors.address" class="mt-1 text-sm text-red-600">{{ errors.address }}</div>
                            </div>

                            <!-- Location -->
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                                <input 
                                    type="text" 
                                    id="location" 
                                    v-model="form.location" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    required
                                />
                                <div v-if="errors.location" class="mt-1 text-sm text-red-600">{{ errors.location }}</div>
                            </div>

                            <!-- Current Logo -->
                            <div v-if="affiliation.logo" class="mt-4">
                                <label class="block text-sm font-medium text-gray-700">Current Logo</label>
                                <img :src="affiliation.logo" alt="Current Logo" class="mt-2 h-20 w-20 object-cover rounded-lg">
                            </div>

                            <!-- Logo Upload -->
                            <div>
                                <label for="logo" class="block text-sm font-medium text-gray-700">New Logo</label>
                                <input 
                                    type="file" 
                                    id="logo" 
                                    @change="handleLogoUpload"
                                    accept="image/*"
                                    class="mt-1 block w-full"
                                />
                                <div v-if="errors.logo" class="mt-1 text-sm text-red-600">{{ errors.logo }}</div>
                            </div>

                            <!-- Current Cover Image -->
                            <div v-if="affiliation.cover_image" class="mt-4">
                                <label class="block text-sm font-medium text-gray-700">Current Cover Image</label>
                                <img :src="affiliation.cover_image" alt="Current Cover" class="mt-2 h-32 w-full object-cover rounded-lg">
                            </div>

                            <!-- Cover Image Upload -->
                            <div>
                                <label for="cover_image" class="block text-sm font-medium text-gray-700">New Cover Image</label>
                                <input 
                                    type="file" 
                                    id="cover_image" 
                                    @change="handleCoverUpload"
                                    accept="image/*"
                                    class="mt-1 block w-full"
                                />
                                <div v-if="errors.cover_image" class="mt-1 text-sm text-red-600">{{ errors.cover_image }}</div>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <Link
                                    :href="route('admin.affiliations.index')"
                                    class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring focus:ring-gray-300 disabled:opacity-25 transition mr-4"
                                >
                                    Cancel
                                </Link>
                                <button
                                    type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"
                                    :disabled="processing"
                                >
                                    {{ processing ? 'Saving...' : 'Save Changes' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    affiliation: {
        type: Object,
        required: true
    }
});

const processing = ref(false);
const errors = ref({});

const form = useForm({
    name: props.affiliation.name,
    email: props.affiliation.email,
    country: props.affiliation.country,
    city: props.affiliation.city,
    address: props.affiliation.address,
    location: props.affiliation.location,
    logo: null,
    cover_image: null,
    _method: 'PUT'
});

const handleLogoUpload = (e) => {
    if (e.target.files.length > 0) {
        form.logo = e.target.files[0];
    }
};

const handleCoverUpload = (e) => {
    if (e.target.files.length > 0) {
        form.cover_image = e.target.files[0];
    }
};

const submit = async () => {
    processing.value = true;
    errors.value = {};

    try {
        await form.post(route('admin.affiliations.update', props.affiliation.affiliation_id), {
            onSuccess: () => {
                // Don't reset the form after successful update
            },
            onError: (err) => {
                errors.value = err;
            },
        });
    } finally {
        processing.value = false;
    }
};
</script> 