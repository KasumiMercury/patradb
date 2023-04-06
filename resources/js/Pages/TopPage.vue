<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, onMounted, onUnmounted } from "vue";
import TodayStream from "../Components/TodayStream.vue";
import TodaySchedule from "../Components/TodaySchedule.vue";
import MonthlySchedule from "../Components/MonthlySchedule.vue";

const mounted = ref(false);

onMounted(() => mounted.value = true);

defineProps({
    stream: Object,
    today: Object,
    month: Object,
    other: Object,
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
            <title>Top</title>
        </Head>
        <Teleport to='[data-slot="header"]' v-if="mounted">
            <p class="text-xs font-semibold text-gray-800">TopPage</p>
        </Teleport>
        <div>
            <TodayStream :data="stream" />
            <TodaySchedule :data="today" />
            <MonthlySchedule :monthData="month" :otherData="other" />
            <div class="mr-4 ml-auto mt-2 w-fit">
                <Link
                    as="button"
                    :href="route('create.schedule')"
                    class="rounded-xl bg-[#c20063] py-8 px-12 text-lg text-[#ffedf3]"
                >
                    スケジュール登録
                </Link>
            </div>
        </div>
    </div>
</template>
