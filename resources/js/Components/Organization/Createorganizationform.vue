<script setup>
import { onMounted, reactive, ref, nextTick } from 'vue';

const organization = reactive({
  name: '',
  description: '',
  email: '',
  phone: '',
  address: '',
  city: '',
  country: '',
  website: '',
  type: '',
  status: 'pending' // Default status
});

const userId = ref(null);
const errorMessage = ref('');
const errorMessageRef = ref(null);

onMounted(async () => {
  try {
    const res = await fetch('/user', {
      credentials: 'include',
    });
    const user = await res.json();
    userId.value = user.id;
  } catch (error) {
    console.error('Failed to fetch user info:', error);
  }
});

const countries = ref([
  'Afghanistan', 'Albania', 'Algeria', 'Andorra', 'Angola', 'Antigua and Barbuda', 'Argentina',
  'Armenia', 'Australia', 'Austria', 'Azerbaijan', 'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados',
  'Belarus', 'Belgium', 'Belize', 'Benin', 'Bhutan', 'Bolivia', 'Bosnia and Herzegovina', 'Botswana',
  'Brazil', 'Brunei', 'Bulgaria', 'Burkina Faso', 'Burundi', 'Cabo Verde', 'Cambodia', 'Cameroon',
  'Canada', 'Central African Republic', 'Chad', 'Chile', 'China', 'Colombia', 'Comoros', 'Congo',
  'Costa Rica', 'Croatia', 'Cuba', 'Cyprus', 'Czech Republic', 'Denmark', 'Djibouti', 'Dominica',
  'Dominican Republic', 'Ecuador', 'Egypt', 'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia',
  'Eswatini', 'Ethiopia', 'Fiji', 'Finland', 'France', 'Gabon', 'Gambia', 'Georgia', 'Germany',
  'Ghana', 'Greece', 'Grenada', 'Guatemala', 'Guinea', 'Guinea-Bissau', 'Guyana', 'Haiti', 'Honduras',
  'Hungary', 'Iceland', 'India', 'Indonesia', 'Iran', 'Iraq', 'Ireland', 'Israel', 'Italy', 'Jamaica',
  'Japan', 'Jordan', 'Kazakhstan', 'Kenya', 'Kiribati', 'Korea, North', 'Korea, South', 'Kosovo',
  'Kuwait', 'Kyrgyzstan', 'Laos', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libya', 'Liechtenstein',
  'Lithuania', 'Luxembourg', 'Madagascar', 'Malawi', 'Malaysia', 'Maldives', 'Mali', 'Malta',
  'Marshall Islands', 'Mauritania', 'Mauritius', 'Mexico', 'Micronesia', 'Moldova', 'Monaco',
  'Mongolia', 'Montenegro', 'Morocco', 'Mozambique', 'Myanmar', 'Namibia', 'Nauru', 'Nepal',
  'Netherlands', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'North Macedonia', 'Norway', 'Oman',
  'Pakistan', 'Palau', 'Palestine', 'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines',
  'Poland', 'Portugal', 'Qatar', 'Romania', 'Russia', 'Rwanda', 'Saint Kitts and Nevis', 'Saint Lucia',
  'Saint Vincent and the Grenadines', 'Samoa', 'San Marino', 'Sao Tome and Principe', 'Saudi Arabia',
  'Senegal', 'Serbia', 'Seychelles', 'Sierra Leone', 'Singapore', 'Slovakia', 'Slovenia',
  'Solomon Islands', 'Somalia', 'South Africa', 'South Sudan', 'Spain', 'Sri Lanka', 'Sudan',
  'Suriname', 'Sweden', 'Switzerland', 'Syria', 'Taiwan', 'Tajikistan', 'Tanzania', 'Thailand',
  'Timor-Leste', 'Togo', 'Tonga', 'Trinidad and Tobago', 'Tunisia', 'Turkey', 'Turkmenistan',
  'Tuvalu', 'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom', 'United States', 'Uruguay',
  'Uzbekistan', 'Vanuatu', 'Vatican City', 'Venezuela', 'Vietnam', 'Yemen', 'Zambia', 'Zimbabwe'
]);

const validateForm = () => {
  if (!organization.name) {
    errorMessage.value = 'Organization name is required';
    scrollToError();
    return false;
  }
  if (!organization.email) {
    errorMessage.value = 'Email is required';
    scrollToError();
    return false;
  }
  if (!organization.type) {
    errorMessage.value = 'Organization type is required';
    scrollToError();
    return false;
  }
  errorMessage.value = '';
  return true;
};

const scrollToError = async () => {
  await nextTick();
  if (errorMessageRef.value) {
    errorMessageRef.value.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }
};

