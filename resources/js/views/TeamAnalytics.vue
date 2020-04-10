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
    console.log("View Analytics (created) : Selected Project ID " + this.$route.params.projectId)
    this.getSprintProgression();
    this.getDeliverability();
    this.getTaskLifecycle();
    this.getEstimation();
  },
  watch: {
    $route(to, from) {
      console.log("View Analytics (watch, route) : Selected Project ID " + this.$route.params.projectId)
      this.getSprintProgression();
      this.getDeliverability();
      this.getTaskLifecycle();
      this.getEstimation();
    }
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
          colors: ['#1e88e5','#e53935'],
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
          colors: ['#1e88e5','#e53935'],
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
        .get('http://127.0.0.1:8000/api/v1/project/' + this.$route.params.projectId + '/analytic/current-sprint-dates', {headers})
        .then((response) => {this.formatSprintProgression(response.data.data, null)});
        
      this.axios
        .get('http://127.0.0.1:8000/api/v1/project/' + this.$route.params.projectId + '/analytic/sprint-progression', {headers})
        .then((response) => {this.formatSprintProgression(null, response.data.data)});
    },
    formatSprintProgression(chartDate, burndown){
      if(chartDate){
        if(this.burndown.chartData.length === 1){
          chartDate.forEach(element => {this.burndown.chartData.push(new Array(element, null, null));});
        }else{
          chartDate.forEach((element, index) => {this.burndown.chartData[index + 1].splice(0, 1, element);});
        }
      }
      if(burndown){
        if(this.burndown.chartData.length === 1){
          burndown.forEach(element => {this.burndown.chartData.push(new Array(null, element[0], element[1]));});
        }else{
          burndown.forEach((element, index) => {this.burndown.chartData[index + 1].splice(1, 1, element[0]);});
          burndown.forEach((element, index) => {this.burndown.chartData[index + 1].splice(2, 1, element[1]);});
        }
      }
    },
    getTaskLifecycle(){
      console.log("View Analytics (method) : Get task lifecycle")
      let token = localStorage.getItem('token')
      const headers = {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + token,
      };
      this.axios
        .get('http://127.0.0.1:8000/api/v1/project/' + this.$route.params.projectId + '/analytic/task-lifecycle', {headers})
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
        .get('http://127.0.0.1:8000/api/v1/project/' + this.$route.params.projectId + '/analytic/sprint-dates', {headers})
        .then((response) => {this.formatDeliverability(response.data.data, null, null)});

      this.axios
        .get('http://127.0.0.1:8000/api/v1/project/' + this.$route.params.projectId + '/analytic/deliverability', {headers})
        .then((response) => {this.formatDeliverability(null, response.data.data, null)});

      this.axios
        .get('http://127.0.0.1:8000/api/v1/project/' + this.$route.params.projectId + '/analytic/rejection', {headers})
        .then((response) => {this.formatDeliverability(null, null, response.data.data)});
    },
    formatDeliverability(chartDate, deliverability, rejection){
      if(chartDate){
        if(this.deliverability.chartData.length === 1){
          chartDate.forEach(element => {this.deliverability.chartData.push(new Array(element, null, null));});
        }else{
          chartDate.forEach((element, index) => {this.deliverability.chartData[index + 1].splice(0, 1, element);});
        }
      }
      if(deliverability){
        if(this.deliverability.chartData.length === 1){
          deliverability.forEach(element => {this.deliverability.chartData.push(new Array(null, element, null));});
        }else{
          deliverability.forEach((element, index) => {this.deliverability.chartData[index + 1].splice(1, 1, element);});
        }
      }
      if(rejection){
        if(this.deliverability.chartData.length === 1){
          rejection.forEach(element => {this.deliverability.chartData.push(new Array(null, null, element));});
        }else{
          rejection.forEach((element, index) => {this.deliverability.chartData[index + 1].splice(2, 1, element);});
        }
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
        .get('http://127.0.0.1:8000/api/v1/project/' + this.$route.params.projectId + '/analytic/estimation', {headers})
        .then(response => (this.formatEstimation(response.data.data)))
    },
    formatEstimation(data){
      this.estimation.velocity = data.velocity
      this.estimation.sprintEstimate = data.estimate
    }
  }
}
</script>