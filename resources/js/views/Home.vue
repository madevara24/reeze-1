<template>
  <div>
    <v-container class="my-5 py-5" fluid style="width: 80%">
      <v-row class="mt-5 pt-5" justify="space-between">
        <h1>My Projects</h1>
        <v-btn class="white--text" @click="dialog = true">
          <span class="body-2">+ Add Project</span>
        </v-btn>
      </v-row>
      <v-row>
        <span class="body-2 grey--text">List of your projects will be shown here.</span>
      </v-row>
      <v-row class="mt-5 pt-5">
        <v-col
          class="mr-4"
          cols="12"
          sm="6"
          md="4"
          lg="3"
          xl="2"
          v-for="project in projects"
          :key="project.id"
          style="padding:0"
        >
          <DashboardProjectCard :projectData="project" />
        </v-col>
      </v-row>
    </v-container>
    <PageNoProjects v-if="checkProjects" />
    <CreateProject v-model="dialog" />
  </div>
</template>

<script>
/* eslint-disable no-console */
import DashboardProjectCard from "../components/DashboardProjectCard";
import PageNoProjects from "../components/PageNoProjects";
import CreateProject from "../views/CreateProject";
import lodash from "lodash";

export default {
  created() {
    this.projects = this.getProjects();
    localStorage.setItem("selectedProjectId", null);

    if (this.$store.getters.getUser === null) {
      let user = localStorage.getItem("user");

      localStorage.removeItem("user");
      this.$store.commit("setUser", JSON.parse(user));
    }
  },
  data() {
    return {
      dialog: false,
      projects: []
    };
  },
  methods: {
    getProjects() {
      let token = localStorage.getItem("token");
      const headers = {
        "Content-Type": "application/json",
        Authorization: "Bearer " + token
      };

      this.axios
        .get(`${this.appUrl}/api/v1/project`, { headers })
        .then(response => (this.projects = response.data.data));
    }
  },
  components: {
    DashboardProjectCard,
    PageNoProjects,
    CreateProject
  },
  computed: {
    checkProjects() {
      return _.isEmpty(this.projects) ? true : false;
    }
  }
};
</script>
<style scoped>
.theme--light.v-btn:not(.v-btn--flat):not(.v-btn--text):not(.v-btn--outlined) {
  background-color: #0a60c5;
}
</style>
