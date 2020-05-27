<template>
  <v-dialog v-model="value" persistent max-width="600px">
    <v-card>
      <v-card-title>
        <h2 class="mt-5 mb-5">Add Task</h2>
      </v-card-title>
      <v-card-text>
        <v-form @submit.prevent ref="form" v-model="valid">
          <v-row>
            <v-col cols="12" class="py-1">
              <v-label class="pt-1">Title</v-label>
              <v-text-field
                outlined
                dense
                v-model="title"
                :rules="titleRules"
                hide-details
                placeholder="e.g My Task Title"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" class="py-1">
              <v-label class="pt-1">Card Type</v-label>
              <v-select hide-details outlined dense v-model="type" :items="types"></v-select>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" class="py-1">
              <v-label class="pt-1">Points</v-label>
              <v-select hide-details outlined dense v-model="point" :items="points"></v-select>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" class="py-1">
              <v-label class="pt-1">Requester</v-label>
              <v-select
                hide-details
                outlined
                dense
                v-model="requester"
                item-text="name"
                item-value="id"
                :items="selectedRequester"
              ></v-select>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" class="py-1">
              <v-label class="pt-1">Owner</v-label>
              <v-select
                hide-details
                outlined
                dense
                v-model="owner"
                :items="projectMembers"
                item-text="username"
                item-value="user_id"
              ></v-select>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" class="py-1">
              <v-label class="pt-1">Description</v-label>
              <v-textarea
                outlined
                dense
                v-model="description"
                hide-details
                :rules="descriptionRules"
              ></v-textarea>
            </v-col>
          </v-row>
        </v-form>
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
      valid: true,
      point: 0,
      points: [0, 1, 3, 5, 8],
      title: "",
      titleRules: [v => !!v || "Task title is required"],
      description: "",
      descriptionRules: [v => !!v || "Description is required"],
      selectedRequester: "",
      requester: null,
      owner: "",
      projectMembers: [],
      type: "Feature",
      types: ["Feature", "Bug"]
    };
  },
  methods: {
    save() {
      this.$refs.form.validate();
      if (this.valid) {
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
          });
      }
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
