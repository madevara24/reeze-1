<template>
  <v-layout>
      <v-col cols="12" sm="4">
        <v-toolbar>
            <v-toolbar-title>Icebox</v-toolbar-title>
            <v-btn fixed right color="primary" @click="openAddTask">Add Task</v-btn>
        </v-toolbar>
        <v-card class="card-scrollable" color="grey">
          <draggable
            :list="list1"
            group="card"
            v-bind="dragOptions"
            @change="changeState"
            @start="drag = true"
            @end="drag = false"
          >
            <transition-group
              tag="div"
              class="list-group"
              type="transition"
              :name="!drag ? 'flip-list' : null"
            >
              <TaskCard v-for="card in list1" :key="card.id" :title="card.title"></TaskCard>
            </transition-group>
          </draggable>
        </v-card>
      </v-col>
      <v-col cols="12" sm="4">
        <v-toolbar>
          <v-toolbar-title>Current Iteration/Backlog</v-toolbar-title>
        </v-toolbar>
        <v-card class="card-scrollable" color="grey">
          <draggable
            :list="list2"
            group="card"
            v-bind="dragOptions"
            @change="changeState"
            @start="drag = true"
            @end="drag = false"
          >
            <transition-group
              tag="div"
              class="list-group"
              type="transition"
              :name="!drag ? 'flip-list' : null"
            >
              <TaskCard v-for="card in list2" :key="card.id" :title="card.title"></TaskCard>
            </transition-group>
          </draggable>
        </v-card>
      </v-col>
      <v-col cols="4" sm="4">
        <v-toolbar>
          <v-toolbar-title>Done</v-toolbar-title>
        </v-toolbar>
        <v-card class="card-scrollable" color="grey">
          <draggable
            :list="list3"
            group="card"
            v-bind="dragOptions"
            @change="changeState"
            @start="drag = true"
            @end="drag = false"
          >
            <transition-group
              tag="div"
              class="list-group"
              type="transition"
              :name="!drag ? 'flip-list' : null"
            >
              <TaskCard v-for="card in list3" :key="card.id" :title="card.title"></TaskCard>
            </transition-group>
          </draggable>
        </v-card>
      </v-col>

      <DialogueBox v-model="dialog" @update="onListUpdate" @click="dialog = true"></DialogueBox>
  </v-layout>
</template>

<script>
/* eslint-disable*/
import draggable from "vuedraggable";
import BoardColumnHeader from "../components/BoardColumnHeader.vue";
import TaskCard from "../components/TaskCard.vue";
import DialogueBox from "../components/DialogueBox.vue";

export default {
  created() {
    this.cards = this.getCards();
  },
  data() {
    return {
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
      list3: []
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
    },
  },
  watch: {
    cards: function() {
      this.list1 = this.cards;
    }
  },
  methods: {
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
        })
        .catch(error => console.log(error));
    },
    changeState() {
      console.log("hello");
    },
    openAddTask(){
      this.dialog = true;
    },
    onListUpdate(newCardData){
      this.list1.push(newCardData);
    }
  },
  components: {
    BoardColumnHeader,
    TaskCard,
    draggable,
    DialogueBox
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
</style>