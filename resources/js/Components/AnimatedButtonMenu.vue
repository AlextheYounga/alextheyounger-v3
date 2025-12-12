<template>
    <div class="fixed left-1 top-1 z-50 md:left-12">
        <button class="group relative border-2 border-sky-100">
            <!-- button container -->
            <div
                @click="clickHandler"
                class="relative z-20 flex h-[50px] w-[50px] transform items-center justify-center rounded-full bg-slate-700 shadow-md ring-0 ring-gray-300 ring-opacity-30 transition-all duration-200 hover:ring-8 group-focus:ring-8"
            >
                <div
                    class="flex h-[20px] w-[20px] origin-center transform flex-col justify-between transition-all duration-300 group-focus:-rotate-[45deg]"
                >
                    <div
                        class="h-[2px] w-1/2 origin-right transform rounded bg-white transition-all delay-75 duration-300 group-focus:h-[1px] group-focus:-translate-y-[1px] group-focus:-rotate-90"
                    ></div>
                    <div class="h-[1px] rounded bg-white"></div>
                    <div
                        class="m-0 h-[2px] w-1/2 origin-left transform self-end rounded bg-white transition-all delay-75 duration-300 group-focus:h-[1px] group-focus:translate-y-[1px] group-focus:-rotate-90"
                    ></div>
                </div>
            </div>
            <!-- End button container -->

            <!-- Menu List -->
            <div
                class="absolute left-6 top-5 h-0 w-0 overflow-hidden rounded-md bg-white shadow-xl transition-all delay-150 duration-300 group-focus:h-[162px] group-focus:w-40"
            >
                <ul class="divide-y py-2 text-right text-sm text-sky-700">
                    <li v-for="item in menuList" :key="item.name" class="flex justify-end hover:bg-gray-100">
                        <Link :href="route(item.link)" class="w-full px-4 py-2">
                            {{ item.name }}
                        </Link>
                    </li>
                </ul>
            </div>
        </button>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

const menuList = [
    { name: "Home", link: "home" },
    { name: "Reading List", link: "pages.books" },
    { name: "Projects", link: "pages.projects" },
    { name: "Explore Starfield", link: "pages.starfield" },
];
</script>

<style scoped>
button {
    border-radius: 100px;
}
</style>
