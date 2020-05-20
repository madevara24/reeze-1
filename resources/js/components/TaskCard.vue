<template>
  <div class="board-card">
    <v-sheet color="white px-3" width="inherit">
      <v-row>
        <template>
          <v-btn class="ma-1" color="primary" dark icon @click="isExtended = !isExtended">
            <v-icon v-if="isExtended" dark>arrow_drop_down</v-icon>
            <v-icon v-if="!isExtended" dark>arrow_right</v-icon>
          </v-btn>
        </template>
        <template v-if="!isExtended">
          <span class="subtitle-1 d-flex align-center board-card-title">{{card.title}}</span>
          <v-spacer></v-spacer>
          <v-btn class="mt-2 mr-2 d-flex align-right" depressed disabled color="primary" text small>{{cardState}}</v-btn>
        </template>
        <template v-if="isExtended">
          <v-text-field v-model="title" class="mr-3" value="title"></v-text-field>
        </template>
      </v-row>
      <template v-if="isExtended">
        <div class="text-center mb-2 mt-2 pb-2 pt-2">
          <v-progress-circular v-if="loading" color="primary" :indeterminate="indeterminate"></v-progress-circular>
        </div>
        <v-form v-if="!loading">
          <v-row class="my-n3">
            <v-col cols="12">
              <v-text-field class="ml-2" single-line dense readonly v-model="card.id" hide-details>
                <template v-slot:append-outer>
                  <v-btn icon @click="copyCardId()">
                    <v-icon>file_copy</v-icon>
                  </v-btn>
                </template>
              </v-text-field>
              <input type="hidden" id="cardIdSelector" :value="card.id" />
            </v-col>
          </v-row>
          <v-row class="my-n3">
            <v-col cols="5" class="d-flex align-center">
              <div class="caption">State</div>
            </v-col>
            <v-col cols="7" class="py-1 my-1">
              <v-select
                dense
                v-model="cardState"
                :items="cardStates"
                @change="changeCardState"
                hide-details
                class="pt-1"
              >
                <template v-slot:prepend>
                  <v-btn color="info" depressed dark small>{{cardState}}</v-btn>
                </template>
              </v-select>
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
                readonly
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
          <v-row class="my-1">
            <v-col cols="5" class="d-flex align-center">
              <div class="caption">Branch</div>
            </v-col>
            <v-col cols="7" class="py-1 my-1">
              <v-text-field v-model="githubBranch" v-if="githubBranchName === null">
                <template v-slot:append>
                  <v-btn color="primary" dark small @click="modal = true">Attach</v-btn>
                </template>
              </v-text-field>
              <v-text-field
                id="branchName"
                v-model="githubBranchName"
                readonly
                v-if="githubBranchName !== null"
              >
                <template v-slot:append-outer>
                  <v-btn icon @click="copyGithubBranchName()">
                    <v-icon>file_copy</v-icon>
                  </v-btn>
                </template>
              </v-text-field>
            </v-col>
          </v-row>
          <v-row class>
            <v-col cols="12">
              <div class="caption">Description</div>
              <v-textarea v-model="description" outlined></v-textarea>
            </v-col>
          </v-row>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn text @click="isExtended = !isExtended">Close</v-btn>
            <v-btn class="primary" text @click="saveCard">Save</v-btn>
          </v-card-actions>
        </v-form>
      </template>
    </v-sheet>
    <v-snackbar v-model="copyCardIdSnackbar.isUp" :timeout="2000">
      {{ copyCardIdSnackbar.message }}
      <v-btn color="blue" text @click="copyCardIdSnackbar.isUp = false">Close</v-btn>
    </v-snackbar>

    <v-dialog v-model="modal" persistent max-width="600px">
      <v-card>
        <v-card-title>Are You Sure?</v-card-title>
        <v-card-text>Accepting this will attaching this card to github and creating "{{githubBranch}}" branch.</v-card-text>
        <v-card-text>
          <strong>Cannot be undone.</strong>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="close">Close</v-btn>
          <v-btn class="primary" text @click="attachBranch">Save</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
