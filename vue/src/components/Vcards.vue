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
        </tr>
      </thead>
      <tbody>
        <tr v-for="vCard in vCards" :key="vCard.phone_number">
          <th scope="row">{{ vCard.phone_number }}</th>
          <td>{{ vCard.name }}</td>
          <td>{{ vCard.email }}</td>
          <td>{{ vCard.balance }}</td>
          <td><button>Editar</button></td>
          <td><button>Eliminar</button></td>
        </tr>
      </tbody>
    </table>
  </div>
  
</template>

