<template>
    <v-select
    class="pt-2"
    dense
    solo
    label="Select Project"
    return-object
    single-line
    :value="this.selectedProject"
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
                .get(`${this.appUrl}/api/v1/project/`, {headers})
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
                .get(`${this.appUrl}/api/v1/project/` + this.$route.params.projectId, {headers})
                .then(response => this.selectedProject = response.data.data)
        },
        selectProject(data){
            let projectId = data.id
            this.$router.push({ path: `/project/${projectId}/`+this.$route.name })
        }
    },
}
</script>
