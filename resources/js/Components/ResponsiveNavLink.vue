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
        ? "block w-full pl-4 lg:pl-12 pr-4 py-2 lg:rounded-xl text-center text-xl font-medium text-ptr-dark-pink fill-ptr-dark-pink bg-[#ffedf3] focus:outline-none focus:text-white focus:bg-ptr-pink focus:border-ptr-dark-pink transition duration-150 ease-in-out"
        : "block w-full pl-4 lg:pl-12 pr-4 py-2 lg:rounded-xl text-center text-xl font-medium text-ptr-dark-pink fill-ptr-dark-pink hover:text-white hover:fill-white hover:bg-ptr-pink focus:outline-none focus:text-white focus:fill-white focus:bg-ptr-pink transition duration-150 ease-in-out";
});
</script>

<template>
    <div>
        <button :class="classes" v-if="as == 'button'">
            <div class="lg:flex lg:w-fit lg:items-center">
                <slot name="icon" />
                <slot />
            </div>
        </button>

        <Link v-else :href="href" :class="classes">
            <div class="lg:flex lg:w-fit lg:items-center">
                <slot name="icon" />
                <slot />
            </div>
        </Link>
    </div>
</template>
