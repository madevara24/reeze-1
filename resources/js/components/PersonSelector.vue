<template>
    <v-select
    class="pt-2"
    dense
    solo
    prepend-icon="folder"
    label="Select Person"
    return-object
    single-line
    v-model="this.selectedProject"
    :items="this.projectSelections"
    v-on:change="selectProject"
    item-text="name"
    item-value="id"
    ></v-select>
</template>

<script>
export default {
    created() {
        console.log("Component ProjectSelecter (created) : Route name : " + this.$route.name)
        this.getProjects()
        this.getSelectedProject();
    },
    data() {
        return {
            projectSelections: null,
            selectedProject: null,
        }
    },
    methods: {
        getProjects(){
            let token = localStorage.getItem('token')
            const headers = {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token,
                };

            this.axios
                .get('http://127.0.0.1:8000/api/v1/project', {headers})
                .then(response => this.projectSelections = response.data.data)
        },
        getSelectedProject(){
            console.log("Component ProjectSelector (computed) : Fetch selected project data")
            let token = localStorage.getItem('token')
            const headers = {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token,
                };

            this.axios
                .get('http://127.0.0.1:8000/api/v1/project/' + this.$route.params.id, {headers})
                .then(response => this.selectedProject = response.data.data)
        },
        selectProject(data){
            let projectId = data.id
            this.$router.push({ path: `/project/${projectId}/`+this.$route.name })
        }
    },
}
</script>