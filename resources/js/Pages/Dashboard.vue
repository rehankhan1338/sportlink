<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import Footer from "@/Components/Footer.vue";
import Navbar from "@/Components/Navbar.vue";
import { Link } from '@inertiajs/vue3';
import { ref, computed, watch, reactive, onMounted, nextTick, onUnmounted } from 'vue';
import { citiesByCountry } from '@/data/cities';
import { timezones } from '@/data/timezones';
import RichTextEditor from '@/Components/RichTextEditor.vue';
import SuccessMessage from '@/Components/SuccessMessage.vue';
import Toast from '@/Components/Toast.vue';
import axios from 'axios';

const showModal = ref(false);
const showEditModal = ref(false);
const editingEvent = ref(null);
const image = ref(null);
const previewImage = ref(null);
const activeComponent = ref('events');
const formErrors = ref({});
const showSuccess = ref(false);
const successMessage = ref('');
const showToast = ref(false);
const toastMessage = ref('');
const toastType = ref('success');

// Organization form state
const organization = reactive({
  name: '',
  company: '',
  firstName: '',
  lastName: '',
  address: '',
  zip: '',
  city: '',
  country: '',
  email: '',
  phone: ''
});

const stripeDetails = reactive({
  accountType: '',
  businessName: '',
  businessTaxId: '',
  accountHolderName: '',
  accountNumber: '',
  routingNumber: '',
  bankAccountType: '',
  address: {
    line1: '',
    city: '',
    state: '',
    postal_code: '',
    country: ''
  },
  email: '',
  phone: ''
});

const stripeFormErrors = ref({});

const userDetails = reactive({
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
  passport_image: null
});

const userDetailsErrors = ref({});
const userDetailsLoading = ref(false);
const userDetailsPreviewImage = ref(null);

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

const props = defineProps({
  events: Array,
  organization: Object
});

const page = usePage();

// Initialize organization data when component mounts
onMounted(() => {
  if (props.organization) {
    const org = props.organization;
    organization.name = org.name || '';
    organization.company = org.company || '';
    organization.firstName = org.first_name || '';
    organization.lastName = org.last_name || '';
    organization.address = org.address || '';
    organization.zip = org.zip || '';
    organization.city = org.city || '';
    organization.country = org.country || '';
    organization.email = org.email || '';
    organization.phone = org.phone || '';
  }

  // Fetch Stripe details if billing component is active
  if (activeComponent.value === 'billing') {
    fetchStripeDetails();
  }
});

// Update organization function
const updateOrganization = async () => {
  try {
    const csrfToken = document
      .querySelector('meta[name="csrf-token"]')
      ?.getAttribute('content');

    const response = await fetch('/organization/update', {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
      },
      credentials: 'include',
      body: JSON.stringify({
        organization: {
          name: organization.name,
          company: organization.company,
          first_name: organization.firstName,
          last_name: organization.lastName,
          address: organization.address,
          zip: organization.zip,
          city: organization.city,
          country: organization.country,
          email: organization.email,
          phone: organization.phone
        }
      }),
    });

    const result = await response.json();

    if (response.ok) {
      showSuccess.value = true;
      successMessage.value = 'Organization updated successfully!';
    } else {
      console.error('Failed:', result);
    }
  } catch (error) {
    console.error('Error updating organization:', error);
  }
};

// Countries list
const countriesList = ref([
  'Afghanistan',
  'Albania',
  'Algeria',
  'Andorra',
  'Angola',
  'Antigua and Barbuda',
  'Argentina',
  'Armenia',
  'Australia',
  'Austria',
  'Azerbaijan',
  'Bahamas',
  'Bahrain',
  'Bangladesh',
  'Barbados',
  'Belarus',
  'Belgium',
  'Belize',
  'Benin',
  'Bhutan',
  'Bolivia',
  'Bosnia and Herzegovina',
  'Botswana',
  'Brazil',
  'Brunei',
  'Bulgaria',
  'Burkina Faso',
  'Burundi',
  'Cabo Verde',
  'Cambodia',
  'Cameroon',
  'Canada',
  'Central African Republic',
  'Chad',
  'Chile',
  'China',
  'Colombia',
  'Comoros',
  'Congo',
  'Costa Rica',
  'Croatia',
  'Cuba',
  'Cyprus',
  'Czech Republic',
  'Denmark',
  'Djibouti',
  'Dominica',
  'Dominican Republic',
  'Ecuador',
  'Egypt',
  'El Salvador',
  'Equatorial Guinea',
  'Eritrea',
  'Estonia',
  'Eswatini',
  'Ethiopia',
  'Fiji',
  'Finland',
  'France',
  'Gabon',
  'Gambia',
  'Georgia',
  'Germany',
  'Ghana',
  'Greece',
  'Grenada',
  'Guatemala',
  'Guinea',
  'Guinea-Bissau',
  'Guyana',
  'Haiti',
  'Honduras',
  'Hungary',
  'Iceland',
  'India',
  'Indonesia',
  'Iran',
  'Iraq',
  'Ireland',
  'Israel',
  'Italy',
  'Jamaica',
  'Japan',
  'Jordan',
  'Kazakhstan',
  'Kenya',
  'Kiribati',
  'Korea, North',
  'Korea, South',
  'Kosovo',
  'Kuwait',
  'Kyrgyzstan',
  'Laos',
  'Latvia',
  'Lebanon',
  'Lesotho',
  'Liberia',
  'Libya',
  'Liechtenstein',
  'Lithuania',
  'Luxembourg',
  'Madagascar',
  'Malawi',
  'Malaysia',
  'Maldives',
  'Mali',
  'Malta',
  'Marshall Islands',
  'Mauritania',
  'Mauritius',
  'Mexico',
  'Micronesia',
  'Moldova',
  'Monaco',
  'Mongolia',
  'Montenegro',
  'Morocco',
  'Mozambique',
  'Myanmar',
  'Namibia',
  'Nauru',
  'Nepal',
  'Netherlands',
  'New Zealand',
  'Nicaragua',
  'Niger',
  'Nigeria',
  'North Macedonia',
  'Norway',
  'Oman',
  'Pakistan',
  'Palau',
  'Panama',
  'Papua New Guinea',
  'Paraguay',
  'Peru',
  'Philippines',
  'Poland',
  'Portugal',
  'Qatar',
  'Romania',
  'Russia',
  'Rwanda',
  'Saint Kitts and Nevis',
  'Saint Lucia',
  'Saint Vincent and the Grenadines',
  'Samoa',
  'San Marino',
  'Sao Tome and Principe',
  'Saudi Arabia',
  'Senegal',
  'Serbia',
  'Seychelles',
  'Sierra Leone',
  'Singapore',
  'Slovakia',
  'Slovenia',
  'Solomon Islands',
  'Somalia',
  'South Africa',
  'South Sudan',
  'Spain',
  'Sri Lanka',
  'Sudan',
  'Suriname',
  'Sweden',
  'Switzerland',
  'Syria',
  'Taiwan',
  'Tajikistan',
  'Tanzania',
  'Thailand',
  'Timor-Leste',
  'Togo',
  'Tonga',
  'Trinidad and Tobago',
  'Tunisia',
  'Turkey',
  'Turkmenistan',
  'Tuvalu',
  'Uganda',
  'Ukraine',
  'United Arab Emirates',
  'United Kingdom',
  'United States',
  'Uruguay',
  'Uzbekistan',
  'Vanuatu',
  'Vatican City',
  'Venezuela',
  'Vietnam',
  'Yemen',
  'Zambia',
  'Zimbabwe'
]);

// Form fields
const title = ref('');
const city = ref('');
const country = ref('');
const startDate = ref('');
const endDate = ref('');
const lastRegistrationDate = ref('');
const timezone = ref('');
const status = ref('');
const visibility = ref('');
const description = ref('');
const adultPrice = ref('');
const minorPrice = ref('');
const childrenPrice = ref('');

const searchTitle = ref('');
const filterStatus = ref('');
const filterVisibility = ref('');

// Computed property for available cities based on selected country
const availableCities = computed(() => {
  return country.value ? citiesByCountry[country.value] || [] : [];
});

// Watch for country changes to reset city if needed
watch(country, (newCountry) => {
  if (!availableCities.value.includes(city.value)) {
    city.value = '';
  }
});

// Modal functions
const openModal = () => { 
  showModal.value = true;
  hasAttemptedDivisionTab.value = false;
  activeTab.value = 'event-details';
  // Reset form fields when opening modal
  title.value = '';
  city.value = '';
  country.value = '';
  startDate.value = '';
  endDate.value = '';
  timezone.value = '';
  status.value = '';
  visibility.value = '';
  description.value = '';
  adultPrice.value = '';
  minorPrice.value = '';
  childrenPrice.value = '';
  image.value = null;
  previewImage.value = null;
};

const closeModal = () => { 
  showModal.value = false;
  // Reset form fields when closing modal
  title.value = '';
  city.value = '';
  country.value = '';
  startDate.value = '';
  endDate.value = '';
  lastRegistrationDate.value = '';
  timezone.value = '';
  status.value = '';
  visibility.value = '';
  description.value = '';
  adultPrice.value = '';
  minorPrice.value = '';
  childrenPrice.value = '';
  image.value = null;
  previewImage.value = null;
};

const openEditModal = (event) => {
  editingEvent.value = event;
  hasAttemptedDivisionTab.value = false;
  title.value = event.title;
  city.value = event.location;
  country.value = event.country;
  startDate.value = event.start_date.slice(0, 10);
  endDate.value = event.end_date.slice(0, 10);
  lastRegistrationDate.value = event.last_date_of_registration ? event.last_date_of_registration.slice(0, 10) : '';
  timezone.value = event.timezone;
  status.value = event.status;
  visibility.value = event.visibility;
  description.value = event.description || '';
  adultPrice.value = event.adult_price || '';
  minorPrice.value = event.minor_price || '';
  childrenPrice.value = event.children_price || '';
  previewImage.value = event.image ? `/storage/${event.image}` : null;
  image.value = null;
  
  // Load divisions data
  if (event.divisions && Array.isArray(event.divisions)) {
    divisions.value = event.divisions.map(division => ({
      id: division.id, // Add this line to preserve division ID
      name: division.name,
      gender: division.gender,
      min_age: division.min_age,
      max_age: division.max_age,
      min_weight: division.min_weight,
      max_weight: division.max_weight,
      belt_level: division.belt_level,
      bracket_type: division.bracket_type,
      match_duration_min: division.match_duration_min,
      start_time: division.start_time || '',
      mat_number: division.mat_number || ''
    }));
  } else {
    divisions.value = [];
  }
  
  showEditModal.value = true;
};

