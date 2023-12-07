<script setup>
import axios from 'axios'
import { useToast } from 'vue-toastification'
import { useRouter } from 'vue-router'
import { ref } from 'vue'
import { useUserStore } from '../../stores/user.js'

const toast = useToast()
const router = useRouter()
const userStore = useUserStore()

const credentials = ref({
  phone_number: '',
  username: '',
  password: ''
})

const emit = defineEmits(['register'])

const register = async () => {
  //to-do
}

</script>

<template>
  <form class="row g-3 needs-validation" novalidate @submit.prevent="login">
    <h3 class="mt-5 mb-3">Register New Account</h3>
    <hr>
    <div class="mb-3">
      <div class="mb-3">
        <label for="inputUsername" class="form-label">Username</label>
        <input type="text" class="form-control" id="inputUsername" required v-model="credentials.username">
      </div>
    </div>
    <div class="mb-3">
      <div class="mb-3">
        <label for="inputphone_number" class="form-label">Phone Number</label>
        <input type="text" class="form-control" id="inputphone_number" required v-model="credentials.phone_number">
      </div>
    </div>
    <div class="mb-3">
      <div class="mb-3">
        <label for="inputPassword" class="form-label">Password</label>
        <input type="password" class="form-control" id="inputPassword" required v-model="credentials.password">
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