<script setup>
import axios from 'axios'
import { useRouter } from 'vue-router'
import { ref, computed, onMounted,inject } from 'vue'
import UserTable from "./UserTable.vue"
import { useToast } from 'vue-toastification'

const router = useRouter()
const socket = inject('socket')

const users = ref([])

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
  socket.emit('editVCard', user)
}

socket.on('editUser', (user) => {
  toast.success(`User ${user.id} was edited`)
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

