<template>
  <div>
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <div @click="$inertia.visit(route('admin.home'))" class="flex items-center space-x-2 cursor-pointer">
        <img src="@/Assets/IMG/wallet.png" alt="wallet logo" class="w-10 h-10">
        <span class="text-xl font-black">BEZTAM</span>
      </div>
      
      <Link
        :href="route('admin.logout')"
        method="post"
        as="button"
        class="hidden md:flex items-center justify-center rounded-xl py-2 px-4 bg-green-300 hover:bg-green-400 border-green-300 border-2 hover:border-black text-black font-semibold text-lg transition-all"
      >
        <img src="@/Assets/IMG/dec.png" alt="Déconnexion" class="w-5 h-5 mr-3">
        <span>Déconnexion</span>
      </Link>
      
      <button @click="showMobileMenu = true" class="md:hidden p-2 bg-white rounded-full shadow-sm border border-gray-200">
        <img src="@/Assets/IMG/menu.png" alt="Menu" class="w-4 h-4">
      </button>
    </div>

    <!-- Desktop Nav - Sticky -->
    <div ref="navRef" class="hidden md:block bg-[#EBFFF5] rounded-xl shadow-sm p-4 mb-8 transition-all duration-300"
         :class="{'fixed-nav': isNavFixed}">
      <nav>
        <ul class="flex justify-center space-x-4">
          <!-- Home Link -->
          <li>
            <Link
              :href="route('admin.home')"
              class="nav-link flex flex-col items-center px-4 py-2 group"
            >
              <div 
                class="nav-icon w-12 h-12 rounded-full border-2 flex items-center justify-center mb-1"
                :class="isActive('/admin/home') || isActive('/admin') ? 
                  'bg-[#D1FAE5] border-[#5AE4A8]' : 
                  'border-black group-hover:bg-[#D1FAE5] group-hover:border-[#5AE4A8]'"
              >
                <img src="@/Assets/IMG/home.png" alt="Accueil" class="w-6 h-6">
              </div>
              <span 
                class="text-xs font-bold"
                :class="isActive('/admin/home') || isActive('/admin') ? 
                  'text-[#10B981]' : 
                  'text-black group-hover:text-[#10B981]'"
              >
                Accueil
              </span>
            </Link>
          </li>

          <!-- Clients Link -->
          <li>
            <Link
              :href="route('admin.clients')"
              class="nav-link flex flex-col items-center px-4 py-2 group"
            >
              <div 
                class="nav-icon w-12 h-12 rounded-full border-2 flex items-center justify-center mb-1"
                :class="isActiveStartsWith('/admin/clients') ? 
                  'bg-[#D1FAE5] border-[#5AE4A8]' : 
                  'border-black group-hover:bg-[#D1FAE5] group-hover:border-[#5AE4A8]'"
              >
                <img src="@/Assets/IMG/client.png" alt="Clients" class="w-6 h-6">
              </div>
              <span 
                class="text-xs font-bold"
                :class="isActiveStartsWith('/admin/clients') ? 
                  'text-[#10B981]' : 
                  'text-black group-hover:text-[#10B981]'"
              >
                Clients
              </span>
            </Link>
          </li>

          <!-- Reclamations Link -->
          <li>
            <Link
              :href="route('admin.reclamations')"
              class="nav-link flex flex-col items-center px-4 py-2 group"
            >
              <div 
                class="nav-icon w-12 h-12 rounded-full border-2 flex items-center justify-center mb-1"
                :class="isActiveStartsWith('/admin/reclamations') ? 
                  'bg-[#D1FAE5] border-[#5AE4A8]' : 
                  'border-black group-hover:bg-[#D1FAE5] group-hover:border-[#5AE4A8]'"
              >
                <img src="@/Assets/IMG/chat.png" alt="Réclamations" class="w-6 h-6">
              </div>
              <span 
                class="text-xs font-bold"
                :class="isActiveStartsWith('/admin/reclamations') ? 
                  'text-[#10B981]' : 
                  'text-black group-hover:text-[#10B981]'"
              >
                Réclamations
              </span>
            </Link>
          </li>

          <!-- Messages Link -->
          <li>
            <Link
              :href="route('admin.messages')"
              class="nav-link flex flex-col items-center px-4 py-2 group"
            >
              <div 
                class="nav-icon w-12 h-12 rounded-full border-2 flex items-center justify-center mb-1"
                :class="isActiveStartsWith('/admin/messages') ? 
                  'bg-[#D1FAE5] border-[#5AE4A8]' : 
                  'border-black group-hover:bg-[#D1FAE5] group-hover:border-[#5AE4A8]'"
              >
                <img src="@/Assets/IMG/mail.png" alt="Messages" class="w-6 h-6">
              </div>
              <span 
                class="text-xs font-bold"
                :class="isActiveStartsWith('/admin/messages') ? 
                  'text-[#10B981]' : 
                  'text-black group-hover:text-[#10B981]'"
              >
                Messages
              </span>
            </Link>
          </li>
        </ul>
      </nav>
    </div>

    <!-- Spacer div that appears when nav becomes fixed - matches exact height of nav -->
    <div v-if="isNavFixed" :style="{ height: navHeight + 'px' }" class="hidden md:block mb-8"></div>

    <!-- Mobile Nav -->
    <div v-if="showMobileMenu" class="fixed inset-0 z-50 bg-black bg-opacity-50 md:hidden">
      <div class="absolute left-0 top-0 h-full w-64 bg-white shadow-lg flex flex-col">
        <div class="p-4 border-b border-gray-200 flex justify-end">
          <button @click="showMobileMenu = false" class="p-2">
            ✕
          </button>
        </div>
        
        <nav class="p-4 flex-grow">
          <ul class="space-y-2">
            <!-- Home Mobile Link -->
            <li>
              <Link
                :href="route('admin.home')"
                @click="showMobileMenu = false"
                class="flex items-center px-4 py-3 rounded-lg border-l-8"
                :class="isActive('/admin/home') || isActive('/admin') ? 
                  'border-primary text-highlight' : 
                  'border-transparent hover:bg-primary-light text-gray-700 hover:text-highlight'"
              >
                <img src="@/Assets/IMG/home.png" alt="Accueil" class="w-6 h-6 mr-4">
                <span class="font-bold">Accueil</span>
              </Link>
            </li>

            <!-- Clients Mobile Link -->
            <li>
              <Link
                :href="route('admin.clients')"
                @click="showMobileMenu = false"
                class="flex items-center px-4 py-3 rounded-lg border-l-8"
                :class="isActiveStartsWith('/admin/clients') ? 
                  'border-primary text-highlight' : 
                  'border-transparent hover:bg-primary-light text-gray-700 hover:text-highlight'"
              >
                <img src="@/Assets/IMG/client.png" alt="Clients" class="w-6 h-6 mr-4">
                <span class="font-bold">Clients</span>
              </Link>
            </li>

            <!-- Reclamations Mobile Link -->
            <li>
              <Link
                :href="route('admin.reclamations')"
                @click="showMobileMenu = false"
                class="flex items-center px-4 py-3 rounded-lg border-l-8"
                :class="isActiveStartsWith('/admin/reclamations') ? 
                  'border-primary text-highlight' : 
                  'border-transparent hover:bg-primary-light text-gray-700 hover:text-highlight'"
              >
                <img src="@/Assets/IMG/chat.png" alt="Réclamations" class="w-6 h-6 mr-4">
                <span class="font-bold">Réclamations</span>
              </Link>
            </li>

            <!-- Messages Mobile Link -->
            <li>
              <Link
                :href="route('admin.messages')"
                @click="showMobileMenu = false"
                class="flex items-center px-4 py-3 rounded-lg border-l-8"
                :class="isActiveStartsWith('/admin/messages') ? 
                  'border-primary text-highlight' : 
                  'border-transparent hover:bg-primary-light text-gray-700 hover:text-highlight'"
              >
                <img src="@/Assets/IMG/mail.png" alt="Messages" class="w-6 h-6 mr-4">
                <span class="font-bold">Messages</span>
              </Link>
            </li>
          </ul>
        </nav>
        
        <div class="p-4 border-t border-gray-200">
          <Link
            :href="route('admin.logout')"
            method="post"
            as="button"
            class="flex items-center justify-center rounded-xl py-2 px-4 bg-green-300 hover:bg-green-400 border-green-300 border-2 hover:border-black text-black font-semibold text-lg transition-all w-full"
          >
            <img src="@/Assets/IMG/dec.png" alt="Déconnexion" class="w-5 h-5 mr-3">
            <span>Déconnexion</span>
          </Link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import { usePage } from '@inertiajs/vue3';

