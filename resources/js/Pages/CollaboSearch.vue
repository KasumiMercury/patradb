<script setup>
import { ref, onMounted, watch } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import MemoryBack from "../Components/MemoryBack.vue";
import PaginateBox from "../Components/PaginateBox.vue";
import InputLabel from "../Components/InputLabel.vue";
import TextInput from "../Components/TextInput.vue";
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import { ja } from "date-fns/locale";
import pkg from "lodash";
import dayjs from "dayjs";
import CollaboVideoList from "../Components/CollaboVideoList.vue";
const { isEmpty, transform } = pkg;
const props = defineProps({
    dataList: Object,
    query: Object,
});

const mounted = ref(false);
onMounted(() => (mounted.value = true));
</script>
<script>
import AppLayout from "../Layouts/AppLayout.vue";

export default {
    layout: AppLayout,
};
</script>
<style scoped>
.v-enter-active,
.v-leave-active {
    transition: opacity 0.2s ease, transform 0.2s ease, width 0.2s ease;
    transform: scale(1, 1);
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
    transform: scale(1, 0);
}
</style>
<template>
    <div>
        <Head>
            <title>CollaboSearch</title>
        </Head>
        <Teleport to='[data-slot="header"]' v-if="mounted">
            <p class="text-xs font-semibold text-gray-800">
                <Link :href="route('memory.top')" class="underline"
                    >Memories</Link
                >
                / CollaboSearch
            </p>
        </Teleport>
        <div class="mt-12 flex flex-col gap-3 px-3 xl:px-12">
            <div
                class="collapse-arrow rounded-box collapse"
                v-for="(item, key, index) in props.dataList"
                :key="index"
            >
                <input type="checkbox" class="peer w-full" />
                <div
                    class="collapse-title bg-neutral text-neutral-content peer-checked:bg-base-100 peer-checked:text-accent-content"
                >
                    {{ key }}
                </div>
                <div
                    class="collapse-content bg-neutral text-neutral-content peer-checked:bg-base-100 peer-checked:text-accent-content"
                >
                    <p>{{ item }}</p>
                </div>
            </div>
        </div>
        <MemoryBack
            class="fixed top-0 left-0 z-[-10] h-full w-full"
        ></MemoryBack>
    </div>
</template>
