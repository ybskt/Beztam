<template>
  <div class="font-poppins bg-[#F8F8F9] min-h-screen">
    <DashNav :user="$page.props.auth.user" />
    <main class="pt-16 md:pt-20 md:ml-64 px-4 md:px-6 lg:px-8">
      <div class="container mx-auto max-w-7xl py-6">
        <slot />
      </div>
    </main>
  </div>
</template>

<script setup>
import DashNav from '@/Components/Dashboard/DashNav.vue'
</script>