<script setup>

  import axios from 'axios'
  import { ref, onMounted } from 'vue'

  const categories = ref([])

  const loadCategories = async () => {
    try {
      const response = await axios.get('categories')
      console.log(response)
      categories.value = response.data.data
    } catch (error) {
      console.error(error)
    }
  }

  onMounted(() => {
    loadCategories()
  })

</script>

<template>
  <div>
    <h1>Categories</h1>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">Name</th>
          <th scope="col">Type</th>
          
        </tr>
      </thead>
      <tbody>
        <tr v-for="categorie in categories" :key="categorie.id">
          <th scope="row">{{ categorie.id}}</th>
          <td>{{ categorie.name }}</td>
          <td>{{ categorie.type }}</td>
          
        </tr>
      </tbody>
    </table>
  </div>
</template>