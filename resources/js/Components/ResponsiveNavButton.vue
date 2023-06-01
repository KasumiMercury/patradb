<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    active: Boolean,
    href: String,
    as: String,
});

const classes = computed(() => {
    return props.active
        ? "block w-full py-2 lg:rounded-md text-center text-xl font-medium text-ptr-dark-pink fill-ptr-dark-pink bg-[#ffedf3] transition duration-150 ease-in-out"
        : "block w-full py-2 lg:rounded-md text-center text-xl font-medium text-ptr-dark-pink fill-ptr-dark-pink hover:text-white hover:fill-white hover:bg-ptr-pink focus:outline-none focus:text-white focus:fill-white focus:bg-ptr-pink transition duration-150 ease-in-out";
});
</script>

<template>
    <div>
        <button :class="classes" v-if="as == 'button'">
            <div class="mx-auto w-fit">
                <slot />
            </div>
        </button>
        <Link v-else :href="href" :class="classes">
            <div class="mx-auto w-fit">
                <slot />
            </div>
        </Link>
    </div>
</template>
