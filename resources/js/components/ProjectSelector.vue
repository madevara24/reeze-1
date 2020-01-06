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
    :value="selectedProjectName"
    ></v-select>
          
</template>

<script>
export default {
    computed: {
        projectSelections(){
            return this.$store.getters.projectSelections
        },
        selectedProjectName(){
            //eslint-disable-next-line
            //console.log(this.$store.getters.selectedProjectName)
            //return this.$store.getters.selectedProjectName
            return "project name"
        }
    },
    data() {
        return {
            selectedProject: null,
        }
    },
    methods: {
        selectProject(id){
            //eslint-disable-next-line
            console.log(id)
            this.$router.push({ name: 'board', params: { id } })
        }
    },
}
</script>