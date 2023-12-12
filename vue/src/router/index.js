import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

import Dashboard from '../components/Dashboard.vue'
import Vcards from '../components/Vcards.vue'
import Login from '../components/auth/Login.vue'
import Transactions from '../components/Transactions/Transactions.vue'
import Transaction from '../components/Transactions/Transaction.vue'
import ChangePassword from '../components/auth/ChangePassword.vue'
import Register from '../components/auth/Register.vue'
import EditProfile from '../components/EditProfile.vue'
import Categories from '../components/Categories.vue'
import User from "../components/users/User.vue"
import Users from "../components/users/Users.vue"

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/about',
      name: 'about',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/AboutView.vue')
    },
    {
      path: '/dashboard',
      name: 'Dashboard',
      component: Dashboard
    },
    {
      path: '/login',
      name: 'Login',
      component: Login 
    },
    {
      path: '/transactions',
      name: 'Transactions',
      component: Transactions,
    },
    {
      path: '/transactions/:id',
      name: 'Transaction',
      component: Transaction,
      props: route => ({ id: parseInt(route.params.id) })
    },
    {
      path: '/transactions/new',
      name: 'NewTransaction',
      component: Transaction,
      props: { id: -1 }
    },
    {
      path: '/vcards',
      name: 'Vcards',
      component: Vcards 
    },
    {
      path: '/changepassword',
      name: 'ChangePassword',
      component: ChangePassword 
    },
    {
      path: '/register',
      name: 'Register',
      component: Register
    },
    {
      path: '/editprofile',
      name: 'EditProfile',
      component: EditProfile
    },
    {
      path: '/categories',
      name: 'Categories',
      component: Categories
    },
    {
      path: '/users',
      name: 'Users',
      component: Users,
    },
    {
      path: '/users/:id',
      name: 'User',
      component: User,
      //props: true
      // Replaced with the following line to ensure that id is a number
      props: route => ({ id: parseInt(route.params.id) })
    }, 
  ]
})

export default router
