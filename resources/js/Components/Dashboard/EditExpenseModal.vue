<template>
    <Transition name="fade">
      <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
          <!-- Background overlay -->
          <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="close"></div>
  
          <!-- Modal content -->
          <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
              <h3 class="text-lg leading-6 font-medium text-[#1E293B] mb-4">
                {{ isMonthly ? 'Modifier Dépense Mensuelle' : 'Modifier Dépense' }}
              </h3>
              
              <form @submit.prevent="submit">
                <div class="space-y-4">
                  <!-- Expense Name -->
                  <div class="flex flex-col sm:flex-row sm:items-center">
                    <label class="text-sm font-medium text-gray-700 mb-1 sm:mb-0 sm:w-1/3 whitespace-nowrap">Nom* :</label>
                    <input
                      v-model="form.name"
                      type="text"
                      class="flex-1 sm:w-2/3 border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent"
                      required
                    >
                  </div>
  
                  <!-- Category -->
                  <div class="flex flex-col sm:flex-row sm:items-center">
                    <label class="text-sm font-medium text-gray-700 mb-1 sm:mb-0 sm:w-1/3 whitespace-nowrap">Catégorie* :</label>
                    <select
                      v-model="form.category_id"
                      class="flex-1 sm:w-2/3 border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent"
                      required
                    >
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
                      >
                      <input type="text" value="DH" readonly class="w-1/4 border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 text-center">
                    </div>
                  </div>
  
                  <!-- Date or Day of Month -->
                  <div class="flex flex-col sm:flex-row sm:items-center">
                    <label class="text-sm font-medium text-gray-700 mb-1 sm:mb-0 sm:w-1/3 whitespace-nowrap">
                      {{ isMonthly ? 'Jour du mois* :' : 'Date* :' }}
                    </label>
                    <select
                      v-if="isMonthly"
                      v-model="form.day_of_month"
                      class="flex-1 sm:w-2/3 border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent"
                      required
                    >
                      <option v-for="day in 31" :key="day" :value="day">{{ day }}</option>
                    </select>
                    <input
                      v-else
                      v-model="form.date"
                      type="date"
                      class="flex-1 sm:w-2/3 border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent"
                      required
                    >
                  </div>
  
                  <!-- Description -->
                  <div class="flex flex-col sm:flex-row">
                    <label class="text-sm font-medium text-gray-700 mb-1 sm:mb-0 sm:w-1/3 sm:pt-2 whitespace-nowrap">Description:</label>
                    <textarea
                      v-model="form.description"
                      class="flex-1 sm:w-2/3 border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent h-20 resize-none"
                    ></textarea>
                  </div>
                </div>
  
                <div class="mt-6 flex justify-end space-x-3">
                  <button
                    type="button"
                    @click="close"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg"
                  >
                    Annuler
                  </button>
                  <button
                    type="submit"
                    class="px-6 py-2 bg-[#86EFAC] hover:bg-[#4ADE80] border-[#86EFAC] text-black font-semibold rounded-lg border-2 hover:border-black transition-all"
                    :disabled="form.processing"
                  >
                    <span v-if="form.processing">Enregistrement...</span>
                    <span v-else>Enregistrer</span>
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </template>
  
  <script setup>
  import { useForm } from '@inertiajs/vue3';
  import { computed, watch } from 'vue';
  
  const props = defineProps({
    show: Boolean,
    expense: Object,
    categories: Array
  });
  
  const emit = defineEmits(['close', 'saved']);
  
  const isMonthly = computed(() => props.expense?.day_of_month !== undefined);
  
  const form = useForm({
    name: props.expense?.name || '',
    category_id: props.expense?.category_id || '',
    amount: props.expense?.amount || '',
    date: props.expense?.date || new Date().toISOString().split('T')[0],
    day_of_month: props.expense?.day_of_month || new Date().getDate(),
    description: props.expense?.description || ''
  });
  
  // Update form when expense prop changes
  watch(() => props.expense, (newExpense) => {
    if (newExpense) {
      form.name = newExpense.name;
      form.category_id = newExpense.category_id;
      form.amount = newExpense.amount;
      form.date = newExpense.date || new Date().toISOString().split('T')[0];
      form.day_of_month = newExpense.day_of_month || new Date().getDate();
      form.description = newExpense.description || '';
    }
  });
  
  const submit = () => {
    const routeName = isMonthly.value ? 'expenses.monthly.destroy' : 'expenses.destroy';
    const method = isMonthly.value ? 'put' : 'put';
    
    form[method](route(routeName, props.expense.id), {
      preserveScroll: true,
      onSuccess: () => {
        emit('saved');
        close();
      }
    });
  };
  
  const close = () => {
    form.reset();
    emit('close');
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