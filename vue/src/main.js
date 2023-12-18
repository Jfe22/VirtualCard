//import './assets/main.css'
import "bootstrap/dist/css/bootstrap.min.css"
import "bootstrap-icons/font/bootstrap-icons.css"
import "bootstrap"

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import { io } from "socket.io-client"

import axios from 'axios'
import App from './App.vue'
import router from './router'

const app = createApp(App)

const serverBaseUrl = 'http://laravel.test'
app.provide('serverBaseUrl', serverBaseUrl)

app.provide('axios', axios);

app.provide('socket', io("http://localhost:8080"))

axios.defaults.baseURL = serverBaseUrl + '/api'
axios.defaults.headers.common['Content-Type'] = 'application/json'

app.use(createPinia())
app.use(router)

app.mount('#app')
