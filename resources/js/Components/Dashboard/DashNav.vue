<template>
  <!-- Header -->
  <header class="w-full fixed bg-[#F8F8F9] top-0 left-0 right-0 z-20">
    <div class="container mx-auto px-4 py-3 flex items-center justify-between">
      <!-- Mobile Menu Button -->
      <button @click="toggleSidebar" class="md:hidden p-2">
        <img src="@/Assets/IMG/menu.png" alt="Menu" class="w-6 h-6">
      </button>

      <!-- Logo -->
      <Link :href="route('dashboard')" class="flex items-center space-x-2 cursor-pointer">
        <img src="@/Assets/IMG/wallet.png" alt="BEZTAM logo" class="w-8 h-8 object-contain">
        <span class="text-xl font-black text-[#1E293B]">BEZTAM</span>
      </Link>

      <!-- Profile Dropdown -->
      <div class="relative">
        <button 
          @click="toggleProfileDropdown" 
          ref="profileButton" 
          class="flex items-center space-x-2 focus:outline-none"
        >
          <div class="w-8 h-8 rounded-full bg-[#D1FAE5] flex items-center justify-center overflow-hidden">
            <span v-if="!user.image" class="text-[#10B981] font-semibold">
              {{ userInitials }}
            </span>
            <img v-else :src="userImageUrl" class="w-full h-full object-cover">
          </div>
          <span class="font-medium hidden sm:inline">{{ user.first_name }}</span>
          <svg 
            xmlns="http://www.w3.org/2000/svg" 
            class="h-4 w-4 hidden sm:inline" 
            viewBox="0 0 20 20" 
            fill="currentColor"
          >
            <path 
              fill-rule="evenodd" 
              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" 
              clip-rule="evenodd" 
            />
          </svg>
        </button>

        <!-- Dropdown Menu -->
        <div 
          v-show="profileDropdownOpen" 
          ref="profileDropdown" 
          class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50"
        >
          <div class="px-4 py-2 border-b border-gray-100">
            <p class="text-sm font-semibold text-[#1E293B]">{{ user.full_name }}</p>
            <p class="text-xs text-gray-500">{{ user.email }}</p>
          </div>
          
          <Link 
            :href="route('contact')" 
            class="block px-4 py-2 text-sm text-gray-700 hover:bg-[#D1FAE5] hover:text-[#10B981]"
          >
           Messagerie
          </Link>

          <Link 
            :href="route('settings')" 
            class="block px-4 py-2 text-sm text-gray-700 hover:bg-[#D1FAE5] hover:text-[#10B981]"
          >
            Paramètres
          </Link>

          <Link 
            :href="route('logout')" 
            method="post" 
            as="button"
            class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700"
          >
            Déconnexion
          </Link>
        </div>
      </div>
    </div>
  </header>

  <!-- Sidebar -->
  <div 
    :class="{
      'hidden': !sidebarOpen, 
      'md:block': true, 
      'fixed inset-y-0 left-0 w-64 bg-[#F8F8F9] pt-16 z-10': true
    }"
  >
    <div class="px-4 py-6 md:bg-[#F8F8F9] bg-white">
      <!-- Nav Links -->
      <nav class="flex flex-col bg-white rounded-xl shadow-sm p-2">
        <Link
          :href="route('dashboard')"
          class="nav-link"
          :class="{ 'active-link': route().current('dashboard') }"
        >
          <img src="@/Assets/IMG/home.png" alt="Accueil" class="w-6 h-6 mr-4">
          <span>Accueil</span>
        </Link>
        
        <Link
          :href="route('budgets')"
          class="nav-link"
          :class="{ 'active-link': route().current('budgets') }"
        >
          <img src="@/Assets/IMG/budget.png" alt="Budgets" class="w-6 h-6 mr-4">
          <span>Revenus</span>
        </Link>
        
        <Link
          :href="route('expenses')"
          class="nav-link"
          :class="{ 'active-link': route().current('expenses') }"
        >
          <img src="@/Assets/IMG/depenses.png" alt="Dépenses" class="w-6 h-6 mr-4">
          <span>Dépenses</span>
        </Link>
        
        <Link
          :href="route('history')"
          class="nav-link"
          :class="{ 'active-link': route().current('history') }"
        >
          <img src="@/Assets/IMG/calendrier.png" alt="Historique" class="w-6 h-6 mr-4">
          <span>Historique</span>
        </Link>
        
        <Link
          :href="route('categories')"
          class="nav-link"
          :class="{ 'active-link': route().current('categories') }"
        >
          <img src="@/Assets/IMG/category.png" alt="Catégories" class="w-6 h-6 mr-4">
          <span>Catégories</span>
        </Link>
        
        <Link
          :href="route('savings')"
          class="nav-link"
          :class="{ 'active-link': route().current('savings') }"
        >
          <img src="@/Assets/IMG/epargne.png" alt="Épargne" class="w-6 h-6 mr-4">
          <span>Épargne</span>
        </Link>
        
        <Link
          :href="route('settings')"
          class="nav-link"
          :class="{ 'active-link': route().current('settings') }"
        >
          <img src="@/Assets/IMG/parametres.png" alt="Paramètres" class="w-6 h-6 mr-4">
          <span>Paramètres</span>
        </Link>
      </nav>

      <!-- Sidebar Logout Button -->
      <div class="mt-4">
        <Link 
          :href="route('logout')" 
          method="post" 
          as="button"
          class="flex items-center justify-center rounded-xl py-2 px-4 bg-green-300 hover:bg-green-400 border-green-300 border-2 hover:border-black text-black font-semibold text-lg transition-all w-full"
        >
          <img src="@/Assets/IMG/dec.png" alt="Déconnexion" class="w-6 h-6 mr-2">
          <span>Déconnexion</span>
        </Link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { Link } from '@inertiajs/vue3';

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
      created_at: null,
      updated_at: null,
      full_name: ''
    })
  }
});

const sidebarOpen = ref(false);
const profileDropdownOpen = ref(false);
const profileButton = ref(null);
const profileDropdown = ref(null);

// Computed properties
const userInitials = computed(() => {
  return props.user?.first_name?.[0]?.toUpperCase() || '';
});

const userImageUrl = computed(() => {
  if (!props.user?.image) return null;
  return props.user.image.startsWith('http') 
    ? props.user.image 
    : `/storage/${props.user.image}`;
});

// Methods
const toggleSidebar = () => {
  sidebarOpen.value = !sidebarOpen.value;
};

const toggleProfileDropdown = () => {
  profileDropdownOpen.value = !profileDropdownOpen.value;
};

const handleClickOutside = (event) => {
  if (!profileButton.value?.contains(event.target) && 
      !profileDropdown.value?.contains(event.target)) {
    profileDropdownOpen.value = false;
  }
};

const checkScreenSize = () => {
  sidebarOpen.value = window.innerWidth >= 768;
};

// Lifecycle hooks
onMounted(() => {
  document.addEventListener('click', handleClickOutside);
  checkScreenSize();
  window.addEventListener('resize', checkScreenSize);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
  window.removeEventListener('resize', checkScreenSize);
});
</script>

<style scoped>
.nav-link {
  @apply flex items-center px-4 py-2 mt-2 mb-4 border-l-8 border-transparent rounded-r-md font-bold text-gray-700 transition-all;
}

.nav-link:hover {
  @apply bg-[#D1FAE5] border-[#5AE4A8] text-[#1E293B];
}

.active-link {
  @apply border-l-8 border-[#5AE4A8] bg-[#D1FAE5] text-[#1E293B];
}
</style>