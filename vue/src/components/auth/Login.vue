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
  username: '',
  password: ''
})

const emit = defineEmits(['login'])

const login = async () => {
  console.log(userStore)
  if (await userStore.login(credentials.value)) {
    toast.success('User ' + userStore.user.name + ' has entered the application.')
    emit('login')
    router.push({name: 'Dashboard'})
  } else {
    credentials.value.password = ''
    toast.error('User credentials are invalid!')
  }
}

</script>

<template>
  <form class="row g-3 needs-validation" novalidate @submit.prevent="login">
    <h3 class="mt-5 mb-3">Login</h3>
    <hr>
    <div class="mb-3">
      <div class="mb-3">
        <label for="inputUsername" class="form-label">Username / Phone Number</label>
        <input type="text" class="form-control" id="inputUsername" required v-model="credentials.username">
      </div>
    </div>
    <div class="mb-3">
      <div class="mb-3">
        <label for="inputPassword" class="form-label">Password</label>
        <input type="password" class="form-control" id="inputPassword" required v-model="credentials.password">
      </div>
    </div>
    <div class="mb-3 d-flex justify-content-center">
      <button type="button" class="btn btn-primary px-5" @click="login">Login</button>
    </div>
    <div class="mb-3 d-flex justify-content-center">
      <button type="button" class="btn btn-primary px-5">
        <router-link class="nav-link" :class="{ active: $route.name === 'Register' }" :to="{ name: 'Register' }">
          Register
        </router-link>
      </button>
    </div>
  </form>
</template>