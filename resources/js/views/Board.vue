<template>
  <v-layout>
    <v-row v-if="loading" align="center" justify="center">
      <div class="text-center mb-2 mt-2 pb-2 pt-2">
        <v-progress-circular color="primary" :indeterminate="indeterminate"></v-progress-circular>
      </div>
    </v-row>
    <v-col cols="12" sm="4" v-if="!loading">
      <v-toolbar>
        <v-toolbar-title>Icebox</v-toolbar-title>
        <v-btn fixed right color="primary" @click="openAddTask">Add Task</v-btn>
      </v-toolbar>
      <v-card class="card-scrollable" color="grey">
        <draggable
          :list="list1"
          group="card"
          v-bind="dragOptions"
          @start="drag = true"
          @end="drag = false"
        >
          <transition-group
            tag="div"
            class="list-group"
            type="transition"
            :name="!drag ? 'flip-list' : null"
          >
            <TaskCard
              v-for="card in list1"
              :key="card.id"
              :card="card"
              :projectMembers="projectMembers"
              @stateChanged="onChangeState"
              @updateCard="onCardUpdate"
              :user="user"
            ></TaskCard>
          </transition-group>
        </draggable>
      </v-card>
    </v-col>
    <v-col cols="12" sm="4" v-if="!loading">
      <v-toolbar>
        <v-toolbar-title>Current Iteration/Backlog</v-toolbar-title>
      </v-toolbar>
      <v-card class="card-scrollable" color="grey">
        <draggable
          :list="list2"
          group="card"
          v-bind="dragOptions"
          @start="drag = true"
          @end="drag = false"
        >
          <transition-group
            tag="div"
            class="list-group"
            type="transition"
            :name="!drag ? 'flip-list' : null"
          >
            <TaskCard
              v-for="card in list2"
              :key="card.id"
              :card="card"
              :projectMembers="projectMembers"
              @stateChanged="onChangeState"
              @updateCard="onCardUpdate"
              :user="user"
            ></TaskCard>
          </transition-group>
        </draggable>
      </v-card>
    </v-col>
    <v-col cols="12" sm="4" v-if="!loading">
      <v-toolbar>
        <v-toolbar-title>Done</v-toolbar-title>
      </v-toolbar>
      <v-card class="card-scrollable" color="grey">
        <draggable
          :list="list3"
          group="card"
          v-bind="dragOptions"
          @start="drag = true"
          @end="drag = false"
        >
          <transition-group
            tag="div"
            class="list-group"
            type="transition"
            :name="!drag ? 'flip-list' : null"
          >
            <TaskCard
              v-for="card in list3"
              :key="card.id"
              :card="card"
              :projectMembers="projectMembers"
              @stateChanged="onChangeState"
              @updateCard="onCardUpdate"
              :user="user"
            ></TaskCard>
          </transition-group>
        </draggable>
      </v-card>
    </v-col>

    <v-col cols="12" sm="4" v-if="!loading">
      <v-toolbar>
        <v-toolbar-title>History</v-toolbar-title>
      </v-toolbar>
      <v-card class="card-scrollable" flat color="grey">
        <v-card v-for="projectLog in projectLogs" :key="projectLog.id" class="my-1 mx-2 py-0 px-3">
          <v-list-item three-line>
            <v-list-item-content>
              <v-list-item-subtitle class="font-weight-medium">{{projectLog.log}}</v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
        </v-card>
      </v-card>
    </v-col>
    <DialogueBoxCreateTask
      v-model="dialog"
      @update="onListUpdate"
      @click="dialog = true"
      :user="user"
    ></DialogueBoxCreateTask>
  </v-layout>
</template>

<script>
/* eslint-disable*/
import draggable from "vuedraggable";
import BoardColumnHeader from "../components/BoardColumnHeader.vue";
import TaskCard from "../components/TaskCard.vue";
import DialogueBoxCreateTask from "../components/DialogueBoxCreateTask.vue";

export default {
  created() {
    this.cards = this.getCards();
    this.getUser();
    this.getProjectLogs();
    this.getProjectMembers();
  },
  data() {
    return {
      indeterminate: true,
      loading: true,
      user: {},
      dialog: false,
      iceboxColumn: {
        title: "Icebox"
      },
      backlogColumn: {
        title: "Current Iteration/Backlog"
      },
      doneColumn: {
        title: "Done"
      },
      cards: null,
      drag: false,
      list1: [],
      list2: [],
      list3: [],
      projectLogs: [],
      projectMembers: []
    };
  },
  computed: {
    dragOptions() {
      return {
        animation: 200,
        group: "description",
        disabled: false,
        ghostClass: "ghost"
      };
    }
  },
  watch: {
    cards: function() {
      this.list1 = this.cards;
    },
    list1: function() {
      if (this.list1 !== undefined) {
        let result = this.list1.filter(function(element) {
          if (element.state == "Finished" || element.state == "Released") {
            return element;
          }
        });
        // for(let i = 0; i < result.length; i++){
        //     this.list3.push(result[i]);
        // }
      }
    },
    list2: function() {
      if (this.list2 !== undefined) {
      }
    },
    list3: function() {
      if (this.list3 !== undefined) {
      }
    }
  },
  methods: {
    getUser() {
      let token = localStorage.getItem("token");
      let selectedProjectId = this.$route.params.projectId;

      const headers = {
        "Content-Type": "application/json",
        Authorization: "Bearer " + token
      };

      this.axios
        .get(`${this.appUrl}/api/v1/user/`, {
          headers
        })
        .then(response => {
          this.user = response.data.data;
        });
    },
    getCards() {
      let token = localStorage.getItem("token");
      let selectedProjectId = this.$route.params.projectId;

      const headers = {
        "Content-Type": "application/json",
        Authorization: "Bearer " + token
      };

      this.axios
        .get(`${this.appUrl}/api/v1/project/` + selectedProjectId + "/cards", {
          headers
        })
        .then(response => {
          this.cards = response.data;
          this.loading = false;
        });
    },
    getProjectLogs() {
      let token = localStorage.getItem("token");
      let selectedProjectId = this.$route.params.projectId;

      const headers = {
        "Content-Type": "application/json",
        Authorization: "Bearer " + token
      };

      this.axios
        .get(`${this.appUrl}/api/v1/project/` + selectedProjectId + "/log", {
          headers
        })
        .then(response => {
          this.projectLogs = response.data.data;
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
          this.projectMembers = response.data.data;
        });
    },
    onChangeState(cardData) {
      this.cards.forEach(function(element) {
        if (element.id == cardData.id) {
          element.state = cardData.state;
        }
      });
      console.log(this.cards);
    },
    onCardUpdate(cardData) {
      this.cards.forEach(function(element) {
        if (element.id == cardData.id) {
          element.title = cardData.data.title;
          element.owner = cardData.data.owner;
          element.github_branch_name = cardData.data.githubBranchName;
          element.description = cardData.data.description;
          element.points = cardData.data.points;
          element.type =
            cardData.data.type.charAt(0).toUpperCase() +
            cardData.data.type.slice(1);
        }
      });
    },
    openAddTask() {
      this.dialog = true;
    },
    onListUpdate(newCardData) {
      this.list1.push(newCardData);
    }
  },
  components: {
    BoardColumnHeader,
    TaskCard,
    draggable,
    DialogueBoxCreateTask
  }
};
</script>

<style scoped>
.list-group {
  min-height: 500px;
  overflow-y: auto;
}

.card-scrollable {
  height: 800px;
  overflow-y: auto;
}

.layout {
  overflow: auto !important;
}
</style>
