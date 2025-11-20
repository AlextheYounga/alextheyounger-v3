<template>
    <div class="bg-transparent">
        <Head title="Star Field" />
        <AnimatedButtonMenu />

        <div ref="hint" class="fixed top-5 right-5 group">
            <div class="group inline-block relative cursor-pointer max-w-fit min-w-fit">
                <div class="absolute hidden group-hover:block pr-0.5 whitespace-nowrap bottom-1 w-full">
                    <div class="flex flex-col-reverse justify-start items-center translate-y-full">
                        <ul
                            class="bg-sky-600 border border-sky-100 bg-opacity-25 text-sky-100 rounded py-1 px-3 cursor-default text-xs"
                        >
                            <li v-for="item in this.items">- {{ item }}</li>
                        </ul>
                    </div>
                </div>
                <span
                    class="inline-flex items-center rounded-md bg-blue-400/10 px-2 py-1 text-xs font-medium text-blue-200 hover:text-blue-300 ring-1 ring-inset ring-blue-400/30 mx-auto mb-2"
                >
                    Points of Interest
                </span>
            </div>
        </div>
    </div>
</template>

<script>
import { Head } from '@inertiajs/vue3';
import { renderStarfield } from '../three/space.js';
import AnimatedButtonMenu from '@/Components/AnimatedButtonMenu.vue';

export default {
    components: {
        Head,
        AnimatedButtonMenu,
    },
    data() {
        return {
            items: ['Starship Enterprise', 'Solar System', 'Trisolaran Droplet', 'SpaceX Starship'],
        };
    },
    created() {
        renderStarfield();
    },
};
</script>
