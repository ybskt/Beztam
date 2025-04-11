<template>
    <Transition name="fade">
      <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
          <!-- Background overlay -->
          <Transition name="fade">
            <div v-if="show" class="fixed inset-0 transition-opacity" aria-hidden="true">
              <div class="absolute inset-0 bg-gray-500 opacity-75" @click="close"></div>
            </div>
          </Transition>
  
          <!-- Modal content -->
          <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
              <div class="w-full">
                <h3 class="text-lg leading-6 font-medium text-[#1E293B] mb-4">Modifier Catégorie</h3>
                <form @submit.prevent="submit">
                  <div class="space-y-4">
                    <!-- Category Name -->
                    <div class="flex flex-col sm:flex-row sm:items-center">
                      <label class="text-sm font-medium text-gray-700 mb-1 sm:mb-0 sm:w-1/3 whitespace-nowrap">Nom de Catégorie* :</label>
                      <input
                        v-model="form.name"
                        type="text"
                        class="flex-1 sm:w-2/3 border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent"
                      >
                      <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                    </div>
  
                    <!-- Limit Amount -->
                    <div class="flex flex-col sm:flex-row sm:items-center">
                      <label class="text-sm font-medium text-gray-700 mb-1 sm:mb-0 sm:w-1/3 whitespace-nowrap">Limite :</label>
                      <div class="flex-1 sm:w-2/3 flex gap-2">
                        <input
                          v-model="form.limit"
                          type="number"
                          min="0"
                          step="0.01"
                          class="w-3/4 border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent"
                        >
                        <input type="text" value="DH" readonly class="w-1/4 border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 text-center">
                      </div>
                      <p v-if="form.errors.limit" class="mt-1 text-sm text-red-600">{{ form.errors.limit }}</p>
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
                      <span v-else>Modifier</span>
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </template>
  
  <script setup>
  import { useForm } from '@inertiajs/vue3';
  import { watch } from 'vue';
  
  const props = defineProps({
    show: Boolean,
    category: Object
  });
  
  const emit = defineEmits(['close']);
  
  const form = useForm({
    name: props.category?.name || '',
    limit: props.category?.limit_amount || null
  });
  
  watch(() => props.category, (newCategory) => {
    if (newCategory) {
      form.name = newCategory.name;
      form.limit = newCategory.limit_amount;
    }
  });
  
  const close = () => {
    form.reset();
    emit('close');
  };
  
  const submit = () => {
    form.put(route('categories.update', props.category.id), {
      preserveScroll: true,
      onSuccess: () => {
        close();
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