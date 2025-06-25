<script setup>
import { usePage } from '@inertiajs/vue3'
import Footer from "@/Components/Footer.vue";
import Navbar from "@/Components/Navbar.vue";
import { onMounted, ref } from 'vue'
import Academyplaceholder from '@/assets/fallbackcover.jpg';

const page = usePage()
const academy = ref(page.props.academy)

onMounted(() => {
    console.log('Full Page Props:', page.props)
    console.log('Academy Data:', academy.value)
    
    // Check if academy data exists
    if (!academy.value) {
        console.error('Academy data is missing from page props')
    } else {
        console.log('Academy Name:', academy.value.name)
        console.log('Academy Logo Path:', academy.value.logo_path)
        console.log('Academy Cover Path:', academy.value.cover_path)
    }
})
</script>

<template>
    <Navbar />
    <div class="">
        <img 
            :src="academy.cover_path ? `/storage/${academy.cover_path}` : Academyplaceholder" 
            alt="Academy Cover" 
            class="w-full h-48 object-cover"
            @error="console.error('Cover image load error:', academy.cover_path)"
        >
    </div>

    <section class="club-page bg-[#141414] py-8">
      <!-- Container -->
      <div class="container mx-auto px-4">
        <div class="flex flex-wrap">
          <!-- Sidebar -->
          <aside class="w-full lg:w-1/4 mb-8 lg:mb-0 relative">

            <div class="flex flex-col items-center justify-center absolute top-[-150px]">
                <img 
            :src="academy.logo_path ? `/storage/${academy.logo_path}` : Academyplaceholder" 
            alt="Academy Cover" 
            class="w-[200px] h-[200px] rounded-full object-cover border-[5px] border-white"
            @error="console.error('Cover image load error:', academy.logo_path)"
        >
            </div>

            <!-- Stats -->
            <div class=" p-4 rounded shadow mt-20">
              <div class="mb-4 flex items-center text-gray-600">
                <span class="material-icons mr-2">place</span>
                <span class="text-white"> {{ academy.country || '' }}</span>
              </div>
  
              <!-- Uncomment for members -->
              <!--
              <div class="flex items-center text-gray-600">
                <span class="material-icons mr-2">group</span>
                <span>9 members</span>
              </div>
              -->
            </div>
  
            <!-- Navigation -->
            <ul class="space-y-2">
              <li>
                <a href="" class="block py-2 px-4 bg-blue-500 text-white rounded">Home</a>
              </li>
              <!-- <li>
                <a href="" class="block py-2 px-4 text-white rounded hover:bg-gray-300 hover:text-black">Statistics</a>
              </li> -->
            </ul>
  
            <!-- Join Button -->
            <!-- <div class="mt-8">
              <button class="w-full py-2 px-4 bg-blue-500 text-white rounded hover:bg-blue-600">Join academy</button>
            </div> -->
          </aside>
  
          <!-- Main Content -->
          <main class="w-full lg:w-3/4 lg:pl-8">

        <div class="mb-5">
            <h1 class="text-[33px] text-white text-2xl font-bold">{{ academy.name }}</h1>
          </div>

            <!-- Club Map -->
            <div class="mb-8">
              <iframe
                width="100%"
                height="288"
                frameborder="0"
                style="border:0"
                :src="`https://www.google.com/maps/embed/v1/place?key=AIzaSyDLMAeXDeS3U_fDPme-UAD_k5RycKzpJBs&q=${encodeURIComponent(academy.address + ', ' + academy.city + ', ' + academy.country)}`"
                allowfullscreen>
              </iframe>
            </div>
  
            <!-- Club Info -->
            <div class="bg-white p-6 rounded shadow mb-8">
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                  <p class="text-gray-600">
                    <span class="font-semibold">Location:</span><br>
                    <p class="text-gray-600">{{ academy.address || 'Address not available' }}</p>
              <p class="text-gray-600">{{ academy.city || 'City' }}, {{ academy.country || 'Country' }}</p>
                  </p>
                </div>
  
                <div>
                  <p class="text-gray-600">
                    <span class="font-semibold">Phone:</span><br>
                    <a href="tel:+380639587188" class="text-blue-500 hover:underline">{{ academy.phone || 'Not available' }}</a>
                  </p>
                </div>
  
                <div>
                  <p class="text-gray-600">
                    <span class="font-semibold">Person in charge:</span><br>
                    {{ academy.person_in_charge || 'Not specified' }}
                  </p>
                </div>
              </div>
            </div>
  
            <!-- Contact Persons -->
            <div class="bg-white p-6 rounded shadow mb-8">
              <h3 class="text-lg font-semibold mb-4">Contact Persons</h3>
              <table class="w-full ">
                <tbody>
                  <tr>
                    <td class="p-2">{{ academy.person_in_charge || 'Not specified' }}</td>
                    <td class="p-2 w-24">
                      <span class="text-sm bg-blue-500 text-white px-2 py-1 rounded">Manager</span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
  
         
          </main>
        </div>
      </div>
    </section>


    <Footer />


  
</template>

<style scoped>
/* Import Material Icons */
@import url("https://fonts.googleapis.com/icon?family=Material+Icons");
</style>