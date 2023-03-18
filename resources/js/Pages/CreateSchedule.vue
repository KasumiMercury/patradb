<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";
import { ref, onMounted, onUnmounted } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
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

const TopNavStyle = ref({
    "background-color": "#2d2a2d",
    "border-bottom": "5px solid",
    "border-image-source":
        "linear-gradient(45deg, #B67B03 0%, #DAAF08 45%, #FEE9A0 70%, #DAAF08 85%, #B67B03 90% 100%)",
    "border-image-slice": 1,
});
const TopNavFontStyle = ref({
    color: "#fffafb",
});
</script>
<template>
    <AppLayout
        title="CreateSchedule"
        :TopNavStyle="TopNavStyle"
        :TopNavFontStyle="TopNavFontStyle"
    >
        <div>
            <div class="max-w-7xl mx-auto pt-4 px-4 sm:px-6 lg:px-8">
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
