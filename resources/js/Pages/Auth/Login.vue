<template>
  <div>
    <Head>
      <title>Se connecter - BEZTAM</title>
    </Head>

    <Nav />

    <section class="my-8 md:my-12 lg:my-20">
  <div class="container mx-auto px-4 md:px-6 max-w-6xl">
    <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center mb-6 md:mb-8 lg:mb-12">
      Se connecter à <span class="text-[#5AE4A8]">BEZTAM</span>
    </h1>

    <form @submit.prevent="submit" class="mt-6 md:mt-8 lg:mt-12 max-w-sm sm:max-w-md mx-auto bg-[#F8F8F9] p-5 md:p-6 lg:p-8 rounded-lg shadow-md">
      <!-- Error Message -->
      <div v-if="form.errors.email" class="mb-4 text-red-500 text-sm">
        {{ form.errors.email }}
      </div>

      <!-- Email Input -->
      <div class="mb-4 md:mb-6">
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">
          Email :
        </label>
        <input
          id="email"
          v-model="form.email"
          type="email"
          class="block w-full border-2 border-[#5AE4A8] rounded-lg p-2 bg-white focus:outline-none focus:ring-2 focus:ring-[#5AE4A8] focus:border-transparent"
          placeholder="Votre email"
          required
          autofocus
        >
      </div>

      <!-- Password Input -->
      <div class="mb-4 md:mb-6">
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">
          Mot de passe :
        </label>
        <input
          id="password"
          v-model="form.password"
          type="password"
          class="block w-full border-2 border-[#5AE4A8] rounded-lg p-2 bg-white focus:outline-none focus:ring-2 focus:ring-[#5AE4A8] focus:border-transparent"
          placeholder="Votre mot de passe"
          required
          autocomplete="current-password"
        >
      </div>

      <!-- Remember Me & Forgot Password -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 md:mb-6 gap-3 sm:gap-0">
        <label class="flex items-center">
          <input
            type="checkbox"
            v-model="form.remember"
            class="rounded border-gray-300 text-[#5AE4A8] focus:ring-[#5AE4A8]"
          >
          <span class="ml-2 text-sm text-gray-600">Se souvenir de moi</span>
        </label>
        <Link
          href="/forgot-password"
          class="text-sm text-[#5AE4A8] hover:underline"
        >
          Mot de passe oublié ?
        </Link>
      </div>

      <!-- Submit Button -->
      <div class="text-center">
        <button
          type="submit"
          class="w-full px-4 sm:px-6 py-2 bg-[#5AE4A8] text-black font-semibold border-[#5AE4A8] border-2 hover:border-black rounded-md hover:bg-[#4ACD97] transition-all"
          :disabled="form.processing"
        >
          Se connecter
        </button>
      </div>

      <!-- Register Link -->
      <div class="mt-4 md:mt-6 text-center">
        <Link
          href="/register"
          class="text-[#5AE4A8] text-sm font-semibold hover:underline"
        >
          Créer un compte
        </Link>
        <div class="w-3/4 h-0.5 bg-[#5AE4A8] mx-auto mt-2"></div>
      </div>
    </form>
  </div>
</section>

    <Footer class="hidden md:block" />

  </div>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import Nav from '@/Components/Public/Nav.vue'
import Footer from '@/Components/Public/Footer.vue'

const form = useForm({
  email: '',
  password: '',
  remember: false,
})

const submit = () => {
  form.post(route('login'), {
    preserveScroll: true,
    onSuccess: () => {
      // Handle successful login if needed
    },
    onError: (errors) => {
      if (errors.email) {
        form.reset('password')
      }
    }
  })
}
</script>