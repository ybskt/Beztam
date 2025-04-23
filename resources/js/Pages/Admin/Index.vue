<template>
  <AdminLayout>
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6 mb-6">
      <!-- Users Card -->
      <div class="bg-[#F8F8F9] rounded-xl shadow-sm border border-gray-200 p-4 md:p-6 text-center">
        <p class="text-[#5AE4A8] text-sm font-bold">Nombre d'utilisateurs</p>
        <p class="mt-2 text-lg md:text-xl font-semibold">{{ stats.total_users }}</p>
      </div>

      <!-- Traffic Card -->
      <div class="bg-[#F8F8F9] rounded-xl shadow-sm border border-gray-200 p-4 md:p-6 text-center">
        <p class="text-[#5AE4A8] text-sm font-bold">Pages vues ce mois</p>
        <p class="mt-2 text-lg md:text-xl font-semibold">{{ stats.monthly_traffic }}</p>
      </div>

      <!-- Rating Card -->
      <div class="bg-[#F8F8F9] rounded-xl shadow-sm border border-gray-200 p-4 md:p-6 text-center">
        <p class="text-[#5AE4A8] text-sm font-bold">Satisfaction clients</p>
        <div class="mt-2 flex items-center justify-center">
          <div class="flex mr-2">
            <template v-for="i in 5" :key="i">
              <span v-if="i <= Math.floor(stats.average_rating)" class="text-yellow-400 text-lg">★</span>
              <span v-else-if="i - 0.5 <= stats.average_rating" class="text-yellow-400 text-lg">½</span>
              <span v-else class="text-gray-300 text-lg">★</span>
            </template>
          </div>
          <span class="text-lg md:text-xl font-semibold">{{ stats.average_rating.toFixed(1) }}</span>
          <span class="ml-1 text-gray-500 text-xs">({{ stats.total_ratings }})</span>
        </div>
      </div>
    </div>

    <!-- User Registrations Chart -->
    <div class="bg-[#F8F8F9] rounded-xl shadow-sm p-6 border border-gray-200 mb-8">
      <h3 class="text-lg font-semibold text-secondary mb-4">Nouveaux utilisateurs (30 jours)</h3>
      <div class="h-64 md:h-80">
        <canvas ref="userChartCanvas"></canvas>
      </div>
    </div>

    <!-- Page Views Chart -->
    <div class="bg-[#F8F8F9] rounded-xl shadow-sm p-6 border border-gray-200 mb-8">
      <h3 class="text-lg font-semibold text-secondary mb-4">Pages vues (30 jours)</h3>
      <div class="h-64 md:h-80">
        <canvas ref="pageViewsChartCanvas"></canvas>
      </div>
    </div>

    <!-- Popular Pages -->
    <div class="bg-[#F8F8F9] rounded-xl shadow-sm p-6 border border-gray-200">
      <h3 class="text-lg font-semibold text-secondary mb-4">Pages les plus visitées</h3>
      <div class="space-y-3">
        <div v-for="(page, index) in stats.popular_pages" :key="index" class="flex justify-between items-center">
          <span class="text-gray-700">{{ page.url }}</span>
          <span class="font-semibold">{{ page.views }} vues</span>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref, onMounted } from 'vue';
import { Chart, registerables } from 'chart.js';

// Register Chart.js components
Chart.register(...registerables);

const props = defineProps({
  stats: {
    type: Object,
    required: true,
    default: () => ({
      total_users: 0,
      monthly_traffic: 0,
      average_rating: 0,
      total_ratings: 0,
      user_registrations: [],
      page_views: [],
      popular_pages: []
    })
  }
});

const userChartCanvas = ref(null);
const pageViewsChartCanvas = ref(null);
let userChartInstance = null;
let pageViewsChartInstance = null;

onMounted(() => {
  if (userChartCanvas.value) {
    renderUserChart();
  }
  if (pageViewsChartCanvas.value) {
    renderPageViewsChart();
  }
});

const renderUserChart = () => {
  if (userChartInstance) {
    userChartInstance.destroy();
  }

  const ctx = userChartCanvas.value.getContext('2d');
  userChartInstance = new Chart(ctx, {
    type: 'line',
    data: {
      labels: props.stats.user_registrations.map(item => {
        const date = new Date(item.date);
        return date.toLocaleDateString('fr-FR', {day: 'numeric', month: 'short'});
      }),
      datasets: [{
        label: 'Nouveaux utilisateurs',
        data: props.stats.user_registrations.map(item => item.count),
        backgroundColor: 'rgba(16, 185, 129, 0.1)',
        borderColor: '#10B981',
        borderWidth: 2,
        tension: 0.3,
        fill: true
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
          grid: {
            display: true,
            color: 'rgba(0, 0, 0, 0.05)'
          },
          ticks: {
            stepSize: 1
          }
        },
        x: {
          grid: {
            display: false
          },
          ticks: {
            maxRotation: 90,
            minRotation: 90,
            padding: 10
          }
        }
      },
      plugins: {
        legend: {
          display: false
        }
      }
    }
  });
};

const renderPageViewsChart = () => {
  if (pageViewsChartInstance) {
    pageViewsChartInstance.destroy();
  }

  const ctx = pageViewsChartCanvas.value.getContext('2d');
  pageViewsChartInstance = new Chart(ctx, {
    type: 'line',
    data: {
      labels: props.stats.page_views.map(item => {
        const date = new Date(item.date);
        return date.toLocaleDateString('fr-FR', {day: 'numeric', month: 'short'});
      }),
      datasets: [{
        label: 'Pages vues',
        data: props.stats.page_views.map(item => item.count),
        backgroundColor: 'rgba(59, 130, 246, 0.1)',
        borderColor: '#3B82F6',
        borderWidth: 2,
        tension: 0.3,
        fill: true
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
          grid: {
            display: true,
            color: 'rgba(0, 0, 0, 0.05)'
          },
          ticks: {
            stepSize: 1
          }
        },
        x: {
          grid: {
            display: false
          },
          ticks: {
            maxRotation: 90,
            minRotation: 90,
            padding: 10
          }
        }
      },
      plugins: {
        legend: {
          display: false
        }
      }
    }
  });
};
</script>
