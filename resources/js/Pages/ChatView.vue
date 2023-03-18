<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, onMounted, onUnmounted } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";

defineProps({
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
</script>
<template>
    <AppLayout
        title="AddData"
        :TopNavStyle="TopNavStyle"
        :TopNavFontStyle="TopNavFontStyle"
    >
        <template #header>
            <p class="font-semibold text-xs text-gray-800">Chat</p>
        </template>

        <div>
            <div class="max-w-7xl mx-auto pt-4 px-4 sm:px-6 lg:px-8"></div>
        </div>
    </AppLayout>
</template>
