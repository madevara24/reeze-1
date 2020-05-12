<template>
  <div>
    <v-container class="my-5">
      <v-row justify="end">
        <v-btn color="primary" @click="createProject()">Add Project</v-btn>
      </v-row>
      <v-row>
        <v-col cols="12" sm="6" md="4" lg="3" xl="2" v-for="project in projects" :key="project.id">
          <DashboardProjectCard :projectData="project" />
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
/* eslint-disable no-console */
import DashboardProjectCard from "../components/DashboardProjectCard";

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
      projects: null
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
    },
    createProject() {
      this.$router.push({ name: "createProject" });
    }
  },
  components: {
    DashboardProjectCard
  }
};
</script>
