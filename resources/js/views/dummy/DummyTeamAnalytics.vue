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
                <span>Sprint progression shows you how much of the tasks points have been done and released</span>
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
                <span>Deliverability and rejection rate shows you the percentage of completed and rejected tasks from the total tasks that was planned for the sprint</span>
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
                <span>Task lifecycle shows you how long your tasks spends in each state on average</span>
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
                <span>The average velocity of your team, how much tasks points your team can finish on one sprint</span>
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
                <span>Estimated number of sprints required to finish the remaining tasks based on your team velocity</span>
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
      burndown: {
        chartData: [
          ['Day','Points Remaining','Ideal Burndown'],
          ['22 Jun',29,29],
          ['23 Jun',29,24.17],
          ['24 Jun',29,19.33],
          ['25 Jun',29,14.5],
          ['26 Jun',0,9.67],
          ['27 Jun',0,4.83],
          ['28 Jun',0,0]
        ],
        chartOptions: {
          subtitle: 'Points Remaining',
          colors: ['#1e88e5','#e53935'],
        }
      },
      deliverability:{
        chartData: [
          ['Iteration','Deliver Rate','Rejection Rate'],
          ['25-01 Jun',0,0],
          ['01-08 Jun',0,0],
          ['08-15 Jun',0,0],
          ['15-22 Jun',100,15.3],
          ['22-29 Jun',100,0]
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
        velocity: 9,
        sprintEstimate: 0
      },
    }
  },
  methods:{

  }
}
</script>