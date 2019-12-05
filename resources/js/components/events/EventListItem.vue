<template>
  <div>
    <!-- <pre>{{ event }}</pre> -->

    <div class="row">
      <div class="col">
        <div>
          <b>{{ event.title }}</b>
          <span v-if="event.project">
            <i
              :id="'label-' + event.id"
              class="material-icons projectcolor"
              :style="{ color: event.project.color }"
            >bookmark</i>

            <b-tooltip
              :target="'label-' + event.id"
              triggers="hover"
              placement="top"
              variant="light"
            >{{ event.project.title }}</b-tooltip>
          </span>
        </div>
        <div>
          <small>{{ startDate }}</small>
          <span class="col-auto px-1" v-if="event.rrule">
            <small>
              <i :id="'tt-' + event.id" class="material-icons-outlined text-secondary">history</i>
              <b-tooltip
                :target="'tt-' + event.id"
                triggers="hover"
                placement="left"
              >{{ event.rrule }}</b-tooltip>
            </small>
          </span>
        </div>
      </div>
      <div class="col-auto pl-1" v-if="editable">
        <button class="btn btn-xs btn-danger" @click="deleteEvent(event)">
          <i :id="'del-' + event.id" class="material-icons">delete</i>
        </button>
      </div>
    </div>

    <div class="row">
      <div class="col">{{ event.description }}</div>
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
      deleteUrl: "events/destroy/"
    };
  },
  props: {
    event: Object,
    editable: {
      type: Boolean,
      default: false
    }
  },
  methods: {
    deleteEvent(delEvent) {
      console.log(delEvent.id);
      this.$bvModal
        .msgBoxConfirm(
          "Are you sure you want to delete '" + delEvent.title + "'?",
          {
            title: "Delete Event",
            size: "sm",
            buttonSize: "sm",
            okVariant: "danger",
            hideHeaderClose: false,
            centered: true
          }
        )
        .then(value => {
          if (value) {
            axios.post(this.deleteUrl + delEvent.id);
            eventBus.$emit("eventDeleted");
          }
        })
        .catch(err => {
          eventBus.$emit("gotErrors", err);
        });
    }
  },
  computed: {
    startDate() {
      return moment(this.event.start).format("LL");
    }
  }
};
</script>
