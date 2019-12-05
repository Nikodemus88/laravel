<template>
  <b-modal
    id="add-project-modal"
    :hide-footer="hideFooter"
    size="lg"
    title="Add Project"
    ref="addProjectModal"
    @shown="getData"
  >
    <b-form @submit.prevent="onSubmit" @reset="onReset" v-if="show">
      <b-form-group id="title-group">
        <b-form-input id="title" v-model="form.title" required placeholder="Title"></b-form-input>
      </b-form-group>

      <b-form-group id="description-group">
        <b-form-textarea
          id="description"
          v-model="form.description"
          placeholder="Description"
          rows="3"
        ></b-form-textarea>
      </b-form-group>

      <div class="row">
        <div class="col-md-6">
          <b-form-group
            id="color-group"
            label="Calendar label color: "
            label-for="input-horizontal"
            label-cols-lg="6"
          >
            <b-form-input id="color" type="color" v-model="form.color"></b-form-input>
          </b-form-group>
          <b-form-group id="url-group">
            <b-form-input id="url" type="url" v-model="form.url" placeholder="Url"></b-form-input>
          </b-form-group>
          <button class="btn btn-primary" @click.prevent="getImage">Fetch Image</button>
        </div>

        <div class="col-md-6 mb-4">
          <div
            class="img-container"
            v-bind:style="{
                            backgroundImage: 'url(' + form.img_url + ')'
                        }"
          >
            <p class="loader">
              <i v-if="imgLoading" class="material-icons text-primary">hourglass_empty</i>
              <i
                v-if="form.img_url == '' && !imgLoading"
                class="material-icons text-secondary"
              >image</i>
            </p>
          </div>
        </div>
      </div>

      <!-- <div class="row" v-if="debug"> -->
      <div class="row">
        <div class="col">
          <pre>
            {{ form }}
          </pre>
        </div>
      </div>
      <div class="row">
        <div class="col text-right">
          <b-button type="reset" variant="danger">Reset fields</b-button>
          <b-button type="submit" variant="primary">Save</b-button>
        </div>
      </div>
    </b-form>
  </b-modal>
</template>

<script>
import { eventBus } from "../../app.js";
import FormMixin from "../../FormMixin";

export default {
  mixins: [FormMixin],
  components: {},

  data() {
    return {
      // Store route
      action: "/projects/store",
      name: "project",

      // Form object
      form: {
        user_id: this.user_id,
        title: "",
        description: "",
        img_url: "",
        url: "",
        color: "#3a87ad"
      },
      imgLoading: false,
      show: true,
      hideFooter: true,
      debug: false // Shows form input data
    };
  },
  props: {
    user_id: Number,
    project_id: Number,
    test: String
  },
  methods: {
    getData() {
      console.dir(this.project);
      if (this.project) {
        this.form.title = this.project.title;
        this.form.description = this.project.description;
        this.form.img_url = this.project.img_url;
        this.form.url = this.project.url;
        this.form.color = this.project.color;
      }
    },
    getImage() {
      this.imgLoading = true;
      var vm = this;

      axios.get("https://picsum.photos/600/400.webp").then(function(response) {
        vm.form.img_url = response.request.responseURL;
        vm.imgLoading = false;
      });
    },
    onSubmit() {
      this.submit(); // Go to Form Mixin submit
      this.show = false;
      this.$refs.addProjectModal.hide();
    },
    onReset() {
      Object.assign(this.$data, this.$options.data.apply(this)); // Reset data object to original state
      this.show = false;
      this.$nextTick(() => {
        this.show = true;
      });
    }
  },
  created() {
    eventBus.$on("editProject", project => {
      this.project = project;
      this.action = "/projects/update/" + project.id;
    });
  }
};
</script>

<style scoped>
.img-container {
  height: 200px;
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  overflow: hidden;
  border-radius: 5px;
  border: 1px solid #ced4da;
  background-color: #fdfdfd;
  text-align: center;
}

.img-container .loader {
  position: relative;
  top: 50%;
  transform: perspective(1px) translateY(-50%);
  font-size: 48px;
}

.img-container .loader i {
  font-size: 36px;
  line-height: 100px;
}
</style>
