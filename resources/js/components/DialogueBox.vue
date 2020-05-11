<template>
  <v-dialog v-model="value" persistent max-width="600px">
    <v-card>
      <v-card-title>
        <span class="headline">User Profile</span>
      </v-card-title>
      <v-card-text>
        <v-container>
          <v-form width="100%">
            <v-row class="my-n3">
              <v-col cols="5" class="d-flex align-center">
                <div class="caption">Title</div>
              </v-col>
              <v-col cols="7" class="py-1 my-1">
                <v-text-field class="mr-3"></v-text-field>
              </v-col>
            </v-row>
            <v-row class="my-n3">
              <v-col cols="5" class="d-flex align-center">
                <div class="caption">Card Type</div>
              </v-col>
              <v-col cols="7" class="py-1 my-1">
                <v-select dense :items="select.type" hide-details class="pt-1"></v-select>
              </v-col>
            </v-row>
            <v-row class="my-n3">
              <v-col cols="5" class="d-flex align-center">
                <div class="caption">Points</div>
              </v-col>
              <v-col cols="7" class="py-1 my-1">
                <v-select dense :items="select.points" hide-details class="pt-1"></v-select>
              </v-col>
            </v-row>
            <v-row class="my-n3">
              <v-col cols="5" class="d-flex align-center">
                <div class="caption">Requester</div>
              </v-col>
              <v-col cols="7" class="py-1 my-1">
                <v-select dense :items="select.teamMembers" hide-details class="pt-1"></v-select>
              </v-col>
            </v-row>
            <v-row class="my-n3">
              <v-col cols="5" class="d-flex align-center">
                <div class="caption">Owner</div>
              </v-col>
              <v-col cols="7" class="py-1 my-1">
                <v-select dense :items="select.teamMembers" hide-details class="pt-1"></v-select>
              </v-col>
            </v-row>
            <v-row class>
              <v-col cols="12">
                <div class="caption">Description</div>
                <v-textarea
                  outlined
                  value="The Woodman set to work at once, and so sharp was his axe that the tree was soon chopped nearly through."
                ></v-textarea>
              </v-col>
            </v-row>
          </v-form>
        </v-container>
        <small>*indicates required field</small>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" text @click="close">Close</v-btn>
        <v-btn color="blue darken-1" text @click="save">Save</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
/*eslint-disable */
export default {
  props: {
    value: Boolean,
    list1: Array
  },
  data() {
    return {
      newCardData: null,
      select: {
        points: [0, 1, 3, 5, 8],
        teamMembers: ["Devara", "Zainokta", "Masjul", "Subosko", "Sendiqi"],
        title: "",
        github_branch_name: null,
        description: "",
        owner: 0,
        iteration: 0,
        type: ["Feature", "Bug"],
      }
    };
  },
  methods: {
    save() {
      let token = localStorage.getItem("token");
      let selectedProjectId = this.$route.params.projectId;

      const headers = {
        "Content-Type": "application/json",
        Authorization: "Bearer " + token
      };

      // this.axios
      //   .post(`${this.appUrl}/api/v1/project/${selectedProjectId}/card/create`, data, { headers })
      //   .then(response => {
      //     console.log(response)
      //   })
      //   .catch(function(error) {
      //     console.log(error);
      //   });
      // this.$emit("update", this.newCardData);
      this.$emit("input", false);
    },
    close() {
      this.$emit("input", false);
    }
  }
};
</script>