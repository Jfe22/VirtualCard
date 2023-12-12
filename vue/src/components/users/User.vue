<script setup>
import axios from 'axios'
import { useToast } from "vue-toastification"
import { ref, watch } from 'vue'
import UserDetail from "./UserDetail.vue"
import { useRouter, onBeforeRouteLeave } from 'vue-router'

const toast = useToast()
const router = useRouter()

const props = defineProps({
    id: {
      type: Number,
      default: null
    }
})

const newUser = () => {
    return {
      id: null,
      name: '',
      email: '',
      gender: 'M',
      photo_url: null
    }
}

const user = ref(newUser())
const errors = ref(null)
const confirmationLeaveDialog = ref(null)
// String with the JSON representation after loading the project (new or edit)
let originalValueStr = ''

const loadUser = async (id) => {
  originalValueStr = ''
  errors.value = null
  if (!id || (id < 0)) {
    user.value = newUser()
  } else {
      try {
        const response = await axios.get('users/' + id)
        user.value = response.data.data
        originalValueStr = JSON.stringify(user.value)
      } catch (error) {
        console.log(error)
      }
  }
}

const save = async () => {
  errors.value = null
  try {
    const response = await axios.put('users/' + props.id, user.value)
    user.value = response.data.data
    originalValueStr = JSON.stringify(user.value)
    toast.success('User #' + user.value.id + ' was updated successfully.')
    router.back()
  } catch (error) {
    if (error.response.status == 422) {
      errors.value = error.response.data.errors
      toast.error('User #' + props.id + ' was not updated due to validation errors!')
    } else {
      toast.error('User #' + props.id + ' was not updated due to unknown server error!')
    }
  }
}

const cancel = () => {
  originalValueStr = JSON.stringify(user.value)
  router.back()
}

watch(
  () => props.id,
  (newValue) => {
      loadUser(newValue)
    },
  {immediate: true}  
)

let nextCallBack = null
const leaveConfirmed = () => {
  if (nextCallBack) {
    nextCallBack()
  }
}

onBeforeRouteLeave((to, from, next) => {
  nextCallBack = null
  let newValueStr = JSON.stringify(user.value)
  if (originalValueStr != newValueStr) {
    // Some value has changed - only leave after confirmation
    nextCallBack = next
    confirmationLeaveDialog.value.show()
  } else {
    // No value has changed, so we can leave the component without confirming
    next()
  }
})


</script>

<template>
  <confirmation-dialog
    ref="confirmationLeaveDialog"
    confirmationBtn="Discard changes and leave"
    msg="Do you really want to leave? You have unsaved changes!"
    @confirmed="leaveConfirmed"
  >
  </confirmation-dialog>  

  <user-detail
    :user="user"
    :errors="errors"
    @save="save"
    @cancel="cancel"
  ></user-detail>
</template>
