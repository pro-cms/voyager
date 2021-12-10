export default {
    props: {
        group: {
            type: Boolean,
            default: false,
        },
        duration: {
            type: [Number, Object],
            default: 300,
        },
    },
    computed: {
        enterDuration() {
            if (typeof this.duration === 'object' && this.duration.constructor === Object) {
                return this.duration.enter;
            }

            return this.duration;
        },
        leaveDuration() {
            if (typeof this.duration === 'object' && this.duration.constructor === Object) {
                return this.duration.leave;
            }

            return this.duration;
        }
    },
}