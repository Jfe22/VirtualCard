//import './assets/main.css'
import "bootstrap/dist/css/bootstrap.min.css"
import "bootstrap-icons/font/bootstrap-icons.css"
import "bootstrap"
import Toast from "vue-toastification"
import "vue-toastification/dist/index.css"

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import { io } from "socket.io-client"

import axios from 'axios'
import App from './App.vue'
import router from './router'

const app = createApp(App)
//const serverBaseUrl = 'http://localhost:8000'
const apiDomain = import.meta.env.VITE_API_DOMAIN
const wsConnction = import.meta.env.VITE_WS_CONNECTION

//app.provide('serverBaseUrl', serverBaseUrl)
app.provide('serverBaseUrl', apiDomain)
//app.provide('socket', io("http://localhost:8080"))
app.provide('socket', io(wsConnction))

app.provide('axios', axios);

axios.defaults.baseURL = apiDomain + '/api'
axios.defaults.headers.common['Content-Type'] = 'application/json'

app.use(createPinia())
app.use(router)

app.use(Toast, {
    position: "top-center",
    timeout: 3000,
    closeOnClick: true,
    pauseOnFocusLoss: true,
    pauseOnHover: true,
    draggable: true,
    draggablePercent: 0.6,
    showCloseButtonOnHover: true,
    hideProgressBar: true,
    closeButton: "button",
    icon: true,
    rtl: false
   })

app.mount('#app')
