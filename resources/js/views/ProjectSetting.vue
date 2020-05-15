<template>
  <v-container>
    <v-card>
      <v-card-title>Project Setting</v-card-title>
      <v-card-text>
        <v-form @submit.prevent>
          <v-row>
            <v-col cols="12" md="8">
              <v-text-field v-model="name" label="Project Name" required></v-text-field>
            </v-col>
            <v-col cols="12" md="4">
              <v-text-field v-model="repository" readonly label="Project Repository"></v-text-field>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" md="4">
              <v-text-field v-model="version" label="Project Version" readonly></v-text-field>
            </v-col>
            <v-col cols="12" md="8">
              <v-text-field v-model="description" label="Description" required></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" md="6">
              <v-select
                :items="sprintDurationOptions"
                v-model="sprintDuration"
                item-text="text"
                item-value="days"
                label="Sprint Duration"
                class="input-group--focused"
              ></v-select>
            </v-col>

            <v-col cols="12" md="6">
              <v-select
                :items="sprintStartDayOptions"
                v-model="sprintStartDay"
                item-text="text"
                item-value="day"
                label="Sprint Start Day"
                class="input-group--focused"
              ></v-select>
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
    this.getProjectData();
  },
  data() {
    return {
      name: "",
      description: "",

      sprintDuration: { text: "1 Week", days: 7 },
      sprintDurationOptions: [
        { text: "1 Week", days: 7 },
        { text: "2 Weeks", days: 14 },
        { text: "3 Weeks", days: 21 },
        { text: "4 Weeks", days: 28 }
      ],

      sprintStartDay: { text: "Monday", day: 1 },
      sprintStartDayOptions: [
        { text: "Monday", day: 1 },
        { text: "Tuesday", day: 2 },
        { text: "Wednesday", day: 3 },
        { text: "Thursday", day: 4 },
        { text: "Friday", day: 5 },
        { text: "Saturday", day: 6 },
        { text: "Sunday", day: 0 }
      ],
      version: "",
      repository: ""
    };
  },
  methods: {
    getProjectData() {
      let token = localStorage.getItem("token");
      let selectedProjectId = this.$route.params.projectId;

      const headers = {
        "Content-Type": "application/json",
        Authorization: "Bearer " + token
      };

      this.axios
        .get(`${this.appUrl}/api/v1/project/` + selectedProjectId, {
          headers
        })
        .then(response => {
          this.name = response.data.data.name;
          this.description = response.data.data.description;
          this.sprintDuration = {
            text: "",
            days: response.data.data.sprint_duration
          };
          this.sprintStartDay = {
            text: "",
            day: response.data.data.sprint_start_day
          };
          this.repository = response.data.data.repository;
          this.version = response.data.data.version;
        });
    },
    submit() {
      let token = localStorage.getItem("token");
      let data = {
        name: this.name,
        description: this.description,
        sprint_duration: this.sprintDuration,
        sprint_start_day: this.sprintStartDay
      };

      console.log(data);

      const headers = {
        "Content-Type": "application/json",
        Authorization: "Bearer " + token
      };

      //   this.axios
      //     .post(`${this.appUrl}/api/v1/project/update`, data, { headers })
      //     .then(response => {
      //       this.$router.go(-1);
      //     })
      //     .catch(function(error) {
      //       console.log(error);
      //     });
    }
  }
};
</script>
