<template>
  <DashLayout>
    <div class="container mx-auto max-w-3xl py-6">
      <!-- Personal Info Flash Message (Top of Page) -->
      <div v-if="personalInfoFlash" class="mb-4 p-4 rounded-lg"
:class="{
        'bg-green-100 text-green-700': personalInfoFlash.type === 'success',
        'bg-red-100 text-red-700': personalInfoFlash.type === 'error'
      }">
        {{ personalInfoFlash.text }}
      </div>

      <!-- Personal Info Section -->
      <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <h1 class="text-2xl font-bold text-[#1E293B] mb-6 text-center">Paramètres du compte</h1>
       
        <!-- Photo Section -->
        <div class="flex flex-col items-center mb-8">
          <div class="w-24 h-24 rounded-full bg-[#D1FAE5] flex items-center justify-center mb-4 overflow-hidden">
            <span v-if="!user.image" class="text-3xl font-semibold text-[#10B981]">
              {{ userInitials }}
            </span>
            <img v-else :src="userImageUrl" class="w-full h-full object-cover">
          </div>
         
          <div class="flex items-center gap-3">
            <div class="relative">
              <input
                type="file"
                ref="photoInput"
                class="hidden"
                @change="handleImageUpload"
                accept="image/jpeg,image/png"
                :disabled="uploadInProgress"
              >
              <button
                type="button"
                @click="selectNewPhoto"
                class="px-4 py-2 bg-[#86EFAC] hover:bg-[#4ADE80] text-black font-semibold rounded-lg transition-all disabled:opacity-50"
                :class="{ 'opacity-50': uploadInProgress }"
              >
                <span v-if="uploadInProgress">
                  <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-black inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Téléchargement...
                </span>
                <span v-else>{{ user.image ? 'Changer la photo' : 'Télécharger une photo' }}</span>
              </button>
            </div>
           
            <button
              v-if="user.image"
              type="button"
              @click="deletePhoto"
              class="px-4 py-2 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg transition-all"
              :disabled="deleteInProgress"
            >
              <span v-if="deleteInProgress">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-red-600 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Suppression...
              </span>
              <span v-else>Supprimer la photo</span>
            </button>
          </div>
         
          <p v-if="imageError" class="mt-1 text-sm text-red-600">{{ imageError }}</p>
          <p class="text-xs text-gray-500 mt-2">Formats acceptés : JPG, PNG (max 1MB)</p>
        </div>

        <form @submit.prevent="submitProfile">
          <div>
            <h2 class="text-lg font-semibold text-[#1E293B] mb-4">Informations personnelles</h2>
            <div class="space-y-4">
              <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
                <input
                  v-model="profileForm.first_name"
                  type="text"
                  id="first_name"
                  class="w-full border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent"
                  :disabled="profileForm.processing"
                >
                <p v-if="profileForm.errors.first_name" class="mt-1 text-sm text-red-600">{{ profileForm.errors.first_name }}</p>
              </div>
             
              <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                <input
                  v-model="profileForm.last_name"
                  type="text"
                  id="last_name"
                  class="w-full border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent"
                  :disabled="profileForm.processing"
                >
                <p v-if="profileForm.errors.last_name" class="mt-1 text-sm text-red-600">{{ profileForm.errors.last_name }}</p>
              </div>
             
              <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input
                  v-model="profileForm.email"
                  type="email"
                  id="email"
                  class="w-full border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent"
                  :disabled="profileForm.processing"
                >
                <p v-if="profileForm.errors.email" class="mt-1 text-sm text-red-600">{{ profileForm.errors.email }}</p>
              </div>
            </div>
           
            <div class="mt-6 flex justify-end">
              <button
                type="submit"
                :disabled="profileForm.processing"
                class="px-6 py-2 bg-[#86EFAC] hover:bg-[#4ADE80] border-[#86EFAC] border-2 hover:border-black text-black font-semibold rounded-lg transition-all disabled:opacity-50"
              >
                <span v-if="profileForm.processing">
                  <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-black inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Enregistrement...
                </span>
                <span v-else>Enregistrer les modifications</span>
              </button>
            </div>
          </div>
        </form>
      </div>

      <!-- Password Flash Message (Above Password Section) -->
      <div v-if="passwordFlash" class="mb-4 p-4 rounded-lg"
