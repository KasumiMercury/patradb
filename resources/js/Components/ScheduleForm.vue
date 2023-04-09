<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import Checkbox from "@/Components/Checkbox.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import { ja } from "date-fns/locale";

const props = defineProps({
    initialForm: Object,
    initialStart: Object,
    initialEnd: Object,
});

const form = useForm({
    title: props.initialForm.title,
    startDate: props.initialForm.startDate,
    endDate: props.initialForm.endDate,
    eventCode: props.initialForm.eventCode,
    type: props.initialForm.type,
    handleName: props.initialForm.handleName,
    showName: props.initialForm.showName,
});
const startTime = ref(props.initialStart);
const endTime = ref(props.initialEnd);
const submit = () => {
    form.transform((data) => ({
        ...data,
    })).post(route("post.schedule"));
};
const endDateCalculation = () => {
    if (form.endDate <= form.startDate) {
        form.endDate = new Date(
            form.startDate.getFullYear(),
            form.startDate.getMonth(),
            form.startDate.getDate(),
            23,
            59,
            59
        );
    }
};
const changeStartTime = () => {
    form.startDate = new Date(
        form.startDate.getFullYear(),
        form.startDate.getMonth(),
        form.startDate.getDate(),
        startTime.value.hours,
        startTime.value.minutes,
        0
    );
};
const changeEndTime = () => {
    form.endDate = new Date(
        form.endDate.getFullYear(),
        form.endDate.getMonth(),
        form.endDate.getDate(),
        endTime.value.hours,
        endTime.value.minutes,
        59
    );
};
</script>

<template>
    <div class="mx-auto mt-10 max-w-xl">
        <form @submit.prevent="submit">
            <div>
                <InputLabel for="title" value="ScheduleTitle" />
                <TextInput
                    id="title"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.title"
                    required
                    autofocus
                />
            </div>
            <div class="mt-4">
                <InputLabel for="startDate" value="Start Date" />
                <VueDatePicker
                    locale="jp"
                    :format-locale="ja"
                    timezone="Asia/Tokyo"
                    cancel-text="キャンセル"
                    select-text="OK"
                    :enable-time-picker="false"
                    v-model="form.startDate"
                    id="startDate"
                    auto-apply
                    @update:model-value="endDateCalculation"
                ></VueDatePicker>
            </div>
            <div class="mt-4">
                <InputLabel for="startTime" value="Start Time" />
                <VueDatePicker
                    locale="jp"
                    :format-locale="ja"
                    timezone="Asia/Tokyo"
                    v-model="startTime"
                    id="startTime"
                    time-picker
                    auto-apply
                    @update:model-value="changeStartTime"
                ></VueDatePicker>
            </div>
            <div class="mt-4">
                <InputLabel for="endDate" value="End Date" />
                <VueDatePicker
                    locale="jp"
                    :format-locale="ja"
                    timezone="Asia/Tokyo"
                    cancel-text="キャンセル"
                    select-text="OK"
                    :enable-time-picker="false"
                    v-model="form.endDate"
                    id="endDate"
                    auto-apply
                ></VueDatePicker>
            </div>
            <div class="mt-4">
                <InputLabel for="endTime" value="End Time" />
                <VueDatePicker
                    locale="jp"
                    :format-locale="ja"
                    timezone="Asia/Tokyo"
                    v-model="endTime"
                    id="endTime"
                    time-picker
                    auto-apply
                    @update:model-value="changeEndTime"
                ></VueDatePicker>
            </div>
            <template v-if="$page.props.auth.user">
                <div class="mt-4" v-if="$page.props.auth.user.role">
                    <InputLabel for="eventCode" value="Event Code" />
                    <TextInput
                        id="eventCode"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.eventCode"
                        required
                        autofocus
                    />
                </div>
            </template>
            <div class="mt-16">
                <div class="mt-4" v-if="!$page.props.auth.user">
                    <InputLabel for="handleName" value="Handle Name" />
                    <TextInput
                        id="handleName"
                        v-model="form.handleName"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autofocus
                    />
                </div>

                <div class="mt-4 block">
                    <label class="flex items-center">
                        <Checkbox
                            v-model:checked="form.showName"
                            name="showName"
                        />
                        <span class="ml-2 text-sm text-gray-600"
                            >あなたのハンドルネームを登録者として表示しますか？</span
                        >
                    </label>
                </div>
            </div>
            <div class="mt-4 flex items-center justify-end">
                <PrimaryButton
                    class="ml-4"
                    v-if="form.title != '' && form.handleName != ''"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Send
                </PrimaryButton>
            </div>
        </form>
    </div>
</template>
