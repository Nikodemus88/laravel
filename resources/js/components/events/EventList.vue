<template>
  <div>
    <hr class />
    <div v-if="!hasEvents">
      <p>
        <i>{{ zeroState }}</i>
      </p>
    </div>
    <div v-for="(event, i) in events" :key="i">
      <event-list-item :event="event" :editable="editable"></event-list-item>

      <hr class="my-4" />
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
      events: [],
      eventTitle: "",
      deleteUrl: "events/destroy/",
      eventListUrl: "events/eventlist/",
      zeroState: "Loading.."
    };
  },
  props: {
    editable: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    hasEvents() {
      return this.events.length > 0 ? true : false;
    }
  },
  methods: {
    getEvents() {
      axios
        .get(this.eventListUrl)
        .then(
          response => (this.events = response.data),
          (this.zeroState = "No projects added")
        );
    }
  },
  computed: {
    hasEvents() {
      return this.events.length > 0 ? true : false;
    }
  },
  mounted() {
    this.getEvents();
  },
  created() {
    eventBus.$on("eventAdded", () => {
      this.getEvents();
    });

    eventBus.$on("eventDeleted", () => {
      this.getEvents();
    });
  }
};
</script>
