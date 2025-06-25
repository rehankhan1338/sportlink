<template>
  <nav class="bg-black text-white shadow-md">
    <div class="container mx-auto px-4 py-2">
      <div class="flex items-center justify-between">
        <!-- Logo -->
        <div class="navbar-header">
          <InertiaLink :href="route('home')" class="navbar-brand">
            <img :src="Logo" alt="Logo" class="rounded-[50px] h-[60px] w-[60px]" />
          </InertiaLink>
        </div>

        <!-- Mobile Menu Button -->
        <button @click="toggleMobileMenu" class="md:hidden text-white">
          <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
          </svg>
        </button>

        <!-- Desktop Menu -->
        <ul class="hidden md:flex space-x-4">
          <li>
            <InertiaLink :href="route('event')" class="text-white">Events</InertiaLink>
          </li>
          <!-- About Dropdown -->
          <li class="relative">
            <a href="#" @click.prevent="toggleDropdown('about')" class="text-white flex items-center">
              <span>About</span>
              <svg class="w-4 h-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </a>
            <ul v-show="activeDropdown === 'about'"
              class="absolute left-0 mt-2 space-y-2 bg-black rounded shadow-md w-[15rem] min-w-[15rem] py-2 z-50"
              @click.stop>
              <li>
                <InertiaLink :href="route('features.pricing')" class="block px-4 py-2 text-white">Features & Pricing
                </InertiaLink>
              </li>
              <li>
                <InertiaLink :href="route('features.scoreboard')" class="block px-4 py-2 text-white">Scoreboard
                </InertiaLink>
              </li>
              <li>
                <InertiaLink :href="route('features.streaming')" class="block px-4 py-2 text-white">Livestreams
                </InertiaLink>
              </li>
              <li>
                <InertiaLink :href="route('features.federation-platform')" class="block px-4 py-2 text-white">Federation
                  Platform</InertiaLink>
              </li>
              <li>
                <InertiaLink :href="route('about')" class="block px-4 py-2 text-white">About SportLink</InertiaLink>
              </li>
              <li>
                <InertiaLink :href="route('support')" class="block px-4 py-2 text-white">Support</InertiaLink>
              </li>
            </ul>
          </li>

          <!-- Community Dropdown -->
          <li class="relative">
            <a href="#" @click.prevent="toggleDropdown('community')" class="text-white flex items-center">
              <span>Community</span>
              <svg class="w-4 h-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </a>
            <ul v-show="activeDropdown === 'community'"
              class="absolute left-0 mt-2 space-y-2 bg-black rounded shadow-md w-[15rem] min-w-[15rem] py-2 z-50"
              @click.stop>
              <li>
                <InertiaLink :href="route('club.finder')" class="block px-4 py-2 text-white">Academies</InertiaLink>
              </li>
              <li>
                <InertiaLink :href="route('affiliation')" class="block px-4 py-2 text-white">Affiliations</InertiaLink>
              </li>
              <li>
                <InertiaLink :href="route('athletes')" class="block px-4 py-2 text-white">Athletes</InertiaLink>
              </li>
            </ul>
          </li>

          <!-- Login & Create Account -->
          <template v-if="$page.props.auth.user">
            <!-- Logged-in Dropdown -->
            <li class="relative">
              <a href="#" @click.prevent="toggleDropdown('account')" class="text-white flex items-center">
                <span class="capitalize font-bold">
                  {{ $page.props.selectedProfile ? $page.props.selectedProfile.name : $page.props.auth.user.name }}
                </span>
                <svg class="w-4 h-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </a>
              <ul v-show="activeDropdown === 'account'"
                class="absolute left-0 mt-2 space-y-2 bg-black rounded shadow-md w-[12rem] py-2 z-50"
                @click.stop>
                <li>
                  <InertiaLink :href="route('dashboard')" class="block px-4 py-2 text-white">Dashboard</InertiaLink>
                </li>
                <li>
                  <InertiaLink :href="route('select-profile')" class="block px-4 py-2 text-white">Switch Profile</InertiaLink>
                </li>
                <li>
                  <InertiaLink :href="route('dashboard') + '#events-container'" class="block px-4 py-2 text-white">My Events</InertiaLink>
                </li>
                <li>
                  <InertiaLink :href="route('profile.edit')" class="block px-4 py-2 text-white">Settings</InertiaLink>
                </li>
                <li>
                  <InertiaLink :href="route('logout')" method="post" as="button" class="block px-4 py-2 text-white w-full text-left">Logout</InertiaLink>
                </li>
              </ul>
            </li>
          </template>

          <template v-else>
            <!-- Show login/register if not logged in -->
            <li>
              <InertiaLink :href="route('login')" class="text-white bg-red-600 account-button">Log in</InertiaLink>
            </li>
            <li>
              <InertiaLink :href="route('register')" class="text-white account-button create-account">Create account</InertiaLink>
            </li>
          </template>
        </ul>
      </div>
    </div>

    <!-- Mobile Menu -->
    <transition name="slide-fade">
      <div v-show="isMobileMenuOpen" class="fixed inset-0 bg-black bg-opacity-75 z-50">
        <div class="absolute right-0 w-full md:w-64 bg-black text-white h-full shadow-lg">
          <button @click="toggleMobileMenu" class="p-4 text-white">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
          <ul class="dropdown-menu flex flex-col space-y-2 p-4">
            <!-- Account Section -->
            <li class="font-bold text-lg">Account</li>
            <li class="account">
              <InertiaLink :href="route('login')">Log in</InertiaLink>
              <InertiaLink :href="route('register')">Create account</InertiaLink>
            </li>

            <!-- Events Section -->
            <li class="font-bold text-lg mt-4">Events</li>
            <li class="menu-events">
              <InertiaLink :href="route('event')" class="title">Upcoming events</InertiaLink>
            </li>
            <li class="menu-events">
              <InertiaLink :href="route('event')">Past events</InertiaLink>
            </li>
            <li class="menu-events">
              <InertiaLink :href="route('event')">My events</InertiaLink>
            </li>

            <!-- Community Section -->
            <li class="font-bold text-lg mt-4">Community</li>
            <li class="community">
              <InertiaLink :href="route('club.finder')">Academies</InertiaLink>
            </li>
            <li class="community">
              <InertiaLink :href="route('affiliation')">Affiliations</InertiaLink>
            </li>
            <li class="community">
              <InertiaLink :href="route('athletes')">Athletes</InertiaLink>
            </li>

            <!-- About Section -->
            <li class="font-bold text-lg mt-4">About</li>
            <li>
              <InertiaLink :href="route('features.pricing')">Features & Pricing</InertiaLink>
            </li>
            <li>
              <InertiaLink :href="route('features.scoreboard')">Scoreboard</InertiaLink>
            </li>
            <li>
              <InertiaLink :href="route('features.streaming')">Livestreams</InertiaLink>
            </li>
            <li>
              <InertiaLink :href="route('features.federation-platform')">Federation Platform</InertiaLink>
            </li>
            <li>
              <InertiaLink :href="route('about')">About SportLink</InertiaLink>
            </li>
            <li>
              <InertiaLink :href="route('support')">Support</InertiaLink>
            </li>
          </ul>
        </div>
      </div>
    </transition>
  </nav>
