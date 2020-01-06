import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)
/*eslint-disable */
export default new Vuex.Store({
    strict: true,
    state:{
        //user
        user:{
            // isLogin: false,
            isLogin: true,
        },
        projects:{
            selectedProjectId: 1,
            projects: [
                {id:1, name: 'Insight', sprintDuration:7, sprintProgress:3, totalTasks:12, completedTasks:7},
                {id:2, name: 'Roundabout', sprintDuration:7, sprintProgress:7, totalTasks:11, completedTasks:10},
                {id:3, name: 'Hexagon', sprintDuration:14, sprintProgress:8, totalTasks:17, completedTasks:10},
                {id:4, name: 'Fast Forward', sprintDuration:7, sprintProgress:1, totalTasks:5, completedTasks:0},
                {id:5, name: 'Skipper', sprintDuration:14, sprintProgress:5, totalTasks:14, completedTasks:7},
            ],
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
            state.selectedProjectId = projectId
        }
    }
})