:class="{
        'bg-green-100 text-green-700': passwordFlash.type === 'success',
        'bg-red-100 text-red-700': passwordFlash.type === 'error'
      }">
        {{ passwordFlash.text }}
      </div>

      <!-- Password Section -->
      <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <h2 class="text-lg font-semibold text-[#1E293B] mb-4">Mot de passe</h2>
        <form @submit.prevent="submitPassword">
          <div class="space-y-4">
            <div>
              <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe actuel</label>
              <input
                v-model="passwordForm.current_password"
                type="password"
                id="current_password"
                class="w-full border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent"
                :disabled="passwordForm.processing"
              >
              <p v-if="passwordForm.errors.current_password" class="mt-1 text-sm text-red-600">{{ passwordForm.errors.current_password }}</p>
            </div>
           
            <div>
              <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Nouveau mot de passe</label>
              <input
                v-model="passwordForm.password"
                type="password"
                id="password"
                class="w-full border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent"
                :disabled="passwordForm.processing"
              >
              <p v-if="passwordForm.errors.password" class="mt-1 text-sm text-red-600">{{ passwordForm.errors.password }}</p>
            </div>
           
            <div>
              <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmer le nouveau mot de passe</label>
              <input
                v-model="passwordForm.password_confirmation"
                type="password"
                id="password_confirmation"
                class="w-full border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent"
                :disabled="passwordForm.processing"
              >
            </div>
           
            <div class="flex justify-end">
              <button
                type="submit"
                :disabled="passwordForm.processing"
                class="px-6 py-2 bg-[#86EFAC] hover:bg-[#4ADE80] border-[#86EFAC] border-2 hover:border-black text-black font-semibold rounded-lg transition-all disabled:opacity-50"
              >
                <span v-if="passwordForm.processing">
                  <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Mise à jour...
                </span>
                <span v-else>Mettre à jour le mot de passe</span>
              </button>
            </div>
          </div>
        </form>
      </div>

      <!-- Rating Section -->
      <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Évaluation de l'application</h2>
        <div class="flex flex-col md:flex-row gap-6">
          <div class="flex-1">
            <h3 class="text-md font-medium text-gray-700 mb-3">Votre note</h3>
            <div class="flex justify-center space-x-1">
              <template v-for="i in 5" :key="i">
                <button
                  @click="updateRating(i)"
                  class="text-3xl transition-colors hover:text-yellow-500 focus:outline-none"
                  :class="{
                    'text-yellow-400': i <= userRating,
                    'text-gray-300': i > userRating
                  }"
                  :disabled="ratingInProgress"
                >
                  ★
                </button>
              </template>
            </div>
            <p class="text-center text-sm text-gray-500 mt-1">
              {{ userRating ? `Vous avez noté ${userRating}/5` : 'Cliquez pour évaluer' }}
            </p>
          </div>

          <div class="flex-1">
            <h3 class="text-md font-medium text-gray-700 mb-3">Note moyenne</h3>
            <div class="bg-gray-50 rounded-lg p-4">
              <div class="flex items-center justify-center">
                <div class="flex mr-2">
                  <template v-for="i in 5" :key="i">
                    <span
                      v-if="i <= Math.floor(currentStats.average_rating)"
                      class="text-yellow-400 text-xl"
                    >★</span>
                    <span
                      v-else-if="i - 0.5 <= currentStats.average_rating"
                      class="text-yellow-400 text-xl"
                    >☆</span>
                    <span
                      v-else
                      class="text-gray-300 text-xl"
                    >★</span>
                  </template>
                </div>
                <span class="text-xl font-semibold">{{ currentStats.average_rating.toFixed(1) }}</span>
                <span class="ml-1 text-gray-500 text-sm">({{ currentStats.total_ratings }})</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Account Deletion Section -->
      <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-lg font-semibold text-[#1E293B] mb-4">Supprimer le compte</h2>
        <p class="text-sm text-gray-600 mb-4">Une fois votre compte supprimé, toutes ses ressources et données seront définitivement effacées.</p>
        
        <ConfirmationModal
          :show="confirmUserDeletion"
          @close="confirmUserDeletion = false"
          title="Supprimer le compte"
        >
          <template #content>
            <div class="space-y-4">
              <p>Êtes-vous sûr de vouloir supprimer votre compte ?</p>
              <p class="text-red-600 font-medium">Cette action est irréversible. Toutes vos données seront définitivement supprimées.</p>
              <div>
                <label for="delete_password" class="block text-sm font-medium text-gray-700 mb-1">Confirmez votre mot de passe</label>
                <input
                  v-model="deleteAccountForm.password"
                  type="password"
                  id="delete_password"
                  class="w-full border-2 rounded-lg p-2 border-red-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                  :disabled="deleteAccountForm.processing"
                >
                <p v-if="deleteAccountForm.errors.password" class="mt-1 text-sm text-red-600">{{ deleteAccountForm.errors.password }}</p>
              </div>
            </div>
          </template>

          <template #footer>
            <button
              @click="confirmUserDeletion = false"
              class="px-4 py-2 bg-gray-200 hover:bg-gray-300  border-gray-200 border-2  hover:border-black ml-2 rounded-lg mr-2"
            >
              Annuler
            </button>
            <button
              @click="deleteUser"
              class="px-4 py-2 bg-red-500 hover:bg-red-600 border-red-500 border-2  hover:border-black text-black rounded-lg"
              :class="{ 'opacity-50': deleteAccountForm.processing }"
              :disabled="deleteAccountForm.processing"
            >
              <span v-if="deleteAccountForm.processing">
                <svg class="animate-spin -ml-1 mr-1 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Suppression...
              </span>
              <span v-else>Supprimer mon compte</span>
            </button>
          </template>
        </ConfirmationModal>

        <div class="flex justify-end">
          <button
            @click="confirmUserDeletion = true"
            class="px-6 py-2 bg-red-500 hover:bg-red-600 border-red-500 border-2  hover:border-black text-black font-semibold rounded-lg transition-colors"
          >
            Supprimer le compte
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
import axios from 'axios';
import ConfirmationModal from '@/Components/Dashboard/ConfirmationModal.vue';

const props = defineProps({
  user: {
    type: Object,
    required: true,
    default: () => ({
      id: null,
      first_name: '',
      last_name: '',
      email: '',
      image: null,
      email_verified_at: null,
      savings_rate: 0,
      created_at: null,
      updated_at: null,
      full_name: '',
      rating: null
    })
  },
  stats: {
    type: Object,
    default: () => ({
      average_rating: 0,
      total_ratings: 0
    })
  }
});

// Refs
const photoInput = ref(null);
const imageError = ref(null);
const uploadInProgress = ref(false);
const deleteInProgress = ref(false);
const confirmUserDeletion = ref(false);
const ratingInProgress = ref(false);

// Flash messages
const personalInfoFlash = ref(null);
const passwordFlash = ref(null);

// Forms
const profileForm = useForm({
  first_name: props.user.first_name,
  last_name: props.user.last_name,
  email: props.user.email
});

const passwordForm = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
});

