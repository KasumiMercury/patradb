<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";
import { ref, onMounted, onUnmounted } from "vue";
import ScheduleForm from "../Components/ScheduleForm.vue";

const today = new Date();

const initialForm = {
    title: "",
    startDate: new Date(
        today.getFullYear(),
        today.getMonth(),
        today.getDate(),
        0,
        0,
        0
    ),
    endDate: new Date(
        today.getFullYear(),
        today.getMonth(),
        today.getDate(),
        23,
        59,
        59
    ),
    eventCode: Math.random().toString(32).substring(2),
    type: null,
    handleName: "",
    showName: true,
};
if (usePage().props.auth.user !== null) {
    initialForm.handleName = usePage().props.auth.user.name;
    if (usePage().props.auth.user.role == "admin") {
        initialForm.showName = false;
    }
}
const startTime = {
    hours: 0,
    minutes: 0,
};
const endTime = {
    hours: 23,
    minutes: 59,
};

const mounted = ref(false);

onMounted(() => mounted.value = true);
</script>
<script>
import AppLayout from "../Layouts/AppLayout.vue";

export default {
    layout: AppLayout,
};
</script>
<template>
    <AppLayout>
        <Head>
            <title>CreateSchedule</title>
        </Head>
        <Teleport to='[data-slot="header"]' v-if="mounted">
            <p class="text-xs font-semibold text-gray-800">CreateSchedule</p>
        </Teleport>
        <div>
            <div class="mx-auto max-w-7xl px-4 pt-4 sm:px-6 lg:px-8">
                <div class="">
                    <ScheduleForm
                        :initialForm="initialForm"
                        :initialStart="startTime"
                        :initialEnd="endTime"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
