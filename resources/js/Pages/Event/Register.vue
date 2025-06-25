<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, nextTick, onUnmounted, onMounted, watch, computed } from 'vue';
import Footer from "@/Components/Footer.vue";
import Navbar from "@/Components/Navbar.vue";
import Placeholderimage from '@/assets/placeholder-image.jpg';
import axios from 'axios';
import { loadStripe } from '@stripe/stripe-js';

// Add date formatting function
const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

const props = defineProps({
    event: {
        type: Object,
        required: true
    },
    selectedProfile: {
        type: Object,
        required: false,
        default: null
    },
    auth: {
        type: Object,
        required: true
    }
});

const form = ref({
    event_id: props.event.id,
    email: props.auth.user.email,
    weight: '',
    age: '',
    gender: '',
    nationality: '',
    date_of_birth: '',
    phone: '',
    address: '',
    country_of_residence: '',
    height: '',
    notes: '',
    passport_image: null,
    academy_id: '',
    academy_name: '',
    payment_status: 'pending',
    profile_id: props.selectedProfile?.id || null,
    division_id: null
});

const errors = ref({});
const loading = ref(false);
const previewImage = ref(null);
const activeTab = ref('user-details');

// Add academy/club related state
const academies = ref([]);
const showNewAcademyForm = ref(false);

// Add academy search related state
const academySearch = ref('');
const filteredAcademies = ref([]);
const isCustomAcademy = ref(false);
const searchTimeout = ref(null);

// Add division-related state
const matchingDivisions = ref([]);
const selectedDivisionId = ref('');
const divisionError = ref(null);
const selectedDivision = computed(() => {
    if (!selectedDivisionId.value) return null;
    return matchingDivisions.value.find(d => d.id === selectedDivisionId.value);
});

// Initialize Stripe with publishable key
const stripePromise = loadStripe('pk_test_51LElEDDbcSSs2pkrnXeKVC4lVkzwbE5KYWf9tGslf2iU9IJo5RDRAtpPyGC6RW5XUVQ693elRhNIQBvOpe7lUsVX00bAogi4sE');

// Add function to load profile data
const loadProfileData = async () => {
    try {
        loading.value = true;
        const response = await axios.get(route('profile.details'));
        if (response.data.profile) {
            const profile = response.data.profile;
            form.value.weight = profile.weight || '';
            form.value.age = profile.age || '';
            form.value.gender = profile.gender || '';
            form.value.nationality = profile.nationality || '';
            form.value.date_of_birth = profile.date_of_birth ? profile.date_of_birth.split('T')[0] : '';
            form.value.phone = profile.phone || '';
            form.value.address = profile.address || '';
            form.value.country_of_residence = profile.country_of_residence || '';
            form.value.height = profile.height || '';
            form.value.notes = profile.notes || '';
            
            // Handle passport image from profile
            if (profile.passport_image_path) {
                // Set the preview image
                previewImage.value = `/storage/${profile.passport_image_path}`;
                
                // Fetch the image file and set it as the form value
                try {
                    const imageResponse = await fetch(`/storage/${profile.passport_image_path}`);
                    const blob = await imageResponse.blob();
                    const file = new File([blob], profile.passport_image_path.split('/').pop(), { type: blob.type });
                    form.value.passport_image = file;
                } catch (error) {
                    console.error('Error loading passport image:', error);
                }
            }
        }
    } catch (error) {
        console.error('Error loading profile data:', error);
    } finally {
        loading.value = false;
    }
};

// Load profile data when component is mounted
onMounted(() => {
    loadProfileData();
});

const genders = ['Male', 'Female'];

// List of countries for nationality and residence
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

const handleFileUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.value.passport_image = file;
        previewImage.value = URL.createObjectURL(file);
    }
};

// Clean up the preview URL when component is unmounted
onUnmounted(() => {
    if (previewImage.value) {
        URL.revokeObjectURL(previewImage.value);
    }
});

const scrollToError = async () => {
    await nextTick();
    const firstError = document.querySelector('.text-red-600');
    if (firstError) {
        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        // Add a highlight effect to the input field
        const inputField = firstError.previousElementSibling;
        if (inputField) {
            inputField.classList.add('border-red-500');
            // Remove the highlight after 2 seconds
            setTimeout(() => {
                inputField.classList.remove('border-red-500');
            }, 2000);
        }
    }
};

