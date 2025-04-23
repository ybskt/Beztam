<template>
  <PublicLayout>
    <Head>
      <title>Contact - BEZTAM</title>
    </Head>

    <section class="my-10 sm:my-16 md:my-20">
      <div class="container mx-auto px-4 sm:px-6 max-w-6xl">
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center">Écrivez-<span class="text-[#5AE4A8]">nous</span></h1>
        
        <!-- Success Message -->
        <div v-if="form.wasSuccessful" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
          Votre message a été envoyé avec succès!
        </div>

        <div class="mt-8 sm:mt-12 bg-[#F8F8F9] p-6 sm:p-8 rounded-lg shadow-md max-w-2xl mx-auto">
          <form @submit.prevent="submitForm">
            <!-- First Row: Nom Complet & Email -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
              <div>
                <label for="nom-complet" class="block text-sm font-medium text-gray-700 mb-2">Nom Complet:</label>
                <input type="text" id="nom-complet" v-model="form.fullName" 
                  class="block w-full border-2 border-[#5AE4A8] rounded-lg p-2 bg-white focus:outline-none focus:ring-2 focus:ring-[#5AE4A8] focus:border-transparent" 
                  placeholder="Votre nom complet">
                <p v-if="form.errors.fullName" class="text-red-500 text-xs mt-1">{{ form.errors.fullName }}</p>
              </div>
              <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email:</label>
                <input type="email" id="email" v-model="form.email" 
                  class="block w-full border-2 border-[#5AE4A8] rounded-lg p-2 bg-white focus:outline-none focus:ring-2 focus:ring-[#5AE4A8] focus:border-transparent" 
                  placeholder="Votre email">
                <p v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</p>
              </div>
            </div>

            <!-- Second Row: Subject -->
            <div class="mt-4 sm:mt-6">
              <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Sujet:</label>
              <input type="text" id="subject" v-model="form.subject" 
                class="block w-full border-2 border-[#5AE4A8] rounded-lg p-2 bg-white focus:outline-none focus:ring-2 focus:ring-[#5AE4A8] focus:border-transparent" 
                placeholder="Le sujet de votre message">
              <p v-if="form.errors.subject" class="text-red-500 text-xs mt-1">{{ form.errors.subject }}</p>
            </div>

            <!-- Third Row: Message -->
            <div class="mt-4 sm:mt-6">
              <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message:</label>
              <textarea id="message" v-model="form.message" rows="6" 
                class="block w-full border-2 border-[#5AE4A8] rounded-lg p-2 bg-white focus:outline-none focus:ring-2 focus:ring-[#5AE4A8] focus:border-transparent" 
                placeholder="Votre message"></textarea>
              <p v-if="form.errors.message" class="text-red-500 text-xs mt-1">{{ form.errors.message }}</p>
            </div>

            <!-- Submit Button -->
            <div class="mt-6 sm:mt-8 text-center">
              <button type="submit" class="inline-block rounded-xl py-2 px-6 sm:px-8 bg-green-300 border-green-300 border-2 hover:bg-green-400 hover:border-black text-black font-semibold text-base sm:text-lg transition-all w-full sm:w-1/2" :disabled="form.processing">
                <span v-if="form.processing">Envoi en cours...</span>
                <span v-else>Envoyer</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </section>

    <!-- Rest of your contact page content -->
  </PublicLayout>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const form = useForm({
  fullName: '',
  email: '',
  subject: '',
  message: '',
  wasSuccessful: false
});

const submitForm = () => {
  form.post(route('guest-contact.store'), {
    preserveScroll: true,
    onSuccess: () => {
      form.wasSuccessful = true;
      form.reset();
      setTimeout(() => form.wasSuccessful = false, 5000);
    }
  });
};
</script>