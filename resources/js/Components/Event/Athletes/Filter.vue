<template>
    <div class="min-h-screen text-white p-6">
        <h1 class="text-2xl font-bold mb-6">Athletes</h1>
        <div class="bg-[rgba(231,234,255,0.1)] mb-8 p-6 rounded-lg w-full mx-auto">
            <form class="flex flex-col gap-4 md:flex-row">
                <!-- Search Field -->
                <input type="text"
                    class="flex-1 p-3 rounded-lg bg-gray-500 text-white placeholder-gray-400 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Search athletes for division" />

                <!-- Country Dropdown -->
                <div class="flex-1 relative">
                    <select
                        class="w-full p-3 rounded-lg bg-gray-500 text-white placeholder-gray-400 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none">
                        <option value="" disabled selected>Choose a country</option>
                        <option value="usa">🇺🇸 United States</option>
                        <option value="canada">🇨🇦 Canada</option>
                        <option value="uk">🇬🇧 United Kingdom</option>
                        <option value="india">🇮🇳 India</option>
                        <option value="australia">🇦🇺 Australia</option>
                    </select>

                    <div
                        class="absolute top-0 right-0 h-full w-10 flex items-center justify-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.23 7.21a.75.75 0 011.06-.02L10 10.94l3.72-3.75a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.23 8.27a.75.75 0 01-.02-1.06z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </form>
        </div>

        <div class="container mx-auto">
            <div class="participant-group mb-16">
                <div class="heading-container mb-4">
                    <h1 class="group-name text-xl sm:text-2xl lg:text-4xl font-semibold text-center sm:text-left">
                        Boys &amp; Juvenile Gi / Kids - White/Grey / Kids 2 (6 - 7 yrs) / -18 kg
                    </h1>
                </div>

                <!-- Table Heading (Visible for larger screens) -->
                <div
                    class="participants-table-head participant-tr muted mb-2 hidden sm:flex justify-between items-center p-4 border-b border-gray-300">
                    <span class="participant-td participant-td-athlete pl-0 pr-4 font-semibold">Athlete</span>
                    <span class="participant-td participant-td-birth font-semibold">Birth</span>
                    <span class="participant-td participant-td-club font-semibold">Academy & Affiliation</span>
                    <span class="participant-td participant-td-registration font-semibold">Registration</span>
                </div>

                <!-- Profile Cards for Mobile View -->
                <div class="profile-cards approved participants-mobile block sm:hidden mb-4">
                    <div
                        class="participant-tr participant-tr-main flex flex-col gap-4 p-4 border-b border-gray-300 bg-gray-800 rounded-lg">
                        <!-- Athlete Info -->
                        <div class="flex items-center">
                            <img src="https://smoothcomp.com/pictures/t/5147626-u0rz/asanali-aydarbekov.jpg"
                                alt="Asanali Aydarbekov" class="w-16 h-16 rounded-full object-cover mr-4">
                            <div>
                                <a href="https://smoothcomp.com/en/profile/1822119"
                                    class="text-sm font-semibold truncate block">Asanali Aydarbekov</a>
                                <div class="profile-country flex items-center text-gray-400">
                                    <span class="flag-icon mr-2 flag-icon-kz"></span>
                                    <span class="font-tiny">Kazakhstan</span>
                                </div>
                            </div>
                        </div>
                        <!-- Additional Info -->
                        <div class="flex flex-col text-gray-300 text-sm mt-2">
                            <div><b>Birth:</b> 2019 (5 years)</div>
                            <div><b>Club:</b> ARMJJ INTERNATIONAL TEAM</div>
                            <div><b>Affiliation:</b> Alliance</div>
                            <div><b>Registration:</b> Kids - White/Grey, Kids 2 (6 - 7 yrs), -18 kg</div>
                        </div>
                    </div>
                </div>

                <!-- Profile Cards for Desktop View -->
                <div class="profile-cards unapproved participants-desktop hidden lg:block mb-4">
                    <div
                        class="participant-tr participant-tr-main flex justify-between items-center p-4 border-b border-gray-300">
                        <div class="participant-td participant-td-athlete flex items-center">
                            <img src="https://smoothcomp.com/pictures/t/5147626-u0rz/asanali-aydarbekov.jpg"
                                alt="Asanali Aydarbekov" class="w-16 h-16 rounded-full object-cover mr-4">
                            <div>
                                <a href="https://smoothcomp.com/en/profile/1822119"
                                    class="text-sm font-semibold truncate block">Asanali Aydarbekov</a>
                                <div class="profile-country flex items-center text-gray-600">
                                    <span class="flag-icon mr-2 flag-icon-kz"></span>
                                    <span class="font-tiny">Kazakhstan</span>
                                </div>
                            </div>
                        </div>
                        <div class="participant-td participant-td-birth text-center">
                            <div class="font-medium">2019</div>
                            <div class="muted text-xs">5 years</div>
                        </div>
                        <div class="participant-td participant-td-club text-center">
                            <a href="https://smoothcomp.com/en/club/31123"
                                class="text-sm font-semibold block truncate">ARMJJ INTERNATIONAL TEAM</a>
                            <a href="https://smoothcomp.com/en/affiliation/4"
                                class="text-sm font-semibold block truncate">Alliance</a>
                        </div>
                        <div class="participant-td participant-td-registration text-center">
                            <div class="truncate-2-rows text-sm">Kids - White/Grey</div>
                            <div class="truncate-2-rows text-sm">Kids 2 (6 - 7 yrs)</div>
                            <div class="truncate-2-rows text-sm">-18 kg</div>
                        </div>
                    </div>
                </div>

                <div class="hidden-regs-container mt-4">
                    <div class="mute text-gray-500 mb-4">Approved registrations: 0</div>
                    <a href="#" class="text-indigo-500 hover:underline">Show unapproved registrations (2)</a>
                </div>
            </div>
        </div>
    </div>
</template>