const submit = async () => {
    try {
        loading.value = true;
        errors.value = {};

        // Validate all required fields
        if (!form.value.email) errors.value.email = 'Email is required';
        if (!form.value.weight) errors.value.weight = 'Weight is required';
        if (!form.value.age) errors.value.age = 'Age is required';
        if (!form.value.gender) errors.value.gender = 'Gender is required';
        if (!form.value.nationality) errors.value.nationality = 'Nationality is required';
        if (!form.value.date_of_birth) errors.value.date_of_birth = 'Date of birth is required';
        if (!form.value.phone) errors.value.phone = 'Phone number is required';
        if (!form.value.country_of_residence) errors.value.country_of_residence = 'Country of residence is required';
        if (!form.value.height) errors.value.height = 'Height is required';
        if (!form.value.passport_image) errors.value.passport_image = 'Passport/Player ID image is required';
        if (!form.value.academy_id) errors.value.academy_id = 'Please select an academy/club';
        if (!form.value.payment_method) errors.value.payment_method = 'Please select a payment method';

        if (Object.keys(errors.value).length > 0) {
            loading.value = false;
            await scrollToError();
            return;
        }

        const formData = new FormData();
        // Add all form fields to FormData
        Object.keys(form.value).forEach(key => {
            if (key === 'passport_image' && form.value[key]) {
                formData.append(key, form.value[key]);
            } else {
                formData.append(key, form.value[key]);
            }
        });

        console.log('Submitting form data:', Object.fromEntries(formData));

        const response = await axios.post(route('event.register.store', { id: props.event.id }), formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        console.log('Response:', response.data);

        if (response.data.success) {
            window.location.href = route('event.show', { id: props.event.id });
        }
    } catch (error) {
        console.error('Registration failed:', error);
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors;
            await scrollToError();
        } else {
            alert(error.response?.data?.message || 'Registration failed. Please try again.');
        }
    } finally {
        loading.value = false;
    }
};

// Add function to load academies
const loadAcademies = async () => {
    try {
        // Replace with your actual API endpoint
        const response = await axios.get(route('academies.list'));
        academies.value = response.data.academies;
    } catch (error) {
        console.error('Error loading academies:', error);
    }
};

// Load academies when entries tab is active
watch(form.value.academy_id, (newAcademyId) => {
    if (newAcademyId) {
        loadAcademies();
    }
});

onMounted(async () => {
    try {
        const response = await axios.get(route('academies.list'));
        academies.value = response.data.academies;

        // Check for success message in URL params
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('success') === 'true' && urlParams.get('payment_status') === 'completed') {
            console.log('Payment successful! Your registration is now complete.');
        }
    } catch (error) {
        console.error('Error loading academies:', error);
    }
});

// Function to perform fuzzy search on academies
const fuzzySearch = (searchTerm, academy) => {
    const search = searchTerm.toLowerCase();
    const name = academy.name.toLowerCase();
    
    // Simple fuzzy matching
    let searchIndex = 0;
    for (let i = 0; i < name.length && searchIndex < search.length; i++) {
        if (name[i] === search[searchIndex]) {
            searchIndex++;
        }
    }
    return searchIndex === search.length;
};

// Function to create a new academy
const createNewAcademy = async (academyName) => {
    try {
        const response = await axios.post(route('academy.store'), {
            name: academyName,
            user_id: props.auth.user.id,
            country: form.value.country_of_residence || '',
            city: 'N/A',
            address: 'N/A',
            person_in_charge: props.auth.user.name || 'N/A',
            email: props.auth.user.email || '',
            phone: form.value.phone || 'N/A'
        });

        if (response.data.success) {
            console.log('New academy created:', response.data.academy);
            // Update the academies list
            academies.value.push(response.data.academy);
            return response.data.academy;
        }
        
        // If we get here without success, throw an error with the message
        throw new Error(response.data.message || 'Failed to create academy');
    } catch (error) {
        console.error('Error creating new academy:', error);
        // If we have validation errors, format them nicely
        if (error.response?.data?.errors) {
            const errorMessages = Object.values(error.response.data.errors)
                .flat()
                .join(', ');
            throw new Error(`Validation failed: ${errorMessages}`);
        }
        // If we have a specific error message from the server
        if (error.response?.data?.message) {
            throw new Error(error.response.data.message);
        }
        // If we have an error message from our throw above
        if (error.message) {
            throw new Error(error.message);
        }
        // Fallback error
        throw new Error('Failed to create new academy. Please try again.');
    }
};

