<template>
  <v-layout>
    <v-col cols="12" sm="4">
      <v-toolbar>
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

    <v-dialog v-model="dialog" persistent max-width="600px">
      <v-card>
        <v-card-title>
          <span class="headline">User Profile</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12" sm="6" md="4">
                <v-text-field label="Legal first name*" required></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="4">
                <v-text-field label="Legal middle name" hint="example of helper text only on focus"></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="4">
                <v-text-field
                  label="Legal last name*"
                  hint="example of persistent helper text"
                  persistent-hint
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field label="Email*" required></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field label="Password*" type="password" required></v-text-field>
              </v-col>
              <v-col cols="12" sm="6">
                <v-select :items="['0-17', '18-29', '30-54', '54+']" label="Age*" required></v-select>
              </v-col>
              <v-col cols="12" sm="6">
                <v-autocomplete
                  :items="['Skiing', 'Ice hockey', 'Soccer', 'Basketball', 'Hockey', 'Reading', 'Writing', 'Coding', 'Basejump']"
                  label="Interests"
                  multiple
                ></v-autocomplete>
              </v-col>
            </v-row>
          </v-container>
          <small>*indicates required field</small>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="dialog = false">Close</v-btn>
          <v-btn color="blue darken-1" text @click="dialog = false">Save</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
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
    }
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