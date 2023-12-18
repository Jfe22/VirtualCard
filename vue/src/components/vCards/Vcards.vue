<script setup>

import axios from 'axios'
import { ref, onMounted,inject } from 'vue'
import { useUserStore } from '../../stores/user.js';

const vCards = ref([])
const userStore = useUserStore()
const socket = inject('socket')

const loadVcards = async () => {
  //only for admins?
  try {
    const response = await axios.get('vcards')
    console.log(response)
    vCards.value = response.data.data
  } catch (error) {
    console.error(error)
  }
}

const deleteVcard = async () => {
  if (userStore.user_type == 'A') {
    try {
      const response = await axios.delete('vcards/' + vCard.phone_number)
      console.log(response)
      socket.emit('deleteVCard')
      loadVcards()
    } catch (error) {
      console.error(error)
    }
  }
}

//socket deleteVCard
socket.on('deleteVCard', (vCard) => {
  vCards.value.push(vCard)
  toast.success(`VCard was deleted`)
})

const blockVcard = async () => {
  if (userStore.user_type == 'A') {
    try {
      //end point still TODO
      const response = await axios.patch('vcards/' + vCard.phone_number + '/block')
      console.log(response)
      socket.emit('vcardBlocked')
      loadVcards()
    } catch (error) {
      console.error(error)
    }
  }
}

//socket vcardBlocked
socket.on('vcardBlocked', (vCard) => {
  vCards.value.push(vCard)
  toast.success(`VCard was blocked`)
})

const unblockVcard = async () => {
  if (userStore.user_type == 'A') {
    try {
      //end point still TODO
      const response = await axios.patch('vcards/' + vCard.phone_number + '/block')
      console.log(response)
      socket.emit('vcardUnblocked')
      loadVcards()
    } catch (error) {
      console.error(error)
    }
  }
}

//socket vcardUnblocked
socket.on('vcardUnblocked', (vCard) => {
  vCards.value.push(vCard)
  toast.success(`VCard was unblocked`)
})


onMounted(() => {
  loadVcards()
})

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
          <td><button type="button" class="btn btn-success px-4 btn-editVcard">
            <router-link class="nav-link" :class="{ active: $route.name == 'User' && $route.params.id == 1 }" 
            :to="{ name: 'User', params: { id: 1 } }"> <!--Mudar para id de cada vCard-->
               <i class="bi bi-pencil"></i>&nbsp;
            </router-link>
        </button></td>
          <!--mudar botao para trocar entre bloquear e desbloquear-->
          <td><button type="button" class="btn btn-danger px-4 btn-blockVcard" @click="blockVcard">&nbsp;Block</button></td>
          <td><button type="button" class="btn btn-danger px-4 btn-deleteVcard" @click="deleteVcard">&nbsp;Delete</button></td>
        </tr>
      </tbody>
    </table>
  </div>
</template>



