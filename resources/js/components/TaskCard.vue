<template>
    <div>
        <v-sheet color="white px-3" width="inherit">
            <v-row>
                <template>
                    <v-btn class="ma-1" color="primary" dark icon @click="isExtended = !isExtended">
                        <v-icon v-if="isExtended" dark>arrow_drop_down</v-icon>
                        <v-icon v-if="!isExtended" dark>arrow_right</v-icon>
                    </v-btn>
                </template>
                <template v-if="!isExtended">
                    <span class="subtitle-1 d-flex align-center">{{cardData.title}}</span>
                    <v-spacer></v-spacer>
                    <v-btn class="mt-2 mr-2 d-flex align-right" color="primary" dark small>
                        State
                    </v-btn>
                </template>
                <template v-if="isExtended">
                    <v-text-field v-model="cardData.title" class="mr-3">

                    </v-text-field>
                </template>
            </v-row>
            <template v-if="isExtended">
                <v-form>
                    <v-row class="my-n3">
                        <v-col cols="12">
                            <v-text-field class="ml-2" single-line dense readonly v-model="cardData.id" hide-details>
                                <template v-slot:append-outer>
                                    <v-btn icon @click="copyCardId()"><v-icon>file_copy</v-icon></v-btn>
                                    <v-btn icon><v-icon>delete</v-icon></v-btn>
                                </template>
                            </v-text-field>
                            <input type="hidden" id="cardIdSelector" :value="cardData.id">
                        </v-col>
                    </v-row>
                    <v-row class="my-n3">
                        <v-col cols="5" class="d-flex align-center">
                            <div class="caption">State</div>
                        </v-col>
                        <v-col cols="7" class="py-1 my-1">
                            <v-select dense :items="select.cardStates" hide-details class="pt-1">
                                <template v-slot:prepend>
                                    <v-btn color="primary" dark small>
                                        State
                                    </v-btn>
                                </template>
                            </v-select>
                        </v-col>
                    </v-row>
                    <v-row class="my-n3">
                        <v-col cols="5" class="d-flex align-center">
                            <div class="caption">Card Type</div>
                        </v-col>
                        <v-col cols="7" class="py-1 my-1">
                            <v-select dense :items="select.storyTypes" hide-details class="pt-1">

                            </v-select>
                        </v-col>
                    </v-row>
                    <v-row class="my-n3">
                        <v-col cols="5" class="d-flex align-center">
                            <div class="caption">Points</div>
                        </v-col>
                        <v-col cols="7" class="py-1 my-1">
                            <v-select dense :items="select.points" hide-details class="pt-1">

                            </v-select>
                        </v-col>
                    </v-row>
                    <v-row class="my-n3">
                        <v-col cols="5" class="d-flex align-center">
                            <div class="caption">Requester</div>
                        </v-col>
                        <v-col cols="7" class="py-1 my-1">
                            <v-select dense :items="select.teamMembers" hide-details class="pt-1">

                            </v-select>
                        </v-col>
                    </v-row>
                    <v-row class="my-n3">
                        <v-col cols="5" class="d-flex align-center">
                            <div class="caption">Owner</div>
                        </v-col>
                        <v-col cols="7" class="py-1 my-1">
                            <v-select dense :items="select.teamMembers" hide-details class="pt-1">

                            </v-select>
                        </v-col>
                    </v-row>
                    <v-row class="my-1">
                        <v-col cols="5" class="d-flex align-center">
                            <div class="caption">Branch</div>
                        </v-col>
                        <v-col cols="7" class="py-1 my-1">
                            <v-select dense :items="select.branches" hide-details class="pt-1">

                            </v-select>
                        </v-col>
                    </v-row>
                    <v-row class="">
                        <v-col cols="12">
                            <div class="caption">Description</div>
                            <v-textarea outlined value="The Woodman set to work at once, and so sharp was his axe that the tree was soon chopped nearly through."></v-textarea>
                        </v-col>
                    </v-row>
                </v-form>
            </template>
        </v-sheet>
        <v-snackbar v-model="copyCardIdSnackbar.isUp" :timeout="2000">
            {{ copyCardIdSnackbar.message }}
            <v-btn color="blue" text @click="copyCardIdSnackbar.isUp = false">Close</v-btn>
        </v-snackbar>
    </div>
</template>
<script>
/*eslint-disable */
export default {
    props:{
        cardData: null
    },
    data() {
        return {
            isExtended: false,
            copyCardIdSnackbar: {
                isUp: false,
                message: null
            },
            select: {
                cardStates: ['Created','Planned','Started','Finished','Accepted','Rejected','Released'],
                storyTypes: ['Feature','Bug'],
                points: [0,1,3,5,8],
                teamMembers: ['Devara','Zainokta','Masjul','Subosko','Sendiqi'],
                branches: ['#194772-feature/make-auth','#194764-feature/make-dashboard','#194777-bug/navbar-button']
            }
        }
    },
    methods: {
        copyCardId(){
            console.log('Click copy')
            let copiedCardId = document.querySelector('#cardIdSelector')
            copiedCardId.setAttribute('type', 'text')
            copiedCardId.select()

            try {
                var success = document.execCommand('copy')
                var message = success ? 'successful' : 'unsuccessful';
                this.copyCardIdSnackbar.message = 'Card ID copied to clipboard'
            } catch (error) {
                this.copyCardIdSnackbar.message = 'Fail to copy Card ID'
            }
            copiedCardId.setAttribute('type', 'hidden')
            window.getSelection().removeAllRanges()
            this.copyCardIdSnackbar.isUp = true
            console.log(this.copyCardIdSnackbar)
        }

    },
}
</script>