<script setup>

  import axios from 'axios'
  import { ref, onMounted } from 'vue'
  import { useUserStore } from '../stores/user.js'

  const userStore = useUserStore()

    const loadUser = async () => {
        try {
        const response = await axios.get('users/' + userStore.user.id)
        userStore.user = response.data.data
        } catch (error) {
        console.log(error)
        }
    }


</script>

<template>
  <div>
    <h1>Profile</h1>
    <h2>to-do</h2>
    <button>Edit Profile</button>
    <h3>Categories</h3>
    <button>Edit Categories</button>
  </div>
</template>