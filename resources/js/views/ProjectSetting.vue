<template>
  <v-container fluid style="width:75%">
    <v-row>
      <v-col cols="12" class="pb-0">
        <h2>Project Setting</h2>
      </v-col>
      <v-col cols="12" class="pt-0">
        <h4 class="grey--text">{{name}}</h4>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="6">
        <v-form @submit.prevent ref="form" v-model="valid">
          <v-row>
            <v-col cols="12" class="py-1">
              <v-label class="pt-1">Project Name</v-label>
              <v-text-field
                background-color="white"
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
              <v-text-field outlined readonly background-color="white" v-model="repository"></v-text-field>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" class="py-1">
              <v-label class="pt-1">Description</v-label>
              <v-textarea
                outlined
                background-color="white"
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
              <v-select
                :items="sprintDurationOptions"
                v-model="sprintDuration"
                item-text="text"
                outlined
                background-color="white"
                dense
                item-value="days"
                label="Sprint Duration"
                class="input-group--focused"
              ></v-select>
            </v-col>

            <v-col cols="12" md="6" class="py-1">
              <v-select
                :items="sprintStartDayOptions"
                v-model="sprintStartDay"
                item-text="text"
                outlined
                background-color="white"
                dense
                item-value="day"
                label="Sprint Start Day"
                class="input-group--focused"
              ></v-select>
            </v-col>
          </v-row>
          <v-row justify="end" class="my-5">
            <v-btn class="mr-4" @click="submit()" width="150" height="50" color="primary">Submit</v-btn>
          </v-row>
        </v-form>
      </v-col>
      <v-snackbar v-model="snackbar.isUp" :timeout="2000">
        {{ snackbar.message }}
        <v-btn color="blue" text @click="snackbar.isUp = false">Close</v-btn>
      </v-snackbar>
      <v-spacer></v-spacer>
      <v-col cols="5" class="ml-5">
        <h3>Project Members</h3>
        <v-autocomplete
          v-model="user"
          :items="users"
          :search-input.sync="search"
          hide-details
          hide-selected
          solo
          chips
          clearable
          multiple
          item-text="name"
          item-value="id"
          label="Search github username"
          placeholder="Start typing to Search"
          return-object
        >
          <template v-slot:no-data>
            <v-list-item>
              <v-list-item-title>Search for project member</v-list-item-title>
            </v-list-item>
          </template>
          <template v-slot:selection="{ attr, on, item, selected }">
            <v-chip
              v-bind="attr"
              :input-value="selected"
              color="blue-grey"
              class="white--text"
              v-on="on"
            >
              <span v-text="item.name"></span>
            </v-chip>
          </template>
          <template v-slot:item="{ item }">
            <v-list-item-avatar
              color="indigo"
              class="headline font-weight-light white--text"
            >{{ item.name.charAt(0) }}</v-list-item-avatar>
            <v-list-item-content>
              <v-list-item-title v-text="item.name"></v-list-item-title>
              <v-list-item-subtitle v-text="item.symbol"></v-list-item-subtitle>
            </v-list-item-content>
          </template>
        </v-autocomplete>
        <v-data-table
          :headers="headers"
          :items="projectMembers"
          hide-default-footer
          hide-default-header
          class="elevation-0 mt-4 transparent"
          item-key="username"
          :loading="loading"
          loading-text="Loading... Please wait"
        >
          <template v-slot:item.action="{ item }">
            <v-icon small @click="deleteMember(item)">mdi-delete</v-icon>
          </template>
        </v-data-table>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
/* eslint-disable no-console */

export default {
  created() {
    this.getProjectData();
    this.getProjectMembers();
  },
  data() {
    return {
      valid: true,
      user: null,
      users: [],
      search: null,
      tab: null,
      loading: true,

      projectMembers: [],
      headers: [
        { align: "start", value: "username" },
        { align: "end", value: "action", sortable: false }
      ],

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
      version: "",
      repository: "",
      snackbar: {
        isUp: false,
        message: null
      }
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
          this.sprintDuration = response.data.data.sprint_duration;
          this.sprintStartDay = response.data.data.sprint_start_day;
          this.repository = response.data.data.repository;
          this.version = response.data.data.version;
        });
    },
    getProjectMembers() {
      let token = localStorage.getItem("token");
      const headers = {
        "Content-Type": "application/json",
        Authorization: "Bearer " + token
      };

      this.axios
        .get(
          `${this.appUrl}/api/v1/project/` +
            this.$route.params.projectId +
            "/members",
          { headers }
        )
        .then(response => {
          this.loading = false;
          this.projectMembers = response.data.data;
        });
    },
    submit() {
      this.$$refs.form.validate();
      if (this.valid) {
        let token = localStorage.getItem("token");
        let selectedProjectId = this.$route.params.projectId;

        let data = {
          name: this.name,
          description: this.description,
          sprint_duration: this.sprintDuration,
          sprint_start_day: this.sprintStartDay
        };

        const headers = {
          "Content-Type": "application/json",
          Authorization: "Bearer " + token
        };

        this.axios
          .put(
            `${this.appUrl}/api/v1/project/${selectedProjectId}/edit`,
            data,
            {
              headers
            }
          )
          .then(response => {
            this.snackbar.message = "Project setting has been updated";
            this.snackbar.isUp = true;
          });
      }
    },
    add() {
      let token = localStorage.getItem("token");
      let selectedProjectId = this.$route.params.projectId;

      let data = {
        users: this.user
      };

      const headers = {
        "Content-Type": "application/json",
        Authorization: "Bearer " + token
      };

      this.axios
        .post(
          `${this.appUrl}/api/v1/project/${selectedProjectId}/add-members`,
          data,
          {
            headers
          }
        )
        .then(response => {
          this.snackbar.message = "User(s) has been added to this project.";
          this.snackbar.isUp = true;
          this.getProjectMembers();
        });
    },
    deleteMember(item) {
      let token = localStorage.getItem("token");
      let selectedProjectId = this.$route.params.projectId;

      let data = {
        user_id: item.user_id
      };

      const headers = {
        "Content-Type": "application/json",
        Authorization: "Bearer " + token
      };

      this.axios
        .delete(
          `${this.appUrl}/api/v1/project/${selectedProjectId}/remove-member`,
          {
            headers,
            data
          }
        )
        .then(response => {
          this.snackbar.message = `Member with username ${item.username} has been removed from this project.`;
          this.snackbar.isUp = true;
          this.getProjectMembers();
        });
    }
  },
  watch: {
    user(val) {
      if (val != null) this.tab = 0;
      else this.tab = null;
    },
    search(val) {
      let token = localStorage.getItem("token");

      const headers = {
        "Content-Type": "application/json",
        Authorization: "Bearer " + token
      };

      let data = {
        name: this.search
      };

      this.axios
        .post(`${this.appUrl}/api/v1/search`, data, {
          headers
        })
        .then(response => {
          this.users = response.data;
        });
    }
  }
};
</script>
