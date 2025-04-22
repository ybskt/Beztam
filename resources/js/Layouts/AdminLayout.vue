<template>
  <div class="font-poppins bg-[#F8F8F9] min-h-screen">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
      <AdminNav />
      <main class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
import AdminNav from '@/Components/Admin/AdminNav.vue';
</script>