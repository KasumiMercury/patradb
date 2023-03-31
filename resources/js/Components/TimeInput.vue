<script setup>
import { onMounted, ref, watch } from "vue";

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
});

defineEmits(["update:modelValue"]);

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
defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        ref="input"
        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        :value="display"
        @input="$emit('update:modelValue', decodeTime($event.target.value))"
    />
</template>
