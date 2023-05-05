<script setup>
const props = defineProps({
    active: Boolean,
    hidden: Boolean,
});
</script>
<template>
    <button
        class="md:text-md flex aspect-square h-6 w-6 items-center justify-center rounded-full text-center text-xs disabled:opacity-50 md:h-7 md:w-7"
        :class="
            props.active
                ? 'bg-ptr-dark-brown text-ptr-white'
                : 'bg-ptr-white text-ptr-dark-brown'
        "
        :disabled="hidden"
    >
        <slot />
    </button>
</template>
