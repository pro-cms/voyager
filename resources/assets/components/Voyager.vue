<template>
    <div key="loader">
        <fade-transition :duration="500">
            <div class="loader" v-if="$store.pageLoading">
                <icon icon="helm" size="auto" class="block icon rotating-cw"></icon>
            </div>
        </fade-transition>
    </div>
    <!-- Sidebar -->
    <div class="flex flex-col w-0 flex-1 overflow-hidden" key="content">
        <main class="flex-1 relative z-0 overflow-y-auto pt-2 pb-6 outline-none">
            <span id="top"></span>
            <!-- Navbar -->
            <div class="mx-auto sm:px-3 md:px-4" id="top">
                <!-- Content -->
            </div>
        </main>
    </div>
    <notifications key="notifications"></notifications>
</template>

<script>
import { ref } from 'vue'

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
}
</script>