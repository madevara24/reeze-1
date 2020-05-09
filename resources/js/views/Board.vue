<template>
  <v-layout>
    <v-col cols="12" sm="4">
      <v-toolbar color="grey">
        <v-toolbar-title>Icebox</v-toolbar-title>
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
            <v-card
              class="my-1 mx-2 py-0 px-3"
              flat
              v-for="(element, index) in list1"
              :key="element.name"
              hover
            >
              <v-list-item three-line>
                <v-list-item-content>
                  <div class="overline mb-4">{{ index }}</div>
                  <v-list-item-title class="headline mb-1">{{ element.name }}</v-list-item-title>
                  <v-list-item-subtitle>Greyhound divisely hello coldly fonwderfully</v-list-item-subtitle>
                </v-list-item-content>

                <v-list-item-avatar tile size="80" color="grey"></v-list-item-avatar>
              </v-list-item>

              <v-card-actions>
                <v-btn text>Button</v-btn>
                <v-btn text>Button</v-btn>
              </v-card-actions>
            </v-card>
          </transition-group>
        </draggable>
      </v-card>
    </v-col>
    <v-col cols="12" sm="4">
      <v-toolbar color="grey">
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
            <v-card
              class="my-1 mx-2 py-0 px-3"
              flat
              v-for="(element, index) in list2"
              :key="element.name"
              hover
            >
              <v-list-item three-line>
                <v-list-item-content>
                  <div class="overline mb-4">{{ index }}</div>
                  <v-list-item-title class="headline mb-1">{{ element.name }}</v-list-item-title>
                  <v-list-item-subtitle>Greyhound divisely hello coldly fonwderfully</v-list-item-subtitle>
                </v-list-item-content>

                <v-list-item-avatar tile size="80" color="grey"></v-list-item-avatar>
              </v-list-item>

              <v-card-actions>
                <v-btn text>Button</v-btn>
                <v-btn text>Button</v-btn>
              </v-card-actions>
            </v-card>
          </transition-group>
        </draggable>
      </v-card>
    </v-col>
    <v-col cols="4" sm="4">
      <v-toolbar color="grey">
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
            <v-card
              class="my-1 mx-2 py-0 px-3"
              flat
              v-for="(element, index) in list3"
              :key="element.name"
              hover
            >
              <v-list-item three-line>
                <v-list-item-content>
                  <div class="overline mb-4">{{ index }}</div>
                  <v-list-item-title class="headline mb-1">{{ element.name }}</v-list-item-title>
                  <v-list-item-subtitle>Greyhound divisely hello coldly fonwderfully</v-list-item-subtitle>
                </v-list-item-content>

                <v-list-item-avatar tile size="80" color="grey"></v-list-item-avatar>
              </v-list-item>

              <v-card-actions>
                <v-btn text>Button</v-btn>
                <v-btn text>Button</v-btn>
              </v-card-actions>
            </v-card>
          </transition-group>
        </draggable>
      </v-card>
    </v-col>
  </v-layout>
</template>

<script>
/* eslint-disable*/
import draggable from "vuedraggable";
import BoardColumnHeader from "../components/BoardColumnHeader.vue";
import TaskCard from "../components/TaskCard.vue";
export default {
  created() {
    this.cards = this.getCards();
  },
  data() {
    return {
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
      list1: [
        { name: "John", id: 1 },
        { name: "Joao", id: 2 },
        { name: "Jean", id: 3 },
        { name: "Gerard", id: 4 }
      ],
      list2: [
        { name: "Masjul", id: 1 },
        { name: "Subosko", id: 2 },
        { name: "Devara", id: 3 },
        { name: "Sujen", id: 4 },
        { name: "Sudhan", id: 5 }
      ],
      list3: [
        { name: "Card 1", id: 1 },
        { name: "Card 2", id: 2 },
        { name: "MAKE A CAR", id: 3 },
        { name: "Covid", id: 4 },
        { name: "Yea", id: 5 },
        { name: "Boii", id: 5 }
      ]
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
        .then(response => (this.cards = response.data));
    },
    changeState() {
      console.log("hello");
    }
  },
  components: {
    BoardColumnHeader,
    TaskCard,
    draggable
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