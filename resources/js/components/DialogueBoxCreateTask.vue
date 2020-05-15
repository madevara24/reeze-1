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
                <v-text-field v-model="title" class="mr-3"></v-text-field>
              </v-col>
            </v-row>
            <v-row class="my-n3">
              <v-col cols="5" class="d-flex align-center">
                <div class="caption">Card Type</div>
              </v-col>
              <v-col cols="7" class="py-1 my-1">
                <v-select dense v-model="type" :items="types" hide-details class="pt-1"></v-select>
              </v-col>
            </v-row>
            <v-row class="my-n3">
              <v-col cols="5" class="d-flex align-center">
                <div class="caption">Points</div>
              </v-col>
              <v-col cols="7" class="py-1 my-1">
                <v-select dense v-model="point" :items="points" hide-details class="pt-1"></v-select>
              </v-col>
            </v-row>
            <v-row class="my-n3">
              <v-col cols="5" class="d-flex align-center">
                <div class="caption">Requester</div>
              </v-col>
              <v-col cols="7" class="py-1 my-1">
                <v-select
                  dense
                  v-model="requester"
                  item-text="name"
                  item-value="id"
                  :items="selectedRequester"
                  class="pt-1"
                ></v-select>
              </v-col>
            </v-row>
            <v-row class="my-n3">
              <v-col cols="5" class="d-flex align-center">
                <div class="caption">Owner</div>
              </v-col>
              <v-col cols="7" class="py-1 my-1">
                <v-select
                  dense
                  v-model="owner"
                  :items="projectMembers"
                  item-text="username"
                  item-value="user_id"
                  hide-details
                  class="pt-1"
                ></v-select>
              </v-col>
            </v-row>
            <v-row class>
              <v-col cols="12">
                <div class="caption">Description</div>
                <v-textarea v-model="description" outlined></v-textarea>
              </v-col>
            </v-row>
          </v-form>
        </v-container>
        <small>*indicates required field</small>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" text @click="close">Close</v-btn>
        <v-btn class="primary" text @click="save">Save</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
/*eslint-disable */
export default {
  created() {
    this.getProjectMembers();
  },
  mounted() {
    this.requester = { id: this.user.id, name: this.user.name };
    this.selectedRequester = [this.requester];
  },
  props: {
    value: Boolean,
    list1: Array,
    user: Object
  },
  data() {
    return {
      point: 0,
      points: [0, 1, 3, 5, 8],
      title: "",
      description: "",
      selectedRequester: "",
      requester: null,
      owner: "",
      projectMembers: [],
      type: "",
      types: ["Feature", "Bug"]
    };
  },
  methods: {
    save() {
      let token = localStorage.getItem("token");
      let selectedProjectId = this.$route.params.projectId;
      let data = {
        title: this.title,
        owner: this.owner,
        description: this.description,
        points: this.point,
        type: this.type
      };

      const headers = {
        "Content-Type": "application/json",
        Authorization: "Bearer " + token
      };

      this.axios
        .post(
          `${this.appUrl}/api/v1/project/${selectedProjectId}/card/create`,
          data,
          { headers }
        )
        .then(response => {
          let newCardData = response.data.card;
          this.$emit("update", newCardData);
          this.$emit("input", false);
        })
    },
    close() {
      this.$emit("input", false);
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
        .then(response => (this.projectMembers = response.data.data));
    }
  },
  watch: {
    user: function(value) {
      this.user = value;
      this.requester = { id: this.user.id, name: this.user.name };
      this.selectedRequester = [this.requester];

    }
  }
};
</script>
