<template>
      <v-row>
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
        <v-col cols=12 xs=12>
          <v-row justify="space-around">
            <v-card flat class="px-3 flex-grow-1">
              <v-card-title>Project Velocity
                <v-tooltip right>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn small icon v-bind="attrs" v-on="on">
                    <v-icon color="grey darken-1">mdi-information-outline</v-icon>
                  </v-btn>
                </template>
                <span>{{this.tooltip.estimation.velocityTooltip}}</span>
              </v-tooltip>
              </v-card-title>
              <v-card-text>
                <v-row class="display-1 font-weight-medium">
                  <v-col cols="2" class="indigo--text text-right">{{estimation.velocity}}</v-col>
                  <v-col class="flex-grow-1">points per sprint</v-col>
                </v-row>
              </v-card-text>
            </v-card>
            <v-card flat class="px-3 flex-grow-1">
              <v-card-title>Estimated Sprints Required
                <v-tooltip right>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn small icon v-bind="attrs" v-on="on">
                    <v-icon color="grey darken-1">mdi-information-outline</v-icon>
                  </v-btn>
                </template>
                <span>{{this.tooltip.estimation.estimationTooltip}}</span>
              </v-tooltip>
              </v-card-title>
              <v-card-text>
                <v-row class="display-1 font-weight-medium">
                  <v-col cols="2" class="indigo--text text-right">{{estimation.sprintEstimate}}</v-col>
                  <v-col class="flex-grow-1">sprints left</v-col>
                </v-row>
              </v-card-text>
            </v-card>
          </v-row>
          <v-row>
            <v-divider></v-divider>
            <v-expansion-panels multiple flat>
              <v-expansion-panel>
                <v-expansion-panel-header>How do I read this chart?</v-expansion-panel-header>
                <v-expansion-panel-content>
                  {{this.tooltip.estimation.help}}
                </v-expansion-panel-content>
              </v-expansion-panel>
            </v-expansion-panels>
          </v-row>
        </v-col>
      </v-row>
</template>

<script>
import { GChart } from 'vue-google-charts'

export default {
  created() {
  },
  watch: {
  },
  components:{
    GChart
  },
  data() {
    return {
      tooltip: {
        burndown:{
          tooltip:"Sprint progression shows you how much of the tasks points have been done and released",
          help:"Sprint progression shows you how much of the tasks points have been done and released. The blue line sums up the total task points that haven't been finished each day. The value goes down when a task is completed and goes up when a task is rejected or a new task is added to the current iteration. The red line represents the ideal graph for the current sprint where the tasks were completed evenly each day until the end of the sprint. Ideally the blue line should be moving downwards towards the end of the chart, moving parallel or below the red line."
        },
        deliverability:{
          tooltip:"Deliverability and rejection rate shows you the percentage of completed and rejected tasks from the total tasks that was planned for the sprint",
          help:"Deliverability and rejection rate shows you the percentage of completed and rejected tasks from the total tasks that was planned for the sprint. Deliverability rate shows you the percentage of task points your team has completed out of all task points planned in the begining of the sprint. Rejection rate shows you the percentage of time that the tasks spent on being rejected. Ideally the deliverability rate should be on 100% and the rejection rate on 0%. Low deliverability sometimes means the team can't complete the task they have planned, where high rejection means the team's tasks were often rejected and have to be started over."
        },
        taskLifecycle:{
          tooltip:"Task lifecycle shows you how long your tasks spends in each state on average",
          help:"Task lifecycle shows you how long your tasks spends in each state on average. This chart could tell you about bottlenecks on the development process by looking which state the tasks spends the most. For instance if the Planned state dominates the chart then it means you have planned way too many tasks, or if it's the Finished state then your tasks testing might be taking too much time."
        },
        estimation:{
          velocityTooltip:"The average velocity of your team, how much tasks points your team can finish on one sprint",
          estimationTooltip:"Estimated number of sprints required to finish the remaining tasks based on your team velocity",
          help:"The velocity shows you how much task points on average your team can complete in one sprint. The number is calculated based on the total task points your team has completed every sprint within up to the last five sprints. Velocity is then calculated on the average of the total completed task points. Estimation number shows you the estimated number of sprints left to complete the unfinished tasks in the backlog and the current iteration. It is calculated by dividing the total task points of unfinished tasks and dividing them by your current velocity."
        },
      },
      burndown: {
        chartData: [
          ['Day','Points Remaining','Ideal Burndown'],
          ["29 Jun",29,29],
          ["30 Jun",24,24.17],
          ["01 Jul",15,19.33],
          ["02 Jul",14,14.5],
          ["03 Jul",6,9.67],
          ["04 Jul",0,4.83],
          ["05 Jul",0,0]
        ],
        chartOptions: {
          subtitle: 'Points Remaining',
          colors: ['#1e88e5','#e53935'],
        }
      },
      deliverability:{
        chartData: [
          ['Iteration','Deliver Rate','Rejection Rate'],
          ["29 Jun - 06 Jul",100,7],
          ["06 Jul - 13 Jul",84,3],
          ["15 Jul - 22 Jul",91,11],
          ["13 Jul - 20 Jul",100,9],
          ["20 Jul - 27 Jul",100,15.3]
        ],
        chartOptions: {
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
          ['Planned', 40.5],
          ['Started', 12.7],
          ['Finished', 1.1],
          ['Accepted', 36.5],
          ['Rejected', 17.2],
        ],
        chartOptions: {
          pieHole: 0.4,
          colors: ['#dbdbdb','#f08000','#203e64','#629200','#a71f39']
        }
      },
      estimation: {
        velocity: 22,
        sprintEstimate: 3
      },
    }
  },
  methods:{

  }
}
</script>