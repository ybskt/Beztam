<template>
  <DashLayout>
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6 mb-6">
      <div class="bg-white rounded-xl shadow-sm p-4 md:p-6 text-center">
        <p class="text-[#5AE4A8] text-sm font-bold">Solde Total</p>
        <p class="mt-2 text-lg md:text-xl font-semibold">{{ formatCurrency(totalBalance) }}</p>
      </div>
      <div class="bg-white rounded-xl shadow-sm p-4 md:p-6 text-center">
        <p class="text-[#5AE4A8] text-sm font-bold">Marge Libre</p>
        <p class="mt-2 text-lg md:text-xl font-semibold">{{ formatCurrency(freeMargin) }}</p>
      </div>
      <div class="bg-white rounded-xl shadow-sm p-4 md:p-6 text-center">
        <p class="text-[#5AE4A8] text-sm font-bold">Épargne Totale</p>
        <p class="mt-2 text-lg md:text-xl font-semibold">{{ formatCurrency(savings) }}</p>
      </div>
    </div>

    <!-- Top Charts Section - Bar and Doughnut side by side -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6">
      <!-- Monthly Summary Bar Chart -->
      <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
        <h2 class="text-lg md:text-xl font-bold text-[#1E293B] mb-3 md:mb-4">Répartition Mensuelle</h2>
        <hr class="border-t-2 border-gray-200 mb-4 md:mb-6">
        <div class="relative h-64 sm:h-80 md:h-96">
          <canvas id="monthlySummaryChart"></canvas>
        </div>
      </div>

      <!-- Budget Distribution Doughnut Chart -->
      <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
        <h2 class="text-lg md:text-xl font-bold text-[#1E293B] mb-3 md:mb-4">Répartition des Fonds</h2>
        <hr class="border-t-2 border-gray-200 mb-4 md:mb-6">
        <div class="relative h-64 sm:h-80 md:h-96">
          <canvas id="budgetDistributionChart"></canvas>
        </div>
      </div>
    </div>

    <!-- Quick Access - Hidden on small screens -->
    <div class="hidden sm:block mt-6 md:mt-8">
      <h2 class="text-lg md:text-xl font-bold text-[#1E293B] mb-3 md:mb-4">Accès Rapide</h2>
      <hr class="border-t-2 border-gray-200 mb-4 md:mb-6">
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        <Link
          :href="route('budgets')"
          class="bg-white rounded-xl shadow-sm p-4 text-center cursor-pointer hover:bg-[#D1FAE5] transition-colors"
        >
          <img src="@/Assets/IMG/budget.png" alt="Revenu" class="w-10 h-10 mx-auto mb-2">
          <p class="text-[#5AE4A8] text-sm font-bold">Revenus</p>
        </Link>
        <Link
          :href="route('expenses')"
          class="bg-white rounded-xl shadow-sm p-4 text-center cursor-pointer hover:bg-[#D1FAE5] transition-colors"
        >
          <img src="@/Assets/IMG/depenses.png" alt="Dépenses" class="w-10 h-10 mx-auto mb-2">
          <p class="text-[#5AE4A8] text-sm font-bold">Dépenses</p>
        </Link>
        <Link
          :href="route('categories')"
          class="bg-white rounded-xl shadow-sm p-4 text-center cursor-pointer hover:bg-[#D1FAE5] transition-colors"
        >
          <img src="@/Assets/IMG/category.png" alt="Catégories" class="w-10 h-10 mx-auto mb-2">
          <p class="text-[#5AE4A8] text-sm font-bold">Catégories</p>
        </Link>
        <Link
          :href="route('savings')"
          class="bg-white rounded-xl shadow-sm p-4 text-center cursor-pointer hover:bg-[#D1FAE5] transition-colors"
        >
          <img src="@/Assets/IMG/epargne.png" alt="Épargne" class="w-10 h-10 mx-auto mb-2">
          <p class="text-[#5AE4A8] text-sm font-bold">Épargne</p>
        </Link>
      </div>
    </div>

    <!-- Daily Evolution Chart -->
    <div class="bg-white rounded-xl shadow-sm p-4 md:p-6 mt-6">
      <h2 class="text-lg md:text-xl font-bold text-[#1E293B] mb-3 md:mb-4">Évolution Mensuelle</h2>
      <hr class="border-t-2 border-gray-200 mb-4 md:mb-6">
      <div class="relative h-64 sm:h-80 md:h-96">
        <canvas id="dailyEvolutionChart"></canvas>
      </div>
    </div>

    <!-- Variation Chart -->
    <div class="bg-white rounded-xl shadow-sm p-4 md:p-6 mt-6">
      <h2 class="text-lg md:text-xl font-bold text-[#1E293B] mb-3 md:mb-4">Variation Mensuelle</h2>
      <hr class="border-t-2 border-gray-200 mb-4 md:mb-6">
      <div class="relative h-64 sm:h-80 md:h-96">
        <canvas id="variationChart"></canvas>
      </div>
    </div>
  </DashLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import DashLayout from '@/Layouts/DashLayout.vue';
