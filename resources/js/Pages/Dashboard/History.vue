<template>
  <DashLayout>
    <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
        <h2 class="text-xl font-bold text-[#1E293B] mb-3 sm:mb-0">Historique</h2>
        
        <div class="relative w-full sm:w-auto">
          <select
            v-model="activeFilter"
            @change="applyFilter"
            class="filter-button flex items-center space-x-2 px-3 py-2 w-full sm:w-auto bg-[#D1FAE5] rounded-lg hover:bg-[#A7F3D0] transition-all justify-center sm:justify-start appearance-none"
            :disabled="loading"
          >
            <option v-for="filter in filters" :key="filter.value" :value="filter.value">
              {{ filter.label }}
            </option>
          </select>
          <span v-if="loading" class="absolute right-3 top-2.5">
            <svg class="animate-spin h-4 w-4 text-[#10B981]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
          </span>
        </div>
      </div>

      <hr class="border-t-2 border-gray-200 mb-6">

      <!-- Table for medium and up screens -->
      <div class="hidden md:block overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full">
            <thead>
              <tr class="text-left text-sm font-medium text-gray-700 border-b-[0.5px] border-b-[#5AE4A8]">
                <th class="py-2 px-4 w-1/4 text-left">Nom</th>
                <th class="py-2 px-4 w-1/6 text-center">Date</th>
                <th class="py-2 px-4 w-1/6 text-center">Montant</th>
                <th class="py-2 px-4 w-1/6 text-center">Type</th>
                <th class="py-2 px-4 w-1/6 text-center">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-[#D1FAE5]">
              <tr
                v-for="transaction in displayedTransactions"
                :key="transaction.id"
                class="border-b-[0.5px] border-b-[#5AE4A8] hover:bg-gray-50 cursor-pointer"
                @click="showTransactionDetails(transaction)"
              >
                <td class="py-3 px-4 text-sm font-medium text-left">{{ transaction.name }}</td>
                <td class="py-3 px-4 text-sm font-medium text-center">{{ formatDate(transaction.date) }}</td>
                <td class="py-3 px-4 text-sm font-medium text-center">{{ formatCurrency(transaction.amount) }}</td>
                <td class="py-3 px-4 text-sm font-medium text-center">
                  <span
                    class="px-2 py-1 rounded-full text-xs"
                    :class="typeClasses(transaction.type)"
                  >
                    {{ transaction.type_label }}
                  </span>
                </td>
                <td class="py-3 px-4 text-sm font-medium text-center">
                  <div class="flex justify-center space-x-2">
                    <button
                      @click.stop="editTransaction(transaction)"
                      class="p-2 rounded-md hover:bg-[#D1FAE5] transition-all"
                    >
                      <img src="@/Assets/IMG/edit.png" alt="Edit" class="w-5 h-5">
                    </button>
                    <button
                      @click.stop="confirmDelete(transaction)"
                      class="p-2 rounded-md hover:bg-red-100 transition-all"
                    >
                      <img src="@/Assets/IMG/delete.png" alt="Delete" class="w-5 h-5">
                    </button>
                  </div>
                </td>
              </tr>
              <tr v-if="displayedTransactions.length === 0">
                <td colspan="5" class="py-4 text-center text-gray-500">
                  Aucune transaction trouvée
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Card layout for small screens -->
      <div class="md:hidden space-y-4">
        <div
          v-for="transaction in displayedTransactions"
          :key="transaction.id"
          class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 hover:bg-gray-50 cursor-pointer"
          @click="showTransactionDetails(transaction)"
        >
          <div class="flex justify-between items-start mb-2">
            <div>
              <h3 class="font-medium text-[#1E293B]">{{ transaction.name }}</h3>
              <p class="text-xs text-gray-500">{{ formatDate(transaction.date) }}</p>
            </div>
            <span
              class="px-2 py-1 rounded-full text-xs"
              :class="typeClasses(transaction.type)"
            >
              {{ transaction.type_label }}
            </span>
          </div>
          <div class="flex justify-between items-center mt-3">
            <p class="font-bold ">{{ formatCurrency(transaction.amount) }}</p>
            <div class="flex space-x-2">
              <button
                @click.stop="editTransaction(transaction)"
                class="p-2 rounded-md bg-[#D1FAE5] transition-all"
              >
                <img src="@/Assets/IMG/edit.png" alt="Edit" class="w-4 h-4">
              </button>
              <button
                @click.stop="confirmDelete(transaction)"
                class="p-2 rounded-md bg-red-50 transition-all"
              >
                <img src="@/Assets/IMG/delete.png" alt="Delete" class="w-4 h-4">
              </button>
            </div>
          </div>
        </div>
        <div
          v-if="displayedTransactions.length === 0"
          class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 text-center text-gray-500"
        >
          Aucune transaction trouvée
        </div>
      </div>

      <!-- Pagination -->
      <div class="mt-6 flex justify-center">
        <nav class="flex items-center space-x-1 sm:space-x-2">
          <button
            @click="previousPage"
            :disabled="currentPage === 1 || loading"
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
            :disabled="loading"
          >
            {{ page }}
          </button>
          <button
            @click="nextPage"
            :disabled="currentPage === totalPages || loading"
            class="px-2 sm:px-3 py-1 rounded-md bg-gray-100 text-gray-700 hover:bg-[#D1FAE5] disabled:opacity-50"
          >
            &gt;
          </button>
        </nav>
      </div>
    </div>

    <!-- Transaction Details Modal -->
    <div v-if="showDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" @click.self="closeDetailsModal">
      <div class="bg-white rounded-xl shadow-lg max-w-md w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-[#1E293B]">Détails de la transaction</h3>
            <button @click="closeDetailsModal" class="text-gray-500 hover:text-gray-700">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          <div class="space-y-4">
            <div>
              <p class="text-sm text-gray-500">Nom</p>
              <p class="font-medium">{{ selectedTransaction.name }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Montant</p>
              <p class="font-medium">{{ formatCurrency(selectedTransaction.amount) }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Date</p>
              <p class="font-medium">{{ formatDate(selectedTransaction.date) }}</p>
            </div>
            <div v-if="selectedTransaction.type === 'expense'">
              <p class="text-sm text-gray-500">Catégorie</p>
              <p class="font-medium">{{ selectedTransaction.category }}</p>
            </div>
            <div v-if="selectedTransaction.description">
              <p class="text-sm text-gray-500">Description</p>
              <p class="font-medium">{{ selectedTransaction.description }}</p>
            </div>
            <div v-else>
              <p class="text-sm text-gray-500">Description</p>
              <p class="font-medium text-gray-400">Aucune description</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Type</p>
              <p class="font-medium">
                <span
                  class="px-2 py-1 rounded-full text-xs"
                  :class="typeClasses(selectedTransaction.type)"
                >
                  {{ selectedTransaction.type_label }}
                </span>
              </p>
            </div>
          </div>
          <div class="mt-6 flex justify-end space-x-3">
            <button
              @click="editTransaction(selectedTransaction)"
              class="px-4 py-2 bg-[#D1FAE5] hover:bg-[#A7F3D0] text-[#1E293B] rounded-lg"
            >
              Modifier
            </button>
            <button
              @click="confirmDelete(selectedTransaction)"
              class="px-4 py-2 bg-red-100 hover:bg-red-200 text-red-800 rounded-lg"
            >
              Supprimer
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit Transaction Modal -->
    <div v-if="showEditModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" @click.self="closeEditModal">
      <div class="bg-white rounded-xl shadow-lg max-w-md w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-[#1E293B]">Modifier la transaction</h3>
            <button @click="closeEditModal" class="text-gray-500 hover:text-gray-700">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <!-- Revenu/Saving Form -->
          <form v-if="selectedTransaction && selectedTransaction.type !== 'expense'" @submit.prevent="submitEditForm">
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom*</label>
                <input
                  v-model="editForm.name"
                  type="text"
                  class="w-full border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent"
                  required
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Montant*</label>
                <input
                  v-model="editForm.amount"
                  type="number"
                  min="0"
                  step="0.01"
                  class="w-full border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent"
                  required
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date*</label>
                <input
                  v-model="editForm.date"
                  type="date"
                  class="w-full border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent"
                  required
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea
                  v-model="editForm.description"
                  class="w-full border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent h-24 resize-none"
                ></textarea>
              </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
              <button
                type="button"
                @click="closeEditModal"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg"
              >
                Annuler
              </button>
              <button
                type="submit"
                class="px-4 py-2 bg-[#10B981] hover:bg-[#059669] text-white rounded-lg"
                :disabled="editForm.processing"
              >
                <span v-if="editForm.processing">Enregistrement...</span>
                <span v-else>Enregistrer</span>
              </button>
            </div>
          </form>

          <!-- Expense Form -->
          <form v-if="selectedTransaction && selectedTransaction.type === 'expense'" @submit.prevent="submitEditForm">
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom*</label>
                <input
                  v-model="editForm.name"
                  type="text"
                  class="w-full border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent"
                  required
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie*</label>
                <select
                  v-model="editForm.category_id"
                  class="w-full border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent"
                  required
                >
                  <option 
                    v-for="category in categories" 
                    :key="category.id" 
                    :value="category.id"
                    :selected="category.id === editForm.category_id"
                  >
                    {{ category.name }}
                  </option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Montant*</label>
                <input
                  v-model="editForm.amount"
                  type="number"
                  min="0"
                  step="0.01"
                  class="w-full border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent"
                  required
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date*</label>
                <input
                  v-model="editForm.date"
                  type="date"
                  class="w-full border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent"
                  required
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea
                  v-model="editForm.description"
                  class="w-full border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent h-24 resize-none"
                ></textarea>
              </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
              <button
                type="button"
                @click="closeEditModal"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg"
              >
                Annuler
              </button>
              <button
                type="submit"
                class="px-4 py-2 bg-[#10B981] hover:bg-[#059669] text-white rounded-lg"
                :disabled="editForm.processing"
              >
                <span v-if="editForm.processing">Enregistrement...</span>
                <span v-else>Enregistrer</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <ConfirmationModal :show="showDeleteModal" @close="showDeleteModal = false">
      <template #title>
        Supprimer la transaction
      </template>
      <template #content>
        Êtes-vous sûr de vouloir supprimer cette transaction? Cette action est irréversible.
      </template>
      <template #footer>
        <button
          @click="showDeleteModal = false"
          class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg mr-2"
        >
          Annuler
        </button>
        <button
          @click="deleteTransaction"
          class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg"
          :class="{ 'opacity-50': deleting }"
          :disabled="deleting"
        >
          <span v-if="deleting">Suppression...</span>
          <span v-else>Supprimer</span>
        </button>
      </template>
    </ConfirmationModal>
  </DashLayout>
</template>

<script setup>
import { Link, useForm, router } from '@inertiajs/vue3';
import { ref, computed, onMounted, watch } from 'vue';
import DashLayout from '@/Layouts/DashLayout.vue';
import ConfirmationModal from '@/Components/Dashboard/ConfirmationModal.vue';

const props = defineProps({
  transactions: Array,
  initialType: {
    type: String,
    default: 'all'
  },
  categories: Array,
  filters: Object
});

const activeFilter = ref(props.initialType);
const currentPage = ref(1);
const itemsPerPage = 10;
const showDeleteModal = ref(false);
const showDetailsModal = ref(false);
const showEditModal = ref(false);
const deleting = ref(false);
const loading = ref(false);
const transactionToDelete = ref(null);
const selectedTransaction = ref(null);

const filters = [
  { value: 'all', label: 'Tous' },
  { value: 'revenu', label: 'Revenu' },
  { value: 'expense', label: 'Dépense' },
  { value: 'saving', label: 'Épargne' }
];

const editForm = useForm({
  id: null,
  name: '',
  amount: '',
  date: '',
  description: '',
  category_id: null,
  type: ''
});

const displayedTransactions = computed(() => {
  let filtered = [...props.transactions];
  
  if (activeFilter.value !== 'all') {
    filtered = filtered.filter(t => t.type === activeFilter.value);
  }
  
  const start = (currentPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  return filtered.slice(start, end);
});

const totalPages = computed(() => {
  const filteredCount = activeFilter.value === 'all'
    ? props.transactions.length
    : props.transactions.filter(t => t.type === activeFilter.value).length;
  return Math.ceil(filteredCount / itemsPerPage);
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

const applyFilter = () => {
  loading.value = true;
  router.get(route('history'), { type: activeFilter.value }, {
    preserveState: true,
    replace: true,
    onFinish: () => {
      loading.value = false;
    }
  });
};

const typeClasses = (type) => {
  return {
    'bg-blue-100 text-blue-800': type === 'revenu',
    'bg-red-100 text-red-800': type === 'expense',
    'bg-green-100 text-green-800': type === 'saving'
  };
};

const formatDate = (dateString) => {
  const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
  return new Date(dateString).toLocaleDateString('fr-FR', options);
};

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('fr-FR', {
    style: 'currency',
    currency: 'MAD',
    minimumFractionDigits: 2
  }).format(amount);
};

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
  currentPage.value = page;
};

const showTransactionDetails = (transaction) => {
  selectedTransaction.value = transaction;
  showDetailsModal.value = true;
};

const closeDetailsModal = () => {
  showDetailsModal.value = false;
};

const editTransaction = (transaction) => {
  selectedTransaction.value = transaction;
  
  editForm.id = transaction.id;
  editForm.name = transaction.name;
  editForm.amount = transaction.amount;
  editForm.date = transaction.date.split('T')[0];
  editForm.description = transaction.description || '';
  editForm.type = transaction.type;
  
  if (transaction.type === 'expense') {
    editForm.category_id = transaction.category_id;
  }
  
  showDetailsModal.value = false;
  showEditModal.value = true;
};

const closeEditModal = () => {
  showEditModal.value = false;
};

const submitEditForm = () => {
  let routeName;
  
  switch (editForm.type) {
    case 'revenu':
      routeName = 'budgets.update';
      break;
    case 'expense':
      routeName = 'expenses.update';
      break;
    case 'saving':
      routeName = 'savings.update';
      break;
    default:
      return;
  }
  
  editForm.put(route(routeName, editForm.id), {
    preserveScroll: true,
    onSuccess: () => {
      closeEditModal();
      router.reload({ only: ['transactions'] });
    }
  });
};

const confirmDelete = (transaction) => {
  transactionToDelete.value = transaction;
  showDeleteModal.value = true;
  showDetailsModal.value = false;
};

const deleteTransaction = () => {
  if (!transactionToDelete.value) return;
  
  deleting.value = true;
  
  let routeName;
  switch (transactionToDelete.value.type) {
    case 'revenu':
      routeName = 'budgets.destroy';
      break;
    case 'expense':
      routeName = 'expenses.destroy';
      break;
    case 'saving':
      routeName = 'savings.destroy';
      break;
    default:
      return;
  }
  
  router.delete(route(routeName, { id: transactionToDelete.value.id }), {
    preserveScroll: true,
    onSuccess: () => {
      showDeleteModal.value = false;
      transactionToDelete.value = null;
      router.reload({ only: ['transactions'] });
    },
    onFinish: () => {
      deleting.value = false;
    }
  });
};

onMounted(() => {
  if (props.initialType) {
    activeFilter.value = props.initialType;
  }
});
</script>