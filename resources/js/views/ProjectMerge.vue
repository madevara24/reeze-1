<template>
  <v-row>
    <v-col cols="12" xs="12">
      <v-card flat class="px-3 mb-5">
        <v-card-title>Project Merge</v-card-title>
        <v-container class="text-center">
          <v-form ref="form" v-model="valid" @submit.prevent>
            <v-row class="pb-3 text-right">
              <v-col sm="6">
                <v-select
                  v-model="releaseType"
                  :items="releaseTypes"
                  label="Release Type"
                  :rules="releaseTypeRules"
                  required
                ></v-select>
              </v-col>
              <v-col sm="6">
                <v-btn
                  depressed
                  color="primary"
                  @click="onPressReleaseButton()"
                  :disabled="release"
                >Release</v-btn>
              </v-col>
            </v-row>
            <v-data-table
              v-model="mergeSelect"
              :headers="headers"
              :items="cards"
              show-select
              class="elevation-1"
              :loading="loading"
              loading-text="Loading... Please wait"
            ></v-data-table>
          </v-form>
        </v-container>
      </v-card>
    </v-col>
    <v-snackbar v-model="copyCardIdSnackbar.isUp" :timeout="2000">
      {{ copyCardIdSnackbar.message }}
      <v-btn color="blue" text @click="copyCardIdSnackbar.isUp = false">Close</v-btn>
    </v-snackbar>
  </v-row>
</template>

<script>
export default {
  created() {
    this.getCards();
  },
  watch: {
    $route(to, from) {
      console.log(
        "View Project Merge (watch, route) : Selected Project ID " +
          this.$route.params.projectId
      );
    }
  },
  data() {
    return {
      valid: true,
      loading: true,
      release: false,
      releaseTypeRules: [v => !!v || "Release type is required"],
      headers: [
        { text: "ID", align: "start", value: "id" },
        { text: "Type", value: "type" },
        { text: "Card Name", value: "title" },
        { text: "Requester", value: "requester" },
        { text: "Owner", value: "owner" },
        { text: "State", value: "state" }
      ],
      cards: [],
      releaseType: null,
      releaseTypes: ["Major", "Minor", "Patch"],
      mergeSelect: [],
      copyCardIdSnackbar: {
        isUp: false,
        message: null
      }
    };
  },
  methods: {
    onPressReleaseButton() {
      this.$refs.form.validate();

      let branchName = [];
      this.mergeSelect.forEach(function(item) {
        branchName.push(item.github_branch_name);
      });

      if (this.valid) {
        this.release = true;
        let token = localStorage.getItem("token");
        let selectedProjectId = this.$route.params.projectId;
        let data = {
          card_branch: branchName,
          release_type: this.releaseType
        };
        const headers = {
          "Content-Type": "application/json",
          Authorization: "Bearer " + token
        };
        this.axios
          .post(
            `${this.appUrl}/api/v1/project/${selectedProjectId}/release`,
            data,
            { headers }
          )
          .then(response => {
            console.log(response);
            this.copyCardIdSnackbar.message =
              "Branch merged. Have a nice day ^^";
            this.copyCardIdSnackbar.isUp = true;
            this.release = false;
          });
      }
    },
    getCards() {
      let token = localStorage.getItem("token");
      let selectedProjectId = this.$route.params.projectId;

      const headers = {
        "Content-Type": "application/json",
        Authorization: "Bearer " + token
      };

      this.axios
        .get(
          `${this.appUrl}/api/v1/project/${selectedProjectId}/cards/accepted`,
          {
            headers
          }
        )
        .then(response => {
          this.cards = response.data;
          this.loading = false;
        });
    }
  }
};
</script>
