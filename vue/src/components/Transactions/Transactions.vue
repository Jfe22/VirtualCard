<script setup>
import axios from 'axios'
import { useToast } from 'vue-toastification'
import { useRouter } from 'vue-router'
import { ref, onMounted, inject } from 'vue'
import TransactionsTable from './TransactionsTable.vue'
import { useUserStore } from '../../stores/user.js'

const toast = useToast()
const router = useRouter()

const transactions = ref([])
const users = ref([])
const transactionToDelete = ref(null)

const userStore = useUserStore();
const socket = inject('socket')

socket.on('newTransaction', (transaction) => {
  console.log('websocket running')
  transactions.value.push(transaction)
})

const loadTransactions = async () => {
  try {
    if (userStore.user) {
      if (userStore.user.user_type == "A") { 
        const response = await axios.get('transactions')
        transactions.value = response.data.data
      }
      else {
        const response = await axios.get('vcards/' + userStore.user.username + '/transactions')
        transactions.value = response.data.data
      }
    } 
  } catch (error) {
    console.error(error)
  }
}

const deleteTransaction = async (transaction) => {
  if (userStore.user_type === 'A') {
    try {
      if (transaction && transaction.id) {
        // Agora, podemos prosseguir com a exclusão usando o ID da transação
        const deleteResponse = await axios.delete(`transactions/${transaction.id}`);
        console.log(deleteResponse);

        socket.emit('deleteTransaction', transaction);
        loadTransactions();

        toast.success(`Transaction ${transaction.id} was deleted`);
      } else {
        console.error('Transaction ID is undefined or null');
        toast.error(`Failed to delete the transaction.`);
      }
    } catch (error) {
      console.error(error);
      toast.error(`Failed to delete the transaction.`);
    }
  }
};

onMounted(() => {
  loadTransactions()
})
</script>

<template>
  <div class="mx-2 mt-2">
    <h1>Transactions History</h1>
    <button type="button" class="btn btn-success px-4 btn-addtask">
      <router-link class="nav-link" :to="{ name: 'NewTransaction' }">
        Add Transaction
      </router-link>
    </button>
  </div>
  <div>
    <hr>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">Vcard</th>
          <th scope="col">Mount</th>
          <th scope="col">Type</th>
          <th scope="col">Reference</th>
          <th scope="col">Classification</th>
          <th scope="col">Date</th>
          <th scope="col">Description</th>
          <th scope="col"></th>
          <th scope="col"></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="transaction in transactions" :key="transactions.id">
          <th scope="row">{{ transaction.vcard}}</th>
          <td>{{ transaction.value}}</td>
          <td>{{ transaction.payment_type }}</td>
          <td>{{ transaction.payment_reference }}</td>
          <td>{{ transaction.type }}</td>
          <td>{{ transaction.date}}</td>
          <td>{{ transaction.description }}</td>
          <td >
            <button type="button" class="btn btn-success px-4 btn-editTransaction"
              @click="editTransaction">&nbsp;Edit</button>
          </td>
          <td>
            <button type="button" class="btn btn-danger px-4 btn-deleteTransaction"
              @click="deleteTransaction(transaction)">&nbsp;Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>