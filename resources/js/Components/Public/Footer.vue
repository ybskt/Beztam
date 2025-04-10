<template>
  <footer class="bg-[#EBFFF5] py-6">
    <div class="container mx-auto px-6 max-w-6xl">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Address Section -->
        <div class="flex flex-col items-center">
          <h3 class="text-lg font-bold mb-6">Adresse</h3>
          <div class="flex items-center space-x-2 mb-6">
            <img src="@/Assets/IMG/location.png" alt="Location" class="w-5 h-5">
            <p class="text-sm">FSA UIZ Agadir, Maroc</p>
          </div>
          <div class="flex items-center space-x-2 mb-6">
            <img src="@/Assets/IMG/telephone.png" alt="Phone" class="w-5 h-5">
            <p class="text-sm">+212 568-301956</p>
          </div>
          <div class="flex items-center space-x-2">
            <img src="@/Assets/IMG/mail.png" alt="Mail" class="w-5 h-5">
            <p class="text-sm">baztam@support.ma</p>
          </div>
        </div>

        <!-- Links Section -->
        <div class="flex flex-col items-center">
          <h3 class="text-lg font-bold mb-6">Liens</h3>
          <div class="flex flex-row md:flex-col space-x-4 md:space-x-0">
            <Link :href="route('home')" class="text-sm hover:text-green-600 md:mb-3">Home</Link>
            <Link :href="route('features')" class="text-sm hover:text-green-600 md:mb-3">Features</Link>
            <Link :href="route('about')" class="text-sm hover:text-green-600 md:mb-3">About</Link>
            <Link :href="route('contact')" class="text-sm hover:text-green-600">Contact</Link>
          </div>
        </div>

        <!-- Social Media Section -->
        <div class="flex flex-col items-center">
          <h3 class="text-lg font-bold mb-6">Réseaux Sociaux</h3>
          <div class="flex flex-row md:flex-col space-x-4 md:space-x-0 md:space-y-3">
            <a href="#"><img src="@/Assets/IMG/f.png" alt="Facebook" class="w-4 h-4"></a>
            <a href="#"><img src="@/Assets/IMG/t.png" alt="Twitter" class="w-4 h-4"></a>
            <a href="#"><img src="@/Assets/IMG/i.png" alt="Instagram" class="w-4 h-4"></a>
            <a href="#"><img src="@/Assets/IMG/X.png" alt="X" class="w-4 h-4"></a>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>