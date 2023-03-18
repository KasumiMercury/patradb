<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
const isCalendarOpen = ref(false);
const dayOfWeek = ref([]);
dayOfWeek.value = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
defineProps({
    monthData: Object,
    otherData: Object,
});
</script>

<style scoped>
.v-enter-active,
.v-leave-active {
    transition: opacity 0.2s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}
</style>
<template>
    <div class="border-b-12 border_gold bg-[#313231]">
        <div
            class="w-full py-4 pl-8 select-none cursor-pointer text-white"
            @click="isCalendarOpen = !isCalendarOpen"
        >
            <h1 class="text-xl">All schedule</h1>
        </div>
        <transition>
            <div v-if="isCalendarOpen" class="w-full px-0 mx-0 rounded-b-xl">
                <div>
                    <div class="text-sm">
                        <div
                            v-if="Object.keys(monthData).length !== 0"
                            class="w-full py-4 grid grid-cols-7 gap-0"
                        >
                            <div
                                v-for="(dayOfWeek, index) in dayOfWeek"
                                :key="index"
                                class="text-center"
                            >
                                <p>{{ dayOfWeek }}</p>
                            </div>
                        </div>
                        <div
                            v-else
                            class="w-full text-xl text-white text-center pb-4"
                        >
                            <p>NONE DATA</p>
                        </div>
                    </div>
                </div>
                <div
                    v-if="Object.keys(otherData).length !== 0"
                    class="border-t-2 mt-4"
                >
                    <h2 class="text-center text-lg pt-4 pb-2">
                        Other schedule
                    </h2>
                    <div class="text-sm">
                        <div class="w-full py-4"></div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>
