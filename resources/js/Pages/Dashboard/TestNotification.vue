<template>
    <DashLayout>
      <!-- Stats Card -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-4 md:p-6 text-center">
          <p class="text-[#5AE4A8] text-sm font-bold">Total Notifications</p>
          <p class="mt-2 text-lg md:text-xl font-semibold">{{ notifications.length }}</p>
        </div>
      </div>
  
      <!-- Notifications List -->
      <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
        <h2 class="text-lg md:text-xl font-bold text-[#1E293B] mb-3 md:mb-4">Test Notifications</h2>
        <hr class="border-t-2 border-gray-200 mb-4 md:mb-6">
  
        <div class="space-y-3">
          <div v-for="notification in notifications" :key="notification.id" class="p-3 border border-[#D1FAE5] rounded-lg">
            <p class="font-medium">{{ notification.notification }}</p>
            <p class="text-sm text-gray-500">{{ notification.type }} - {{ notification.created_at }}</p>
          </div>
          
          <div v-if="notifications.length === 0" class="text-center py-4 text-gray-500">
            No notifications found
          </div>
        </div>
  
        <!-- Test Buttons -->
        <div class="mt-6 space-y-4">
          <button @click="createTestNotification('limit')" class="px-4 py-2 bg-blue-100 text-blue-800 rounded">
            Create Limit Notification
          </button>
          <button @click="createTestNotification('message')" class="px-4 py-2 bg-green-100 text-green-800 rounded">
            Create Message Notification
          </button>
          <button @click="clearNotifications" class="px-4 py-2 bg-red-100 text-red-800 rounded">
            Clear All
          </button>
        </div>
      </div>
    </DashLayout>
  </template>
  
  <script setup>
  import { Link } from '@inertiajs/vue3';
  import { reactive, onMounted } from 'vue';
  import DashLayout from '@/Layouts/DashLayout.vue';
  
  const props = defineProps({
    notifications: {
      type: Array,
      default: () => []
    }
  });
  
  const createTestNotification = async (type) => {
    try {
      const messages = {
        limit: 'Budget limit reached for category',
        message: 'New message from support team'
      };
      
      await axios.post('/test-notifications', {
        type: type,
        message: `${messages[type]} - ${new Date().toLocaleTimeString()}`
      });
      
      window.location.reload();
    } catch (error) {
      console.error('Error creating notification:', error);
    }
  };
  
  const clearNotifications = async () => {
    try {
      await axios.delete('/test-notifications');
      window.location.reload();
    } catch (error) {
      console.error('Error clearing notifications:', error);
    }
  };
  </script>