const closeEditModal = () => {
  showEditModal.value = false;
  editingEvent.value = null;
  // Reset form fields
  title.value = '';
  city.value = '';
  country.value = '';
  startDate.value = '';
  endDate.value = '';
  timezone.value = '';
  status.value = '';
  visibility.value = '';
  description.value = '';
  adultPrice.value = '';
  minorPrice.value = '';
  childrenPrice.value = '';
  image.value = null;
  previewImage.value = null;
};

const handleImageChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    image.value = file;
    previewImage.value = URL.createObjectURL(file);
  }
};

// Function to scroll to first error
const scrollToFirstError = () => {
  const modalContent = document.querySelector('.modal-content');
  const firstError = document.querySelector('.error-message');
  if (firstError && modalContent) {
    modalContent.scrollTo({
      top: firstError.offsetTop - 20,
      behavior: 'smooth'
    });
  }
};

// Add these new refs for division management
const activeTab = ref('event-details');
const showDivisionModal = ref(false);
const divisions = ref([]);

// Add new reactive object for division form
const divisionForm = reactive({
  name: '',
  gender: '',
  min_age: '',
  max_age: '',
  min_weight: '',
  max_weight: '',
  belt_level: '',
  bracket_type: '',
  match_duration_min: '',
  start_time: '',
  mat_number: ''
});

// Add this for division form validation
const divisionFormErrors = ref({});

// Add new methods for division management
const addDivision = () => {
  showDivisionModal.value = true;
};

// Add the validation function
const validateDivisionForm = () => {
  divisionFormErrors.value = {};
  let isValid = true;

  if (!divisionForm.name) {
    divisionFormErrors.value.name = 'Division name is required';
    isValid = false;
  }
  if (!divisionForm.gender) {
    divisionFormErrors.value.gender = 'Gender is required';
    isValid = false;
  }
  if (!divisionForm.min_age) {
    divisionFormErrors.value.min_age = 'Minimum age is required';
    isValid = false;
  }
  if (!divisionForm.max_age) {
    divisionFormErrors.value.max_age = 'Maximum age is required';
    isValid = false;
  }
  if (!divisionForm.min_weight) {
    divisionFormErrors.value.min_weight = 'Minimum weight is required';
    isValid = false;
  }
  if (!divisionForm.max_weight) {
    divisionFormErrors.value.max_weight = 'Maximum weight is required';
    isValid = false;
  }
  if (!divisionForm.bracket_type) {
    divisionFormErrors.value.bracket_type = 'Bracket type is required';
    isValid = false;
  }
  if (!divisionForm.match_duration_min) {
    divisionFormErrors.value.match_duration_min = 'Match duration is required';
    isValid = false;
  }

  // Additional numeric validations
  if (divisionForm.min_age && divisionForm.max_age) {
    if (Number(divisionForm.min_age) >= Number(divisionForm.max_age)) {
      divisionFormErrors.value.max_age = 'Maximum age must be greater than minimum age';
      isValid = false;
    }
  }

  if (divisionForm.min_weight && divisionForm.max_weight) {
    if (Number(divisionForm.min_weight) >= Number(divisionForm.max_weight)) {
      divisionFormErrors.value.max_weight = 'Maximum weight must be greater than minimum weight';
      isValid = false;
    }
  }

  return isValid;
};

// Modify the saveDivision function
const saveDivision = () => {
  if (!validateDivisionForm()) {
    toastMessage.value = 'Please fill in all required fields for the division';
    toastType.value = 'error';
    showToast.value = true;
    return;
  }

  // Create a new division object with all the form data
  const newDivision = {
    ...divisionForm,
    id: null // New divisions will have null ID, existing ones will keep their ID
  };

  divisions.value.push(newDivision);
  
  // Reset form
  Object.keys(divisionForm).forEach(key => {
    divisionForm[key] = '';
  });
  divisionFormErrors.value = {};
  showDivisionModal.value = false;
};

const removeDivision = (index) => {
  divisions.value.splice(index, 1);
};

// Add this validation function
const validateEventDetails = () => {
  formErrors.value = {};
  let isValid = true;

  if (!title.value) {
    formErrors.value.title = 'Event name is required';
    isValid = false;
  }
  if (!country.value) {
    formErrors.value.country = 'Country is required';
    isValid = false;
  }
  if (!city.value) {
    formErrors.value.city = 'City is required';
    isValid = false;
  }
  if (!startDate.value) {
    formErrors.value.startDate = 'Start date is required';
    isValid = false;
  }
  if (!endDate.value) {
    formErrors.value.endDate = 'End date is required';
    isValid = false;
  }
  if (!lastRegistrationDate.value) {
    formErrors.value.lastRegistrationDate = 'Last registration date is required';
    isValid = false;
  }
  if (!timezone.value) {
    formErrors.value.timezone = 'Timezone is required';
    isValid = false;
  }
  if (!status.value) {
    formErrors.value.status = 'Status is required';
    isValid = false;
  }
  if (!visibility.value) {
    formErrors.value.visibility = 'Visibility is required';
    isValid = false;
  }
  if (!adultPrice.value) {
    formErrors.value.adultPrice = 'Adult price is required';
    isValid = false;
  }
  if (!minorPrice.value) {
    formErrors.value.minorPrice = 'Minor price is required';
    isValid = false;
  }
  if (!childrenPrice.value) {
    formErrors.value.childrenPrice = 'Children price is required';
    isValid = false;
  }

  // Validate last registration date is after start date and before end date
  if (lastRegistrationDate.value && startDate.value && endDate.value) {
    const lastRegDate = new Date(lastRegistrationDate.value);
    const eventStartDate = new Date(startDate.value);
    const eventEndDate = new Date(endDate.value);

    if (lastRegDate < eventStartDate) {
      formErrors.value.lastRegistrationDate = 'Last registration date must be after the event start date';
      isValid = false;
    } else if (lastRegDate > eventEndDate) {
      formErrors.value.lastRegistrationDate = 'Last registration date must be before the event end date';
      isValid = false;
    }
  }

  if (!isValid) {
    toastMessage.value = 'Please fill in all required fields before proceeding to Divisions';
    toastType.value = 'error';
    showToast.value = true;
  }

  return isValid;
};

// Modify the button click handlers
const goToDivisionsTab = () => {
  hasAttemptedDivisionTab.value = true;
  if (validateEventDetails()) {
    activeTab.value = 'divisions';
  } else {
    // Wait for Vue to update the DOM
    nextTick(() => {
      scrollToFirstError();
    });
  }
};

const goToEventDetailsTab = () => {
  activeTab.value = 'event-details';
};

// Modify createEvent method to include divisions
const createEvent = () => {
  // Reset errors
  formErrors.value = {};

  // Validate required fields
  if (!title.value) formErrors.value.title = 'Event name is required';
  if (!country.value) formErrors.value.country = 'Country is required';
  if (!city.value) formErrors.value.city = 'City is required';
  if (!startDate.value) formErrors.value.startDate = 'Start date is required';
  if (!endDate.value) formErrors.value.endDate = 'End date is required';
  if (!lastRegistrationDate.value) formErrors.value.lastRegistrationDate = 'Last registration date is required';
  if (!timezone.value) formErrors.value.timezone = 'Timezone is required';
  if (!status.value) formErrors.value.status = 'Status is required';
  if (!visibility.value) formErrors.value.visibility = 'Visibility is required';
  if (!adultPrice.value) formErrors.value.adultPrice = 'Adult price is required';
  if (!minorPrice.value) formErrors.value.minorPrice = 'Minor price is required';
  if (!childrenPrice.value) formErrors.value.childrenPrice = 'Children price is required';

  // Validate that at least one division exists
  if (divisions.value.length === 0) {
    formErrors.value.divisions = 'At least one division is required';
    toastMessage.value = 'Please add at least one division before creating the event';
    toastType.value = 'error';
    showToast.value = true;
    activeTab.value = 'divisions'; // Switch to divisions tab
    return;
  }

  // If there are errors, don't submit
  if (Object.keys(formErrors.value).length > 0) {
    nextTick(() => {
      scrollToFirstError();
    });
    return;
  }

  const formData = new FormData();
  formData.append('title', title.value);
  formData.append('location', city.value);
  formData.append('country', country.value);
  formData.append('start_date', startDate.value);
  formData.append('end_date', endDate.value);
  formData.append('last_date_of_registration', lastRegistrationDate.value);
  formData.append('timezone', timezone.value);
  formData.append('status', status.value);
  formData.append('visibility', visibility.value);
  formData.append('description', description.value);
  formData.append('adult_price', adultPrice.value);
  formData.append('minor_price', minorPrice.value);
  formData.append('children_price', childrenPrice.value);
  formData.append('divisions', JSON.stringify(divisions.value));
  if (image.value) {
    formData.append('image', image.value);
  }

  router.post(route('events.store'), formData, {
    forceFormData: true,
    onSuccess: () => {
      closeModal();
      // Reset all form fields
      title.value = '';
      city.value = '';
      country.value = '';
      startDate.value = '';
      endDate.value = '';
      timezone.value = '';
      status.value = '';
      visibility.value = '';
      description.value = '';
      adultPrice.value = '';
      minorPrice.value = '';
      childrenPrice.value = '';
      image.value = null;
      previewImage.value = null;
      divisions.value = [];
      formErrors.value = {};
      
      // Show success toast
      toastMessage.value = 'Event created successfully!';
      toastType.value = 'success';
      showToast.value = true;
    },
    onError: (errors) => {
      console.error('Error creating event:', errors);
      formErrors.value = errors;
      
      // Show error toast with first error message
      const firstError = Object.values(errors)[0];
      toastMessage.value = firstError;
      toastType.value = 'error';
      showToast.value = true;
      
      nextTick(() => {
        scrollToFirstError();
      });
    }
  });
};

