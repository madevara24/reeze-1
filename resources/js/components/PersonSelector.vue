<template>
    <v-select
    class="pt-2 mb-n2"
    dense
    solo
    prepend-icon="person"
    label="Select Person"
    return-object
    single-line
    v-model="this.selectedPerson"
    :items="this.personSelections"
    v-on:change="selectPerson"
    item-text="username"
    item-value="user_id"
    ></v-select>
</template>

<script>
export default {
    created() {
        console.log("Component PersonSelecter (created) : Route name : " + this.$route.name)
        this.getPerson()
        this.getSelectedPerson()
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
            console.log(this.$route.params.personId);
            if(this.$route.params.personId){
                console.log("Component PersonSelector (computed) : Fetch selected person data")
                let token = localStorage.getItem('token')
                const headers = {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + token,
                    };

                this.axios
                    .get('http://127.0.0.1:8000/api/v1/user/' + this.$route.params.personId, {headers})
                    .then(response => this.selectedPerson = response.data.data)
            }
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