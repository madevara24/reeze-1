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
          <v-btn class="mt-2 mr-2 d-flex align-right" color="primary" dark small>{{cardState}}</v-btn>
        </template>
        <template v-if="isExtended">
          <v-text-field v-model="title" class="mr-3" value="title"></v-text-field>
        </template>
      </v-row>
      <template v-if="isExtended">
        <v-form>
          <v-row class="my-n3">
            <v-col cols="12">
              <v-text-field class="ml-2" single-line dense readonly v-model="card.id" hide-details>
                <template v-slot:append-outer>
                  <v-btn icon @click="copyCardId()">
                    <v-icon>file_copy</v-icon>
                  </v-btn>
                  <v-btn icon>
                    <v-icon>delete</v-icon>
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
              <v-select dense v-model="cardState" :items="cardStates" hide-details class="pt-1">
                <template v-slot:prepend>
                  <v-btn color="primary" dark small>{{cardState}}</v-btn>
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
              <v-select dense hide-details class="pt-1"></v-select>
            </v-col>
          </v-row>
          <v-row class>
            <v-col cols="12">
              <div class="caption">Description</div>
              <v-textarea v-model="description" outlined></v-textarea>
            </v-col>
          </v-row>
        </v-form>
      </template>
    </v-sheet>
    <v-snackbar v-model="copyCardIdSnackbar.isUp" :timeout="2000">
      {{ copyCardIdSnackbar.message }}
      <v-btn color="blue" text @click="copyCardIdSnackbar.isUp = false">Close</v-btn>
    </v-snackbar>
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
      isExtended: false,
      copyCardIdSnackbar: {
        isUp: false,
        message: null
      },
      cardState: this.card.state,
      cardStates: [
        "Created",
        "Planned",
        "Started",
        "Finished",
        "Accepted",
        "Rejected",
        "Released"
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