const updateEvent = () => {
  // Reset errors
  formErrors.value = {};

  // Validate required fields
  if (!title.value) formErrors.value.title = 'Event name is required';
  if (!country.value) formErrors.value.country = 'Country is required';
  if (!city.value) formErrors.value.city = 'City is required';
  if (!startDate.value) formErrors.value.startDate = 'Start date is required';
  if (!endDate.value) formErrors.value.endDate = 'End date is required';
  if (!lastRegistrationDate.value) formErrors.value.lastRegistrationDate = 'Last registration date is required';
  if (!timezone.value) formErrors.value.timezone = 'Timezone is required';
  if (!status.value) formErrors.value.status = 'Status is required';
  if (!visibility.value) formErrors.value.visibility = 'Visibility is required';
  if (!adultPrice.value) formErrors.value.adultPrice = 'Adult price is required';
  if (!minorPrice.value) formErrors.value.minorPrice = 'Minor price is required';
  if (!childrenPrice.value) formErrors.value.childrenPrice = 'Children price is required';

  // Validate that at least one division exists
  if (divisions.value.length === 0) {
    formErrors.value.divisions = 'At least one division is required';
    toastMessage.value = 'Please add at least one division before updating the event';
    toastType.value = 'error';
    showToast.value = true;
    activeTab.value = 'divisions'; // Switch to divisions tab
    return;
  }

  // Validate last registration date is after start date
  if (lastRegistrationDate.value && startDate.value) {
    const lastRegDate = new Date(lastRegistrationDate.value);
    const eventStartDate = new Date(startDate.value);
    if (lastRegDate < eventStartDate) {
      formErrors.value.lastRegistrationDate = 'Last registration date must be after the event start date';
    }
  }

  // If there are errors, don't submit
  if (Object.keys(formErrors.value).length > 0) {
    // Wait for Vue to update the DOM
    nextTick(() => {
      scrollToFirstError();
    });
    return;
  }

  // Log the divisions data before sending
  console.log('Divisions data before update:', divisions.value);

  const formData = new FormData();
  formData.append('_method', 'PUT');
  formData.append('title', title.value);
  formData.append('location', city.value);
  formData.append('country', country.value);
  formData.append('start_date', startDate.value);
  formData.append('end_date', endDate.value);
  formData.append('last_date_of_registration', lastRegistrationDate.value);
  formData.append('timezone', timezone.value);
  formData.append('status', status.value);
  formData.append('visibility', visibility.value);
  formData.append('description', description.value || '');
  formData.append('adult_price', adultPrice.value);
  formData.append('minor_price', minorPrice.value);
  formData.append('children_price', childrenPrice.value);
  
  // Ensure all division fields are properly formatted
  const divisionsData = divisions.value.map(division => ({
    id: division.id || null, // Preserve existing division IDs
    name: division.name,
    gender: division.gender,
    min_age: Number(division.min_age),
    max_age: Number(division.max_age),
    min_weight: Number(division.min_weight),
    max_weight: Number(division.max_weight),
    belt_level: division.belt_level,
    bracket_type: division.bracket_type,
    match_duration_min: Number(division.match_duration_min),
    start_time: division.start_time || null,
    mat_number: division.mat_number || null
  }));
  
  formData.append('divisions', JSON.stringify(divisionsData));
  
  if (image.value) {
    formData.append('image', image.value);
  }

  router.post(route('events.update', { id: editingEvent.value.id }), formData, {
    forceFormData: true,
    onSuccess: () => {
      closeEditModal();
      formErrors.value = {};
      
      // Show success toast
      toastMessage.value = 'Event updated successfully!';
      toastType.value = 'success';
      showToast.value = true;
    },
    onError: (errors) => {
      console.error('Error updating event:', errors);
      formErrors.value = errors;
      
      // Show error toast with first error message
      const firstError = Object.values(errors)[0];
      toastMessage.value = firstError;
      toastType.value = 'error';
      showToast.value = true;
      
      nextTick(() => {
        scrollToFirstError();
      });
    }
  });
};

const filteredEvents = computed(() => {
  return props.events.filter((event) => {
    const matchesTitle = event.title.toLowerCase().includes(searchTitle.value.toLowerCase());
    const matchesStatus = filterStatus.value ? event.status === filterStatus.value : true;
    const matchesVisibility = filterVisibility.value ? event.visibility === filterVisibility.value : true;

    return matchesTitle && matchesStatus && matchesVisibility;
  });
});

const deleteEvent = (id) => {
  if (confirm('Are you sure you want to delete this event?')) {
    router.delete(route('events.destroy', { id }), {
      preserveScroll: true,
      onSuccess: () => {
        // No need to reload as Inertia will handle the page refresh
      },
      onError: (errors) => {
        console.error('Error deleting event:', errors);
      }
    });
  }
};

// Function to scroll to first error
const scrollToFirstStripeError = () => {
  const modalContent = document.querySelector('.modal-content');
  const firstError = document.querySelector('.error-message');
  if (firstError && modalContent) {
    modalContent.scrollTo({
      top: firstError.offsetTop - 20,
      behavior: 'smooth'
    });
  }
};

const saveStripeDetails = async () => {
  try {
    stripeFormErrors.value = {};
    const csrfToken = document
      .querySelector('meta[name="csrf-token"]')
      ?.getAttribute('content');

    const response = await fetch('/organization/stripe-details', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
      },
      credentials: 'include',
      body: JSON.stringify({
        stripe_details: {
          account_type: stripeDetails.accountType,
          business_name: stripeDetails.businessName,
          business_tax_id: stripeDetails.businessTaxId,
          account_holder_name: stripeDetails.accountHolderName,
          account_number: stripeDetails.accountNumber,
          routing_number: stripeDetails.routingNumber,
          bank_account_type: stripeDetails.bankAccountType,
          address: {
            line1: stripeDetails.address.line1,
            city: stripeDetails.address.city,
            state: stripeDetails.address.state,
            postal_code: stripeDetails.address.postal_code,
            country: stripeDetails.address.country
          },
          email: stripeDetails.email,
          phone: stripeDetails.phone
        }
      }),
    });

    const result = await response.json();

    if (response.ok) {
      alert('Stripe payment details saved successfully!');
    } else {
      if (result.errors) {
        // Transform the errors object to match the expected format
        const transformedErrors = {};
        Object.keys(result.errors).forEach(key => {
          transformedErrors[key] = Array.isArray(result.errors[key]) 
            ? result.errors[key] 
            : [result.errors[key]];
        });
        stripeFormErrors.value = transformedErrors;
        
        // Wait for Vue to update the DOM
        nextTick(() => {
          scrollToFirstStripeError();
        });
      } else {
        console.error('Failed:', result);
        alert('Failed to save payment details. Please try again.');
      }
    }
  } catch (error) {
    console.error('Error saving Stripe details:', error);
    alert('An error occurred while saving payment details. Please try again.');
  }
};

// Add a function to check if a field has an error
const hasError = (field) => {
  return stripeFormErrors.value[field] && stripeFormErrors.value[field].length > 0;
};

// Add a function to get error message
const getErrorMessage = (field) => {
  return hasError(field) ? stripeFormErrors.value[field][0] : '';
};

// Add function to fetch Stripe details
const fetchStripeDetails = async () => {
  try {
    const response = await fetch('/organization/stripe-details', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
      },
      credentials: 'include',
    });

    if (response.ok) {
      const data = await response.json();
      if (data.stripe_details) {
        // Update the form fields with existing data
        stripeDetails.accountType = data.stripe_details.account_type || '';
        stripeDetails.businessName = data.stripe_details.business_name || '';
        stripeDetails.businessTaxId = data.stripe_details.business_tax_id || '';
        stripeDetails.accountHolderName = data.stripe_details.account_holder_name || '';
        stripeDetails.accountNumber = data.stripe_details.account_number || '';
        stripeDetails.routingNumber = data.stripe_details.routing_number || '';
        stripeDetails.bankAccountType = data.stripe_details.bank_account_type || '';
        stripeDetails.address.line1 = data.stripe_details.address?.line1 || '';
        stripeDetails.address.city = data.stripe_details.address?.city || '';
        stripeDetails.address.state = data.stripe_details.address?.state || '';
        stripeDetails.address.postal_code = data.stripe_details.address?.postal_code || '';
        stripeDetails.address.country = data.stripe_details.address?.country || '';
        stripeDetails.email = data.stripe_details.email || '';
        stripeDetails.phone = data.stripe_details.phone || '';
      }
    }
  } catch (error) {
    console.error('Error fetching Stripe details:', error);
  }
};

// Update onMounted to fetch Stripe details when billing component is active
watch(activeComponent, (newComponent) => {
  if (newComponent === 'billing') {
    fetchStripeDetails();
  }
});

// Add function to load profile data
const loadProfileData = async () => {
    try {
        console.log('Starting to load profile data');
        const page = usePage();
        console.log('Selected profile from session:', page.props.selectedProfile);
        userDetailsLoading.value = true;
        const response = await axios.get(route('profile.details'));
        console.log('Profile data response:', response.data);
        if (response.data.profile) {
            const profile = response.data.profile;
            userDetails.weight = profile.weight || '';
            userDetails.age = profile.age || '';
            userDetails.gender = profile.gender || '';
            userDetails.nationality = profile.nationality || '';
            userDetails.date_of_birth = profile.date_of_birth ? profile.date_of_birth.split('T')[0] : '';
            userDetails.phone = profile.phone || '';
            userDetails.address = profile.address || '';
            userDetails.country_of_residence = profile.country_of_residence || '';
            userDetails.height = profile.height || '';
            userDetails.notes = profile.notes || '';
            if (profile.passport_image_path) {
                userDetailsPreviewImage.value = `/storage/${profile.passport_image_path}`;
            } else {
                userDetailsPreviewImage.value = null;
            }
        } else {
            console.log('No profile data found in response');
            // Show error toast
            toastMessage.value = 'No profile data found. Please make sure you have selected a profile.';
            toastType.value = 'error';
            showToast.value = true;
            // Redirect to profile selection if no profile is selected
            if (!page.props.selectedProfile) {
                window.location.href = route('select-profile');
            }
        }
    } catch (error) {
        console.error('Error loading profile data:', error.response || error);
        // Show error toast with more specific message
        toastMessage.value = error.response?.data?.message || 'Failed to load profile data. Please try again.';
        toastType.value = 'error';
        showToast.value = true;
        // Redirect to profile selection if error is due to no selected profile
        if (error.response?.status === 422 && error.response?.data?.message?.includes('No profile selected')) {
            window.location.href = route('select-profile');
        }
    } finally {
        userDetailsLoading.value = false;
    }
};

