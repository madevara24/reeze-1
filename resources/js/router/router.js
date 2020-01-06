/* eslint-disable no-console */
/* eslint-disable no-unused-vars */
import Vue from 'vue'
import Router from 'vue-router'
import store from '../store/store'

import Home from '../views/Home.vue'
import Project from '../views/Project.vue'
import Login from '../views/Login.vue'
import Board from '../views/Board.vue'
import Analytics from '../views/Analytics.vue'

import guest from './middleware/guest'
import auth from './middleware/auth'
import middlewarePipeline from './middlewarePipeline'
Vue.use(Router)

const router = new Router({
  mode: 'history',
  base: process.env.BASE_URL,
  routes: [
    {
      path: '/',
      name: 'home',
      component: Home,
      meta: { 
        middleware:[
          auth
        ]
      }
    },
    {
      path: '/project/:id',
      name: 'project',
      component: Project,
      meta: { 
        middleware:[
          auth
        ]
      },
      children: [
        {
          path: 'board',
          name: 'board',
          component: Board
        },
        {
          path: 'analytics',
          name: 'analytics',
          component: Analytics
        }]
    },
    {
      path: '/login',
      name: 'login',
      component: Login,
    },
    {
      path: '/github-callback',
      name: 'github-callback',
      redirect: to=>{
        store.commit('setLogin', true)
        console.log('store login: ' + store.getters.getIsLogin)
        return '/'
      },
    },
    // { path: '/dynamic-redirect/:id?',
    //   redirect: to => {
    //     const { hash, params, query } = to
    //     if (query.to === 'foo') {
    //       return { path: '/foo', query: null }
    //     }
    //     if (hash === '#baz') {
    //       return { name: 'baz', hash: '' }
    //     }
    //     if (params.id) {
    //       return '/with-params/:id'
    //     } else {
    //       return '/bar'
    //     }
    //   }
    // },
  ]
})

router.beforeEach((to, from, next) => {
  if (!to.meta.middleware) {
      return next()
  }
  const middleware = to.meta.middleware

  const context = {
      to,
      from,
      next,
      store
  }


  return middleware[0]({
      ...context,
      next: middlewarePipeline(context, middleware, 1)
  })

})

export default router