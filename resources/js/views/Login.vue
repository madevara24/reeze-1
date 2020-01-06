<template>
    <v-container class="fill-height" fluid>
        <v-row align="center" justify="center">
          <v-col cols="12" sm="12" md="4">
            <v-btn href="http://127.0.0.1:8000/login-github" @click="login()" color="primary">
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
/* eslint-disable no-console */
  export default {
    created(){
      console.log(this.$store.getters.getIsLogin)
    },
    props: {
      source: String,
    },
    data: () => ({
      drawer: null,
    }),
    methods: {
      login(){

      }
    },
  }
</script>