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
    console.log("View Analytics (created) : Selected Project ID " + this.$route.params.id)
    this.getSprintProgression();
    this.getDeliverability();
    this.getTaskLifecycle();
    this.getEstimation();
  },
  watch: {
    $route(to, from) {
      console.log("View Analytics (watch, route) : Selected Project ID " + this.$route.params.id)
      this.getSprintProgression();
      this.getDeliverability();
      this.getTaskLifecycle();
      this.getEstimation();
    }
  },
  beforeUpdate(){
    
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
      console.log("View Analytics (method) : Get sprint progression")
      let token = localStorage.getItem('token')
      const headers = {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + token,
      };
      this.axios
        .get('http://127.0.0.1:8000/api/v1/project/' + this.$route.params.id + '/analytic/sprint-progression', {headers})
        .then(response => (this.burndown.chartData = this.burndown.chartData.concat(response.data.data)));
    },
    getTaskLifecycle(){
      console.log("View Analytics (method) : Get task lifecycle")
      let token = localStorage.getItem('token')
      const headers = {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + token,
      };
      this.axios
        .get('http://127.0.0.1:8000/api/v1/project/' + this.$route.params.id + '/analytic/task-lifecycle', {headers})
        .then(response => (this.taskLifecycle.chartData = this.taskLifecycle.chartData.concat(response.data.data)))
    },
    getDeliverability(){
      console.log("View Analytics (method) : Get deliverability")
      let token = localStorage.getItem('token')
      const headers = {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + token,
      };
      this.axios
        .get('http://127.0.0.1:8000/api/v1/project/' + this.$route.params.id + '/analytic/deliverability', {headers})
        .then(response => (this.formatDeliverability(response.data.data)))
    },
    formatDeliverability(data){
      this.deliverability.chartData = this.deliverability.chartData.concat(data);
      this.getRejection();
    },
    getRejection(){
      console.log("View Analytics (method) : Get rejection")
      let token = localStorage.getItem('token')
      const headers = {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + token,
      };
      this.axios
        .get('http://127.0.0.1:8000/api/v1/project/' + this.$route.params.id + '/analytic/rejection', {headers})
        .then(response => (this.formatRejection(response.data.data)))
    },
    formatRejection(data){
      for (let index = 0; index < data.length; index++) {
        this.deliverability.chartData[index + 1].push(data[index])
      }
    },
    getEstimation(){
      console.log("View Analytics (method) : Get estimation")
      let token = localStorage.getItem('token')
      const headers = {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + token,
      };
      this.axios
        .get('http://127.0.0.1:8000/api/v1/project/' + this.$route.params.id + '/analytic/estimation', {headers})
        .then(response => (this.formatEstimation(response.data.data)))
    },
    formatEstimation(data){
      this.estimation.velocity = data.velocity
      this.estimation.sprintEstimate = data.estimate
    }
  }
}
</script>