<template>
    <DashLayout>
      <div class="container mx-auto max-w-3xl py-6">
        <!-- Profile Section -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
          <h1 class="text-2xl font-bold text-[#1E293B] mb-6 text-center">Paramètres du Compte</h1>
          
          <!-- Profile Photo -->
          <div class="flex flex-col items-center mb-8">
            <div class="w-24 h-24 rounded-full bg-[#D1FAE5] flex items-center justify-center mb-4">
              <span v-if="!form.photo" class="text-3xl font-semibold text-[#10B981]">
                {{ userInitials }}
              </span>
              <img v-else :src="form.photoPreview" class="w-full h-full rounded-full object-cover">
            </div>
            <div class="relative">
              <input 
                type="file" 
                ref="photoInput"
                class="hidden" 
                @change="updatePhotoPreview"
                accept="image/*"
              >
              <button 
                type="button" 
                @click="selectNewPhoto"
                class="px-6 py-2 bg-[#86EFAC] hover:bg-[#4ADE80] border-[#86EFAC] text-black font-semibold rounded-lg border-2 hover:border-black transition-all"
              >
                Charger Photo
              </button>
              <button
                v-if="form.photo"
                type="button"
                @click="deletePhoto"
                class="absolute -right-20 top-0 px-4 py-2 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg transition-all"
              >
                Supprimer
              </button>
            </div>
            <p class="text-xs text-gray-500 mt-2">Formats acceptés: JPG, PNG (max 10MB)</p>
          </div>
          
          <!-- Personal Info Form -->
          <form @submit.prevent="submitProfile">
            <div>
              <h2 class="text-lg font-semibold text-[#1E293B] mb-4">Informations Personnelles</h2>
              <div class="space-y-4">
                <div>
                  <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                  <input 
                    v-model="form.name" 
                    type="text" 
                    id="name" 
                    class="w-full border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent"
                  >
                  <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                </div>
                <div>
                  <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                  <input 
                    v-model="form.email" 
                    type="email" 
                    id="email" 
                    class="w-full border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent"
                  >
                  <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
                </div>
              </div>
              <div class="mt-6 flex justify-end">
                <button 
                  type="submit" 
                  :disabled="form.processing"
                  class="px-6 py-2 bg-[#86EFAC] hover:bg-[#4ADE80] border-[#86EFAC] text-black font-semibold rounded-lg border-2 hover:border-black transition-all disabled:opacity-50"
                >
                  <span v-if="form.processing">Enregistrement...</span>
                  <span v-else>Enregistrer</span>
                </button>
              </div>
            </div>
          </form>
        </div>
        
        <!-- Password Section -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
          <h2 class="text-lg font-semibold text-[#1E293B] mb-4">Mot de Passe</h2>
          <form @submit.prevent="submitPassword">
            <div class="space-y-4">
              <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Mot de Passe Actuel</label>
                <input 
                  v-model="passwordForm.current_password" 
                  type="password" 
                  id="current_password" 
                  class="w-full border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent"
                >
                <p v-if="passwordForm.errors.current_password" class="mt-1 text-sm text-red-600">{{ passwordForm.errors.current_password }}</p>
              </div>
              <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Nouveau Mot de Passe</label>
                <input 
                  v-model="passwordForm.password" 
                  type="password" 
                  id="password" 
                  class="w-full border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent"
                >
                <p v-if="passwordForm.errors.password" class="mt-1 text-sm text-red-600">{{ passwordForm.errors.password }}</p>
              </div>
              <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmer Nouveau Mot de Passe</label>
                <input 
                  v-model="passwordForm.password_confirmation" 
                  type="password" 
                  id="password_confirmation" 
                  class="w-full border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent"
                >
              </div>
              <div class="flex justify-end">
                <button 
                  type="submit" 
                  :disabled="passwordForm.processing"
                  class="px-6 py-2 bg-[#86EFAC] hover:bg-[#4ADE80] border-[#86EFAC] text-black font-semibold rounded-lg border-2 hover:border-black transition-all disabled:opacity-50"
                >
                  <span v-if="passwordForm.processing">Enregistrement...</span>
                  <span v-else>Enregistrer Mot de Passe</span>
                </button>
              </div>
            </div>
          </form>
        </div>
        
        <!-- Delete Account Section -->
        <div class="bg-white rounded-xl shadow-sm p-6">
          <h2 class="text-lg font-semibold text-[#1E293B] mb-4">Supprimer le Compte</h2>
          <p class="text-sm text-gray-600 mb-4">Cette action est irréversible. Toutes vos données seront définitivement supprimées.</p>
          
          <ConfirmationModal :show="confirmingUserDeletion" @close="confirmingUserDeletion = false">
            <template #title>
              Supprimer le compte
            </template>
  
            <template #content>
              Êtes-vous sûr de vouloir supprimer votre compte? Cette action est irréversible.
            </template>
  
            <template #footer>
              <button 
                @click="confirmingUserDeletion = false" 
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg mr-2"
              >
                Annuler
              </button>
              <button 
                @click="deleteUser" 
                class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg"
                :class="{ 'opacity-50': deleteForm.processing }"
                :disabled="deleteForm.processing"
              >
                Supprimer le Compte
              </button>
            </template>
          </ConfirmationModal>
  
          <div class="flex justify-end">
            <button 
              @click="confirmingUserDeletion = true"
              class="px-6 py-2 bg-red-500 border-2 border-red-500 hover:bg-red-600 hover:border-black text-black font-semibold rounded-lg transition-colors"
            >
              Supprimer Mon Compte
            </button>
          </div>
        </div>
      </div>
    </DashLayout>
  </template>
  
  <script setup>
  import { computed, ref } from 'vue';
  import { useForm, router } from '@inertiajs/vue3';
  import DashLayout from '@/Layouts/DashLayout.vue';
//   import ConfirmationModal from '@/Components/ConfirmationModal.vue';
  
  const props = defineProps({
    user: Object
  });
  
  const photoInput = ref(null);
  const confirmingUserDeletion = ref(false);
  
  // Profile form
  const form = useForm({
    name: props.user.name,
    email: props.user.email,
    photo: null,
    photoPreview: props.user.profile_photo_url,
  });
  
  // Password form
  const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
  });
  
  // Delete form
  const deleteForm = useForm({});
  
  // Compute user initials for avatar
  const userInitials = computed(() => {
    return props.user.name
      .split(' ')
      .map(name => name[0])
      .join('')
      .toUpperCase();
  });
  
  // Photo handling
  const selectNewPhoto = () => {
    photoInput.value.click();
  };
  
  const updatePhotoPreview = () => {
    const photo = photoInput.value.files[0];
    
    if (!photo) return;
  
    const reader = new FileReader();
  
    reader.onload = (e) => {
      form.photoPreview = e.target.result;
    };
  
    reader.readAsDataURL(photo);
    form.photo = photo;
  };
  
  const deletePhoto = () => {
    form.photo = null;
    form.photoPreview = null;
    photoInput.value.value = '';
  };
  
  // Form submissions
  const submitProfile = () => {
    if (form.photo) {
      form.post(route('user-profile-photo.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset('photo'),
      });
    } else {
      form.patch(route('profile.update'), {
        preserveScroll: true,
      });
    }
  };
  
  const submitPassword = () => {
    passwordForm.put(route('password.update'), {
      preserveScroll: true,
      onSuccess: () => passwordForm.reset(),
    });
  };
  
  const deleteUser = () => {
    deleteForm.delete(route('profile.destroy'), {
      preserveScroll: true,
      onSuccess: () => {
        confirmingUserDeletion.value = false;
        router.visit('/');
      },
    });
  };
  </script>