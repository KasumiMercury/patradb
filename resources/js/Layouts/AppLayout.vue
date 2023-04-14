<script setup>
import { ref, onMounted, onUnmounted, watch, computed } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";

const props = defineProps({
    title: String,
    forceMenuHide: Boolean,
});
/* Get Device Width */
const innerWidth = ref(2000);
const isServer = typeof window === "undefined";
const currentUrl = ref("");

if (!isServer) {
    innerWidth.value = window.innerWidth;
}

const checkWindowSize = () => {
    if (props.forceMenuHide) {
        minimumWindow();
    } else {
        if (window.innerWidth >= 1280) {
            if (isSideOpen.value === false && innerWidth.value < 1280)
                isSideOpen.value = true;
        } else {
            if (isSideOpen.value === true) minimumWindow();
        }
    }
    innerWidth.value = window.innerWidth;
};
/* Get Device Width End*/

const onKeyDown = (e) => {
    console.log(e);
    // keyInputArray.push(e);
    // if (keyInputArray.length >= 10) {
    //     keyInputArray = keyInputArray.slice(-10);
    //     if (String(keyInputArray) === String(command)) {
    //         console.log("command fire");
    //     }
    // }
};

onMounted(() => {
    // document.addEventListener('keydown', onKeyDown)
    if (!isServer) {
        innerWidth.value = window.innerWidth;
        window.addEventListener("resize", checkWindowSize);
        if (props.forceMenuHide) {
            minimumWindow();
        }
    }
});
onUnmounted(() => {
    // document.removeEventListener('keydown', onKeyDown)
    if (!isServer) {
        window.removeEventListener("resize", checkWindowSize);
    }
});

const showingNavigationDropdown = ref(false);

const isSideOpen = ref(innerWidth.value >= 1280 ? true : false);
const isMaximum = ref(false);

watch(
    () => props.forceMenuHide,
    (value) => {
        if (value) {
            showingNavigationDropdown.value = false;
            minimumWindow();
        }
    }
);
watch(
    () => currentUrl.value,
    () => {
        showingNavigationDropdown.value = false;
        isSideOpen.value = innerWidth.value >= 1280 ? true : false;
    }
);

const minimumWindow = () => {
    isSideOpen.value = false;
    isMaximum.value = false;
};

const logout = () => {
    router.post(route("logout"));
};

