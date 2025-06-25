<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, nextTick, watch } from 'vue';
import Footer from "@/Components/Footer.vue";
import Navbar from "@/Components/Navbar.vue";
import SuccessMessage from '@/Components/SuccessMessage.vue';
import axios from 'axios';

const props = defineProps({
    event_id: {
        type: [String, Number],
        default: null
    },
    auth: {
        type: Object,
        required: true
    }
});

const form = ref({
    name: '',
    country: '',
    city: '',
    address: '',
    latitude: '',
    longitude: '',
    person_in_charge: '',
    email: '',
    phone: '',
    website: '',
    about: '',
    logo: null,
    cover_image: null,
    affiliation: '',
    affiliations: [] // This will be populated from your backend
});

const errors = ref({});
const loading = ref(false);
const showSuccess = ref(false);
const successMessage = ref('');
const previewLogo = ref(null);
const previewCover = ref(null);
const mapLoaded = ref(false);
const map = ref(null);
const marker = ref(null);
const autocomplete = ref(null);
const addressInput = ref(null);

// List of countries (you can move this to a separate file)
const countries = [
    'Afghanistan', 'Albania', 'Algeria', 'Andorra', 'Angola', 'Antigua and Barbuda', 'Argentina', 'Armenia', 
    'Australia', 'Austria', 'Azerbaijan', 'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 
    'Belize', 'Benin', 'Bhutan', 'Bolivia', 'Bosnia and Herzegovina', 'Botswana', 'Brazil', 'Brunei', 'Bulgaria', 
    'Burkina Faso', 'Burundi', 'Cabo Verde', 'Cambodia', 'Cameroon', 'Canada', 'Central African Republic', 'Chad', 
    'Chile', 'China', 'Colombia', 'Comoros', 'Congo', 'Costa Rica', 'Croatia', 'Cuba', 'Cyprus', 'Czech Republic', 
    'Denmark', 'Djibouti', 'Dominica', 'Dominican Republic', 'Ecuador', 'Egypt', 'El Salvador', 'Equatorial Guinea', 
    'Eritrea', 'Estonia', 'Eswatini', 'Ethiopia', 'Fiji', 'Finland', 'France', 'Gabon', 'Gambia', 'Georgia', 'Germany', 
    'Ghana', 'Greece', 'Grenada', 'Guatemala', 'Guinea', 'Guinea-Bissau', 'Guyana', 'Haiti', 'Honduras', 'Hungary', 
    'Iceland', 'India', 'Indonesia', 'Iran', 'Iraq', 'Ireland', 'Israel', 'Italy', 'Jamaica', 'Japan', 'Jordan', 
    'Kazakhstan', 'Kenya', 'Kiribati', 'Korea, North', 'Korea, South', 'Kosovo', 'Kuwait', 'Kyrgyzstan', 'Laos', 
    'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libya', 'Liechtenstein', 'Lithuania', 'Luxembourg', 'Madagascar', 
    'Malawi', 'Malaysia', 'Maldives', 'Mali', 'Malta', 'Marshall Islands', 'Mauritania', 'Mauritius', 'Mexico', 
    'Micronesia', 'Moldova', 'Monaco', 'Mongolia', 'Montenegro', 'Morocco', 'Mozambique', 'Myanmar', 'Namibia', 
    'Nauru', 'Nepal', 'Netherlands', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'North Macedonia', 'Norway', 
    'Oman', 'Pakistan', 'Palau', 'Palestine', 'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines', 
    'Poland', 'Portugal', 'Qatar', 'Romania', 'Russia', 'Rwanda', 'Saint Kitts and Nevis', 'Saint Lucia', 
    'Saint Vincent and the Grenadines', 'Samoa', 'San Marino', 'Sao Tome and Principe', 'Saudi Arabia', 'Senegal', 
    'Serbia', 'Seychelles', 'Sierra Leone', 'Singapore', 'Slovakia', 'Slovenia', 'Solomon Islands', 'Somalia', 
    'South Africa', 'South Sudan', 'Spain', 'Sri Lanka', 'Sudan', 'Suriname', 'Sweden', 'Switzerland', 'Syria', 
    'Taiwan', 'Tajikistan', 'Tanzania', 'Thailand', 'Timor-Leste', 'Togo', 'Tonga', 'Trinidad and Tobago', 'Tunisia', 
    'Turkey', 'Turkmenistan', 'Tuvalu', 'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom', 'United States', 
    'Uruguay', 'Uzbekistan', 'Vanuatu', 'Vatican City', 'Venezuela', 'Vietnam', 'Yemen', 'Zambia', 'Zimbabwe'
];

