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

      <!-- Notification and Profile -->
      <div class="flex items-center space-x-4">
        <!-- Notification Button -->
        <div class="relative">
          <button
            @click="toggleNotificationDropdown"
            class="p-2 rounded-full border-2 border-[#5AE4A8] bg-white hover:bg-[#D1FAE5] focus:outline-none transition-colors relative"
            ref="notificationButton"
          >
            <img src="@/Assets/IMG/notif.png" alt="Notifications" class="w-5 h-5">
            <span v-if="notifications.length > 0" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
              {{ notifications.length }}
            </span>
          </button>

          <!-- Notification Dropdown -->
          <div
            v-show="notificationDropdownOpen"
            ref="notificationDropdown"
            class="absolute right-0 mt-2 w-72 md:w-80 bg-white rounded-md shadow-lg py-1 z-50 max-h-96 overflow-y-auto border border-[#5AE4A8]"
          >
            <div class="px-4 py-3 border-b border-[#5AE4A8] bg-[#D1FAE5]">
              <p class="text-sm font-semibold text-[#1E293B]">Notifications</p>
            </div>
            <div v-if="notifications.length > 0" class="divide-y divide-[#5AE4A8]">
              <div
                v-for="notification in notifications"
                :key="notification.id"
                class="px-4 py-2 hover:bg-[#D1FAE5] cursor-pointer"
                @click="handleNotificationClick(notification)"
              >
                <p class="text-sm text-gray-700">{{ notification.notification }}</p>
                <p class="text-xs text-gray-500">{{ formatTimeAgo(notification.created_at) }}</p>
              </div>
            </div>
            <div v-else class="px-4 py-4 text-center text-sm text-gray-500">
              Pas de notifications
            </div>
          </div>
        </div>

        <!-- Profile Dropdown -->
        <div class="relative" ref="profileDropdownContainer">
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

          <!-- Profile Dropdown Menu -->
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
              :href="route('dashContact')"
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
          <span>revenus</span>
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
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Link, router } from '@inertiajs/vue3';

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
      full_name: ''
    })
  },
  notifications: {
    type: Array,
    default: () => []
  }
});

// Reactive state
const sidebarOpen = ref(false);
const notificationDropdownOpen = ref(false);
const profileDropdownOpen = ref(false);
const notificationButton = ref(null);
const notificationDropdown = ref(null);
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

// Time formatter
const formatTimeAgo = (dateString) => {
  const date = new Date(dateString);
  const now = new Date();
  const seconds = Math.floor((now - date) / 1000);
  
  const intervals = {
    année: 31536000,
    mois: 2592000,
    semaine: 604800,
    jour: 86400,
    heure: 3600,
    minute: 60
  };

  for (const [unit, secondsInUnit] of Object.entries(intervals)) {
    const interval = Math.floor(seconds / secondsInUnit);
    if (interval >= 1) {
      return `il y a ${interval} ${unit}${interval === 1 ? '' : 's'}`;
    }
  }
  return 'il y a quelques secondes';
};

// Notification handler
const handleNotificationClick = (notification) => {
  notificationDropdownOpen.value = false;
  
  switch (notification.type) {
    case 'limit':
      router.visit(route('categories'));
      break;
    case 'monthlyexpense':
      router.visit(route('expenses'));
      break;
    case 'message':
      router.visit(route('dashContact'));
      break;
  }
};

// Toggle functions
const toggleSidebar = () => {
  sidebarOpen.value = !sidebarOpen.value;
};

const toggleNotificationDropdown = () => {
  notificationDropdownOpen.value = !notificationDropdownOpen.value;
  if (notificationDropdownOpen.value) {
    profileDropdownOpen.value = false;
  }
};

const toggleProfileDropdown = () => {
  profileDropdownOpen.value = !profileDropdownOpen.value;
  if (profileDropdownOpen.value) {
    notificationDropdownOpen.value = false;
  }
};

// Click outside handler
const handleClickOutside = (event) => {
  if (!profileButton.value?.contains(event.target) &&
      !profileDropdown.value?.contains(event.target)) {
    profileDropdownOpen.value = false;
  }
  
  if (!notificationButton.value?.contains(event.target) &&
      !notificationDropdown.value?.contains(event.target)) {
    notificationDropdownOpen.value = false;
  }
};

// Screen size handler
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