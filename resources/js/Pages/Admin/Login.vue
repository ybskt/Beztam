<template>
  <div class="min-h-screen flex items-center justify-center py-12 bg-white">
    <div class="max-w-md w-full mx-4">
      <form @submit.prevent="submit" class="bg-[#F8F8F9] p-8 rounded-lg shadow-md">
        <!-- Logo and Brand -->
        <div class="flex justify-center items-center space-x-3 mb-8">
          <img src="@/Assets/IMG/wallet.png" alt="BEZTAM" class="w-10 h-10">
          <span class="text-2xl font-black">BEZTAM</span>
        </div>
        
        <!-- Title -->
        <h1 class="text-2xl font-bold text-center mb-10">Accès <span class="text-[#5AE4A8]">Administrateur</span></h1>
        
        <!-- Password Field -->
        <div class="mb-8">
          <label for="password" class="block text-sm font-medium text-gray-700 mb-3">Mot de passe</label>
          <input
            v-model="form.password"
            id="password"
            type="password"
            required
            class="block w-full border-2 border-[#5AE4A8] rounded-lg p-3 bg-white focus:outline-none focus:ring-2 focus:ring-[#5AE4A8] focus:border-transparent"
            placeholder="Entrez le mot de passe"
          >
          <div v-if="form.errors.password" class="mt-2 text-sm text-red-600">
            {{ form.errors.password }}
          </div>
        </div>

        <!-- Submit Button -->
        <button
          type="submit"
          class="w-full px-6 py-3 bg-[#5AE4A8] text-black font-semibold border-[#5AE4A8] border-2 hover:border-black rounded-md hover:bg-[#4ACD97] transition-all mb-6"
          :disabled="form.processing"
        >
          <span v-if="!form.processing">Se connecter</span>
          <span v-else>Connexion en cours...</span>
        </button>

        <!-- Back Link -->
        <div class="text-center pt-2">
          <Link :href="route('login')" class="text-black text-sm font-semibold hover:underline">
            ← Retour à la connexion utilisateur
          </Link>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

const form = useForm({
  password: '',
});

const submit = () => {
  form.post(route('admin.login.attempt'), {
    onFinish: () => form.reset('password'),
    preserveScroll: true,
  });
};
</script>