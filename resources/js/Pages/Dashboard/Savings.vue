<template>
  <DashLayout>
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6 mb-6">
      <div class="bg-white rounded-xl shadow-sm p-4 md:p-6 text-center">
        <p class="text-[#5AE4A8] text-sm font-bold">Balance Total</p>
        <p class="mt-2 text-lg md:text-xl font-semibold">{{ formatCurrency(totalBalance) }}</p>
      </div>
      <div class="bg-white rounded-xl shadow-sm p-4 md:p-6 text-center">
        <p class="text-[#5AE4A8] text-sm font-bold">Épargne Total</p>
        <p class="mt-2 text-lg md:text-xl font-semibold">{{ formatCurrency(totalSavings) }}</p>
      </div>
      <div class="bg-white rounded-xl shadow-sm p-4 md:p-6 text-center">
        <p class="text-[#5AE4A8] text-sm font-bold">Pourcentage à économiser</p>
        <p class="mt-2 text-lg md:text-xl font-semibold">{{ savingsRate }}%</p>
      </div>
    </div>

    <!-- Top Grid Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
      <!-- Add Savings Form -->
      <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
        <h2 class="text-lg md:text-xl font-bold text-[#1E293B] mb-3 md:mb-4">Ajouter Montant à l'Épargne</h2>
        <hr class="border-t-2 border-gray-200 mb-4 md:mb-6">
        <form @submit.prevent="submitSavings">
          <div class="space-y-3 md:space-y-5">
            <!-- Name -->
            <div class="flex flex-col sm:flex-row sm:items-center">
              <label class="text-sm font-medium text-gray-700 mb-1 sm:mb-0 sm:w-1/3 whitespace-nowrap">Nom* :</label>
              <input v-model="form.name" type="text" class="flex-1 sm:w-2/3 border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent">
            </div>
            <!-- Amount -->
            <div class="flex flex-col sm:flex-row sm:items-center">
              <label class="text-sm font-medium text-gray-700 mb-1 sm:mb-0 sm:w-1/3 whitespace-nowrap">Montant* :</label>
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

      <!-- Recent Savings -->
      <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
        <h2 class="text-lg md:text-xl font-bold text-[#1E293B] mb-3 md:mb-4">Épargnes Récentes</h2>
        <hr class="border-t-2 border-gray-200 mb-4 md:mb-6">
        <div class="overflow-hidden">
          <div v-if="recentSavings.length > 0">
            <table class="min-w-full">
              <thead>
                <tr class="text-left text-sm font-medium text-gray-700 border-b-[0.5px] border-b-[#5AE4A8]">
                  <th class="py-2 px-2 md:px-4">Nom</th>
                  <th class="py-2 px-2 md:px-4 text-center">Date</th>
                  <th class="py-2 px-2 md:px-4 text-center">Montant</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-[#D1FAE5]">
                <tr v-for="saving in recentSavings" :key="saving.id" class="border-b-[0.5px] border-b-[#5AE4A8]">
                  <td class="py-3 px-2 md:px-4 text-sm font-medium">{{ saving.name }}</td>
                  <td class="py-3 px-2 md:px-4 text-sm font-medium text-center">{{ saving.date }}</td>
                  <td class="py-3 px-2 md:px-4 text-sm font-medium text-center">{{ formatCurrency(saving.amount) }}</td>
                </tr>
              </tbody>
            </table>
            <div class="mt-4 md:mt-6 flex justify-center">
              <Link :href="route('savings.all')" class="text-sm font-medium text-[#10B981] hover:text-[#059669] transition duration-200">
                Voir toutes les épargnes →
              </Link>
            </div>
          </div>
          <div v-else class="text-center py-4 text-gray-500">
            Pas d'épargnes enregistrées
          </div>
        </div>
      </div>
    </div>

    <!-- Bottom Grid Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mt-4 md:mt-6">
      <!-- Savings Rate -->
      <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
        <h2 class="text-lg md:text-xl font-bold text-[#1E293B] mb-3 md:mb-4">Taux d'Épargne</h2>
        <hr class="border-t-2 border-gray-200 mb-4 md:mb-6">
        <form @submit.prevent="updateSavingsRate">
          <div class="space-y-3 md:space-y-5">
            <!-- Savings Rate -->
            <div class="flex flex-col sm:flex-row sm:items-center">
              <label class="text-sm font-medium text-gray-700 mb-1 sm:mb-0 sm:w-1/3 whitespace-nowrap">Pourcentage :</label>
              <div class="flex-1 sm:w-2/3 flex gap-2">
                <input v-model="rateForm.percentage" type="number" class="w-3/4 border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent">
                <input type="text" value="%" readonly class="w-1/4 border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 text-center">
              </div>
            </div>
          </div>
          <div class="mt-4 md:mt-6 flex justify-center sm:justify-end">
            <button type="submit" class="px-6 md:px-8 py-2 bg-[#86EFAC] hover:bg-[#4ADE80] border-[#86EFAC] text-black font-semibold rounded-lg border-2 hover:border-black transition-all w-full sm:w-auto">
              Confirmer
            </button>
          </div>
        </form>
      </div>

      <!-- Transfer to Free Margin -->
      <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
        <h2 class="text-lg md:text-xl font-bold text-[#1E293B] mb-3 md:mb-4">Transférer vers la Marge Libre</h2>
        <hr class="border-t-2 border-gray-200 mb-4 md:mb-6">
        <form @submit.prevent="transferToMargin">
          <div class="space-y-3 md:space-y-5">
            <!-- Transfer Amount -->
            <div class="flex flex-col sm:flex-row sm:items-center">
              <label class="text-sm font-medium text-gray-700 mb-1 sm:mb-0 sm:w-1/3 whitespace-nowrap">Montant :</label>
              <div class="flex-1 sm:w-2/3 flex gap-2">
                <input v-model="transferForm.amount" type="number" class="w-3/4 border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent">
                <input type="text" value="DH" readonly class="w-1/4 border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 text-center">
              </div>
            </div>
          </div>
          <div class="mt-4 md:mt-6 flex justify-center sm:justify-end">
            <button type="submit" class="px-6 md:px-8 py-2 bg-[#86EFAC] hover:bg-[#4ADE80] border-[#86EFAC] text-black font-semibold rounded-lg border-2 hover:border-black transition-all w-full sm:w-auto">
              Transférer
            </button>
          </div>
        </form>
      </div>
    </div>
  </DashLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { reactive } from 'vue';
import DashLayout from '@/Layouts/DashLayout.vue';

const props = defineProps({
  totalBalance: Number,
  totalSavings: Number,
  savingsRate: Number,
  recentSavings: Array
});

const form = reactive({
  name: '',
  amount: '',
  date: '',
  description: ''
});

const rateForm = reactive({
  percentage: props.savingsRate
});

const transferForm = reactive({
  amount: ''
});

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'MAD' }).format(amount);
};

