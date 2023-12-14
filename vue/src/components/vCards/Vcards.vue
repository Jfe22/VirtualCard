<script setup>

import axios from 'axios'
import { ref, onMounted } from 'vue'

const vCards = ref([])

const loadVcards = async () => {
  try {
    const response = await axios.get('vcards')
    console.log(response)
    vCards.value = response.data.data
  } catch (error) {
    console.error(error)
  }
}

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
            <router-link class="nav-link" :class="{ active: $route.name === 'EditVcards' }" :to="{ name: 'EditVcards' }">
               <i class="bi bi-pencil"></i>&nbsp;
            </router-link>
        </button></td>
          <td><button type="button" class="btn btn-danger px-4 btn-blockVcard" @click="blockVcard">&nbsp;Block</button></td>
          <td><button type="button" class="btn btn-danger px-4 btn-deleteVcard" @click="deleteVcard">&nbsp;Delete</button></td>
        </tr>
      </tbody>
    </table>
  </div>
</template>



