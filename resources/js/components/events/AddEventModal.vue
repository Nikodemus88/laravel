<template>
  <b-modal
    id="add-event-modal"
    :hide-footer="hideFooter"
    size="lg"
    title="Add Event"
    ref="addEventModal"
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

      <b-form-group id="project-group" label-cols-lg="3" label="Price" label-for="input-horizontal">
        <b-form-input
          id="price"
          class="col-md-3"
          type="number"
          v-model="form.price"
          placeholder="Price (€)"
          step=".01"
        ></b-form-input>
      </b-form-group>

      <b-form-group
        id="price-group"
        label-cols-lg="3"
        label="Linked to project:"
        label-for="input-horizontal"
      >
        <b-form-select @click="getProjects" v-model="form.project_id" :options="projectSelector"></b-form-select>
      </b-form-group>

      <div class="row">
        <div class="col-md-5">
          <flat-pickr
            v-model="form.start"
            placeholder="Start date/time"
            :config="config.dateTime"
            class="form-control"
            :required="true"
          ></flat-pickr>
        </div>
        <div class="col-auto pt-2">
          <b-form-checkbox id="allDay" v-model="form.allday" name="allDay">All day</b-form-checkbox>
        </div>
        <div class="col-md-5">
          <flat-pickr
            v-if="!form.allday"
            v-model="form.duration"
            placeholder="Duration"
            :config="config.time"
            class="form-control"
          ></flat-pickr>
        </div>
      </div>
      <br />

      <b-tabs content-class="mt-3" fill>
        <b-tab
          title="Once"
          title-item-class="btn-outline tab-empty"
          @click="form.rrule.freq = 'ONCE'"
          active
        ></b-tab>
        <b-tab title="Daily" @click="form.rrule.freq = 'DAILY'">
          <div class="m-2">
            <b-form-group>
              <div class="row pb-2">
                <div class="col">
                  <b-form-radio
                    v-model="form.rrule.daily.freq"
                    name="dailyfreq"
                    value="1"
                    :required="isDaily ? true : false"
                  >Dagelijks</b-form-radio>
                </div>
              </div>

              <div class="row pb-2">
                <div class="col-auto">
                  <b-form-radio v-model="form.rrule.daily.freq" name="dailyfreq" value="2">Every</b-form-radio>
                </div>
                <div class="col-2 p-0">
                  <b-form-input
                    v-model="form.rrule.daily.interval"
                    type="number"
                    min="1"
                    name="dailyint"
                    size="sm"
                    class="cc-inline"
                    placeholder="(number)"
                    :required="isDailyInterval ? true : false"
                  ></b-form-input>
                </div>
                <div class="col">days</div>
              </div>
            </b-form-group>
          </div>
        </b-tab>
        <b-tab title="Weekly" @click="form.rrule.freq = 'WEEKLY'">
          <div class="m-2">
            <div class="row pb-2">
              <div class="col-auto">Repeats every</div>
              <div class="col-2 p-0">
                <b-form-input
                  v-model="form.rrule.weekly.interval"
                  type="number"
                  min="1"
                  name="weeklyfreq"
                  size="sm"
                  class="cc-inline"
                  placeholder="(aantal)"
                  :required="isWeekly ? true : false"
                ></b-form-input>
              </div>
              <div class="col">week(s) on:</div>
            </div>
            <b-form-group>
              <b-form-checkbox-group
                v-model="form.rrule.weekly.bywdaylist"
                :options="dates.days"
                name="bywdaylist"
                stacked
              ></b-form-checkbox-group>
            </b-form-group>
          </div>
        </b-tab>
        <b-tab title="Monthly" @click="form.rrule.freq = 'MONTHLY'">
          <div class="m-2">
            <b-form-group>
              <div class="row pb-2">
                <div class="col-auto">
                  <b-form-radio
                    v-model="form.rrule.monthly.freq"
                    name="monthlyfreq"
                    value="datenum"
                    :required="isMonthly ? true : false"
                  >Day</b-form-radio>
                </div>
                <div class="col-2 p-0">
                  <b-form-input
                    v-model="
                                            form.rrule.monthly.datenum.daynum
                                        "
                    type="number"
                    min="1"
                    name="monthlydatenumfreq"
                    size="sm"
                    class="cc-inline"
                    placeholder="(number)"
                    :required="isMonthlyDatenum ? true : false"
                  ></b-form-input>
                </div>
                <div class="col-auto">of every</div>
                <div class="col-2 p-0">
                  <b-form-input
                    v-model="
                                            form.rrule.monthly.datenum.interval
                                        "
                    type="number"
                    min="1"
                    class="cc-inline"
                    name="monthlydatenuminterval"
                    size="sm"
                    placeholder="(aantal)"
                    :required="isMonthlyDatenum ? true : false"
                  ></b-form-input>
                </div>
                <div class="col-auto">month(s)</div>
              </div>

              <div class="row pb-2">
                <div class="col-auto">
                  <b-form-radio
                    v-model="form.rrule.monthly.freq"
                    name="monthlyfreq"
                    value="datename"
                  >The</b-form-radio>
                </div>
                <div class="col-auto p-0">
                  <b-form-select
                    v-model="
                                            form.rrule.monthly.datename.nth
                                        "
                    :options="dates.weeks"
                    size="sm"
                    class="cc-inline"
                    :required="isMonthlyDatename ? true : false"
                  ></b-form-select>
                </div>
                <div class="col-auto pr-0">
                  <b-form-select
                    v-model="
                                            form.rrule.monthly.datename.day
                                        "
                    :options="dates.days"
                    class="cc-inline"
                    size="sm"
                    :required="isMonthlyDatename ? true : false"
                  ></b-form-select>
                </div>
                <div class="col-auto">of every</div>
                <div class="col-2 p-0">
                  <b-form-input
                    v-model="
                                            form.rrule.monthly.datename.interval
                                        "
                    type="number"
                    min="1"
                    name="monthlydatenameinterval"
                    size="sm"
                    class="cc-inline"
                    placeholder="(aantal)"
                    :required="isMonthlyDatename ? true : false"
                  ></b-form-input>
                </div>
                <div class="col-auto">month(s)</div>
              </div>
            </b-form-group>
          </div>
        </b-tab>
        <b-tab title="Jaarlijks" @click="form.rrule.freq = 'YEARLY'">
          <div class="m-2">
            <div class="row pb-2">
              <div class="col-auto">Repeats every</div>
              <div class="col-2 p-0">
                <b-form-input
                  v-model="form.rrule.yearly.interval"
                  name="yearlyinterval"
                  size="sm"
                  type="number"
                  min="1"
                  class="cc-inline"
                  placeholder="(aantal)"
                  :required="isYearly ? true : false"
                ></b-form-input>
              </div>
              <div class="col-auto">year(s) on:</div>
            </div>
            <b-form-group>
              <div class="row pb-2">
                <div class="col-auto">
                  <b-form-radio
                    v-model="form.rrule.yearly.freq"
                    name="yearlyfreq"
                    value="date"
                    :required="isYearly ? true : false"
                  >Day</b-form-radio>
                </div>
                <div class="col-2 p-0">
                  <b-form-input
                    v-model="form.rrule.yearly.date.daynum"
                    type="number"
                    min="1"
                    name="yearlydaydaynum"
                    size="sm"
                    class="cc-inline"
                    placeholder="(number)"
                    :required="isYearlyDate ? true : false"
                  ></b-form-input>
                </div>
                <div class="col-auto">
                  <b-form-select
                    v-model="form.rrule.yearly.date.month"
                    :options="dates.months"
                    size="sm"
                    class="cc-inline"
                    :required="isYearlyDate ? true : false"
                  ></b-form-select>
                </div>
              </div>
              <div class="row pb-2">
                <div class="col-auto">
                  <b-form-radio
                    v-model="form.rrule.yearly.freq"
                    name="yearlyfreq"
                    value="nthdate"
                  >The</b-form-radio>
                </div>
                <div class="col pl-0">
                  <b-form-select
                    v-model="form.rrule.yearly.nthdate.nth"
                    :options="dates.weeks"
                    size="sm"
                    class="cc-inline"
                    :required="isYearlyNthdate ? true : false"
                  ></b-form-select>
                </div>
                <div class="col p-0">
                  <b-form-select
                    v-model="form.rrule.yearly.nthdate.day"
                    :options="dates.days"
                    class="cc-inline"
                    size="sm"
                    :required="isYearlyNthdate ? true : false"
                  ></b-form-select>
                </div>
                <div class="col-auto">of</div>
                <div class="col pl-0">
                  <b-form-select
                    v-model="
                                            form.rrule.yearly.nthdate.month
                                        "
                    :options="dates.months"
                    class="cc-inline"
                    size="sm"
                    :required="isYearlyNthdate ? true : false"
                  ></b-form-select>
                </div>
              </div>
            </b-form-group>
          </div>
        </b-tab>
      </b-tabs>
      <div class="m-2" v-if="form.rrule.freq != 'ONCE'">
        <hr />
        <div class="row pb-2">
          <div class="col">
            <b-form-radio
              v-model="form.rrule.end.endtype"
              name="endtype"
              value="none"
              :required="!isOnce ? true : false"
            >Infinite</b-form-radio>
          </div>
        </div>
        <div class="row pb-2">
          <div class="col-md-2">
            <b-form-radio
              v-model="form.rrule.end.endtype"
              name="endtype"
              value="occurrences"
            >Ends after</b-form-radio>
          </div>
          <div class="col-2 p-0">
            <b-form-input
              v-model="form.rrule.end.occurrences"
              type="number"
              min="1"
              name="endoccurrences"
              class="cc-inline"
              size="sm"
              placeholder="(aantal)"
              :required="endtypeOccurrences ? true : false"
            ></b-form-input>
          </div>
          <div class="col-auto">times</div>
        </div>
        <div class="row pb-2">
          <div class="col-md-2">
            <b-form-radio v-model="form.rrule.end.endtype" name="endtype" value="date">Ends on</b-form-radio>
          </div>
          <div class="col-auto p-0">
            <flat-pickr
              v-model="form.rrule.end.enddate"
              :config="config.date"
              class="form-control cc-inline"
              :required="endtypeDate ? true : false"
            ></flat-pickr>
          </div>
        </div>
      </div>
      <div class="row" v-if="debug">
        <div class="col">
          <pre>
            {{ form }}
          </pre>
        </div>
      </div>
      <div class="row">
        <div class="col text-right">
          <b-button type="reset" variant="danger">Reset fields</b-button>
          <b-button type="submit" variant="primary">Add Event</b-button>
        </div>
      </div>
    </b-form>
  </b-modal>
