<template>
    <v-select
    class="pt-2"
    dense
    solo
    prepend-icon="folder"
    label="Select Person"
    return-object
    single-line
    v-model="this.selectedPerson"
    :items="this.personSelections"
    v-on:change="selectPerson"
    item-text="name"
    item-value="id"
    ></v-select>
</template>

<script>
export default {
    created() {
        console.log("Component PersonSelecter (created) : Route name : " + this.$route.name)
        this.getPerson()
    },
    data() {
        return {
            personSelections: null,
            selectedPerson: null,
        }
    },
    methods: {
        getPerson(){
            let token = localStorage.getItem('token')
            const headers = {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token,
                };

            this.axios
                .get('http://127.0.0.1:8000/api/v1/project/' + this.$route.params.projectId + '/members', {headers})
                .then(response => this.personSelections = response.data.data)
        },
        getSelectedPerson(){
            console.log("Component PersonSelector (computed) : Fetch selected person data")
            let token = localStorage.getItem('token')
            const headers = {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token,
                };

            // this.axios
            //     .get('http://127.0.0.1:8000/api/v1/project/' + this.$route.projectId, {headers})
            //     .then(response => this.selectedPerson = response.data.data)
        },
        selectPerson(data){
            let personId = data.id
            this.$router.push({ path: `/project/${this.$route.params.projectId}/` + this.$route.name + `/${personId}/` })
        }
    },
    computed: {
        
    }
}
</script>