<template>
  <div class="row">
    <div class="col">
      <div :class="'alert alert-' + alertClass" role="alert">{{ alertContent }}</div>
      <div></div>
    </div>
  </div>
</template>

<script>
import { eventBus } from "../app.js";
export default {
  data() {
    return {
      alertClass: "",
      alertContent: null
    };
  },
  methods: {
    capitalizeFirstLetter(string) {
      return string.charAt(0).toUpperCase() + string.slice(1);
    }
  },
  props: {},
  created() {
    eventBus.$on("notify", msg => {
      this.alertClass = msg.class;
      this.alertContent = this.capitalizeFirstLetter(msg.content);
    });
  }
};
</script>