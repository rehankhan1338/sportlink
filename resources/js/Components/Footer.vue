<template>

<div class="footer">
    <footer>
        <div class="container mx-auto px-4">
            <div class="flex flex-col items-center text-center">
                <div class="mb-4 max-w-xl">
                    <p class="text-gray-700 text-sm">
                        Sportlink is a tournament software for combat sports. The smoothest way to participate,
                        organize and follow competitions. See you on the mat!
                    </p>
                </div>
                <div class="flex justify-center flex-wrap gap-4 mb-4">
                    <a href="https://www.youtube.com/channel/UCdAUQHslnp2OpY3RG4F0ftQ" target="_blank" class="hover:opacity-80">
                        <img :src="youtube" alt="YouTube" class="h-6">
                    </a>
                    <a href="https://facebook.com/Sportlink-404690579727843" target="_blank" class="hover:opacity-80">
                        <img :src="facebook" alt="Facebook" class="h-6">
                    </a>
                    <a href="https://instagram.com/sportlink" target="_blank" class="hover:opacity-80">
                        <img :src="instagram" alt="Instagram" class="h-6">
                    </a>
                </div>
                <div class="flex justify-center flex-wrap gap-2">
                    <a href="#" class="btn btn-sm border border-gray-400 px-4 py-2 text-gray-600 hover:bg-gray-100">
                        Get support
                    </a>
                    <a href="#" class="btn btn-sm border border-gray-400 px-4 py-2 text-gray-600 hover:bg-gray-100">
                        Terms of Service
                    </a>
                    <a href="#" class="btn btn-sm border border-gray-400 px-4 py-2 text-gray-600 hover:bg-gray-100">
                        Privacy Policy
                    </a>
                </div>
            </div>
        </div>
    </footer>
</div>


</template>

<script setup>
 import youtube from '@/assets/youtube.svg';
 import facebook from '@/assets/facebook.svg';
 import instagram from '@/assets/instagram.svg';
</script>

<style scoped>
footer {
    background: #171719;
    border-top: 2px #121214 solid;
    color: #fff;
    padding: 60px 0;
}


</style>