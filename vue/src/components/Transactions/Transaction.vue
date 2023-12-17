<script setup>
import axios from 'axios'
import { useToast } from "vue-toastification"
import { useRouter, onBeforeRouteLeave } from 'vue-router'
import { useUserStore } from '../../stores/user'

import { ref, watch, computed, onMounted, inject } from 'vue'
import TransactionDetail from "./TransactionDetail.vue"

const toast = useToast()
const router = useRouter()
const userStore = useUserStore()

const newTransaction = () => {
  return {
    //id: '',   
    vcard: '',
    //date: '',
    //datetime: '',
    type: '',
    value: '',
    //old_balance: '',
    //new_balance: '',
    payment_type: '',
    payment_reference: '',
    //pair_transaction: '',
    //pair_vcard: '',
    category_id: '',
    description: '',
    is_pair: '',
  }
}

const transaction = ref(newTransaction())
const pair_transaction = ref(newTransaction())
const users = ref([])
const errors = ref(null)
const confirmationLeaveDialog = ref(null)
// devia estar numa transaction store??
const socket = inject('socket')
// String with the JSON representation after loading the transaction (new or edit)
let originalValueStr = ''


const loadTransaction = async (id) => {
  originalValueStr = ''
  errors.value = null
  if (!id || (id < 0)) {
    transaction.value = newTransaction()
    originalValueStr = JSON.stringify(transaction.value)
  } else {
    try {
      const response = await axios.get('transactions/' + id)
      transaction.value = response.data.data
      originalValueStr = JSON.stringify(transaction.value)
    } catch (error) {
      console.log(error)
    }
  }
}

const save = async () => {
  errors.value = null
  if (operation.value == 'insert') {
    try {


      //creates the normal transaction
      //transaction.value.vcard = userStore.userId
      console.log('user store id val')
      console.log(userStore.user.username)
      console.log('user store id val')
      transaction.value.vcard = userStore.user.username
      transaction.value.is_pair = false
      transaction.value.type = 'D'
      const response = await axios.post('transactions', transaction.value)
      transaction.value = response.data.data

      //patch the user vcard balance
      const responsePatch = await axios.patch('vcards/' + transaction.value.vcard + '/balance', transaction.value.new_balance)

      //creates the pair transaction
      if (transaction.value.payment_type == 'VCARD') {
        pair_transaction.value.vcard = transaction.value.pair_vcard

        if (transaction.value.type == 'D')
          pair_transaction.value.type = 'C'
        else 
          pair_transaction.value.type = 'D'

        pair_transaction.value.value = transaction.value.value
        pair_transaction.value.payment_type = 'VCARD'
        pair_transaction.value.payment_reference = transaction.value.vcard
        pair_transaction.value.is_pair = true

        const responsePairT = await axios.post('transactions', pair_transaction.value)
        pair_transaction.value = responsePairT.data.data

        //patch the pair vcard balance
        const responsePatchPair = await axios.patch('vcards/' + pair_transaction.value.vcard + '/balance', pair_transaction.value.new_balance)
      }


      socket.emit('newTransaction', transaction.value)


      originalValueStr = JSON.stringify(transaction.value)
      toast.success('transaction #' + transaction.value.id + ' was created successfully.')
      router.back()
    } catch (error) {
      if (error.response.status == 422) {
        errors.value = error.response.data.errors
        toast.error('transaction was not created due to validation errors!')
      } else {
        toast.error('transaction was not created due to unknown server error!')
      }
    }
  } else {
    try {
      const response = await axios.put('transactions/' + props.id, transaction.value)
      transaction.value = response.data.data
      originalValueStr = JSON.stringify(transaction.value)
      toast.success('transaction #' + transaction.value.id + ' was updated successfully.')
      router.back()
    } catch (error) {
      if (error.response.status == 422) {
        errors.value = error.response.data.errors
        toast.error('transaction #' + props.id + ' was not updated due to validation errors!')
      } else {
        toast.error('transaction #' + props.id + ' was not updated due to unknown server error!')
      }
    }
  }
}

const cancel = () => {
  originalValueStr = JSON.stringify(transaction.value)
  router.back()
}

const props = defineProps({
  id: {
    type: Number,
    default: null
  }
})

const operation = computed(() => {
  return (!props.id || props.id < 0) ? 'insert' : 'update'
})

watch(
  () => props.id,
  (newValue) => {
    loadTransaction(newValue)
  }, {
  immediate: true,
}
)

let nextCallBack = null
const leaveConfirmed = () => {
  if (nextCallBack) {
    nextCallBack()
  }
}

onBeforeRouteLeave((to, from, next) => {
  nextCallBack = null
  let newValueStr = JSON.stringify(transaction.value)
  if (originalValueStr != newValueStr) {
    // Some value has changed - only leave after confirmation
    nextCallBack = next
    confirmationLeaveDialog.value.show()
  } else {
    // No value has changed, so we can leave the component without confirming
    next()
  }
})

onMounted(async () => {
  users.value = []
  try {
    const response = await axios.get('users')
    users.value = response.data.data
  } catch (error) {
    console.log(error)
  }
})
</script>


<template>
  <confirmation-dialog ref="confirmationLeaveDialog" confirmationBtn="Discard changes and leave"
    msg="Do you really want to leave? You have unsaved changes!" @confirmed="leaveConfirmed">
  </confirmation-dialog>
  <transaction-detail :transaction="transaction" :operationType="operation" :users="users" :errors="errors" @save="save"
    @cancel="cancel"></transaction-detail>
</template>