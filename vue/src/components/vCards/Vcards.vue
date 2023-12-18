<script setup>

import axios from 'axios';
import { ref, onMounted, inject } from 'vue';
import { useUserStore } from '../../stores/user.js';
import { useToast } from 'vue-toastification';

const vCards = ref([]);
const userStore = useUserStore();
const toast = useToast();
const socket = inject('socket');

const loadVcards = async () => {
  try {
    const response = await axios.get('vcards');
    vCards.value = response.data.data;
  } catch (error) {
    console.error(error);
  }
};

const deleteVcard = async (vCard) => {
  if (userStore.user_type === 'A') {
    try {
      // Antes de excluir, definir o saldo do VCard para zero
      await axios.patch(`vcards/${vCard.phone_number}/balance`, { balance: 0 });

      // Agora, excluir o VCard
      const response = await axios.delete(`vcards/${vCard.phone_number}`);
      console.log(response);
      toast.success(`VCard with phone_number ${vCard.phone_number} was deleted`);
      socket.emit('deleteVCard', vCard);
      loadVcards();
    } catch (error) {
      console.error(error);
    }
  }
};

socket.on('deleteVCard', (vCard) => {
  toast.success(`VCard with phone_number ${vCard.phone_number} was deleted`);
});

const blockVcard = async (vCard) => {
  if (userStore.user_type === 'A') {
    try {
      const response = await axios.patch(`vcards/${vCard.phone_number}/block`);
      console.log(response);
      socket.emit('vcardBlocked', vCard);
      loadVcards();
    } catch (error) {
      console.error(error);
    }
  }
};

socket.on('vcardBlocked', (vCard) => {
  toast.success(`VCard with phone_number ${vCard.phone_number} was blocked`);
});

const unblockVcard = async (vCard) => {
  if (userStore.user_type === 'A') {
    try {
      const response = await axios.patch(`vcards/${vCard.phone_number}/unblock`);
      console.log(response);
      socket.emit('vcardUnblocked', vCard);
      loadVcards();
    } catch (error) {
      console.error(error);
    }
  }
};

socket.on('vcardUnblocked', (vCard) => {
  toast.success(`VCard with phone_number ${vCard.phone_number} was unblocked`);
});

onMounted(() => {
  loadVcards();
});

</script>

<template>
  <div>
    <h1>VCards</h1>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">Phone Number</th>
          <th scope="col">Name</th>
          <th scope="col">Email</th>
          <th scope="col">Balance</th>
          <th scope="col">Blocked</th>
          <th scope="col"></th>
          <th scope="col"></th>
          <th scope="col"></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="vCard in vCards" :key="vCard.phone_number">
          <th scope="row">{{ vCard.phone_number }}</th>
          <td>{{ vCard.name }}</td>
          <td>{{ vCard.email }}</td>
          <td>{{ vCard.balance }}</td>
          <td>{{ vCard.blocked }}</td>
          <td>
            <button type="button" class="btn btn-success px-4 btn-editVcard">
              <router-link class="nav-link" :class="{ active: $route.name == 'User' && $route.params.id == 1 }" 
              :to="{ name: 'User', params: { id: vCard.phone_number } }">
                <i class="bi bi-pencil"></i>&nbsp;
              </router-link>
            </button>
          </td>
          <td><button type="button" class="btn btn-danger px-4 btn-blockVcard" @click="blockVcard(vCard)">&nbsp;Block</button></td>
          <td><button type="button" class="btn btn-danger px-4 btn-blockVcard" @click="unblockVcard(vCard)">&nbsp;Unblock</button></td>
          <td><button type="button" class="btn btn-danger px-4 btn-deleteVcard" @click="deleteVcard(vCard)">&nbsp;Delete</button></td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
