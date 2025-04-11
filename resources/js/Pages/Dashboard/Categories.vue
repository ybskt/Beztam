<template>
    <DashLayout>
      <!-- Confirmation Modal -->
      <ConfirmationModal
        :show="showDeleteModal"
        @close="showDeleteModal = false"
      >
        <template #title>
          Supprimer la catégorie
        </template>
        <template #content>
          Êtes-vous sûr de vouloir supprimer cette catégorie? Cette action est irréversible.
        </template>
        <template #footer>
          <button
            @click="showDeleteModal = false"
            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg mr-2"
          >
            Annuler
          </button>
          <button
            @click="deleteCategory"
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
      <EditCategoryModal
        :show="showEditModal"
        :category="selectedCategory"
        @close="showEditModal = false"
        @success="fetchCategories"
      />
  
      <!-- Top Grid Section -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
        <!-- Add Category Form -->
        <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
          <h2 class="text-lg md:text-xl font-bold text-[#1E293B] mb-3 md:mb-4">Ajouter Catégorie</h2>
          <hr class="border-t-2 border-gray-200 mb-4 md:mb-6">
          <form @submit.prevent="submit">
            <div class="space-y-3 md:space-y-5">
              <!-- Category Name -->
              <div class="flex flex-col sm:flex-row sm:items-center">
                <label class="text-sm font-medium text-gray-700 mb-1 sm:mb-0 sm:w-1/3 whitespace-nowrap">Nom de Catégorie* :</label>
                <input
                  v-model="form.name"
                  type="text"
                  class="flex-1 sm:w-2/3 border-2 rounded-lg p-2 border-[#D1FAE5] bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent"
                  required
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
  
            <div class="mt-4 md:mt-6 flex justify-center sm:justify-end">
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
  
        <!-- Consumption Chart -->
        <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
          <h2 class="text-lg md:text-xl font-bold text-[#1E293B] mb-3 md:mb-4">Graphe de consommation</h2>
          <hr class="border-t-2 border-gray-200 mb-4 md:mb-6">
          <div class="h-64 flex items-center justify-center bg-slate-50 rounded-lg">
            <p class="text-gray-500">Graphique à venir</p>
          </div>
        </div>
      </div>
  
      <!-- My Categories Section -->
      <div class="bg-white rounded-xl shadow-sm p-4 md:p-6 mt-4 md:mt-6">
        <h2 class="text-lg md:text-xl font-bold text-[#1E293B] mb-3 md:mb-4">Mes Catégories</h2>
        <hr class="border-t-2 border-gray-200 mb-4 md:mb-6">
  
        <!-- Card Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
          <!-- Category Card -->
          <div
            v-for="category in categories"
            :key="category.id"
            class="bg-white border border-[#D1FAE5] rounded-lg shadow-sm p-5"
          >
            <div class="flex justify-between items-start mb-3">
              <div>
                <h3 class="text-lg font-semibold text-[#1E293B]">
                  {{ category.name }}
                  <span v-if="category.name === 'Autre'" class="text-xs text-gray-500 ml-1">(Défaut)</span>
                </h3>
              </div>
            </div>
            <div class="flex flex-col text-base text-gray-600 mb-4 space-y-2">
              <div class="flex justify-between py-2 border-b border-gray-100">
                <span>Dépense Totale:</span>
                <span class="font-medium">{{ formatCurrency(category.total_expenses) }}</span>
              </div>
              <div class="flex justify-between py-2 border-b border-gray-100">
                <span>Dépense Mensuelle:</span>
                <span class="font-medium">{{ formatCurrency(category.monthly_expenses) }}</span>
              </div>
              <div class="flex justify-between py-2">
                <span>Limite:</span>
                <span class="font-medium">
                  {{ category.limit_amount ? formatCurrency(category.limit_amount) : 'Non définie' }}
                </span>
              </div>
            </div>
            <div class="flex justify-end space-x-3 pt-3 border-t border-[#D1FAE5]">
              <button
                @click="openEditModal(category)"
                class="p-2 rounded-full bg-[#D1FAE5] hover:bg-[#A7F3D0] transition-all"
                :disabled="category.name === 'Autre'"
                :class="{ 'opacity-50 cursor-not-allowed': category.name === 'Autre' }"
              >
                <img src="@/Assets/IMG/edit.png" alt="Edit" class="w-5 h-5">
              </button>
              <button
                @click="confirmDelete(category)"
                class="p-2 rounded-full bg-red-50 hover:bg-red-100 transition-all"
                :disabled="category.name === 'Autre'"
                :class="{ 'opacity-50 cursor-not-allowed': category.name === 'Autre' }"
              >
                <img src="@/Assets/IMG/delete.png" alt="Delete" class="w-5 h-5">
              </button>
            </div>
          </div>
        </div>
      </div>
    </DashLayout>
  </template>
  
  <script setup>
  import { Link, useForm, router } from '@inertiajs/vue3';
  import { ref } from 'vue';
  import DashLayout from '@/Layouts/DashLayout.vue';
  import ConfirmationModal from '@/Components/Dashboard/ConfirmationModal.vue';
  import EditCategoryModal from '@/Components/Dashboard/EditCategoryModal.vue';
  
  const props = defineProps({
    categories: Array,
    consumptionData: Object
  });
  
  const form = useForm({
    name: '',
    limit: null
  });
  
  const deleteForm = useForm({});
  
  const showDeleteModal = ref(false);
  const showEditModal = ref(false);
  const selectedCategory = ref(null);
  const categoryToDelete = ref(null);
  
  const formatCurrency = (amount) => {
    if (amount === null || amount === undefined) return 'N/A';
    return new Intl.NumberFormat('fr-FR', { 
      style: 'currency', 
      currency: 'MAD',
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    }).format(amount);
  };
  
  const submit = () => {
    form.post(route('categories.store'), {
      preserveScroll: true,
      onSuccess: () => {
        form.reset();
        router.reload({ only: ['categories'] });
      }
    });
  };
  
  const fetchCategories = () => {
    router.reload({ only: ['categories'] });
  };
  
  const openEditModal = (category) => {
    if (category.name === 'Autre') return;
    selectedCategory.value = category;
    showEditModal.value = true;
  };
  
  const confirmDelete = (category) => {
    if (category.name === 'Autre') return;
    categoryToDelete.value = category;
    showDeleteModal.value = true;
  };
  
  const deleteCategory = () => {
    deleteForm.delete(route('categories.destroy', categoryToDelete.value.id), {
      preserveScroll: true,
      onSuccess: () => {
        showDeleteModal.value = false;
        router.reload({ only: ['categories'] });
      }
    });
  };
  </script>