router.on("start", (event) => {
    currentUrl.value = event.detail.visit.url;
});
</script>
<style>
.bg_gold {
    background-image: linear-gradient(
        45deg,
        #b67b03 0%,
        #daaf08 45%,
        #fee9a0 70%,
        #daaf08 85%,
        #b67b03 90% 100%
    );
}
.border_gold {
    border-image-source: linear-gradient(
        45deg,
        #b67b03 0%,
        #daaf08 45%,
        #fee9a0 70%,
        #daaf08 85%,
        #b67b03 90% 100%
    );
    border-image-slice: 1;
}
.customSticky {
    display: flex;
    position: sticky;
    position: -webkit-sticky;
    top: 0;
}
.TopNavBar {
    border-bottom: 0.5rem solid;
    border-image-source: linear-gradient(
        45deg,
        #b67b03 0%,
        #daaf08 45%,
        #fee9a0 70%,
        #daaf08 85%,
        #b67b03 90% 100%
    );
    border-image-slice: 1;

    box-shadow: 0px 2px 4px 2px rgba(83, 77, 83, 0.5);
}
</style>
<style scoped>
.v-enter-active,
.v-leave-active {
    transition: opacity 0.4s ease, transform 0.2s ease;
    transform: translateX(0);
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
    transform: translateY(-1rem);
}
</style>
<template>
    <div class="relative h-full w-full bg-[#ffedf3]">
        <div
            class="absolute top-0 left-0 z-0 min-h-full w-full"
            data-slot="bg-wrapper"
        ></div>

        <div class="flex min-h-screen flex-col py-0">
            <!-- App TopBar Navigation-->
            <nav class="TopNavBar relative z-40 bg-ptr-dark-brown pt-safe">
                <!-- Primary Navigation Menu -->
                <div
                    class="mx-auto max-w-7xl py-3 px-4 sm:px-6 lg:py-8 lg:px-8"
                >
                    <div
                        class="flex h-16 items-center justify-between lg:mx-auto lg:w-fit"
                    >
                        <!-- App Name -->
                        <div class="flex items-center">
                            <Link :href="route('toppage')">
                                <!-- <ApplicationMark class="block h-9 w-auto" /> -->
                                <h1
                                    class="ml-0 mr-auto px-4 font-Abril text-xl tracking-widest text-[#fffafb] sm:text-2xl md:ml-2 md:text-4xl lg:mx-0"
                                >
                                    Unofficial Patra DB
                                </h1>
                            </Link>
                        </div>

                        <!-- Hamburger -->
                        <div
                            class="-mr-2 flex h-fit items-center md:mr-4 lg:hidden"
                        >
                            <button
                                class="inline-flex items-center justify-center rounded-md p-2 text-gray-100 transition duration-150 ease-in-out hover:bg-[#fb7faf] hover:text-ptr-dark-pink focus:bg-gray-100 focus:text-ptr-dark-pink focus:outline-none"
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                            >
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex':
                                                !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex':
                                                showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- SP Float Menu -->
            <div
                v-if="showingNavigationDropdown"
                @click="showingNavigationDropdown = false"
                class="absolute top-0 left-0 z-10 h-full w-full bg-custom-shadow/50"
            ></div>
            <transition>
                <div
                    v-if="showingNavigationDropdown"
                    class="absolute top-24 z-40 w-full bg-ptr-light-pink pt-4 pb-1 lg:hidden"
                >
                    <!-- Responsive Settings -->
                    <div class="flex items-center px-4">
                        <div
                            v-if="$page.props.auth.user"
                            class="my-2 w-full select-none text-center text-xl font-medium text-ptr-dark-brown"
                        >
                            {{ $page.props.auth.user.name }}
                        </div>
                        <div v-else class="flex w-full flex-row justify-around">
                            <ResponsiveNavLink :href="route('register')">
                                Register
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('login')">
                                Log in
                            </ResponsiveNavLink>
                        </div>
                    </div>

                    <div class="mt-3">
                        <ResponsiveNavLink
                            :href="route('toppage')"
                            :active="route().current('toppage')"
                        >
                            Schedules
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('dataview')"
                            :active="route().current('dataview')"
                        >
                            Memories
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('adddata')"
                            :active="route().current('adddata')"
                        >
                            Launch
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('chatview')"
                            :active="route().current('chatview')"
                        >
                            Chat
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('toolstop')"
                            :active="route().current('toolstop')"
                        >
                            Tools
                        </ResponsiveNavLink>
                        <div v-if="$page.props.auth.user" class="mt-4 mb-2">
                            <!-- Authentication -->
                            <form method="POST" @submit.prevent="logout">
                                <ResponsiveNavLink as="button">
                                    Log Out
                                </ResponsiveNavLink>
                            </form>
                        </div>
                    </div>
                </div>
            </transition>

            <!-- Main Unit-->
            <div class="flex w-full grow-0 lg:grow lg:flex-row">
                <div
                    v-if="isSideOpen"
                    @click="isSideOpen = !isSideOpen"
                    class="absolute inset-0 z-10 min-h-full min-w-full bg-[#0c0c0e]/50 xl:hidden"
                ></div>
                <!-- PG SideBar Navigation-->
                <div
                    class="relative ml-0 hidden min-h-full w-0 select-none lg:flex lg:w-fit"
                >
                    <div
                        v-if="isSideOpen"
                        class="hidden min-h-full w-0 select-none overscroll-contain pl-1 lg:absolute lg:flex xl:relative"
                        :class="{
                            'mx-0 mr-2 border-r border-ptr-dark-brown bg-ptr-light-pink shadow-md shadow-custom-shadow/80 lg:w-72':
                                isMaximum,
                            'mx-5 lg:w-64': !isMaximum,
                        }"
                    >
                        <div
                            class="customSticky z-30 h-fit w-full select-none"
                            :class="{
                                'pt-0': isMaximum,
                                'mt-12 pt-12': !isMaximum,
                            }"
                        >
                            <div
                                class="w-full rounded-md lg:flex lg:flex-col"
                                :class="{
                                    'py-0': isMaximum,
                                    'border-2 border-ptr-dark-brown bg-ptr-light-pink pb-12 shadow-lg shadow-custom-shadow/50':
                                        !isMaximum,
                                }"
                            >
                                <div
                                    class="flex w-full flex-row items-end border-b-2 border-ptr-dark-brown px-3 pb-2"
                                >
                                    <p
                                        class="ml-2 font-Abril text-ptr-dark-brown"
                                    >
                                        Menu
                                    </p>
                                    <div class="mr-0 ml-auto w-fit">
                                        <button
                                            class="mt-2 box-border inline-flex w-fit items-center justify-center rounded-md border border-ptr-dark-brown bg-[#82c8c6] p-1 text-ptr-dark-brown transition duration-150 ease-in-out hover:bg-[#fb7faf] hover:text-white focus:bg-[#fb7faf] focus:text-white focus:outline-none"
                                            @click="minimumWindow"
                                        >
                                            <svg
                                                class="h-4 w-4"
                                                stroke="currentColor"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M4 18h16"
                                                />
                                            </svg>
                                        </button>
                                        <button
                                            v-if="!isMaximum"
                                            class="mx-1 mt-2 box-border inline-flex w-fit items-center justify-center rounded-md border border-ptr-dark-brown bg-[#82c8c6] p-1 text-ptr-dark-brown transition duration-150 ease-in-out hover:bg-[#fb7faf] hover:text-white focus:bg-[#fb7faf] focus:text-white focus:outline-none"
                                            @click="isMaximum = true"
                                        >
                                            <svg
                                                class="h-4 w-4"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 512 512"
                                            >
                                                <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                                <path
                                                    d="M.3 89.5C.1 91.6 0 93.8 0 96V224 416c0 35.3 28.7 64 64 64l384 0c35.3 0 64-28.7 64-64V224 96c0-35.3-28.7-64-64-64H64c-2.2 0-4.4 .1-6.5 .3c-9.2 .9-17.8 3.8-25.5 8.2C21.8 46.5 13.4 55.1 7.7 65.5c-3.9 7.3-6.5 15.4-7.4 24zM48 224H464l0 192c0 8.8-7.2 16-16 16L64 432c-8.8 0-16-7.2-16-16l0-192z"
                                                />
                                            </svg>
                                        </button>
                                        <button
                                            v-else
                                            class="mx-1 mt-2 box-border inline-flex w-fit items-center justify-center rounded-md border border-ptr-dark-brown bg-[#82c8c6] p-1 text-ptr-dark-brown transition duration-150 ease-in-out hover:bg-[#fb7faf] hover:text-white focus:bg-[#fb7faf] focus:text-white focus:outline-none"
                                            @click="isMaximum = false"
                                        >
                                            <svg
                                                class="h-4 w-4"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 512 512"
                                            >
                                                <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                                <path
                                                    d="M432 48H208c-17.7 0-32 14.3-32 32V96H128V80c0-44.2 35.8-80 80-80H432c44.2 0 80 35.8 80 80V304c0 44.2-35.8 80-80 80H416V336h16c17.7 0 32-14.3 32-32V80c0-17.7-14.3-32-32-32zM48 448c0 8.8 7.2 16 16 16H320c8.8 0 16-7.2 16-16V256H48V448zM64 128H320c35.3 0 64 28.7 64 64V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V192c0-35.3 28.7-64 64-64z"
                                                />
                                            </svg>
                                        </button>
                                        <button
                                            class="mt-2 box-border inline-flex w-fit items-center justify-center rounded-md border border-ptr-dark-brown bg-[#82c8c6] p-1 text-ptr-dark-brown transition duration-150 ease-in-out hover:bg-[#fb7faf] hover:text-white focus:bg-[#fb7faf] focus:text-white focus:outline-none"
                                            @click="minimumWindow"
                                        >
                                            <svg
                                                class="h-4 w-4"
                                                stroke="currentColor"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    :class="{
                                                        hidden: isSideOpen,
                                                        'inline-flex':
                                                            !isSideOpen,
                                                    }"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M4 6h16M4 12h16M4 18h16"
                                                />
                                                <path
                                                    :class="{
                                                        hidden: !isSideOpen,
                                                        'inline-flex':
                                                            isSideOpen,
                                                    }"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"
                                                />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="px-3">
                                    <div
                                        v-if="$page.props.auth.user"
                                        class="mx-auto mt-2 mb-4 w-fit select-none text-xl text-ptr-dark-brown"
                                    >
                                        {{ $page.props.auth.user.name }}
                                    </div>
                                    <div
                                        v-else
                                        class="my-4 flex w-full flex-col text-xl text-ptr-dark-pink"
                                    >
                                        <ResponsiveNavLink
                                            :href="route('login')"
                                        >
                                            Log in
                                        </ResponsiveNavLink>
                                        <ResponsiveNavLink
                                            :href="route('register')"
                                        >
                                            Register
                                        </ResponsiveNavLink>
                                    </div>
                                    <ResponsiveNavLink
                                        :href="route('toppage')"
                                        :active="route().current('toppage')"
                                    >
                                        <template #icon>
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 448 512"
                                                class="mr-2 h-5 w-5"
                                            >
                                                <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                                <path
                                                    d="M96 32V64H48C21.5 64 0 85.5 0 112v48H448V112c0-26.5-21.5-48-48-48H352V32c0-17.7-14.3-32-32-32s-32 14.3-32 32V64H160V32c0-17.7-14.3-32-32-32S96 14.3 96 32zM448 192H0V464c0 26.5 21.5 48 48 48H400c26.5 0 48-21.5 48-48V192z"
                                                />
                                            </svg>
                                        </template>
                                        Schedules
                                    </ResponsiveNavLink>
                                    <ResponsiveNavLink
                                        :href="route('dataview')"
                                        :active="route().current('dataview')"
                                    >
                                        <template #icon>
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 576 512"
                                                class="mr-2 h-5 w-5"
                                            >
                                                <!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                                <path
                                                    d="M88.7 223.8L0 375.8V96C0 60.7 28.7 32 64 32H181.5c17 0 33.3 6.7 45.3 18.7l26.5 26.5c12 12 28.3 18.7 45.3 18.7H416c35.3 0 64 28.7 64 64v32H144c-22.8 0-43.8 12.1-55.3 31.8zm27.6 16.1C122.1 230 132.6 224 144 224H544c11.5 0 22 6.1 27.7 16.1s5.7 22.2-.1 32.1l-112 192C453.9 474 443.4 480 432 480H32c-11.5 0-22-6.1-27.7-16.1s-5.7-22.2 .1-32.1l112-192z"
                                                />
                                            </svg>
                                        </template>
                                        Memories
                                    </ResponsiveNavLink>
                                    <ResponsiveNavLink
                                        :href="route('adddata')"
                                        :active="route().current('adddata')"
                                    >
                                        <template #icon>
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 512 512"
                                                class="mr-2 h-5 w-5"
                                            >
                                                <!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                                <path
                                                    d="M156.6 384.9L125.7 354c-8.5-8.5-11.5-20.8-7.7-32.2c3-8.9 7-20.5 11.8-33.8L24 288c-8.6 0-16.6-4.6-20.9-12.1s-4.2-16.7 .2-24.1l52.5-88.5c13-21.9 36.5-35.3 61.9-35.3l82.3 0c2.4-4 4.8-7.7 7.2-11.3C289.1-4.1 411.1-8.1 483.9 5.3c11.6 2.1 20.6 11.2 22.8 22.8c13.4 72.9 9.3 194.8-111.4 276.7c-3.5 2.4-7.3 4.8-11.3 7.2v82.3c0 25.4-13.4 49-35.3 61.9l-88.5 52.5c-7.4 4.4-16.6 4.5-24.1 .2s-12.1-12.2-12.1-20.9V380.8c-14.1 4.9-26.4 8.9-35.7 11.9c-11.2 3.6-23.4 .5-31.8-7.8zM384 168a40 40 0 1 0 0-80 40 40 0 1 0 0 80z"
                                                />
                                            </svg>
                                        </template>
                                        Launch
                                    </ResponsiveNavLink>
                                    <ResponsiveNavLink
                                        :href="route('chatview')"
                                        :active="route().current('chatview')"
                                    >
                                        <template #icon>
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 640 512"
                                                class="mr-2 h-5 w-5"
                                            >
                                                <!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                                <path
                                                    d="M208 352c114.9 0 208-78.8 208-176S322.9 0 208 0S0 78.8 0 176c0 38.6 14.7 74.3 39.6 103.4c-3.5 9.4-8.7 17.7-14.2 24.7c-4.8 6.2-9.7 11-13.3 14.3c-1.8 1.6-3.3 2.9-4.3 3.7c-.5 .4-.9 .7-1.1 .8l-.2 .2 0 0 0 0C1 327.2-1.4 334.4 .8 340.9S9.1 352 16 352c21.8 0 43.8-5.6 62.1-12.5c9.2-3.5 17.8-7.4 25.3-11.4C134.1 343.3 169.8 352 208 352zM448 176c0 112.3-99.1 196.9-216.5 207C255.8 457.4 336.4 512 432 512c38.2 0 73.9-8.7 104.7-23.9c7.5 4 16 7.9 25.2 11.4c18.3 6.9 40.3 12.5 62.1 12.5c6.9 0 13.1-4.5 15.2-11.1c2.1-6.6-.2-13.8-5.8-17.9l0 0 0 0-.2-.2c-.2-.2-.6-.4-1.1-.8c-1-.8-2.5-2-4.3-3.7c-3.6-3.3-8.5-8.1-13.3-14.3c-5.5-7-10.7-15.4-14.2-24.7c24.9-29 39.6-64.7 39.6-103.4c0-92.8-84.9-168.9-192.6-175.5c.4 5.1 .6 10.3 .6 15.5z"
                                                />
                                            </svg>
                                        </template>
                                        Chat
                                    </ResponsiveNavLink>
                                    <ResponsiveNavLink
                                        :href="route('toolstop')"
                                        :active="route().current('toolstop')"
                                    >
                                        <template #icon>
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 512 512"
                                                class="mr-2 h-5 w-5"
                                            >
                                                <!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                                <path
                                                    d="M78.6 5C69.1-2.4 55.6-1.5 47 7L7 47c-8.5 8.5-9.4 22-2.1 31.6l80 104c4.5 5.9 11.6 9.4 19 9.4h54.1l109 109c-14.7 29-10 65.4 14.3 89.6l112 112c12.5 12.5 32.8 12.5 45.3 0l64-64c12.5-12.5 12.5-32.8 0-45.3l-112-112c-24.2-24.2-60.6-29-89.6-14.3l-109-109V104c0-7.5-3.5-14.5-9.4-19L78.6 5zM19.9 396.1C7.2 408.8 0 426.1 0 444.1C0 481.6 30.4 512 67.9 512c18 0 35.3-7.2 48-19.9L233.7 374.3c-7.8-20.9-9-43.6-3.6-65.1l-61.7-61.7L19.9 396.1zM512 144c0-10.5-1.1-20.7-3.2-30.5c-2.4-11.2-16.1-14.1-24.2-6l-63.9 63.9c-3 3-7.1 4.7-11.3 4.7H352c-8.8 0-16-7.2-16-16V102.6c0-4.2 1.7-8.3 4.7-11.3l63.9-63.9c8.1-8.1 5.2-21.8-6-24.2C388.7 1.1 378.5 0 368 0C288.5 0 224 64.5 224 144l0 .8 85.3 85.3c36-9.1 75.8 .5 104 28.7L429 274.5c49-23 83-72.8 83-130.5zM56 432a24 24 0 1 1 48 0 24 24 0 1 1 -48 0z"
                                                />
                                            </svg>
                                        </template>
                                        Tools
                                    </ResponsiveNavLink>
                                    <div
                                        v-if="$page.props.auth.user"
                                        class="mt-6"
                                    >
                                        <div
                                            class="mx-4 mb-2 h-px bg-ptr-dark-pink"
                                        ></div>
                                        <!-- Authentication -->
                                        <form
                                            method="POST"
                                            @submit.prevent="logout"
                                        >
                                            <ResponsiveNavLink as="button">
                                                <template #icon
                                                    ><svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 512 512"
                                                        class="mr-2 h-5 w-5"
                                                    >
                                                        <!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                                        <path
                                                            d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"
                                                        />
                                                    </svg>
                                                </template>
                                                Log Out
                                            </ResponsiveNavLink>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        v-if="!isSideOpen"
                        class="relative z-10 hidden max-h-full min-h-full w-0 bg-ptr-light-pink shadow-md shadow-custom-shadow/80 lg:flex lg:w-12 lg:flex-col"
                    >
                        <div class="customSticky h-fit w-full">
                            <div class="mx-auto mt-2 w-fit">
                                <button
                                    class="inline-flex items-center justify-center rounded-md p-2 text-ptr-dark-pink transition duration-150 ease-in-out hover:bg-[#fb7faf] hover:text-white focus:bg-[#fb7faf] focus:text-white focus:outline-none"
                                    @click="isSideOpen = !isSideOpen"
                                >
                                    <svg
                                        class="h-6 w-6"
                                        stroke="currentColor"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            :class="{
                                                hidden: isSideOpen,
                                                'inline-flex': !isSideOpen,
                                            }"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M4 6h16M4 12h16M4 18h16"
                                        />
                                        <path
                                            :class="{
                                                hidden: !isSideOpen,
                                                'inline-flex': isSideOpen,
                                            }"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="z-0 h-full grow">
                    <!-- Page Heading -->
                    <header class="py-3 px-6">
                        <div class="mx-auto" data-slot="header"></div>
                    </header>

                    <!-- Page Content -->
                    <main class="w-full max-w-full pb-24 lg:pb-24">
                        <!-- <div class="w-full h-fit" data-slot="youtube-player"></div> -->
                        <slot />
                    </main>
                </div>

                <!-- SP BottomBar Navigation -->
                <div
                    class="fixed inset-x-0 bottom-0 z-30 block h-12 w-screen bg-gray-300 pb-safe lg:absolute lg:hidden"
                ></div>
            </div>
        </div>
    </div>
</template>
