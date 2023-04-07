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
    <div class="border_gold border-b-12 bg-[#313231]">
        <div
            class="w-full cursor-pointer select-none py-4 pl-8 text-white"
            @click="isCalendarOpen = !isCalendarOpen"
        >
            <h1 class="text-xl">All schedule</h1>
        </div>
        <transition>
            <div v-if="isCalendarOpen" class="mx-0 w-full rounded-b-xl px-0">
                <div>
                    <div class="text-sm">
                        <div
                            v-if="Object.keys(monthData).length !== 0"
                            class="grid w-full grid-cols-7 gap-0 py-4"
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
                            class="w-full pb-4 text-center text-xl text-white"
                        >
                            <p>NONE DATA</p>
                        </div>
                    </div>
                </div>
                <div
                    v-if="Object.keys(otherData).length !== 0"
                    class="mt-4 border-t-2"
                >
                    <h2 class="pt-4 pb-2 text-center text-lg">
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
