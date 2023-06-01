<script setup>
import { Head, Link, usePage, useForm } from "@inertiajs/vue3";
import { ref, onMounted, onUnmounted } from "vue";
import Checkbox from "@/Components/Checkbox.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";

const today = new Date();

const form = useForm({
    title: "",
    scheduled_at: new Date(
        today.getFullYear(),
        today.getMonth(),
        today.getDate(),
        0,
        0,
        0
    ),
    nostream: false,
});
const scheduleTime = ref({
    hours: 0,
    minutes: 0,
});

const changeScheduleTime = () => {
    form.scheduled_at = new Date(
        form.scheduled_at.getFullYear(),
        form.scheduled_at.getMonth(),
        form.scheduled_at.getDate(),
        scheduleTime.value.hours,
        scheduleTime.value.minutes,
        0
    );
};

const mounted = ref(false);

onMounted(() => (mounted.value = true));
const submit = () => {
    form.transform((data) => ({
        ...data,
    })).post(route("post.stream"));
};
</script>
<script>
import AppLayout from "../Layouts/AppLayout.vue";

export default {
    layout: AppLayout,
};
</script>
<template>
    <div>
        <Head>
            <title>CreateStream</title>
        </Head>
        <Teleport to='[data-slot="header"]' v-if="mounted">
            <p class="text-xs font-semibold text-gray-800">CreateStream</p>
        </Teleport>
        <div>
            <div class="mx-auto mt-10 max-w-xl">
                <form @submit.prevent="submit">
                    <div>
                        <InputLabel for="title" value="ScheduleTitle" />
                        <TextInput
                            id="title"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.title"
                            autofocus
                        />
                    </div>
                    <div class="mt-4">
                        <InputLabel for="scheduleDate" value="Schedule Date" />
                        <VueDatePicker
                            locale="jp"
                            timezone="Asia/Tokyo"
                            cancel-text="キャンセル"
                            select-text="OK"
                            :enable-time-picker="false"
                            v-model="form.scheduled_at"
                            id="scheduleDate"
                            auto-apply
                        ></VueDatePicker>
                    </div>
                    <div class="mt-4">
                        <InputLabel for="scheduleTime" value="Schedule Time" />
                        <VueDatePicker
                            locale="jp"
                            timezone="Asia/Tokyo"
                            v-model="scheduleTime"
                            id="scheduleTime"
                            time-picker
                            auto-apply
                            @update:model-value="changeScheduleTime"
                        ></VueDatePicker>
                    </div>
                    <div class="mt-4">
                        <InputLabel for="nostream" value="No Stream" />
                        <Checkbox
                            id="nostream"
                            v-model:checked="form.nostream"
                            name="nostream"
                        />
                    </div>
                    <div class="mt-4 flex items-center justify-end">
                        <PrimaryButton
                            class="ml-4"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Send
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
