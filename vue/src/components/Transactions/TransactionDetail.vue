<script setup>
import { ref, watch, computed } from 'vue'

const props = defineProps({
  transaction: {
    type: Object,
    required: true
  },
  operationType: {
    type: String,
    default: 'insert'  // insert / update
  },
  users: {
    type: Array,
    required: true
  },
  errors: {
    type: Object,
    required: false,
  },
})

const emit = defineEmits(['save', 'cancel'])

const editingTransaction = ref(props.transaction)

watch(
  () => props.transaction,
  (newTransaction) => {
    editingTransaction.value = newTransaction
  }
)

const transactionTitle = computed(() => {
  if (!editingTransaction.value) {
    return ''
  }
  return props.operationType == 'insert' ? 'New Transaction' : 'Transaction #' + editingTransaction.value.id
})

const save = async () => {
  emit('save', editingTransaction.value)
}

const cancel = () => {
  emit('cancel', editingTransaction.value)
}

</script>

<template>
  <form class="row g-3 needs-validation" novalidate @submit.prevent="save">
    <h3 class="mt-5 mb-3">{{ transactionTitle }}</h3>
    <hr>

    <div class="d-flex flex-wrap justify-content-between">
      <div class="mb-3 me-3 flex-grow-1">
        <label for="inputpayment_reference" class="form-label">Payment Reference</label>
        <input type="text" class="form-control" :class="{ 'is-invalid': errors ? errors['payment_reference'] : false }"
          id="inputpayment_reference" placeholder="" required v-model="editingTransaction.payment_reference">
        <field-error-message :errors="errors" fieldName="payment_reference"></field-error-message>
      </div>

      <div class="mb-3 ms-xs-3 flex-grow-1">
        <label for="inputpayment_type" class="form-label">Payment Type</label>
        <select class="form-select" :class="{ 'is-invalid': errors ? errors['type'] : false }" id="inputpayment_type" required
          v-model="editingTransaction.payment_type">
          <option :value="null"></option>
          <option value="V">vCard</option>
          <option value="M">MBway</option>
          <option value="P">Paypal</option>
          <option value="I">IBAN</option>
          <option value="B">MB</option>
          <option value="S">VISA</option>
        </select>
        <field-error-message :errors="errors" fieldName="payment_type"></field-error-message>
      </div>
    </div>
    
      <div class="mb-3 me-3 flex-grow-1">
        <label for="inputvalue" class="form-label">Mount</label>
        <input type="number" class="form-control" :class="{ 'is-invalid': errors ? errors['value'] : false }"
          id="inputvalue" required v-model="editingTransaction.value">
        <field-error-message :errors="errors" fieldName="value"></field-error-message>
      </div>

    <div class="mb-3">
      <label for="inputDescrition" class="form-label">Description</label>
        <input type="text" class="form-control" :class="{ 'is-invalid': errors ? errors['description'] : false }"
          id="inputdescription" placeholder="" v-model="editingTransaction.description">
        <field-error-message :errors="errors" fieldName="description"></field-error-message>
    </div>

    <div class="mb-3 d-flex justify-content-end">
      <button type="button" class="btn btn-primary px-5" @click="save">Save</button>
      <button type="button" class="btn btn-light px-5" @click="cancel">Cancel</button>
    </div>
  </form>
</template>

<style scoped>
.total_price {
  width: 26rem;
}

.checkBilled {
  margin-top: 0.4rem;
  min-height: 2.375rem;
}
</style>
