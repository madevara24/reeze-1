<template>
      <v-row>
        <v-col cols=12 xs=12>
          <v-card flat class="px-3 mb-5">
            <v-card-title>Project Merge</v-card-title>
            <v-card-text>
              <v-row>
                <div class="pl-3">
                  <v-btn depressed small color="primary" @click="onPressReleaseButton()">Release</v-btn>
                </div>
              </v-row>
              <v-row>
                <div class="pl-3">
                  <v-simple-table>
                    <template v-slot:default>
                      <thead>
                      <tr>
                        <th class="text-left"></th>
                        <th class="text-left">Card ID</th>
                        <th class="text-left">Type</th>
                        <th class="text-left">Name</th>
                        <th class="text-left">Requester</th>
                        <th class="text-left">Owner</th>
                        <th class="text-left">State</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="item in cards" :key="item.id">
                        <td><v-checkbox v-model="mergeSelect" :value=item.id></v-checkbox></td>
                        <td>{{ item.id }}</td>
                        <td>{{ item.type }}</td>
                        <td>{{ item.name }}</td>
                        <td>{{ item.requester }}</td>
                        <td>{{ item.owner }}</td>
                        <td>{{ item.state }}</td>
                      </tr>
                    </tbody>
                    </template>
                  </v-simple-table>
                </div>
              </v-row>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
</template>

<script>

export default {
  created() {
    console.log("View Project Merge (created) : Selected Project ID " + this.$route.params.projectId)

  },
  watch: {
    $route(to, from) {
      console.log("View Project Merge (watch, route) : Selected Project ID " + this.$route.params.projectId)
      
    }
  },
  data() {
    return {
      cards: [
        {
          id: '8264419271',
          type: 'Feature',
          name: 'Make Login',
          requester: 'Julian',
          owner: 'Zainokta',
          state: 'Accepted',
        },
        {
          id: '8264411971',
          type: 'Feature',
          name: 'Make Homepage',
          requester: 'Julian',
          owner: 'Devara',
          state: 'Accepted',
        },
        {
          id: '1035619271',
          type: 'Feature',
          name: 'Design Navbar',
          requester: 'Julian',
          owner: 'Julian',
          state: 'Accepted',
        },
      ],
      mergeSelect: []
    }
  },
  methods:{
    onPressReleaseButton(){
      console.log(this.mergeSelect);
      console.log('ping');
    }
  }
}
</script>