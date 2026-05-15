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
                    Space to boost forward &middot; Mouse to steer &middot; Esc to release
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
            <p>Space - Boost Forward | Mouse - Steer | Esc - Release</p>
            <p class="mt-1">
                Velocity {{ velocity.speed }} u/s
                (x: {{ velocity.x }}, y: {{ velocity.y }}, z: {{ velocity.z }})
            </p>
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
            velocity: {
                x: '0.0',
                y: '0.0',
                z: '0.0',
                speed: '0.0',
            },
        };
    },
    created() {
        renderStarfield();
    },
    mounted() {
        this._onPointerLockChange = () => {
            this.isLocked = document.pointerLockElement !== null;
        };
        this._onTelemetry = (event) => {
            const telemetry = event?.detail?.velocity;
            if (!telemetry) return;

            this.velocity = {
                x: telemetry.x.toFixed(1),
                y: telemetry.y.toFixed(1),
                z: telemetry.z.toFixed(1),
                speed: telemetry.speed.toFixed(1),
            };
        };
        document.addEventListener('pointerlockchange', this._onPointerLockChange);
        window.addEventListener('starfield:telemetry', this._onTelemetry);
    },
    beforeUnmount() {
        document.removeEventListener('pointerlockchange', this._onPointerLockChange);
        window.removeEventListener('starfield:telemetry', this._onTelemetry);
    },
};
</script>
