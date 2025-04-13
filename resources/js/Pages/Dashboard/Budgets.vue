<template>
  <DashLayout>
    <!-- Confirmation Modal -->
    <ConfirmationModal
      :show="showDeleteModal"
      @close="showDeleteModal = false"
    >
      <template #title>
        Supprimer le budget mensuel
      </template>
      <template #content>
        Êtes-vous sûr de vouloir supprimer ce budget mensuel? Cette action est irréversible.
      </template>
      <template #footer>
        <button
          @click="showDeleteModal = false"
          class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg mr-2"
        >
          Annuler
        </button>
        <button
          @click="deleteMonthlyBudget"
          class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg"
        >
          Supprimer
        </button>
      </template>
    </ConfirmationModal>

    <!-- Edit Monthly Budget Modal -->
    <EditMonthlyBudgetModal
      :show="showEditModal"
      :budget="selectedBudget"
      @close="showEditModal = false"
      @saved="handleSaved"
    />

    <!-- Budget Details Modal -->
    <div v-if="showDetailsModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
      <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4" @click.stop>
        <div class="flex justify-between items-start mb-4">
          <h3 class="text-lg font-bold text-[#1E293B]">{{ selectedBudget?.name }}</h3>
          <button @click="showDetailsModal = false" class="text-gray-500 hover:text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        
        <div class="space-y-3">
          <div>
            <p class="text-sm text-gray-500">Montant:</p>
            <p class="font-medium">{{ formatCurrency(selectedBudget?.amount) }} DH</p>
          </div>
          <div>
            <p class="text-sm text-gray-500">Jour d'exécution:</p>
            <p class="font-medium">{{ selectedBudget?.day_of_month }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-500">Date de création:</p>
            <p class="font-medium">{{ formatDate(selectedBudget?.created_at) }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-500">Description:</p>
            <p class="font-medium">{{ selectedBudget?.description || 'Aucune description' }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6 mb-6">
      <div class="bg-white rounded-xl shadow-sm p-4 md:p-6 text-center">
        <p class="text-[#5AE4A8] text-sm font-bold">Balance Total</p>
        <p class="mt-2 text-lg md:text-xl font-semibold">{{ formatCurrency(totalBalance) }}</p>
      </div>
      <div class="bg-white rounded-xl shadow-sm p-4 md:p-6 text-center">
        <p class="text-[#5AE4A8] text-sm font-bold">Marge libre</p>
        <p class="mt-2 text-lg md:text-xl font-semibold">{{ formatCurrency(freeMargin) }}</p>
      </div>
      <div class="bg-white rounded-xl shadow-sm p-4 md:p-6 text-center">
        <p class="text-[#5AE4A8] text-sm font-bold">Epargne totale</p>
        <p class="mt-2 text-lg md:text-xl font-semibold">{{ formatCurrency(totalSavings) }}</p>
      </div>
    </div>

    <!-- Top Grid Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
      <!-- Add Budget Form -->
      <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
        <h2 class="text-lg md:text-xl font-bold text-[#1E293B] mb-3 md:mb-4">Ajouter Budget</h2>
        <hr class="border-t-2 border-gray-200 mb-4 md:mb-6">
        <form @submit.prevent="submitBudget">
          <div class="space-y-3 md:space-y-5">
            <!-- Budget Name -->
            <div class="flex flex-col sm:flex-row sm:items-center">
              <label class="text-sm font-medium text-gray-700 mb-1 sm:mb-0 sm:w-1/3 whitespace-nowrap">Nom du budget* :</label>
              <input v-model="form.name" type="text" class="flex-1 sm:w-2/3 border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent">
            </div>

            <!-- Total Amount -->
            <div class="flex flex-col sm:flex-row sm:items-center">
              <label class="text-sm font-medium text-gray-700 mb-1 sm:mb-0 sm:w-1/3 whitespace-nowrap">Montant Total* :</label>
              <div class="flex-1 sm:w-2/3 flex gap-2">
                <input v-model="form.amount" type="number" step="0.01" class="w-3/4 border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent">
                <input type="text" value="DH" readonly class="w-1/4 border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 text-center">
              </div>
            </div>

            <!-- Date -->
            <div class="flex flex-col sm:flex-row sm:items-center">
              <label class="text-sm font-medium text-gray-700 mb-1 sm:mb-0 sm:w-1/3 whitespace-nowrap">Date:</label>
              <input v-model="form.date" type="date" class="flex-1 sm:w-2/3 border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent">
            </div>

            <!-- Checkboxes -->
            <div class="flex flex-col sm:flex-row sm:items-start">
              <label class="font-medium text-gray-700 mb-1 sm:mb-0 sm:w-1/3"></label>
              <div class="flex-1 sm:w-2/3 flex flex-col xs:flex-row flex-wrap gap-2 xs:gap-4">
                <label class="inline-flex items-center">
                  <input v-model="form.is_monthly" type="checkbox" class="w-4 h-4 rounded text-[#10B981] focus:ring-[#10B981]">
                  <span class="ml-2 text-sm font-medium">Set mensuel</span>
                </label>
                <label class="inline-flex items-center">
                  <input v-model="form.apply_saving" type="checkbox" class="w-4 h-4 rounded text-[#10B981] focus:ring-[#10B981]">
                  <span class="ml-2 text-sm font-medium">Appliquer l'épargne</span>
                </label>
              </div>
            </div>

            <!-- Description -->
            <div class="flex flex-col sm:flex-row">
              <label class="text-sm font-medium text-gray-700 mb-1 sm:mb-0 sm:w-1/3 sm:pt-2 whitespace-nowrap">Description:</label>
              <textarea v-model="form.description" class="flex-1 sm:w-2/3 border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent h-20 md:h-24 resize-none"></textarea>
            </div>
          </div>

          <div class="mt-4 md:mt-6 flex justify-center sm:justify-end">
            <div v-if="formErrors" class="text-red-500 text-sm mr-3 self-center">
              {{ formErrors }}
            </div>
            <button type="submit" class="px-6 md:px-8 py-2 bg-[#86EFAC] hover:bg-[#4ADE80] border-[#86EFAC] text-black font-semibold rounded-lg border-2 hover:border-black transition-all w-full sm:w-auto">
              Ajouter
            </button>
          </div>
        </form>
      </div>

      <!-- Recent Budgets -->
      <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
        <h2 class="text-lg md:text-xl font-bold text-[#1E293B] mb-3 md:mb-4">Budgets ajoutés récemment</h2>
        <hr class="border-t-2 border-gray-200 mb-4 md:mb-6">
        <div class="overflow-hidden">
          <div v-if="recentBudgets.length > 0">
            <table class="min-w-full">
              <thead>
                <tr class="text-left text-sm font-medium text-gray-700 border-b-[0.5px] border-b-[#5AE4A8]">
                  <th class="py-2 px-2 md:px-4">Nom</th>
                  <th class="py-2 px-2 md:px-4 text-center">Date</th>
                  <th class="py-2 px-2 md:px-4 text-center">Montant</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-[#D1FAE5]">
                <tr v-for="budget in recentBudgets" :key="budget.id" class="border-b-[0.5px] border-b-[#5AE4A8]">
                  <td class="py-3 px-2 md:px-4 text-sm font-medium">{{ budget.name }}</td>
                  <td class="py-3 px-2 md:px-4 text-sm font-medium text-center">{{ formatDate(budget.date) }}</td>
                  <td class="py-3 px-2 md:px-4 text-sm font-medium text-center">{{ formatCurrency(budget.amount) }}</td>
                </tr>
              </tbody>
            </table>
            <div class="mt-4 md:mt-6 flex justify-center">
              <Link :href="route('history', { type: 'budget' })" class="text-sm font-medium text-[#10B981] hover:text-[#059669] transition duration-200">
                Voir tous les budgets →
              </Link>
            </div>
          </div>
          <div v-else class="text-center py-4 text-gray-500">
            Pas de budgets enregistrés
          </div>
        </div>
      </div>
    </div>

    <!-- Monthly Budget Section -->
    <div class="bg-white rounded-xl shadow-sm p-4 md:p-6 mt-4 md:mt-6">
      <h2 class="text-lg md:text-xl font-bold text-[#1E293B] mb-3 md:mb-4">Budget Mensuel</h2>
      <hr class="border-t-2 border-gray-200 mb-4 md:mb-6">

      <!-- Table for md screens and larger -->
      <div class="hidden md:block">
        <table class="min-w-full">
          <thead>
            <tr class="text-left text-sm font-medium text-gray-700 border-b-[0.5px] border-b-[#5AE4A8]">
              <th class="py-2 px-4">Nom</th>
              <th class="py-2 px-4 text-center">Jour du mois</th>
              <th class="py-2 px-4 text-center">Montant</th>
              <th class="py-2 px-4 text-center">Occurrences</th>
              <th class="py-2 px-4 text-center">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[#D1FAE5]">
            <tr 
              v-for="monthlyBudget in monthlyBudgets" 
              :key="monthlyBudget.id" 
              class="border-b-[0.5px] border-b-[#5AE4A8] cursor-pointer hover:bg-gray-50"
              @click="showBudgetDetails(monthlyBudget)"
            >
              <td class="py-2 px-4 text-sm font-medium">{{ monthlyBudget.name }}</td>
              <td class="py-2 px-4 text-sm font-medium text-center">{{ monthlyBudget.day_of_month }}</td>
              <td class="py-2 px-4 text-sm font-medium text-center">{{ formatCurrency(monthlyBudget.amount) }} DH</td>
              <td class="py-2 px-4 text-sm font-medium text-center">{{ monthlyBudget.occurrences }}</td>
              <td class="py-2 px-4 text-sm font-medium text-center" @click.stop>
                <div class="flex justify-center space-x-2">
                  <button @click="editMonthlyBudget(monthlyBudget)" class="p-1 rounded-md hover:bg-[#D1FAE5] transition-all">
                    <img src="@/Assets/IMG/edit.png" alt="Edit" class="w-5 h-5">
                  </button>
                  <button @click="confirmDelete(monthlyBudget)" class="p-1 rounded-md hover:bg-red-100 transition-all">
                    <img src="@/Assets/IMG/delete.png" alt="Delete" class="w-5 h-5">
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Cards for small screens -->
      <div class="md:hidden space-y-4">
        <div 
          v-for="monthlyBudget in monthlyBudgets" 
          :key="monthlyBudget.id" 
          class="bg-white border border-[#D1FAE5] rounded-lg shadow-sm p-4 cursor-pointer"
          @click="showBudgetDetails(monthlyBudget)"
        >
          <div class="flex justify-between items-start mb-2">
            <div>
              <h3 class="font-semibold text-[#1E293B]">{{ monthlyBudget.name }}</h3>
            </div>
            <span class="font-bold text-[#1E293B]">{{ formatCurrency(monthlyBudget.amount) }} DH</span>
          </div>
          <div class="flex justify-between items-center text-sm text-gray-600 mb-3">
            <div>
              <div>Jour: <span class="font-medium">{{ monthlyBudget.day_of_month }}</span></div>
              <div>Occurrences: <span class="font-medium">{{ monthlyBudget.occurrences }}</span></div>
            </div>
          </div>
          <div class="flex justify-end space-x-2 pt-2 border-t border-[#D1FAE5]" @click.stop>
            <button @click="editMonthlyBudget(monthlyBudget)" class="p-2 rounded-full bg-[#D1FAE5] hover:bg-[#A7F3D0] transition-all">
              <img src="@/Assets/IMG/edit.png" alt="Edit" class="w-4 h-4">
            </button>
            <button @click="confirmDelete(monthlyBudget)" class="p-2 rounded-full bg-red-50 hover:bg-red-100 transition-all">
              <img src="@/Assets/IMG/delete.png" alt="Delete" class="w-4 h-4">
            </button>
          </div>
        </div>
      </div>
    </div>
  </DashLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import { reactive, ref } from 'vue';
import DashLayout from '@/Layouts/DashLayout.vue';
import ConfirmationModal from '@/Components/Dashboard/ConfirmationModal.vue';
import EditMonthlyBudgetModal from '@/Components/Dashboard/EditMonthlyBudgetModal.vue';

const props = defineProps({
  totalBalance: {
    type: Number,
    required: true,
    default: 0
  },
  totalSavings: {
    type: Number,
    required: true,
    default: 0
  },
  recentBudgets: {
    type: Array,
    default: () => []
  },
  monthlyBudgets: {
    type: Array,
    default: () => []
  },
  freeMargin: {
    type: Number,
    default: 0
  }
});

const form = reactive({
  name: '',
  amount: '',
  date: '',
  description: '',
  is_monthly: false,
  apply_saving: false
});

const formErrors = ref('');
const showDeleteModal = ref(false);
const showEditModal = ref(false);
const showDetailsModal = ref(false);
const selectedBudget = ref(null);
const budgetToDelete = ref(null);

const formatCurrency = (amount) => {
  amount = typeof amount === 'number' ? amount : parseFloat(amount) || 0;
  return new Intl.NumberFormat('fr-FR', {
    style: 'currency',
    currency: 'MAD',
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  }).format(amount);
};

const formatDate = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  const day = date.getDate().toString().padStart(2, '0');
  const month = (date.getMonth() + 1).toString().padStart(2, '0');
  const year = date.getFullYear();
  return `${day}-${month}-${year}`;
};

const submitBudget = async () => {
  formErrors.value = '';
  
  try {
    if (!form.name.trim()) {
      formErrors.value = 'Le nom est obligatoire';
      return;
    }
    
    const amount = parseFloat(form.amount);
    if (isNaN(amount)) {
      formErrors.value = 'Montant invalide';
      return;
    }
    
    if (amount <= 0) {
      formErrors.value = 'Le montant doit être positif';
      return;
    }
    
    const response = await axios.post('/budgets', {
      name: form.name.trim(),
      amount: amount,
      date: form.date || new Date().toISOString().split('T')[0],
      description: form.description.trim(),
      is_monthly: form.is_monthly,
      apply_saving: form.apply_saving
    });
    
    // Reset form
    form.name = '';
    form.amount = '';
    form.date = '';
    form.description = '';
    form.is_monthly = false;
    form.apply_saving = false;
    
    window.location.reload();
  } catch (error) {
    formErrors.value = error.response?.data?.message || 'Veuillez réessayer';
    console.error('Error adding budget:', error);
  }
};

const showBudgetDetails = (budget) => {
  selectedBudget.value = budget;
  showDetailsModal.value = true;
};

const editMonthlyBudget = (budget) => {
  selectedBudget.value = budget;
  showEditModal.value = true;
};

const confirmDelete = (budget) => {
  budgetToDelete.value = budget;
  showDeleteModal.value = true;
};

const deleteMonthlyBudget = () => {
  router.delete(route('budgets.monthly.destroy', { id: budgetToDelete.value.id }), {
    preserveScroll: true,
    onSuccess: () => {
      showDeleteModal.value = false;
    },
    onError: (errors) => {
      alert(errors.message || 'Erreur lors de la suppression');
    }
  });
};

const handleSaved = () => {
  window.location.reload();
};
</script>