<template>
  <div class="px-3">
    <div v-if="!hasProjects">
      <p>
        <i>{{ zeroState }}</i>
      </p>
    </div>
    <div v-for="(project, i) in projects" :key="i">
      <!-- Featured Project -->
      <div class="row mb-4" v-if="i == 0 && project.img_url">
        <div
          class="col bg-img cover"
          v-bind:style="{ backgroundImage: 'linear-gradient(to right, rgba(28, 77, 117, 0.8), rgba(28, 77, 117, 0)), url(' + project.img_url + ')' }"
        >
          <div class="row pt-3">
            <div class="col"></div>
            <div class="col-auto p-0" v-if="project.url">
              <a :href="project.url" target="_BLANK">
                <i :id="'url-' + i" class="material-icons text-primary">language</i>
              </a>
              <b-tooltip :target="'url-' + i" triggers="hover" placement="left">{{ project.url }}</b-tooltip>
            </div>
            <div class="col-auto px-1">
              <button class="btn btn-xs btn-primary" @click="editProject(project)">
                <i :id="'edit-' + i" class="material-icons">edit</i>
              </button>
            </div>
            <div class="col-auto pl-0">
              <button class="btn btn-xs btn-danger" @click="deleteProject(project)">
                <i :id="'del-' + i" class="material-icons">delete</i>
              </button>
            </div>
          </div>
          <div class="px-4 pt-2 pb-4 text-white">
            <h2>
              <b>{{ project.title }}</b>
            </h2>
            <p>{{ project.description }}</p>

            <div class="row" v-if="project.events.length > 0">
              <div class="col" v-for="(event, i) in project.events" :key="i">
                <div class="row">
                  <div class="col">
                    <p>
                      <b>{{ event.title }}</b>
                      <b-badge
                        :id="'info-' + event.id"
                        pill
                        variant="primary"
                        class="eventinfo ml-2"
                      >i</b-badge>

                      <b-tooltip
                        :target="'info-' + event.id"
                        triggers="hover"
                        placement="top"
                        variant="light"
                      >{{ event.description }}</b-tooltip>
                      <br />
                      <small>
                        <span v-if="event.next">{{ moment(event.next.date).format('LL') }}</span>
                        <br />
                        <span
                          v-if="event.next && !event.allday"
                        >{{ moment(event.next.date).format('LT') }} - {{ moment(event.next.date).add(event.duration, 'minutes').format('LT') }}</span>
                        <span v-else>All day event</span>
                      </small>
                      <br />
                      <b-badge v-if="event.price > 0" pill variant="light">€ {{ event.price }}</b-badge>
                      <b-badge v-else pill variant="success">free</b-badge>
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Other Projects -->
      <div v-else class="row">
        <div class="col">
          <h5>
            <b>{{ project.title }}</b>
          </h5>
          <p>{{ project.description }}</p>
          <div v-if="project.events.length > 0">
            <div class="row" v-for="(event, i) in project.events" :key="i">
              <div class="col">
                <p>
                  <small>
                    <b>{{ event.title }}</b>
                    <b-badge
                      :id="'info-' + event.id"
                      pill
                      variant="primary"
                      class="eventinfo mx-1"
                    >i</b-badge>

                    <b-tooltip
                      :target="'info-' + event.id"
                      triggers="hover"
                      placement="top"
                      variant="secondary"
                    >{{ event.description }}</b-tooltip>
                      
                    <b-badge v-if="event.price > 0" pill variant="light">€ {{ event.price }}</b-badge>
                    <b-badge v-else pill variant="success">free</b-badge>

                    <br />
                    <span v-if="event.next">{{ moment(event.next.date).format('LL') }}</span> - 
                    <span
                      v-if="event.next && !event.allday"
                    >{{ moment(event.next.date).format('LT') }} / {{ moment(event.next.date).add(event.duration, 'minutes').format('LT') }}</span>
                    <span v-else>All day event</span>
                  </small>
                </p>
              </div>
            </div>
          </div>
          <div v-else>
            <small>
              <i>No events planned..</i>
            </small>
          </div>
        </div>
        <div
          class="col-md-5 bg-img"
          v-bind:style="{ backgroundImage: 'url(' + project.img_url + ')' }"
          v-if="project.img_url"
        >
          <div class="row pt-3">
            <div class="col"></div>
            <div class="col-auto p-0" v-if="project.url">
              <a :href="project.url" target="_BLANK">
                <i :id="'url-' + i" class="material-icons text-primary">language</i>
              </a>
              <b-tooltip :target="'url-' + i" triggers="hover" placement="left">{{ project.url }}</b-tooltip>
            </div>
            <div class="col-md-auto px-1">
              <button class="btn btn-xs btn-primary" @click="editProject(project)">
                <i :id="'edit-' + i" class="material-icons">edit</i>
              </button>
            </div>
            <div class="col-md-auto pl-0">
              <button class="btn btn-xs btn-danger" @click="deleteProject(project)">
                <i :id="'del-' + i" class="material-icons">delete</i>
              </button>
            </div>
          </div>
        </div>
        <div v-else class="col-auto">
          <div class="row">
            <div class="col-md-auto px-1">
              <button class="btn btn-xs btn-primary" @click="editProject(project)">
                <i :id="'edit-' + i" class="material-icons">edit</i>
              </button>
            </div>
            <div class="col-md-auto pl-0">
              <button class="btn btn-xs btn-danger" @click="deleteProject(project)">
                <i :id="'del-' + i" class="material-icons">delete</i>
              </button>
            </div>
          </div>
        </div>
      </div>

      <hr />
    </div>
  </div>
</template>

<script>
import { eventBus } from "../../app.js";
import moment from "moment";

Vue.prototype.moment = moment;

export default {
  data() {
    return {
      projects: [],
      projectTitle: "",
      deleteUrl: "projects/destroy/",
      projectListUrl: "projects/projectlist/",
      zeroState: "Loading.."
    };
  },
  computed: {
    hasProjects() {
      return this.projects.length > 0 ? true : false;
    }
  },
  methods: {
    getProjects() {
      var vm = this;
      axios.get(this.projectListUrl).then(function(response) {
        vm.projects = response.data;
        console.dir(response.data);
        vm.zeroState = "No projects added";
      });
    },
    editProject(project) {
      console.log("Edit this! ->" + project.title);
    },
    deleteProject(delProject) {
      this.$bvModal
        .msgBoxConfirm(
          "Are you sure you want to delete '" + delProject.title + "'?",
          {
            title: "Delete Project",
            size: "sm",
            buttonSize: "sm",
            okVariant: "danger",
            hideHeaderClose: false,
            centered: true
          }
        )
        .then(value => {
          if (value) {
            axios.post(this.deleteUrl + delProject.id);
            this.getProjects();
          }
        })
        .catch(err => {
          // An error occurred
        });
    }
  },
  computed: {
    hasProjects() {
      return this.projects.length > 0 ? true : false;
    }
  },
  mounted() {
    this.getProjects();
  },
  created() {
    eventBus.$on("projectAdded", () => {
      this.getProjects();
    });
  }
};
</script>

<style scoped>
.bg-img {
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  overflow: hidden;
  border-radius: 5px;
}

.bg-img.cover {
  min-height: 300px;
}

.eventinfo:hover {
  background-color: #212529;
  cursor: pointer;
}
</style>
