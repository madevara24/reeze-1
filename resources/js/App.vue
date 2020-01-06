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
      console.log('Login : ' + this.$store.getters.getIsLogin)
      return this.$store.getters.getIsLogin
    }
  },
};
</script>
