import { createRouter, createWebHistory } from 'vue-router'
import Index from './views/Index.vue'
import Login from './views/Login.vue'

const routes = [
  { path: '/', component: Index },
  { path: '/login', component: Login }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
