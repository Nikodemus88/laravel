<template>
    <div>
        <hr class />
        <div v-if="!hasEvents">
            <p>
                <i>{{ zeroState }}</i>
            </p>
        </div>
        <div v-for="(event, i) in events" :key="i">
            <event-list-item :event="event"></event-list-item>

            <hr class="my-1" />
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
        },
        deleteEvent(delEvent) {
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
                        this.getEvents();
                    }
                })
                .catch(err => {
                    // An error occurred
                });
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
    }
};
</script>
