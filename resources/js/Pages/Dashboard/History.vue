<template>
    <DashLayout>
      <!-- History Section -->
      <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
          <h2 class="text-xl font-bold text-[#1E293B] mb-3 sm:mb-0">Historique</h2>
          
          <!-- Filter Container -->
          <div class="relative w-full sm:w-auto">
            <button 
              @click="toggleFilterDropdown"
              class="filter-button flex items-center space-x-2 px-3 py-2 w-full sm:w-auto bg-[#D1FAE5] rounded-lg hover:bg-[#A7F3D0] transition-all justify-center sm:justify-start"
            >
              <img src="@/Assets/IMG/filter.png" alt="Filter" class="w-5 h-5">
              <span class="text-sm font-medium">Filtrer</span>
              <span v-if="activeFilter" class="ml-1 text-xs bg-[#10B981] text-white px-2 py-1 rounded-full">
                {{ activeFilterLabel }}
              </span>
            </button>
            
            <!-- Filter Dropdown -->
            <div 
              v-show="filterDropdownOpen"
              class="filter-dropdown absolute right-0 mt-2 bg-white shadow-md rounded-lg p-2 w-full sm:w-48 border border-[#D1FAE5] z-20"
            >
              <div class="py-1">
                <button 
                  v-for="filter in filters"
                  :key="filter.value"
                  @click="applyFilter(filter.value)"
                  class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-[#D1FAE5] rounded-md"
                  :class="{ 'bg-[#D1FAE5]': activeFilter === filter.value }"
                >
                  {{ filter.label }}
                </button>
              </div>
            </div>
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
                  v-for="transaction in filteredTransactions" 
                  :key="transaction.id" 
                  class="border-b-[0.5px] border-b-[#5AE4A8]"
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
                        @click="editTransaction(transaction)"
                        class="p-2 rounded-md hover:bg-[#D1FAE5] transition-all"
                      >
                        <img src="@/Assets/IMG/edit.png" alt="Edit" class="w-5 h-5">
                      </button>
                      <button 
                        @click="confirmDelete(transaction)"
                        class="p-2 rounded-md hover:bg-red-100 transition-all"
                      >
                        <img src="@/Assets/IMG/delete.png" alt="Delete" class="w-5 h-5">
                      </button>
                    </div>
                  </td>
                </tr>
                <tr v-if="filteredTransactions.length === 0">
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
            v-for="transaction in filteredTransactions"
            :key="transaction.id"
            class="bg-white rounded-lg shadow-sm border border-gray-100 p-4"
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
              <p class="font-bold text-[#10B981]">{{ formatCurrency(transaction.amount) }}</p>
              <div class="flex space-x-2">
                <button 
                  @click="editTransaction(transaction)"
                  class="p-2 rounded-md bg-[#D1FAE5] transition-all"
                >
                  <img src="@/Assets/IMG/edit.png" alt="Edit" class="w-4 h-4">
                </button>
                <button 
                  @click="confirmDelete(transaction)"
                  class="p-2 rounded-md bg-red-50 transition-all"
                >
                  <img src="@/Assets/IMG/delete.png" alt="Delete" class="w-4 h-4">
                </button>
              </div>
            </div>
          </div>
          <div 
            v-if="filteredTransactions.length === 0"
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
  import { Link } from '@inertiajs/vue3';
  import { ref, computed, onMounted } from 'vue';
  import DashLayout from '@/Layouts/DashLayout.vue';
  import ConfirmationModal from '@/Components/ConfirmationModal.vue';
  
  const props = defineProps({
    transactions: Array,
    filters: Object
  });
  
  const filterDropdownOpen = ref(false);
  const activeFilter = ref('all');
  const currentPage = ref(1);
  const itemsPerPage = 10;
  const showDeleteModal = ref(false);
  const deleting = ref(false);
  const transactionToDelete = ref(null);
  
  const filters = computed(() => [
    { value: 'all', label: 'Tous' },
    { value: 'budget', label: 'Budget' },
    { value: 'expense', label: 'Dépense' },
    { value: 'saving', label: 'Épargne' }
  ]);
  
  const activeFilterLabel = computed(() => {
    const filter = filters.value.find(f => f.value === activeFilter.value);
    return filter ? filter.label : '';
  });
  
  const filteredTransactions = computed(() => {
    let filtered = [...props.transactions];
    
    // Apply type filter
    if (activeFilter.value !== 'all') {
      filtered = filtered.filter(t => t.type === activeFilter.value);
    }
    
    // Apply pagination
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
  
  const toggleFilterDropdown = () => {
    filterDropdownOpen.value = !filterDropdownOpen.value;
  };
  
  const applyFilter = (filter) => {
    activeFilter.value = filter;
    currentPage.value = 1;
    filterDropdownOpen.value = false;
  };
  
  const typeClasses = (type) => {
    return {
      'bg-blue-100 text-blue-800': type === 'budget',
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
  
  const editTransaction = (transaction) => {
    // Determine the appropriate edit route based on transaction type
    let routeName;
    switch (transaction.type) {
      case 'budget':
        routeName = 'budgets.edit';
        break;
      case 'expense':
        routeName = 'expenses.edit';
        break;
      case 'saving':
        routeName = 'savings.edit';
        break;
      default:
        return;
    }
    
    Inertia.visit(route(routeName, { id: transaction.id }));
  };
  
  const confirmDelete = (transaction) => {
    transactionToDelete.value = transaction;
    showDeleteModal.value = true;
  };
  
  const deleteTransaction = () => {
    if (!transactionToDelete.value) return;
    
    deleting.value = true;
    
    // Determine the appropriate delete route based on transaction type
    let routeName;
    switch (transactionToDelete.value.type) {
      case 'budget':
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
    
    Inertia.delete(route(routeName, { id: transactionToDelete.value.id }), {
      preserveScroll: true,
      onSuccess: () => {
        showDeleteModal.value = false;
        transactionToDelete.value = null;
      },
      onFinish: () => {
        deleting.value = false;
      }
    });
  };
  
  // Close dropdown when clicking outside
  const handleClickOutside = (event) => {
    if (!event.target.closest('.filter-button') && !event.target.closest('.filter-dropdown')) {
      filterDropdownOpen.value = false;
    }
  };
  
  onMounted(() => {
    window.addEventListener('click', handleClickOutside);
  });
  
  onUnmounted(() => {
    window.removeEventListener('click', handleClickOutside);
  });
  </script>