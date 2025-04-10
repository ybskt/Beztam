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
    </div>
  </template>
  
  <script setup>
  import Nav from '@/Components/Public/Nav.vue';
  import Footer from '@/Components/Public/Footer.vue';
  </script>