// Load profile data when component is mounted and user-details is active
watch(activeComponent, (newComponent) => {
  console.log('Active component changed to:', newComponent);
  if (newComponent === 'user-details') {
    console.log('Loading profile data...');
    loadProfileData();
  }
});

const handleUserDetailsFileUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        userDetails.passport_image = file;
        if (userDetailsPreviewImage.value) {
            URL.revokeObjectURL(userDetailsPreviewImage.value);
        }
        userDetailsPreviewImage.value = URL.createObjectURL(file);
    } else {
        userDetails.passport_image = null;
        if (userDetailsPreviewImage.value) {
            URL.revokeObjectURL(userDetailsPreviewImage.value);
        }
        userDetailsPreviewImage.value = null;
    }
};

const saveUserDetails = async () => {
    try {
        userDetailsLoading.value = true;
        userDetailsErrors.value = {};

        // Validate required fields
        if (!userDetails.weight) userDetailsErrors.value.weight = 'Weight is required';
        if (!userDetails.age) userDetailsErrors.value.age = 'Age is required';
        if (!userDetails.gender) userDetailsErrors.value.gender = 'Gender is required';
        if (!userDetails.nationality) userDetailsErrors.value.nationality = 'Nationality is required';
        if (!userDetails.date_of_birth) userDetailsErrors.value.date_of_birth = 'Date of birth is required';
        if (!userDetails.phone) userDetailsErrors.value.phone = 'Phone is required';
        if (!userDetails.address) userDetailsErrors.value.address = 'Address is required';
        if (!userDetails.country_of_residence) userDetailsErrors.value.country_of_residence = 'Country of residence is required';
        if (!userDetails.height) userDetailsErrors.value.height = 'Height is required';
        
        // Only require image if there's no existing image
        if (!userDetailsPreviewImage.value) {
            userDetailsErrors.value.passport_image = 'Passport image is required';
        }

        if (Object.keys(userDetailsErrors.value).length > 0) {
            userDetailsLoading.value = false;
            return;
        }

        const formData = new FormData();
        formData.append('weight', userDetails.weight);
        formData.append('age', userDetails.age);
        formData.append('gender', userDetails.gender);
        formData.append('nationality', userDetails.nationality);
        formData.append('date_of_birth', userDetails.date_of_birth);
        formData.append('phone', userDetails.phone);
        formData.append('address', userDetails.address);
        formData.append('country_of_residence', userDetails.country_of_residence);
        formData.append('height', userDetails.height);
        formData.append('notes', userDetails.notes || '');
        
        // Only append new image if one was selected
        if (userDetails.passport_image) {
            formData.append('passport_image', userDetails.passport_image);
        }

        const response = await axios.post(route('profile.details.update'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
                'Accept': 'application/json'
            }
        });

        if (response.data.success) {
            showSuccess.value = true;
            successMessage.value = 'Profile details saved successfully!';
            // Reload profile data to get updated values
            await loadProfileData();
        }
    } catch (error) {
        console.error('Failed to save profile details:', error);
        if (error.response?.data?.errors) {
            userDetailsErrors.value = error.response.data.errors;
        } else if (error.response?.data?.message) {
            alert(error.response.data.message);
        } else {
            alert('Failed to save profile details. Please try again.');
        }
    } finally {
        userDetailsLoading.value = false;
    }
};

// Clean up any object URLs when component is unmounted
onUnmounted(() => {
    if (userDetailsPreviewImage.value) {
        URL.revokeObjectURL(userDetailsPreviewImage.value);
    }
});

// Update the watch on activeTab
watch(activeTab, (newTab) => {
  if (newTab === 'divisions' && hasAttemptedDivisionTab.value) {
    if (!validateEventDetails()) {
      activeTab.value = 'event-details';
      nextTick(() => {
        scrollToFirstError();
      });
    }
  }
});

// Add this computed property for tab styling
const isDivisionsTabDisabled = computed(() => {
  // Only check validation if we've attempted to go to divisions tab
  if (!hasAttemptedDivisionTab.value) {
    return false;
  }
  return !validateEventDetails();
});

// Add this ref for tracking attempts to go to divisions tab
const hasAttemptedDivisionTab = ref(false);

</script>

<template>
    <Navbar />
    <Head title="Dashboard" />

    <div class="py-12" style="background-color: rgb(28, 28, 28);">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 h-full">
            <div class="overflow-hidden h-full" style="align-content: center;">
                <div class="capitalize text-center text-[35px] font-bold p-6 text-gray-900 text-white">
                    {{ $page.props.auth.user.name }}
                </div>

                <!-- Success Message Component -->
                <SuccessMessage 
                    v-if="showSuccess" 
                    :message="successMessage" 
                    :duration="5000"
                />

                <Link :href="route('profile.edit')" style="border: 1px solid;
       border: 1px solid;
    padding: 5px;
    border-radius: 6px;
    display: block;
    margin: 0px auto;
    width: fit-content;" class="text-white text-center">
                            Settings
                            </Link>


                             <div class="mt-5 dashboard-container">
    <!-- Top Navigation Bar -->
    <div class="nav-bar">
      <button class="nav-item">
        <span class="icon">👤</span>
        Account
      </button>
      <button class="nav-item">
        <span class="icon">👥</span>
        Coach manager
      </button>
      <button class="nav-item">
        <span class="icon">🏆</span>
        Organizer manager
      </button>
      <button class="nav-item">
        <span class="icon">🔗</span>
        Affiliation manager
      </button>
    </div>

    <!-- Main Title -->
    <h1 class="main-title">
  <template v-if="$page.props.organization?.name">
    <span class="mb-5 block capitalize">{{ $page.props.organization.name }}</span>


       <!-- Cards Container -->
       <div class="cards-container">
      <!-- Current Balance Card -->
      <div class="card">
        <div class="card-icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="2" y="5" width="20" height="14" rx="2" />
            <line x1="2" y1="10" x2="22" y2="10" />
          </svg>
        </div>
        <h2 class="card-title">CURRENT BALANCE</h2>
        <p class="card-value">20 credits</p>
        <p class="card-subtitle">1 credit = 1 athlete</p>
        <button class="action-button">Buy credits</button>
      </div>

      <!-- Auto Refill Card -->
      <div class="card">
        <div class="card-icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="2" y="5" width="20" height="14" rx="2" />
            <circle cx="18" cy="9" r="3" />
            <path d="M18 9v4" />
          </svg>
        </div>
        <h2 class="card-title">AUTO REFILL: <span class="off-text">OFF</span></h2>
        <p class="card-description">Auto refill my balance with 0 credits before it's empty</p>
        <button class="action-button">Configure</button>
      </div>

      <!-- Billing Notification Card -->
      <div class="card">
        <div class="card-icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="2" y="4" width="20" height="16" rx="2" />
            <path d="M22 7l-10 7L2 7" />
          </svg>
        </div>
        <h2 class="card-title">BILLING NOTIFICATION</h2>
        <p class="card-description">
          We'll email you when you reach 5 credits. When you reach 0, new registrations will remain unapproved until you refill your balance.
        </p>
      </div>
    </div>
  </template>
  <template v-else>
    <Link :href="route('Organization')"
  class="text-[16px] bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
>
  Create Organization
</Link>

  </template>
