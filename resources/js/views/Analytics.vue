<template>
      <v-row>
        <v-col cols=12 xs=12>
          <v-card flat class="px-3 mb-5">
            <v-card-title>Sprint Progression</v-card-title>
            <v-card-text>
              <GChart
                style="height: 400px;"
                type="LineChart"
                :data="burndown.chartData"
                :options="burndown.chartOptions"
              />
            </v-card-text>
          </v-card>
        </v-col>
        <v-col cols=12 xs=12>
          <v-card flat class="px-3 mb-5">
            <v-card-title>Deliverabillity</v-card-title>
            <v-card-text>
              <GChart
                style="height: 400px;"
                type="LineChart"
                :data="deliverability.chartData"
                :options="deliverability.chartOptions"
              />
            </v-card-text>
          </v-card>
        </v-col>
        <v-col cols=12 xs=12>
          <v-card flat class="px-3 mb-5">
            <v-card-title>Task Lifecycle</v-card-title>
            <v-card-text>
              <GChart
                style="height: 500px;"
                type="PieChart"
                :data="taskLifecycle.chartData"
                :options="taskLifecycle.chartOptions"
              />
            </v-card-text>
          </v-card>
        </v-col>
        <v-col cols=12 xs=12>
          <v-card flat class="px-3 mb-5">
            <v-card-title>Estimation Chart</v-card-title>
            <v-card-text>
              <!-- <GChart
                type="Timeline"
                :data="estimation.chartData"
                :options="estimation.chartOptions"
              /> -->
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
</template>

<script>
import { GChart } from 'vue-google-charts'

export default {
  created() {
    this.getSprintProgression();
    this.getDeliverability();
    this.getTaskLifecycle();

  },
  components:{
    GChart
  },
  data() {
    return {
      burndown: {
        chartData: [
          ['Day', 'Points Remaining', 'Ideal Burndown']
        ],
        chartOptions: {
          title: 'Burndown Chart',
          subtitle: 'Points Remaining',
        }
      },
      deliverability:{
        chartData: [
          ['Iteration', 'Deliver Rate', 'Rejection Rate'],
          // ['Iteration 1', 92, 12],
          // ['Iteration 2', 88, 18],
          // ['Iteration 3', 91, 8],
          // ['Iteration 4', 96, 14],
          // ['Current Iteration', 90, 9]
        ],
        chartOptions: {
          title: 'Deliverability',
          vAxis : {
            maxValue : 100,
            minValue : 0
          },
          colors: ['blue','red'],
        }
      },
      taskLifecycle:{
        chartData: [
          ['State', 'Avg. Hours'],
        ],
        chartOptions: {
          title: 'Task Lifecycle',
          pieHole: 0.4,
          colors: ['#dbdbdb','#f08000','#203e64','#629200','#a71f39']
        }
      },
      estimation: {
        chartData: [
          ['Day', 'Points Remaining', 'Ideal Burndown'],
          ['Monday', 15, 15],
          ['Tuesday', 13, 11.25],
          ['Wednesday', 11, 7.5],
          ['Thursday', 6, 3.75],
          ['Friday', 3, 0],
          ['Saturday', 3, 0],
          ['Sunday', 3, 0],
        ],
        chartOptions: {
          title: 'Burndown Chart',
          subtitle: 'Points Remaining, Iteration : 2',
        }
      },
    }
  },
  methods:{
    getSprintProgression(){
      this.axios
        .get('http://127.0.0.1:8000/v1/analytic/sprint-progression/' + this.$store.getters.getSelectedProjectId)
        .then(response => (this.burndown.chartData = this.burndown.chartData.concat(response.data)))
    },
    getTaskLifecycle(){
      this.axios
        .get('http://127.0.0.1:8000/v1/analytic/task-lifecycle/' + this.$store.getters.getSelectedProjectId)
        .then(response => (this.taskLifecycle.chartData = this.taskLifecycle.chartData.concat(response.data)))
    },
    getDeliverability(){
      console.log("Get deliverability")
      this.axios
        .get('http://127.0.0.1:8000/v1/analytic/deliverability/' + this.$store.getters.getSelectedProjectId)
        .then(response => (this.formatDeliverability(response)))
    },
    formatDeliverability(data){
      this.deliverability.chartData = this.deliverability.chartData.concat(data.data);
      this.getRejection();
    },
    getRejection(){
      this.axios
        .get('http://127.0.0.1:8000/v1/analytic/rejection/' + this.$store.getters.getSelectedProjectId)
        .then(response => (this.formatRejection(response)))
    },
    formatRejection(data){
      for (let index = 0; index < data.data.length; index++) {
        this.deliverability.chartData[index + 1].push(data.data[index])
      }
      console.table(this.deliverability.chartData)
    }
  }
}
</script>