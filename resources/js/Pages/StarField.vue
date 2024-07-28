<template>
    <div class="bg-transparent">
        <Head title="Star Field" />
        <AnimatedButtonMenu />
    </div>
</template>

<script>
import { Head } from '@inertiajs/vue3';
import { renderInteractiveStarfield } from '../three/space.js';
import AnimatedButtonMenu from '@/Components/AnimatedButtonMenu.vue';

export default {
    components: {
        Head,
        AnimatedButtonMenu
    },
    created() {
        renderInteractiveStarfield();
    }
}
</script>