/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");
require("flatpickr/dist/flatpickr.css")

window.Vue = require("vue");

import FullCalendar from "vue-full-calendar"; //Import Full-calendar
Vue.use(FullCalendar);
import "fullcalendar/dist/fullcalendar.css";

import BootstrapVue from "bootstrap-vue";
Vue.use(BootstrapVue);

Vue.component(
    "notifications-bar",
    require("./components/Notifications.vue").default
);

Vue.component(
    "calendar-component",
    require("./components/CalendarComponent.vue").default
);

Vue.component(
    "add-event-button",
    require("./components/events/AddEventButton.vue").default
);

Vue.component(
    "add-event-modal",
    require("./components/events/AddEventModal.vue").default
);

Vue.component(
    "event-list",
    require("./components/events/EventList.vue").default
);

Vue.component(
    "event-list-item",
    require("./components/events/EventListItem.vue").default
);

Vue.component(
    "add-project-button",
    require("./components/projects/AddProjectButton.vue").default
);

Vue.component(
    "add-project-modal",
    require("./components/projects/AddProjectModal.vue").default
);

Vue.component(
    "project-list",
    require("./components/projects/ProjectList.vue").default
);



/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
export const eventBus = new Vue();

const app = new Vue({
    el: "#app"
});
