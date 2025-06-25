<template>
    <AdminLayout title="Create Affiliation">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit" class="space-y-6" enctype="multipart/form-data">
                            <!-- Display general error if any -->
                            <div v-if="errors.error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                                <span class="block sm:inline">{{ errors.error }}</span>
                            </div>

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

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea 
                                    id="description" 
                                    v-model="form.description" 
                                    rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    required
                                ></textarea>
                                <div v-if="errors.description" class="mt-1 text-sm text-red-600">{{ errors.description }}</div>
                            </div>

                            <!-- Website -->
                            <div>
                                <label for="website" class="block text-sm font-medium text-gray-700">Website</label>
                                <input 
                                    type="url" 
                                    id="website" 
                                    v-model="form.website" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="https://example.com"
                                />
                                <div v-if="errors.website" class="mt-1 text-sm text-red-600">{{ errors.website }}</div>
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                                <input 
                                    type="tel" 
                                    id="phone" 
                                    v-model="form.phone" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                />
                                <div v-if="errors.phone" class="mt-1 text-sm text-red-600">{{ errors.phone }}</div>
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select 
                                    id="status" 
                                    v-model="form.status" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    required
                                >
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="pending">Pending</option>
                                </select>
                                <div v-if="errors.status" class="mt-1 text-sm text-red-600">{{ errors.status }}</div>
                            </div>

                            <!-- Logo -->
                            <div>
                                <label for="logo" class="block text-sm font-medium text-gray-700">Logo</label>
                                <input 
                                    type="file" 
                                    id="logo" 
                                    @change="handleLogoUpload"
                                    accept="image/*"
                                    class="mt-1 block w-full"
                                />
                                <div v-if="errors.logo" class="mt-1 text-sm text-red-600">{{ errors.logo }}</div>
                            </div>

                            <!-- Cover Image -->
                            <div>
                                <label for="cover_image" class="block text-sm font-medium text-gray-700">Cover Image</label>
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
                                    {{ processing ? 'Creating...' : 'Create Affiliation' }}
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

const processing = ref(false);
const errors = ref({});

const form = useForm({
    name: '',
    email: '',
    country: '',
    city: '',
    address: '',
    location: '',
    description: '',
    website: '',
    phone: '',
    status: 'pending',
    logo: null,
    cover_image: null,
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
        await form.post(route('admin.affiliations.store'), {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
                processing.value = false;
            },
            onError: (err) => {
                errors.value = err;
                processing.value = false;
            },
            onFinish: () => {
                processing.value = false;
            }
        });
    } catch (e) {
        console.error('Error submitting form:', e);
        processing.value = false;
        errors.value = {
            error: 'An unexpected error occurred. Please try again.'
        };
    }
};
</script> 