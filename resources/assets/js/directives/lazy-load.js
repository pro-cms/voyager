import lozad from 'lozad';

export default {
    mounted(el, binding) {
        lozad(el).observe();
    }
};