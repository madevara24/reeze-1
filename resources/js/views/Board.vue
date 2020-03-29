<template>
  <div>
    <v-row class="black">
      <v-col cols="12" sm="4" class="py-0 px-3  grey">
        <v-row>
          <BoardColumnHeader :headerData="iceboxColumn" />
        </v-row>
        <v-row>
          <draggable class="list-group" :list="list1" group="card" @change="changeState">
            <v-card class="my-1 mx-2" flat v-for="(element, index) in list1" :key="element.name">
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
          </draggable>
        </v-row>
      </v-col>
      <v-col cols="12" sm="4" class="py-0 px-3  grey">
        <v-row>
          <BoardColumnHeader :headerData="backlogColumn" />
        </v-row>
        <v-row>
          <draggable class="list-group" :list="list2" group="card" @change="changeState">
            <v-card class="my-1 mx-2" flat v-for="(element, index) in list2" :key="element.name">
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
          </draggable>
        </v-row>
      </v-col>
      <v-col cols="12" sm="4" class="py-0 px-3  grey">
        <v-row>
          <BoardColumnHeader :headerData="doneColumn" />
        </v-row>
        <v-row>
          <draggable class="list-group" :list="list3" group="card" @change="changeState">
            <v-card class="my-1 mx-2" flat v-for="(element, index) in list3" :key="element.name">
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
          </draggable>
        </v-row>
      </v-col>
    </v-row>
  </div>
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
  methods: {
    getCards() {
      let token = localStorage.getItem("token");
      let selectedProjectId = this.$route.params.projectId;

      const headers = {
        "Content-Type": "application/json",
        Authorization: "Bearer " + token
      };

      this.axios
        .get(
          "http://127.0.0.1:8000/api/v1/project/" +
            selectedProjectId +
            "/cards",
          { headers }
        )
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