/* eslint-disable no-console */
/* eslint-disable no-unused-vars */
import Vue from 'vue'
import Router from 'vue-router'
import axios from 'axios'
import store from '../store/store'

import Home from '../views/Home.vue'
import Project from '../views/Project.vue'
import Login from '../views/Login.vue'
import Board from '../views/Board.vue'
import TeamAnalytics from '../views/TeamAnalytics.vue'
import PersonalAnalytics from '../views/PersonalAnalytics.vue'
import ProjectMerge from '../views/ProjectMerge.vue'
import ProjectSetting from '../views/ProjectSetting.vue'
import CreateProject from '../views/CreateProject.vue'

//dummy route
import DummyTeamAnalytics from '../views/dummy/DummyTeamAnalytics.vue'
import DummyPersonalAnalytics from '../views/dummy/DummyPersonalAnalytics.vue'

import auth from './middleware/auth'
import middlewarePipeline from './middlewarePipeline'
Vue.use(Router)

const router = new Router({
    mode: 'history',
    base: process.env.BASE_URL,
    routes: [{
            path: '/',
            name: 'home',
            component: Home,
            meta: {
                middleware: [
                    auth
                ]
            }
        },
        {
            path: '/project/:projectId',
            name: 'project',
            component: Project,
            meta: {
                middleware: [
                    auth
                ]
            },
            children: [{
                    path: 'board',
                    name: 'board',
                    component: Board
                },
                {
                    path: 'teamAnalytics',
                    name: 'teamAnalytics',
                    component: TeamAnalytics
                },
                {
                    path: 'personalAnalytics/:personId?',
                    name: 'personalAnalytics',
                    component: PersonalAnalytics
                },
                // dummy start
                {
                    path: 'dummyTeamAnalytics',
                    name: 'teamAnalytics',
                    component: DummyTeamAnalytics
                },
                {
                    path: 'dummyPersonalAnalytics',
                    name: 'personalAnalytics',
                    component: DummyPersonalAnalytics
                },
                // dummy end
                {
                    path: 'projectMerge',
                    name: 'projectMerge',
                    component: ProjectMerge
                },
                {
                  path: 'projectSetting',
                  name: 'projectSetting',
                  component: ProjectSetting
                },
            ]
        },
        {
            path: '/login',
            name: 'login',
            component: Login,
        }
    ]
})

router.beforeEach((to, from, next) => {
    axios.interceptors.response.use((response) => {
        return response
    }, function (error) {

        if (error.response.status === 401) {
            store.commit("setLogin", false);
            localStorage.removeItem("token");

            next('/login');
            return Promise.reject(error);
        }

        return Promise.reject(error);
    });

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
