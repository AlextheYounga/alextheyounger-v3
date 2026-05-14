<template>
    <div class="bg-transparent">
        <Head title="Star Field" />
        <AnimatedButtonMenu />

        <div ref="hint" class="group fixed right-5 top-5">
            <div class="group relative inline-block min-w-fit max-w-fit cursor-pointer">
                <div class="absolute bottom-1 hidden w-full whitespace-nowrap pr-0.5 group-hover:block">
                    <div class="flex translate-y-full flex-col-reverse items-center justify-start">
                        <ul
                            class="cursor-default rounded border border-sky-100 bg-sky-600 bg-opacity-25 px-3 py-1 text-xs text-sky-100"
                        >
                            <li v-for="item in this.items">- {{ item }}</li>
                        </ul>
                    </div>
                </div>
                <span
                    class="mx-auto mb-2 inline-flex items-center rounded-md bg-blue-400/10 px-2 py-1 text-xs font-medium text-blue-200 ring-1 ring-inset ring-blue-400/30 hover:text-blue-300"
                >
                    Points of Interest
                </span>
            </div>
        </div>
    </div>
</template>

<script>
import { Head } from "@inertiajs/vue3";
import { renderStarfield } from "../three/space.js";
import AnimatedButtonMenu from "@/Components/AnimatedButtonMenu.vue";

export default {
    components: {
        Head,
        AnimatedButtonMenu,
    },
    data() {
        return {
            items: ["Starship Enterprise", "Solar System", "Trisolaran Droplet", "SpaceX Starship"],
        };
    },
    created() {
        renderStarfield();
    },
};
</script>
