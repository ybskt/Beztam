<template>
  <DashLayout>
    <div class="container mx-auto max-w-3xl py-6">
      <!-- Flash Messages -->
      <div v-if="$page.props.flash?.success" class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
        {{ $page.props.flash.success }}
      </div>
      <div v-if="$page.props.flash?.error" class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
        {{ $page.props.flash.error }}
      </div>

      <!-- Profile Section -->
      <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <h1 class="text-2xl font-bold text-[#1E293B] mb-6 text-center">Account Settings</h1>
        
        <!-- Profile Photo Section -->
        <div class="flex flex-col items-center mb-8">
          <div class="w-24 h-24 rounded-full bg-[#D1FAE5] flex items-center justify-center mb-4 overflow-hidden">
            <span v-if="!user.image" class="text-3xl font-semibold text-[#10B981]">
              {{ userInitials }}
            </span>
            <img v-else :src="userImageUrl" class="w-full h-full object-cover">
          </div>
          
          <div class="flex items-center gap-3">
            <!-- Upload Button -->
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
                  Uploading...
                </span>
                <span v-else>{{ user.image ? 'Change Photo' : 'Upload Photo' }}</span>
              </button>
            </div>
            
            <!-- Delete Button -->
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
                Deleting...
              </span>
              <span v-else>Delete Photo</span>
            </button>
          </div>
          
          <p v-if="imageError" class="mt-1 text-sm text-red-600">{{ imageError }}</p>
          <p class="text-xs text-gray-500 mt-2">Accepted formats: JPG, PNG (max 1MB)</p>
        </div>
        
        <!-- Personal Info Form -->
        <form @submit.prevent="submitProfile">
          <div>
            <h2 class="text-lg font-semibold text-[#1E293B] mb-4">Personal Information</h2>
            <div class="space-y-4">
              <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
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
                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
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
                class="px-6 py-2 bg-[#10B981] hover:bg-[#059669] text-white font-semibold rounded-lg transition-all disabled:opacity-50"
              >
                <span v-if="profileForm.processing">
                  <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Saving...
                </span>
                <span v-else>Save Changes</span>
              </button>
            </div>
          </div>
        </form>
      </div>
      
      <!-- Password Section -->
      <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <h2 class="text-lg font-semibold text-[#1E293B] mb-4">Password</h2>
        <form @submit.prevent="submitPassword">
          <div class="space-y-4">
            <div>
              <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
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
              <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
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
              <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
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
                class="px-6 py-2 bg-[#10B981] hover:bg-[#059669] text-white font-semibold rounded-lg transition-all disabled:opacity-50"
              >
                <span v-if="passwordForm.processing">
                  <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Updating...
                </span>
                <span v-else>Update Password</span>
              </button>
            </div>
          </div>
        </form>
      </div>
      
      <!-- Delete Account Section -->
      <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-lg font-semibold text-[#1E293B] mb-4">Delete Account</h2>
        <p class="text-sm text-gray-600 mb-4">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
        
        <ConfirmationModal 
          :show="confirmUserDeletion" 
          @close="confirmUserDeletion = false"
          title="Delete Account"
        >
          <template #content>
            <div class="space-y-4">
              <p>Are you sure you want to delete your account?</p>
              <p class="text-red-600 font-medium">This action cannot be undone. All your data will be permanently deleted.</p>
              
              <div>
                <label for="delete_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm your password</label>
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
              class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg mr-2"
            >
              Cancel
            </button>
            <button 
              @click="deleteUser" 
              class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg"
              :class="{ 'opacity-50': deleteAccountForm.processing }"
              :disabled="deleteAccountForm.processing"
            >
              <span v-if="deleteAccountForm.processing">
                <svg class="animate-spin -ml-1 mr-1 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Deleting...
              </span>
              <span v-else>Delete My Account</span>
            </button>
          </template>
        </ConfirmationModal>

        <!-- <ConfirmationModal 
          :show="confirmPhotoDeletion" 
          @close="confirmPhotoDeletion = false"
          title="Delete Profile Photo"
        >
          <template #content>
            Are you sure you want to delete your profile photo?
          </template>
          <template #footer>
            <button 
              @click="confirmPhotoDeletion = false" 
              class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg mr-2"
            >
              Cancel
            </button>
            <button 
              @click="deletePhoto" 
              class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg"
              :class="{ 'opacity-50': deleteInProgress }"
              :disabled="deleteInProgress"
            >
              <span v-if="deleteInProgress">
                <svg class="animate-spin -ml-1 mr-1 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Deleting...
              </span>
              <span v-else>Delete Photo</span>
            </button>
          </template>
        </ConfirmationModal> -->

        <div class="flex justify-end">
          <button 
            @click="confirmUserDeletion = true"
            class="px-6 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg transition-colors"
          >
            Delete Account
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
      full_name: ''
    })
  },
  flash: {
    type: Object,
    default: () => ({})
  }
});

const photoInput = ref(null);
const imageError = ref(null);
const uploadInProgress = ref(false);
const deleteInProgress = ref(false);
const confirmUserDeletion = ref(false);
const confirmPhotoDeletion = ref(false);

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
  return props.user?.first_name?.[0]?.toUpperCase() || '';
});

const userImageUrl = computed(() => {
  return props.user?.image 
    ? props.user.image.startsWith('http') 
      ? props.user.image 
      : `/storage/${props.user.image}`
    : null;
});

// Image handling
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
    imageError.value = 'Only JPG and PNG formats are accepted.';
    return;
  }

  if (file.size > maxSize) {
    imageError.value = 'Image size must not exceed 1MB.';
    return;
  }

  uploadInProgress.value = true;
  
  try {
    const formData = new FormData();
    formData.append('photo', file);

    await router.post(route('settings.photo.upload'), formData, {
      preserveScroll: true,
      onSuccess: () => {
        imageError.value = null;
      },
      onError: (errors) => {
        imageError.value = errors.photo || 'Error uploading image';
      }
    });
  } catch (error) {
    imageError.value = 'Error uploading image';
  } finally {
    uploadInProgress.value = false;
    event.target.value = ''; // Reset input
  }
};

const deletePhoto = async () => {
  if (!props.user?.image) return;
  
  deleteInProgress.value = true;
  confirmPhotoDeletion.value = false;
  
  try {
    await router.delete(route('settings.photo.destroy'), {
      preserveScroll: true,
      onSuccess: () => {
        imageError.value = null;
      },
      onError: () => {
        imageError.value = 'Error deleting image';
      }
    });
  } catch (error) {
    imageError.value = 'Error deleting image';
  } finally {
    deleteInProgress.value = false;
  }
};

const submitProfile = () => {
  profileForm.put(route('settings.profile.update'), {
    preserveScroll: true,
    onError: () => {
      // Handle errors
    }
  });
};

const submitPassword = () => {
  passwordForm.put(route('settings.password.update'), {
    preserveScroll: true,
    onSuccess: () => passwordForm.reset(),
    onError: () => {
      // Handle errors
    }
  });
};

const deleteUser = () => {
  deleteAccountForm.delete(route('settings.account.destroy'), {
    preserveScroll: false, // Important for redirect to work
    onSuccess: () => {
    },
    onError: (errors) => {
    }
  });
};
</script>