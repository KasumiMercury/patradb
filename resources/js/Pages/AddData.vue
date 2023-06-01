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
            <title>Launch</title>
        </Head>
        <Teleport to='[data-slot="header"]' v-if="mounted">
            <p class="text-xs font-semibold text-gray-800">Launch</p>
        </Teleport>

        <div>
            <div
                class="mx-auto mt-24 flex max-w-5xl flex-col justify-center gap-12 px-4 pt-4 sm:px-6 lg:px-8"
            >
                <div v-if="!$page.props.auth.user" class="text-center">
                    <p class="font-bold text-ptr-main">
                        以下の機能は現在、ログインユーザーのみ使用可能です。
                    </p>
                    <p class="my-3 text-sm">
                        だまして悪いが、仕様なんでな　<Link
                            class="px-1n link-primary link"
                            :href="route('transition.login')"
                            >ログイン</Link
                        >してもらおう
                    </p>
                </div>
                <Link
                    as="button"
                    :disabled="!$page.props.auth.user"
                    :href="route('create.player')"
                    class="rounded-xl bg-ptr-dark-brown py-8 px-12 text-lg text-ptr-white disabled:opacity-70"
                >
                    Launch Memory
                </Link>
                <Link
                    as="button"
                    :disabled="!$page.props.auth.user"
                    :href="route('create.collabo')"
                    class="rounded-xl bg-ptr-dark-brown py-8 px-12 text-lg text-ptr-white disabled:opacity-70"
                >
                    Register Collabo
                </Link>
            </div>
        </div>
    </div>
</template>