onMounted(async () => {
    if (!props.auth.user) {
        window.location.href = route('login');
        return;
    }

    try {
        loading.value = true;
        const response = await axios.get(route('affiliations.list'));
        form.value.affiliations = response.data.affiliations;
        
        // Initialize map after component is mounted
        await loadGoogleMapsScript();
    } catch (error) {
        console.error('Error loading affiliations:', error);
        errors.value.general = 'Failed to load affiliations. Please try again.';
    } finally {
        loading.value = false;
    }
});

const handleLogoUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.value.logo = file;
        previewLogo.value = URL.createObjectURL(file);
    }
};

const handleCoverUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.value.cover_image = file;
        previewCover.value = URL.createObjectURL(file);
    }
};

// Initialize Google Maps when component is mounted
onMounted(() => {
    console.log('Component mounted, loading Google Maps...');
    loadGoogleMapsScript();
});

const loadGoogleMapsScript = () => {
    return new Promise((resolve, reject) => {
        console.log('Loading Google Maps script...');
        const script = document.createElement('script');
        script.src = `https://maps.googleapis.com/maps/api/js?key=${window.googleMapsApiKey}&libraries=places`;
        script.async = true;
        script.defer = true;
        
        script.onload = () => {
            console.log('Google Maps script loaded');
            mapLoaded.value = true;
            nextTick(() => {
                initMap();
                initAutocomplete();
                resolve();
            });
        };
        
        script.onerror = (error) => {
            console.error('Error loading Google Maps script:', error);
            reject(error);
        };
        
        document.head.appendChild(script);
    });
};

const initMap = () => {
    console.log('Initializing map...');
    const mapElement = document.getElementById('map');
    if (!mapElement) {
        console.error('Map element not found');
        return;
    }

    try {
        map.value = new google.maps.Map(mapElement, {
            center: { lat: 0, lng: 0 },
            zoom: 2,
            styles: [
                {
                    "featureType": "all",
                    "elementType": "all"
                }
            ]
        });

        marker.value = new google.maps.Marker({
            map: map.value,
            draggable: true
        });

        console.log('Map initialized successfully');
    } catch (error) {
        console.error('Error initializing map:', error);
    }
};

const initAutocomplete = () => {
    console.log('Initializing autocomplete...');
    if (!google || !google.maps || !google.maps.places) {
        console.error('Google Maps Places API not loaded');
        return;
    }

    const input = document.getElementById('address');
    if (!input) {
        console.error('Address input element not found');
        return;
    }

    try {
        // Create the autocomplete object
        autocomplete.value = new google.maps.places.Autocomplete(input, {
            types: ['address'],
            componentRestrictions: { country: form.value.country || undefined },
            fields: ['address_components', 'geometry', 'formatted_address', 'name']
        });

        // Add listener for place selection
        autocomplete.value.addListener('place_changed', () => {
            console.log('Place changed event triggered');
            const place = autocomplete.value.getPlace();
            console.log('Selected place:', place);

            if (!place.geometry) {
                console.log('No geometry found for selected place');
                return;
            }

            // Update form values
            form.value.address = place.formatted_address;
            form.value.latitude = place.geometry.location.lat();
            form.value.longitude = place.geometry.location.lng();

            // Update map
            if (map.value && marker.value) {
                map.value.setCenter(place.geometry.location);
                marker.value.setPosition(place.geometry.location);
                map.value.setZoom(15);
            }
        });

        console.log('Autocomplete initialized successfully');
    } catch (error) {
        console.error('Error initializing autocomplete:', error);
    }
};

// Watch for country changes to update autocomplete restrictions
watch(() => form.value.country, (newCountry) => {
    if (autocomplete.value) {
        autocomplete.value.setComponentRestrictions({
            country: newCountry || undefined
        });
    }
});

