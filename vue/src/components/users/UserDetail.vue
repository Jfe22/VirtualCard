<script setup>
import { ref, watch, computed, inject } from "vue";
import avatarNoneUrl from '@/assets/avatar-none.png'

const serverBaseUrl = inject("serverBaseUrl");

const props = defineProps({
  user: {
    type: Object,
    required: true,
  },
  errors: {
    type: Object,
    required: false,
  },
});

const emit = defineEmits(["save", "cancel"]);

const editingUser = ref(props.user)

watch(
  () => props.user,
  (newUser) => {
    editingUser.value = newUser
  },
  { immediate: true }
)

const photoFullUrl = computed(() => {
  return editingUser.value.photo_url
    ? serverBaseUrl + "/storage/fotos/" + editingUser.value.photo_url
    : avatarNoneUrl
})

const save = () => {
  emit("save", editingUser.value);
}

const cancel = () => {
  emit("cancel", editingUser.value);
}
</script>

<template>
  <form class="row g-3 needs-validation" novalidate @submit.prevent="save">
    <h3 class="mt-5 mb-3">{{ editingUser.name }} vCard</h3>
    <hr />
    <div class="d-flex flex-wrap justify-content-between">
      <div class="w-75 pe-4">
        <div class="mb-3">
          <label for="inputName" class="form-label">Name</label>
          <input type="text" class="form-control" :class="{ 'is-invalid': errors ? errors['name'] : false }"
            id="inputName" placeholder="User Name" required v-model="editingUser.name" />
          <field-error-message :errors="errors" fieldName="name"></field-error-message>
        </div>
        <div class="mb-3">
          <label for="inputphone_number" class="form-label">Phone Number</label>
          <input type="text" class="form-control" :class="{ 'is-invalid': errors ? errors['phone_number'] : false }"
            id="inputphone_number" placeholder="Phone number" required v-model="editingUser.phone_number" />
          <field-error-message :errors="errors" fieldName="phone_number"></field-error-message>
        </div>
        <div class="mb-3 px-1">
          <label for="inputEmail" class="form-label">Email</label>
          <input type="email" class="form-control" :class="{ 'is-invalid': errors ? errors['email'] : false }"
            id="inputEmail" placeholder="Email" required v-model="editingUser.email" />
          <field-error-message :errors="errors" fieldName="email"></field-error-message>
        </div>
        <div class="d-flex ms-1 mt-4 flex-wrap justify-content-between">
          <div class="mb-3 me-3 flex-grow-1">
            <label for="inputpassword" class="form-label">Password</label>
            <input type="password" class="form-control" :class="{ 'is-invalid': errors ? errors['password'] : false }"
              id="inputpassword" placeholder="Password" required v-model="editingUser.password" />
            <field-error-message :errors="errors" fieldName="password"></field-error-message>
          </div>
          <div class="mb-3 me-3 flex-grow-1">
            <label for="inputconfirmation_code" class="form-label">Confirmation Code</label>
            <input type="confirmation_code" class="form-control" :class="{ 'is-invalid': errors ? errors['confirmation_code'] : false }"
              id="inputconfirmation_code" placeholder="" required v-model="editingUser.confirmation_code" />
            <field-error-message :errors="errors" fieldName="confirmation_code"></field-error-message>
          </div>
        </div>
      </div>
      <div class="w-25">
        <div class="mb-3">
          <label class="form-label">Photo</label>
          <div class="form-control text-center">
            <img :src="photoFullUrl" class="w-100" />
          </div>
        </div>
      </div>
    </div>
    <div class="mb-3 d-flex justify-content-end">
      <button type="button" class="btn btn-primary px-5" @click="save">Save</button>
      <button type="button" class="btn btn-light px-5" @click="cancel">Cancel</button>
    </div>
  </form>
</template>

<style scoped>
.total_hours {
  width: 26rem;
}
</style>
