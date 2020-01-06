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
          <v-row justify="space-around">
            <v-card flat class="px-3 mb-5 flex-grow-1">
              <v-card-title>Project Velocity</v-card-title>
              <v-card-text>
                <v-row class="display-1 font-weight-medium">
                  <v-col cols="2" class="indigo--text text-right">{{estimation.velocity}}</v-col>
                  <v-col class="flex-grow-1">points per sprint</v-col>
                </v-row>
              </v-card-text>
            </v-card>
            <v-card flat class="px-3 mb-5 flex-grow-1">
              <v-card-title>Estimated Sprints Required</v-card-title>
              <v-card-text>
                <v-row class="display-1 font-weight-medium">
                  <v-col cols="2" class="indigo--text text-right">{{estimation.sprintEstimate}}</v-col>
                  <v-col class="flex-grow-1">sprints left</v-col>
                </v-row>
              </v-card-text>
            </v-card>
          </v-row>
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
    this.getEstimation();

  },
  beforeUpdate(){
    console.log("Before update " + this.$store.getters.getSelectedProjectId)
  },
  components:{
    GChart
  },
  data() {
    return {
      burndown: {
        chartData: [
          ['Day', 'Points Remaining', 'Ideal Burndown'],
          
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
        velocity: 0,
        sprintEstimate: 0
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
        .then(response => (this.formatDeliverability(response.data)))
    },
    formatDeliverability(data){
      this.deliverability.chartData = this.deliverability.chartData.concat(data);
      this.getRejection();
    },
    getRejection(){
      this.axios
        .get('http://127.0.0.1:8000/v1/analytic/rejection/' + this.$store.getters.getSelectedProjectId)
        .then(response => (this.formatRejection(response.data)))
    },
    formatRejection(data){
      for (let index = 0; index < data.length; index++) {
        this.deliverability.chartData[index + 1].push(data[index])
      }
      console.table(this.deliverability.chartData)
    },
    getEstimation(){
      this.axios
        .get('http://127.0.0.1:8000/v1/analytic/estimation/' + this.$store.getters.getSelectedProjectId)
        .then(response => (this.formatEstimation(response.data)))
    },
    formatEstimation(data){
      this.estimation.velocity = data[0]
      this.estimation.sprintEstimate = data[1]
    }
  }
}
</script>