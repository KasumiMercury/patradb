<script setup>
import { Head,Link } from "@inertiajs/vue3";
import { ref, onMounted, onUnmounted } from "vue";

defineProps({
    today: Object,
    month: Object,
    other: Object,
});

const mounted = ref(false);

onMounted(() => mounted.value = true);
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
            <title>AddData</title>
        </Head>
        <Teleport to='[data-slot="header"]' v-if="mounted">
            <p class="text-xs font-semibold text-gray-800">AddData</p>
        </Teleport>

        <div>
            <div class="mx-auto max-w-7xl px-4 pt-4 sm:px-6 lg:px-8">
                <div class="mx-auto mt-12 w-fit">
                    <Link
                        as="button"
                        :href="route('create.player')"
                        class="rounded-xl bg-[#c20063] py-8 px-12 text-lg text-[#ffedf3]"
                    >
                        プレイヤー登録
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
