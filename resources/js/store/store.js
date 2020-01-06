import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)
/*eslint-disable */
export default new Vuex.Store({
    strict: true,
    state:{
        //user
        user:{
            isLogin: false,
        },
        projects:{
            selectedProjectId: null,
        },
        //people
        selectedPerson: null,
    },
    methods: {
    },
    getters:{
        //user
        getIsLogin(state){
            return state.user.isLogin
        },
        //projects
        projectData(state){
            return state.projects.projects
        },
        projectSelections (state) {
            var projectSelections = state.projects.projects.map( project => {
                return {
                    name: project.name,
                    id: project.id
                }
            })
            return projectSelections
        },
        getSelectedProjectId(state){
            return state.projects.selectedProjectId
        }
    },
    mutations:{
        //users
        setLogin(state, login){
            state.user.isLogin = login
        },
        //project
        selectProject (state, projectId) {
            state.projects.selectedProjectId = projectId
        }
    }
})