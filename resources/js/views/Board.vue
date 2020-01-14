<template>
    <div>
        <v-row class="black">
            <v-col cols="12" sm="4" class="py-0 px-3 custom-border grey" >
                <v-row><BoardColumnHeader :headerData="iceboxColumn"/></v-row>
                <v-row>
                    <v-col cols="12" sm="12">
                        <TaskCard :cardData="cards[0]" />
                    </v-col>
                </v-row>
            </v-col>
            <v-col cols="12" sm="4" class="py-0 px-3 custom-border grey" >
                <v-row><BoardColumnHeader :headerData="backlogColumn"/></v-row>
                <v-row>
                    <v-card v-for="n in 12" :key="n" class="my-1 mx-2" flat><v-list-item three-line>
                        <v-list-item-content>
                            <div class="overline mb-4">OVERLINE</div>
                            <v-list-item-title class="headline mb-1">Headline 5</v-list-item-title>
                            <v-list-item-subtitle>Greyhound divisely hello coldly fonwderfully</v-list-item-subtitle>
                        </v-list-item-content>

                        <v-list-item-avatar
                            tile
                            size="80"
                            color="grey"
                        ></v-list-item-avatar>
                        </v-list-item>

                        <v-card-actions>
                        <v-btn text>Button</v-btn>
                        <v-btn text>Button</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-row>
            </v-col>
            <v-col cols="12" sm="4" class="py-0 px-3 custom-border grey" >
                <v-row><BoardColumnHeader :headerData="doneColumn"/></v-row>
                <v-row>
                    <v-card v-for="n in 12" :key="n" class="my-1 mx-2" flat><v-list-item three-line>
                        <v-list-item-content>
                            <div class="overline mb-4">OVERLINE</div>
                            <v-list-item-title class="headline mb-1">Headline 5</v-list-item-title>
                            <v-list-item-subtitle>Greyhound divisely hello coldly fonwderfully</v-list-item-subtitle>
                        </v-list-item-content>

                        <v-list-item-avatar
                            tile
                            size="80"
                            color="grey"
                        ></v-list-item-avatar>
                        </v-list-item>

                        <v-card-actions>
                        <v-btn text>Button</v-btn>
                        <v-btn text>Button</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-row>
            </v-col>
        </v-row>
        
    </div>
</template>

<script>
/* eslint-disable*/
import BoardColumnHeader from '../components/BoardColumnHeader.vue'
import TaskCard from '../components/TaskCard.vue'
export default {
    created() {
        this.cards = this.getCards()
    },
    data() {
        return {
            iceboxColumn:{
                title:'Icebox'
            },
            backlogColumn:{
                title:'Current Iteration/Backlog'
            },
            doneColumn:{
                title:'Done'
            },
            cards: null
        }
    },
    methods: {
        getCards() {
            let token = localStorage.getItem('token')
            let selectedProjectId = this.$route.params.id
            
            const headers = {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token,
                };

            this.axios
                .get('http://127.0.0.1:8000/api/v1/project/' + selectedProjectId + '/cards', {headers})
                .then(response => this.cards = response.data)
            }
    },
    components: {
        BoardColumnHeader, TaskCard
    }
}
</script>

<style scoped>
    .custom-border {
        border: 10px red;
    }
</style>