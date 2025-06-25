<template>
    <AdminLayout :title="organization ? 'Edit Organization' : 'Create Organization'">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ organization ? 'Edit Organization' : 'Create Organizationss' }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- User Selection -->
                                <div>
                                    <InputLabel for="user_id" value="User" />
                                    <select v-model="form.user_id" id="user_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="">Select User</option>
                                        <option v-for="user in users" :key="user.id" :value="user.id">
                                            {{ user.name }} ({{ user.email }})
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.user_id" class="mt-2" />
                                </div>

                                <!-- Organization Name -->
                                <div>
                                    <InputLabel for="name" value="Organization Name" />
                                    <TextInput
                                        id="name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.name"
                                        required
                                    />
                                    <InputError :message="form.errors.name" class="mt-2" />
                                </div>

                                <!-- Company -->
                                <div>
                                    <InputLabel for="company" value="Company" />
                                    <TextInput
                                        id="company"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.company"
                                        required
                                    />
                                    <InputError :message="form.errors.company" class="mt-2" />
                                </div>

                                <!-- First Name -->
                                <div>
                                    <InputLabel for="first_name" value="First Name" />
                                    <TextInput
                                        id="first_name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.first_name"
                                        required
                                    />
                                    <InputError :message="form.errors.first_name" class="mt-2" />
                                </div>

                                <!-- Last Name -->
                                <div>
                                    <InputLabel for="last_name" value="Last Name" />
                                    <TextInput
                                        id="last_name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.last_name"
                                        required
                                    />
                                    <InputError :message="form.errors.last_name" class="mt-2" />
                                </div>

                                <!-- Email -->
                                <div>
                                    <InputLabel for="email" value="Email" />
                                    <TextInput
                                        id="email"
                                        type="email"
                                        class="mt-1 block w-full"
                                        v-model="form.email"
                                        required
                                    />
                                    <InputError :message="form.errors.email" class="mt-2" />
                                </div>

                                <!-- Phone -->
                                <div>
                                    <InputLabel for="phone" value="Phone" />
                                    <TextInput
                                        id="phone"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.phone"
                                        required
                                    />
                                    <InputError :message="form.errors.phone" class="mt-2" />
                                </div>

                                <!-- Address -->
                                <div>
                                    <InputLabel for="address" value="Address" />
                                    <TextInput
                                        id="address"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.address"
                                        required
                                    />
                                    <InputError :message="form.errors.address" class="mt-2" />
                                </div>

                                <!-- City -->
                                <div>
                                    <InputLabel for="city" value="City" />
                                    <TextInput
                                        id="city"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.city"
                                        required
                                    />
                                    <InputError :message="form.errors.city" class="mt-2" />
                                </div>

                                <!-- Zip -->
                                <div>
                                    <InputLabel for="zip" value="ZIP Code" />
                                    <TextInput
                                        id="zip"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.zip"
                                        required
                                    />
                                    <InputError :message="form.errors.zip" class="mt-2" />
                                </div>

                                <!-- Country -->
                                <div>
                                    <InputLabel for="country" value="Country" />
                                    <TextInput
                                        id="country"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.country"
                                        required
                                    />
                                    <InputError :message="form.errors.country" class="mt-2" />
                                </div>

                                <!-- Card Number -->
                                <div>
                                    <InputLabel for="card_number" value="Card Number" />
                                    <TextInput
                                        id="card_number"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.card_number"
                                    />
                                    <InputError :message="form.errors.card_number" class="mt-2" />
                                </div>
                            </div>

                            <div class="flex items-center justify-end mt-6 space-x-3">
                                <Link :href="route('admin.organizations.index')" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Cancel
                                </Link>
                                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    {{ organization ? 'Update' : 'Create' }}
                                </PrimaryButton>
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
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    organization: {
        type: Object,
        default: null
    },
    users: {
        type: Array,
        required: true
    }
});

const form = useForm({
    user_id: props.organization?.user_id ?? '',
    name: props.organization?.name ?? '',
    company: props.organization?.company ?? '',
    first_name: props.organization?.first_name ?? '',
    last_name: props.organization?.last_name ?? '',
    address: props.organization?.address ?? '',
    zip: props.organization?.zip ?? '',
    city: props.organization?.city ?? '',
    country: props.organization?.country ?? '',
    email: props.organization?.email ?? '',
    phone: props.organization?.phone ?? '',
    card_number: props.organization?.card_number ?? '',
});

const submit = () => {
    if (props.organization) {
        form.put(route('admin.organizations.update', props.organization.id));
    } else {
        form.post(route('admin.organizations.store'));
    }
};
</script> 