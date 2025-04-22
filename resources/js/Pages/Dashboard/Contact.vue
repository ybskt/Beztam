<template>
  <DashLayout>
    <!-- Message Details Modal -->
    <Transition name="fade">
      <div v-if="showDetailsModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="closeDetailsModal"></div>
        
        <div class="relative z-50 bg-white rounded-xl shadow-lg max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto">
          <div class="p-6">
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-xl font-bold text-[#1E293B]">{{ selectedMessage?.subject }}</h3>
              <button @click="closeDetailsModal" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
            <div class="space-y-4">
              <div>
                <p class="text-sm text-gray-500">Date:</p>
                <p class="font-medium">{{ formatDate(selectedMessage?.created_at) }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Message:</p>
                <div class="bg-gray-50 p-3 rounded-lg">
                  <p class="font-medium whitespace-pre-line">{{ selectedMessage?.message }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Reply Modal -->
    <Transition name="fade">
      <div v-if="showReplyModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="closeReplyModal"></div>
        
        <div class="relative z-50 bg-white rounded-xl shadow-lg max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto">
          <div class="p-6">
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-xl font-bold text-[#1E293B]">Envoyer un message</h3>
              <button @click="closeReplyModal" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
            <form @submit.prevent="sendReply">
              <div class="space-y-4">
                <div>
                  <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Objet*</label>
                  <input
                    v-model="replyForm.subject"
                    type="text"
                    id="subject"
                    class="w-full border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent"
                    required
                  >
                </div>
                <div>
                  <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message*</label>
                  <textarea
                    v-model="replyForm.message"
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
                  :disabled="!replyForm.subject || !replyForm.message"
                >
                  Envoyer
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Contact Navigation Tabs -->
    <div class="flex flex-col sm:flex-row gap-4 mb-6 justify-center">
        <button
          @dblclick="refreshTab('received')"
          @click="activeTab = 'received'"
          :class="{
            'bg-[#86EFAC] border-black': activeTab === 'received',
            'bg-white border-[#5AE4A8]': activeTab !== 'received'
          }"
          class="flex items-center justify-center rounded-xl py-2 px-4 border-2 text-black font-semibold text-lg transition-all w-full sm:w-auto"
        >
          <span>Messages Reçus</span>
        </button>
        <button
          @dblclick="refreshTab('support')"
          @click="activeTab = 'support'"
          :class="{
            'bg-[#86EFAC] border-black': activeTab === 'support',
            'bg-white border-[#5AE4A8]': activeTab !== 'support'
          }"
          class="flex items-center justify-center rounded-xl py-2 px-4 border-2 text-black font-semibold text-lg transition-all w-full sm:w-auto"
        >
          <span>Contacter Support</span>
        </button>
        <button
          @dblclick="refreshTab('sent')"
          @click="activeTab = 'sent'"
          :class="{
            'bg-[#86EFAC] border-black': activeTab === 'sent',
            'bg-white border-[#5AE4A8]': activeTab !== 'sent'
          }"
          class="flex items-center justify-center rounded-xl py-2 px-4 border-2 text-black font-semibold text-lg transition-all w-full sm:w-auto"
        >
          <span>Messages Envoyés</span>
        </button>
      </div>
    <!-- Messages Reçus Section -->
    <div v-show="activeTab === 'received'" class="bg-white rounded-xl shadow-sm p-4 md:p-6">
      <h2 class="text-lg md:text-xl font-bold text-[#1E293B] mb-3 md:mb-4">Messages Reçus</h2>
      <hr class="border-t-2 border-gray-200 mb-4 md:mb-6">

      <!-- Table for medium and up screens -->
      <div class="hidden md:block overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full">
            <thead>
              <tr class="text-left text-sm font-medium text-gray-700 border-b-[0.5px] border-b-[#5AE4A8]">
                <th class="py-2 px-4 text-left">Date</th>
                <th class="py-2 px-4 text-center">Objet</th>
                <th class="py-2 px-4 text-center">Statut</th>
                <th class="py-2 px-4 text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="message in receivedMessages.data"
                :key="message.id"
                
                class="border-b-[0.5px] border-b-[#5AE4A8] bg-white cursor-pointer"
                @click="showMessageDetails(message)"
              >
                <td class="py-2 px-4 text-sm font-medium text-left">{{ formatDate(message.created_at) }}</td>
                <td class="py-2 px-4 text-sm font-medium text-center">{{ message.subject }}</td>
                <td class="py-2 px-4 text-sm font-medium text-center">
                  <span
                    :class="{
                      'bg-yellow-100 text-yellow-800': !message.is_read,
                      'bg-green-100 text-green-800': message.is_read
                    }"
                    class="px-2 py-1 rounded-full text-xs"
                  >
                    {{ message.is_read ? 'Lu' : 'Non lu' }}
                  </span>
                </td>
                <td class="py-2 px-4 text-sm font-medium text-center" @click.stop>
                  <button
                    @click="openReplyModal(message)"
                    class="p-1 rounded-md hover:bg-[#D1FAE5] transition-all"
                  >
                    <img src="@/Assets/IMG/mail.png" alt="Reply" class="w-5 h-5">
                  </button>
                </td>
              </tr>
              <tr v-if="receivedMessages.data.length === 0">
                <td colspan="4" class="py-4 text-center text-gray-500">
                  Aucun message reçu
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-center">
          <nav class="flex items-center space-x-1 sm:space-x-2">
            <button
              @click="prevPage('received')"
              :disabled="receivedMessages.current_page === 1"
              class="px-2 sm:px-3 py-1 rounded-md bg-gray-100 text-gray-700 hover:bg-[#D1FAE5] disabled:opacity-50"
            >
              &lt;
            </button>
            <button
              v-for="page in receivedMessages.last_page > 5 ? 
                (receivedMessages.current_page <= 3 ? 
                  range(1, Math.min(5, receivedMessages.last_page)) : 
                  range(Math.max(1, receivedMessages.current_page - 2), Math.min(receivedMessages.current_page + 2, receivedMessages.last_page))) : 
                range(1, receivedMessages.last_page)"
              :key="page"
              @click="goToPage('received', page)"
              class="px-2 sm:px-3 py-1 rounded-md"
              :class="{
                'bg-[#10B981] text-white': receivedMessages.current_page === page,
                'bg-gray-100 text-gray-700 hover:bg-[#D1FAE5]': receivedMessages.current_page !== page
              }"
            >
              {{ page }}
            </button>
            <button
              @click="nextPage('received')"
              :disabled="receivedMessages.current_page === receivedMessages.last_page"
              class="px-2 sm:px-3 py-1 rounded-md bg-gray-100 text-gray-700 hover:bg-[#D1FAE5] disabled:opacity-50"
            >
              &gt;
            </button>
          </nav>
        </div>
      </div>

      <!-- Card layout for small screens -->
      <div class="md:hidden space-y-4">
        <div
          v-for="message in receivedMessages.data"
          :key="message.id"
          :class="{
            'bg-[#F5F5F5]': !message.is_read,
            'bg-white': message.is_read
          }"
          class="rounded-lg shadow-sm border border-gray-200 p-4 cursor-pointer"
          @click="showMessageDetails(message)"
        >
          <div class="flex justify-between items-start mb-2">
            <div>
              <h3 class="font-medium text-[#1E293B]">{{ message.subject }}</h3>
              <p class="text-xs text-gray-500">{{ formatDate(message.created_at) }}</p>
            </div>
            <span
              :class="{
                'bg-yellow-100 text-yellow-800': !message.is_read,
                'bg-green-100 text-green-800': message.is_read
              }"
              class="px-2 py-1 rounded-full text-xs"
            >
              {{ message.is_read ? 'Lu' : 'Non lu' }}
            </span>
          </div>
          <div class="flex justify-end pt-2 border-t border-[#D1FAE5]" @click.stop>
            <button
              @click="openReplyModal(message)"
              class="p-2 rounded-full bg-[#D1FAE5] hover:bg-[#A7F3D0] transition-all"
            >
              <img src="@/Assets/IMG/mail.png" alt="Reply" class="w-4 h-4">
            </button>
          </div>
        </div>
        <div v-if="receivedMessages.data.length === 0" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 text-center text-gray-500">
          Aucun message reçu
        </div>
      </div>
    </div>

    <!-- Contacter Support Section -->
    <div v-show="activeTab === 'support'" class="bg-white rounded-xl shadow-sm p-4 md:p-6">
      <h2 class="text-lg md:text-xl font-bold text-[#1E293B] mb-3 md:mb-4">Contacter Support</h2>
      <hr class="border-t-2 border-gray-200 mb-4 md:mb-6">
      <form @submit.prevent="submitSupportRequest" class="max-w-2xl mx-auto">
        <!-- Subject -->
        <div class="mb-6">
          <label for="support-subject" class="block text-sm font-medium text-gray-700 mb-2">Objet:</label>
          <input
            v-model="supportForm.subject"
            type="text"
            id="support-subject"
            class="block w-full border-2 border-[#D1FAE5] rounded-lg p-2 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent"
            placeholder="Objet de votre message"
            required
          >
        </div>
        <!-- Message -->
        <div class="mb-6">
          <label for="support-message" class="block text-sm font-medium text-gray-700 mb-2">Message:</label>
          <textarea
            v-model="supportForm.message"
            id="support-message"
            rows="6"
            class="block w-full border-2 border-[#D1FAE5] rounded-lg p-2 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent resize-none"
            placeholder="Décrivez votre problème en détail"
            required
          ></textarea>
        </div>
        <!-- Submit Button -->
        <div class="text-center">
          <button
            type="submit"
            class="inline-block rounded-xl py-2 px-8 bg-[#86EFAC] hover:bg-[#4ADE80] border-[#86EFAC] border-2 hover:border-black text-black font-semibold text-lg transition-all w-full sm:w-auto"
            :disabled="supportForm.processing"
          >
            <span v-if="supportForm.processing">Envoi en cours...</span>
            <span v-else>Envoyer</span>
          </button>
        </div>
      </form>
    </div>

    <!-- Messages Envoyés Section -->
    <div v-show="activeTab === 'sent'" class="bg-white rounded-xl shadow-sm p-4 md:p-6">
      <h2 class="text-lg md:text-xl font-bold text-[#1E293B] mb-3 md:mb-4">Messages Envoyés</h2>
      <hr class="border-t-2 border-gray-200 mb-4 md:mb-6">

      <!-- Table for medium and up screens -->
      <div class="hidden md:block overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full">
            <thead>
              <tr class="text-left text-sm font-medium text-gray-700 border-b-[0.5px] border-b-[#5AE4A8]">
                <th class="py-2 px-4 text-left">Date</th>
                <th class="py-2 px-4 text-center">Objet</th>
                <th class="py-2 px-4 text-center">Statut</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="message in sentMessages.data"
                :key="message.id"
                class="border-b-[0.5px] border-b-[#5AE4A8] bg-white cursor-pointer"
                @click="showSentMessageDetails(message)"
              >
                <td class="py-3 px-4 text-sm font-medium text-left">{{ formatDate(message.created_at) }}</td>
                <td class="py-3 px-4 text-sm font-medium text-center">{{ message.subject }}</td>
                <td class="py-3 px-4 text-sm font-medium text-center">
                  <span
                    :class="{
                      'bg-yellow-100 text-yellow-800': !message.is_read,
                      'bg-green-100 text-green-800': message.is_read
                    }"
                    class="px-2 py-1 rounded-full text-xs"
                  >
                    {{ message.is_read ? 'Lu' : 'Non lu' }}
                  </span>
                </td>
              </tr>
              <tr v-if="sentMessages.data.length === 0">
                <td colspan="3" class="py-4 text-center text-gray-500">
                  Aucun message envoyé
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-center">
          <nav class="flex items-center space-x-1 sm:space-x-2">
            <button
              @click="prevPage('sent')"
              :disabled="sentMessages.current_page === 1"
              class="px-2 sm:px-3 py-1 rounded-md bg-gray-100 text-gray-700 hover:bg-[#D1FAE5] disabled:opacity-50"
            >
              &lt;
            </button>
            <button
              v-for="page in sentMessages.last_page > 5 ? 
                (sentMessages.current_page <= 3 ? 
                  range(1, Math.min(5, sentMessages.last_page)) : 
                  range(Math.max(1, sentMessages.current_page - 2), Math.min(sentMessages.current_page + 2, sentMessages.last_page))) : 
                range(1, sentMessages.last_page)"
              :key="page"
              @click="goToPage('sent', page)"
              class="px-2 sm:px-3 py-1 rounded-md"
              :class="{
                'bg-[#10B981] text-white': sentMessages.current_page === page,
                'bg-gray-100 text-gray-700 hover:bg-[#D1FAE5]': sentMessages.current_page !== page
              }"
            >
              {{ page }}
            </button>
            <button
              @click="nextPage('sent')"
              :disabled="sentMessages.current_page === sentMessages.last_page"
              class="px-2 sm:px-3 py-1 rounded-md bg-gray-100 text-gray-700 hover:bg-[#D1FAE5] disabled:opacity-50"
            >
              &gt;
            </button>
          </nav>
        </div>
      </div>

      <!-- Card layout for small screens -->
      <div class="md:hidden space-y-4">
        <div
          v-for="message in sentMessages.data"
          :key="message.id"
          class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 cursor-pointer"
          @click="showSentMessageDetails(message)"
        >
          <div class="flex justify-between items-start mb-2">
            <div>
              <h3 class="font-medium text-[#1E293B]">{{ message.subject }}</h3>
              <p class="text-xs text-gray-500">{{ formatDate(message.created_at) }}</p>
            </div>
            <span
              :class="{
                'bg-yellow-100 text-yellow-800': !message.is_read,
                'bg-green-100 text-green-800': message.is_read
              }"
              class="px-2 py-1 rounded-full text-xs"
            >
              {{ message.is_read ? 'Lu' : 'Non lu' }}
            </span>
          </div>
        </div>
        <div v-if="sentMessages.data.length === 0" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 text-center text-gray-500">
          Aucun message envoyé
        </div>
      </div>
    </div>
  </DashLayout>