// Enhanced academy search handler
const handleAcademySearch = () => {
    console.log('Academy search value:', academySearch.value);
    
    // Clear previous timeout
    if (searchTimeout.value) {
        clearTimeout(searchTimeout.value);
    }

    // Reset form values if search is empty
    if (academySearch.value.trim() === '') {
        filteredAcademies.value = [];
        form.value.academy_id = null;
        form.value.academy_name = '';
        isCustomAcademy.value = false;
        errors.value.academy_name = null;
        return;
    }

    // Set loading state
    searchTimeout.value = setTimeout(() => {
        const searchTerm = academySearch.value.trim();
        
        // Perform fuzzy search
        const filtered = academies.value.filter(academy => 
            fuzzySearch(searchTerm, academy)
        );

        filteredAcademies.value = filtered;
        console.log('Filtered academies:', filtered);

        // If no matches found, mark as potential new academy
        if (filtered.length === 0) {
            form.value.academy_id = null;
            form.value.academy_name = searchTerm;
            isCustomAcademy.value = true;
            console.log('Marking as potential new academy:', {
                academy_name: form.value.academy_name,
                isCustom: isCustomAcademy.value
            });
        }

        // Clear loading state
        searchTimeout.value = null;
    }, 300);
};

// Enhanced academy selection handler
const selectAcademy = (academy) => {
    console.log('Selected academy:', academy);
    academySearch.value = academy.name;
    form.value.academy_id = academy.id || 0; // Ensure we have a valid ID or 0
    form.value.academy_name = academy.name;
    filteredAcademies.value = [];
    isCustomAcademy.value = false;
    // Clear loading state
    searchTimeout.value = null;
    console.log('Selected existing academy:', {
        academy_id: form.value.academy_id,
        academy_name: form.value.academy_name,
        isCustom: isCustomAcademy.value
    });
};

// Add validation function for academy selection
const validateAcademySelection = () => {
    if (!academySearch.value.trim()) {
        errors.value.academy_name = 'Please select an existing academy or enter a new one';
        return false;
    }
    errors.value.academy_name = null;
    return true;
};

// Update validateCurrentTab function
const validateCurrentTab = () => {
    errors.value = {};
    
    if (activeTab.value === 'user-details') {
        if (!form.value.email) errors.value.email = 'Email is required';
        if (!form.value.weight) errors.value.weight = 'Weight is required';
        if (!form.value.age) errors.value.age = 'Age is required';
        if (!form.value.gender) errors.value.gender = 'Gender is required';
        if (!form.value.nationality) errors.value.nationality = 'Nationality is required';
        if (!form.value.date_of_birth) errors.value.date_of_birth = 'Date of birth is required';
        if (!form.value.phone) errors.value.phone = 'Phone number is required';
        if (!form.value.country_of_residence) errors.value.country_of_residence = 'Country of residence is required';
        if (!form.value.height) errors.value.height = 'Height is required';
        if (!form.value.passport_image) errors.value.passport_image = 'Passport/Player ID image is required';
    }

    return Object.keys(errors.value).length === 0;
};

// Update handleStripeCheckout to include division data
const handleStripeCheckout = async () => {
    try {
        loading.value = true;
        
        // Validate that we have a selected profile
        if (!props.selectedProfile || !props.selectedProfile.id) {
            throw new Error('No profile selected. Please select a profile first.');
        }

        // Validate academy information
        if (!academySearch.value.trim()) {
            throw new Error('Please select an existing academy or enter a new one');
        }

        // Validate division selection
        if (!selectedDivisionId.value) {
            throw new Error('Please select a valid division');
        }

        // First update the profile with the new details
        const profileUpdated = await updateUserProfile();
        if (!profileUpdated) {
            throw new Error('Failed to update profile');
        }

        let academyId = form.value.academy_id;
        let academyName = form.value.academy_name;

        // If this is a custom academy, create it first
        if (isCustomAcademy.value && !academyId) {
            try {
                const newAcademy = await createNewAcademy(academyName);
                academyId = newAcademy.id;
                academyName = newAcademy.name;
                // Update form values with the new academy
                form.value.academy_id = academyId;
                form.value.academy_name = academyName;
                isCustomAcademy.value = false;
            } catch (error) {
                throw new Error(`Failed to create new academy: ${error.message}`);
            }
        }

        // Log division information before sending
        console.log('Selected Division:', selectedDivision.value);

        // Prepare checkout data with explicit division_id
        const checkoutData = {
            event_id: props.event.id,
            price: eventPrice.value,
            user_type: userType.value,
            profile_id: props.selectedProfile.id,
            academy_name: academyName,
            academy_id: academyId,
            division_id: parseInt(selectedDivisionId.value) // Ensure it's a number
        };

        // Log the data being sent
        console.log('Sending checkout data:', checkoutData);

        const response = await axios.post(route('stripe.checkout'), checkoutData);
        
        if (response.data.sessionId) {
            const stripe = await stripePromise;
            const { error } = await stripe.redirectToCheckout({
                sessionId: response.data.sessionId
            });
            
            if (error) {
                throw new Error(error.message);
            }
        } else {
            throw new Error('No session ID received from server');
        }
    } catch (error) {
        console.error('Error creating checkout session:', error);
        if (error.response?.data?.message) {
            alert(`Payment Error: ${error.response.data.message}`);
        } else {
            alert(`Error: ${error.message}`);
        }
    } finally {
        loading.value = false;
    }
};

