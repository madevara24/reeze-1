<template>
<v-hover v-slot:default="{ hover }">
    <v-card class="mb-5" flat @click="selectProject(projectData.id)" outlined :elevation="hover ? 12 : 0">
      <v-card-title style="background:#26c6da;color:white" class="px-5">
        <div>
          <v-row>
          {{projectData.name}}
        </v-row>
        <v-row>
          <div class="caption">Bapak Subandrio</div>
        </v-row>
        </div>
      </v-card-title>
      <v-divider></v-divider>
      <v-card-text>
        <v-row>
          <v-col class="font-weight-medium" cols="12" sm="12">Sprint Progression : {{projectData.sprintProgress}}/{{projectData.sprintDuration}}</v-col>
        </v-row>
        <v-row>
          <v-col class="font-weight-medium" cols="12" sm="12">Task Progression : {{projectData.completedTasks}}/{{projectData.totalTasks}}</v-col>
        </v-row>
      </v-card-text>
      <v-card-actions>
      </v-card-actions>
    </v-card>
  </v-hover>
</template>

<script>
/* eslint-disable */
export default {
  props:{
    projectData: null
  },
  methods: {
    selectProject(id){
      console.log('Clicked: Project' + this.projectData.name)
      this.$store.commit('selectProject', id)
      console.log('Selected project: ' + this.$store.state.selectedProjectId)
      this.$router.push({ name: 'board', params: { id } })
    }
  },
}
</script>