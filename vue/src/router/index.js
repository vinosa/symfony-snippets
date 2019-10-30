import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'
import Errors from '../views/Errors.vue'
import New from '../views/New.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'home',
    component: Home
  },
  {
    path: '/new',
    name: 'new',
    component: New
  },
  {
    path: '/errors',
    name: 'errors',
    component: Errors,
    props: true
  },
  {
    path: '/errors/:archiveId?',
    name: 'errorsByArchive',
    component: Errors,
    props: true
  },
  {
    path: '/about',
    name: 'about',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "about" */ '../views/About.vue')
  }
]

const router = new VueRouter({
  routes
})

export default router
