<template>
  <nav class="bg-white shadow-md py-4">
    <div class="container mx-auto px-6 max-w-6xl flex items-center justify-between">
      <div @click="$inertia.visit(route('home'))" class="flex items-center space-x-2 cursor-pointer">
        <img src="@/Assets/IMG/wallet.png" alt="wallet logo" class="w-10 h-10">
        <span class="text-xl font-black">BEZTAM</span>
      </div>

      <!-- Mobile Menu Button -->
      <div class="md:hidden">
        <img 
          src="@/Assets/IMG/menu.png" 
          alt="Menu" 
          class="w-8 h-8 cursor-pointer"
          @click="mobileMenuOpen = !mobileMenuOpen"
        >
      </div>

      <!-- Desktop Navigation -->
      <div class="hidden md:flex space-x-8 items-center">
        <Link :href="route('home')" class="text-black font-bold relative group" :class="{ 'text-[#5AE4A8]': $page.url === '/' }">
          Home
          <span class="absolute left-0 right-0 -bottom-4 h-0.5 bg-[#5AE4A8] transform scale-x-0 group-hover:scale-x-100 transition-transform"></span>
        </Link>

        <Link :href="route('features')" class="text-black font-bold relative group" :class="{ 'text-[#5AE4A8]': $page.url.startsWith('/features') }">
          Features
          <span class="absolute left-0 right-0 -bottom-4 h-0.5 bg-[#5AE4A8] transform scale-x-0 group-hover:scale-x-100 transition-transform"></span>
        </Link>

        <Link :href="route('about')" class="text-black font-bold relative group" :class="{ 'text-[#5AE4A8]': $page.url.startsWith('/about') }">
          About
          <span class="absolute left-0 right-0 -bottom-4 h-0.5 bg-[#5AE4A8] transform scale-x-0 group-hover:scale-x-100 transition-transform"></span>
        </Link>

        <Link :href="route('contact')" class="text-black font-bold relative group" :class="{ 'text-[#5AE4A8]': $page.url.startsWith('/contact') }">
          Contact
          <span class="absolute left-0 right-0 -bottom-4 h-0.5 bg-[#5AE4A8] transform scale-x-0 group-hover:scale-x-100 transition-transform"></span>
        </Link>
      </div>

      <div class="hidden md:flex space-x-4 items-center">
        <Link :href="route('login')" class="rounded-xl py-2 px-6 bg-green-300 hover:bg-green-400 border-2 border-green-300 hover:border-black text-black font-bold transition-all">Log in</Link>
      </div>
    </div>

    <!-- Mobile Menu (Hidden by Default) -->
    <div 
      v-if="mobileMenuOpen" 
      class="md:hidden bg-white py-4 px-6 shadow-lg absolute w-full z-50 left-0 top-16"
    >
      <div class="flex flex-col space-y-4">
        <Link 
          :href="route('home')" 
          class="text-black font-bold py-2 border-b border-gray-100"
          :class="{ 'text-[#5AE4A8]': $page.url === '/' }"
          @click="mobileMenuOpen = false"
        >
          Home
        </Link>
        <Link 
          :href="route('features')" 
          class="text-black font-bold py-2 border-b border-gray-100"
          :class="{ 'text-[#5AE4A8]': $page.url.startsWith('/features') }"
          @click="mobileMenuOpen = false"
        >
          Features
        </Link>
        <Link 
          :href="route('about')" 
          class="text-black font-bold py-2 border-b border-gray-100"
          :class="{ 'text-[#5AE4A8]': $page.url.startsWith('/about') }"
          @click="mobileMenuOpen = false"
        >
          About
        </Link>
        <Link 
          :href="route('contact')" 
          class="text-black font-bold py-2 border-b border-gray-100"
          :class="{ 'text-[#5AE4A8]': $page.url.startsWith('/contact') }"
          @click="mobileMenuOpen = false"
        >
          Contact
        </Link>
        <div class="pt-2">
          <Link 
            :href="route('login')" 
            class="block text-center rounded-xl py-2 px-6 bg-green-300 hover:bg-green-400 border-2 border-green-300 hover:border-black text-black font-bold transition-all"
            @click="mobileMenuOpen = false"
          >
            Log in
          </Link>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const mobileMenuOpen = ref(false);
</script>