<template>
  <DashLayout>
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6 mb-6">
      <div class="bg-white rounded-xl shadow-sm p-4 md:p-6 text-center">
        <p class="text-[#5AE4A8] text-sm font-bold">Balance Totale</p>
        <p class="mt-2 text-lg md:text-xl font-semibold">{{ formatCurrency(totalBalance) }}</p>
      </div>
      <div class="bg-white rounded-xl shadow-sm p-4 md:p-6 text-center">
        <p class="text-[#5AE4A8] text-sm font-bold">Marge Libre</p>
        <p class="mt-2 text-lg md:text-xl font-semibold">{{ formatCurrency(freeMargin) }}</p>
      </div>
      <div class="bg-white rounded-xl shadow-sm p-4 md:p-6 text-center">
        <p class="text-[#5AE4A8] text-sm font-bold">Épargne</p>
        <p class="mt-2 text-lg md:text-xl font-semibold">{{ formatCurrency(savings) }}</p>
      </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
      <!-- Daily Chart -->
      <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
        <h2 class="text-lg md:text-xl font-bold text-[#1E293B] mb-3 md:mb-4">Graphique Journalier</h2>
        <hr class="border-t-2 border-gray-200 mb-4 md:mb-6">
        <div class="h-48 md:h-64 flex items-center justify-center">
          <!-- <DailyExpenseChart :data="dailyExpenseData" /> -->
        </div>
      </div>

      <!-- Budget Distribution Chart -->
      <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
        <h2 class="text-lg md:text-xl font-bold text-[#1E293B] mb-3 md:mb-4">Distribution Budget Marge Libre</h2>
        <hr class="border-t-2 border-gray-200 mb-4 md:mb-6">
        <div class="h-48 md:h-64 flex items-center justify-center">
          <!-- <BudgetDistributionChart :data="budgetDistributionData" /> -->
        </div>
      </div>
    </div>

    <!-- Quick Access - Hidden on small screens -->
    <div class="hidden sm:block mt-6 md:mt-8">
      <h2 class="text-lg md:text-xl font-bold text-[#1E293B] mb-3 md:mb-4">Accès Rapide</h2>
      <hr class="border-t-2 border-gray-200 mb-4 md:mb-6">
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        <Link 
          :href="route('budgets.create')" 
          class="bg-white rounded-xl shadow-sm p-4 text-center cursor-pointer hover:bg-[#D1FAE5] transition-colors"
        >
          <img src="@/Assets/IMG/budget.png" alt="Budget" class="w-10 h-10 mx-auto mb-2">
          <p class="text-[#5AE4A8] text-sm font-bold">Ajouter Montant</p>
        </Link>
        <Link 
          :href="route('expenses.create')" 
          class="bg-white rounded-xl shadow-sm p-4 text-center cursor-pointer hover:bg-[#D1FAE5] transition-colors"
        >
          <img src="@/Assets/IMG/depenses.png" alt="Dépenses" class="w-10 h-10 mx-auto mb-2">
          <p class="text-[#5AE4A8] text-sm font-bold">Ajouter Dépenses</p>
        </Link>
        <Link 
          :href="route('categories.index')" 
          class="bg-white rounded-xl shadow-sm p-4 text-center cursor-pointer hover:bg-[#D1FAE5] transition-colors"
        >
          <img src="@/Assets/IMG/category.png" alt="Catégories" class="w-10 h-10 mx-auto mb-2">
          <p class="text-[#5AE4A8] text-sm font-bold">Ajouter Catégorie</p>
        </Link>
        <Link 
          :href="route('savings.index')" 
          class="bg-white rounded-xl shadow-sm p-4 text-center cursor-pointer hover:bg-[#D1FAE5] transition-colors"
        >
          <img src="@/Assets/IMG/epargne.png" alt="Épargne" class="w-10 h-10 mx-auto mb-2">
          <p class="text-[#5AE4A8] text-sm font-bold">Gestion Épargne</p>
        </Link>
      </div>
    </div>

    <!-- Daily Expense Charts -->
    <div class="mt-6 md:mt-8">
      <h2 class="text-lg md:text-xl font-bold text-[#1E293B] mb-3 md:mb-4">Graphiques Journaliers des Dépenses</h2>
      <hr class="border-t-2 border-gray-200 mb-4 md:mb-6">
      <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
        <!-- <ExpenseByCategoryChart :data="expenseByCategoryData" /> -->
      </div>
    </div>
  </DashLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import DashLayout from '@/Layouts/DashLayout.vue';
// import DailyExpenseChart from '@/Components/Charts/DailyExpenseChart.vue';
// import BudgetDistributionChart from '@/Components/Charts/BudgetDistributionChart.vue';
// import ExpenseByCategoryChart from '@/Components/Charts/ExpenseByCategoryChart.vue';

const props = defineProps({
  totalBalance: Number,
  freeMargin: Number,
  savings: Number,
  dailyExpenseData: Object,
  budgetDistributionData: Object,
  expenseByCategoryData: Object
});

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'MAD' }).format(amount);
};
</script>