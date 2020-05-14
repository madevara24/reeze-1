import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)
/*eslint-disable */
export default new Vuex.Store({
    strict: true,
    state:{
        //user
        user:{
            isLogin: false
        },
    },
    methods: {
    },
    getters:{
        //user
        getIsLogin(state){
            return state.user.isLogin
        }
    },
    mutations:{
        //users
        setLogin(state, login){
            state.user.isLogin = login
        }
    }
})