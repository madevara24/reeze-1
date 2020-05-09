<template>
  <v-container>
    <v-card>
      <v-card-title>Create a Project</v-card-title>
      <v-card-text>
        <v-form @submit.prevent>
          <v-row>
            <v-col cols="12" md="8">
              <v-text-field v-model="name" label="Project Name" required></v-text-field>
            </v-col>
            <v-col cols="12" md="4">
              <v-select
                :items="repositories"
                v-model="repository"
                label="Choose Repository"
                class="input-group--focused"
                item-value="text"
              ></v-select>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" md="8">
              <v-text-field v-model="description" label="Description" required></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" md="6">
              <v-text-field v-model="sprintDuration" label="Sprint Duration" required></v-text-field>
            </v-col>

            <v-col cols="12" md="6">
              <v-text-field v-model="sprintStartDay" label="Sprint Start Day" required></v-text-field>
            </v-col>
          </v-row>

          <v-btn class="mr-4" @click="submit()" color="primary">Submit</v-btn>
        </v-form>
      </v-card-text>
    </v-card>
  </v-container>
</template>

<script>
/* eslint-disable no-console */

export default {
  created() {
    this.getRepository();
  },
  data() {
    return {
      name: "",
      description: "",
      sprintDuration: 0,
      sprintStartDay: 0,
      repository: "",
      repositories: []
    };
  },
  methods: {
    getRepository() {
      let token = localStorage.getItem("token");
      const headers = {
        "Content-Type": "application/json",
        Authorization: "Bearer " + token
      };

      this.axios
        .get(`${this.appUrl}/api/v1/list-repo`, { headers })
        .then(response => {
          console.log(response.data.data);
          this.repositories = response.data.data;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    submit() {
      let token = localStorage.getItem("token");
      let data = {
        name: this.name,
        repository: this.repository,
        description: this.description,
        sprint_duration: this.sprintDuration,
        sprint_start_day: this.sprintStartDay
      };

      const headers = {
        "Content-Type": "application/json",
        Authorization: "Bearer " + token
      };

      this.axios
        .post(`${this.appUrl}/api/v1/project/create`, data, { headers })
        .then(response => {
          console.log(response);
        })
        .catch(function(error) {
          console.log(error);
        });
    }
  }
};
</script>
