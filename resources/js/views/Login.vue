<template>
  <v-container fluid fill-height class="pa-0">
    <v-layout style="background-color:#2F82E2" align-center>
      <v-col cols="12" align-self="center">
        <v-row justify="center" align="center">
          <h1 class="white--text display-4">REEZE</h1>
        </v-row>
      </v-col>
    </v-layout>
    <v-col cols="6" align-self="center">
      <v-row justify="center" align="center">
        <v-col cols="6">
          <v-card flat>
            <v-btn width="150" height="50" block @click="login()" color="primary">
              <v-icon large dark left>{{ githubIcon }}</v-icon>Login with Github
            </v-btn>
          </v-card>
        </v-col>
      </v-row>
    </v-col>
  </v-container>
</template>

<script>
import store from "../store/store";
import { mdiGithub } from "@mdi/js";
/* eslint-disable no-console */
export default {
  created() {
    if (store.getters.getIsLogin == true) {
      this.$router.push({ name: "home" });
    }
  },
  data() {
    return {
      githubIcon: mdiGithub
    };
  },

  methods: {
    login() {
      this.axios
        .post(`${this.appUrl}/api/v1/login`)
        .then(response => {
          store.commit("setLogin", true);
          window.location.href = response.data;
        })
        .catch(function(error) {
          console.error(error);
        });
    }
  }
};
</script>

