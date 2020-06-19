<template>
  <div>
    <v-toolbar flat class="top-navbar">
      <v-toolbar-title class="grey--text">
        <div class="pl-5">
          <div class="pl-5">
            <a
              href="/"
              class="title white--text"
              style="text-decoration:none"
            >Reeze Project Management Tool</a>
          </div>
        </div>
      </v-toolbar-title>
      <!-- <v-toolbar-items>
        <div class="px-2"></div>
        <ProjectSelector v-if="isOnProjectPage" />
      </v-toolbar-items> -->
      <div class="flex-grow-1"></div>

      <v-menu offset-y>
        <template v-slot:activator="{ on }">
          <v-btn icon v-on="on">
            <v-icon color="white">person</v-icon>
          </v-btn>
        </template>

        <v-list>
          <v-list-item v-for="n in 1" :key="n" @click="logout()">
            <v-list-item-title>Log Out</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
      <template v-if="isOnProjectPage" #extension>
        <v-toolbar-items>
          <v-tabs background-color="#2f82e2" grow v-model="active_tab">
            <router-link
              v-for="tab of tabs"
              :key="tab.id"
              :to="tab.route"
              tag="v-tab"
              class="white--text"
            >{{tab.name}}</router-link>
          </v-tabs>
        </v-toolbar-items>
      </template>
    </v-toolbar>
  </div>
</template>

<script>
/* eslint-disable */
import ProjectSelector from "../components/ProjectSelector";

export default {
  created() {
    this.active_tab = this.activeTab();
  },
  data() {
    return {
      sideDrawer: false,
      links: [
        { icon: "dashboard", text: "Dashboard", route: "/" },
        // {icon: 'folder', text: 'Projects', route: '/projects' },
        { icon: "group", text: "Teams", route: "/teams" },
        { icon: "person", text: "Individual", route: "/individuals" }
      ],
      tabs: [
        { id: 1, name: "Board", route: "board" },
        { id: 2, name: "Team Report", route: "teamAnalytics" },
        { id: 3, name: "Personal Report", route: "personalAnalytics" },
        { id: 4, name: "Project Merge", route: "projectMerge" },
        { id: 5, name: "Project Setting", route: "projectSetting" }
      ],
      active_tab: 0
    };
  },
  components: {
    ProjectSelector
  },
  computed: {
    isOnProjectPage() {
      return this.$route.path.includes("/project");
    }
  },
  methods: {
    logout() {
      let token = localStorage.getItem("token");

      const headers = {
        "Content-Type": "application/json",
        Authorization: "Bearer " + token
      };

      this.axios
        .post(
          `${this.appUrl}/api/v1/logout`,
          { headers },
          {
            data: {
              token: token
            }
          }
        )
        .then(response => {
          localStorage.removeItem("token");
          window.location.href = "/";
        })
        .catch(function(error) {
          console.error(error);
        });
    },
    activeTab() {
      let tab = null;
      if (this.$route.path.includes("/board")) {
        tab = 0;
      } else if (this.$route.path.includes("/teamAnalytics")) {
        tab = 1;
      } else if (this.$route.path.includes("/personalAnalytics")) {
        tab = 2;
      } else if (this.$route.path.includes("/projectMerge")) {
        tab = 3;
      } else if (this.$route.path.includes("/projectSetting")) {
        tab = 4;
      }
      return tab;
    }
  }
};
</script>
<style>
.top-navbar {
  background-color: #2f82e2 !important;
}

.v-tabs-slider {
  background-color: white !important;
}
</style>