// Add watch for route changes
watch(
    () => route().params,
    (newParams) => {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('success') === 'true' && urlParams.get('payment_status') === 'completed') {
            console.log('Payment successful! Your registration is now complete.');
        }
    },
    { immediate: true }
);

onMounted(() => {
    try {
        loadAcademies();
        
        // Check URL parameters for payment success
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('success') === 'true' && urlParams.get('payment_status') === 'completed') {
            console.log('Payment successful! Your registration is now complete.');
        }
    } catch (error) {
        console.error('Error in onMounted:', error);
    }
});

// Add computed property for user type
const userType = computed(() => {
    const age = parseInt(form.value.age);
    if (isNaN(age)) return 'Unknown';
    if (age < 12) return 'Children';
    if (age < 18) return 'Minor';
    return 'Adult';
});

// Add computed property for price based on user type
const eventPrice = computed(() => {
    switch (userType.value) {
        case 'Children':
            return props.event.children_price;
        case 'Minor':
            return props.event.minor_price;
        case 'Adult':
            return props.event.adult_price;
        default:
            return 0;
    }
});

// Function to find matching divisions based on athlete details
const findMatchingDivisions = () => {
    if (!form.value.age || !form.value.weight || !form.value.gender) {
        divisionError.value = 'Please complete your personal details first';
        return [];
    }

    // Filter divisions based on athlete's details
    const matches = props.event.divisions.filter(division => {
        const age = parseInt(form.value.age);
        const weight = parseFloat(form.value.weight);
        const gender = form.value.gender.toLowerCase();

        return (
            age >= division.min_age &&
            age <= division.max_age &&
            weight >= division.min_weight &&
            weight <= division.max_weight &&
            (division.gender.toLowerCase() === gender || division.gender.toLowerCase() === 'any')
        );
    });

    matchingDivisions.value = matches;
    
    // Auto-select if only one division matches
    if (matches.length === 1) {
        selectedDivisionId.value = matches[0].id;
        divisionError.value = null;
    } else if (matches.length === 0) {
        divisionError.value = 'No matching divisions found for your profile';
        selectedDivisionId.value = '';
    } else {
        selectedDivisionId.value = ''; // Reset selection if multiple matches
    }

    return matches;
};

// Watch for changes in relevant form fields and find matching divisions
watch([
    () => form.value.age,
    () => form.value.weight,
    () => form.value.gender
], () => {
    // Always find matching divisions when user details change
    findMatchingDivisions();
}, { immediate: true });

// Update handleNext to include division validation
const handleNext = async () => {
    try {
        if (activeTab.value === 'user-details') {
            // Validate user details
            if (!validateCurrentTab()) {
                await scrollToError();
                return;
            }

            loading.value = true;
            await updateUserProfile();
            activeTab.value = 'entries';
        } else if (activeTab.value === 'entries') {
            // Check if we have an academy name
            if (!academySearch.value.trim()) {
                errors.value.academy_name = 'Please select an existing academy or enter a new one';
                await scrollToError();
                return;
            }
            activeTab.value = 'division';
            // Find matching divisions when entering the division tab
            findMatchingDivisions();
        } else if (activeTab.value === 'division') {
            // Validate division selection
            if (!selectedDivision.value) {
                divisionError.value = 'Please select a division';
                await scrollToError();
                return;
            }
            activeTab.value = 'payment';
        }
    } catch (error) {
        console.error('Error in handleNext:', error);
        errors.value.general = error.message || 'An error occurred while saving your information';
        await scrollToError();
    } finally {
        loading.value = false;
    }
};

// Update handleBack to include division tab
const handleBack = () => {
    if (activeTab.value === 'entries') {
        activeTab.value = 'user-details';
    } else if (activeTab.value === 'division') {
        activeTab.value = 'entries';
    } else if (activeTab.value === 'payment') {
        activeTab.value = 'division';
    }
};