</template>

<script setup>
import DashLayout from '@/Layouts/DashLayout.vue';
import { ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';


const props = defineProps({
  receivedMessages: Object,
  sentMessages: Object
})

const activeTab = ref('received')

const refreshTab = (tab) => {
  activeTab.value = tab
  // Refresh data for the current tab only
  router.reload({
    only: ['receivedMessages', 'sentMessages'],
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => {
      // Force scroll to top after refresh if needed
      window.scrollTo(0, 0)
    }
  })
}
const showDetailsModal = ref(false);
const showReplyModal = ref(false);
const selectedMessage = ref(null);

const replyForm = useForm({
  subject: '',
  message: '',
  original_message_id: null
});

const supportForm = useForm({
  subject: '',
  message: ''
});

const range = (start, end) => {
  return Array.from({ length: end - start + 1 }, (_, i) => start + i);
};

const formatDate = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  const day = date.getDate().toString().padStart(2, '0');
  const month = (date.getMonth() + 1).toString().padStart(2, '0');
  const year = date.getFullYear();
  return `${day}-${month}-${year}`;
};

const showMessageDetails = (message) => {
  selectedMessage.value = message;
  showDetailsModal.value = true;
  
};

const showSentMessageDetails = (message) => {
  selectedMessage.value = message;
  showDetailsModal.value = true;
};

