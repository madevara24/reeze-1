<template>
  <v-hover class="mr-5" v-slot:default="{ hover }">
    <v-card
      class="mb-5"
      flat
      @click="selectProject(projectData.id)"
      outlined
      :elevation="hover ? 12 : 0"
    >
      <v-card-title style="color:black" class="px-5">
        <div>
          <v-row class="ml-2">{{projectData.name}}</v-row>
          <v-row class="ml-2 pt-2">
            <div class="caption grey--text">
              <v-avatar
                color="#2F82E2"
                class="headline font-weight-light white--text mr-2"
                size="30"
              >
                <span class="body-1">{{ projectData.pic_name.charAt(0) }}</span>
              </v-avatar>
              {{projectData.pic_name}}
            </div>
          </v-row>
        </div>
      </v-card-title>
      <v-card-text>
        <v-row class="ml-auto">
          <v-col
            class="font-weight-medium"
            cols="12"
            sm="12"
          >Sprint day {{sprintDays.currentDay}} of {{sprintDays.totalDays}}</v-col>
        </v-row>
        <v-row class="ml-auto">
          <v-col
            class="font-weight-medium"
            cols="12"
            sm="12"
          >Task Progression : {{projectData.completedTasks}}/{{projectData.totalTasks}}</v-col>
        </v-row>
      </v-card-text>
      <v-card-actions></v-card-actions>
    </v-card>
  </v-hover>
</template>

<script>
/* eslint-disable */
export default {
  created() {
    this.getSprintDays();
    this.getSprintProgression();
  },
  props: {
    projectData: null
  },
  data() {
    return{
      sprintDays: {
        currentDay: 0,
        totalDays: 0
      },
      sprintProgression: null
    }
  },
  methods: {
    selectProject(id) {
      console.log(
        "Component DashboardProjectCard (methods) : Clicked Project ID " +
          this.projectData.id
      );
      this.$router.push({ name: "board", params: { projectId: id } });
    },
    getSprintDays(){
      let token = localStorage.getItem("token");
      const headers = {
        "Content-Type": "application/json",
        Authorization: "Bearer " + token
      };

      this.axios
        .get(`${this.appUrl}/api/v1/project/${this.projectData.id}/analytic/sprint-day`, { headers })
        .then(response => (this.formatSprintDays(response.data)));
    },
    formatSprintDays(data){
      console.log(data.data);
      this.sprintDays.currentDay = data.data;
      this.sprintDays.totalDays = this.projectData.sprint_duration
    },
    getSprintProgression(){
      let token = localStorage.getItem("token");
      const headers = {
        "Content-Type": "application/json",
        Authorization: "Bearer " + token
      };

      this.axios
        .get(`${this.appUrl}/api/v1/project/${this.projectData.id}/analytic/sprint-progression/`, { headers })
        .then(response => (this.formatSprintDays(response.data.data)));
    },
    formatSprintProgression(data){
      console.log(data);
    }
  }
};
</script>