const deleteAccountForm = useForm({
  password: '',
});

// Computed properties
const userInitials = computed(() => {
  return (props.user?.first_name?.[0] || '') + (props.user?.last_name?.[0] || '');
});

const userImageUrl = computed(() => {
  return props.user?.image
    ? props.user.image.startsWith('http')
      ? props.user.image
      : `/storage/${props.user.image}`
    : null;
});

// Reactive rating data
const userRating = ref(props.user.rating ? Number(props.user.rating) : 0);
const currentStats = ref({
  average_rating: props.stats.average_rating ? Number(props.stats.average_rating) : 0,
  total_ratings: props.stats.total_ratings || 0
});

// Methods
const selectNewPhoto = () => {
  photoInput.value?.click();
};

const handleImageUpload = async (event) => {
  const file = event.target.files?.[0];
  imageError.value = null;

  if (!file) return;

  // Client-side validation
  const validTypes = ['image/jpeg', 'image/png'];
  const maxSize = 1024 * 1024; // 1MB

  if (!validTypes.includes(file.type)) {
    imageError.value = 'Seuls les formats JPG et PNG sont acceptés.';
    return;
  }

  if (file.size > maxSize) {
    imageError.value = 'La taille de l\'image ne doit pas dépasser 1MB.';
    return;
  }

  uploadInProgress.value = true;
 
  try {
    const formData = new FormData();
    formData.append('photo', file);
   
    await router.post(route('settings.photo.upload'), formData, {
      preserveScroll: true,
      onSuccess: () => {
        personalInfoFlash.value = {
          type: 'success',
          text: 'Photo de profil mise à jour avec succès'
        };
        imageError.value = null;
      },
      onError: (errors) => {
        personalInfoFlash.value = {
          type: 'error',
          text: errors.photo || 'Erreur lors du téléchargement de la photo'
        };
        imageError.value = errors.photo || 'Erreur lors du téléchargement de la photo';
      }
    });
  } catch (error) {
    personalInfoFlash.value = {
      type: 'error',
      text: 'Erreur lors du téléchargement de la photo'
    };
    imageError.value = 'Erreur lors du téléchargement de la photo';
  } finally {
    uploadInProgress.value = false;
    event.target.value = '';
  }
};

