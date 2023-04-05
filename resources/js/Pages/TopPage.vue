<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, onMounted, onUnmounted } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import TodayStream from "../Components/TodayStream.vue";
import TodaySchedule from "../Components/TodaySchedule.vue";
import MonthlySchedule from "../Components/MonthlySchedule.vue";

defineProps({
    stream: Object,
    today: Object,
    month: Object,
    other: Object,
});
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
const headerStyle = ref({
    "background-color": "#4a0139",
});
</script>
<!-- <script>
import PlayerLayout from "../Layouts/PlayerLayout.vue";

export default {
    layout: PlayerLayout,
};
</script> -->
<template>
    <AppLayout
        title="TopPage"
        :TopNavStyle="TopNavStyle"
        :TopNavFontStyle="TopNavFontStyle"
        :headerStyle="headerStyle"
    >
        <template #header>
            <p class="text-xs font-semibold text-gray-100">TopPage</p>
        </template>

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
    </AppLayout>
</template>