</h1>


  </div>


  <div class="events-container" id="events-container">
    <!-- Left Sidebar -->
    <div class="sidebar">
      <div 
        class="sidebar-item" 
        :class="{ active: activeComponent === 'events' }"
        @click="activeComponent = 'events'"
      >
        Events
      </div>
      <div 
        class="sidebar-item" 
        :class="{ active: activeComponent === 'settings' }"
        @click="activeComponent = 'settings'"
      >
        Settings
      </div>
      <div 
        class="sidebar-item" 
        :class="{ active: activeComponent === 'user-details' }"
        @click="activeComponent = 'user-details'"
      >
        User details
      </div>
      <div 
        class="sidebar-item" 
        :class="{ active: activeComponent === 'credits' }"
        @click="activeComponent = 'credits'"
      >
        Credit history
      </div>
      <div 
        class="sidebar-item" 
        :class="{ active: activeComponent === 'messages' }"
        @click="activeComponent = 'messages'"
      >
        Message center
      </div>
      <div 
        class="sidebar-item" 
        :class="{ active: activeComponent === 'coupons' }"
        @click="activeComponent = 'coupons'"
      >
        Coupon codes
      </div>
      <div 
        class="sidebar-item" 
        :class="{ active: activeComponent === 'federations' }"
        @click="activeComponent = 'federations'"
      >
        Federations
      </div>
      <div 
        class="sidebar-item" 
        :class="{ active: activeComponent === 'admins' }"
        @click="activeComponent = 'admins'"
      >
        Admins & Staff
      </div>
    </div>


    <template v-if="$page.props.organization?.name">

    <!-- Main Content -->
    <div class="main-content component-container">
      <!-- Events Component -->
      <div v-if="activeComponent === 'events'">
        <div class="header">
          <h2>My events</h2>
          <button class="create-button" @click="openModal">Create new event</button>
        </div>

        <!-- Filters -->
        <div class="filters">
          <div class="filter-group">
            <label>Event name</label>
            <input type="text" v-model="searchTitle" placeholder="Enter event name" class="filter-input" />
          </div>

          <div class="filter-group">
            <label>Status</label>
            <select v-model="filterStatus" class="filter-select">
              <option value="">None selected</option>
              <option value="draft">Draft</option>
              <option value="published">Published</option>
            </select>
          </div>

          <div class="filter-group">
            <label>Visibility</label>
            <select v-model="filterVisibility" class="filter-select">
              <option value="">None selected</option>
              <option value="public">Public</option>
              <option value="private">Private</option>
            </select>
          </div>
        </div>

        <!-- Events Stats -->
        <div class="events-stats">
          <p>Showing {{ filteredEvents.length }} of {{ props.events.length }} total events</p>
        </div>

        <div>
          <div v-if="props.events.length === 0" class="no-events">
            <p>NO EVENTS FOUND</p>
          </div>

          <div v-else class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-300 bg-white text-sm text-left text-black rounded shadow">
              <thead class="bg-gray-100 text-xs uppercase tracking-wider text-gray-600">
                <tr>
                  <th scope="col" class="px-4 py-3">Title</th>
                  <th scope="col" class="px-4 py-3">Location</th>
                  <th scope="col" class="px-4 py-3">Country</th>
                  <th scope="col" class="px-4 py-3">Start Date</th>
                  <th scope="col" class="px-4 py-3">End Date</th>
                  <th scope="col" class="px-4 py-3">Status</th>
                  <th scope="col" class="px-4 py-3">Action</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                <tr v-for="event in filteredEvents" :key="event.id" class="hover:bg-gray-50">
                  <td class="px-4 py-2 font-medium">{{ event.title }}</td>
                  <td class="px-4 py-2">{{ event.location }}</td>
                  <td class="px-4 py-2">{{ event.country }}</td>
                  <td class="px-4 py-2">{{ event.start_date.slice(0, 10) }}</td>
                  <td class="px-4 py-2">{{ event.end_date.slice(0, 10) }}</td>
                  <td
                    class="block text-white capitalize rounded text-center mt-2"
                    :class="{
                      'bg-green-600': event.status === 'published',
                      'bg-yellow-600': event.status === 'draft',
                    }"
                  >
                    {{ event.status }}
                  </td>
                  <td class="px-4 py-2">
                    <button
                      class="text-blue-600 hover:underline mr-2"
                      @click="openEditModal(event)"
                    >
                      Edit
                    </button>
                    <button
                      class="text-red-600 hover:underline"
                      @click="deleteEvent(event.id)"
                    >
                      Delete
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Settings Component -->
      <div v-else-if="activeComponent === 'settings'">
        <div class="header">
          <h2 class="bold">Organization Settings</h2>
        </div>
        <div class="settings-content">
          <div class="settings-section">
            <form @submit.prevent="updateOrganization" class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="setting-item">
                <label class="block text-sm font-medium text-gray-700 mb-2">Organization Name</label>
                <input type="text" v-model="organization.name" class="filter-input w-full" required />
              </div>
              <div class="setting-item">
                <label class="block text-sm font-medium text-gray-700 mb-2">Company</label>
                <input type="text" v-model="organization.company" class="filter-input w-full" />
              </div>
              <div class="setting-item">
                <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                <input type="text" v-model="organization.firstName" class="filter-input w-full" required />
              </div>
              <div class="setting-item">
                <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                <input type="text" v-model="organization.lastName" class="filter-input w-full" required />
              </div>
              <div class="setting-item">
                <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                <input type="text" v-model="organization.address" class="filter-input w-full" />
              </div>
              <div class="setting-item">
                <label class="block text-sm font-medium text-gray-700 mb-2">ZIP Code</label>
                <input type="text" v-model="organization.zip" class="filter-input w-full" />
              </div>
              <div class="setting-item">
                <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                <input type="text" v-model="organization.city" class="filter-input w-full" />
              </div>
              <div class="setting-item">
                <label class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                <select v-model="organization.country" class="filter-select w-full">
                  <option value="" disabled>Select country</option>
                  <option v-for="countryName in countriesList" :key="countryName" :value="countryName">
                    {{ countryName }}
                  </option>
                </select>
              </div>
              <div class="setting-item">
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" disabled v-model="organization.email" class="filter-input w-full" />
              </div>
              <div class="setting-item">
                <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                <input type="tel" v-model="organization.phone" class="filter-input w-full" />
              </div>
              <div class="setting-item col-span-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                  Update Organization
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>


      <!-- User Details Component -->
      <div v-else-if="activeComponent === 'user-details'">
        <div class="header">
          <h2>User Details</h2>
        </div>
        <div class="user-details-content">
          <div class="user-details-section">
            <form @submit.prevent="saveUserDetails" class="space-y-6">
              <!-- Weight -->
              <div class="form-group">
                <label class="flex flex-col">
                  <span>Weight (kg) <span class="text-red-500">*</span></span>
                  <span v-if="userDetailsErrors.weight" class="text-red-500 text-sm mt-1 error-message">{{ userDetailsErrors.weight }}</span>
                </label>
                <input type="number" v-model="userDetails.weight" class="filter-input w-full" :class="{ 'border-red-500': userDetailsErrors.weight }" required />
              </div>

              <!-- Height -->
              <div class="form-group">
                <label class="flex flex-col">
                  <span>Height (cm) <span class="text-red-500">*</span></span>
                  <span v-if="userDetailsErrors.height" class="text-red-500 text-sm mt-1 error-message">{{ userDetailsErrors.height }}</span>
                </label>
                <input type="number" v-model="userDetails.height" class="filter-input w-full" :class="{ 'border-red-500': userDetailsErrors.height }" required />
              </div>

              <!-- Date of Birth -->
              <div class="form-group">
                <label class="flex flex-col">
                  <span>Date of Birth <span class="text-red-500">*</span></span>
                  <span v-if="userDetailsErrors.date_of_birth" class="text-red-500 text-sm mt-1 error-message">{{ userDetailsErrors.date_of_birth }}</span>
                </label>
                <input 
                  type="date" 
                  v-model="userDetails.date_of_birth"
                  class="filter-input w-full" 
                  :class="{ 'border-red-500': userDetailsErrors.date_of_birth }" 
                  required 
                />
              </div>

              <!-- Age -->
              <div class="form-group">
                <label class="flex flex-col">
                  <span>Age <span class="text-red-500">*</span></span>
                  <span v-if="userDetailsErrors.age" class="text-red-500 text-sm mt-1 error-message">{{ userDetailsErrors.age }}</span>
                </label>
                <input type="number" v-model="userDetails.age" class="filter-input w-full" :class="{ 'border-red-500': userDetailsErrors.age }" required />
              </div>

              <!-- Gender -->
              <div class="form-group">
                <label class="flex flex-col">
                  <span>Gender <span class="text-red-500">*</span></span>
                  <span v-if="userDetailsErrors.gender" class="text-red-500 text-sm mt-1 error-message">{{ userDetailsErrors.gender }}</span>
                </label>
                <select v-model="userDetails.gender" class="filter-select w-full" :class="{ 'border-red-500': userDetailsErrors.gender }" required>
                  <option value="">Select Gender</option>
                  <option v-for="gender in genders" :key="gender" :value="gender">{{ gender }}</option>
                </select>
              </div>

              <!-- Nationality -->
              <div class="form-group">
                <label class="flex flex-col">
                  <span>Nationality <span class="text-red-500">*</span></span>
                  <span v-if="userDetailsErrors.nationality" class="text-red-500 text-sm mt-1 error-message">{{ userDetailsErrors.nationality }}</span>
                </label>
                <select v-model="userDetails.nationality" class="filter-select w-full" :class="{ 'border-red-500': userDetailsErrors.nationality }" required>
                  <option value="">Select Nationality</option>
                  <option v-for="country in countries" :key="country" :value="country">{{ country }}</option>
                </select>
              </div>

              <!-- Country of Residence -->
              <div class="form-group">
                <label class="flex flex-col">
                  <span>Country of Residence <span class="text-red-500">*</span></span>
                  <span v-if="userDetailsErrors.country_of_residence" class="text-red-500 text-sm mt-1 error-message">{{ userDetailsErrors.country_of_residence }}</span>
                </label>
                <select v-model="userDetails.country_of_residence" class="filter-select w-full" :class="{ 'border-red-500': userDetailsErrors.country_of_residence }" required>
                  <option value="">Select Country of Residence</option>
                  <option v-for="country in countries" :key="country" :value="country">{{ country }}</option>
                </select>
              </div>

              <!-- Phone -->
              <div class="form-group">
                <label class="flex flex-col">
                  <span>Phone Number <span class="text-red-500">*</span></span>
                  <span v-if="userDetailsErrors.phone" class="text-red-500 text-sm mt-1 error-message">{{ userDetailsErrors.phone }}</span>
                </label>
                <input type="tel" v-model="userDetails.phone" class="filter-input w-full" :class="{ 'border-red-500': userDetailsErrors.phone }" required />
              </div>

              <!-- Address -->
              <div class="form-group">
                <label class="flex flex-col">
                  <span>Address</span>
                  <span v-if="userDetailsErrors.address" class="text-red-500 text-sm mt-1 error-message">{{ userDetailsErrors.address }}</span>
                </label>
                <textarea v-model="userDetails.address" rows="3" class="filter-input w-full" :class="{ 'border-red-500': userDetailsErrors.address }"></textarea>
              </div>

              <!-- Passport/Player ID Image -->
              <div class="form-group">
                <label class="flex flex-col">
                  <span>Passport/Player ID Image <span v-if="!userDetailsPreviewImage" class="text-red-500">*</span></span>
                  <span v-if="userDetailsErrors.passport_image" class="text-red-500 text-sm mt-1 error-message">{{ userDetailsErrors.passport_image }}</span>
                </label>
                <input 
                  type="file" 
                  @change="handleUserDetailsFileUpload" 
                  accept="image/*" 
                  class="filter-input w-full" 
                  :class="{ 'border-red-500': userDetailsErrors.passport_image }" 
                />
                <div v-if="userDetailsPreviewImage" class="mt-2">
                  <img :src="userDetailsPreviewImage" alt="Preview" class="h-32 w-32 object-cover rounded-lg" />
                </div>
              </div>

              <!-- Notes -->
              <div class="form-group">
                <label class="flex flex-col">
                  <span>Additional Notes</span>
                  <span v-if="userDetailsErrors.notes" class="text-red-500 text-sm mt-1 error-message">{{ userDetailsErrors.notes }}</span>
                </label>
                <textarea v-model="userDetails.notes" rows="3" class="filter-input w-full" :class="{ 'border-red-500': userDetailsErrors.notes }"></textarea>
              </div>

              <!-- Submit Button -->
              <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700" :disabled="userDetailsLoading">
                  {{ userDetailsLoading ? 'Saving...' : 'Save Details' }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Credits Component -->
      <div v-else-if="activeComponent === 'credits'">
        <div class="header">
          <h2>Credit History</h2>
        </div>
        <div class="credits-content">
          <div class="credits-section">
            <h3 class="text-lg font-semibold mb-4">Transaction History</h3>
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-300 bg-white text-sm text-left text-black rounded shadow">
                <thead class="bg-gray-100 text-xs uppercase tracking-wider text-gray-600">
                  <tr>
                    <th scope="col" class="px-4 py-3">Date</th>
                    <th scope="col" class="px-4 py-3">Transaction Type</th>
                    <th scope="col" class="px-4 py-3">Amount</th>
                    <th scope="col" class="px-4 py-3">Status</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  <tr>
                    <td class="px-4 py-2">No transactions found</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Messages Component -->
      <div v-else-if="activeComponent === 'messages'">
        <div class="header">
          <h2>Message Center</h2>
        </div>
        <div class="messages-content">
          <div class="messages-section">
            <h3 class="text-lg font-semibold mb-4">Recent Messages</h3>
            <div class="space-y-4">
              <div class="message-item p-4 bg-gray-50 rounded-lg">
                <p class="text-gray-500">No messages found</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Coupons Component -->
      <div v-else-if="activeComponent === 'coupons'">
        <div class="header">
          <h2>Coupon Codes</h2>
        </div>
        <div class="coupons-content">
          <div class="coupons-section">
            <h3 class="text-lg font-semibold mb-4">Active Coupons</h3>
            <div class="space-y-4">
              <div class="coupon-item p-4 bg-gray-50 rounded-lg">
                <p class="text-gray-500">No active coupons found</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Federations Component -->
      <div v-else-if="activeComponent === 'federations'">
        <div class="header">
          <h2>Federations</h2>
        </div>
        <div class="federations-content">
          <div class="federations-section">
            <h3 class="text-lg font-semibold mb-4">Associated Federations</h3>
            <div class="space-y-4">
              <div class="federation-item p-4 bg-gray-50 rounded-lg">
                <p class="text-gray-500">No federations found</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Admins Component -->
      <div v-else-if="activeComponent === 'admins'">
        <div class="header">
          <h2>Admins & Staff</h2>
        </div>
        <div class="admins-content">
          <div class="admins-section">
            <h3 class="text-lg font-semibold mb-4">Team Members</h3>
            <div class="space-y-4">
              <div class="admin-item p-4 bg-gray-50 rounded-lg">
                <p class="text-gray-500">No team members found</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </template>

  <template v-else>
    <div class="text-center w-full content-center">
    <p class="w-full p-5 text-center">Please create an organization first to add new events .</p>
    <Link :href="route('Organization')"
  class="text-[16px] bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
>
  Create Organization
</Link>
</div>
  </template>
  </div>



                </div>
            </div>
        </div>


        <!-- Create Event Modal -->
        <template v-if="showModal">
          <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="h-[500px] overflow-y-scroll bg-white text-black p-6 rounded-lg shadow-lg max-w-md w-full relative modal-content">
              <h2 class="text-lg font-bold mb-4">Create New Event</h2>
              
              <!-- Tabs -->
              <div class="flex border-b mb-4">
                <button 
                  @click="goToEventDetailsTab"
                  :class="['px-4 py-2 -mb-px', activeTab === 'event-details' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-600']"
                >
                  Event Details
                </button>
                <button 
                  @click="goToDivisionsTab"
                  :class="['px-4 py-2 -mb-px', 
                    activeTab === 'divisions' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-600',
                    isDivisionsTabDisabled ? 'opacity-50 cursor-not-allowed' : ''
                  ]"
                >
                  Divisions
                </button>
              </div>

              <!-- Event Details Tab -->
              <div v-if="activeTab === 'event-details'" class="space-y-4">
                <!-- Existing event form fields -->
                <div class="filter-group">
                  <label class="flex flex-col">
                    <span>Whats the name of your event ? <span class="text-red-500">*</span></span>
                    <span v-if="formErrors.title" class="text-red-500 text-sm mt-1 error-message">{{ formErrors.title }}</span>
                  </label>
                  <input type="text" required placeholder="Enter event name" v-model="title" value="" class="filter-input mt-2" :class="{ 'border-red-500': formErrors.title }" />
                </div>

                <div class="filter-group mt-3">
                  <label class="flex flex-col">
                    <span>Country <span class="text-red-500">*</span></span>
                    <span v-if="formErrors.country" class="text-red-500 text-sm mt-1 error-message">{{ formErrors.country }}</span>
                  </label>
                  <select required v-model="country" class="filter-select mt-2" :class="{ 'border-red-500': formErrors.country }">
                    <option value="" disabled selected>Select a country</option>
                    <option v-for="countryName in Object.keys(citiesByCountry)" :key="countryName" :value="countryName">
                      {{ countryName }}
                    </option>
                  </select>
                </div>

                <div class="filter-group mt-3">
                  <label class="flex flex-col">
                    <span>City <span class="text-red-500">*</span></span>
                    <span v-if="formErrors.city" class="text-red-500 text-sm mt-1 error-message">{{ formErrors.city }}</span>
                  </label>
                  <select required v-model="city" class="filter-select mt-2" :disabled="!country" :class="{ 'border-red-500': formErrors.city }">
                    <option value="" disabled selected>{{ country ? 'Select a city' : 'Select a country first' }}</option>
                    <option v-for="cityName in availableCities" :key="cityName" :value="cityName">
                      {{ cityName }}
                    </option>
                  </select>
                </div>

                <div class="filter-group mt-3">
                  <label class="flex flex-col">
                    <span>Start Date <span class="text-red-500">*</span></span>
                    <span v-if="formErrors.startDate" class="text-red-500 text-sm mt-1 error-message">{{ formErrors.startDate }}</span>
                  </label>
                  <input type="date" required v-model="startDate" class="filter-input mt-2" :class="{ 'border-red-500': formErrors.startDate }" />
                </div>

                <div class="filter-group mt-3">
                  <label class="flex flex-col">
                    <span>End Date <span class="text-red-500">*</span></span>
                    <span v-if="formErrors.endDate" class="text-red-500 text-sm mt-1 error-message">{{ formErrors.endDate }}</span>
                  </label>
                  <input type="date" required v-model="endDate" class="filter-input mt-2" :class="{ 'border-red-500': formErrors.endDate }" />
                </div>

                <div class="filter-group mt-3">
                  <label class="flex flex-col">
                    <span>Last Registration Date <span class="text-red-500">*</span></span>
                    <span v-if="formErrors.lastRegistrationDate" class="text-red-500 text-sm mt-1 error-message">{{ formErrors.lastRegistrationDate }}</span>
                  </label>
                  <input type="date" required v-model="lastRegistrationDate" class="filter-input mt-2" :class="{ 'border-red-500': formErrors.lastRegistrationDate }" />
                  <small class="text-gray-500">Must be after the event start date</small>
                </div>

                <div class="filter-group mt-3">
                  <label class="flex flex-col">
                    <span>Timezone <span class="text-red-500">*</span></span>
                    <span v-if="formErrors.timezone" class="text-red-500 text-sm mt-1 error-message">{{ formErrors.timezone }}</span>
                  </label>
                  <select required v-model="timezone" class="filter-select mt-2" :class="{ 'border-red-500': formErrors.timezone }">
                    <option value="" disabled selected>Select a timezone</option>
                    <option v-for="tz in timezones" :key="tz.value" :value="tz.value">
                      {{ tz.label }}
                    </option>
                  </select>
                </div>

                <div class="filter-group mt-3">
                  <label class="flex flex-col">
                    <span>Status <span class="text-red-500">*</span></span>
                    <span v-if="formErrors.status" class="text-red-500 text-sm mt-1 error-message">{{ formErrors.status }}</span>
                  </label>
                  <select required v-model="status" id="status" class="filter-select mt-2" :class="{ 'border-red-500': formErrors.status }">
                    <option selected value="">Select a status</option>
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                  </select>
                </div>

                <div class="filter-group mt-3">
                  <label class="flex flex-col">
                    <span>Visibility <span class="text-red-500">*</span></span>
                    <span v-if="formErrors.visibility" class="text-red-500 text-sm mt-1 error-message">{{ formErrors.visibility }}</span>
                  </label>
                  <select required v-model="visibility" id="visibility" class="filter-select mt-2" :class="{ 'border-red-500': formErrors.visibility }">
                    <option selected value="">Select an option</option>
                    <option value="public">Public</option>
                    <option value="private">Private</option>
                  </select>
                </div>

                <div class="filter-group mt-3">
                  <label>Event Image</label>
                  <input 
                    type="file" 
                    @change="handleImageChange" 
                    accept="image/*"
                    class="filter-input" 
                  />
                  <img 
                    v-if="previewImage" 
                    :src="previewImage" 
                    class="mt-2 rounded-lg max-h-40 mx-auto"
                  />
                </div>


                <div class="filter-group mt-3">
                <label>Event Description</label>
                <RichTextEditor v-model="description" />
                </div>

                <div class="filter-group mt-3">
                  <label class="flex flex-col">
                    <span>Adult Event Price ($) <span class="text-red-500">*</span></span>
                    <span v-if="formErrors.adultPrice" class="text-red-500 text-sm mt-1 error-message">{{ formErrors.adultPrice }}</span>
                  </label>
                  <input type="number" step="0.01" min="0" required v-model="adultPrice" class="filter-input mt-2" :class="{ 'border-red-500': formErrors.adultPrice }" />
                </div>

                <div class="filter-group mt-3">
                  <label class="flex flex-col">
                    <span>Minor Event Price ($) <span class="text-red-500">*</span></span>
                    <span v-if="formErrors.minorPrice" class="text-red-500 text-sm mt-1 error-message">{{ formErrors.minorPrice }}</span>
                  </label>
                  <input type="number" step="0.01" min="0" required v-model="minorPrice" class="filter-input mt-2" :class="{ 'border-red-500': formErrors.minorPrice }" />
                </div>

                <div class="filter-group mt-3">
                  <label class="flex flex-col">
                    <span>Children Event Price ($) <span class="text-red-500">*</span></span>
                    <span v-if="formErrors.childrenPrice" class="text-red-500 text-sm mt-1 error-message">{{ formErrors.childrenPrice }}</span>
                  </label>
                  <input type="number" step="0.01" min="0" required v-model="childrenPrice" class="filter-input mt-2" :class="{ 'border-red-500': formErrors.childrenPrice }" />
                </div>

                <div class="flex justify-end gap-2 mt-6">
                  <button class="bg-gray-300 px-4 py-2 rounded" @click="closeModal">Cancel</button>
                  <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700" @click="goToDivisionsTab">Next</button>
                </div>
              </div>

              <!-- Divisions Tab -->
              <div v-if="activeTab === 'divisions'" class="space-y-4">
                <button 
                  @click="addDivision"
                  class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 mb-4"
                >
                  Add Division
                </button>

                <!-- List of added divisions -->
                <div v-if="divisions.length > 0" class="space-y-2">
                  <div v-for="(division, index) in divisions" :key="index" class="bg-gray-50 p-3 rounded flex justify-between items-center">
                    <div>
                      <h4 class="font-medium">{{ division.name }}</h4>
                      <p class="text-sm text-gray-600">
                        {{ division.gender }} | Age: {{ division.min_age }}-{{ division.max_age }} | 
                        Weight: {{ division.min_weight }}-{{ division.max_weight }}kg
                      </p>
                      <p class="text-sm text-gray-600" v-if="division.start_time || division.mat_number">
                        <span v-if="division.start_time">Start: {{ new Date(division.start_time).toLocaleString() }}</span>
                        <span v-if="division.mat_number"> | Mat: {{ division.mat_number }}</span>
                      </p>
                    </div>
                    <button 
                      @click="removeDivision(index)"
                      class="text-red-600 hover:text-red-800"
                    >
                      Remove
                    </button>
                  </div>
                </div>

                <div v-else class="text-center text-gray-500 py-4">
                  No divisions added yet
                </div>

                <div class="flex justify-end gap-2 mt-6">
                  <button class="bg-gray-300 px-4 py-2 rounded" @click="activeTab = 'event-details'">Back</button>
                  <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700" @click="createEvent">Save Event</button>
                </div>
              </div>

              <button
                class="absolute top-2 right-3 text-gray-600 hover:text-black"
                @click="closeModal"
              >
                ✖
              </button>
            </div>
          </div>
        </template>

        <!-- Division Modal -->
        <template v-if="showDivisionModal">
          <div class="add-division-div fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-99">
            <div class="bg-white text-black p-6 rounded-lg shadow-lg max-w-md w-full relative">
              <h3 class="text-lg font-bold mb-4">Add Division</h3>

              <div class="space-y-4 form-add-division">
                <div class="filter-group">
                  <label class="flex flex-col">
                    <span>Division Name <span class="text-red-500">*</span></span>
                    <span v-if="divisionFormErrors.name" class="text-red-500 text-sm mt-1">{{ divisionFormErrors.name }}</span>
                  </label>
                  <input type="text" v-model="divisionForm.name" class="filter-input" :class="{ 'border-red-500': divisionFormErrors.name }" required />
                </div>

                <div class="filter-group">
                  <label class="flex flex-col">
                    <span>Gender <span class="text-red-500">*</span></span>
                    <span v-if="divisionFormErrors.gender" class="text-red-500 text-sm mt-1">{{ divisionFormErrors.gender }}</span>
                  </label>
                  <select v-model="divisionForm.gender" class="filter-select" :class="{ 'border-red-500': divisionFormErrors.gender }" required>
                    <option value="">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="mixed">Mixed</option>
                  </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                  <div class="filter-group">
                    <label class="flex flex-col">
                      <span>Min Age <span class="text-red-500">*</span></span>
                      <span v-if="divisionFormErrors.min_age" class="text-red-500 text-sm mt-1">{{ divisionFormErrors.min_age }}</span>
                    </label>
                    <input type="number" v-model="divisionForm.min_age" class="filter-input" :class="{ 'border-red-500': divisionFormErrors.min_age }" required />
                  </div>
                  <div class="filter-group">
                    <label class="flex flex-col">
                      <span>Max Age <span class="text-red-500">*</span></span>
                      <span v-if="divisionFormErrors.max_age" class="text-red-500 text-sm mt-1">{{ divisionFormErrors.max_age }}</span>
                    </label>
                    <input type="number" v-model="divisionForm.max_age" class="filter-input" :class="{ 'border-red-500': divisionFormErrors.max_age }" required />
                  </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                  <div class="filter-group">
                    <label class="flex flex-col">
                      <span>Min Weight (kg) <span class="text-red-500">*</span></span>
                      <span v-if="divisionFormErrors.min_weight" class="text-red-500 text-sm mt-1">{{ divisionFormErrors.min_weight }}</span>
                    </label>
                    <input type="number" v-model="divisionForm.min_weight" class="filter-input" :class="{ 'border-red-500': divisionFormErrors.min_weight }" required />
                  </div>
                  <div class="filter-group">
                    <label class="flex flex-col">
                      <span>Max Weight (kg) <span class="text-red-500">*</span></span>
                      <span v-if="divisionFormErrors.max_weight" class="text-red-500 text-sm mt-1">{{ divisionFormErrors.max_weight }}</span>
                    </label>
                    <input type="number" v-model="divisionForm.max_weight" class="filter-input" :class="{ 'border-red-500': divisionFormErrors.max_weight }" required />
                  </div>
                </div>

                <div class="filter-group">
                  <label class="flex flex-col">
                    <span>Belt Level</span>
                  </label>
                  <select v-model="divisionForm.belt_level" class="filter-select">
                    <option value="">Select Belt Level</option>
                    <option value="white">White</option>
                    <option value="blue">Blue</option>
                    <option value="purple">Purple</option>
                    <option value="brown">Brown</option>
                    <option value="black">Black</option>
                  </select>
                </div>

                <div class="filter-group">
                  <label class="flex flex-col">
                    <span>Bracket Type <span class="text-red-500">*</span></span>
                    <span v-if="divisionFormErrors.bracket_type" class="text-red-500 text-sm mt-1">{{ divisionFormErrors.bracket_type }}</span>
                  </label>
                  <select v-model="divisionForm.bracket_type" class="filter-select" :class="{ 'border-red-500': divisionFormErrors.bracket_type }" required>
                    <option value="">Select Bracket Type</option>
                    <option value="single_elimination">Single Elimination</option>
                    <option value="double_elimination">Double Elimination</option>
                    <option value="round_robin">Round Robin</option>
                  </select>
                </div>

                <div class="filter-group">
                  <label class="flex flex-col">
                    <span>Match Duration (minutes) <span class="text-red-500">*</span></span>
                    <span v-if="divisionFormErrors.match_duration_min" class="text-red-500 text-sm mt-1">{{ divisionFormErrors.match_duration_min }}</span>
                  </label>
                  <input type="number" v-model="divisionForm.match_duration_min" class="filter-input" :class="{ 'border-red-500': divisionFormErrors.match_duration_min }" required />
                </div>

                <div class="filter-group">
                  <label class="flex flex-col">
                    <span>Estimated Start Time</span>
                    <span v-if="divisionFormErrors.start_time" class="text-red-500 text-sm mt-1">{{ divisionFormErrors.start_time }}</span>
                  </label>
                  <input type="datetime-local" v-model="divisionForm.start_time" class="filter-input" />
                </div>

                <div class="filter-group">
                  <label class="flex flex-col">
                    <span>Mat / Area Name</span>
                    <span v-if="divisionFormErrors.mat_number" class="text-red-500 text-sm mt-1">{{ divisionFormErrors.mat_number }}</span>
                  </label>
                  <input type="text" v-model="divisionForm.mat_number" class="filter-input" placeholder="e.g. Mat 1, Ring A" />
                </div>
              </div>

              <div class="flex justify-end gap-2 mt-6">
                <button 
                  class="bg-gray-300 px-4 py-2 rounded"
                  @click="showDivisionModal = false"
                >
                  Cancel
                </button>
                <button 
                  class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                  @click="saveDivision"
                >
                  Add Division
                </button>
              </div>
            </div>
          </div>
        </template>

        <template v-if="showEditModal">
          <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="h-[500px] overflow-y-scroll bg-white text-black p-6 rounded-lg shadow-lg max-w-md w-full relative modal-content">
              <h2 class="text-lg font-bold mb-4">Edit Event</h2>

              <!-- Tabs -->
              <div class="flex border-b mb-4">
                <button 
                  @click="goToEventDetailsTab"
                  :class="['px-4 py-2 -mb-px', activeTab === 'event-details' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-600']"
                >
                  Event Details
                </button>
                <button 
                  @click="goToDivisionsTab"
                  :class="['px-4 py-2 -mb-px', 
                    activeTab === 'divisions' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-600',
                    isDivisionsTabDisabled ? 'opacity-50 cursor-not-allowed' : ''
                  ]"
                >
                  Divisions
                </button>
              </div>

              <!-- Event Details Tab -->
              <div v-if="activeTab === 'event-details'" class="space-y-4">
                <div class="filter-group">
                  <label class="flex flex-col">
                    <span>Event name <span class="text-red-500">*</span></span>
                    <span v-if="formErrors.title" class="text-red-500 text-sm mt-1 error-message">{{ formErrors.title }}</span>
                  </label>
                  <input type="text" placeholder="Enter event name" v-model="title" class="filter-input mt-2" :class="{ 'border-red-500': formErrors.title }" />
                </div>

                <div class="filter-group mt-3">
                  <label class="flex flex-col">
                    <span>Country <span class="text-red-500">*</span></span>
                    <span v-if="formErrors.country" class="text-red-500 text-sm mt-1 error-message">{{ formErrors.country }}</span>
                  </label>
                  <select v-model="country" class="filter-select mt-2" :class="{ 'border-red-500': formErrors.country }">
                    <option value="" disabled selected>Select a country</option>
                    <option v-for="countryName in Object.keys(citiesByCountry)" :key="countryName" :value="countryName">
                      {{ countryName }}
                    </option>
                  </select>
                </div>

                <div class="filter-group mt-3">
                  <label>City</label>
                  <select v-model="city" class="filter-select" :disabled="!country">
                    <option value="" disabled selected>{{ country ? 'Select a city' : 'Select a country first' }}</option>
                    <option v-for="cityName in availableCities" :key="cityName" :value="cityName">
                      {{ cityName }}
                    </option>
                  </select>
                </div>

                <div class="filter-group mt-3">
                  <label>Start Date</label>
                  <input type="date" v-model="startDate" class="filter-input" />
                </div>

                <div class="filter-group mt-3">
                  <label class="flex flex-col">
                    <span>End Date <span class="text-red-500">*</span></span>
                    <span v-if="formErrors.endDate" class="text-red-500 text-sm mt-1 error-message">{{ formErrors.endDate }}</span>
                  </label>
                  <input type="date" required v-model="endDate" class="filter-input mt-2" :class="{ 'border-red-500': formErrors.endDate }" />
                </div>

                <div class="filter-group mt-3">
                  <label class="flex flex-col">
                    <span>Last Registration Date <span class="text-red-500">*</span></span>
                    <span v-if="formErrors.lastRegistrationDate" class="text-red-500 text-sm mt-1 error-message">{{ formErrors.lastRegistrationDate }}</span>
                  </label>
                  <input type="date" required v-model="lastRegistrationDate" class="filter-input mt-2" :class="{ 'border-red-500': formErrors.lastRegistrationDate }" />
                  <small class="text-gray-500">Must be after the event start date</small>
                </div>

                <div class="filter-group mt-3">
                  <label>Timezone</label>
                  <select v-model="timezone" class="filter-select">
                    <option value="" disabled selected>Select a timezone</option>
                    <option v-for="tz in timezones" :key="tz.value" :value="tz.value">
                      {{ tz.label }}
                    </option>
                  </select>
                </div>

                <div class="filter-group mt-3">
                  <label for="status">Status</label>
                  <select v-model="status" id="status" class="filter-select">
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                  </select>
                </div>

                <div class="filter-group mt-3">
                  <label for="visibility">Visibility</label>
                  <select v-model="visibility" id="visibility" class="filter-select">
                    <option value="public">Public</option>
                    <option value="private">Private</option>
                  </select>
                </div>

                <div class="filter-group mt-3">
                  <label>Event Image</label>
                  <input 
                    type="file" 
                    @change="handleImageChange" 
                    accept="image/*"
                    class="filter-input" 
                  />
                  <img 
                    v-if="previewImage" 
                    :src="previewImage" 
                    class="mt-2 rounded-lg max-h-40 mx-auto"
                  />
                </div>

                <div class="filter-group mt-3">
                  <label>Event Description</label>
                  <RichTextEditor v-model="description" />
                </div>

                <div class="filter-group mt-3">
                  <label class="flex flex-col">
                    <span>Adult Event Price ($) <span class="text-red-500">*</span></span>
                    <span v-if="formErrors.adultPrice" class="text-red-500 text-sm mt-1 error-message">{{ formErrors.adultPrice }}</span>
                  </label>
                  <input type="number" step="0.01" min="0" required v-model="adultPrice" class="filter-input mt-2" :class="{ 'border-red-500': formErrors.adultPrice }" />
                </div>

                <div class="filter-group mt-3">
                  <label class="flex flex-col">
                    <span>Minor Event Price ($) <span class="text-red-500">*</span></span>
                    <span v-if="formErrors.minorPrice" class="text-red-500 text-sm mt-1 error-message">{{ formErrors.minorPrice }}</span>
                  </label>
                  <input type="number" step="0.01" min="0" required v-model="minorPrice" class="filter-input mt-2" :class="{ 'border-red-500': formErrors.minorPrice }" />
                </div>

                <div class="filter-group mt-3">
                  <label class="flex flex-col">
                    <span>Children Event Price ($) <span class="text-red-500">*</span></span>
                    <span v-if="formErrors.childrenPrice" class="text-red-500 text-sm mt-1 error-message">{{ formErrors.childrenPrice }}</span>
                  </label>
                  <input type="number" step="0.01" min="0" required v-model="childrenPrice" class="filter-input mt-2" :class="{ 'border-red-500': formErrors.childrenPrice }" />
                </div>

                <div class="flex justify-end gap-2 mt-6">
                  <button class="bg-gray-300 px-4 py-2 rounded" @click="closeEditModal">Cancel</button>
                  <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700" @click="goToDivisionsTab">Next</button>
                </div>
              </div>

              <!-- Divisions Tab -->
              <div v-if="activeTab === 'divisions'" class="space-y-4">
                <button 
                  @click="addDivision"
                  class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 mb-4"
                >
                  Add Division
                </button>

                <!-- List of added divisions -->
                <div v-if="divisions.length > 0" class="space-y-2">
                  <div v-for="(division, index) in divisions" :key="index" class="bg-gray-50 p-3 rounded flex justify-between items-center">
                    <div>
                      <h4 class="font-medium">{{ division.name }}</h4>
                      <p class="text-sm text-gray-600">
                        {{ division.gender }} | Age: {{ division.min_age }}-{{ division.max_age }} | 
                        Weight: {{ division.min_weight }}-{{ division.max_weight }}kg
                      </p>
                      <p class="text-sm text-gray-600" v-if="division.start_time || division.mat_number">
                        <span v-if="division.start_time">Start: {{ new Date(division.start_time).toLocaleString() }}</span>
                        <span v-if="division.mat_number"> | Mat: {{ division.mat_number }}</span>
                      </p>
                    </div>
                    <button 
                      @click="removeDivision(index)"
                      class="text-red-600 hover:text-red-800"
                    >
                      Remove
                    </button>
                  </div>
                </div>

                <div v-else class="text-center text-gray-500 py-4">
                  No divisions added yet
                </div>

                <div class="flex justify-end gap-2 mt-6">
                  <button class="bg-gray-300 px-4 py-2 rounded" @click="goToEventDetailsTab">Back</button>
                  <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700" @click="updateEvent">Save Event</button>
                </div>
              </div>

              <button
                class="absolute top-2 right-3 text-gray-600 hover:text-black"
                @click="closeEditModal"
              >
                ✖
              </button>
            </div>
          </div>
        </template>

        <Footer />
        
        <!-- Toast Notifications -->
        <Toast
          v-if="showToast"
          :message="toastMessage"
          :type="toastType"
          @close="showToast = false"
        />
