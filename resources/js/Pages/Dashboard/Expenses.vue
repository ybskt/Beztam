<template>
  <DashLayout>

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

    <!-- Confirmation Modal -->
    <ConfirmationModal
      :show="showDeleteModal"
      @close="showDeleteModal = false"
    >
      <template #title>
        Supprimer la dépense
      </template>
      <template #content>
        Êtes-vous sûr de vouloir supprimer cette dépense? Cette action est
        irréversible.
      </template>
      <template #footer>
        <button
          @click="showDeleteModal = false"
          class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg mr-2"
        >
          Annuler
        </button>
        <button
          @click="deleteItem"
          class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg"
          :class="{ 'opacity-50': deleteForm.processing }"
          :disabled="deleteForm.processing"
        >
          <span v-if="deleteForm.processing">Suppression...</span>
          <span v-else>Supprimer</span>
        </button>
      </template>
    </ConfirmationModal>

    <!-- Edit Modal -->
    <EditExpenseModal
      :show="showEditModal"
      :expense="selectedExpense"
      :categories="categories"
      @close="showEditModal = false"
      @saved="handleSaved"
    />

    <!-- Top Grid Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">

      <!-- Add Expense Form -->
      <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
        <h2 class="text-lg md:text-xl font-bold text-[#1E293B] mb-3 md:mb-4">Ajouter Dépense</h2>
        <hr class="border-t-2 border-gray-200 mb-4 md:mb-6" />
        <form @submit.prevent="submitExpense">
          <div class="space-y-3 md:space-y-5">
            <!-- Expense Name -->
            <div class="flex flex-col sm:flex-row sm:items-center">
              <label class="text-sm font-medium text-gray-700 mb-1 sm:mb-0 sm:w-1/3 whitespace-nowrap">Nom de dépense* :</label>
              <input v-model="form.name" type="text" class="flex-1 sm:w-2/3 border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent">

            </div>

            <!-- Category -->
            <div class="flex flex-col sm:flex-row sm:items-center">
              <label class="text-sm font-medium text-gray-700 mb-1 sm:mb-0 sm:w-1/3 whitespace-nowrap">Catégorie* :</label>
              <select
                v-model="form.category"
                class="flex-1 sm:w-2/3 border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent"
                required
              >
                <option value="" disabled selected>Sélectionner une catégorie</option>
                <option v-for="category in categories" :key="category.id" :value="category.id">
                  {{ category.name }}
                </option>
              </select>
            </div>

            <!-- Amount -->
            <div class="flex flex-col sm:flex-row sm:items-center">
              <label class="text-sm font-medium text-gray-700 mb-1 sm:mb-0 sm:w-1/3 whitespace-nowrap">Montant* :</label>
              <div class="flex-1 sm:w-2/3 flex gap-2">
                <input
                  v-model="form.amount"
                  type="number"
                  min="0"
                  step="0.01"
                  class="w-3/4 border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent"
                  required
                />
                <input type="text" value="DH" readonly class="w-1/4 border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 text-center" />
              </div>
            </div>

            <!-- Date -->
            <div class="flex flex-col sm:flex-row sm:items-center">
              <label class="text-sm font-medium text-gray-700 mb-1 sm:mb-0 sm:w-1/3 whitespace-nowrap">Date* :</label>
              <input
                v-model="form.date"
                type="date"
                class="flex-1 sm:w-2/3 border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent"
                :max="new Date().toISOString().split('T')[0]" 
              >
            </div>

            <!-- Monthly Checkbox -->
            <div class="flex flex-col sm:flex-row sm:items-start">
              <label class="font-medium text-gray-700 mb-1 sm:mb-0 sm:w-1/3"></label>
              <div class="flex-1 sm:w-2/3 flex flex-col xs:flex-row flex-wrap gap-2 xs:gap-4">
                <label class="inline-flex items-center">
                  <input v-model="form.is_monthly" type="checkbox" class="w-4 h-4 rounded text-[#10B981] focus:ring-[#10B981]" />
                  <span class="ml-2 text-sm font-medium">Dépense mensuelle</span>
                </label>
              </div>
            </div>

            <!-- Description -->
            <div class="flex flex-col sm:flex-row">
              <label class="text-sm font-medium text-gray-700 mb-1 sm:mb-0 sm:w-1/3 sm:pt-2 whitespace-nowrap">Description:</label>
              <textarea
                v-model="form.description"
                class="flex-1 sm:w-2/3 border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent h-20 md:h-24 resize-none"
              ></textarea>
            </div>
          </div>

          <div class="mt-4 md:mt-6 flex justify-center sm:justify-end">
            <div v-if="amountError" class="text-red-500 text-sm mr-3 self-center">
              Solde indisponible
            </div>
            <button
              type="submit"
              class="px-6 md:px-8 py-2 bg-[#86EFAC] hover:bg-[#4ADE80] border-[#86EFAC] text-black font-semibold rounded-lg border-2 hover:border-black transition-all w-full sm:w-auto"
              :disabled="form.processing"
            >
              <span v-if="form.processing">Ajout en cours...</span>
              <span v-else>Ajouter</span>
            </button>
          </div>
        </form>
      </div>

      <!-- Recent Expenses -->
      <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
        <h2 class="text-lg md:text-xl font-bold text-[#1E293B] mb-3 md:mb-4">Dépenses récentes</h2>
        <hr class="border-t-2 border-gray-200 mb-4 md:mb-6" />
        <div class="overflow-hidden">
          <div v-if="recentExpenses.length > 0">
            <table class="min-w-full">
              <thead>
                <tr class="text-left text-sm font-medium text-gray-700 border-b-[0.5px] border-b-[#5AE4A8]">
                  <th class="py-2 px-2 md:px-4">Nom</th>
                  <th class="py-2 px-2 md:px-4 text-center">Catégorie</th>
                  <th class="py-2 px-2 md:px-4 text-center">Date</th>
                  <th class="py-2 px-2 md:px-4 text-center">Montant</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-[#D1FAE5]">
                <tr v-for="expense in recentExpenses" :key="expense.id" class="border-b-[0.5px] border-b-[#5AE4A8]">
                  <td class="py-3 px-2 md:px-4 text-sm font-medium">{{ expense.name }}</td>
                  <td class="py-3 px-2 md:px-4 text-sm font-medium text-center">{{ expense.category.name }}</td>
                  <td class="py-3 px-2 md:px-4 text-sm font-medium text-center">{{ formatDate(expense.date) }}</td>
                  <td class="py-3 px-2 md:px-4 text-sm font-medium text-center">{{ formatCurrency(expense.amount) }}</td>
                </tr>
              </tbody>
            </table>
            <div class="mt-4 md:mt-6 flex justify-center">
              <Link :href="route('history', { type: 'expense' })" class="text-sm font-medium text-[#10B981] hover:text-[#059669] transition duration-200">
                Voir toutes les dépenses →
              </Link>
            </div>
          </div>
          <div v-else class="text-center py-4 text-gray-500">
            Aucune dépense récente
          </div>
        </div>
      </div>
    </div>

    <!-- Monthly Expenses Section -->
    <div class="bg-white rounded-xl shadow-sm p-4 md:p-6 mt-4 md:mt-6">
      <h2 class="text-lg md:text-xl font-bold text-[#1E293B] mb-3 md:mb-4">Dépenses Mensuelles</h2>
      <hr class="border-t-2 border-gray-200 mb-4 md:mb-6" />

      <!-- Table for md screens and larger -->
      <div class="hidden md:block">
        <table class="min-w-full">
          <thead>
            <tr class="text-left text-sm font-medium text-gray-700 border-b-[0.5px] border-b-[#5AE4A8]">
              <th class="py-2 px-4">Nom</th>
              <th class="py-2 px-4 text-center">Catégorie</th>
              <th class="py-2 px-4 text-center">Jour du mois</th>
              <th class="py-2 px-4 text-center">Montant</th>
              <th class="py-2 px-4 text-center">Occurrences</th>
              <th class="py-2 px-4 text-center">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[#D1FAE5]">
            <tr v-for="expense in monthlyExpenses" :key="expense.id" class="border-b-[0.5px] border-b-[#5AE4A8]">
              <td class="py-2 px-4 text-sm font-medium">{{ expense.name }}</td>
              <td class="py-2 px-4 text-sm font-medium text-center">{{ expense.category.name }}</td>
              <td class="py-2 px-4 text-sm font-medium text-center">{{ expense.day_of_month }}</td>
              <td class="py-2 px-4 text-sm font-medium text-center">{{ formatCurrency(expense.amount) }}</td>
              <td class="py-2 px-4 text-sm font-medium text-center">{{ expense.occurrences }}</td>
              <td class="py-2 px-4 text-sm font-medium text-center">
                <div class="flex justify-center space-x-2">
                  <button @click="editExpense(expense)" class="p-1 rounded-md hover:bg-[#D1FAE5] transition-all">
                    <img src="@/Assets/IMG/edit.png" alt="Edit" class="w-5 h-5" />
                  </button>
                  <button @click="confirmDelete(expense, true)" class="p-1 rounded-md hover:bg-red-100 transition-all">
                    <img src="@/Assets/IMG/delete.png" alt="Delete" class="w-5 h-5" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Cards for small screens -->
      <div class="md:hidden space-y-4">
        <div v-for="expense in monthlyExpenses" :key="expense.id" class="bg-white border border-[#D1FAE5] rounded-lg shadow-sm p-4">
          <div class="flex justify-between items-start mb-2">
            <div>
              <h3 class="font-semibold text-[#1E293B]">{{ expense.name }}</h3>
              <span class="inline-block bg-[#D1FAE5] text-[#10B981] text-xs px-2 py-1 rounded-full mt-1">
                {{ expense.category.name }}
              </span>
            </div>
            <span class="font-bold text-[#1E293B]">{{ formatCurrency(expense.amount) }}</span>
          </div>
          <div class="flex justify-between items-center text-sm text-gray-600 mb-3">
            <div>
              <div>Jour du mois: <span class="font-medium">{{ expense.day_of_month }}</span></div>
              <div>Occurrences: <span class="font-medium">{{ expense.occurrences }}</span></div>
            </div>
          </div>
          <div class="flex justify-end space-x-2 pt-2 border-t border-[#D1FAE5]">
            <button @click="editExpense(expense)" class="p-2 rounded-full bg-[#D1FAE5] hover:bg-[#A7F3D0] transition-all">
              <img src="@/Assets/IMG/edit.png" alt="Edit" class="w-4 h-4" />
            </button>
            <button @click="confirmDelete(expense, true)" class="p-2 rounded-full bg-red-50 hover:bg-red-100 transition-all">
              <img src="@/Assets/IMG/delete.png" alt="Delete" class="w-4 h-4" />
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- Daily Expenses Chart Section -->
    <div class="bg-white rounded-xl shadow-sm p-4 md:p-6 mt-4 md:mt-6">
      <h2 class="text-lg md:text-xl font-bold text-[#1E293B] mb-3 md:mb-4">Graphe Journalier des Dépenses</h2>
      <hr class="border-t-2 border-gray-200 mb-4 md:mb-6">
      <div class="relative h-64 sm:h-80 md:h-96">
        <canvas id="dailyExpensesChart"></canvas>
      </div>
    </div>
  </DashLayout>
</template>

<script setup>
import { Link, useForm, router } from '@inertiajs/vue3';
import { ref, onMounted  } from 'vue';
import DashLayout from '@/Layouts/DashLayout.vue';
import ConfirmationModal from '@/Components/Dashboard/ConfirmationModal.vue';
import EditExpenseModal from '@/Components/Dashboard/EditExpenseModal.vue';
import { Chart, registerables } from 'chart.js';
Chart.register(...registerables);

const props = defineProps({
  categories: Array,
  recentExpenses: Array,
  monthlyExpenses: Array,
  totalBalance: Number,
  freeMargin: Number,
  totalSavings: Number
});

const form = useForm({
  name: '',
  category: '',
  amount: '',
  date: new Date().toISOString().split('T')[0], // Same as budget page
  is_monthly: false,
  description: ''
});

const deleteForm = useForm({});
const showDeleteModal = ref(false);
const showEditModal = ref(false);
const selectedExpense = ref(null);
const itemToDelete = ref(null);
const isMonthlyDelete = ref(false);
const amountError = ref(false);

const formatCurrency = (amount) => {
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

const submitExpense = () => {
  if (parseFloat(form.amount) > props.freeMargin) {
    amountError.value = true;
    return;
  }

  amountError.value = false;

  form.post(route('expenses.store'), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset();
      form.date = new Date().toISOString().split('T')[0]; // Same as budget page
      window.location.reload(); // Full reload like budget page
    },
    onError: (errors) => {
      console.error('Error adding expense:', errors);
    }
  });
};

const handleSaved = () => {
  router.reload({ only: ['recentExpenses', 'monthlyExpenses'] });
};

const editExpense = (expense) => {
  selectedExpense.value = expense;
  showEditModal.value = true;
};

const confirmDelete = (expense, isMonthly = false) => {
  itemToDelete.value = expense;
  isMonthlyDelete.value = isMonthly;
  showDeleteModal.value = true;
};

const deleteItem = () => {
  const routeName = isMonthlyDelete.value ? 'expenses.monthly.destroy' : 'expenses.destroy';
  deleteForm.delete(route(routeName, itemToDelete.value.id), {
    preserveScroll: true,
    onSuccess: () => {
      showDeleteModal.value = false;
      router.reload({ only: ['recentExpenses', 'monthlyExpenses'] });
    }
  });
};

const initDailyExpensesChart = async () => {
  try {
    const response = await axios.get(route('expenses.daily-data'));
    const { labels, data } = response.data;
    const ctx = document.getElementById('dailyExpensesChart');
    
    // Destroy previous chart instance if exists
    if (window.expenseChartInstance) {
      window.expenseChartInstance.destroy();
    }
    
    if (!ctx) return;
    
    window.expenseChartInstance = new Chart(ctx, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'Dépenses quotidiennes',
          data: data,
          borderColor: '#EF4444',
          backgroundColor: 'rgba(239, 68, 68, 0.1)',
          borderWidth: 2,
          fill: true,
          tension: 0.4,
          spanGaps: true
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'top',
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                return context.raw !== null
                  ? `${context.dataset.label}: ${formatCurrency(context.raw)}`
                  : 'Pas de données';
              }
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: function(value) {
                return formatCurrency(value);
              }
            }
          },
          x: {
            title: {
              display: true,
              text: 'Jour du mois'
            }
          }
        }
      }
    });
  } catch (error) {
    console.error('Error initializing expenses chart:', error);
  }
};
// Call it in onMounted
onMounted(() => {
  initDailyExpensesChart();
});


</script>