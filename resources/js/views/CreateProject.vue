<template>
  <v-dialog v-model="value" persistent max-width="500px">
    <v-card>
      <v-card-title>
        <h2 class="mt-5 mb-5">Create a Project</h2>
      </v-card-title>
      <v-card-text>
        <v-form @submit.prevent ref="form" v-model="valid">
          <v-row>
            <v-col cols="12" class="py-1">
              <v-label class="pt-1">Project Name</v-label>
              <v-text-field
                outlined
                dense
                :rules="nameRules"
                v-model="name"
                placeholder="e.g My Awesome Project"
                required
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" class="py-1">
              <v-label class="pt-1">Repository</v-label>
              <v-select
                :items="repositories"
                v-model="repository"
                :rules="repositoryRules"
                outlined
                dense
                placeholder="Choose from your listed Github repository"
                class="input-group--focused"
                item-value="text"
              ></v-select>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" class="py-1">
              <v-label class="pt-1">Description</v-label>
              <v-textarea
                height="100"
                outlined
                dense
                :rules="descriptionRules"
                v-model="description"
                placeholder="Description"
                required
              ></v-textarea>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" md="6" class="py-1">
              <v-label class="pt-1">Sprint Duration</v-label>
              <v-select
                :items="sprintDurationOptions"
                v-model="sprintDuration"
                item-text="text"
                outlined
                dense
                item-value="days"
                class="input-group--focused"
              ></v-select>
            </v-col>

            <v-col cols="12" md="6" class="py-1">
              <v-label class="pt-1">Sprint Start Day</v-label>
              <v-select
                :items="sprintStartDayOptions"
                v-model="sprintStartDay"
                item-text="text"
                outlined
                dense
                item-value="day"
                class="input-group--focused"
              ></v-select>
            </v-col>
          </v-row>
          <v-row justify="end" class="my-5">
            <v-btn class="mr-4" @click="cancel()" text>Cancel</v-btn>
            <v-btn class="mr-4" @click="submit()" color="primary">Submit</v-btn>
          </v-row>
        </v-form>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>

<script>
/* eslint-disable no-console */

export default {
  created() {
    this.getRepository();
  },
  props: {
    value: Boolean
  },
  data() {
    return {
      valid: true,
      name: "",
      nameRules: [v => !!v || "Project name is required"],
      description: "",
      descriptionRules: [v => !!v || "Description is required"],
      sprintDuration: 7,
      sprintDurationOptions: [
        { text: "1 Week", days: 7 },
        { text: "2 Weeks", days: 14 },
        { text: "3 Weeks", days: 21 },
        { text: "4 Weeks", days: 28 }
      ],

      sprintStartDay: 1,
      sprintStartDayOptions: [
        { text: "Monday", day: 1 },
        { text: "Tuesday", day: 2 },
        { text: "Wednesday", day: 3 },
        { text: "Thursday", day: 4 },
        { text: "Friday", day: 5 },
        { text: "Saturday", day: 6 },
        { text: "Sunday", day: 0 }
      ],
      repository: "",
      repositoryRules: [v => !!v || "Repository is required."],
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
          this.repositories = response.data.data;
        });
    },
    submit() {
      this.$refs.form.validate();
      if (this.valid) {
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
            this.$emit("input", false);
            this.$emit("getProjects");
          });
      }
    },
    cancel() {
      this.$emit("input", false);
    }
  }
};
</script>
