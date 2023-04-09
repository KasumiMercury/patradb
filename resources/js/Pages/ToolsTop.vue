<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, onMounted, onUnmounted } from "vue";

defineProps({
    today: Object,
    month: Object,
    other: Object,
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
<template>
    <div>
        <Head>
            <title>Tools</title>
        </Head>
        <Teleport to='[data-slot="header"]' v-if="mounted">
            <p class="text-xs font-semibold text-gray-800">Tools</p>
        </Teleport>

        <div>
            <div class="mx-auto max-w-7xl px-4 pt-4 sm:px-6 lg:px-8"></div>
        </div>
    </div>
</template>
