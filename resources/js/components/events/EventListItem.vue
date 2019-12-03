<template>
    <div>
        <pre>{{ event }}</pre>

        <div class="row">
            <div class="col-md-3">
                <small>{{ startDate }}</small>
            </div>
            <div class="col-md-9 pl-0">
                <b>{{ event.title }}</b>
            </div>
        </div>

        <div class="row">
            <div class="col">{{ event.description }}</div>
            <div class="col-auto px-1" v-if="event.rrule">
                <small>
                    <i
                        :id="'tt-' + i"
                        class="material-icons-outlined text-secondary"
                        >history</i
                    >
                    <b-tooltip
                        :target="'tt-' + i"
                        triggers="hover"
                        placement="left"
                        >{{ event.rrule }}</b-tooltip
                    >
                </small>
            </div>
            <div class="col-md-auto pl-1">
                <button
                    class="btn btn-xs btn-danger"
                    @click="deleteEvent(event)"
                >
                    <i :id="'del-' + i" class="material-icons">delete</i>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import moment from "moment";
Vue.prototype.moment = moment;

export default {
    props: {
        event: Object
    },
    methods: {},
    computed: {
        startDate() {
            return moment(this.event.start).format("LL");
        }
    }
};
</script>
