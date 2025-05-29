<template>
    <div class="chat-container">
      <button @click="toggleChat" class="chat-toggle" :style="{ background: '#5AE4A8' }">
        {{ isOpen ? '×' : '💬' }}
      </button>
  
      <div v-if="isOpen" class="chat-window">
        <div class="chat-header">
          <h3>Assistant IA</h3>
          <button @click="toggleChat" class="close-btn">×</button>
        </div>
        
        <div class="chat-messages" ref="messagesContainer">
          <div v-for="(msg, i) in messages" :key="i" :class="['message', msg.role]">
            {{ msg.content }}
          </div>
          <div v-if="loading" class="message assistant">Je réfléchis...</div>
        </div>
        
        <form @submit.prevent="sendMessage" class="chat-input">
          <input v-model="inputMessage" placeholder="Tapez votre message..." />
          <button type="submit" :disabled="loading">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#5AE4A8">
              <path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/>
            </svg>
          </button>
        </form>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, nextTick, onMounted } from 'vue';
  import axios from 'axios';
  
  // No props needed since we're using a single route for all users
  const isOpen = ref(false);
  const inputMessage = ref('');
  const messages = ref([{ role: 'assistant', content: 'Bonjour ! Comment puis-je vous aider ?' }]);
  const loading = ref(false);
  const messagesContainer = ref(null);
  
  // Toggle chat window
  const toggleChat = () => isOpen.value = !isOpen.value;
  
  // Use localStorage to persist chat state across page refreshes
  onMounted(() => {
    // Try to load previous messages from localStorage
    try {
      const savedMessages = localStorage.getItem('ollama_chat_messages');
      if (savedMessages) {
        messages.value = JSON.parse(savedMessages);
      }
      
      // Scroll to bottom on mount
      if (messagesContainer.value) {
        messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
      }
    } catch (e) {
      console.error('Error loading saved messages:', e);
    }
  });
  
  // Send message function
  const sendMessage = async () => {
    if (!inputMessage.value.trim()) return;
    
    const userMsg = inputMessage.value;
    messages.value.push({ role: 'user', content: userMsg });
    inputMessage.value = '';
    loading.value = true;
    
    // Save current messages to localStorage
    try {
      localStorage.setItem('ollama_chat_messages', JSON.stringify(messages.value));
    } catch (e) {
      console.error('Error saving messages:', e);
    }
    
    try {
      // Format conversation history for the API
      const history = messages.value
        .filter(m => m.role !== 'system')
        .map((m, i, arr) => {
          if (m.role === 'user') {
            return {
              user: m.content,
              assistant: null
            };
          } else if (m.role === 'assistant' && i > 0 && arr[i-1]?.role === 'user') {
            return {
              user: arr[i-1].content,
              assistant: m.content
            };
          }
          return null;
        })
        .filter(m => m !== null && (m.user || m.assistant));
  
      // Use a single endpoint for all users
      const response = await axios.post('/ollama/chat', {
        message: userMsg,
        history: history,
        language: 'fr' // Specify French language
      }, {
        withCredentials: true,
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        }
      });
      
      // Process the response
      if (response.data && (response.data.response || response.data.message)) {
        const responseText = response.data.response || response.data.message;
        messages.value.push({ role: 'assistant', content: responseText });
        
        // Save updated messages to localStorage
        localStorage.setItem('ollama_chat_messages', JSON.stringify(messages.value));
      } else {
        throw new Error('Format de réponse invalide');
      }
    } catch (err) {
      console.error('Chat error:', err);
      
      // Error message in French
      messages.value.push({ 
        role: 'assistant', 
        content: 'Désolé, j\'ai rencontré une erreur. Veuillez réessayer.' 
      });
      
      // Log detailed error information
      if (err.response) {
        console.error('Server response:', err.response.data);
        console.error('Status:', err.response.status);
      }
    } finally {
      loading.value = false;
      nextTick(() => {
        if (messagesContainer.value) {
          messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
        }
      });
    }
  };
  </script>
  
  <style scoped>
  .chat-container {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1000;
  }
  .chat-toggle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    border: none;
    color: white;
    font-size: 24px;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  }
  .chat-window {
    width: 350px;
    height: 450px;
    background: #F8F8F9;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    margin-bottom: 15px;
    overflow: hidden;
    animation: fadeIn 0.3s ease;
  }
  .chat-header {
    background: #5AE4A8;
    color: white;
    padding: 12px;
    display: flex;
    justify-content: space-between;
  }
  .close-btn {
    background: none;
    border: none;
    color: white;
    font-size: 24px;
    cursor: pointer;
  }
  .chat-messages {
    height: calc(100% - 120px);
    overflow-y: auto;
    padding: 12px;
    background: white;
  }
  .message {
    margin: 8px 0;
    padding: 10px 15px;
    border-radius: 18px;
    max-width: 80%;
  }
  .message.user {
    margin-left: auto;
    background: #D1FAE5;
    color: #065F46;
  }
  .message.assistant {
    margin-right: auto;
    background: #F8F8F9;
    border: 1px solid #E5E7EB;
  }
  .chat-input {
    display: flex;
    padding: 10px;
    background: white;
    border-top: 1px solid #E5E7EB;
  }
  .chat-input input {
    flex: 1;
    padding: 10px 15px;
    border: 1px solid #E5E7EB;
    border-radius: 20px;
    margin-right: 10px;
  }
  .chat-input button {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: none;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
  }
  .chat-input button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
  }
  </style>