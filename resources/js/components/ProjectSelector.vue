<template>
    <v-select
    class="pt-2"
    dense
    solo
    prepend-icon="folder"
    @change="selectProject(selectedProject.id)"
    v-model="selectedProject"
    :items="projectSelections"
    item-text="name"
    item-value="id"
    label="Select Project"
    return-object
    single-line
    :value="selectedProject.name"
    ></v-select>
          
</template>

<script>
export default {
    created() {
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
                .then(response => this.projects = response.data.data)
        },
        getSelectedProject(){
            console.log("Component ProjectSelector (computed) : Fetch selected project data")
            let token = localStorage.getItem('token')
            const headers = {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token,
                };

            this.axios
                .get('http://127.0.0.1:8000/api/v1/project/' + localStorage.getItem("selectedProjectId"), {headers})
                .then(response => this.selectedProject = response.data.data)
        },
        selectProject(id){
            //eslint-disable-next-line
            console.log("Change project to " + id)
            this.$router.push({ name: 'board', params: { id } })
        }
    },
}
</script>