const showMobileMenu = ref(false);
const page = usePage();
const isNavFixed = ref(false);
const navRef = ref(null);
const navHeight = ref(0);
let navOffsetTop = 0;
const scrollOffset = 20; // Pixels to scroll down before fixing nav

// Helper functions for active state checking
const isActive = (path) => {
  return page.url === path;
};

const isActiveStartsWith = (path) => {
  return page.url.startsWith(path);
};

// Handle scroll event to fix navbar
const handleScroll = () => {
  // Only fix the nav after scrolling past nav position + offset
  if (window.scrollY >= navOffsetTop + scrollOffset) {
    isNavFixed.value = true;
  } else {
    isNavFixed.value = false;
  }
};

// Setup scroll event listeners
onMounted(() => {
  if (navRef.value) {
    // Get the initial position and height of the nav
    navOffsetTop = navRef.value.offsetTop;
    navHeight.value = navRef.value.offsetHeight;
    
    // Add event listeners
    window.addEventListener('scroll', handleScroll);
    
    // Re-calculate on resize
    const handleResize = () => {
      if (navRef.value) {
        navOffsetTop = navRef.value.offsetTop;
        navHeight.value = navRef.value.offsetHeight;
      }
    };
    
    window.addEventListener('resize', handleResize);
    
    // Initial calculation after content loads
    setTimeout(handleResize, 100);
  }
});

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
  window.removeEventListener('resize', () => {});
});
</script>

<style scoped>
/* Desktop Nav Styles */
.nav-link {
  @apply transition-all duration-200;
}

.nav-icon {
  @apply transition-all duration-200;
}

/* Active states defined in classes */
.active .nav-icon {
  @apply bg-[#D1FAE5] border-[#5AE4A8];
}

.active span {
  @apply text-[#10B981];
}

/* Mobile Nav Styles */
.border-primary {
  @apply border-[#5AE4A8];
}

.text-highlight {
  @apply text-[#10B981];
}

.hover\:bg-primary-light:hover {
  @apply bg-[#D1FAE5];
}

.hover\:text-highlight:hover {
  @apply text-[#10B981];
}

/* Fixed nav styles */
.fixed-nav {
  @apply fixed top-0 left-0 right-0 z-40 mx-4 sm:mx-6 lg:mx-8 mt-4;
  border-radius: 0.75rem;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
  background-color: rgba(235, 255, 245, 0.98); /* Slightly transparent for a modern look */
}
</style>