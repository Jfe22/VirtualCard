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

  const transactionTitle = computed(()=>{
    if (!editingTransaction.value) {
        return ''
      }
      return props.operationType == 'insert' ? 'New Transaction' : 'Transaction #' + editingTransaction.value.id
  })

  const save = () => {
      emit('save', editingTransaction.value)
  }

  const cancel = () => {
      emit('cancel', editingTransaction.value)
  }

</script>

<template>
  <form
    class="row g-3 needs-validation"
    novalidate
    @submit.prevent="save"
  >
    <h3 class="mt-5 mb-3">{{ transactionTitle }}</h3>
    <hr>

    <div class="mb-3">
      <label
        for="inputName"
        class="form-label"
      >Name</label>
      <input
        type="text"
        class="form-control"
        :class="{ 'is-invalid': errors ? errors['name'] : false }"
        id="inputName"
        placeholder="Transaction Name"
        required
        v-model="editingTransaction.name"
      >
      <field-error-message :errors="errors" fieldName="name"></field-error-message>
    </div>

    <div class="d-flex flex-wrap justify-content-between">
      <div class="mb-3 me-3 flex-grow-1">
        <label
          for="inputResponsible"
          class="form-label"
        >Responsible</label>
        <select
          class="form-select pe-2"
          :class="{ 'is-invalid': errors ? errors['responsible_id'] : false }"
          id="inputResponsible"
          v-model="editingTransaction.responsible_id"
        >
          <option :value="null">-- No Responsible --</option>
          <option
            v-for="user in users"
            :key="user.id"
            :value="user.id"
          >{{user.name}}</option>
        </select>
        <field-error-message :errors="errors" fieldName="responsible_id"></field-error-message>
      </div>

      <div class="mb-3 ms-xs-3 flex-grow-1">
        <label
          for="inputTransaction"
          class="form-label"
        >Status</label>
        <select
          class="form-select"
          :class="{ 'is-invalid': errors ? errors['status'] : false }"
          id="inputTransaction"
          v-model="editingTransaction.status"
        >
          <option :value="null"></option>
          <option value="P">Pending</option>
          <option value="W">Work In Progress</option>
          <option value="C">Completed</option>
          <option value="I">Interrupted</option>
          <option value="D">Discarded</option>
        </select>
        <field-error-message :errors="errors" fieldName="status"></field-error-message>
      </div>
    </div>

    <div class="d-flex flex-wrap justify-content-between">
      <div class="mb-3 me-3 flex-grow-1">
        <label
          for="inputPreview_start_date"
          class="form-label"
        >Preview Start Date</label>
        <input
          type="date"
          class="form-control"
          :class="{ 'is-invalid': errors ? errors['preview_start_date'] : false }"
          id="inputPreview_start_date"
          placeholder="Preview Start Date"
          v-model="editingTransaction.preview_start_date"
        >
        <field-error-message :errors="errors" fieldName="preview_start_date"></field-error-message>
      </div>

      <div class="mb-3 ms-xs-3 flex-grow-1">
        <label
          for="inputPreview_end_date"
          class="form-label"
        >Preview End Date</label>
        <input
          type="date"
          class="form-control"
          :class="{ 'is-invalid': errors ? errors['preview_end_date'] : false }"
          id="inputPreview_end_date"
          placeholder="Preview End Date"
          v-model="editingTransaction.preview_end_date"
        >
        <field-error-message :errors="errors" fieldName="preview_end_date"></field-error-message>
      </div>
    </div>

    <div class="d-flex flex-wrap justify-content-between">
      <div class="mb-3 me-3 flex-grow-1">
        <label
          for="inputReal_start_date"
          class="form-label"
        >Real Start Date</label>
        <input
          type="date"
          class="form-control"
          :class="{ 'is-invalid': errors ? errors['real_start_date'] : false }"
          id="inputReal_start_date"
          placeholder="Real Start Date"
          v-model="editingTransaction.real_start_date"
        >
        <field-error-message :errors="errors" fieldName="real_start_date"></field-error-message>
      </div>

      <div class="mb-3 ms-xs-3 flex-grow-1">
        <label
          for="inputReal_end_date"
          class="form-label"
        >Real End Date</label>
        <input
          type="date"
          class="form-control"
          :class="{ 'is-invalid': errors ? errors['real_end_date'] : false }"
          id="inputReal_end_date"
          placeholder="Real End Date"
          v-model="editingTransaction.real_end_date"
        >
        <field-error-message :errors="errors" fieldName="real_end_date"></field-error-message>
      </div>
    </div>

    <div class="mb-3">
      <label
        for="inputTotalHours"
        class="form-label"
      >Total Hours</label>
      <input
        type="number"
        class="form-control"
        :class="{ 'is-invalid': errors ? errors['total_hours'] : false }"
        id="inputTotalHours"
        v-model="editingTransaction.total_hours"
      >
      <field-error-message :errors="errors" fieldName="total_hours"></field-error-message>
    </div>

    <div class="d-flex flex-wrap justify-content-between">
      <div class="mb-3 checkBilled">
        <div class="form-check">
          <input
            class="form-check-input"
            :class="{ 'is-invalid': errors ? errors['billed'] : false }"
            type="checkbox"
            v-model="editingTransaction.billed"
            id="inputBilled"
          >
          <label
            class="form-check-label"
            for="inputBilled"
          >
          Transaction is Billed
          </label>
        </div>
        <field-error-message :errors="errors" fieldName="billed"></field-error-message>
      </div>
      <div
        class="row mb-3 total_price"
        v-show="editingTransaction.billed"
      >
        <label
          for="inputTotalPrice"
          class="col-sm-3 col-form-label"
        >Total Price</label>
        <div class="col-sm-9">
          <input
            type="number"
            class="form-control"
            :class="{ 'is-invalid': errors ? errors['total_price'] : false }"
            id="inputTotalPrice"
            v-model="editingTransaction.total_price"
          >
          <field-error-message :errors="errors" fieldName="total_price"></field-error-message>
        </div>
      </div>
    </div>

    <div class="mb-3 d-flex justify-content-end">
      <button
        type="button"
        class="btn btn-primary px-5"
        @click="save"
      >Save</button>
      <button
        type="button"
        class="btn btn-light px-5"
        @click="cancel"
      >Cancel</button>
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