const closeDetailsModal = () => {
  showDetailsModal.value = false;
};

const openReplyModal = (message) => {
  selectedMessage.value = message;
  replyForm.subject = `Re: ${message.subject}`;
  replyForm.original_message_id = message.id;
  replyForm.message = '';
  showReplyModal.value = true;
};

const closeReplyModal = () => {
  showReplyModal.value = false;
};

const sendReply = () => {
  replyForm.post(route('messages.reply'), {
    preserveScroll: true,
    onSuccess: () => {
      closeReplyModal();
    }
  });
};

const submitSupportRequest = () => {
  supportForm.post(route('messages.support'), {
    preserveScroll: true,
    onSuccess: () => {
      supportForm.reset();
      activeTab.value = 'sent';
    }
  });
};


const prevPage = (type) => {
  const currentPage = props[`${type}Messages`].current_page;
  if (currentPage > 1) {
    router.get(route('contact'), { [type === 'received' ? 'received_page' : 'sent_page']: currentPage - 1 }, {
      preserveState: true,
      replace: true
    });
  }
};

const nextPage = (type) => {
  const currentPage = props[`${type}Messages`].current_page;
  const lastPage = props[`${type}Messages`].last_page;
  if (currentPage < lastPage) {
    router.get(route('contact'), { [type === 'received' ? 'received_page' : 'sent_page']: currentPage + 1 }, {
      preserveState: true,
      replace: true
    });
  }
};

const goToPage = (type, page) => {
  router.get(route('contact'), { [type === 'received' ? 'received_page' : 'sent_page']: page }, {
    preserveState: true,
    replace: true
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