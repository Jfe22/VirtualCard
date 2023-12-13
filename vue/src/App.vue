<script setup>
import { RouterLink, RouterView } from 'vue-router'
import { useToast } from 'vue-toastification'
import { useUserStore } from './stores/user.js'
import axios from 'axios';
import { ref, onMounted } from 'vue'

const toast = useToast()
const userStore = useUserStore()

const logout = async () => {
  if (await userStore.logout()) {
    toast.success('User has logged out of the application.')
    delete axios.defaults.headers.common.Authorization
    router.push({ name: 'Login' })
  } else {
    toast.error('There was a problem logging out of the application!')
  }
}

const userTransactions = ref([])

onMounted(async () => {
  try {
    const userId = 1
    const response = await axios.get("users/" + userId + "/transactions")
    userTransactions.value = response.data.data
  } catch (error) {
    console.log(error)
  }
})
</script>

<template>
  <nav class="navbar navbar-expand-md navbar-dark bg-dark sticky-top flex-md-nowrap p-0 shadow">
    <div class="container-fluid">
      <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">
        <img src="@/assets/logo.svg" alt="" width="30" height="24" class="d-inline-block align-text-top">
        Card
      </a>
      <button id="buttonSidebarExpandId" class="navbar-toggler" type="button" data-bs-toggle="collapse"
        data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav">
          <li class="nav-item">
            <router-link class="nav-link" :class="{ active: $route.name === 'Register' }" :to="{ name: 'Register' }">
              <i class="bi bi-bi bi-person-check-fill"></i>
              Register
            </router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link" :class="{ active: $route.name === 'Login' }" :to="{ name: 'Login' }">
              <i class="bi bi-box-arrow-in-right"></i>
              Login
            </router-link>
          </li>
          <li class="nav-item dropdown"> //v-if="login"
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
              data-bs-toggle="dropdown" aria-expanded="false">
              <img :src="userStore.userPhotoUrl" class="rounded-circle z-depth-0 avatar-img" alt="avatar image">
              <span class="avatar-text">{{ userStore.userName }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
              <li>
                <router-link class="dropdown-item" :class="{ active: $route.name == 'User' && $route.params.id == 1 }"
                  :to="{ name: 'User', params: { id: 1 } }">
                  <i class="bi bi-person-circle"></i>
                  Profile
                </router-link>
              </li>
              <li>
                <router-link :class="{ active: $route.name === 'ChangePassword' }" :to="{ name: 'ChangePassword' }">
                  <a class="dropdown-item">
                    <i class="bi bi-key-fill"></i>
                    Change password
                  </a>
                </router-link>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li>
                <a class="dropdown-item" @click.prevent="logout"><i class="bi bi-arrow-right"></i>Logout</a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container-fluid">
    <div class="row">
      <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse"> //v-if="login"
        <div class="position-sticky pt-3">
          <ul class="nav flex-column">
            <li class="nav-item">
              <router-link class="nav-link" :class="{ active: $route.name === 'Dashboard' }" :to="{ name: 'Dashboard' }">
                <i class="bi bi-house"></i>
                Dashboard
              </router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" :class="{ active: $route.name === 'Users' }" :to="{ name: 'Users' }">
                <i class="bi bi-caret-right"></i>
                Users
              </router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" :class="{ active: $route.name === 'Vcards' }" :to="{ name: 'Vcards' }">
                <i class="bi bi-caret-right"></i>
                Vcards
              </router-link>
            </li>
            <li class="nav-item" ><!--v-for="transaction in userTransactions" :key="transaction.id"-->
              <router-link class="nav-link"
                :class="{ active: $route.name == 'Transactions'  }" 
                :to="{ name: 'Transactions' }"> <!--&& $route.params.id == transaction.id // , params: { id: transaction.id }-->
                <i class="bi bi-caret-right"></i>
                Transactions
              </router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" :class="{ active: $route.name === 'Statistics' }"
                :to="{ name: 'Statistics' }">
                <i class="bi bi-caret-right"></i>
                Statistics
              </router-link>
            </li>
          </ul>
        </div>
      </nav>
      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <router-view></router-view>
      </main>
    </div>
  </div>
</template>

<style>
@import "./assets/dashboard.css";

.avatar-img {
  margin: -1.2rem 0.8rem -2rem 0.8rem;
  width: 3.3rem;
  height: 3.3rem;
}

.avatar-text {
  line-height: 2.2rem;
  margin: 1rem 0.5rem -2rem 0;
  padding-top: 1rem;
}

.dropdown-item {
  font-size: 0.875rem;
}

.btn:focus {
  outline: none;
  box-shadow: none;
}

#sidebarMenu {
  overflow-y: auto;
}
</style>
