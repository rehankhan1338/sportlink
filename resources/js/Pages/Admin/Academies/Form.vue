<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import InputError from '@/Components/InputError.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextArea from '@/Components/TextArea.vue'
import FileInput from '@/Components/FileInput.vue'
import Spinner from '@/Components/Spinner.vue'

const props = defineProps({
    academy: {
        type: Object,
        default: null
    }
})

const form = useForm({
    name: props.academy?.name ?? '',
    country: props.academy?.country ?? '',
    city: props.academy?.city ?? '',
    address: props.academy?.address ?? '',
    email: props.academy?.email ?? '',
    person_in_charge: props.academy?.person_in_charge ?? '',
    phone: props.academy?.phone ?? '',
    website: props.academy?.website ?? '',
    about: props.academy?.about ?? '',
    logo: null,
    cover: null,
})

const logoPreview = ref(props.academy?.logo_path ? `/storage/academies/${props.academy.logo_path}` : null)
const coverPreview = ref(props.academy?.cover_path ? `/storage/academies/${props.academy.cover_path}` : null)

const handleLogoChange = (e) => {
    const file = e.target.files[0]
    if (file) {
        form.logo = file
        logoPreview.value = URL.createObjectURL(file)
    }
}

const handleCoverChange = (e) => {
    const file = e.target.files[0]
    if (file) {
        form.cover = file
        coverPreview.value = URL.createObjectURL(file)
    }
}

const submit = () => {
    if (props.academy) {
        form.put(route('admin.academies.update', props.academy.id), {
            preserveScroll: true,
            preserveFiles: true,
            onSuccess: () => {
                form.reset()
                logoPreview.value = null
                coverPreview.value = null
            }
        })
    } else {
        form.post(route('admin.academies.store'), {
            preserveFiles: true,
            onSuccess: () => {
                form.reset()
                logoPreview.value = null
                coverPreview.value = null
            }
        })
    }
}
</script>

<template>
    <form @submit.prevent="submit" class="space-y-6">
        <div>
            <InputLabel for="name" value="Name" />
            <TextInput
                id="name"
                type="text"
                class="mt-1 block w-full"
                v-model="form.name"
                required
                autofocus
            />
            <InputError class="mt-2" :message="form.errors.name" />
        </div>

        <div>
            <InputLabel for="country" value="Country" />
            <TextInput
                id="country"
                type="text"
                class="mt-1 block w-full"
                v-model="form.country"
                required
            />
            <InputError class="mt-2" :message="form.errors.country" />
        </div>

        <div>
            <InputLabel for="city" value="City" />
            <TextInput
                id="city"
                type="text"
                class="mt-1 block w-full"
                v-model="form.city"
                required
            />
            <InputError class="mt-2" :message="form.errors.city" />
        </div>

        <div>
            <InputLabel for="address" value="Address" />
            <TextInput
                id="address"
                type="text"
                class="mt-1 block w-full"
                v-model="form.address"
                required
            />
            <InputError class="mt-2" :message="form.errors.address" />
        </div>

        <div>
            <InputLabel for="email" value="Email" />
            <TextInput
                id="email"
                type="email"
                class="mt-1 block w-full"
                v-model="form.email"
                required
            />
            <InputError class="mt-2" :message="form.errors.email" />
        </div>

        <div>
            <InputLabel for="person_in_charge" value="Person in Charge" />
            <TextInput
                id="person_in_charge"
                type="text"
                class="mt-1 block w-full"
                v-model="form.person_in_charge"
                required
            />
            <InputError class="mt-2" :message="form.errors.person_in_charge" />
        </div>

        <div>
            <InputLabel for="phone" value="Phone" />
            <TextInput
                id="phone"
                type="text"
                class="mt-1 block w-full"
                v-model="form.phone"
                required
            />
            <InputError class="mt-2" :message="form.errors.phone" />
        </div>

        <div>
            <InputLabel for="website" value="Website (Optional)" />
            <TextInput
                id="website"
                type="url"
                class="mt-1 block w-full"
                v-model="form.website"
            />
            <InputError class="mt-2" :message="form.errors.website" />
        </div>

        <div>
            <InputLabel for="about" value="About (Optional)" />
            <TextArea
                id="about"
                class="mt-1 block w-full"
                v-model="form.about"
                rows="4"
            />
            <InputError class="mt-2" :message="form.errors.about" />
        </div>

        <div>
            <InputLabel for="logo" value="Logo" />
            <FileInput
                id="logo"
                class="mt-1 block w-full"
                @input="handleLogoChange"
                accept="image/*"
            />
            <InputError class="mt-2" :message="form.errors.logo" />
            <div v-if="logoPreview" class="mt-2">
                <img :src="logoPreview" alt="Academy logo" class="h-20 w-20 object-cover rounded-full" />
            </div>
        </div>

        <div>
            <InputLabel for="cover" value="Cover Image" />
            <FileInput
                id="cover"
                class="mt-1 block w-full"
                @input="handleCoverChange"
                accept="image/*"
            />
            <InputError class="mt-2" :message="form.errors.cover" />
            <div v-if="coverPreview" class="mt-2">
                <img :src="coverPreview" alt="Academy cover" class="h-32 w-full object-cover rounded" />
            </div>
        </div>

        <div class="flex items-center gap-4">
            <PrimaryButton :disabled="form.processing">
                {{ academy ? 'Update' : 'Create' }}
            </PrimaryButton>
            <Spinner v-if="form.processing" />
        </div>
    </form>
</template>

 