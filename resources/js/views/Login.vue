<template>
    <v-container class="fill-height" fluid>
        <v-row align="center" justify="center">
          <v-col cols="12" sm="12" md="4">
            <v-btn @click="login()" color="primary">
            Login
            </v-btn>
            <!-- <v-card>
              <v-toolbar color="primary" dark flat>
                <v-toolbar-title>Login form</v-toolbar-title>
                <div class="flex-grow-1"></div>
              </v-toolbar>
              <v-card-text>
              </v-card-text>
              <v-card-actions>
                <div class="flex-grow-1"></div>
                <v-btn color="primary">Login</v-btn>
              </v-card-actions>
            </v-card> -->
          </v-col>
        </v-row>
      </v-container>
</template>

<script>
import store from '../store/store'
/* eslint-disable no-console */
  export default {
    created() {
      if(store.getters.getIsLogin == true){
        this.$router.push({ name: "home"})
      }
    },
    mounted () {
        window.addEventListener('message', this.onMessage, false)
    },

    beforeDestroy () {
        window.removeEventListener('message', this.onMessage)
    },

    methods : {
        // This method call the function to launch the popup and makes the request to the controller. 
        login () {
            this.axios
                .post('api/v1/login')
                .then(response => {
                    store.commit('setLogin', true)
                    window.location.href = response.data
                })
                .catch(function (error) {
                    console.error(error);
                });
            },
    }
}

</script>