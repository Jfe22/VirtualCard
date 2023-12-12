<script setup>
import axios from 'axios'
import { useRouter } from 'vue-router'
import { ref, computed, onMounted } from 'vue'
import UserTable from "./UserTable.vue"

const router = useRouter()

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
}

onMounted (() => {
  loadUsers()
})
</script>

<template>
  <h3 class="mt-5 mb-3">Team Members</h3>
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