const submitSavings = async () => {
  try {
      if (!form.name || !form.amount) {
          alert('Le nom et le montant sont obligatoires');
          return;
      }
      
      if (form.amount <= 0) {
          alert('Le montant doit être positif');
          return;
      }
      
      if (!form.date) {
          form.date = new Date().toISOString().split('T')[0];
      }

      const response = await axios.post('/dashboard/savings', form);
      window.location.reload();
  } catch (error) {
      console.error(error);
      alert('Une erreur est survenue lors de l\'ajout');
  }
};

const updateSavingsRate = async () => {
  try {
      if (rateForm.percentage < 0 || rateForm.percentage > 100) {
          alert('Le pourcentage doit être entre 0 et 100');
          return;
      }

      const response = await axios.post('/dashboard/savings/rate', rateForm);
      window.location.reload();
  } catch (error) {
      console.error(error);
      alert('Une erreur est survenue lors de la mise à jour');
  }
};

const transferToMargin = async () => {
  try {
      if (!transferForm.amount || transferForm.amount <= 0) {
          alert('Le montant doit être positif');
          return;
      }

      const response = await axios.post('/dashboard/savings/transfer', transferForm);
      window.location.reload();
  } catch (error) {
      console.error(error);
      alert(error.response?.data?.message || 'Le montant dépasse votre épargne totale');
  }
};
</script>