const submit = async () => {
    try {
        loading.value = true;
        errors.value = {};

        // Validate required fields
        if (!form.value.name) errors.value.name = 'Name is required';
        if (!form.value.country) errors.value.country = 'Country is required';
        if (!form.value.city) errors.value.city = 'City is required';
        if (!form.value.address) errors.value.address = 'Address is required';
        if (!form.value.person_in_charge) errors.value.person_in_charge = 'Person in charge is required';
        if (!form.value.email) errors.value.email = 'Email is required';
        if (!form.value.phone) errors.value.phone = 'Phone number is required';

        // Email validation
        if (form.value.email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.value.email)) {
            errors.value.email = 'Please enter a valid email address';
        }

        // Phone validation (basic format)
        if (form.value.phone && !/^\+?[\d\s-]{8,}$/.test(form.value.phone)) {
            errors.value.phone = 'Please enter a valid phone number';
        }

        // Website validation if provided
        if (form.value.website && !/^https?:\/\/.+/.test(form.value.website)) {
            errors.value.website = 'Please enter a valid website URL';
        }

        // Check if there are any validation errors
        if (Object.keys(errors.value).length > 0) {
            loading.value = false;
            // Scroll to the first error
            await nextTick();
            const firstError = document.querySelector('.text-red-600');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            return;
        }

        const formData = new FormData();
        Object.keys(form.value).forEach(key => {
            if (form.value[key] !== null) {
                formData.append(key, form.value[key]);
            }
        });

        const response = await axios.post(route('academy.store'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        if (response.data.success) {
            // Redirect to club finder with success parameters
            window.location.href = route('club.finder') + '?success=true&message=Academy registered successfully!';
        }
    } catch (error) {
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors;
            // Scroll to the first error
            await nextTick();
            const firstError = document.querySelector('.text-red-600');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        } else {
            showSuccess.value = false;
            successMessage.value = error.response?.data?.message || 'Failed to register academy. Please try again.';
        }
    } finally {
        loading.value = false;
    }
};
</script>