</template>




<style scoped>
.form-add-division{
  max-height: 500px;
  overflow-y: scroll;
}
.add-division-div{
  z-index: 99;
}
.dashboard-container {
  color: white;
  font-family: Arial, sans-serif;
  padding: 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.nav-bar {
  background-color: #222;
  border-radius: 30px;
  display: flex;
  justify-content: space-between;
  padding: 15px 20px;
  width: 100%;
  max-width: 1000px;
  margin-bottom: 40px;
}

.nav-item {
  background: transparent;
  border: none;
  color: white;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 5px;
  font-size: 14px;
  opacity: 0.8;
  transition: opacity 0.2s;
}

.nav-item:hover {
  opacity: 1;
}

.main-title {
  font-size: 28px;
  font-weight: bold;
  margin-bottom: 40px;
  text-align: center;
}

.cards-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 20px;
  width: 100%;
  max-width: 1200px;
}

.card {
  background-color: #222;
  border-radius: 10px;
  padding: 30px;
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
}

.card-icon {
  margin-bottom: 15px;
}

.card-title {
  font-size: 18px;
  font-weight: bold;
  margin-bottom: 15px;
}

.card-value {
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 5px;
}

.card-subtitle {
  font-size: 14px;
  opacity: 0.7;
  margin-bottom: 20px;
}

