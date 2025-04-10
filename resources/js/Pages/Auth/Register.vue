<template>
  <div>
    <Head title="Register" />
    
    <Nav />
    
    <section class="my-8 md:my-12 lg:my-20">
  <div class="container mx-auto px-4 md:px-6 max-w-6xl">
    <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center mb-6 md:mb-8 lg:mb-12">
      Create Account <span class="text-[#5AE4A8]">BEZTAM</span>
    </h1>

    <form @submit.prevent="submit" class="mt-6 md:mt-8 lg:mt-12 max-w-sm sm:max-w-md mx-auto bg-[#F8F8F9] p-5 md:p-6 lg:p-8 rounded-lg shadow-md">
      <div class="mb-4 md:mb-6">
        <label for="firstName" class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">
          First Name:
        </label>
        <input
          id="firstName"
          v-model="form.firstName"
          type="text"
          class="block w-full border-2 border-[#5AE4A8] rounded-lg p-2 bg-white focus:outline-none focus:ring-2 focus:ring-[#5AE4A8] focus:border-transparent"
          placeholder="Votre nom"
          required
          autofocus
        >
        <div v-if="form.errors.firstName" class="text-red-500 text-sm mt-1">
          {{ form.errors.firstName }}
        </div>
      </div>

      <div class="mb-4 md:mb-6">
        <label for="lastName" class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">
          Last Name:
        </label>
        <input
          id="lastName"
          v-model="form.lastName"
          type="text"
          class="block w-full border-2 border-[#5AE4A8] rounded-lg p-2 bg-white focus:outline-none focus:ring-2 focus:ring-[#5AE4A8] focus:border-transparent"
          placeholder="Votre prenom"
          required
        >
        <div v-if="form.errors.lastName" class="text-red-500 text-sm mt-1">
          {{ form.errors.lastName }}
        </div>
      </div>

      <div class="mb-4 md:mb-6">
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">
          Email:
        </label>
        <input
          id="email"
          v-model="form.email"
          type="email"
          class="block w-full border-2 border-[#5AE4A8] rounded-lg p-2 bg-white focus:outline-none focus:ring-2 focus:ring-[#5AE4A8] focus:border-transparent"
          placeholder="Votre email"
          required
        >
        <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">
          {{ form.errors.email }}
        </div>
      </div>

      <div class="mb-4 md:mb-6">
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">
          Password:
        </label>
        <input
          id="password"
          v-model="form.password"
          type="password"
          class="block w-full border-2 border-[#5AE4A8] rounded-lg p-2 bg-white focus:outline-none focus:ring-2 focus:ring-[#5AE4A8] focus:border-transparent"
          placeholder="Votre mot de passe"
          required
          autocomplete="new-password"
        >
        <div v-if="form.errors.password" class="text-red-500 text-sm mt-1">
          {{ form.errors.password }}
        </div>
      </div>

      <div class="mb-4 md:mb-6">
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">
          Confirm Password:
        </label>
        <input
          id="password_confirmation"
          v-model="form.password_confirmation"
          type="password"
          class="block w-full border-2 border-[#5AE4A8] rounded-lg p-2 bg-white focus:outline-none focus:ring-2 focus:ring-[#5AE4A8] focus:border-transparent"
          placeholder="Confirmation de mot de passe"
          required
        >
      </div>

      <div class="text-center">
        <button
          type="submit"
          class="w-full px-4 sm:px-6 py-2 bg-[#5AE4A8] text-black font-semibold border-[#5AE4A8] border-2 hover:border-black rounded-md hover:bg-[#4ACD97] transition-all"
          :disabled="form.processing"
        >
          Register
        </button>
      </div>

      <div class="mt-4 md:mt-6 text-center">
        <Link
          href="/login"
          class="text-[#5AE4A8] text-sm font-semibold hover:underline"
        >
          Already have an account? Login
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
  firstName: '',
  lastName: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const submit = () => {
  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
}
</script>