const updateUserProfile = async () => {
    try {
        // Validate required profile data
        if (!props.selectedProfile || !props.selectedProfile.id) {
            throw new Error('No profile selected');
        }

        // Ensure all numeric fields are properly formatted
        const profileData = {
            weight: parseFloat(form.value.weight) || 0,
            age: parseInt(form.value.age) || 0,
            gender: form.value.gender,
            nationality: form.value.nationality,
            date_of_birth: form.value.date_of_birth,
            phone: form.value.phone,
            address: form.value.address || '',
            country_of_residence: form.value.country_of_residence,
            height: parseFloat(form.value.height) || 0,
            notes: form.value.notes || ''
        };

        // Validate required fields with type checking
        const requiredFields = {
            weight: { label: 'Weight', type: 'number', min: 0, max: 500 },
            age: { label: 'Age', type: 'number', min: 0, max: 150 },
            gender: { label: 'Gender', type: 'string', values: ['Male', 'Female'] },
            nationality: { label: 'Nationality', type: 'string' },
            date_of_birth: { label: 'Date of Birth', type: 'date' },
            phone: { label: 'Phone Number', type: 'string' },
            country_of_residence: { label: 'Country of Residence', type: 'string' },
            height: { label: 'Height', type: 'number', min: 0, max: 300 }
        };

        const missingFields = [];
        const invalidFields = [];

        Object.entries(requiredFields).forEach(([field, rules]) => {
            const value = profileData[field];
            
            // Check if field is missing
            if (!value && value !== 0) {
                missingFields.push(rules.label);
                return;
            }

            // Type validation
            if (rules.type === 'number') {
                if (isNaN(value)) {
                    invalidFields.push(`${rules.label} must be a number`);
                } else if (rules.min !== undefined && value < rules.min) {
                    invalidFields.push(`${rules.label} must be at least ${rules.min}`);
                } else if (rules.max !== undefined && value > rules.max) {
                    invalidFields.push(`${rules.label} must be at most ${rules.max}`);
                }
            } else if (rules.type === 'date') {
                const dateValue = new Date(value);
                if (isNaN(dateValue.getTime())) {
                    invalidFields.push(`${rules.label} must be a valid date`);
                }
                // Add validation for reasonable date range (e.g., not in the future and not too far in the past)
                const today = new Date();
                const minDate = new Date();
                minDate.setFullYear(minDate.getFullYear() - 100); // 100 years ago
                
                if (dateValue > today) {
                    invalidFields.push(`${rules.label} cannot be in the future`);
                } else if (dateValue < minDate) {
                    invalidFields.push(`${rules.label} cannot be more than 100 years ago`);
                }
            } else if (rules.type === 'string' && rules.values) {
                if (!rules.values.includes(value)) {
                    invalidFields.push(`${rules.label} must be one of: ${rules.values.join(', ')}`);
                }
            }
        });

        if (missingFields.length > 0) {
            throw new Error(`Missing required fields: ${missingFields.join(', ')}`);
        }

        if (invalidFields.length > 0) {
            throw new Error(`Invalid fields: ${invalidFields.join(', ')}`);
        }

        // Validate passport image if present
        if (form.value.passport_image) {
            const maxSize = 2 * 1024 * 1024; // 2MB
            if (form.value.passport_image.size > maxSize) {
                throw new Error('Passport image must be less than 2MB');
            }
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!allowedTypes.includes(form.value.passport_image.type)) {
                throw new Error('Passport image must be a valid image file (JPEG, PNG)');
            }
        }

        let response;
        try {
            // If there's a new passport image, add it to the form data
            if (form.value.passport_image) {
                const formData = new FormData();
                Object.keys(profileData).forEach(key => {
                    formData.append(key, profileData[key]);
                });
                formData.append('passport_image', form.value.passport_image);
                
                response = await axios.post(route('profile.details.update'), formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Accept': 'application/json'
                    }
                });
            } else {
                // If no new image, just update the profile data
                response = await axios.post(route('profile.details.update'), profileData, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
            }

            if (!response.data) {
                throw new Error('No response data received from server');
            }

            if (!response.data.success) {
                throw new Error(response.data.message || 'Profile update failed');
            }

            return true;
        } catch (error) {
            if (error.response?.status === 422) {
                // Validation error from server
                const errors = error.response.data.errors;
                const errorMessages = Object.entries(errors)
                    .map(([field, messages]) => `${field}: ${messages.join(', ')}`)
                    .join('; ');
                throw new Error(errorMessages);
            }
            throw error;
        }
    } catch (error) {
        console.error('Error updating profile:', error);
        if (error.response?.data?.errors) {
            const errorMessages = Object.values(error.response.data.errors).flat();
            throw new Error(errorMessages.join(', '));
        } else if (error.response?.data?.message) {
            throw new Error(error.response.data.message);
        } else if (error.message) {
            throw new Error(error.message);
        } else {
            throw new Error('Failed to update profile');
        }
    }
};

</script>

