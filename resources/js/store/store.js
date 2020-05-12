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
            credentials: null
        },
    },
    methods: {
    },
    getters:{
        //user
        getIsLogin(state){
            return state.user.isLogin
        },
        //credentials
        getUser(state){
            return state.user.credentials
        }
    },
    mutations:{
        //users
        setLogin(state, login){
            state.user.isLogin = login
        },
        //credentials
        setUser(state, user){
            state.user.credentials = user
        }
    }
})