.card-description {
  font-size: 14px;
  opacity: 0.7;
  margin-bottom: 20px;
  line-height: 1.5;
}

.action-button {
  background-color: #0099ff;
  border: none;
  border-radius: 20px;
  color: white;
  cursor: pointer;
  font-size: 14px;
  padding: 8px 20px;
  transition: background-color 0.2s;
}

.action-button:hover {
  background-color: #007acc;
}

.off-text {
  color: #ff5555;
}

@media (max-width: 768px) {
  .nav-bar {
    flex-direction: column;
    gap: 10px;
    align-items: center;
  }
  
  .cards-container {
    grid-template-columns: 1fr;
  }
}
.events-container {
  display: flex;
  min-height: 100vh;
  background-color: #121212;
  color: white;
  font-family: Arial, sans-serif;
}

.sidebar {
  width: 250px;
  background-color: #121212;
  padding: 20px 0;
}

.sidebar-item {
  padding: 12px 20px;
  cursor: pointer;
  color: #aaa;
  transition: all 0.2s;
}

.sidebar-item:hover {
  color: white;
  background-color: rgba(255, 255, 255, 0.05);
}

.sidebar-item.active {
  color: white;
  background-color: #222;
  border-radius: 4px;
  margin: 0 10px;
  padding: 12px 10px;
}

