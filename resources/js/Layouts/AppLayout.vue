<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import Banner from "@/Components/Banner.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import pkg from "lodash";
const { debounce } = pkg;

defineProps({
    title: String,
    TopNavStyle: Object,
    TopNavFontStyle: Object,
    headerStyle: Object,
});

/* Get Device Width */
const innerWidth = ref(2000);
const isServer = typeof window === "undefined";

if (!isServer) {
    innerWidth.value = window.innerWidth;
}

const checkWindowSize = () => {
    if (window.innerWidth >= 1280) {
        if (isSideOpen.value === false && innerWidth.value < 1280)
            isSideOpen.value = true;
    } else {
        if (isSideOpen.value === true) isSideOpen.value = false;
    }
    innerWidth.value = window.innerWidth;
};
/* Get Device Width End*/

onMounted(() => {
    if (!isServer) {
        window.addEventListener("resize", debounce(checkWindowSize, 50));
    }
});
onUnmounted(() => {
    if (!isServer) {
        window.removeEventListener("resize", checkWindowSize);
    }
});

const showingNavigationDropdown = ref(false);

const isSideOpen = ref(innerWidth.value >= 1280 ? true : false);

const logout = () => {
    router.post(route("logout"));
};
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
</style>
<template>
    <div class="bg-[#ffedf3]">
        <Head :title="title" />

        <Banner />

        <div class="min-h-screen py-0 flex flex-col">
            <!-- App TopBar Navigation-->
            <nav
                class="bg-[#ff99cd] border-gray-100 shadow z-40 pt-safe"
                :style="TopNavStyle"
            >
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto py-3 px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16 lg:mx-auto lg:w-fit">
                        <div class="flex">
                            <!-- App Name -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('toppage')">
                                    <!-- <ApplicationMark class="block h-9 w-auto" /> -->
                                    <h1
                                        class="ml-0 md:ml-2 mr-auto lg:mx-0 text-2xl md:text-4xl px-4 text-white font-Titan"
                                        :style="TopNavFontStyle"
                                    >
                                        Unofficial Patra DB
                                    </h1>
                                </Link>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-mr-2 md:mr-4 flex items-center lg:hidden">
                            <button
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-100 hover:text-[#c20063] hover:bg-[#fb7faf] focus:outline-none focus:bg-gray-100 focus:text-[#c20063] transition duration-150 ease-in-out"
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

                <!-- SP Float Menu -->
                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="lg:hidden z-40 bg-[#ffc9e3]"
                >
                    <!-- Responsive Settings -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="flex items-center px-4">
                            <div
                                v-if="$page.props.auth.user"
                                class="select-none font-medium text-xl text-[#c20063] my-2 w-full text-center"
                            >
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div v-else class="w-full">
                                <ResponsiveNavLink :href="route('login')">
                                    Log in
                                </ResponsiveNavLink>
                                <ResponsiveNavLink :href="route('register')">
                                    Register
                                </ResponsiveNavLink>
                            </div>
                        </div>

                        <div class="mt-3">
                            <ResponsiveNavLink
                                :href="route('toppage')"
                                :active="route().current('toppage')"
                            >
                                TopPage
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('dataview')"
                                :active="route().current('dataview')"
                            >
                                Data
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('adddata')"
                                :active="route().current('adddata')"
                            >
                                Add Data
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
                </div>
            </nav>

            <!-- Main Unit-->
            <div class="w-full lg:flex lg:flex-row relative grow shrink-0">
                <!-- PG SideBar Navigation-->
                <div
                    class="min-h-full hidden ml-0 w-0 lg:w-fit lg:flex lg:flex-none bg-[#ffc9e3]"
                >
                    <div
                        v-if="isSideOpen"
                        @click="isSideOpen = !isSideOpen"
                        class="z-10 absolute inset-0 min-w-full min-h-full bg-gray-800/50 xl:hidden"
                    ></div>
                    <div
                        v-if="isSideOpen"
                        class="hidden w-0 min-h-full pl-1 overscroll-contain z-30 lg:absolute xl:relative lg:flex lg:w-64 bg-[#ffc9e3]"
                    >
                        <div
                            class="customSticky w-full h-fit lg:flex lg:flex-col"
                        >
                            <div
                                v-if="$page.props.auth.user"
                                class="select-none pt-8 pb-4 my-2 w-fit mx-auto text-xl text-[#c20063]"
                            >
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div
                                v-else
                                class="pt-8 pb-4 my-2 w-full text-xl text-[#c20063] flex flex-col"
                            >
                                <ResponsiveNavLink :href="route('login')">
                                    Log in
                                </ResponsiveNavLink>
                                <ResponsiveNavLink :href="route('register')">
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
                                        viewBox="0 0 576 512"
                                        class="w-5 h-5 mr-2"
                                    >
                                        <!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                        <path
                                            d="M575.8 255.5c0 18-15 32.1-32 32.1h-32l.7 160.2c0 2.7-.2 5.4-.5 8.1V472c0 22.1-17.9 40-40 40H456c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1H416 392c-22.1 0-40-17.9-40-40V448 384c0-17.7-14.3-32-32-32H256c-17.7 0-32 14.3-32 32v64 24c0 22.1-17.9 40-40 40H160 128.1c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2H104c-22.1 0-40-17.9-40-40V360c0-.9 0-1.9 .1-2.8V287.6H32c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z"
                                        />
                                    </svg>
                                </template>
                                TopPage
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('dataview')"
                                :active="route().current('dataview')"
                            >
                                <template #icon>
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 576 512"
                                        class="w-5 h-5 mr-2"
                                    >
                                        <!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                        <path
                                            d="M88.7 223.8L0 375.8V96C0 60.7 28.7 32 64 32H181.5c17 0 33.3 6.7 45.3 18.7l26.5 26.5c12 12 28.3 18.7 45.3 18.7H416c35.3 0 64 28.7 64 64v32H144c-22.8 0-43.8 12.1-55.3 31.8zm27.6 16.1C122.1 230 132.6 224 144 224H544c11.5 0 22 6.1 27.7 16.1s5.7 22.2-.1 32.1l-112 192C453.9 474 443.4 480 432 480H32c-11.5 0-22-6.1-27.7-16.1s-5.7-22.2 .1-32.1l112-192z"
                                        />
                                    </svg>
                                </template>
                                Data
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('adddata')"
                                :active="route().current('adddata')"
                            >
                                <template #icon>
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512"
                                        class="w-5 h-5 mr-2"
                                    >
                                        <!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                        <path
                                            d="M156.6 384.9L125.7 354c-8.5-8.5-11.5-20.8-7.7-32.2c3-8.9 7-20.5 11.8-33.8L24 288c-8.6 0-16.6-4.6-20.9-12.1s-4.2-16.7 .2-24.1l52.5-88.5c13-21.9 36.5-35.3 61.9-35.3l82.3 0c2.4-4 4.8-7.7 7.2-11.3C289.1-4.1 411.1-8.1 483.9 5.3c11.6 2.1 20.6 11.2 22.8 22.8c13.4 72.9 9.3 194.8-111.4 276.7c-3.5 2.4-7.3 4.8-11.3 7.2v82.3c0 25.4-13.4 49-35.3 61.9l-88.5 52.5c-7.4 4.4-16.6 4.5-24.1 .2s-12.1-12.2-12.1-20.9V380.8c-14.1 4.9-26.4 8.9-35.7 11.9c-11.2 3.6-23.4 .5-31.8-7.8zM384 168a40 40 0 1 0 0-80 40 40 0 1 0 0 80z"
                                        />
                                    </svg>
                                </template>
                                Add Data
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('chatview')"
                                :active="route().current('chatview')"
                            >
                                <template #icon>
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 640 512"
                                        class="w-5 h-5 mr-2"
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
                                        class="w-5 h-5 mr-2"
                                    >
                                        <!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                        <path
                                            d="M78.6 5C69.1-2.4 55.6-1.5 47 7L7 47c-8.5 8.5-9.4 22-2.1 31.6l80 104c4.5 5.9 11.6 9.4 19 9.4h54.1l109 109c-14.7 29-10 65.4 14.3 89.6l112 112c12.5 12.5 32.8 12.5 45.3 0l64-64c12.5-12.5 12.5-32.8 0-45.3l-112-112c-24.2-24.2-60.6-29-89.6-14.3l-109-109V104c0-7.5-3.5-14.5-9.4-19L78.6 5zM19.9 396.1C7.2 408.8 0 426.1 0 444.1C0 481.6 30.4 512 67.9 512c18 0 35.3-7.2 48-19.9L233.7 374.3c-7.8-20.9-9-43.6-3.6-65.1l-61.7-61.7L19.9 396.1zM512 144c0-10.5-1.1-20.7-3.2-30.5c-2.4-11.2-16.1-14.1-24.2-6l-63.9 63.9c-3 3-7.1 4.7-11.3 4.7H352c-8.8 0-16-7.2-16-16V102.6c0-4.2 1.7-8.3 4.7-11.3l63.9-63.9c8.1-8.1 5.2-21.8-6-24.2C388.7 1.1 378.5 0 368 0C288.5 0 224 64.5 224 144l0 .8 85.3 85.3c36-9.1 75.8 .5 104 28.7L429 274.5c49-23 83-72.8 83-130.5zM56 432a24 24 0 1 1 48 0 24 24 0 1 1 -48 0z"
                                        />
                                    </svg>
                                </template>
                                Tools
                            </ResponsiveNavLink>
                            <div v-if="$page.props.auth.user" class="mt-6">
                                <div class="mx-4 h-px bg-[#c20063]"></div>
                                <!-- Authentication -->
                                <form method="POST" @submit.prevent="logout">
                                    <ResponsiveNavLink as="button">
                                        <template #icon
                                            ><svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 512 512"
                                                class="w-5 h-5 mr-2"
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
                    <div
                        class="hidden w-0 min-h-full max-h-full relative lg:w-12 lg:flex lg:flex-col bg-[#ffc9e3] xl:hidden"
                    >
                        <div class="w-full h-fit customSticky">
                            <div class="mx-auto w-fit mt-2">
                                <button
                                    class="inline-flex items-center justify-center p-2 rounded-md text-[#c20063] hover:text-white hover:bg-[#fb7faf] focus:outline-none focus:bg-[#fb7faf] focus:text-white transition duration-150 ease-in-out"
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

                <!-- SP BottomBar Navigation -->
                <div
                    class="w-screen pb-safe h-12 block fixed inset-x-0 bottom-0 lg:hidden lg:absolute bg-gray-300 z-30"
                ></div>

                <div class="lg:grow z-0">
                    <!-- Page Heading -->
                    <header
                        v-if="$slots.header"
                        :style="headerStyle"
                        class="bg-[#ffedf3]"
                    >
                        <div class="mx-auto py-3 px-6 lg:px-12">
                            <slot name="header" />
                        </div>
                    </header>

                    <!-- Page Content -->
                    <main class="pb-12 lg:pb-0">
                        <slot />
                    </main>
                </div>
            </div>
        </div>
    </div>
</template>
