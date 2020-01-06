<template>
  <v-toolbar flat dark dense class="mx-0 px-0">
      <v-toolbar-title class="subtitle-2">{{ headerData.title }}</v-toolbar-title>

      <v-spacer></v-spacer>
  </v-toolbar>
</template>

<script>

export default {
    props:{
        headerData: null
    },
}
</script>