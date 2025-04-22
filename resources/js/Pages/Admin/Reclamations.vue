<template>
  <AdminLayout>
    <h2 class="text-xl md:text-2xl font-bold text-secondary mb-6">Réclamations</h2>
    
    <!-- Desktop Table -->
    <div class="hidden md:block bg-white rounded-xl shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full">
          <thead>
            <tr class="text-sm font-medium text-gray-700 border-b-[0.5px] border-b-[#5AE4A8]">
              <th class="py-3 px-4 text-left">ID</th>
              <th class="py-3 px-4 text-center">Nom</th>
              <th class="py-3 px-4 text-center">Email</th>
              <th class="py-3 px-4 text-center">Objet</th>
              <th class="py-3 px-4 text-center">Date</th>
              <th class="py-3 px-4 text-center">Statut</th>
              <th class="py-3 px-4 text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr 
              v-for="reclamation in reclamations" 
              :key="reclamation.id" 
              class="border-b-[0.5px] border-b-[#5AE4A8] hover:bg-gray-50 cursor-pointer"
              @click="showMessageDetails(reclamation)"
            >
              <td class="py-3 px-4 text-sm font-medium text-left">#{{ reclamation.id }}</td>
              <td class="py-3 px-4 text-sm text-center">{{ reclamation.user.first_name }} {{ reclamation.user.last_name }}</td>
              <td class="py-3 px-4 text-sm text-center">{{ reclamation.user.email }}</td>
              <td class="py-3 px-4 text-sm text-center">{{ reclamation.subject }}</td>
              <td class="py-3 px-4 text-sm text-center">{{ formatDate(reclamation.created_at) }}</td>
              <td class="py-3 px-4 text-sm text-center">
                <span :class="{
                  'bg-yellow-100 text-yellow-800': !reclamation.is_read,
                  'bg-gray-100 text-gray-800': reclamation.is_read
                }" class="px-2 py-1 rounded-full text-xs">
                  {{ reclamation.is_read ? 'Lu' : 'Non lu' }}
                </span>
              </td>
              <td class="py-3 px-4 text-sm text-center" @click.stop>
                <div class="flex justify-center space-x-2">
                  <button 
                    @click="openReplyModal(reclamation)"
                    class="p-1 rounded-md hover:bg-[#D1FAE5] transition-all"
                  >
                    <img src="@/Assets/IMG/mail.png" alt="Reply" class="w-5 h-5">
                  </button>
                  <button 
                    @click="openDeleteModal(reclamation)"
                    class="p-1 rounded-md hover:bg-red-100 transition-all"
                  >
                    <img src="@/Assets/IMG/delete.png" alt="Delete" class="w-5 h-5">
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="reclamations.length === 0">
              <td colspan="7" class="py-4 text-center text-gray-500">
                Aucune réclamation trouvée
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-center">
      <nav class="flex items-center space-x-1 sm:space-x-2">
        <button
          @click="previousPage"
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

    <!-- Mobile Cards -->
    <div class="md:hidden space-y-4">
      <div 
        v-for="reclamation in reclamations" 
        :key="reclamation.id"
        class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:bg-gray-50 cursor-pointer"
        @click="showMessageDetails(reclamation)"
      >
        <div class="flex justify-between items-start mb-2">
          <div>
            <h3 class="font-medium text-[#1E293B]">#{{ reclamation.id }} - {{ reclamation.user.first_name }} {{ reclamation.user.last_name }}</h3>
            <p class="text-sm text-gray-600">{{ reclamation.subject }}</p>
          </div>
          <span :class="{
            'bg-yellow-100 text-yellow-800': !reclamation.is_read,
            'bg-gray-100 text-gray-800': reclamation.is_read
          }" class="px-2 py-1 rounded-full text-xs">
            {{ reclamation.is_read ? 'Lu' : 'Non lu' }}
          </span>
        </div>
        <div class="flex justify-between items-center mt-3">
          <p class="text-sm text-gray-600">{{ formatDate(reclamation.created_at) }}</p>
          <div class="flex space-x-2" @click.stop>
            <button 
              @click="openReplyModal(reclamation)"
              class="p-2 rounded-md bg-[#D1FAE5] transition-all"
            >
              <img src="@/Assets/IMG/mail.png" alt="Reply" class="w-4 h-4">
            </button>
            <button 
              @click="openDeleteModal(reclamation)"
              class="p-2 rounded-md bg-red-50 transition-all"
            >
              <img src="@/Assets/IMG/delete.png" alt="Delete" class="w-4 h-4">
            </button>
          </div>
        </div>
      </div>
      <div v-if="reclamations.length === 0" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 text-center text-gray-500">
        Aucune réclamation trouvée
      </div>
    </div>

    <!-- Message Details Modal -->
    <Transition name="fade">
      <div v-if="showDetailsModal" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="closeDetailsModal"></div>
        
        <div class="bg-white rounded-xl shadow-lg max-w-md w-full max-h-[90vh] overflow-y-auto transform transition-all">
          <div class="p-6">
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-xl font-bold text-[#1E293B]">Détails de la réclamation</h3>
              <button @click="closeDetailsModal" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <div class="space-y-4">
              <div>
                <p class="text-sm text-gray-500">Client</p>
                <p class="font-medium">{{ selectedMessage.user.first_name }} {{ selectedMessage.user.last_name }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Email</p>
                <p class="font-medium">{{ selectedMessage.user.email }}</p>
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

    <!-- Reply Modal -->
    <Transition name="fade">
      <div v-if="showReplyModal" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="closeReplyModal"></div>
        
        <div class="bg-white rounded-xl shadow-lg max-w-md w-full max-h-[90vh] overflow-y-auto transform transition-all">
          <div class="p-6">
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-xl font-bold text-[#1E293B]">Répondre au client</h3>
              <button @click="closeReplyModal" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <form @submit.prevent="sendReply">
              <div class="space-y-4">
                <div>
                  <p class="font-medium">À: {{ selectedMessage.user.first_name }} {{ selectedMessage.user.last_name }}</p>
                  <p class="text-sm text-gray-600">{{ selectedMessage.user.email }}</p>
                </div>
                <div>
                  <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Objet*</label>
                  <input
                    v-model="replyData.subject"
                    type="text"
                    id="subject"
                    class="w-full border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent"
                    required
                  >
                </div>
                <div>
                  <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message*</label>
                  <textarea
                    v-model="replyData.message"
                    id="message"
                    rows="4"
                    class="w-full border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent"
                    required
                  ></textarea>
                </div>
              </div>

              <div class="mt-6 flex justify-end space-x-3">
                <button
                  type="button"
                  @click="closeReplyModal"
                  class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg"
                >
                  Annuler
                </button>
                <button
                  type="submit"
                  class="px-4 py-2 bg-[#10B981] hover:bg-[#059669] text-white rounded-lg"
                  :disabled="!replyData.subject || !replyData.message"
                >
                  Envoyer
                </button>
              </div>
            </form>
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
        Êtes-vous sûr de vouloir supprimer cette réclamation ? Cette action est irréversible.
      </template>
      <template #footer>
        <button
          @click="closeDeleteModal"
          class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg mr-2"
        >
          Annuler
        </button>
        <button
          @click="deleteMessage"
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
  reclamations: {
    type: Array,
    required: true
  },
  pagination: {
    type: Object,
    required: true
  }
});

// Pagination
const currentPage = ref(1);
const itemsPerPage = 10;

// Computed properties
const paginatedReclamations = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  return props.reclamations.slice(start, end);
});