const deletePhoto = async () => {
  if (!props.user?.image) return;

  deleteInProgress.value = true;
 
  try {
    await router.delete(route('settings.photo.destroy'), {
      preserveScroll: true,
      onSuccess: () => {
        personalInfoFlash.value = {
          type: 'success',
          text: 'Photo de profil supprimée avec succès'
        };
        imageError.value = null;
      },
      onError: () => {
        personalInfoFlash.value = {
          type: 'error',
          text: 'Erreur lors de la suppression de la photo'
        };
        imageError.value = 'Erreur lors de la suppression de la photo';
      }
    });
  } catch (error) {
    personalInfoFlash.value = {
      type: 'error',
      text: 'Erreur lors de la suppression de la photo'
    };
    imageError.value = 'Erreur lors de la suppression de la photo';
  } finally {
    deleteInProgress.value = false;
  }
};

const submitProfile = () => {
  personalInfoFlash.value = null;
  profileForm.put(route('settings.profile.update'), {
    preserveScroll: true,
    onSuccess: () => {
      personalInfoFlash.value = {
        type: 'success',
        text: 'Vos informations personnelles ont été mises à jour'
      };
    },
    onError: () => {
      personalInfoFlash.value = {
        type: 'error',
        text: 'Erreur lors de la mise à jour des informations'
      };
    }
  });
};

const submitPassword = () => {
  passwordFlash.value = null;
  passwordForm.put(route('settings.password.update'), {
    preserveScroll: true,
    onSuccess: () => {
      passwordForm.reset();
      passwordFlash.value = {
        type: 'success',
        text: 'Votre mot de passe a été changé avec succès'
      };
    },
    onError: () => {
      passwordFlash.value = {
        type: 'error',
        text: 'Erreur lors du changement de mot de passe'
      };
    }
  });
};

const updateRating = async (rating) => {
  if (ratingInProgress.value) return;
  
  ratingInProgress.value = true;
  
  try {
    // Update UI immediately for better UX
    const previousRating = userRating.value;
    userRating.value = rating;
   
    const response = await axios.put(route('settings.rating.update'), { rating });
   
    // Update stats with server response
    if (response.data) {
      currentStats.value = {
        average_rating: Number(response.data.average_rating) || 0,
        total_ratings: Number(response.data.total_ratings) || 0
      };
    }
   
  } catch (error) {
    console.error('Erreur lors de la mise à jour de la note :', error);
    // Revert UI change on error
    userRating.value = previousRating;
    
    // Show error message
    personalInfoFlash.value = {
      type: 'error',
      text: 'Erreur lors de la mise à jour de votre évaluation'
    };
  } finally {
    ratingInProgress.value = false;
  }
};

const deleteUser = () => {
  deleteAccountForm.delete(route('settings.account.destroy'), {
    preserveScroll: false,
    onSuccess: () => {},
    onError: (errors) => {}
  });
};
</script>