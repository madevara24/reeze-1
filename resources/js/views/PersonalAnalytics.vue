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
          <v-card flat class="px-3">
            <v-card-title>Sprint Progression
              <v-tooltip right>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn small icon v-bind="attrs" v-on="on">
                    <v-icon color="grey darken-1">mdi-information-outline</v-icon>
                  </v-btn>
                </template>
                <span>{{this.tooltip.burndown.tooltip}}</span>
              </v-tooltip>
            </v-card-title>
            <v-card-text>
              <GChart
                style="height: 400px;"
                type="LineChart"
                :data="burndown.chartData"
                :options="burndown.chartOptions"
              />
            </v-card-text>
             <v-divider></v-divider>
            <v-expansion-panels multiple flat>
            <v-expansion-panel>
              <v-expansion-panel-header>How do I read this chart?</v-expansion-panel-header>
              <v-expansion-panel-content>
                {{this.tooltip.burndown.help}}
              </v-expansion-panel-content>
            </v-expansion-panel>
          </v-expansion-panels>
          </v-card>
        </v-col>
        <v-col cols=12 xs=12>
          <v-card flat class="px-3">
            <v-card-title>Deliverabillity & Rejection
              <v-tooltip right>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn small icon v-bind="attrs" v-on="on">
                    <v-icon color="grey darken-1">mdi-information-outline</v-icon>
                  </v-btn>
                </template>
                <span>{{this.tooltip.deliverability.tooltip}}</span>
              </v-tooltip>
            </v-card-title>
            <v-card-text>
              <GChart
                style="height: 400px;"
                type="LineChart"
                :data="deliverability.chartData"
                :options="deliverability.chartOptions"
              />
            </v-card-text>
             <v-divider></v-divider>
            <v-expansion-panels multiple flat>
            <v-expansion-panel>
              <v-expansion-panel-header>How do I read this chart?</v-expansion-panel-header>
              <v-expansion-panel-content>
                {{this.tooltip.deliverability.help}}
              </v-expansion-panel-content>
            </v-expansion-panel>
          </v-expansion-panels>
          </v-card>
        </v-col>
        <v-col cols=12 xs=12>
          <v-card flat class="px-3">
            <v-card-title>Card Timeline
              <v-tooltip right>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn small icon v-bind="attrs" v-on="on">
                    <v-icon color="grey darken-1">mdi-information-outline</v-icon>
                  </v-btn>
                </template>
                <span>{{this.tooltip.cardTimeline.tooltip}}</span>
              </v-tooltip>
            </v-card-title>
            <v-card-text>
              <GChart
                :settings="{ packages: ['timeline'] }"
                type="Timeline"
                :data="cardTimeline.chartData"
                :option="cardTimeline.chartOptions"
                style="height: 500px"
              />
            </v-card-text>
             <v-divider></v-divider>
            <v-expansion-panels multiple flat>
            <v-expansion-panel>
              <v-expansion-panel-header>How do I read this chart?</v-expansion-panel-header>
              <v-expansion-panel-content>
                {{this.tooltip.cardTimeline.help}}
              </v-expansion-panel-content>
            </v-expansion-panel>
          </v-expansion-panels>
          </v-card>
        </v-col>
        <v-col cols=12 xs=12>
          <v-card flat class="px-3">
            <v-card-title>Task Lifecycle
              <v-tooltip right>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn small icon v-bind="attrs" v-on="on">
                    <v-icon color="grey darken-1">mdi-information-outline</v-icon>
                  </v-btn>
                </template>
                <span>{{this.tooltip.taskLifecycle.tooltip}}</span>
              </v-tooltip>
            </v-card-title>
            <v-card-text>
              <GChart
                style="height: 500px;"
                type="PieChart"
                :data="taskLifecycle.chartData"
                :options="taskLifecycle.chartOptions"
              />
            </v-card-text>
             <v-divider></v-divider>
            <v-expansion-panels multiple flat>
            <v-expansion-panel>
              <v-expansion-panel-header>How do I read this chart?</v-expansion-panel-header>
              <v-expansion-panel-content>
                {{this.tooltip.taskLifecycle.help}}
              </v-expansion-panel-content>
            </v-expansion-panel>
          </v-expansion-panels>
          </v-card>
        </v-col>
      </v-row>