const totalPages = computed(() => {
  return Math.ceil(props.reclamations.length / itemsPerPage);
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

// Navigation
const previousPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
  }
};

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
  }
};

const goToPage = (page) => {
  if (page !== '...') {
    currentPage.value = page;
  }
};

// Modal states
const showDetailsModal = ref(false);
const showReplyModal = ref(false);
const showDeleteModal = ref(false);
const selectedMessage = ref({});
const replyData = ref({
  subject: '',
  message: ''
});

// Format date
const formatDate = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleDateString('fr-FR');
};

// Show message details and mark as read
const showMessageDetails = async (message) => {
  selectedMessage.value = message;
  showDetailsModal.value = true;
  
  // Mark as read if unread
  if (!message.is_read) {
    try {
      await axios.patch(route('admin.user-messages.read', message.id));
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

const openReplyModal = (message) => {
  selectedMessage.value = message;
  replyData.value = { 
    subject: `Re: ${message.subject}`,
    message: ''
  };
  showReplyModal.value = true;
};

const closeReplyModal = () => {
  showReplyModal.value = false;
};

const openDeleteModal = (message) => {
  selectedMessage.value = message;
  showDeleteModal.value = true;
};

const closeDeleteModal = () => {
  showDeleteModal.value = false;
};

// Actions
const sendReply = async () => {
  try {
    await axios.post(route('admin.user-messages.reply'), {
      user_id: selectedMessage.value.user.id,
      subject: replyData.value.subject,
      message: replyData.value.message
    });
    closeReplyModal();
    // Show success notification
  } catch (error) {
    console.error('Error sending reply:', error);
  }
};

const deleteMessage = () => {
  router.delete(route('admin.user-messages.destroy', selectedMessage.value.id), {
    preserveScroll: true,
    onSuccess: () => {
      closeDeleteModal();
      // Show success notification
    },
    onError: () => {
      // Show error notification
    }
  });
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