</template>

<script setup>
import { InertiaLink } from '@inertiajs/inertia-vue3';
import Logo from '@/assets/mainlogowebsite.jpg';
import { ref } from 'vue';

// Track which dropdown is active
const activeDropdown = ref(null);
const isMobileMenuOpen = ref(false); // Track mobile menu state

console.log(route('club.finder'));
// Toggle dropdown based on the name
const toggleDropdown = (dropdownName) => {
  activeDropdown.value = activeDropdown.value === dropdownName ? null : dropdownName;
};

// Toggle mobile menu
const toggleMobileMenu = () => {
  isMobileMenuOpen.value = !isMobileMenuOpen.value;
};
</script>
<style scoped>
a.account-button {
  padding: 7px 15px;
  margin: 11px;
  border-radius: 3px;
}

a.create-account {
  background: #8d9799;
  margin-left: 0;
  margin-right: 0;
}

nav.primary ul li a {
  color: #fff;
  border-right: 1px solid transparent;
  padding: 1.125rem 1.425rem;
}

.dropdown-menu {
  padding: 1rem;
}

.slide-fade-enter-active,
.slide-fade-leave-active {
  transition: opacity 0.5s, transform 0.5s;
}

.slide-fade-enter,
.slide-fade-leave-to {
  opacity: 0;
  transform: translateX(100%);
}
</style>
