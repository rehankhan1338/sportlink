<template>
    <div class="row" bis_skin_checked="1">
        <div class="col-sm-12" bis_skin_checked="1">
            <div class="alert alert-info margin-top-xs-0" role="alert" bis_skin_checked="1">
                <span class="icon-warning"></span> Times are <strong>dynamic</strong> and change in real time. Refresh
                your browser for the latest information.
            </div>
        </div>
    </div>
    <div class="bg-black text-white min-h-screen flex flex-col lg:flex-row p-4 gap-6">
        <!-- Filters -->
        <div class="p-4 rounded-lg w-full lg:w-1/3 bg-gray-800">
            <h2 class="text-lg font-bold mb-4">Filters</h2>
            <div class="space-y-4">
                <input
                    type="text"
                    placeholder="Athlete"
                    class="w-full p-2 rounded-lg bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
                <select
                    class="w-full p-2 rounded-lg bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option>Academy/Club</option>
                </select>
                <select
                    class="w-full p-2 rounded-lg bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option>All categories</option>
                </select>
                <select
                    class="w-full p-2 rounded-lg bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option>Filter on mat</option>
                </select>
                <select
                    class="w-full p-2 rounded-lg bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option>Select country</option>
                </select>
                <button class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold p-2 rounded-lg">
                    Search
                </button>
            </div>
        </div>

        <!-- Match Details -->
        <div class="w-full lg:w-2/3">
            <div
                v-for="match in matches"
                :key="match.id"
                class="bg-[rgb(231_234_255_/_10%)] rounded-lg p-4 mb-4 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4"
            >
                <div>
                    <h3 class="text-sm text-gray-400">{{ match.category }}</h3>
                    <div class="text-2xl font-bold flex items-center">
                        <span>{{ match.score1 }}</span>
                        <span class="mx-2">-</span>
                        <span>{{ match.score2 }}</span>
                    </div>
                    <div class="text-sm mt-1">
                        <p class="text-green-500">{{ match.winner }}</p>
                        <p class="text-gray-400">{{ match.details }}</p>
                    </div>
                </div>
                <button class="bg-gray-700 text-sm text-gray-300 px-4 py-2 rounded-lg">
                    {{ match.status }}
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "MatchList",
    data() {
        return {
            matches: [
                {
                    id: 1,
                    category: "Мальчики 4-5 лет / -18 кг (Day 1)",
                    score1: 2,
                    score2: 1,
                    winner: "Жовид Шомуродов",
                    details: "Won by Submission - 00:14",
                    status: "Finished",
                },
                {
                    id: 2,
                    category: "Мальчики 6-7 лет / -24 кг (Day 1)",
                    score1: 1,
                    score2: 1,
                    winner: "Тургунбоев Мирзийо",
                    details: "Won by Submission - 01:29",
                    status: "Finished",
                },
                {
                    id: 3,
                    category: "Мальчики 6-7 лет / -24 кг (Day 1)",
                    score1: 2,
                    score2: 2,
                    winner: "Парвиз Ярашбаев",
                    details: "Won by Submission - 01:24",
                    status: "Finished",
                },
                // Add more match data as needed
            ],
        };
    },
};
</script>
