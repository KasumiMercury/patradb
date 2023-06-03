<script setup>
import { ref, watch, defineEmits } from "vue";
const props = defineProps({
    registered: String,
    topic: String,
});
const emits = defineEmits(["controllSubscribe"]);
const range = ref("disabled");
const changing = ref(false);

watch(
    () => props.registered,
    (newValue) => {
        if (newValue == null) {
            range.value = "disabled";
        } else {
            let newRange = newValue.replace(props.topic, "");
            if (newRange === "") {
                newRange = "disabled";
            } else {
                range.value = newRange;
            }
        }
        changing.value = false;
    }
);
const controllSubscribe = (targetRange) => {
    if (targetRange === range.value) {
        return;
    } else {
        changing.value = true;
        emits("controllSubscribe", props.registered, false);
        if (targetRange !== "disabled") {
            emits("controllSubscribe", String(targetRange + props.topic), true);
        }
    }
};
</script>
<template>
    <div class="flex flex-row items-center gap-3">
        <slot></slot>
        <div class="relative">
            <div class="tabs tabs-boxed w-fit bg-ptr-dark-brown">
                <button
                    class="tab tab-md text-ptr-white"
                    :class="{ 'tab-active': range === 'disabled' }"
                    :disabled="changing"
                    @click="controllSubscribe('disabled')"
                >
                    無効
                </button>
                <button
                    class="tab tab-md text-ptr-white"
                    :class="{ 'tab-active': range === 'today' }"
                    :disabled="changing"
                    @click="controllSubscribe('today')"
                >
                    当日
                </button>
                <button
                    class="tab tab-md text-ptr-white"
                    :class="{ 'tab-active': range === 'all' }"
                    :disabled="changing"
                    @click="controllSubscribe('all')"
                >
                    全部
                </button>
            </div>
            <div
                v-if="changing"
                class="absolute top-0 left-0 z-10 flex h-full w-full flex-col items-center justify-center"
            >
                <p class="text-ptr-white">変更適用中</p>
            </div>
        </div>
    </div>
</template>
