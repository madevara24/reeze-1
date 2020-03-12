<template>
      <v-row>
        <v-col cols=12 xs=12>
          <v-card flat class="px-3 mb-n1">
            <v-card-text>
              <v-row>
                <v-col cols=12 sm=4>
                  <PersonSelector />
                </v-col>
              </v-row>
            </v-card-text>
          </v-card>
        </v-col>
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
      </v-row>
</template>

<script>
import { GChart } from 'vue-google-charts'
import PersonSelector from '../components/PersonSelector'

export default {
  created() {
    console.log("View Analytics (created) : Selected Project ID " + this.$route.params.projectId)
    this.getSprintProgression();
    // this.getDeliverability();
    // this.getTaskLifecycle();
  },
  watch: {
    $route(to, from) {
      console.log("View Analytics (watch, route) : Selected Project ID " + this.$route.params.projectId)
      this.getSprintProgression();
      // this.getDeliverability();
      // this.getTaskLifecycle();
    }
  },
  components:{
    GChart,
    PersonSelector
  },
  data() {
    return {
      burndown: {
        chartData: [
        ],
        chartOptions: {
          title: 'Burndown Chart',
          subtitle: 'Points Remaining',
          colors: ['#1e88e5','#e53935','#6ab7ff','#ff6f60'],
        }
      },
      deliverability:{
        chartData: [
          ['Iteration', 'Team Deliver Rate', 'Personal Deliver Rate', 'Team Rejection Rate', 'Personal Rejection Rate'],
        ],
        chartOptions: {
          title: 'Deliverability',
          vAxis : {
            maxValue : 100,
            minValue : 0
          },
          colors: ['#1e88e5','#e53935','#6ab7ff','#ff6f60'],
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
      if(this.$route.params.personId){
        this.burndown.chartData.push(['Day', 'Team Points Remaining', 'Team Ideal Burndown', 'Personal Points Remaining', 'Personal Ideal Burndown']);
        
        this.axios
        .get('http://127.0.0.1:8000/api/v1/project/' + this.$route.params.projectId + '/analytic/sprint-progression/' + this.$route.params.personId, {headers})
        .then((response) => {this.formatSprintProgression(null, null, response.data.data)});
      }
      else{
        this.burndown.chartData.push(['Day', 'Points Remaining', 'Ideal Burndown']);
      }

      this.axios
        .get('http://127.0.0.1:8000/api/v1/project/' + this.$route.params.projectId + '/analytic/current-sprint-dates', {headers})
        .then((response) => {this.formatSprintProgression(response.data.data, null, null)});
        
      this.axios
        .get('http://127.0.0.1:8000/api/v1/project/' + this.$route.params.projectId + '/analytic/sprint-progression', {headers})
        .then((response) => {this.formatSprintProgression(null, response.data.data, null)});
    },
    formatSprintProgression(chartDate, teamBurndown, personalBurndown){
      if(chartDate){
        if(this.burndown.chartData.length === 1){
          chartDate.forEach(element => {this.burndown.chartData.push(new Array(element, null, null));});
        }else{
          chartDate.forEach((element, index) => {this.burndown.chartData[index + 1].splice(0, 1, element);});
        }
      }
      if(teamBurndown){
        if(this.burndown.chartData.length === 1){
          teamBurndown.forEach(element => {this.burndown.chartData.push(new Array(null, element[0], element[1]));});
        }else{
          teamBurndown.forEach((element, index) => {this.burndown.chartData[index + 1].splice(1, 1, element[0]);});
          teamBurndown.forEach((element, index) => {this.burndown.chartData[index + 1].splice(2, 1, element[1]);});
        }
      }
      if(personalBurndown){
        if(this.burndown.chartData.length === 1){
          personalBurndown.forEach(element => {this.burndown.chartData.push(new Array(null, null, null, element[0], element[1]));});
        }else{
          personalBurndown.forEach((element, index) => {this.burndown.chartData[index + 1].push(element);console.log('element :', element);});
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
        .get('http://127.0.0.1:8000/api/v1/project/' + this.$route.params.projectId + '/analytic/formated-chart-dates', {headers})
        .then((response) => {this.formatDeliverability(response.data.data, null, null)});

      this.axios
        .get('http://127.0.0.1:8000/api/v1/project/' + this.$route.params.projectId + '/analytic/deliverability', {headers})
        .then((response) => {this.formatDeliverability(null, response.data.data, null)});

      this.axios
        .get('http://127.0.0.1:8000/api/v1/project/' + this.$route.params.projectId + '/analytic/rejection', {headers})
        .then((response) => {this.formatDeliverability(null, null, response.data.data)});
    },
    formatDeliverability(chartDate, teamDeliverability, teamRejection, personDeliverability, personRejection){
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
    }
  }
}
</script>