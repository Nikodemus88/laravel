<template>
  <div class="row">
    <div class="col">
      <div>{{alert}}</div>
    </div>
  </div>
</template>

<script>
import { eventBus } from "../app.js";
export default {
  props: {
    alert
  },
  created() {
    eventBus.$on("gotErrors", errors => {
      this.alert = errors;
    });
  }
};
</script>