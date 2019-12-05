/* 
    This needs a 'name' (for callback), 'action' (route), and 'form' (input)
*/

import {
    eventBus
} from "./app";

export default {
    data() {
        return {
            errors: {},
            success: false,
            loaded: true,
            output: {}
        }
    },

    methods: {

        submit() {
            if (this.loaded) {
                this.loaded = false;
                this.success = false;
                this.errors = {};
                axios.post(this.action, this.form).then(response => {
                    Object.assign(this.$data, this.$options.data.apply(this)); // Reset data object to original state
                    this.loaded = true;
                    this.success = true;
                    eventBus.$emit(this.name + 'Added')
                    eventBus.$emit('notify', {
                        'class': 'success',
                        'content': this.name + ' saved!'
                    });
                }).catch(error => {
                    this.loaded = true;
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors || {};
                        eventBus.$emit('notify', {
                            'class': 'danger',
                            'content': this.errors
                        });
                    }
                });
            }
        },
    },
}
