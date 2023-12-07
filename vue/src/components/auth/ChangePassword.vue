<script setup>
import { useToast } from "vue-toastification"
import { useRouter } from 'vue-router'
import { useUserStore } from '../../stores/user.js'
import { ref } from 'vue'

const toast = useToast()
const router = useRouter()
const userStore = useUserStore()

const passwords = ref({
  current_password: '',
  password: '',
  password_confirmation: ''
})

const errors = ref(null)

const emit = defineEmits(['changedPassword'])

const changePassword = async () => {
  try {
    await userStore.changePassword(passwords.value)
    toast.success('Password has been changed.')
    emit('changedPassword')
    router.back()
  } catch (error) {
    if (error.response.status == 422) {
      errors.value = error.response.data.errors
      toast.error('Password has not been changed due to validation errors!')
    } else {
      toast.error('Password has not been changed due to unknown server error!')
    }
  }
}
</script>

<template>
  <form class="row g-3 needs-validation" novalidate @submit.prevent="changePassword">
    <h3 class="mt-5 mb-3">Change Password</h3>
    <hr>
    <div class="mb-3">
      <div class="mb-3">
        <label for="inputCurrentPassword" class="form-label">Current Password</label>
        <input type="password" class="form-control" id="inputCurrentPassword" required
          v-model="passwords.current_password">
        <field-error-message :errors="errors" fieldName="current_password"></field-error-message>
      </div>
    </div>
    <div class="mb-3">
      <div class="mb-3">
        <label for="inputPassword" class="form-label">New Password</label>
        <input type="password" class="form-control" id="inputPassword" required v-model="passwords.password">
        <field-error-message :errors="errors" fieldName="password"></field-error-message>
      </div>
    </div>
    <div class="mb-3">
      <div class="mb-3">
        <label for="inputPasswordConfirm" class="form-label">Password Confirmation</label>
        <input type="password" class="form-control" id="inputPasswordConfirm" required
          v-model="passwords.password_confirmation">
        <field-error-message :errors="errors" fieldName="password_confirmation"></field-error-message>
      </div>
    </div>
    <div class="mb-3 d-flex justify-content-center">
      <button type="button" class="btn btn-primary px-5" @click="changePassword">Change Password</button>
    </div>
  </form>
</template>