<template>
    <Head :title="`Register for ${event.title}`" />
    <Navbar />
    
    <div class="min-h-screen bg-[#171719]">
        <!-- Event Image Banner -->
        <div class="relative h-64 w-full">
            <img 
                :src="event.image ? `/storage/${event.image}` : Placeholderimage" 
                alt="Event Banner" 
                class="w-full h-full object-cover banner-image-regsiterpage"
            >
            <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                <h1 class="text-xl font-bold text-white">Become a Member</h1>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-4 py-12">
            <!-- Event Info -->
            <div class="bg-[#212121] rounded-lg p-6 mb-8">
                <h2 class="text-2xl font-bold text-white mb-4">Register for {{ event.title }}</h2>
                <div class="text-gray-400">
                    <p>Date: {{ formatDate(event.start_date) }} - {{ formatDate(event.end_date) }}</p>
                </div>
            </div>

            <!-- Tabs Navigation -->
            <div class="bg-[#212121] rounded-lg mb-8">
                <div class="flex border-b border-gray-700">
                    <button 
                        class="px-6 py-3 text-white font-medium"
                        :class="{ 
                            'border-b-2 border-blue-500': activeTab === 'user-details',
                            'opacity-75': activeTab !== 'user-details'
                        }"
                    >
                        User Details
                    </button>
                    <button 
                        class="px-6 py-3 text-white font-medium"
                        :class="{ 
                            'border-b-2 border-blue-500': activeTab === 'entries',
                            'opacity-75': activeTab !== 'entries'
                        }"
                    >
                        Entries
                    </button>
                    <button 
                        class="px-6 py-3 text-white font-medium"
                        :class="{ 
                            'border-b-2 border-blue-500': activeTab === 'division',
                            'opacity-75': activeTab !== 'division'
                        }"
                    >
                        Division
                    </button>
                    <button 
                        class="px-6 py-3 text-white font-medium"
                        :class="{ 
                            'border-b-2 border-blue-500': activeTab === 'payment',
                            'opacity-75': activeTab !== 'payment'
                        }"
                    >
                        Payment
                    </button>
                </div>
            </div>

            <!-- General Error Message -->
            <div v-if="errors.general" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <span class="block sm:inline">{{ errors.general }}</span>
            </div>

            <!-- Tab Content -->
            <div class="bg-[#212121] rounded-lg p-6">
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- User Details Tab -->
                    <div v-if="activeTab === 'user-details'">
                        <h2 class="text-xl font-semibold text-white mb-6">Personal Information</h2>
                        
                        <!-- Profile Info -->
                        <div class="mb-6 p-4 bg-[#2a2a2a] rounded-lg">
                            <h3 class="text-lg font-medium text-white mb-2">Selected Profile</h3>
                            <p class="text-gray-400">{{ selectedProfile.first_name }} {{ selectedProfile.last_name }}</p>
                        </div>

                        <!-- User Details Section -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-white mb-4">Personal Information</h3>
                            <!-- Email -->
                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input disabled type="email" id="email" v-model="form.email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" :class="{ 'border-red-500': errors.email }" />
                                <span v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email }}</span>
                            </div>

                            <!-- Weight -->
                            <div class="mb-4">
                                <label for="weight" class="block text-sm font-medium text-gray-700">Weight (kg) <span class="text-red-500">*</span></label>
                                <span v-if="errors.weight" class="mt-1 text-sm text-red-600">{{ errors.weight }}</span>
                                <input type="number" id="weight" v-model="form.weight" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" :class="{ 'border-red-500': errors.weight }" />
                            </div>

                            <!-- Height -->
                            <div class="mb-4">
                                <label for="height" class="block text-sm font-medium text-gray-700">Height (cm) <span class="text-red-500">*</span></label>
                                <span v-if="errors.height" class="mt-1 text-sm text-red-600">{{ errors.height }}</span>
                                <input type="number" id="height" v-model="form.height" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" :class="{ 'border-red-500': errors.height }" />
                            </div>

                            <!-- Date of Birth -->
                            <div class="mb-4">
                                <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth <span class="text-red-500">*</span></label>
                                <span v-if="errors.date_of_birth" class="mt-1 text-sm text-red-600">{{ errors.date_of_birth }}</span>
                                <input type="date" id="date_of_birth" v-model="form.date_of_birth" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" :class="{ 'border-red-500': errors.date_of_birth }" />
                            </div>

                            <!-- Age -->
                            <div class="mb-4">
                                <label for="age" class="block text-sm font-medium text-gray-700">Age <span class="text-red-500">*</span></label>
                                <span v-if="errors.age" class="mt-1 text-sm text-red-600">{{ errors.age }}</span>
                                <input type="number" id="age" v-model="form.age" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" :class="{ 'border-red-500': errors.age }" />
                            </div>

                            <!-- Gender -->
                            <div class="mb-4">
                                <label for="gender" class="block text-sm font-medium text-gray-700">Gender <span class="text-red-500">*</span></label>
                                <span v-if="errors.gender" class="mt-1 text-sm text-red-600">{{ errors.gender }}</span>
                                <select id="gender" v-model="form.gender" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" :class="{ 'border-red-500': errors.gender }">
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>

                            <!-- Nationality -->
                            <div class="mb-4">
                                <label for="nationality" class="block text-sm font-medium text-gray-700">Nationality <span class="text-red-500">*</span></label>
                                <span v-if="errors.nationality" class="mt-1 text-sm text-red-600">{{ errors.nationality }}</span>
                                <select
                                    id="nationality"
                                    v-model="form.nationality"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    :class="{ 'border-red-500': errors.nationality }"
                                >
                                    <option value="" disabled selected>Select your nationality</option>
                                    <option v-for="country in countries" :key="country" :value="country">
                                        {{ country }}
                                    </option>
                                </select>
                            </div>

                            <!-- Country of Residence -->
                            <div class="mb-4">
                                <label for="country_of_residence" class="block text-sm font-medium text-gray-700">Country of Residence <span class="text-red-500">*</span></label>
                                <span v-if="errors.country_of_residence" class="mt-1 text-sm text-red-600">{{ errors.country_of_residence }}</span>
                                <select
                                    id="country_of_residence"
                                    v-model="form.country_of_residence"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    :class="{ 'border-red-500': errors.country_of_residence }"
                                >
                                    <option value="" disabled selected>Select your country of residence</option>
                                    <option v-for="country in countries" :key="country" :value="country">
                                        {{ country }}
                                    </option>
                                </select>
                            </div>

                            <!-- Phone -->
                            <div class="mb-4">
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number <span class="text-red-500">*</span></label>
                                <span v-if="errors.phone" class="mt-1 text-sm text-red-600">{{ errors.phone }}</span>
                                <input type="tel" id="phone" v-model="form.phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" :class="{ 'border-red-500': errors.phone }" />
                            </div>

                            <!-- Address -->
                            <div class="mb-4">
                                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                                <textarea id="address" v-model="form.address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                            </div>

                            <!-- Passport/Player ID Image -->
                            <div class="mb-4">
                                <label for="passport_image" class="block text-sm font-medium text-gray-700">Passport/Player ID Image <span class="text-red-500">*</span></label>
                                <span v-if="errors.passport_image" class="mt-1 text-sm text-red-600">{{ errors.passport_image }}</span>
                                <input 
                                    type="file" 
                                    id="passport_image" 
                                    @change="handleFileUpload" 
                                    accept="image/*" 
                                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" 
                                    :class="{ 'border-red-500': errors.passport_image }" 
                                />
                                <div v-if="previewImage" class="mt-2">
                                    <img :src="previewImage" alt="Preview" class="h-32 w-32 object-cover rounded-lg" />
                                    <p class="text-sm text-gray-400 mt-1">Current passport image</p>
                                </div>
                            </div>

                            <!-- Notes -->
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-300 mb-1">Additional Notes</label>
                                <textarea
                                    id="notes"
                                    v-model="form.notes"
                                    rows="3"
                                    class="w-full px-4 py-2 bg-[#2a2a2a] border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                ></textarea>
                            </div>

                            <!-- Next Button -->
                            <div class="flex justify-end mt-6">
                                <button
                                    type="button"
                                    @click="handleNext"
                                    class="px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                    Next
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Entries Tab -->
                    <div v-if="activeTab === 'entries'">
                        <h2 class="text-xl font-semibold text-white mb-6">Academy/Club Information</h2>
                        
                        <div class="space-y-6">
                            <!-- Academy Selection -->
                            <div class="mb-4">
                                <label for="academy" class="block text-sm font-medium text-gray-700 text-white">Select or Enter Academy/Club Name <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input
                                        type="text"
                                        id="academy"
                                        v-model="academySearch"
                                        @input="handleAcademySearch"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-[#2a2a2a] text-white"
                                        :class="{ 'border-red-500': errors.academy_id || errors.academy_name }"
                                        placeholder="Search for an academy or enter a new one"
                                    />
                                    <!-- Loading indicator -->
                                    <div v-if="searchTimeout" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                        <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                    <!-- Dropdown for academy suggestions -->
                                    <div v-if="filteredAcademies.length > 0 && !isCustomAcademy" 
                                         class="absolute z-10 w-full mt-1 bg-[#2a2a2a] rounded-md shadow-lg border border-gray-600">
                                        <ul class="max-h-60 overflow-auto rounded-md py-1 text-base">
                                            <li
                                                v-for="academy in filteredAcademies"
                                                :key="academy.id"
                                                @click="selectAcademy(academy)"
                                                class="cursor-pointer px-4 py-2 text-white hover:bg-[#3a3a3a]"
                                            >
                                                {{ academy.name }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- Error message -->
                                <span v-if="errors.academy_id || errors.academy_name" class="mt-1 text-sm text-red-600">
                                    {{ errors.academy_id || errors.academy_name }}
                                </span>
                                <!-- Status message -->
                                <p class="mt-2 text-sm" :class="isCustomAcademy ? 'text-yellow-400' : 'text-gray-400'">
                                    {{ isCustomAcademy 
                                        ? `Creating new academy: ${academySearch}` 
                                        : 'Search for an existing academy or type a new name' 
                                    }}
                                </p>
                            </div>

                            <div class="flex justify-between items-center">
                                <Link
                                    v-if="$page.props.auth.user"
                                    :href="route('academy.register', { event_id: event.id })"
                                    class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                    Register New Academy
                                </Link>
                                <Link
                                    v-else
                                    :href="route('login')"
                                    class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                    Login to Register Academy
                                </Link>
                            </div>

                            <!-- Navigation Buttons -->
                            <div class="flex justify-between mt-6">
                                <button
                                    type="button"
                                    @click="handleBack"
                                    class="px-6 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500"
                                >
                                    Back
                                </button>
                                <button
                                    type="button"
                                    @click="handleNext"
                                    class="px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                    Next
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Division Tab -->
                    <div v-if="activeTab === 'division'">
                        <h2 class="text-xl font-semibold text-white mb-6">Division Information</h2>
                        
                        <div class="space-y-6">
                            <!-- Division Selection -->
                            <div class="mb-4">
                                <label for="division" class="block text-sm font-medium text-gray-700 text-white">Select Division <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <select
                                        id="division"
                                        v-model="selectedDivisionId"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-[#2a2a2a] text-white"
                                        :class="{ 'border-red-500': errors.division }"
                                    >
                                        <option value="" disabled selected>Select a division</option>
                                        <option v-for="division in matchingDivisions" :key="division.id" :value="division.id">
                                            {{ division.name }} ({{ division.min_age }}-{{ division.max_age }} years, {{ division.min_weight }}-{{ division.max_weight }} kg)
                                        </option>
                                    </select>
                                </div>
                                <!-- Error message -->
                                <span v-if="divisionError" class="mt-1 text-sm text-red-600">
                                    {{ divisionError }}
                                </span>
                            </div>

                            <!-- Navigation Buttons -->
                            <div class="flex justify-between mt-6">
                                <button
                                    type="button"
                                    @click="handleBack"
                                    class="px-6 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500"
                                >
                                    Back
                                </button>
                                <button
                                    type="button"
                                    @click="handleNext"
                                    class="px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                    Next
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Tab -->
                    <div v-if="activeTab === 'payment'">
                        <h2 class="text-xl font-semibold text-white mb-6">Payment Information</h2>
                        
                        <div class="bg-[#2a2a2a] rounded-lg p-6 mb-6">
                            <h3 class="text-lg font-medium text-white mb-4">Event Details</h3>
                            <div class="text-gray-400">
                                <p class="mb-2">Event: {{ event.title }}</p>
                                
                                <p class="mb-2">Price: ${{ eventPrice }}</p>
                            </div>
                        </div>

                        <div class="bg-[#2a2a2a] rounded-lg p-6 mb-6">
                            <h3 class="text-lg font-medium text-white mb-4">Payment Method</h3>
                            <p class="text-gray-400 mb-4">We accept payments through Stripe. Click the button below to proceed with payment.</p>
                            <button
                                @click="handleStripeCheckout"
                                class="w-full px-6 py-3 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                :disabled="loading"
                            >
                                {{ loading ? 'Processing...' : 'Proceed to Payment' }}
                            </button>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="flex justify-between mt-6">
                            <button
                                type="button"
                                @click="handleBack"
                                class="px-6 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500"
                            >
                                Back
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <Footer />
</template> 

<style scoped>
.banner-image-regsiterpage {
    max-height: 200px;
}

.error-message {
    display: block;
    margin-top: 0.25rem;
}
label{
    color: white;
}

/* Add styles for tabs */
.tab-button {
    transition: all 0.3s ease;
}

.tab-button:hover {
    background-color: rgba(255, 255, 255, 0.1);
}
</style>