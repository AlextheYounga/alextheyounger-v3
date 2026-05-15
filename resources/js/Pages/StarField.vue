<template>
    <div class="bg-transparent">
        <Head title="Star Field" />
        <AnimatedButtonMenu />

        <div
            v-if="!isLocked"
            class="pointer-events-none fixed inset-0 z-50 flex items-center justify-center"
        >
            <div class="animate-pulse text-center text-sky-200/70">
                <p class="text-lg">Click to explore</p>
                <p class="mt-1 text-xs text-sky-200/40">
                    WASD to move &middot; Space/Shift for up/down &middot; Ctrl to boost &middot; Mouse to look
                </p>
            </div>
        </div>

        <div
            v-if="isLocked"
            class="pointer-events-none fixed inset-0 z-50 flex items-center justify-center"
        >
            <div class="h-1 w-1 rounded-full bg-sky-200/50"></div>
        </div>

        <div
            v-if="isLocked"
            class="pointer-events-none fixed bottom-5 left-5 z-50 text-xs text-sky-200/30"
        >
            <p>WASD - Move | Space - Up | Shift - Down | Ctrl - Boost | Esc - Release</p>
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
            isLocked: false,
        };
    },
    created() {
        renderStarfield();
    },
    mounted() {
        this._onPointerLockChange = () => {
            this.isLocked = document.pointerLockElement !== null;
        };
        document.addEventListener('pointerlockchange', this._onPointerLockChange);
    },
    beforeUnmount() {
        document.removeEventListener('pointerlockchange', this._onPointerLockChange);
    },
};
</script>
