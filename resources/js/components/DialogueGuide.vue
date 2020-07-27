<template>
  <v-dialog v-model="value" persistent max-width="600px">
    <v-card>
      <v-card-title>
        <h2 class="mt-5 mb-5">How to Use?</h2>
      </v-card-title>
      <v-card-text>
          <p>1. In this page (Board Page) you can create task and the task will be shown as a card.</p>
          <p>2. Task card should have detailed information about the task you want to do.<p>
          <p>3. Each task card has it own state.</p>
          <p>Created means the card is just created at the time and must be in icebox</p>
          <p>Planned means the card is planned to be done by someone own the card</p>
          <p>Started means someone is already working on the card</p>
          <p>Finished means someone has finished his task on the card</p>
          <p>Accepted means the card is accepted to be released</p>
          <p>Rejected means the card is rejected and should be revised</p>
          <p>4. Each task card can be attached to github by filling the branch field and attach it.</p>
          <p>5. To access merge feature, open <strong>Project Merge</strong> page.</p>
          <p>6. Tasks shown on <strong>Project Merge</strong> page are only <strong>Accepted</strong> card.</p>
          <h3>Before you start merge, make sure you and your team within the project aren't working at the same branch or at the same file. 
              Just keep it different, to make sure there are no problems on merging.
          </h3>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" text @click="close">Close</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
/*eslint-disable */
export default {
  props: {
    value: Boolean,
  },
  data() {
    return {
      valid: true,
    };
  },
  methods: {
    close() {
      this.$emit("input", false);
      localStorage.setItem("user_dialog", false)
    },
  },
};
</script>