<template>
    <Head title="Register Academy" />
    <Navbar />
    
    <div v-if="loading" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-4 rounded-lg shadow-lg">
            <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500 mx-auto"></div>
            <p class="mt-4 text-center">Loading...</p>
        </div>
    </div>

    <div v-if="errors.general" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-4 mx-auto max-w-7xl" role="alert">
        <strong class="font-bold">Error!</strong>
        <span class="block sm:inline">{{ errors.general }}</span>
    </div>

    <div class="min-h-screen bg-[#171719]">
        <!-- Success Message Component -->
        <SuccessMessage 
            v-if="showSuccess" 
            :message="successMessage" 
            :duration="5000"
        />
        
        <div class="max-w-4xl mx-auto px-4 py-12">
            <div class="bg-[#212121] rounded-lg p-6">
                <h2 class="text-2xl font-bold text-white mb-6">Register New Academy</h2>
                
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Name -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-white">Name <span class="text-red-500">*</span></label>
                        <span v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</span>
                        <input type="text" id="name" v-model="form.name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-[#2a2a2a] text-white" :class="{ 'border-red-500': errors.name }" />
                    </div>

                    <!-- Country -->
                    <div class="mb-4">
                        <label for="country" class="block text-sm font-medium text-white">Country <span class="text-red-500">*</span></label>
                        <span v-if="errors.country" class="mt-1 text-sm text-red-600">{{ errors.country }}</span>
                        <select id="country" v-model="form.country" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-[#2a2a2a] text-white" :class="{ 'border-red-500': errors.country }">
                            <option value="">Select Country</option>
                            <option v-for="country in countries" :key="country" :value="country">{{ country }}</option>
                        </select>
                    </div>

                    <!-- City -->
                    <div class="mb-4">
                        <label for="city" class="block text-sm font-medium text-white">City <span class="text-red-500">*</span></label>
                        <span v-if="errors.city" class="mt-1 text-sm text-red-600">{{ errors.city }}</span>
                        <input type="text" id="city" v-model="form.city" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-[#2a2a2a] text-white" :class="{ 'border-red-500': errors.city }" />
                    </div>

                    <!-- Address with Google Places Autocomplete -->
                    <div class="mb-4">
                        <label for="address" class="block text-sm font-medium text-white">Address <span class="text-red-500">*</span></label>
                        <span v-if="errors.address" class="mt-1 text-sm text-red-600">{{ errors.address }}</span>
                        <div class="relative">
                            <input 
                                type="text" 
                                id="address" 
                                v-model="form.address" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-[#2a2a2a] text-white" 
                                :class="{ 'border-red-500': errors.address }"
                                placeholder="Type to search for an address..."
                                autocomplete="off"
                                ref="addressInput"
                            />
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Map Preview -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-white mb-2">Location Preview</label>
                        <div id="map" class="w-full h-64 rounded-lg bg-[#2a2a2a] border border-gray-600"></div>
                    </div>

                    <!-- Person in Charge -->
                    <div class="mb-4">
                        <label for="person_in_charge" class="block text-sm font-medium text-white">Person in Charge <span class="text-red-500">*</span></label>
                        <span v-if="errors.person_in_charge" class="mt-1 text-sm text-red-600">{{ errors.person_in_charge }}</span>
                        <input type="text" id="person_in_charge" v-model="form.person_in_charge" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-[#2a2a2a] text-white" :class="{ 'border-red-500': errors.person_in_charge }" />
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-white">Email <span class="text-red-500">*</span></label>
                        <span v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email }}</span>
                        <input type="email" id="email" v-model="form.email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-[#2a2a2a] text-white" :class="{ 'border-red-500': errors.email }" />
                    </div>

                    <!-- Phone -->
                    <div class="mb-4">
                        <label for="phone" class="block text-sm font-medium text-white">Phone <span class="text-red-500">*</span></label>
                        <span v-if="errors.phone" class="mt-1 text-sm text-red-600">{{ errors.phone }}</span>
                        <input type="tel" id="phone" v-model="form.phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-[#2a2a2a] text-white" :class="{ 'border-red-500': errors.phone }" />
                    </div>

                    <!-- Website -->
                    <div class="mb-4">
                        <label for="website" class="block text-sm font-medium text-white">Website</label>
                        <span v-if="errors.website" class="mt-1 text-sm text-red-600">{{ errors.website }}</span>
                        <input type="url" id="website" v-model="form.website" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-[#2a2a2a] text-white" :class="{ 'border-red-500': errors.website }" />
                    </div>

                    <!-- About -->
                    <div class="mb-4">
                        <label for="about" class="block text-sm font-medium text-white">About</label>
                        <span v-if="errors.about" class="mt-1 text-sm text-red-600">{{ errors.about }}</span>
                        <textarea id="about" v-model="form.about" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-[#2a2a2a] text-white" :class="{ 'border-red-500': errors.about }"></textarea>
                    </div>

                    <!-- Logo -->
                    <div class="mb-4">
                        <label for="logo" class="block text-sm font-medium text-white">Logo</label>
                        <span v-if="errors.logo" class="mt-1 text-sm text-red-600">{{ errors.logo }}</span>
                        <input type="file" id="logo" @change="handleLogoUpload" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" :class="{ 'border-red-500': errors.logo }" />
                        <div v-if="previewLogo" class="mt-2">
                            <img :src="previewLogo" alt="Logo Preview" class="h-32 w-32 object-contain rounded-lg" />
                        </div>
                    </div>

                    <!-- Cover -->
                    <div class="mb-4">
                        <label for="cover_image" class="block text-sm font-medium text-white">Cover Image</label>
                        <span v-if="errors.cover_image" class="mt-1 text-sm text-red-600">{{ errors.cover_image }}</span>
                        <input type="file" id="cover_image" @change="handleCoverUpload" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" :class="{ 'border-red-500': errors.cover_image }" />
                        <div v-if="previewCover" class="mt-2">
                            <img :src="previewCover" alt="Cover Preview" class="h-32 w-full object-cover rounded-lg" />
                        </div>
                    </div>

                    <!-- Affiliation -->
                    <div class="mb-4">
                        <label for="affiliation" class="block text-sm font-medium text-white">Add affiliation/team</label>
                        <span v-if="errors.affiliation" class="mt-1 text-sm text-red-600">{{ errors.affiliation }}</span>
                        <select id="affiliation" v-model="form.affiliation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-[#2a2a2a] text-white" :class="{ 'border-red-500': errors.affiliation }">
                            <option value="">Select Affiliation</option>
                            <option v-for="affiliation in form.affiliations" :key="affiliation.id" :value="affiliation.id">
                                {{ affiliation.name }}
                            </option>
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500" :disabled="loading">
                            {{ loading ? 'Processing...' : 'Register Academy' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <Footer />
</template>

<style scoped>
input[type="text"],
input[type="email"],
input[type="tel"],
input[type="url"],
select,
textarea {
    background-color: #2a2a2a;
    color: white;
    border-color: #4a4a4a;
}

input[type="text"]:focus,
input[type="email"]:focus,
input[type="tel"]:focus,
input[type="url"]:focus,
select:focus,
textarea:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
}

.file-input {
    color: white;
}

.file-input::file-selector-button {
    background-color: #3b82f6;
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    cursor: pointer;
}

.file-input::file-selector-button:hover {
    background-color: #2563eb;
}

#map {
    min-height: 256px;
    width: 100%;
    background-color: #2a2a2a;
    border: 1px solid #4a4a4a;
    border-radius: 0.5rem;
}

/* Google Places Autocomplete Styles */
.pac-container {
    background-color: #2a2a2a !important;
    border: 1px solid #4a4a4a !important;
    border-radius: 0.5rem !important;
    margin-top: 0.5rem !important;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
    z-index: 1000 !important;
}

.pac-item {
    color: white !important;
    padding: 0.5rem 1rem !important;
    cursor: pointer !important;
    font-size: 14px !important;
}

.pac-item:hover {
    background-color: #3a3a3a !important;
}

.pac-item-query {
    color: white !important;
    font-size: 14px !important;
}

.pac-matched {
    color: #3b82f6 !important;
    font-weight: bold !important;
}

.pac-icon {
    filter: invert(1) !important;
}
</style> 