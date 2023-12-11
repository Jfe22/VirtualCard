<script setup>

  import axios from 'axios'
  import { ref, onMounted } from 'vue'

  const transactions = ref([])

  const newTransactionParams = ref({
    id: '',   //como chegar ao metodo do lara que gera next id? 
    vcard: '', 
    date: '', 
    datetime: '',
    type: '',
    old_balance:'',
    new_balance: '', 
    payment_type: '',
    payment_reference: '',
    pair_transaction: '',
    pair_vcard: '',
    category_id: '',
    description: ''
  })

  const loadTransactions = async () => {
    try {
      const response = await axios.get('transactions')
      console.log(response)
      transactions.value = response.data.data
    } catch (error) {
      console.error(error)
    }
  }



  const emit = defineEmits(['registerNewTransaction'])


  const registerNewTransaction = async () => {
    try {
      const response = await axios.post('transactions', newTransactionParams.value)
      console.log(response)
      // here we have to find a way to call http patch requests to update vcards balance
      // const response = await axios.patch('vcards/' + newTransactionParams.value.vcard, newTransactionParams.value.new_balance)
      toast.success('Transaction ' + newTransactionParams.value.id + ' completed successfully.')
      emit('registerNewTransaction')
      router.back()
    } catch (error) {
      console.log(error)
      toast.error('error while registering!')
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

