<template>
  <AdminLayout>
    <h2 class="text-xl md:text-2xl font-bold text-secondary mb-6">Messages Reçus</h2>
    
    <!-- Desktop Table -->
    <div class="hidden md:block bg-white rounded-xl shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full">
          <thead>
            <tr class="text-left text-sm font-medium text-gray-700 border-b-[0.5px] border-b-[#5AE4A8]">
              <th class="py-3 px-4 text-left">Nom</th>
              <th class="py-3 px-4 text-center">Email</th>
              <th class="py-3 px-4 text-center">Objet</th>
              <th class="py-3 px-4 text-center">Statut</th>
              <th class="py-3 px-4 text-center">Date</th>
              <th class="py-3 px-4 text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr 
              v-for="message in paginatedMessages" 
              :key="message.id" 
              class="border-b-[0.5px] border-b-[#5AE4A8] hover:bg-gray-50 cursor-pointer"
              @click="showMessageDetails(message)"
            >
              <td class="py-3 px-4 text-sm text-left">{{ message.full_name }}</td>
              <td class="py-3 px-4 text-sm text-center">{{ message.email }}</td>
              <td class="py-3 px-4 text-sm text-center">{{ message.subject }}</td>
              <td class="py-3 px-4 text-sm text-center">{{ formatDate(message.created_at) }}</td>

              <td class="py-3 px-4 text-sm text-center">
                <span :class="{
                  'bg-yellow-100 text-yellow-800': !message.is_read,
                  'bg-gray-100 text-gray-800': message.is_read
                }" class="px-2 py-1 rounded-full text-xs">
                  {{ message.is_read ? 'Lu' : 'Non lu' }}
                </span>
              </td>
              <td class="py-3 px-4 text-sm text-center" @click.stop>
                <button 
                  @click="deleteMessage(message)"
                  class="p-1 rounded-md hover:bg-red-100 transition-all"
                >
                  <img src="@/Assets/IMG/delete.png" alt="Delete" class="w-5 h-5">
                </button>
              </td>
            </tr>
            <tr v-if="paginatedMessages.length === 0">
              <td colspan="6" class="py-4 text-center text-gray-500">
                Aucun message trouvé
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Pagination -->
      <div v-if="paginatedMessages.length != 0" class="mt-6 flex justify-center px-4 py-3">
        <nav class="flex items-center space-x-1 sm:space-x-2">
          <button
            @click="prevPage"
            :disabled="currentPage === 1"
            class="px-2 sm:px-3 py-1 rounded-md bg-gray-100 text-gray-700 hover:bg-[#D1FAE5] disabled:opacity-50"
          >
            &lt;
          </button>
          <button
            v-for="page in pages"
            :key="page"
            @click="goToPage(page)"
            class="px-2 sm:px-3 py-1 rounded-md"
            :class="{
              'bg-[#10B981] text-white': currentPage === page,
              'bg-gray-100 text-gray-700 hover:bg-[#D1FAE5]': currentPage !== page
            }"
          >
            {{ page }}
          </button>
          <button
            @click="nextPage"
            :disabled="currentPage === totalPages"
            class="px-2 sm:px-3 py-1 rounded-md bg-gray-100 text-gray-700 hover:bg-[#D1FAE5] disabled:opacity-50"
          >
            &gt;
          </button>
        </nav>
      </div>
    </div>

    <!-- Mobile Cards -->
    <div class="md:hidden space-y-4">
      <div 
        v-for="message in paginatedMessages" 
        :key="message.id"
        class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:bg-gray-50 cursor-pointer"
        @click="showMessageDetails(message)"
      >
        <div class="flex justify-between items-start mb-2">
          <div>
            <h3 class="font-medium text-[#1E293B]">{{ message.full_name }}</h3>
            <p class="text-sm text-gray-600">{{ message.email }}</p>
          </div>
          <span :class="{
            'bg-yellow-100 text-yellow-800': !message.is_read,
            'bg-gray-100 text-gray-800': message.is_read
          }" class="px-2 py-1 rounded-full text-xs">
            {{ message.is_read ? 'Lu' : 'Non lu' }}
          </span>
        </div>
        <div class="flex justify-between items-center mt-3">
          <p class="text-sm text-gray-600">{{ message.subject }}</p>
          <p class="text-sm text-gray-600">{{ formatDate(message.created_at) }}</p>
        </div>
        <div class="flex justify-end pt-2 border-t border-gray-200" @click.stop>
          <button 
            @click="deleteMessage(message)"
            class="p-2 rounded-md bg-red-50 hover:bg-red-100 transition-all"
          >
            <img src="@/Assets/IMG/delete.png" alt="Delete" class="w-4 h-4">
          </button>
        </div>
      </div>
      <div v-if="paginatedMessages.length === 0" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 text-center text-gray-500">
        Aucun message trouvé
      </div>
      
      <!-- Mobile Pagination -->
      <div class="flex justify-center px-4 py-3">
        <nav class="flex items-center space-x-1 sm:space-x-2">
          <button
            @click="prevPage"
            :disabled="currentPage === 1"
            class="px-2 sm:px-3 py-1 rounded-md bg-gray-100 text-gray-700 hover:bg-[#D1FAE5] disabled:opacity-50"
          >
            &lt;
          </button>
          <button
            v-for="page in pages"
            :key="page"
            @click="goToPage(page)"
            class="px-2 sm:px-3 py-1 rounded-md"
            :class="{
              'bg-[#10B981] text-white': currentPage === page,
              'bg-gray-100 text-gray-700 hover:bg-[#D1FAE5]': currentPage !== page
            }"
          >
            {{ page }}
          </button>
          <button
            @click="nextPage"
            :disabled="currentPage === totalPages"
            class="px-2 sm:px-3 py-1 rounded-md bg-gray-100 text-gray-700 hover:bg-[#D1FAE5] disabled:opacity-50"
          >
            &gt;
          </button>
        </nav>
      </div>
    </div>

    <!-- Message Details Modal -->
    <Transition name="fade">
      <div v-if="showDetailsModal" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="closeDetailsModal"></div>
        
        <div class="bg-white rounded-xl shadow-lg max-w-md w-full max-h-[90vh] overflow-y-auto transform transition-all">
          <div class="p-6">
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-xl font-bold text-[#1E293B]">Détails du message</h3>
              <button @click="closeDetailsModal" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <div class="space-y-4">
              <div>
                <p class="text-sm text-gray-500">Nom complet</p>
                <p class="font-medium">{{ selectedMessage.full_name }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Email</p>
                <p class="font-medium">{{ selectedMessage.email }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Date</p>
                <p class="font-medium">{{ formatDate(selectedMessage.created_at) }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Objet</p>
                <p class="font-medium">{{ selectedMessage.subject }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Message</p>
                <div class="bg-gray-50 p-3 rounded-lg">
                  <p class="font-medium whitespace-pre-line">{{ selectedMessage.message }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Delete Confirmation Modal -->
    <ConfirmationModal 
      :show="showDeleteModal" 
      @close="closeDeleteModal"
    >
      <template #title>
        Confirmer la suppression
      </template>
      <template #content>
        Êtes-vous sûr de vouloir supprimer ce message ? Cette action est irréversible.
      </template>
      <template #footer>
        <button
          @click="closeDeleteModal"
          class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg mr-2"
        >
          Annuler
        </button>
        <button
          @click="confirmDelete"
          class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg"
        >
          Supprimer
        </button>
      </template>
    </ConfirmationModal>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ConfirmationModal from '@/Components/Dashboard/ConfirmationModal.vue';
import { ref, computed, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
  messages: {
    type: Array,
    required: true,
    default: () => []
  },
  pagination: {
    type: Object,
    required: true,
    default: () => ({
      current_page: 1,
      last_page: 1,
      per_page: 10,
      total: 0
    })
  }
});

// Reactive state
const currentPage = ref(props.pagination.current_page);
const itemsPerPage = props.pagination.per_page;
const showDetailsModal = ref(false);
const showDeleteModal = ref(false);
const selectedMessage = ref({});
const messageToDelete = ref(null);

// Computed properties
const paginatedMessages = computed(() => {
  return props.messages;
});

const totalPages = computed(() => {
  return props.pagination.last_page;
});

const pages = computed(() => {
  const maxVisiblePages = 5;
  const half = Math.floor(maxVisiblePages / 2);
  let start = Math.max(currentPage.value - half, 1);
  let end = Math.min(start + maxVisiblePages - 1, totalPages.value);
  
  if (end - start + 1 < maxVisiblePages) {
    start = Math.max(end - maxVisiblePages + 1, 1);
  }
  
  return Array.from({ length: end - start + 1 }, (_, i) => start + i);
});

// Methods
const formatDate = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleDateString('fr-FR');
};

const showMessageDetails = async (message) => {
  selectedMessage.value = message;
  showDetailsModal.value = true;
  
  // Mark as read if unread
  if (!message.is_read) {
    try {
      await axios.patch(route('admin.guest-messages.read', message.id));
      // Update local state
      message.is_read = 1;
    } catch (error) {
      console.error('Error marking message as read:', error);
    }
  }
};

const closeDetailsModal = () => {
  showDetailsModal.value = false;
};

const deleteMessage = (message) => {
  messageToDelete.value = message;
  showDeleteModal.value = true;
};

const closeDeleteModal = () => {
  showDeleteModal.value = false;
  messageToDelete.value = null;
};

const confirmDelete = () => {
  if (!messageToDelete.value) return;
  
  router.delete(route('admin.guest-messages.destroy', messageToDelete.value.id), {
    preserveScroll: true,
    onSuccess: () => {
      closeDeleteModal();
    }
  });
};

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
    router.get(route('admin.messages'), { page: currentPage.value }, {
      preserveState: true,
      replace: true
    });
  }
};

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
    router.get(route('admin.messages'), { page: currentPage.value }, {
      preserveState: true,
      replace: true
    });
  }
};

const goToPage = (page) => {
  if (page !== '...' && page !== currentPage.value) {
    currentPage.value = page;
    router.get(route('admin.messages'), { page }, {
      preserveState: true,
      replace: true
    });
  }
};
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>