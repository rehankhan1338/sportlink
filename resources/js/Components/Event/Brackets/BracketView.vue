<template>
  <div class="bg-gray-900 min-h-screen text-white p-8">
    <!-- Bracket Header -->
    <div class="mb-8">
      <h1 class="text-2xl font-bold mb-4">{{ bracket?.name }}</h1>
      <div class="flex flex-wrap gap-4 text-sm">
        <div class="bg-gray-800 px-4 py-2 rounded-lg">
          <span class="text-gray-400">Category:</span>
          <span class="ml-2">Day {{ bracket?.day }}</span>
        </div>
        <div class="bg-gray-800 px-4 py-2 rounded-lg">
          <span class="text-gray-400">Area:</span>
          <span class="ml-2">Mat {{ bracket?.mat_number }}</span>
        </div>
        <div class="bg-gray-800 px-4 py-2 rounded-lg">
          <span class="text-gray-400">Bracket type:</span>
          <span class="ml-2">{{ bracket?.type }}</span>
        </div>
        <div class="bg-gray-800 px-4 py-2 rounded-lg">
          <span class="text-gray-400">Participants:</span>
          <span class="ml-2">{{ bracket?.participants_count }}</span>
        </div>
        <div class="bg-gray-800 px-4 py-2 rounded-lg">
          <span class="text-gray-400">Nr of matches:</span>
          <span class="ml-2">{{ bracket?.matches_count }}</span>
        </div>
        <div class="bg-gray-800 px-4 py-2 rounded-lg">
          <span class="text-gray-400">Time per match:</span>
          <span class="ml-2">{{ bracket?.time_per_match }}</span>
        </div>
      </div>
    </div>

    <!-- Tournament Bracket -->
    <div class="tournament-bracket">
      <div class="rounds flex justify-between gap-8">
        <!-- Semifinals -->
        <div class="round">
          <h3 class="text-gray-400 mb-4">Semifinals</h3>
          <div class="matches space-y-4">
            <div v-for="match in bracket?.semifinals" :key="match.id" 
                 class="match bg-gray-800 rounded-lg overflow-hidden">
              <div class="player p-3" :class="{ 'winner': match.player1.isWinner }">
                <div class="flex items-center gap-3">
                  <img :src="match.player1.avatar" class="w-12 h-12 rounded-full">
                  <div>
                    <div class="flex items-center gap-2">
                      <span class="flag-icon" :class="'flag-icon-' + match.player1.country.toLowerCase()"></span>
                      <span>{{ match.player1.name }}</span>
                    </div>
                    <div class="text-sm text-gray-400">{{ match.player1.team }}</div>
                  </div>
                </div>
              </div>
              <div class="player p-3" :class="{ 'winner': match.player2.isWinner }">
                <div class="flex items-center gap-3">
                  <img :src="match.player2.avatar" class="w-12 h-12 rounded-full">
                  <div>
                    <div class="flex items-center gap-2">
                      <span class="flag-icon" :class="'flag-icon-' + match.player2.country.toLowerCase()"></span>
                      <span>{{ match.player2.name }}</span>
                    </div>
                    <div class="text-sm text-gray-400">{{ match.player2.team }}</div>
                  </div>
                </div>
              </div>
              <div class="match-info bg-gray-700 px-3 py-2 text-sm">
                <div>Match {{ match.number }}</div>
                <div>{{ match.time }}</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Finals -->
        <div class="round">
          <h3 class="text-gray-400 mb-4">Finals</h3>
          <div class="matches space-y-4">
            <div v-for="match in bracket?.finals" :key="match.id" 
                 class="match bg-gray-800 rounded-lg overflow-hidden">
              <div class="player p-3" :class="{ 'winner': match.player1.isWinner }">
                <div class="flex items-center gap-3">
                  <img :src="match.player1.avatar" class="w-12 h-12 rounded-full">
                  <div>
                    <div class="flex items-center gap-2">
                      <span class="flag-icon" :class="'flag-icon-' + match.player1.country.toLowerCase()"></span>
                      <span>{{ match.player1.name }}</span>
                    </div>
                    <div class="text-sm text-gray-400">{{ match.player1.team }}</div>
                  </div>
                </div>
              </div>
              <div class="player p-3" :class="{ 'winner': match.player2.isWinner }">
                <div class="flex items-center gap-3">
                  <img :src="match.player2.avatar" class="w-12 h-12 rounded-full">
                  <div>
                    <div class="flex items-center gap-2">
                      <span class="flag-icon" :class="'flag-icon-' + match.player2.country.toLowerCase()"></span>
                      <span>{{ match.player2.name }}</span>
                    </div>
                    <div class="text-sm text-gray-400">{{ match.player2.team }}</div>
                  </div>
                </div>
              </div>
              <div class="match-info bg-gray-700 px-3 py-2 text-sm">
                <div>Match {{ match.number }}</div>
                <div>{{ match.time }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps } from 'vue';

const props = defineProps({
  bracket: {
    type: Object,
    default: null
  }
});
</script>

<style scoped>
.tournament-bracket {
  overflow-x: auto;
}

.round {
  min-width: 320px;
}

.match {
  border: 1px solid #374151;
}

.player.winner {
  background-color: rgba(16, 185, 129, 0.1);
  border-left: 4px solid #10B981;
}

.match-info {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

/* Flag icons */
.flag-icon {
  width: 1.33333333em;
  line-height: 1em;
  background-size: contain;
  background-position: 50%;
  background-repeat: no-repeat;
  display: inline-block;
}
</style>