/*eslint-disable */
export default {
  created() {
    this.owner = { user_id: this.card.owner, username: "" };
    this.getRequesterCreds();
  },
  props: {
    card: Object,
    projectMembers: Array,
    user: Object
  },
  data() {
    return {
      modal: false,
      isExtended: false,
      loading: true,
      indeterminate: true,
      copyCardIdSnackbar: {
        isUp: false,
        message: null
      },
      githubBranch: "",
      githubBranchName: this.card.github_branch_name,
      cardState: this.card.state,
      cardStates: [
        "Created",
        "Planned",
        "Started",
        "Finished",
        "Accepted",
        "Rejected"
      ],
      point: parseInt(this.card.points),
      points: [0, 1, 3, 5, 8],
      title: this.card.title,
      description: this.card.description,
      selectedRequester: "",
      requester: null,
      owner: "",
      type: this.card.type.charAt(0).toUpperCase() + this.card.type.slice(1),
      types: ["Feature", "Bug"]
    };
  },
  methods: {
    copyCardId() {
      let copiedCardId = document.querySelector("#cardIdSelector");
      copiedCardId.setAttribute("type", "text");
      copiedCardId.select();

      try {
        var success = document.execCommand("copy");
        var message = success ? "successful" : "unsuccessful";
        this.copyCardIdSnackbar.message = "Card ID copied to clipboard";
      } catch (error) {
        this.copyCardIdSnackbar.message = "Fail to copy Card ID";
      }
      copiedCardId.setAttribute("type", "hidden");
      window.getSelection().removeAllRanges();
      this.copyCardIdSnackbar.isUp = true;
    },
    copyGithubBranchName() {
      let githubBranchName = document.querySelector("#branchName");
      githubBranchName.removeAttribute("readonly");
      githubBranchName.select();

      try {
        var success = document.execCommand("copy");
        var message = success ? "successful" : "unsuccessful";
        this.copyCardIdSnackbar.message =
          "Github Branch Name copied to clipboard";
      } catch (error) {
        this.copyCardIdSnackbar.message = "Fail to copy Github Branch Name";
      }
      githubBranchName.setAttribute("readonly", true);
      window.getSelection().removeAllRanges();
      this.copyCardIdSnackbar.isUp = true;
    },
    getRequesterCreds() {
      let token = localStorage.getItem("token");
      let selectedProjectId = this.$route.params.projectId;

      const headers = {
        "Content-Type": "application/json",
        Authorization: "Bearer " + token
      };

      this.axios
        .get(`${this.appUrl}/api/v1/user/${this.card.requester}`, {
          headers
        })
        .then(response => {
          this.requester = {
            id: response.data.data.user_id,
            name: response.data.data.username
          };

          this.selectedRequester = [this.requester];
          this.loading = false;
        });
    },
    attachBranch() {
      let token = localStorage.getItem("token");
      let selectedProjectId = this.$route.params.projectId;

      let data = {
        branch_name: this.githubBranch
      };

      const headers = {
        "Content-Type": "application/json",
        Authorization: "Bearer " + token
      };

      this.axios
        .post(
          `${this.appUrl}/api/v1/project/${selectedProjectId}/card/${this.card.id}/create-branch`,
          data,
          { headers }
        )
        .then(response => {
          this.githubBranchName = response.data.data;
          this.copyCardIdSnackbar.message = "Github branch has been attached";
          this.copyCardIdSnackbar.isUp = true;
          this.modal = false;
        });
    },
    close() {
      this.modal = false;
    },
    saveCard() {
      let token = localStorage.getItem("token");
      let selectedProjectId = this.$route.params.projectId;
      let data = {
        title: this.title,
        owner: this.owner.user_id,
        github_branch_name: this.githubBranchName,
        description: this.description,
        points: this.point,
        type: this.type
      };

      const headers = {
        "Content-Type": "application/json",
        Authorization: "Bearer " + token
      };
      this.axios
        .put(
          `${this.appUrl}/api/v1/project/${selectedProjectId}/card/${this.card.id}/edit`,
          data,
          { headers }
        )
        .then(response => {
          this.$emit("updateCard", { id: this.card.id, data: data });
          this.copyCardIdSnackbar.message = "Card has been updated";
          this.copyCardIdSnackbar.isUp = true;
        });
    },
    changeCardState() {
      let token = localStorage.getItem("token");
      let selectedProjectId = this.$route.params.projectId;
      let data = {
        state: this.cardState
      };

      const headers = {
        "Content-Type": "application/json",
        Authorization: "Bearer " + token
      };
      this.axios
        .post(
          `${this.appUrl}/api/v1/project/${selectedProjectId}/card/${this.card.id}/update-state`,
          data,
          { headers }
        )
        .then(response => {
          this.$emit("stateChanged", {
            id: this.card.id,
            state: this.cardState
          });
          this.copyCardIdSnackbar.message = "Card state has been changed";
          this.copyCardIdSnackbar.isUp = true;
        });
    }
  },
  computed: {
    upperCaseWord: {
      get() {
        return this.card.type.charAt(0).toUpperCase() + this.card.type.slice(1);
      },
      set(value) {
        this.value = value;
      }
    }
  },
  watch: {
    user: function(value) {
      this.user = value;
    }
  }
};
</script>

<style scoped>
.board-card {
  padding: 10px 10px;
}

.board-card-title {
  display: inline-block;
  max-width: 60%;
  white-space: nowrap;
  overflow: hidden !important;
  text-overflow: ellipsis;
}
</style>
