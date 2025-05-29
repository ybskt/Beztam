<template>
    <div>
      <!-- Navbar -->
      <Nav />
  
      <!-- Page Content -->
      <main>
        <slot />
      </main>
  
      <!-- Footer -->
      <Footer />
      <OllamaChat />

    </div>
  </template>
  
  <script setup>
  import Nav from '@/Components/Public/Nav.vue';
  import Footer from '@/Components/Public/Footer.vue';
  import OllamaChat  from '@/Components/OllamaChat.vue';

  </script>