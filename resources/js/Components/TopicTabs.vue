<script setup>
const props = defineProps({
    registered: String,
});
</script>
<template>
    <div class="flex flex-row gap-3 items-center">
        <slot></slot>
        <div class="tabs tabs-boxed w-fit bg-ptr-dark-brown">
            <button class="tab tab-md text-ptr-white"
                :class="{'tab-active': !registered}"
            >無効</button>
            <button class="tab tab-md text-ptr-white"
                :class="{'tab-active': registered && registered.includes('today')}"
            >当日</button>
            <button class="tab tab-md text-ptr-white"
                :class="{'tab-active': registered && registered.includes('all')}"
            >全部</button>
        </div>
    </div>
</template>
