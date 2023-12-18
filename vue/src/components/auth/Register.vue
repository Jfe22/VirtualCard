<script setup>
import axios from 'axios'
import { useToast } from 'vue-toastification'
import { useRouter } from 'vue-router'
import { ref,inject } from 'vue'
import { useUserStore } from '../../stores/user.js'

const toast = useToast()
const router = useRouter()
const userStore = useUserStore()
const socket = inject('socket')

const credentials = ref({
  phone_number: '',
  name: '',
  email: '',
  photo_url: '',
  password: '',
  confirmation_code: '',
  blocked: 0,
  balance: 0,
  max_debit: 10000
})

const emit = defineEmits(['register'])

const register = async () => {
  try {
    const response = await axios.post('vcards', credentials.value)
    console.log(response)
    //credentials.value = response.data.data
    toast.success('User ' + credentials.username + ' has registered successfully.')
    socket.emit('newVCard')
    router.back()
  } catch (error) {
    console.log(error)
    toast.error('error while registering!')

  }
}

socket.on('newVCard', (vcard) => {
  credentials.value.push(vcard)
  toast.success(`A new vcard was created`)
})

</script>

<template>
  <form class="row g-3 needs-validation" novalidate @submit.prevent="login">
    <h3 class="mt-5 mb-3">Create a new vCard</h3>
    <hr>
    <div class="mb-3">
      <div class="mb-3">
        <label for="inputphone_number" class="form-label">Phone Number</label>
        <input type="text" class="form-control" id="inputphone_number" required v-model="credentials.phone_number">
      </div>
    </div>
    <div class="mb-3">
      <div class="mb-3">
        <label for="inputName" class="form-label">Name</label>
        <input type="text" class="form-control" id="inputName" required v-model="credentials.name">
      </div>
    </div>
    <div class="mb-3">
      <div class="mb-3">
        <label for="inputEmail" class="form-label">Email</label>
        <input type="text" class="form-control" id="inputEmail" required v-model="credentials.email">
      </div>
    </div>
    <div class="mb-3">
      <div class="mb-3">
        <label for="inputPassword" class="form-label">Password</label>
        <input type="password" class="form-control" id="inputPassword" required v-model="credentials.password">
      </div>
    </div>
    <div class="mb-3">
      <div class="mb-3">
        <label for="inputConfirmation_code" class="form-label">Confirmation code</label>
        <input type="password" class="form-control" id="inputConfirmation_code" required
          v-model="credentials.confirmation_code">
      </div>
    </div>

    <div class="mb-3 d-flex justify-content-center">
      <button type="button" class="btn btn-primary px-5" @click="register">Register</button>
      <div class="mb-3 d-flex justify-content-center">
        <button type="button" class="btn btn-primary px-5">
          <router-link class="nav-link" :class="{ active: $route.name === 'Login' }" :to="{ name: 'Login' }">
            Already have an account? Login
          </router-link>
        </button>
      </div>
    </div>
  </form>
</template>