<script setup>

  import axios from 'axios'
  import { ref, onMounted } from 'vue'

  const transactions = ref([])

  const loadTransactions = async () => {
    try {
      const response = await axios.get('transactions')
      console.log(response)
      transactions.value = response.data.data
    } catch (error) {
      console.error(error)
    }
  }

  onMounted(() => {
    loadTransactions()
  })

</script>

<template>
  <div>
    <h1>Transactions History</h1>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">Mount</th>
          <th scope="col">Type</th>
          <th scope="col">Reference</th>
          <th scope="col">Classification</th>
          <th scope="col">Description</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="transaction in transactions" :key="transactions.id">
          <th scope="row">{{ transactions.id}}</th>
          <td>{{ transaction.value }}</td>
          <td>{{ transaction.payment_type }}</td>
          <td>{{ transaction.payment_reference }}</td>
          <td>{{ transaction.type }}</td>
          <td>{{ transaction.description }}</td> 
        </tr>
      </tbody>
    </table>
  </div>
</template>