const submitForm = async () => {
  if (!validateForm()) {
    return;
  }

  try {
    const csrfToken = document
      .querySelector('meta[name="csrf-token"]')
      ?.getAttribute('content');

    // Create the request data
    const requestData = {
      userId: userId.value,
      organization: {
        name: organization.name,
        description: organization.description,
        email: organization.email,
        phone: organization.phone,
        address: organization.address,
        city: organization.city,
        country: organization.country,
        website: organization.website,
        type: organization.type,
        status: organization.status
      }
    };

    // Log the data being sent
    console.log('Sending data:', requestData);

    const response = await fetch('/organization', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
      },
      credentials: 'include',
      body: JSON.stringify(requestData),
    });

    const result = await response.json();
    console.log('Server response:', result);

    if (response.ok) {
      console.log('Organization created:', result.organization);
      window.location.href = 'dashboard#events-container';
    } else {
      errorMessage.value = result.message || 'Failed to create organization';
      scrollToError();
      console.error('Failed:', result);
    }
  } catch (error) {
    errorMessage.value = 'Error submitting form. Please try again.';
    scrollToError();
    console.error('Error submitting form:', error);
  }
};
</script>

<template>
  <div class="form-container w-full mx-auto">
    <div v-if="errorMessage" ref="errorMessageRef" class="error-message">
      {{ errorMessage }}
    </div>
    
    <h2 class="section-title">Organization</h2>
    <div class="form-field">
      <label class="field-label">Organization name <span class="text-red-500">*</span></label>
      <input 
        type="text" 
        required
        v-model="organization.name" 
        placeholder="Organization name" 
        class="input-field"
      />
    </div>

    <div class="form-field">
      <label class="field-label">Description</label>
      <textarea 
        v-model="organization.description" 
        placeholder="Organization description" 
        class="input-field"
        rows="3"
      ></textarea>
    </div>

    <div class="form-field">
      <label class="field-label">Email <span class="text-red-500">*</span></label>
      <input 
        type="email" 
        required
        v-model="organization.email" 
        placeholder="Email" 
        class="input-field"
      />
    </div>
    
    <div class="form-field">
      <label class="field-label">Phone</label>
      <input 
        type="tel" 
        v-model="organization.phone" 
        placeholder="Phone" 
        class="input-field"
      />
    </div>
    
    <div class="form-field">
      <label class="field-label">Address</label>
      <input 
        type="text" 
        v-model="organization.address" 
        placeholder="Address" 
        class="input-field"
      />
    </div>
    
    <div class="form-field">
      <label class="field-label">City</label>
      <input 
        type="text" 
        v-model="organization.city" 
        placeholder="City" 
        class="input-field"
      />
    </div>
    
    <div class="form-field">
      <label class="field-label">Country</label>
      <div class="select-wrapper">
        <select v-model="organization.country" class="input-field select-field">
          <option value="" disabled selected>Select country</option>
          <option v-for="country in countries" :key="country" :value="country">
            {{ country }}
          </option>
        </select>
        <div class="select-arrow">▼</div>
      </div>
    </div>

    <div class="form-field">
      <label class="field-label">Website</label>
      <input 
        type="url" 
        v-model="organization.website" 
        placeholder="Website URL" 
        class="input-field"
      />
    </div>

    <div class="form-field">
      <label class="field-label">Organization Type <span class="text-red-500">*</span></label>
      <div class="select-wrapper">
        <select v-model="organization.type" class="input-field select-field" required>
          <option value="" disabled selected>Select type</option>
          <option value="business">Business</option>
          <option value="non-profit">Non-Profit</option>
          <option value="educational">Educational</option>
          <option value="sports">Sports</option>
        </select>
        <div class="select-arrow">▼</div>
      </div>
    </div>
    
    <button class="checkout-button" @click="submitForm">
      Create organization
    </button>
  </div>
</template>

<style scoped>
.form-container {
  max-width: 500px;
  padding: 20px;
  background-color: #000;
  color: #fff;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
}

.error-message {
  background-color: #ff4444;
  color: white;
  padding: 10px;
  margin-bottom: 20px;
  border-radius: 4px;
}

.section-title {
  font-size: 24px;
  font-weight: 500;
  margin-bottom: 16px;
  margin-top: 24px;
  color: #fff;
}

.form-field {
  margin-bottom: 16px;
  position: relative;
}

.field-label {
  display: block;
  font-size: 14px;
  margin-bottom: 4px;
  color: #9ca3af;
}

.input-field {
  width: 100%;
  padding: 12px 16px;
  background-color: transparent;
  border: 1px solid #333;
  border-radius: 6px;
  color: #fff;
  font-size: 16px;
  outline: none;
  transition: border-color 0.2s;
}

.input-field:focus {
  border-color: #666;
}

.input-field::placeholder {
  color: #666;
}

.select-wrapper {
  position: relative;
}

.select-field {
  appearance: none;
  padding-right: 30px;
  background-color: #000;
  color: #fff;
}

.select-field option {
  background-color: #222;
  color: #fff;
}

.select-arrow {
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
  color: #666;
  font-size: 12px;
  pointer-events: none;
}

.checkout-button {
  width: 100%;
  padding: 12px;
  background-color: #2c3edc;
  color: #fff;
  border: none;
  border-radius: 6px;
  font-size: 16px;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.2s;
  margin-top: 24px;
}

.checkout-button:hover {
  background-color: #1e2db3;
}
</style>