import { Chart, registerables } from 'chart.js';
Chart.register(...registerables);

const props = defineProps({
  totalBalance: Number,
  freeMargin: Number,
  savings: Number,
  budgetDistributionData: Object,
  monthlySummaryData: Object
});

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('fr-FR', {
    style: 'currency',
    currency: 'MAD',
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  }).format(amount);
};

const initCharts = async () => {
  try {
    // Monthly Summary Bar Chart
    const monthlyCtx = document.getElementById('monthlySummaryChart');
    if (monthlyCtx) {
      new Chart(monthlyCtx, {
        type: 'bar',
        data: {
          labels: ['Revenus', 'Dépenses', 'Épargne'],
          datasets: [{
            data: [
              props.monthlySummaryData.budgets,
              props.monthlySummaryData.expenses,
              props.monthlySummaryData.savings
            ],
            backgroundColor: [
              '#5AE4A8', // Green for budgets/revenus
              '#EF4444', // Red for expenses
              '#3B82F6'  // Blue for savings
            ],
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  return `${context.label}: ${formatCurrency(context.raw)}`;
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
            }
          }
        }
      });
    }

    // Budget Distribution Doughnut Chart
    const distributionCtx = document.getElementById('budgetDistributionChart');
    if (distributionCtx) {
      new Chart(distributionCtx, {
        type: 'doughnut',
        data: {
          labels: Object.keys(props.budgetDistributionData),
          datasets: [{
            data: Object.values(props.budgetDistributionData),
            backgroundColor: [
              '#5AE4A8',
              '#3B82F6'
            ],
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'right',
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  return `${context.label}: ${formatCurrency(context.raw)}`;
                }
              }
            }
          }
        }
      });
    }

    // Daily Evolution Chart
    const dailyResponse = await axios.get(route('dashboard.daily-data'));
    const dailyData = dailyResponse.data;
    
    const dailyCtx = document.getElementById('dailyEvolutionChart');
    if (dailyCtx) {
      new Chart(dailyCtx, {
        type: 'line',
        data: {
          labels: dailyData.labels,
          datasets: [
            {
              label: 'Solde Total',
              data: dailyData.data.map(item => item.balance),
              borderColor: '#5AE4A8',
              backgroundColor: 'rgba(90, 228, 168, 0.1)',
              borderWidth: 2,
              tension: 0.4
            },
            {
              label: 'Marge Libre',
              data: dailyData.data.map(item => item.free_margin),
              borderColor: '#3B82F6',
              backgroundColor: 'rgba(59, 130, 246, 0.1)',
              borderWidth: 2,
              tension: 0.4
            },
            {
              label: 'Épargne',
              data: dailyData.data.map(item => item.savings),
              borderColor: '#EF4444',
              backgroundColor: 'rgba(239, 68, 68, 0.1)',
              borderWidth: 2,
              tension: 0.4
            }
          ]
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
                  return `${context.dataset.label}: ${formatCurrency(context.raw)}`;
                }
              }
            }
          },
          scales: {
            y: {
              beginAtZero: false,
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
    }

    // Variation Chart
    const variationCtx = document.getElementById('variationChart');
    if (variationCtx) {
      new Chart(variationCtx, {
        type: 'line',
        data: {
          labels: dailyData.labels,
          datasets: [
            {
              label: 'Variation Revenus',
              data: dailyData.data.map(item => item.budgets),
              borderColor: '#5AE4A8',
              backgroundColor: 'rgba(90, 228, 168, 0.1)',
              borderWidth: 2,
              tension: 0.4
            },
            {
              label: 'Variation Dépenses',
              data: dailyData.data.map(item => item.expenses),
              borderColor: '#EF4444',
              backgroundColor: 'rgba(239, 68, 68, 0.1)',
              borderWidth: 2,
              tension: 0.4
            }
          ]
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
                  return `${context.dataset.label}: ${formatCurrency(context.raw)}`;
                }
              }
            }
          },
          scales: {
            y: {
              beginAtZero: false,
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
    }

  } catch (error) {
    console.error('Error initializing charts:', error);
  }
};

onMounted(() => {
  initCharts();
});
</script>