</template>

<script>
import { GChart } from 'vue-google-charts'
import PersonSelector from '../components/PersonSelector'

export default {
  created() {
    console.log("View Analytics (created) : Selected Project ID " + this.$route.params.projectId +"/"+ this.$route.params.personId)
    this.getSprintProgression();
    this.getDeliverability();
    this.getTaskLifecycle();
    this.getCardTimeline();
  },
  watch: {
    $route(to, from) {
      console.log("View Analytics (watch, route) : Selected Project ID " + this.$route.params.projectId +"/"+ this.$route.params.personId)
      this.getSprintProgression();
      this.getDeliverability();
      this.getTaskLifecycle();
      this.getCardTimeline();
    }
  },
  components:{
    GChart,
    PersonSelector
  },
  data() {
    return {
      tooltip: {
        burndown:{
          tooltip:"Sprint progression shows you how much of the tasks points have been done and released",
          help:"Sprint progression shows you how much of the tasks points have been done and released. The blue line sums up the total task points that haven't been finished each day. The value goes down when a task is completed and goes up when a task is rejected or a new task is added to the current iteration. The red line represents the ideal graph for the current sprint where the tasks were completed evenly each day until the end of the sprint. Ideally the blue line should be moving downwards towards the end of the chart, moving parallel or below the red line. Personal burndown shows you the progression of an individual team member while comparing them with the team's progression."
        },
        deliverability:{
          tooltip:"Deliverability and rejection rate shows you the percentage of completed and rejected tasks from the total tasks that was planned for the sprint",
          help:"Deliverability and rejection rate shows you the percentage of completed and rejected tasks from the total tasks that was planned for the sprint. Deliverability rate shows you the percentage of task points your team or a team member has completed out of all task points planned in the begining of the sprint. Rejection rate shows you the percentage of time that the tasks spent on being rejected. Ideally the deliverability rate should be on 100% and the rejection rate on 0%. Low deliverability sometimes means the team or team member can't complete the task they have planned, where high rejection means the team or team member's tasks were often rejected and have to be started over."
        },
        taskLifecycle:{
          tooltip:"Task lifecycle shows you how long your tasks spends in each state on average",
          help:"Task lifecycle shows you how long your tasks spends in each state on average. This chart could tell you about bottlenecks on the development process by looking which state the tasks spends the most. For instance if the Planned state dominates the chart then it means you have planned way too many tasks, or if it's the Finished state then your tasks testing might be taking too much time."
        },
        cardTimeline:{
          tooltip:"Card timeline shows you how each task progresses during the sprint",
          help:"Card timeline shows you how each task progresses during the sprint. This chart shows you the journey of each task from the start of the sprint until the end. You can also see how each team member tasks progresses by using the person selector on the top left of the page."
        },
      },
      burndown: {
        chartData: [],
        chartOptions: {
          subtitle: 'Points Remaining',
          colors: ['#1e88e5','#e53935','#6ab7ff','#ff6f60'],
          vAxis : {
            minValue : 0
          },
        }
      },
      deliverability:{
        chartData: [],
        chartOptions: {
          vAxis : {
            maxValue : 100,
            minValue : 0
          },
          colors: ['#1e88e5','#e53935','#6ab7ff','#ff6f60'],
        }
      },
      cardTimeline: {
        chartData: [
          [
            { type: 'string', id: 'Role' },{ type: 'string', id: 'Name' },{ type: 'string', id: 'style', role: 'style' },{ type: 'date', id: 'Start' },{ type: 'date', id: 'End' }
          ],
        ],
        chartOptions: {
        }
      },
      taskLifecycle:{
        chartData: [
          ['State', 'Avg. Hours'],
        ],
        chartOptions: {
          pieHole: 0.4,
          colors: ['#dbdbdb','#f08000','#203e64','#629200','#a71f39']
        }
      },
    }
  },
  methods:{
    getSprintProgression(){
      console.log("View Analytics (method) : Get sprint progression " + this.$route.params.projectId +"/"+ this.$route.params.personId)
      let token = localStorage.getItem('token')
      const headers = {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + token,
      };

      this.burndown.chartData = []

      if(this.$route.params.personId){
        this.burndown.chartData.push(['Day', 'Team Points Remaining', 'Team Ideal Burndown', 'Personal Points Remaining', 'Personal Ideal Burndown']);
        
        this.axios
        .get(`${this.appUrl}/api/v1/project/` + this.$route.params.projectId + '/analytic/sprint-progression/' + this.$route.params.personId, {headers})
        .then((response) => {this.formatSprintProgression(null, null, response.data.data)});
      }
      else{
        this.burndown.chartData.push(['Day', 'Points Remaining', 'Ideal Burndown']);
      }

      this.axios
        .get(`${this.appUrl}/api/v1/project/` + this.$route.params.projectId + '/analytic/current-sprint-dates', {headers})
        .then((response) => {this.formatSprintProgression(response.data.data, null, null)});
      
      this.axios
        .get(`${this.appUrl}/api/v1/project/` + this.$route.params.projectId + '/analytic/sprint-progression', {headers})
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
          personalBurndown.forEach((element, index) => {this.burndown.chartData[index + 1].push(element);});
        }
      }
    },
    getTaskLifecycle(){
      console.log("View Analytics (method) : Get task lifecycle " + this.$route.params.projectId +"/"+ this.$route.params.personId)
      let token = localStorage.getItem('token')
      const headers = {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + token,
      };

      this.taskLifecycle.chartData = [['State', 'Avg. Hours']]
      
      if(this.$route.params.personId){
        this.axios
          .get(`${this.appUrl}/api/v1/project/` + this.$route.params.projectId + '/analytic/task-lifecycle/' + this.$route.params.personId, {headers})
          .then(response => (this.taskLifecycle.chartData = this.taskLifecycle.chartData.concat(response.data.data)))
      }else{
        this.axios
          .get(`${this.appUrl}/api/v1/project/` + this.$route.params.projectId + '/analytic/task-lifecycle', {headers})
          .then(response => (this.taskLifecycle.chartData = this.taskLifecycle.chartData.concat(response.data.data)))
      }
    },
    getDeliverability(){
      console.log("View Analytics (method) : Get deliverability " + this.$route.params.projectId +"/"+ this.$route.params.personId)
      let token = localStorage.getItem('token')
      const headers = {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + token,
      };

      this.deliverability.chartData = []

      if(this.$route.params.personId){
        this.deliverability.chartData.push(['Iteration', 'Team Deliver Rate', 'Team Rejection Rate', 'Personal Deliver Rate', 'Personal Rejection Rate'],);
        
        this.axios
          .get(`${this.appUrl}/api/v1/project/` + this.$route.params.projectId + '/analytic/deliverability/' + this.$route.params.personId, {headers})
          .then((response) => {this.formatDeliverability(null, null, null, response.data.data, null)});

        this.axios
          .get(`${this.appUrl}/api/v1/project/` + this.$route.params.projectId + '/analytic/rejection/' + this.$route.params.personId, {headers})
          .then((response) => {this.formatDeliverability(null, null, null, null, response.data.data)});
      }
      else{
        this.deliverability.chartData.push(['Iteration', 'Team Deliver Rate', 'Team Rejection Rate']);
      }

      this.axios
        .get(`${this.appUrl}/api/v1/project/` + this.$route.params.projectId + '/analytic/sprint-dates', {headers})
        .then((response) => {this.formatDeliverability(response.data.data, null, null, null, null)});

      this.axios
        .get(`${this.appUrl}/api/v1/project/` + this.$route.params.projectId + '/analytic/deliverability', {headers})
        .then((response) => {this.formatDeliverability(null, response.data.data, null, null, null)});

      this.axios
        .get(`${this.appUrl}/api/v1/project/` + this.$route.params.projectId + '/analytic/rejection', {headers})
        .then((response) => {this.formatDeliverability(null, null, response.data.data, null, null)});
    },
    formatDeliverability(chartDate, teamDeliverability, teamRejection, personalDeliverability, personalRejection){
      if(chartDate){
        if(this.deliverability.chartData.length === 1){
          chartDate.forEach(element => {this.deliverability.chartData.push(new Array(element, null, null));});
        }else{
          chartDate.forEach((element, index) => {this.deliverability.chartData[index + 1].splice(0, 1, element);});
        }
      }
      if(teamDeliverability){
        if(this.deliverability.chartData.length === 1){
          teamDeliverability.forEach(element => {this.deliverability.chartData.push(new Array(null, element, null));});
        }else{
          teamDeliverability.forEach((element, index) => {this.deliverability.chartData[index + 1].splice(1, 1, element);});
        }
      }
      if(teamRejection){
        if(this.deliverability.chartData.length === 1){
          teamRejection.forEach(element => {this.deliverability.chartData.push(new Array(null, null, element));});
        }else{
          teamRejection.forEach((element, index) => {this.deliverability.chartData[index + 1].splice(2, 1, element);});
        }
      }
      if(personalDeliverability){
        if(this.deliverability.chartData.length === 1){
          personalDeliverability.forEach(element => {this.deliverability.chartData.push(new Array(null, null, null, element, null));});
        }else{
          if(this.deliverability.chartData[1].length <5){
            personalDeliverability.forEach((element, index) => {this.deliverability.chartData[index + 1].push(element, null);});
          }else{
            personalDeliverability.forEach((element, index) => {this.deliverability.chartData[index + 1].splice(3, 1, element);});
          }
        }
      }
      if(personalRejection){
        if(this.deliverability.chartData.length === 1){
          personalRejection.forEach(element => {this.deliverability.chartData.push(new Array(null, null, null, element, element));});
        }else{
          if(this.deliverability.chartData[1].length <5){
            personalRejection.forEach((element, index) => {this.deliverability.chartData[index + 1].push(null, element);});
          }else{
            personalRejection.forEach((element, index) => {this.deliverability.chartData[index + 1].splice(4, 1, element);});
          }
        }
      }
    },
    getCardTimeline(){
      console.log("View Analytics (method) : Get task lifecycle " + this.$route.params.projectId +"/"+ this.$route.params.personId)
      console.log(new Date(2020, 4, 6, 9, 3, 8), new Date(2020, 4, 6, 9, 15, 17));
      let token = localStorage.getItem('token')
      const headers = {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + token,
      };

      this.cardTimeline.chartData = [
        [
          { type: 'string', id: 'Role' },
          { type: 'string', id: 'Name' },
          { type: 'string', id: 'style', role: 'style' },
          { type: 'date', id: 'Start' },
          { type: 'date', id: 'End' }
        ],
      ]

      if(this.$route.params.personId){
        this.axios
        .get(`${this.appUrl}/api/v1/project/` + this.$route.params.projectId + '/analytic/card-timeline/' + this.$route.params.personId, {headers})
        .then(response => (this.formatCardTimeline(response.data.data)))
      }else{
        this.axios
        .get(`${this.appUrl}/api/v1/project/` + this.$route.params.projectId + '/analytic/card-timeline/', {headers})
        .then(response => (this.formatCardTimeline(response.data.data)))
      }
    },
    formatCardTimeline(data){
      data.forEach(element => {
        let row = [element[0], element[1], element[2], new Date(element[3]), new Date(element[4])]
        this.cardTimeline.chartData.push(row)
      });
    }
  }
}
</script>