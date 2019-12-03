<template>
  <div class="my-2" :class="{'d-inline': inline}">
    <b-button variant="primary" v-b-modal.add-event-modal>
      <i class="material-icons float-left">event</i>&nbsp;
      <span>Add Event</span>
    </b-button>
  </div>
</template>

<script>
export default {
  props: {
    inline: Boolean
  }
};
</script>
