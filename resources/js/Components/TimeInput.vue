<script setup>
import { onMounted, onUnmounted, ref, watch } from "vue";

const encodeTime = (input) => {
    let hour = ("0" + Math.floor(input / 3600)).slice(-2);
    let min = ("0" + Math.floor((input % 3600) / 60)).slice(-2);
    let sec = ("0" + (input % 60)).slice(-2);
    return hour + ":" + min + ":" + sec;
};

const decodeTime = (input) => {
    let inputArray = input.split(":");
    return (
        Number(inputArray[0]) * 3600 +
        Number(inputArray[1]) * 60 +
        Number(inputArray[2])
    );
};

const props = defineProps({
    modelValue: Number,
    isOpenMenu: Boolean,
});

const emits = defineEmits([
    "update:modelValue",
    "showMenu",
    "playAt",
    "remove",
    "adjust",
]);

const input = ref(null);
let display = encodeTime(props.modelValue);

onMounted(() => {
    if (input.value.hasAttribute("autofocus")) {
        input.value.focus();
    }
});
watch(
    () => props.modelValue,
    () => {
        display = encodeTime(props.modelValue);
    }
);
const showMenu = () => {
    emits("showMenu");
};
const adjust = (diff) => {
    emits("update:modelValue", props.modelValue + diff);
    emits("adjust");
};
const playAt = () => {
    emits("playAt");
};
const remove = () => {
    emits("remove");
};
defineExpose({ focus: () => input.value.focus() });
</script>
<template>
    <div class="flex w-full flex-col border-0 bg-transparent">
        <div class="flex w-full flex-row border-0 bg-transparent">
            <input
                ref="input"
                class="grow rounded-md border-gray-300 px-2 py-1 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                :value="display"
                @input="
                    $emit('update:modelValue', decodeTime($event.target.value))
                "
            />
            <button
                class="mx-3 h-full w-fit rounded-md bg-white px-2 py-2"
                @click="showMenu"
            >
                <svg
                    class="h-4 w-4"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 512 512"
                >
                    <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                    <path
                        d="M0 416c0 17.7 14.3 32 32 32l54.7 0c12.3 28.3 40.5 48 73.3 48s61-19.7 73.3-48L480 448c17.7 0 32-14.3 32-32s-14.3-32-32-32l-246.7 0c-12.3-28.3-40.5-48-73.3-48s-61 19.7-73.3 48L32 384c-17.7 0-32 14.3-32 32zm128 0a32 32 0 1 1 64 0 32 32 0 1 1 -64 0zM320 256a32 32 0 1 1 64 0 32 32 0 1 1 -64 0zm32-80c-32.8 0-61 19.7-73.3 48L32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l246.7 0c12.3 28.3 40.5 48 73.3 48s61-19.7 73.3-48l54.7 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-54.7 0c-12.3-28.3-40.5-48-73.3-48zM192 128a32 32 0 1 1 0-64 32 32 0 1 1 0 64zm73.3-64C253 35.7 224.8 16 192 16s-61 19.7-73.3 48L32 64C14.3 64 0 78.3 0 96s14.3 32 32 32l86.7 0c12.3 28.3 40.5 48 73.3 48s61-19.7 73.3-48L480 128c17.7 0 32-14.3 32-32s-14.3-32-32-32L265.3 64z"
                    />
                </svg>
            </button>
        </div>
        <div v-if="isOpenMenu" class="flex flex-row justify-end pt-3">
            <button
                @click="remove"
                class="mr-12 h-full w-fit rounded-md bg-white px-2 py-2"
            >
                <svg
                    class="h-4 w-4"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 448 512"
                >
                    <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                    <path
                        d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"
                    />
                </svg>
            </button>
            <button
                @click="adjust(-1)"
                class="mx-2 h-full w-fit rounded-md bg-white px-2 py-2"
            >
                <svg
                    class="h-4 w-4"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 448 512"
                >
                    <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                    <path
                        d="M432 256c0 17.7-14.3 32-32 32L48 288c-17.7 0-32-14.3-32-32s14.3-32 32-32l352 0c17.7 0 32 14.3 32 32z"
                    />
                </svg>
            </button>
            <button
                @click="adjust(1)"
                class="mx-2 h-full w-fit rounded-md bg-white px-2 py-2"
            >
                <svg
                    class="h-4 w-4"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 448 512"
                >
                    <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                    <path
                        d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z"
                    />
                </svg>
            </button>
            <button
                @click="playAt"
                class="ml-6 mr-3 h-full w-fit rounded-md bg-white px-2 py-2"
            >
                <svg
                    class="h-4 w-4"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 384 512"
                >
                    <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                    <path
                        d="M73 39c-14.8-9.1-33.4-9.4-48.5-.9S0 62.6 0 80V432c0 17.4 9.4 33.4 24.5 41.9s33.7 8.1 48.5-.9L361 297c14.3-8.7 23-24.2 23-41s-8.7-32.2-23-41L73 39z"
                    />
                </svg>
            </button>
        </div>
    </div>
</template>