.main-content {
  flex: 1;
  background-color: white;
  color: #333;
  border-radius: 8px;
  margin: 20px;
  padding: 20px;
  display: flex;
  flex-direction: column;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.header h2 {
  font-size: 20px;
  font-weight: 500;
  color: #555;
}

.create-button {
  background-color: #7bc043;
  color: white;
  border: none;
  border-radius: 4px;
  padding: 8px 16px;
  cursor: pointer;
  font-weight: 500;
  transition: background-color 0.2s;
}

.create-button:hover {
  background-color: #6aa53a;
}

.filters {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
  margin-bottom: 20px;
}

.filter-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.filter-group label {
  font-size: 14px;
  color: #555;
}

.filter-input, .filter-select {
  padding: 8px 12px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 14px;
}

.events-stats {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
  font-size: 14px;
  color: #555;
}

.archive-button {
  display: flex;
  align-items: center;
  gap: 8px;
  background-color: #6c757d;
  color: white;
  border: none;
  border-radius: 4px;
  padding: 8px 16px;
  cursor: pointer;
  transition: background-color 0.2s;
}

.archive-button:hover {
  background-color: #5a6268;
}

.no-events {
  flex: 1;
  display: flex;
  justify-content: center;
  align-items: center;
  color: #888;
  font-size: 16px;
  font-weight: 500;
  padding: 40px 0;
}

@media (max-width: 768px) {
  .events-container {
    flex-direction: column;
  }
  
  .sidebar {
    width: 100%;
    padding: 10px;
  }
  
  .filters {
    grid-template-columns: 1fr;
  }
  
  .events-stats {
    flex-direction: column;
    gap: 10px;
    align-items: flex-start;
  }
}
</style>