<template>
  <PublicLayout>
    <!-- Set Dynamic Title -->
    <Head>
      <title>Contact - BEZTAM</title>
    </Head>

    <!-- Contact Section -->
    <section class="my-10 sm:my-16 md:my-20">
      <div class="container mx-auto px-4 sm:px-6 max-w-6xl">
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center">Écrivez-<span class="text-[#5AE4A8]">nous</span></h1>

        <div class="mt-8 sm:mt-12 bg-[#F8F8F9] p-6 sm:p-8 rounded-lg shadow-md max-w-2xl mx-auto">
          <!-- Form -->
          <form @submit.prevent="submitForm">
            <!-- First Row: Nom Complet & Email -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
              <div>
                <label for="nom-complet" class="block text-sm font-medium text-gray-700 mb-2">Nom Complet:</label>
                <input type="text" id="nom-complet" v-model="form.fullName" class="block w-full border-2 border-[#5AE4A8] rounded-lg p-2 bg-white focus:outline-none focus:ring-2 focus:ring-[#5AE4A8] focus:border-transparent" placeholder="Votre nom complet" required>
              </div>
              <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email:</label>
                <input type="email" id="email" v-model="form.email" class="block w-full border-2 border-[#5AE4A8] rounded-lg p-2 bg-white focus:outline-none focus:ring-2 focus:ring-[#5AE4A8] focus:border-transparent" placeholder="Votre email" required>
              </div>
            </div>

            <!-- Second Row: Subject -->
            <div class="mt-4 sm:mt-6">
              <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Sujet:</label>
              <input type="text" id="subject" v-model="form.subject" class="block w-full border-2 border-[#5AE4A8] rounded-lg p-2 bg-white focus:outline-none focus:ring-2 focus:ring-[#5AE4A8] focus:border-transparent" placeholder="Le sujet de votre message" required>
            </div>

            <!-- Third Row: Message -->
            <div class="mt-4 sm:mt-6">
              <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message:</label>
              <textarea id="message" v-model="form.message" rows="6" class="block w-full border-2 border-[#5AE4A8] rounded-lg p-2 bg-white focus:outline-none focus:ring-2 focus:ring-[#5AE4A8] focus:border-transparent" placeholder="Votre message" required></textarea>
            </div>

            <!-- Submit Button -->
            <div class="mt-6 sm:mt-8 text-center">
              <button type="submit" class="inline-block rounded-xl py-2 px-6 sm:px-8 bg-green-300 border-green-300 border-2 hover:bg-green-400 hover:border-black text-black font-semibold text-base sm:text-lg transition-all w-full sm:w-1/2">Transférer</button>
            </div>
          </form>
        </div>
      </div>
    </section>

    <!-- Coordonnées Section -->
    <section class="my-10 sm:my-16 md:my-20">
      <div class="container mx-auto px-4 sm:px-6 max-w-6xl">
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center">Coordonnées</h1>

        <div class="mt-8 sm:mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
          <!-- Div 1: Instagram -->
          <div class="bg-[#F8F8F9] p-4 sm:p-6 rounded-lg shadow-md hover:shadow-[#a7f3d0] transition-all h-full mx-auto flex flex-col items-center justify-center w-full max-w-[250px] sm:max-w-none">
            <div class="w-16 h-16 sm:w-20 sm:h-20 border-4 border-[#5AE4A8] rounded-full bg-white flex items-center justify-center">
              <img src="@/Assets/IMG/i.png" alt="Instagram" class="w-8 h-8 sm:w-10 sm:h-10">
            </div>
            <h2 class="text-[#5AE4A8] text-lg sm:text-xl font-semibold mt-4 sm:mt-6">Instagram</h2>
            <p class="text-black text-sm sm:text-base mt-2">@beztam_app</p>
          </div>

          <!-- Div 2: Facebook -->
          <div class="bg-[#F8F8F9] p-4 sm:p-6 rounded-lg shadow-md hover:shadow-[#a7f3d0] transition-all h-full mx-auto flex flex-col items-center justify-center w-full max-w-[250px] sm:max-w-none">
            <div class="w-16 h-16 sm:w-20 sm:h-20 border-4 border-[#5AE4A8] rounded-full bg-white flex items-center justify-center">
              <img src="@/Assets/IMG/f.png" alt="Facebook" class="w-8 h-8 sm:w-10 sm:h-10">
            </div>
            <h2 class="text-[#5AE4A8] text-lg sm:text-xl font-semibold mt-4 sm:mt-6">Facebook</h2>
            <p class="text-black text-sm sm:text-base mt-2">@beztam_app</p>
          </div>

          <!-- Div 3: X -->
          <div class="bg-[#F8F8F9] p-4 sm:p-6 rounded-lg shadow-md hover:shadow-[#a7f3d0] transition-all h-full mx-auto flex flex-col items-center justify-center w-full max-w-[250px] sm:max-w-none">
            <div class="w-16 h-16 sm:w-20 sm:h-20 border-4 border-[#5AE4A8] rounded-full bg-white flex items-center justify-center">
              <img src="@/Assets/IMG/x.png" alt="X" class="w-8 h-8 sm:w-10 sm:h-10">
            </div>
            <h2 class="text-[#5AE4A8] text-lg sm:text-xl font-semibold mt-4 sm:mt-6">X</h2>
            <p class="text-black text-sm sm:text-base mt-2">@beztam_app</p>
          </div>

          <!-- Div 4: Phone -->
          <div class="bg-[#F8F8F9] p-4 sm:p-6 rounded-lg shadow-md hover:shadow-[#a7f3d0] transition-all h-full mx-auto flex flex-col items-center justify-center w-full max-w-[250px] sm:max-w-none">
            <div class="w-16 h-16 sm:w-20 sm:h-20 border-4 border-[#5AE4A8] rounded-full bg-white flex items-center justify-center">
              <img src="@/Assets/IMG/telephone.png" alt="Phone" class="w-8 h-8 sm:w-10 sm:h-10">
            </div>
            <h2 class="text-[#5AE4A8] text-lg sm:text-xl font-semibold mt-4 sm:mt-6">Phone</h2>
            <p class="text-black text-sm sm:text-base mt-2">+212 568-301956</p>
          </div>

          <!-- Div 5: Address -->
          <div class="bg-[#F8F8F9] p-4 sm:p-6 rounded-lg shadow-md hover:shadow-[#a7f3d0] transition-all h-full mx-auto flex flex-col items-center justify-center w-full max-w-[250px] sm:max-w-none">
            <div class="w-16 h-16 sm:w-20 sm:h-20 border-4 border-[#5AE4A8] rounded-full bg-white flex items-center justify-center">
              <img src="@/Assets/IMG/location.png" alt="Address" class="w-8 h-8 sm:w-10 sm:h-10">
            </div>
            <h2 class="text-[#5AE4A8] text-lg sm:text-xl font-semibold mt-4 sm:mt-6">Address</h2>
            <p class="text-black text-sm sm:text-base mt-2">FSA UIZ Agadir, Maroc</p>
          </div>

          <!-- Div 6: Gmail -->
          <div class="bg-[#F8F8F9] p-4 sm:p-6 rounded-lg shadow-md hover:shadow-[#a7f3d0] transition-all h-full mx-auto flex flex-col items-center justify-center w-full max-w-[250px] sm:max-w-none">
            <div class="w-16 h-16 sm:w-20 sm:h-20 border-4 border-[#5AE4A8] rounded-full bg-white flex items-center justify-center">
              <img src="@/Assets/IMG/mail.png" alt="Gmail" class="w-8 h-8 sm:w-10 sm:h-10">
            </div>
            <h2 class="text-[#5AE4A8] text-lg sm:text-xl font-semibold mt-4 sm:mt-6">Gmail</h2>
            <p class="text-black text-sm sm:text-base mt-2">baztam@support.ma</p>
          </div>
        </div>
      </div>
    </section>
  </PublicLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import { reactive } from 'vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';

// Form handling
const form = reactive({
  fullName: '',
  email: '',
  subject: '',
  message: '',
});

const submitForm = () => {
  // Handle form submission (e.g., send data to backend)
  console.log('Form submitted:', form);
};
</script>