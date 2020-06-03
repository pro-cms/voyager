import { ref } from 'vue';

export default {
    setup() {
        const count = ref(0)
        const inc = () => {
            count.value++
        }
        return {
            count,
            inc
        }
    }    
};