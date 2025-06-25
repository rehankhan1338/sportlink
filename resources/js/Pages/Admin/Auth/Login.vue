<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <h2 class="text-2xl font-bold text-center mb-6">Admin Login</h2>

            <form @submit.prevent="submit">
                <div>
                    <label class="block font-medium text-sm text-gray-700" for="email">
                        Email
                    </label>
                    <input
                        id="email"
                        type="email"
                        class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        v-model="form.email"
                        required
                        autofocus
                    >
                    <div v-if="form.errors.email" class="text-red-600 mt-1 text-sm">{{ form.errors.email }}</div>
                </div>

                <div class="mt-4">
                    <label class="block font-medium text-sm text-gray-700" for="password">
                        Password
                    </label>
                    <input
                        id="password"
                        type="password"
                        class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                    >
                </div>

                <div class="flex items-center justify-end mt-4">
                    <button
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150 ml-4"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Log in
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import { defineComponent } from 'vue'
import { useForm } from '@inertiajs/vue3'

export default defineComponent({
    setup() {
        const form = useForm({
            email: '',
            password: '',
        })

        const submit = () => {
            form.post(route('admin.login'))
        }

        return {
            form,
            submit,
        }
    },
})
</script> 