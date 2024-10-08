import axios from 'axios'
import { ref, computed, inject } from 'vue'
import { defineStore } from 'pinia'
import avatarNoneUrl from '@/assets/avatar-none.png'

export const useUserStore = defineStore('user', () => {
  const serverBaseUrl = inject('serverBaseUrl')
  const user = ref(null)
  const vcardNmr = computed(() => user.value?.username ?? -1)
  const user_type = computed(() => user.value.user_type)
  const userId = computed(() => user.value?.id ?? -1)
  const userName = computed(() => user.value?.name ?? 'Anonymous')
  const userPhotoUrl = computed(() =>
    user.value?.photo_url
      ? serverBaseUrl + '/storage/fotos/' + user.value.photo_url
      : avatarNoneUrl)

  async function loadUser() {
    try {
      console.log('antes')
      const response = await axios.get('users/me')
      console.log('depois')
      user.value = response.data.data
    } catch (error) {
      console.log(error)
      clearUser()
      throw error
    }
  }

  function clearUser() {
    delete axios.defaults.headers.common.Authorization
    user.value = null
  }

  async function login(credentials) {
    try {
      const response = await axios.post('login', credentials)
      axios.defaults.headers.common.Authorization = "Bearer " + response.data.access_token
      sessionStorage.setItem('token', response.data.access_token)
      await loadUser()
      return true
    }
    catch (error) {
      clearUser()
      return false
    }
  }

  async function logout() {
    try {
      await axios.post('logout')
      clearUser()
      return true
    } catch (error) {
      return false
    }
  }

  async function restoreToken() {
    let storedToken = sessionStorage.getItem('token')
    if (storedToken) {
      axios.defaults.headers.common.Authorization = "Bearer " + storedToken
      await loadUser()
      return true
    }
    clearUser()
    return false
  }

  return { user, userId, userName, userPhotoUrl, loadUser, clearUser, login, logout, vcardNmr, user_type, restoreToken}
})