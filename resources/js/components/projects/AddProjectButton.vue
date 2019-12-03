<template>
  <div class="my-2" :class="{'d-inline': inline}">
    <b-button variant="primary" v-b-modal.add-project-modal>
      <i class="material-icons float-left">outlined_flag</i>&nbsp;
      <span>Add Project</span>
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
