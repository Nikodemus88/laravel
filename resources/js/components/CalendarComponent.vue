<template>
  <div>
    <full-calendar
      :events="events"
      @view-render="viewRender"
      ref="calendar"
      theme-system="bootstrap"
    ></full-calendar>
  </div>
</template>

<script>
import { eventBus } from "../app.js";
import FullCalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import RRule from "rrule";
import moment from "moment";

Vue.prototype.moment = moment;

export default {
  data() {
    return {
      eventUrl: "events/calendarevents",
      events: {},
      viewStart: "",
      viewEnd: ""
    };
  },
  methods: {
    viewRender(view, element) {
      // Set start date and end date of calendar view
      this.viewStart = moment(view.start).format();
      this.viewEnd = moment(view.end).format();

      // Get matching events
      this.getEvents();
    },

    // Get Events by Axios-call
    async getEvents() {
      await axios
        .get(this.eventUrl, {
          params: {
            from: this.viewStart,
            till: this.viewEnd
          }
        })
        .then(response => {
          this.events = response.data;
        })
        .catch(errors => {
          console.log(errors);
        });
    }
  },
  mounted() {
    eventBus.$on("eventAdded", () => {
      this.getEvents();
    });
  }
};
</script>