</template>

<script>
import { eventBus } from "../../app.js";
import FormMixin from "../../FormMixin";
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.css";

export default {
  mixins: [FormMixin],
  components: {
    flatPickr
  },

  data() {
    return {
      // Store route
      action: "/events/store",
      name: "event",
      projectListUrl: "/projects/projectselector",

      // Form object
      form: {
        project_id: null,
        user_id: this.user_id,
        title: "",
        description: "",
        start: "",
        duration: "01:00",
        allday: false,
        price: null,
        rrule: {
          freq: "ONCE",
          daily: {
            freq: null,
            interval: null
          },
          weekly: {
            bywdaylist: [],
            interval: null
          },
          monthly: {
            freq: null,
            datenum: {
              daynum: null,
              interval: null
            },
            datename: {
              nth: null,
              day: null,
              interval: null
            }
          },
          yearly: {
            freq: null,
            date: {
              daynum: null,
              month: null
            },
            nthdate: {
              nth: null,
              weekday: null,
              month: null
            }
          },
          end: {
            endtype: null,
            enddate: null,
            occurrences: null
          }
        }
      },
      projectSelector: [],

      // Select box values
      dates: {
        days: [
          { text: "Monday", value: "MO" },
          { text: "Tuesday", value: "TU" },
          { text: "Wednesday", value: "WE" },
          { text: "Thursday", value: "TH" },
          { text: "Friday", value: "FR" },
          { text: "Saturday", value: "SA" },
          { text: "Sunday", value: "SU" }
        ],
        weeks: [
          { text: "First", value: 1 },
          { text: "Second", value: 2 },
          { text: "Third", value: 3 },
          { text: "Fourth", value: 4 },
          { text: "Last", value: -1 }
        ],
        months: [
          { text: "Januari", value: 1 },
          { text: "February", value: 2 },
          { text: "March", value: 3 },
          { text: "April", value: 4 },
          { text: "May", value: 5 },
          { text: "June", value: 6 },
          { text: "July", value: 7 },
          { text: "August", value: 8 },
          { text: "September", value: 9 },
          { text: "October", value: 10 },
          { text: "November", value: 11 },
          { text: "December", value: 12 }
        ]
      },

      // Flatpickr configs
      config: {
        date: {},
        dateTime: {
          enableTime: true,
          time_24hr: true,
          minDate: "today"
        },
        time: {
          enableTime: true,
          noCalendar: true,
          dateFormat: "H:i",
          time_24hr: true,
          defaultDate: "01:00"
        }
      },
      show: true,
      hideFooter: true,
      debug: false // Shows form input data
    };
  },
  props: {
    user_id: Number
  },
  methods: {
    getProjects() {
      var vm = this;
      this.projectSelector = [
        {
          value: null,
          text: "- No project selected -"
        }
      ];
      axios.get(this.projectListUrl).then(function(response) {
        response.data.forEach(project => {
          vm.projectSelector.push(project);
        });
      });
    },
    onSubmit() {
      this.submit(); // Go to Form Mixin submit
      this.show = false;
      this.$refs.addEventModal.hide();
    },
    onReset() {
      Object.assign(this.$data, this.$options.data.apply(this)); // Reset data object to original state
      this.show = false;
      this.$nextTick(() => {
        this.show = true;
      });
    }
  },
  computed: {
    // Conditional form validation checks (mainly RRule)
    isOnce() {
      return this.form.rrule.freq == "ONCE" ? true : false;
    },
    isDaily() {
      return this.form.rrule.freq == "DAILY" ? true : false;
    },
    isDailyInterval() {
      return this.isDaily && this.form.rrule.daily.freq == "2" ? true : false;
    },
    isWeekly() {
      return this.form.rrule.freq == "WEEKLY" ? true : false;
    },
    isMonthly() {
      return this.form.rrule.freq == "MONTHLY" ? true : false;
    },
    isMonthlyDatenum() {
      return this.isMonthly && this.form.rrule.monthly.freq == "datenum"
        ? true
        : false;
    },
    isMonthlyDatename() {
      return this.isMonthly && this.form.rrule.monthly.freq == "datename"
        ? true
        : false;
    },
    isYearly() {
      return this.form.rrule.freq == "YEARLY" ? true : false;
    },
    isYearlyDate() {
      return this.isYearly && this.form.rrule.yearly.freq == "date"
        ? true
        : false;
    },
    isYearlyNthdate() {
      return this.isYearly && this.form.rrule.yearly.freq == "nthdate"
        ? true
        : false;
    },
    endtypeOccurrences() {
      return !this.isOnce && this.form.rrule.end.endtype == "occurrences"
        ? true
        : false;
    },
    endtypeDate() {
      return !this.isOnce && this.form.rrule.end.endtype == "date"
        ? true
        : false;
    }
  },
  mounted() {
    this.getProjects();
  }
};
</script>
