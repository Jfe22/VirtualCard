<script setup>
import axios from 'axios'
import { useToast } from 'vue-toastification'
import {useRouter} from 'vue-router'
import { ref,computed ,onMounted } from 'vue'
import TransactionsTable from './TransactionsTable.vue'

const toast = useToast()
const router = useRouter()

const transactions = ref([])
const users = ref([])
const transactionToDelete = ref(null)
const deleteConfirmationDialog = ref(null)

const loadTransactions = async () => {
  try {
    const response = await axios.get('transactions')
    transactions.value = response.data.data
  } catch (error) {
    console.error(error)
  }
}

const loadUsers = async () => {
  try {
    const response = await axios.get('users')
    users.value = response.data.data
  } catch (error) {
    console.error(error)
  }
}

const addTransaction = () => {
  router.push({ name: 'NewTransaction' })
}

const editTransaction = (transaction) => {
  router.push({ name: 'Transaction', params: { id: transaction.id } })
}

const deleteTransaction = (transaction) => {
  transactionToDelete.value = transaction
  deleteConfirmationDialog.value.show()
}

const deleteTransactionConfirmed = async () => {
  try {
    const response = await axios.delete('transactions/' + transactionToDelete.value.id)
    let deletedTransaction = response.data.data
    let idx = transactions.value.findIndex((t) => t.id === deletedTransaction.id)
    if (idx >= 0) {
      transactions.value.splice(idx, 1)
    }
    toast.info(`Transaction ${transactionToDeleteDescription.value} was deleted`)
  } catch (error) {
    console.log(error)
    toast.error(`It was not possible to delete Transaction ${transactionToDeleteDescription.value}!`)
  }  
}

onMounted(() => {
  loadTransactions()
})
</script>

<template>
  <div class="mx-2 mt-2">
    <h1>Transactions History</h1>
    <button type="button" class="btn btn-success px-4 btn-addtask" @click="addTransaction"><i
        class="bi bi-xs bi-plus-circle"></i>&nbsp; Add Transaction</button>
  </div>
  <div>
    <hr>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">Mount</th>
          <th scope="col">Type</th>
          <th scope="col">Reference</th>
          <th scope="col">Classification</th>
          <th scope="col">Description</th>
          <th scope="col"></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="transaction in transactions" :key="transactions.id">
          <th scope="row">{{ transaction.value }}</th>
          <td>{{ transaction.payment_type }}</td>
          <td>{{ transaction.payment_reference }}</td>
          <td>{{ transaction.type }}</td>
          <td>{{ transaction.description }}</td>
          <td>
            <button type="button" class="btn btn-success px-4 btn-addtask" @click="editTransaction">&nbsp;Edit</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <nav>
    <ul class="pagination">
      <li class="page-item"><a class="page-link" href="#">Previous</a></li>
      <li class="page-item"><a class="page-link" href="#">1</a></li>
      <li class="page-item"><a class="page-link" href="#">2</a></li>
      <li class="page-item"><a class="page-link" href="#">3</a></li>
      <li class="page-item"><a class="page-link" href="#">Next</a></li>
    </ul>
  </nav>
</template>

<style scoped>
.btn-addtask {
  margin-top: 1.85rem;
}
</style>