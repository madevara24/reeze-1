<template>
  <v-container class="fill-height" fluid>
    <v-row align="center" justify="center">
      <v-col cols="12" sm="8" md="6" lg="4">
        <v-card flat>
          <v-card-actions>
            <v-btn block @click="login()" color="primary">
              <v-icon dark left>person</v-icon>Login with Github
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import store from "../store/store";
/* eslint-disable no-console */
export default {
  created() {
    if (store.getters.getIsLogin == true) {
      this.$router.push({ name: "home" });
    }
  },
  mounted() {
    window.addEventListener("message", this.onMessage, false);
  },

  beforeDestroy() {
    window.removeEventListener("message", this.onMessage);
  },

  methods: {
    // This method call the function to launch the popup and makes the request to the controller.
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