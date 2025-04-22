<template>
  <AdminLayout>
    <h2 class="text-xl md:text-2xl font-bold text-secondary mb-6">Gestion des Clients</h2>
    
    <div class="mb-6">
      <div class="relative max-w-md">
        <input 
          type="text" 
          placeholder="Rechercher par email..."
          v-model="searchQuery"
          class="block w-full border-2 border-gray-200 rounded-lg p-2 pl-10 bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
        >
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <img src="@/Assets/IMG/search.png" alt="Search" class="w-5 h-5 text-gray-400">
        </div>
      </div>
    </div>

    <!-- Desktop Table -->
    <div class="hidden md:block bg-white rounded-xl shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full">
          <thead>
            <tr class="text-left text-sm font-medium text-gray-700 border-b border-gray-200">
              <th class="py-3 px-3 w-[10%] text-left">ID</th>
              <th class="py-3 px-3 w-[20%] text-left">Prénom</th>
              <th class="py-3 px-3 w-[20%] text-left">Nom</th>
              <th class="py-3 px-3 w-[25%] text-left lg:table-cell hidden">Email</th>
              <th class="py-3 px-3 w-[15%] text-center">Date</th>
              <th class="py-3 px-3 w-[10%] text-center">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr 
              v-for="client in paginatedClients" 
              :key="client.id"
              class="hover:bg-gray-50 cursor-pointer"
              @click="showUserInfo(client)"
            >
              <td class="py-3 px-3 text-sm text-left">#{{ client.id }}</td>
              <td class="py-3 px-3 text-sm text-left">{{ client.first_name }}</td>
              <td class="py-3 px-3 text-sm text-left">{{ client.last_name }}</td>
              <td class="py-3 px-3 text-sm text-left lg:table-cell hidden">{{ client.email }}</td>
              <td class="py-3 px-3 text-sm text-center">{{ formatDate(client.created_at) }}</td>
              <td class="py-3 px-3 text-sm text-center" @click.stop>
                <div class="flex justify-center gap-2">
                  <button 
                    @click="openMailModal(client)"
                    class="p-1 rounded-md hover:bg-[#D1FAE5] transition-all"
                  >
                    <img src="@/Assets/IMG/mail.png" alt="Mail" class="w-5 h-5">
                  </button>
                  <button 
                    @click="openDeleteModal(client)"
                    class="p-1 rounded-md hover:bg-red-100 transition-all"
                  >
                    <img src="@/Assets/IMG/delete.png" alt="Delete" class="w-5 h-5">
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>


    <!-- Mobile Cards -->
    <div class="md:hidden space-y-4">
      <div 
        v-for="client in paginatedClients" 
        :key="client.id"
        class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:bg-gray-50 cursor-pointer"
        @click="showUserInfo(client)"
      >
        <div class="flex justify-between items-start mb-2">
          <div>
            <h3 class="font-medium text-[#1E293B]">#{{ client.id }} {{ client.first_name }} {{ client.last_name }}</h3>
            <p class="text-xs text-gray-500">{{ client.email }}</p>
          </div>
        </div>
        <div class="flex justify-between items-center mt-3">
          <p class="text-sm text-gray-600">Date: {{ formatDate(client.created_at) }}</p>
          <div class="flex space-x-2" @click.stop>
            <button 
              @click="openMailModal(client)"
              class="p-2 rounded-md bg-[#D1FAE5] transition-all"
            >
              <img src="@/Assets/IMG/mail.png" alt="Mail" class="w-4 h-4">
            </button>
            <button 
              @click="openDeleteModal(client)"
              class="p-2 rounded-md bg-red-50 transition-all"
            >
              <img src="@/Assets/IMG/delete.png" alt="Delete" class="w-4 h-4">
            </button>
          </div>
        </div>
      </div>
      <div v-if="paginatedClients.length === 0" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 text-center text-gray-500">
        Aucun client trouvé
      </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-center">
      <nav class="flex items-center space-x-1 sm:space-x-2">
        <button
          @click="prevPage"
          :disabled="pagination.currentPage === 1"
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
            'bg-[#10B981] text-white': pagination.currentPage === page,
            'bg-gray-100 text-gray-700 hover:bg-[#D1FAE5]': pagination.currentPage !== page
          }"
        >
          {{ page }}
        </button>
        <button
          @click="nextPage"
          :disabled="pagination.currentPage === pagination.lastPage"
          class="px-2 sm:px-3 py-1 rounded-md bg-gray-100 text-gray-700 hover:bg-[#D1FAE5] disabled:opacity-50"
        >
          &gt;
        </button>
      </nav>
    </div>

    <!-- User Info Modal -->
    <Transition name="fade">
      <div v-if="showUserModal" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="closeUserModal"></div>
        
        <div class="bg-white rounded-xl shadow-lg max-w-md w-full max-h-[90vh] overflow-y-auto transform transition-all">
          <div class="p-6">
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-xl font-bold text-[#1E293B]">Informations du client</h3>
              <button @click="closeUserModal" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <div class="space-y-4">
              <div>
                <p class="text-sm text-gray-500">ID</p>
                <p class="font-medium">{{ selectedUser.id }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Prénom</p>
                <p class="font-medium">{{ selectedUser.first_name }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Nom</p>
                <p class="font-medium">{{ selectedUser.last_name }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Email</p>
                <p class="font-medium">{{ selectedUser.email }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Date de création</p>
                <p class="font-medium">{{ formatDate(selectedUser.created_at) }}</p>
              </div>
              <div v-if="selectedUser.savings_rate">
                <p class="text-sm text-gray-500">Taux d'épargne</p>
                <p class="font-medium">{{ selectedUser.savings_rate }}%</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Mail Modal -->
    <Transition name="fade">
      <div v-if="showMailModal" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="closeMailModal"></div>
        
        <div class="bg-white rounded-xl shadow-lg max-w-md w-full max-h-[90vh] overflow-y-auto transform transition-all">
          <div class="p-6">
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-xl font-bold text-[#1E293B]">Envoyer un message</h3>
              <button @click="closeMailModal" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <form @submit.prevent="sendMail">
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Destinataire</label>
                  <p class="font-medium">{{ selectedUser.first_name }} {{ selectedUser.last_name }} ({{ selectedUser.email }})</p>
                </div>
                <div>
                  <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Objet*</label>
                  <input
                    v-model="mailData.subject"
                    type="text"
                    id="subject"
                    class="w-full border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent"
                    required
                  >
                </div>
                <div>
                  <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message*</label>
                  <textarea
                    v-model="mailData.message"
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
                  @click="closeMailModal"
                  class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg"
                >
                  Annuler
                </button>
                <button
                  type="submit"
                  class="px-4 py-2 bg-[#10B981] hover:bg-[#059669] text-white rounded-lg"
                  :disabled="!mailData.subject || !mailData.message"
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
        Êtes-vous sûr de vouloir supprimer le client {{ selectedUser.first_name }} {{ selectedUser.last_name }} ? Cette action est irréversible.
      </template>
      <template #footer>
        <button
          @click="closeDeleteModal"
          class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg mr-2"
        >
          Annuler
        </button>
        <button
          @click="deleteUser"
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

const props = defineProps({
  clients: {
    type: Array,
    required: true
  },
  pagination: {
    type: Object,
    required: true
  }
});

const searchQuery = ref('');

// Modal states
const showUserModal = ref(false);
const showMailModal = ref(false);
const showDeleteModal = ref(false);
const selectedUser = ref({});
const mailData = ref({
  subject: '',
  message: ''
});

// Computed properties
const filteredClients = computed(() => {
  return props.clients.filter(client => 
    client.email.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    client.first_name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    client.last_name.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});

const paginatedClients = computed(() => {
  const start = (props.pagination.currentPage - 1) * props.pagination.perPage;
  const end = start + props.pagination.perPage;
  return filteredClients.value.slice(start, end);
});

const pages = computed(() => {
  const range = [];
  const total = props.pagination.lastPage;
  const current = props.pagination.currentPage;
  const delta = 2;
  
  let start = Math.max(2, current - delta);
  let end = Math.min(total - 1, current + delta);
  
  if (current - delta > 2) range.push('...');
  for (let i = start; i <= end; i++) range.push(i);
  if (current + delta < total - 1) range.push('...');
  
  return [1, ...range, total].filter((x, i, a) => a.indexOf(x) === i);
});

// Navigation
const nextPage = () => {
  if (props.pagination.currentPage < props.pagination.lastPage) {
    router.get(route('admin.clients'), { page: props.pagination.currentPage + 1 }, {
      preserveState: true,
      replace: true
    });
  }
};

const prevPage = () => {
  if (props.pagination.currentPage > 1) {
    router.get(route('admin.clients'), { page: props.pagination.currentPage - 1 }, {
      preserveState: true,
      replace: true
    });
  }
};

const goToPage = (page) => {
  if (page !== '...') {
    router.get(route('admin.clients'), { page }, {
      preserveState: true,
      replace: true
    });
  }
};

// Format date
const formatDate = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleDateString('fr-FR');
};

// Modal functions
const showUserInfo = (user) => {
  selectedUser.value = user;
  showUserModal.value = true;
};

const closeUserModal = () => {
  showUserModal.value = false;
};

const openMailModal = (user) => {
  selectedUser.value = user;
  mailData.value = { subject: '', message: '' };
  showMailModal.value = true;
};

const closeMailModal = () => {
  showMailModal.value = false;
};

const openDeleteModal = (user) => {
  selectedUser.value = user;
  showDeleteModal.value = true;
};

const closeDeleteModal = () => {
  showDeleteModal.value = false;
};

// Actions
const sendMail = () => {
  router.post(route('admin.send-message'), {
    user_id: selectedUser.value.id,
    subject: mailData.value.subject,
    message: mailData.value.message
  }, {
    onSuccess: () => {
      closeMailModal();
      // You might want to add a success notification here
    }
  });
};

const deleteUser = () => {
  router.delete(route('admin.clients.destroy', selectedUser.value.id), {
    onSuccess: () => {
      closeDeleteModal();
      // You might want to add a success notification here
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