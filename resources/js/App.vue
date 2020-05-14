<template>
  <v-app>
    
    <Navbar v-if="isLogin"/>
    <v-content >
      <router-view>
        
      </router-view>
    </v-content>
  </v-app>
</template>

<script>
/* eslint-disable */
import Navbar from './components/Navbar'

export default {
  name: 'App',
  components: {
    Navbar
  },
  data: () => ({
    //
  }),
    computed: {
    isLogin(){
      if(localStorage.getItem('token') != null)
      {
        this.$store.commit('setLogin', true)
      }
      
      return this.$store.getters.getIsLogin
    }
  },
};
</script>
