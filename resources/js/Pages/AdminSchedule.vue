<script setup>
import { Head, Link, usePage, useForm } from "@inertiajs/vue3";
import { ref, onMounted, watch, computed } from "vue";
import draggable from "vuedraggable";
import InputLabel from "@/Components/InputLabel.vue";
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";

const props = defineProps({
    scheduleInfos: Object,
});

const form = useForm({
    event_title: "",
    event_en: "",
    event_code: "",
    start_date: new Date(),
    end_date: new Date(),
    relation: [],
});

const mounted = ref(false);

onMounted(() => (mounted.value = true));

const submit = () => {
    form.transform((data) => ({
        ...data,
    })).post(route("authorize.schedule"));
};

const relate = (event) => {
    console.log(event);
    return event.id;
};
const deleteRelate = (id) => {
    form.relation = form.relation.filter((item) => item.id !== id);
};

const inconsistentDate = computed(() => {
    return form.start_date >= form.end_date;
});
const isFilled = computed(() => {
    return (
        form.event_title &&
        form.event_en &&
        form.event_code &&
        form.start_date &&
        form.end_date
    );
});

// if added data is already in the form.relation, delete it from the form.relation
watch(form.relation, (newVal) => {
    if (newVal.length > 0) {
        const last = newVal[newVal.length - 1];
        const index = newVal.findIndex((item) => item === last);
        if (index !== newVal.length - 1) {
            form.relation.splice(index, 1);
        }
    }
});
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
            <title>Admin・RegisterSchedule</title>
        </Head>
        <Teleport to='[data-slot="header"]' v-if="mounted">
            <p class="text-xs font-semibold text-gray-800">
                Admin・RegisterSchedule
            </p>
        </Teleport>
        <div class="flex w-full gap-2">
            <div class="basis-1/2 px-2">
                <draggable
                    class="dragArea list-group"
                    :list="scheduleInfos"
                    item-key="id"
                    :clone="relate"
                    :group="{ name: 'people', pull: 'clone', put: false }"
                    handle=".handle"
                >
                    <template #item="{ element }">
                        <div
                            class="list-group-item my-2 rounded-md border border-ptr-dark-brown px-4 py-2"
                        >
                            <div class="my-2 flex items-center gap-2">
                                <div class="handle">
                                    <svg
                                        class="h-6 w-6"
                                        xmlns="http://www.w3.org/2000/svg"
                                        height="1em"
                                        viewBox="0 0 448 512"
                                    >
                                        <!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                        <path
                                            d="M32 288c-17.7 0-32 14.3-32 32s14.3 32 32 32l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32L32 288zm0-128c-17.7 0-32 14.3-32 32s14.3 32 32 32l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32L32 160z"
                                        />
                                    </svg>
                                </div>
                                <div>
                                    <p>{{ element.id }}</p>
                                </div>
                            </div>
                            <div>
                                <textarea
                                    class="textarea-bordered textarea-ghost textarea w-full"
                                    :value="element.content"
                                ></textarea>
                            </div>
                        </div>
                    </template>
                </draggable>
            </div>
            <div class="mt-20 basis-1/2 px-4">
                <form @submit.prevent="submit">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text">event_title</span>
                        </label>
                        <input
                            type="text"
                            v-model="form.event_title"
                            class="input-bordered input w-full"
                        />
                    </div>
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text">event_en</span>
                        </label>
                        <input
                            type="text"
                            v-model="form.event_en"
                            class="input-bordered input w-full"
                        />
                    </div>
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text">event_code</span>
                        </label>
                        <input
                            type="text"
                            v-model="form.event_code"
                            class="input-bordered input w-full"
                        />
                    </div>
                    <InputLabel for="startDate" value="Start Date" />
                    <VueDatePicker
                        locale="jp"
                        timezone="Asia/Tokyo"
                        v-model="form.start_date"
                        id="startDate"
                        auto-apply
                    ></VueDatePicker>
                    <InputLabel for="endDate" value="End Date" />
                    <VueDatePicker
                        locale="jp"
                        timezone="Asia/Tokyo"
                        v-model="form.end_date"
                        id="endDate"
                        auto-apply
                    ></VueDatePicker>
                    <draggable
                        class="dragArea list-group min-h-16 mt-4 flex w-full flex-wrap items-center gap-x-4 gap-y-2 rounded-md border border-ptr-dark-brown px-4 py-2"
                        :list="form.relation"
                        group="people"
                        item-key="id"
                    >
                        <template #item="{ element }">
                            <div
                                class="list-group-item flex w-fit items-center gap-2 rounded-full border border-ptr-main py-1 px-2"
                            >
                                <button
                                    class="btn-xs btn-circle btn"
                                    @click="deleteRelate(element)"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-2 w-2"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"
                                        />
                                    </svg>
                                </button>
                                <p>{{ element }}</p>
                            </div>
                        </template>
                    </draggable>
                    <div class="my-3 ml-auto mr-4 w-fit">
                        <button
                            class="btn"
                            :disabled="inconsistentDate || !isFilled"
                            :class="{
                                'btn-secondary': !isFilled,
                                'btn-primary': !inconsistentDate || isFilled,
                            }"
                        >
                            Send
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
