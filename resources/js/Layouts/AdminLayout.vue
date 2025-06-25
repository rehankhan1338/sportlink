<template>
    <div class="min-h-screen bg-gray-100">
        <!-- Off-canvas menu for mobile -->
        <TransitionRoot as="template" :show="sidebarOpen">
            <Dialog as="div" class="relative z-40 md:hidden" @close="sidebarOpen = false">
                <TransitionChild
                    as="template"
                    enter="transition-opacity ease-linear duration-300"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="transition-opacity ease-linear duration-300"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div class="fixed inset-0 bg-gray-600 bg-opacity-75" />
                </TransitionChild>

                <div class="fixed inset-0 z-40 flex">
                    <TransitionChild
                        as="template"
                        enter="transition ease-in-out duration-300 transform"
                        enter-from="-translate-x-full"
                        enter-to="translate-x-0"
                        leave="transition ease-in-out duration-300 transform"
                        leave-from="translate-x-0"
                        leave-to="-translate-x-full"
                    >
                        <DialogPanel class="relative flex w-full max-w-xs flex-1 flex-col bg-gray-800">
                            <div class="absolute top-0 right-0 -mr-12 pt-2">
                                <button
                                    type="button"
                                    class="ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                                    @click="sidebarOpen = false"
                                >
                                    <span class="sr-only">Close sidebar</span>
                                    <XMarkIcon class="h-6 w-6 text-white" aria-hidden="true" />
                                </button>
                            </div>

                            <div class="h-0 flex-1 overflow-y-auto pt-5 pb-4">
                                <div class="flex flex-shrink-0 items-center px-4">
                                    <ApplicationLogo class="h-8 w-auto text-white" />
                                </div>
                                <nav class="mt-5 space-y-1 px-2">
                                    <Link
                                        :href="route('admin.dashboard')"
                                        class="group flex items-center px-2 py-2 text-base font-medium rounded-md"
                                        :class="[
                                            route().current('admin.dashboard')
                                                ? 'bg-gray-900 text-white'
                                                : 'text-gray-300 hover:bg-gray-700 hover:text-white'
                                        ]"
                                    >
                                        <HomeIcon class="mr-4 h-6 w-6 flex-shrink-0" aria-hidden="true" />
                                        Dashboard
                                    </Link>

                                    <Link
                                        :href="route('admin.users.index')"
                                        class="group flex items-center px-2 py-2 text-base font-medium rounded-md"
                                        :class="[
                                            route().current('admin.users.*')
                                                ? 'bg-gray-900 text-white'
                                                : 'text-gray-300 hover:bg-gray-700 hover:text-white'
                                        ]"
                                    >
                                        <UsersIcon class="mr-4 h-6 w-6 flex-shrink-0" aria-hidden="true" />
                                        Users
                                    </Link>

                                    <Link
                                        :href="route('admin.events.index')"
                                        class="group flex items-center px-2 py-2 text-base font-medium rounded-md"
                                        :class="[
                                            route().current('admin.events.*')
                                                ? 'bg-gray-900 text-white'
                                                : 'text-gray-300 hover:bg-gray-700 hover:text-white'
                                        ]"
                                    >
                                        <CalendarIcon class="mr-4 h-6 w-6 flex-shrink-0" aria-hidden="true" />
                                        Events
                                    </Link>

                                    <Link
                                        :href="route('admin.affiliations.index')"
                                        class="group flex items-center px-2 py-2 text-base font-medium rounded-md"
                                        :class="[
                                            route().current('admin.affiliations.*')
                                                ? 'bg-gray-900 text-white'
                                                : 'text-gray-300 hover:bg-gray-700 hover:text-white'
                                        ]"
                                    >
                                        <BuildingOfficeIcon class="mr-4 h-6 w-6 flex-shrink-0" aria-hidden="true" />
                                        Affiliations
                                    </Link>

                                    <Link
                                        :href="route('admin.academies.index')"
                                        class="group flex items-center px-2 py-2 text-base font-medium rounded-md"
                                        :class="[
                                            route().current('admin.academies.*')
                                                ? 'bg-gray-900 text-white'
                                                : 'text-gray-300 hover:bg-gray-700 hover:text-white'
                                        ]"
                                    >
                                        <AcademicCapIcon class="mr-4 h-6 w-6 flex-shrink-0" aria-hidden="true" />
                                        Academies
                                    </Link>

                                    <Link
                                        :href="route('admin.organizations.index')"
                                        class="group flex items-center px-2 py-2 text-base font-medium rounded-md"
                                        :class="[
                                            route().current('admin.organizations.*')
                                                ? 'bg-gray-900 text-white'
                                                : 'text-gray-300 hover:bg-gray-700 hover:text-white'
                                        ]"
                                    >
                                        <BuildingLibraryIcon class="mr-4 h-6 w-6 flex-shrink-0" aria-hidden="true" />
                                        Organizations
                                    </Link>

                                    <Link
                                        :href="route('admin.brackets.index')"
                                        class="group flex items-center px-2 py-2 text-base font-medium rounded-md"
                                        :class="[
                                            route().current('admin.brackets.*')
                                                ? 'bg-gray-900 text-white'
                                                : 'text-gray-300 hover:bg-gray-700 hover:text-white'
                                        ]"
                                    >
                                        <ChartBarIcon class="mr-4 h-6 w-6 flex-shrink-0" aria-hidden="true" />
                                        Brackets
                                    </Link>

                                    <Link
                                        :href="route('admin.matches.index')"
                                        class="group flex items-center px-2 py-2 text-base font-medium rounded-md"
                                        :class="[
                                            route().current('admin.matches.*')
                                                ? 'bg-gray-900 text-white'
                                                : 'text-gray-300 hover:bg-gray-700 hover:text-white'
                                        ]"
                                    >
                                        <LinkIcon class="mr-4 h-6 w-6 flex-shrink-0" aria-hidden="true" />
                                        Matches
                                    </Link>
                                </nav>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </Dialog>
        </TransitionRoot>

        <!-- Static sidebar for desktop -->
        <div class="hidden md:fixed md:inset-y-0 md:flex md:w-64 md:flex-col">
            <div class="flex min-h-0 flex-1 flex-col bg-gray-800">
                <div class="flex flex-1 flex-col overflow-y-auto pt-5 pb-4">
                    <div class="flex flex-shrink-0 items-center px-4">
                        <ApplicationLogo class="h-8 w-auto text-white" />
                    </div>
                    <nav class="mt-5 flex-1 space-y-1 px-2">
                        <Link
                            :href="route('admin.dashboard')"
                            class="group flex items-center px-2 py-2 text-sm font-medium rounded-md"
                            :class="[
                                route().current('admin.dashboard')
                                    ? 'bg-gray-900 text-white'
                                    : 'text-gray-300 hover:bg-gray-700 hover:text-white'
                            ]"
                        >
                            <HomeIcon class="mr-3 h-6 w-6 flex-shrink-0" aria-hidden="true" />
                            Dashboard
                        </Link>

                        <Link
                            :href="route('admin.users.index')"
                            class="group flex items-center px-2 py-2 text-sm font-medium rounded-md"
                            :class="[
                                route().current('admin.users.*')
                                    ? 'bg-gray-900 text-white'
                                    : 'text-gray-300 hover:bg-gray-700 hover:text-white'
                            ]"
                        >
                            <UsersIcon class="mr-3 h-6 w-6 flex-shrink-0" aria-hidden="true" />
                            Users
                        </Link>

                        <Link
                            :href="route('admin.events.index')"
                            class="group flex items-center px-2 py-2 text-sm font-medium rounded-md"
                            :class="[
                                route().current('admin.events.*')
                                    ? 'bg-gray-900 text-white'
                                    : 'text-gray-300 hover:bg-gray-700 hover:text-white'
                            ]"
                        >
                            <CalendarIcon class="mr-3 h-6 w-6 flex-shrink-0" aria-hidden="true" />
                            Events
                        </Link>

                        <Link
                            :href="route('admin.affiliations.index')"
                            class="group flex items-center px-2 py-2 text-sm font-medium rounded-md"
                            :class="[
                                route().current('admin.affiliations.*')
                                    ? 'bg-gray-900 text-white'
                                    : 'text-gray-300 hover:bg-gray-700 hover:text-white'
                            ]"
                        >
                            <BuildingOfficeIcon class="mr-3 h-6 w-6 flex-shrink-0" aria-hidden="true" />
                            Affiliations
                        </Link>

                        <Link
                            :href="route('admin.academies.index')"
                            class="group flex items-center px-2 py-2 text-sm font-medium rounded-md"
                            :class="[
                                route().current('admin.academies.*')
                                    ? 'bg-gray-900 text-white'
                                    : 'text-gray-300 hover:bg-gray-700 hover:text-white'
                            ]"
                        >
                            <AcademicCapIcon class="mr-3 h-6 w-6 flex-shrink-0" aria-hidden="true" />
                            Academies
                        </Link>

                        <Link
                            :href="route('admin.organizations.index')"
                            class="group flex items-center px-2 py-2 text-sm font-medium rounded-md"
                            :class="[
                                route().current('admin.organizations.*')
                                    ? 'bg-gray-900 text-white'
                                    : 'text-gray-300 hover:bg-gray-700 hover:text-white'
                            ]"
                        >
                            <BuildingLibraryIcon class="mr-3 h-6 w-6 flex-shrink-0" aria-hidden="true" />
                            Organizations
                        </Link>

                        <Link
                            :href="route('admin.brackets.index')"
                            class="group flex items-center px-2 py-2 text-sm font-medium rounded-md"
                            :class="[
                                route().current('admin.brackets.*')
                                    ? 'bg-gray-900 text-white'
                                    : 'text-gray-300 hover:bg-gray-700 hover:text-white'
                            ]"
                        >
                            <ChartBarIcon class="mr-3 h-6 w-6 flex-shrink-0" aria-hidden="true" />
                            Brackets
                        </Link>

                        <Link
                            :href="route('admin.matches.index')"
                            class="group flex items-center px-2 py-2 text-sm font-medium rounded-md"
                            :class="[
                                route().current('admin.matches.*')
                                    ? 'bg-gray-900 text-white'
                                    : 'text-gray-300 hover:bg-gray-700 hover:text-white'
                            ]"
                        >
                            <LinkIcon class="mr-3 h-6 w-6 flex-shrink-0" aria-hidden="true" />
                            Matches
                        </Link>
                    </nav>
                </div>
            </div>
        </div>

        <div class="flex flex-1 flex-col md:pl-64">
            <div class="sticky top-0 z-10 bg-gray-100 pl-1 pt-1 sm:pl-3 sm:pt-3 md:hidden">
                <button
                    type="button"
                    class="-ml-0.5 -mt-0.5 inline-flex h-12 w-12 items-center justify-center rounded-md text-gray-500 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
                    @click="sidebarOpen = true"
                >
                    <span class="sr-only">Open sidebar</span>
                    <Bars3Icon class="h-6 w-6" aria-hidden="true" />
                </button>
            </div>

            <!-- Header -->
            <div class="sticky top-0 z-10 flex h-16 flex-shrink-0 bg-white shadow">
                <div class="flex flex-1 justify-between px-4">
                    <div class="flex flex-1">
                        <slot name="header">
                            <h1 class="text-2xl font-semibold text-gray-900 my-auto">{{ title }}</h1>
                        </slot>
                    </div>
                    <div class="ml-4 flex items-center md:ml-6">
                        <!-- Profile dropdown -->
                        <Menu as="div" class="relative ml-3">
                            <div>
                                <MenuButton class="flex max-w-xs items-center rounded-full bg-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    <span class="sr-only">Open user menu</span>
                                    <UserCircleIcon class="h-8 w-8 text-gray-400" aria-hidden="true" />
                                </MenuButton>
                            </div>
                            <transition
                                enter-active-class="transition ease-out duration-100"
                                enter-from-class="transform opacity-0 scale-95"
                                enter-to-class="transform opacity-100 scale-100"
                                leave-active-class="transition ease-in duration-75"
                                leave-from-class="transform opacity-100 scale-100"
                                leave-to-class="transform opacity-0 scale-95"
                            >
                                <MenuItems class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                    <MenuItem v-slot="{ active }">
                                        <Link
                                            :href="route('admin.logout')"
                                            method="post"
                                            as="button"
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700"
                                            :class="{ 'bg-gray-100': active }"
                                        >
                                            Logout
                                        </Link>
                                    </MenuItem>
                                </MenuItems>
                            </transition>
                        </Menu>
                    </div>
                </div>
            </div>

            <main class="flex-1">
                <div class="py-6">
                    <div class="mx-auto max-w-7xl px-4 sm:px-6 md:px-8">
                        <slot />
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import {
    Dialog,
    DialogPanel,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    TransitionChild,
    TransitionRoot,
} from '@headlessui/vue';
import {
    Bars3Icon,
    HomeIcon,
    UsersIcon,
    CalendarIcon,
    LinkIcon,
    AcademicCapIcon,
    ChartBarIcon,
    XMarkIcon,
    UserCircleIcon,
    BuildingOfficeIcon,
    BuildingLibraryIcon,
} from '@heroicons/vue/24/outline';
import { Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

defineProps({
    title: {
        type: String,
        required: true
    }
});

const sidebarOpen = ref(false);
</script> 