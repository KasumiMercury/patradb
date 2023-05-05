<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, onMounted, onUnmounted } from "vue";
import MemoryBack from "@/Components/MemoryBack.vue";

defineProps({});

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
            <title>Memoories</title>
        </Head>
        <Teleport to='[data-slot="header"]' v-if="mounted">
            <p class="text-xs font-semibold text-gray-800">Memories</p>
        </Teleport>

        <div>
            <div
                class="mx-auto mt-28 flex max-w-7xl flex-col justify-around gap-20 lg:mt-40 lg:flex-row lg:gap-0"
            >
                <Link as="button" :href="route('memory.search')"
                    >MemorySearch</Link
                >
                <Link as="button" :href="route('memory.videos')"
                    >VideoSearch</Link
                >
                <Link as="button" :href="route('memory.collabo')"
                    >CollaboSearch</Link
                >
            </div>
        </div>
        <MemoryBack
            class="fixed top-0 left-0 z-[-10] h-full w-full"
        ></MemoryBack>
    </div>
</template>
