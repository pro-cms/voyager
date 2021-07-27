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
    methods: {
        getElementWidth(el) {
            return new Promise((resolve) => {
                const original_position = el.style.position;
                const original_opacity = el.style.opacity;
                // Set position to absolute, hide it and set width to 0
                el.style.opacity = '0';
                el.style.transition = '';
                el.style.position = 'absolute';
                el.style.width = '0';

                requestAnimationFrame(() => {
                    el.style.width = '';
                    requestAnimationFrame(() => {
                        const width = el.scrollWidth;
                        el.style.width = '0';
                        
                        requestAnimationFrame(() => {
                            
                            el.style.position = original_position;
                            requestAnimationFrame(() => {
                                el.style.opacity = original_opacity;
                                el.style.overflow = 'hidden';

                                resolve(width);
                            });
                        });
                    });
                });
            });
        },
        getElementHeight(el) {
            return new Promise((resolve) => {
                const original_position = el.style.position;
                const original_opacity = el.style.opacity;
                // Set position to absolute, hide it and set height to 0
                el.style.opacity = '0';
                el.style.transition = '';
                el.style.position = 'absolute';
                el.style.height = '0';

                requestAnimationFrame(() => {
                    el.style.height = 'auto';
                    requestAnimationFrame(() => {
                        const height = el.scrollHeight;
                        el.style.height = '0';
                        requestAnimationFrame(() => {
                            
                            el.style.position = original_position;
                            requestAnimationFrame(() => {
                                el.style.opacity = original_opacity;
                                el.style.overflow = 'hidden';

                                resolve(height);
                            });
                        });
                    });
                });
            });
        },
    }
}