<template>
  <div>
    <v-container class="my-5">
      <v-row justify="end">
          <v-btn color="primary">Add Project</v-btn>
      </v-row>
      <v-row >
        <v-col cols="12" sm="6" md="4" lg="3" xl="2" v-for="project in projects" :key="project.name">
          <DashboardProjectCard  :projectData="project"/>          
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
/* eslint-disable no-console */
import DashboardProjectCard from '../components/DashboardProjectCard'

export default {
  created() {
    //sujen titip
    // console.log(this.$store.getters.getIsLogin)
    this.projects = this.getProjects()
    // this.axios
    //   .get('http://127.0.0.1:8000/api/v1/project')
    //   .then(response => (console.log(response.data)))
  },
  data() {
    return {
      projects: null
    }
  },
  methods: {
    getProjects(){
      return this.$store.getters.projectData
    }
  },
  components: {
    DashboardProjectCard
  },
};
</script>
