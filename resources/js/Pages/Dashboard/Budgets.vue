<template>
  <DashLayout>
    <!-- Top Grid Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
      <!-- Ajouter Budget -->
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
                <input v-model="form.amount" type="number" class="w-3/4 border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent">
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
          <div>
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
                  <td class="py-3 px-2 md:px-4 text-sm font-medium text-center">{{ budget.date }}</td>
                  <td class="py-3 px-2 md:px-4 text-sm font-medium text-center">{{ budget.amount }} DH</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="mt-4 md:mt-6 flex justify-center">
            <Link href="#" class="text-sm font-medium text-[#10B981] hover:text-[#059669] transition duration-200">
              Voir tous les budgets →
            </Link>
          </div>
        </div>
      </div>
    </div>

    <!-- Budget Mensuel Section -->
    <div class="bg-white rounded-xl shadow-sm p-4 md:p-6 mt-4 md:mt-6">
      <h2 class="text-lg md:text-xl font-bold text-[#1E293B] mb-3 md:mb-4">Budget Mensuel</h2>
      <hr class="border-t-2 border-gray-200 mb-4 md:mb-6">
      
      <!-- Table for md screens and larger -->
      <div class="hidden md:block">
        <table class="min-w-full">
          <thead>
            <tr class="text-left text-sm font-medium text-gray-700 border-b-[0.5px] border-b-[#5AE4A8]">
              <th class="py-2 px-4">Nom</th>
              <th class="py-2 px-4 text-center">Date</th>
              <th class="py-2 px-4 text-center">Montant</th>
              <th class="py-2 px-4 text-center">Occurrences</th>
              <th class="py-2 px-4 text-center">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[#D1FAE5]">
            <tr v-for="monthlyBudget in monthlyBudgets" :key="monthlyBudget.id" class="border-b-[0.5px] border-b-[#5AE4A8]">
              <td class="py-2 px-4 text-sm font-medium">{{ monthlyBudget.name }}</td>
              <td class="py-2 px-4 text-sm font-medium text-center">{{ monthlyBudget.date }}</td>
              <td class="py-2 px-4 text-sm font-medium text-center">{{ monthlyBudget.amount }} DH</td>
              <td class="py-2 px-4 text-sm font-medium text-center">{{ monthlyBudget.occurrences }}</td>
              <td class="py-2 px-4 text-sm font-medium text-center">
                <div class="flex justify-center space-x-2">
                  <button @click="editBudget(monthlyBudget.id)" class="p-1 rounded-md hover:bg-[#D1FAE5] transition-all">
                    <img src="@/Assets/IMG/edit.png" alt="Edit" class="w-5 h-5">
                  </button>
                  <button @click="deleteBudget(monthlyBudget.id)" class="p-1 rounded-md hover:bg-red-100 transition-all">
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
        <div v-for="monthlyBudget in monthlyBudgets" :key="monthlyBudget.id" class="bg-white border border-[#D1FAE5] rounded-lg shadow-sm p-4">
          <div class="flex justify-between items-start mb-2">
            <div>
              <h3 class="font-semibold text-[#1E293B]">{{ monthlyBudget.name }}</h3>
            </div>
            <span class="font-bold text-[#1E293B]">{{ monthlyBudget.amount }} DH</span>
          </div>
          <div class="flex justify-between items-center text-sm text-gray-600 mb-3">
            <div>
              <div>Date: <span class="font-medium">{{ monthlyBudget.date }}</span></div>
              <div>Occurrences: <span class="font-medium">{{ monthlyBudget.occurrences }}</span></div>
            </div>
          </div>
          <div class="flex justify-end space-x-2 pt-2 border-t border-[#D1FAE5]">
            <button @click="editBudget(monthlyBudget.id)" class="p-2 rounded-full bg-[#D1FAE5] hover:bg-[#A7F3D0] transition-all">
              <img src="@/Assets/IMG/edit.png" alt="Edit" class="w-4 h-4">
            </button>
            <button @click="deleteBudget(monthlyBudget.id)" class="p-2 rounded-full bg-red-50 hover:bg-red-100 transition-all">
              <img src="@/Assets/IMG/delete.png" alt="Delete" class="w-4 h-4">
            </button>
          </div>
        </div>
      </div>
    </div>
  </DashLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import DashLayout from '@/Layouts/DashLayout.vue';
import { reactive } from 'vue';

const props = defineProps({
  recentBudgets: Array,
  monthlyBudgets: Array
});

const form = reactive({
  name: '',
  amount: '',
  date: '',
  is_monthly: false,
  apply_saving: false,
  description: ''
});

const submitBudget = () => {
  // Handle form submission
  console.log('Form submitted:', form);
  // You would typically use Inertia.post here
};

const editBudget = (id) => {
  // Handle edit
  console.log('Edit budget with id:', id);
};

const deleteBudget = (id) => {
  // Handle delete
  console.log('Delete budget with id:', id);
};
</script>