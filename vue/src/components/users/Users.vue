<script setup>
import axios from 'axios'
import { useRouter } from 'vue-router'
import { ref, computed, onMounted, inject } from 'vue'
import UserTable from "./UserTable.vue"
import { emit } from 'process';
import { useToast } from 'vue-toastification';

const router = useRouter()
const toast = useToast()
const users = ref([])
const socket = inject("socket")

const totalUsers = computed(() => {
  return users.value.length
})

const loadUsers = async () => {
    try {
      const response = await axios.get('users')
    users.value = response.data.data

  } catch (error) {
    console.log(error)
  }
}

const editUser = (user) => {
  router.push({ name: 'User', params: { id: user.id } })
  socket.emit('editVCard')
}

socket.on('editVCard', (user) => {
  users.value.push(user)
  toast.success(`VCard was edited`)
})

onMounted (() => {
  loadUsers()
})
</script>

<template>
  <h1 class="mt-5 mb-3">Users</h1>
  <hr>
  <user-table
    :users="users"
    :showId="false"
    @edit="editUser"
  ></user-table>
</template>

<style scoped>
.filter-div {
  min-width: 12rem;
}
.total-filtro {
  margin-top: 2.3rem;
}
</style>

