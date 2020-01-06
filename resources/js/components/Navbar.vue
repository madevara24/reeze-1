<template>
  <div>
    <v-toolbar flat>

      <v-toolbar-title class="grey--text">
        <v-btn class="grey--text" :ripple="false" :to="{ name: 'home'}">
          <div class="title">
            <span class="font-weight-light">AU</span>
            <span class="">SOM</span>
          </div>
        </v-btn>
      </v-toolbar-title>
      <v-toolbar-items>
        <div class="px-2"></div>
        <ProjectSelector v-if="isOnProjectPage" />
      </v-toolbar-items>
      <div class="flex-grow-1"></div>

      <v-menu offset-y>
        <template v-slot:activator="{ on }">
          <v-btn icon v-on="on">
            <v-icon>person</v-icon>
          </v-btn>
        </template>

        <v-list>
          <v-list-item
            v-for="n in 5"
            :key="n"
            @click="() => {}"
          >
            <v-list-item-title>Option {{ n }}</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
      <template v-if="isOnProjectPage" #extension>
          <v-toolbar-items>
            <v-tabs grow>
              <router-link to="board" tag="v-tab">
                Board
              </router-link>
              <router-link to="analytics" tag="v-tab">
                Analytics
              </router-link>
              <!-- <v-tab href="board" exact>
                Board
              </v-tab>
              <v-tab href="analytics" exact>
                Analytics
              </v-tab> -->
            </v-tabs>
        </v-toolbar-items>
        </template>
    </v-toolbar>

  </div>
</template>

<script>
/* eslint-disable */
import ProjectSelector from '../components/ProjectSelector'

export default {
  data() {
    return {
      sideDrawer: false,
      links:[
              {icon: 'dashboard', text: 'Dashboard', route: '/' },
              // {icon: 'folder', text: 'Projects', route: '/projects' },
              {icon: 'group', text: 'Teams', route: '/teams' },
              {icon: 'person', text: 'Individual', route: '/individuals' },
          ],
    }
  },
  components:{
    ProjectSelector
  },
  computed: {
    isOnProjectPage(){
      console.log('On project page: ')
      console.log(this.$route.path.includes('/project'))
      return this.$route.path.includes('/project')
    }
  },
  methods: {
  },
}
</script>