<template>
      <v-row>
        <v-col cols=12 xs=12>
          <v-card flat class="px-3 mb-n1">
            <v-card-text>
              <v-row>
                <v-col cols=12 sm=4>
                  <v-select
                    class="pt-2 mb-n2"
                    dense
                    solo
                    prepend-icon="person"
                    label="Select Person"
                    return-object
                    single-line
                    :items="this.personSelections"
                    v-on:change="selectPerson"
                    item-text="username"
                    item-value="user_id"
                  ></v-select>
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

export default {
  created() {
    this.burndown.chartData = this.dummyData.team.burndown
    this.deliverability.chartData = this.dummyData.team.deliverability
    this.cardTimeline.chartData = this.dummyData.team.cardTimeline
    this.taskLifecycle.chartData = this.dummyData.team.taskLifecycle
  },
  watch: {
  },
  components:{
    GChart,
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
      personSelections: [
        { "user_id": 0, "username": "Whole Team" },
        { "user_id": 1, "username": "zainokta" },
        { "user_id": 2, "username": "madevara24" },
        { "user_id": 5, "username": "julian29" }
      ],
      selectedPerson: null,
      dummyData: {
        team:{
          burndown:[
            ["Day","Points Remaining","Ideal Burndown"],
            ["29 Jun",29,29],
            ["30 Jun",24,24.17],
            ["01 Jul",15,19.33],
            ["02 Jul",14,14.5],
            ["03 Jul",6,9.67],
            ["04 Jul",0,4.83],
            ["05 Jul",0,0]
          ],
          deliverability:[
            ['Iteration','Team Deliver Rate','Team Rejection Rate'],
            ["29 Jun - 06 Jul",100,7],
            ["06 Jul - 13 Jul",84,3],
            ["15 Jul - 22 Jul",91,11],
            ["13 Jul - 20 Jul",100,9],
            ["20 Jul - 27 Jul",100,15.3]
          ],
          taskLifecycle:[
            ['State','Avg. Hours'],
            ['planned',40.5],
            ['started',12.7],
            ['finished',1.1],
            ['accepted',36.5],
            ['rejected',17.2]
          ],
          cardTimeline:[
            [
              {type:"string", id:"Role"},
              {type:"string", id:"Name"},
              {type:"string", id:"style","role":"style"},
              {type:"date", id:"Start"},
              {type:"date", id:"End"}
            ],
            [ "#13 - Fix DB Connection for MongoDB", "Started", "#f08000", new Date("2020-06-29T02:27:12.000000Z"), new Date("2020-06-29T09:09:45.000000Z")],
            [ "#13 - Fix DB Connection for MongoDB", "Finished", "#203e64", new Date("2020-06-29T09:09:45.000000Z"), new Date("2020-06-29T09:39:54.000000Z")],
            [ "#13 - Fix DB Connection for MongoDB", "Accepted", "#629200", new Date("2020-06-29T09:39:54.000000Z"), new Date("2020-07-03T09:10:09.000000Z")],
            [ "#14 - Make UI Design for Login Page", "Planned", "#dbdbdb", new Date("2020-06-29T02:17:19.000000Z"), new Date("2020-06-29T02:31:05.000000Z")],
            [ "#14 - Make UI Design for Login Page", "Started", "#f08000", new Date("2020-06-29T02:31:05.000000Z"), new Date("2020-06-29T07:27:15.000000Z")],
            [ "#14 - Make UI Design for Login Page", "Finished", "#203e64", new Date("2020-06-29T07:27:15.000000Z"), new Date("2020-06-29T09:36:27.000000Z")],
            [ "#14 - Make UI Design for Login Page", "Rejected", "#a71f39", new Date("2020-06-29T09:36:27.000000Z"), new Date("2020-06-30T04:01:43.000000Z")],
            [ "#14 - Make UI Design for Login Page", "Started", "#f08000", new Date("2020-06-30T04:01:43.000000Z"), new Date("2020-06-30T09:01:20.000000Z")],
            [ "#14 - Make UI Design for Login Page", "Finished", "#203e64", new Date("2020-06-30T09:01:20.000000Z"), new Date("2020-06-30T09:09:20.000000Z")],
            [ "#14 - Make UI Design for Login Page", "Accepted", "#629200", new Date("2020-06-30T09:09:20.000000Z"), new Date("2020-07-03T09:13:45.000000Z")],
            [ "#15 - Make UI Design for Homepage", "Planned", "#dbdbdb", new Date("2020-06-29T02:17:27.000000Z"), new Date("2020-06-29T07:53:28.000000Z")],
            [ "#15 - Make UI Design for Homepage", "Started", "#f08000", new Date("2020-06-29T07:53:28.000000Z"), new Date("2020-06-30T03:44:00.000000Z")],
            [ "#15 - Make UI Design for Homepage", "Finished", "#203e64", new Date("2020-06-30T03:44:00.000000Z"), new Date("2020-06-30T09:14:00.000000Z")],
            [ "#15 - Make UI Design for Homepage", "Accepted", "#629200", new Date("2020-06-30T09:14:00.000000Z"), new Date("2020-07-03T09:16:28.000000Z")],
            [ "#16 - Set Up Web Routes", "Planned", "#dbdbdb", new Date("2020-06-29T02:17:43.000000Z"), new Date("2020-06-30T02:36:43.000000Z")],
            [ "#16 - Set Up Web Routes", "Started", "#f08000", new Date("2020-06-30T02:36:43.000000Z"), new Date("2020-06-30T08:56:12.000000Z")],
            [ "#16 - Set Up Web Routes", "Finished", "#203e64", new Date("2020-06-30T08:56:12.000000Z"), new Date("2020-06-30T09:05:12.000000Z")],
            [ "#16 - Set Up Web Routes", "Accepted", "#629200", new Date("2020-06-30T09:05:12.000000Z"), new Date("2020-07-03T09:19:54.000000Z")],
            [ "#17 - Make Biome Creation Feature", "Planned", "#dbdbdb", new Date("2020-06-29T02:18:23.000000Z"), new Date("2020-06-29T02:57:51.000000Z")],
            [ "#17 - Make Biome Creation Feature", "Started", "#f08000", new Date("2020-06-29T02:57:51.000000Z"), new Date("2020-06-29T09:33:12.000000Z")],
            [ "#17 - Make Biome Creation Feature", "Finished", "#203e64", new Date("2020-06-29T09:33:12.000000Z"), new Date("2020-06-29T09:40:21.000000Z")],
            [ "#17 - Make Biome Creation Feature", "Accepted", "#629200", new Date("2020-06-29T09:40:21.000000Z"), new Date("2020-07-03T09:21:40.000000Z")],
            [ "#26 - Make Setting Page", "Planned", "#dbdbdb", new Date("2020-06-29T02:19:27.000000Z"), new Date("2020-07-01T02:29:37.000000Z")],
            [ "#26 - Make Setting Page", "Started", "#f08000", new Date("2020-07-01T02:29:37.000000Z"), new Date("2020-07-01T08:13:25.000000Z")],
            [ "#26 - Make Setting Page", "Finished", "#203e64", new Date("2020-07-01T08:13:25.000000Z"), new Date("2020-07-01T09:03:25.000000Z")],
            [ "#26 - Make Setting Page", "Accepted", "#629200", new Date("2020-07-01T09:03:25.000000Z"), new Date("2020-07-03T09:10:09.000000Z")],
            [ "#27 - Fix Bug Google Login", "Planned", "#dbdbdb", new Date("2020-06-29T02:18:19.000000Z"), new Date("2020-07-02T02:05:44.000000Z")],
            [ "#27 - Fix Bug Google Login", "Started", "#f08000", new Date("2020-07-02T02:05:44.000000Z"), new Date("2020-07-02T09:03:04.000000Z")],
            [ "#27 - Fix Bug Google Login", "Finished", "#203e64", new Date("2020-07-02T09:03:04.000000Z"), new Date("2020-07-02T09:30:04.000000Z")],
            [ "#27 - Fix Bug Google Login", "Accepted", "#629200", new Date("2020-07-02T09:30:04.000000Z"), new Date("2020-07-03T09:13:45.000000Z")],
            [ "#28 - Make UI Design for Biome Creation Page", "Planned", "#dbdbdb", new Date("2020-06-29T02:19:35.000000Z"), new Date("2020-07-01T02:29:37.000000Z")],
            [ "#28 - Make UI Design for Biome Creation Page", "Started", "#f08000", new Date("2020-07-01T02:29:37.000000Z"), new Date("2020-07-01T05:37:38.000000Z")],
            [ "#28 - Make UI Design for Biome Creation Page", "Finished", "#203e64", new Date("2020-07-01T05:37:38.000000Z"), new Date("2020-07-01T09:07:38.000000Z")],
            [ "#28 - Make UI Design for Biome Creation Page", "Accepted", "#629200", new Date("2020-07-01T09:07:38.000000Z"), new Date("2020-07-03T09:16:28.000000Z")],
            [ "#29 - Make UI Design for Biome Cards", "Planned", "#dbdbdb", new Date("2020-06-29T02:20:43.000000Z"), new Date("2020-07-01T05:57:45.000000Z")],
            [ "#29 - Make UI Design for Biome Cards", "Started", "#f08000", new Date("2020-07-01T05:57:45.000000Z"), new Date("2020-07-01T08:31:54.000000Z")],
            [ "#29 - Make UI Design for Biome Cards", "Finished", "#203e64", new Date("2020-07-01T08:31:54.000000Z"), new Date("2020-07-01T09:11:54.000000Z")],
            [ "#29 - Make UI Design for Biome Cards", "Accepted", "#629200", new Date("2020-07-01T09:11:54.000000Z"), new Date("2020-07-03T09:19:54.000000Z")],
            [ "#30 - Make UI Design for Setting Page", "Planned", "#dbdbdb", new Date("2020-06-29T02:18:23.000000Z"), new Date("2020-07-03T09:21:40.000000Z")],
            [ "#31 - Make Biome Lifecycle Feature", "Planned", "#dbdbdb", new Date("2020-06-29T02:19:27.000000Z"), new Date("2020-06-30T02:47:56.000000Z")],
            [ "#31 - Make Biome Lifecycle Feature", "Started", "#f08000", new Date("2020-06-30T02:47:56.000000Z"), new Date("2020-07-01T08:40:41.000000Z")],
            [ "#31 - Make Biome Lifecycle Feature", "Finished", "#203e64", new Date("2020-07-01T08:40:41.000000Z"), new Date("2020-07-01T09:14:05.000000Z")],
            [ "#31 - Make Biome Lifecycle Feature", "Rejected", "#a71f39", new Date("2020-07-01T09:14:05.000000Z"), new Date("2020-07-02T02:32:35.000000Z")],
            [ "#31 - Make Biome Lifecycle Feature", "Started", "#f08000", new Date("2020-07-02T02:32:35.000000Z"), new Date("2020-07-02T09:16:26.000000Z")],
            [ "#31 - Make Biome Lifecycle Feature", "Finished", "#203e64", new Date("2020-07-02T09:16:26.000000Z"), new Date("2020-07-02T09:36:43.000000Z")],
            [ "#31 - Make Biome Lifecycle Feature", "Accepted", "#629200", new Date("2020-07-02T09:36:43.000000Z"), new Date("2020-07-03T09:23:33.000000Z")],
            [ "#32 - Make Homepage", "Planned", "#dbdbdb", new Date("2020-06-29T02:20:20.000000Z"), new Date("2020-07-03T02:20:58.000000Z")],
            [ "#32 - Make Homepage", "Started", "#f08000", new Date("2020-07-03T02:20:58.000000Z"), new Date("2020-07-03T04:34:45.000000Z")],
            [ "#32 - Make Homepage", "Finished", "#203e64", new Date("2020-07-03T04:34:45.000000Z"), new Date("2020-07-03T08:44:45.000000Z")],
            [ "#32 - Make Homepage", "Accepted", "#629200", new Date("2020-07-03T08:44:45.000000Z"), new Date("2020-07-03T09:25:19.000000Z")],
            [ "#33 - Make Navigation System", "Planned", "#dbdbdb", new Date("2020-06-29T02:21:50.000000Z"), new Date("2020-07-03T05:04:55.000000Z")],
            [ "#33 - Make Navigation System", "Started", "#f08000", new Date("2020-07-03T05:04:55.000000Z"), new Date("2020-07-03T08:30:29.000000Z")],
            [ "#33 - Make Navigation System", "Finished", "#203e64", new Date("2020-07-03T08:30:29.000000Z"), new Date("2020-07-03T08:50:29.000000Z")],
            [ "#33 - Make Navigation System", "Accepted", "#629200", new Date("2020-07-03T08:50:29.000000Z"), new Date("2020-07-03T09:27:38.000000Z")],
            [ "#34 - Make UI Design for Navigation", "Planned", "#dbdbdb", new Date("2020-06-29T02:22:33.000000Z"), new Date("2020-07-02T02:21:25.000000Z")],
            [ "#34 - Make UI Design for Navigation", "Started", "#f08000", new Date("2020-07-02T02:21:25.000000Z"), new Date("2020-07-02T08:53:20.000000Z")],
            [ "#34 - Make UI Design for Navigation", "Finished", "#203e64", new Date("2020-07-02T08:53:20.000000Z"), new Date("2020-07-02T09:23:20.000000Z")],
            [ "#34 - Make UI Design for Navigation", "Accepted", "#629200", new Date("2020-07-02T09:23:20.000000Z"), new Date("2020-07-03T09:30:04.000000Z")],
            [ "#35 - Make Biome Alteration Feature", "Planned", "#dbdbdb", new Date("2020-06-29T02:23:01.000000Z"), new Date("2020-07-03T02:27:24.000000Z")],
            [ "#35 - Make Biome Alteration Feature", "Started", "#f08000", new Date("2020-07-03T02:27:24.000000Z"), new Date("2020-07-03T08:39:20.000000Z")],
            [ "#35 - Make Biome Alteration Feature", "Finished", "#203e64", new Date("2020-07-03T08:39:20.000000Z"), new Date("2020-07-03T08:59:20.000000Z")],
            [ "#35 - Make Biome Alteration Feature", "Accepted", "#629200", new Date("2020-07-03T08:59:20.000000Z"), new Date("2020-07-03T09:32:46.000000Z")]
          ]
        },
        zainokta:{
          burndown:[
            ["Day","Team Points Remaining","Team Ideal Burndown","Personal Points Remaining","Personal Ideal Burndown"],
            ["29 Jun",29,29,9,9],
            ["30 Jun",24,24.17,6,7.5],
            ["01 Jul",15,19.33,3,6],
            ["02 Jul",14,14.5,6,4.5],
            ["03 Jul",6,9.67,3,3],
            ["04 Jul",0,4.83,0,1.5],
            ["05 Jul",0,0,0,0]
          ],
          deliverability:[
            ["Iteration","Team Deliver Rate","Team Rejection Rate","Personal Deliver Rate","Personal Rejection Rate"],
            ["29 Jun - 06 Jul",100,7,100,5],
            ["06 Jul - 13 Jul",84,3,77,0],
            ["15 Jul - 22 Jul",91,11,80,3],
            ["13 Jul - 20 Jul",100,9,100,6],
            ["20 Jul - 27 Jul",100,15.3,100,15.3]
          ],
          taskLifecycle:[ ["State","Avg. Hours"], ["planned",30.3], ["started",22.5], ["finished",0.4], ["accepted",30.4], ["rejected",16.6] ],
          cardTimeline:[
            [
              {"type":"string","id":"Role"},
              {"type":"string","id":"Name"},
              {"type":"string","id":"style","role":"style"},
              {"type":"date","id":"Start"},
              {"type":"date","id":"End"}
            ],
            ["#17 - Make Biome Creation Feature","Planned","#dbdbdb",new Date("2020-06-29T02:18:23.000Z"),new Date("2020-06-29T02:57:51.000Z")],
            ["#17 - Make Biome Creation Feature","Started","#f08000",new Date("2020-06-29T02:57:51.000Z"),new Date("2020-06-29T09:33:12.000Z")],
            ["#17 - Make Biome Creation Feature","Finished","#203e64",new Date("2020-06-29T09:33:12.000Z"),new Date("2020-06-29T09:40:21.000Z")],
            ["#17 - Make Biome Creation Feature","Accepted","#629200",new Date("2020-06-29T09:40:21.000Z"),new Date("2020-07-03T09:21:40.000Z")],
            ["#31 - Make Biome Lifecycle Feature","Planned","#dbdbdb",new Date("2020-06-29T02:19:27.000Z"),new Date("2020-06-30T02:47:56.000Z")],
            ["#31 - Make Biome Lifecycle Feature","Started","#f08000",new Date("2020-06-30T02:47:56.000Z"),new Date("2020-07-01T08:40:41.000Z")],
            ["#31 - Make Biome Lifecycle Feature","Finished","#203e64",new Date("2020-07-01T08:40:41.000Z"),new Date("2020-07-01T09:14:05.000Z")],
            ["#31 - Make Biome Lifecycle Feature","Rejected","#a71f39",new Date("2020-07-01T09:14:05.000Z"),new Date("2020-07-02T02:32:35.000Z")],
            ["#31 - Make Biome Lifecycle Feature","Started","#f08000",new Date("2020-07-02T02:32:35.000Z"),new Date("2020-07-02T09:16:26.000Z")],
            ["#31 - Make Biome Lifecycle Feature","Finished","#203e64",new Date("2020-07-02T09:16:26.000Z"),new Date("2020-07-02T09:36:43.000Z")],
            ["#31 - Make Biome Lifecycle Feature","Accepted","#629200",new Date("2020-07-02T09:36:43.000Z"),new Date("2020-07-03T09:23:33.000Z")],
            ["#35 - Make Biome Alteration Feature","Planned","#dbdbdb",new Date("2020-06-29T02:23:01.000Z"),new Date("2020-07-03T02:27:24.000Z")],
            ["#35 - Make Biome Alteration Feature","Started","#f08000",new Date("2020-07-03T02:27:24.000Z"),new Date("2020-07-03T08:39:20.000Z")],
            ["#35 - Make Biome Alteration Feature","Finished","#203e64",new Date("2020-07-03T08:39:20.000Z"),new Date("2020-07-03T08:59:20.000Z")],
            ["#35 - Make Biome Alteration Feature","Accepted","#629200",new Date("2020-07-03T08:59:20.000Z"),new Date("2020-07-03T09:32:46.000Z")]
          ],
        },
        devara:{
          burndown:[
            ["Day","Team Points Remaining","Team Ideal Burndown","Personal Points Remaining","Personal Ideal Burndown"],
            ["29 Jun",29,29,14,14],
            ["30 Jun",24,24.17,13,11.67],
            ["01 Jul",15,19.33,8,9.33],
            ["02 Jul",14,14.5,5,7],
            ["03 Jul",6,9.67,0,4.67],
            ["04 Jul",0,4.83,0,2.33],
            ["05 Jul",0,0,0,0]
          ],
          deliverability: [
            ["Iteration","Team Deliver Rate","Team Rejection Rate","Personal Deliver Rate","Personal Rejection Rate"],
            ["29 Jun - 06 Jul",100,7,100,0],
            ["06 Jul - 13 Jul",84,3,100,0],
            ["15 Jul - 22 Jul",91,11,100,0],
            ["13 Jul - 20 Jul",100,9,100,0],
            ["20 Jul - 27 Jul",100,15.3,100,0]
          ],
          taskLifecycle: [ ["State","Avg. Hours"], ["planned",42.9], ["started",10.6], ["finished",0.9], ["accepted",30.6], ["rejected",0] ],
          cardTimeline: [
            [
              {"type":"string","id":"Role"},
              {"type":"string","id":"Name"},
              {"type":"string","id":"style","role":"style"},
              {"type":"date","id":"Start"},
              {"type":"date","id":"End"}
            ],
            ["#13 - Fix DB Connection for MongoDB","Planned","#dbdbdb",new Date("2020-06-29T02:16:27.000Z"),new Date("2020-06-29T02:27:12.000Z")],
            ["#13 - Fix DB Connection for MongoDB","Started","#f08000",new Date("2020-06-29T02:27:12.000Z"),new Date("2020-06-29T09:09:45.000Z")],
            ["#13 - Fix DB Connection for MongoDB","Finished","#203e64",new Date("2020-06-29T09:09:45.000Z"),new Date("2020-06-29T09:39:54.000Z")],
            ["#13 - Fix DB Connection for MongoDB","Accepted","#629200",new Date("2020-06-29T09:39:54.000Z"),new Date("2020-07-03T09:10:09.000Z")],
            ["#16 - Set Up Web Routes","Planned","#dbdbdb",new Date("2020-06-29T02:17:43.000Z"),new Date("2020-06-30T02:36:43.000Z")],
            ["#16 - Set Up Web Routes","Started","#f08000",new Date("2020-06-30T02:36:43.000Z"),new Date("2020-06-30T08:56:12.000Z")],
            ["#16 - Set Up Web Routes","Finished","#203e64",new Date("2020-06-30T08:56:12.000Z"),new Date("2020-06-30T09:05:12.000Z")],
            ["#16 - Set Up Web Routes","Accepted","#629200",new Date("2020-06-30T09:05:12.000Z"),new Date("2020-07-03T09:19:54.000Z")],
            ["#26 - Make Setting Page","Planned","#dbdbdb",new Date("2020-06-29T02:19:27.000Z"),new Date("2020-07-01T02:29:37.000Z")],
            ["#26 - Make Setting Page","Started","#f08000",new Date("2020-07-01T02:29:37.000Z"),new Date("2020-07-01T08:13:25.000Z")],
            ["#26 - Make Setting Page","Finished","#203e64",new Date("2020-07-01T08:13:25.000Z"),new Date("2020-07-01T09:03:25.000Z")],
            ["#26 - Make Setting Page","Accepted","#629200",new Date("2020-07-01T09:03:25.000Z"),new Date("2020-07-03T09:10:09.000Z")],
            ["#27 - Fix Bug Google Login","Planned","#dbdbdb",new Date("2020-06-29T02:18:19.000Z"),new Date("2020-07-02T02:05:44.000Z")],
            ["#27 - Fix Bug Google Login","Started","#f08000",new Date("2020-07-02T02:05:44.000Z"),new Date("2020-07-02T09:03:04.000Z")],
            ["#27 - Fix Bug Google Login","Finished","#203e64",new Date("2020-07-02T09:03:04.000Z"),new Date("2020-07-02T09:30:04.000Z")],
            ["#27 - Fix Bug Google Login","Accepted","#629200",new Date("2020-07-02T09:30:04.000Z"),new Date("2020-07-03T09:13:45.000Z")],
            ["#32 - Make Homepage","Planned","#dbdbdb",new Date("2020-06-29T02:20:20.000Z"),new Date("2020-07-03T02:20:58.000Z")],
            ["#32 - Make Homepage","Started","#f08000",new Date("2020-07-03T02:20:58.000Z"),new Date("2020-07-03T04:34:45.000Z")],
            ["#32 - Make Homepage","Finished","#203e64",new Date("2020-07-03T04:34:45.000Z"),new Date("2020-07-03T08:44:45.000Z")],
            ["#32 - Make Homepage","Accepted","#629200",new Date("2020-07-03T08:44:45.000Z"),new Date("2020-07-03T09:25:19.000Z")],
            ["#33 - Make Navigation System","Planned","#dbdbdb",new Date("2020-06-29T02:21:50.000Z"),new Date("2020-07-03T05:04:55.000Z")],
            ["#33 - Make Navigation System","Started","#f08000",new Date("2020-07-03T05:04:55.000Z"),new Date("2020-07-03T08:30:29.000Z")],
            ["#33 - Make Navigation System","Finished","#203e64",new Date("2020-07-03T08:30:29.000Z"),new Date("2020-07-03T08:50:29.000Z")],
            ["#33 - Make Navigation System","Accepted","#629200",new Date("2020-07-03T08:50:29.000Z"),new Date("2020-07-03T09:27:38.000Z")]
          ]
        },
        julian:{
          burndown:[
            ["Day","Team Points Remaining","Team Ideal Burndown","Personal Points Remaining","Personal Ideal Burndown"],
            ["29 Jun",29,29,6,6],
            ["30 Jun",24,24.17,5,5],
            ["01 Jul",15,19.33,4,4],
            ["02 Jul",14,14.5,3,3],
            ["03 Jul",6,9.67,3,2],
            ["04 Jul",0,4.83,0,1],
            ["05 Jul",0,0,0,0]
          ],
          deliverability: [
            ["Iteration","Team Deliver Rate","Team Rejection Rate","Personal Deliver Rate","Personal Rejection Rate"],
            ["29 Jun - 06 Jul",100,7,100,2],
            ["06 Jul - 13 Jul",84,3,76,3],
            ["15 Jul - 22 Jul",91,11,88,8],
            ["13 Jul - 20 Jul",100,9,100,3],
            ["20 Jul - 27 Jul",100,15.3,100,0]
          ],
          taskLifecycle: [ ["State","Avg. Hours"], ["planned",43.5], ["started",6.7], ["finished",1.9], ["accepted",48.4], ["rejected",18.4] ],
          cardTimeline: [
            [
              {"type":"string","id":"Role"},
              {"type":"string","id":"Name"},
              {"type":"string","id":"style","role":"style"},
              {"type":"date","id":"Start"},
              {"type":"date","id":"End"}
            ],
            ["#14 - Make UI Design for Login Page","Planned","#dbdbdb",new Date("2020-06-29T02:17:19.000Z"),new Date("2020-06-29T02:31:05.000Z")],
            ["#14 - Make UI Design for Login Page","Started","#f08000",new Date("2020-06-29T02:31:05.000Z"),new Date("2020-06-29T07:27:15.000Z")],
            ["#14 - Make UI Design for Login Page","Finished","#203e64",new Date("2020-06-29T07:27:15.000Z"),new Date("2020-06-29T09:36:27.000Z")],
            ["#14 - Make UI Design for Login Page","Rejected","#a71f39",new Date("2020-06-29T09:36:27.000Z"),new Date("2020-06-30T04:01:43.000Z")],
            ["#14 - Make UI Design for Login Page","Started","#f08000",new Date("2020-06-30T04:01:43.000Z"),new Date("2020-06-30T09:01:20.000Z")],
            ["#14 - Make UI Design for Login Page","Finished","#203e64",new Date("2020-06-30T09:01:20.000Z"),new Date("2020-06-30T09:09:20.000Z")],
            ["#14 - Make UI Design for Login Page","Accepted","#629200",new Date("2020-06-30T09:09:20.000Z"),new Date("2020-07-03T09:13:45.000Z")],
            ["#15 - Make UI Design for Homepage","Planned","#dbdbdb",new Date("2020-06-29T02:17:27.000Z"),new Date("2020-06-29T07:53:28.000Z")],
            ["#15 - Make UI Design for Homepage","Started","#f08000",new Date("2020-06-29T07:53:28.000Z"),new Date("2020-06-30T03:44:00.000Z")],
            ["#15 - Make UI Design for Homepage","Finished","#203e64",new Date("2020-06-30T03:44:00.000Z"),new Date("2020-06-30T09:14:00.000Z")],
            ["#15 - Make UI Design for Homepage","Accepted","#629200",new Date("2020-06-30T09:14:00.000Z"),new Date("2020-07-03T09:16:28.000Z")],
            ["#28 - Make UI Design for Biome Creation Page","Planned","#dbdbdb",new Date("2020-06-29T02:19:35.000Z"),new Date("2020-07-01T02:29:37.000Z")],
            ["#28 - Make UI Design for Biome Creation Page","Started","#f08000",new Date("2020-07-01T02:29:37.000Z"),new Date("2020-07-01T05:37:38.000Z")],
            ["#28 - Make UI Design for Biome Creation Page","Finished","#203e64",new Date("2020-07-01T05:37:38.000Z"),new Date("2020-07-01T09:07:38.000Z")],
            ["#28 - Make UI Design for Biome Creation Page","Accepted","#629200",new Date("2020-07-01T09:07:38.000Z"),new Date("2020-07-03T09:16:28.000Z")],
            ["#29 - Make UI Design for Biome Cards","Planned","#dbdbdb",new Date("2020-06-29T02:20:43.000Z"),new Date("2020-07-01T05:57:45.000Z")],
            ["#29 - Make UI Design for Biome Cards","Started","#f08000",new Date("2020-07-01T05:57:45.000Z"),new Date("2020-07-01T08:31:54.000Z")],
            ["#29 - Make UI Design for Biome Cards","Finished","#203e64",new Date("2020-07-01T08:31:54.000Z"),new Date("2020-07-01T09:11:54.000Z")],
            ["#29 - Make UI Design for Biome Cards","Accepted","#629200",new Date("2020-07-01T09:11:54.000Z"),new Date("2020-07-03T09:19:54.000Z")],
            ["#30 - Make UI Design for Setting Page","Planned","#dbdbdb",new Date("2020-06-29T02:18:23.000Z"),new Date("2020-07-03T09:21:40.000Z")],
            ["#34 - Make UI Design for Navigation","Planned","#dbdbdb",new Date("2020-06-29T02:22:33.000Z"),new Date("2020-07-02T02:21:25.000Z")],
            ["#34 - Make UI Design for Navigation","Started","#f08000",new Date("2020-07-02T02:21:25.000Z"),new Date("2020-07-02T08:53:20.000Z")],
            ["#34 - Make UI Design for Navigation","Finished","#203e64",new Date("2020-07-02T08:53:20.000Z"),new Date("2020-07-02T09:23:20.000Z")],
            ["#34 - Make UI Design for Navigation","Accepted","#629200",new Date("2020-07-02T09:23:20.000Z"),new Date("2020-07-03T09:30:04.000Z")]
          ]
        }
      },
      burndown: {
        chartData: null,
        chartOptions: {
          subtitle: 'Points Remaining',
          colors: ['#1e88e5','#e53935','#6ab7ff','#ff6f60'],
          vAxis : {
            minValue : 0
          },
        }
      },
      deliverability:{
        chartData: null,
        chartOptions: {
          vAxis : {
            maxValue : 100,
            minValue : 0
          },
          colors: ['#1e88e5','#e53935','#6ab7ff','#ff6f60'],
        }
      },
      cardTimeline: {
        chartData: null,
        chartOptions: {
        }
      },
      taskLifecycle:{
        chartData: null,
        chartOptions: {
          pieHole: 0.4,
          colors: ['#dbdbdb','#f08000','#203e64','#629200','#a71f39']
        }
      },
    }
  },
  methods:{
    selectPerson(data){
      let personId = data.user_id
      switch (personId) {
        case 0:
          this.burndown.chartData = this.dummyData.team.burndown
          this.deliverability.chartData = this.dummyData.team.deliverability
          this.cardTimeline.chartData = this.dummyData.team.cardTimeline
          this.taskLifecycle.chartData = this.dummyData.team.taskLifecycle
          break;
        case 1:
          this.burndown.chartData = this.dummyData.zainokta.burndown
          this.deliverability.chartData = this.dummyData.zainokta.deliverability
          this.cardTimeline.chartData = this.dummyData.zainokta.cardTimeline
          this.taskLifecycle.chartData = this.dummyData.zainokta.taskLifecycle
          break;
        case 2:
          this.burndown.chartData = this.dummyData.devara.burndown
          this.deliverability.chartData = this.dummyData.devara.deliverability
          this.cardTimeline.chartData = this.dummyData.devara.cardTimeline
          this.taskLifecycle.chartData = this.dummyData.devara.taskLifecycle
          break;
        case 5:
          this.burndown.chartData = this.dummyData.julian.burndown
          this.deliverability.chartData = this.dummyData.julian.deliverability
          this.cardTimeline.chartData = this.dummyData.julian.cardTimeline
          this.taskLifecycle.chartData = this.dummyData.julian.taskLifecycle
          break;
        default:
          this.burndown.chartData = this.dummyData.team.burndown
          this.deliverability.chartData = this.dummyData.team.deliverability
          this.cardTimeline.chartData = this.dummyData.team.cardTimeline
          this.taskLifecycle.chartData = this.dummyData.team.taskLifecycle
          break;
      }
    }
  }
}
</script>