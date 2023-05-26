<template>
    <Head title="Terrain Playgound" />
    <p class="text-center">Pretty rad, right?</p>
    <button @click="terrain.redraw" class="bg-white block border border-red-800 hover:bg-red-900 hover:text-red-100 mt-12 mx-auto no-underline normal-font py-2 rounded shadow-lg text-red-900 text-center w-36">
        Redraw Terrain
    </button>

</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import { getCurrentInstance } from 'vue';

const terrain = getCurrentInstance().appContext.config.globalProperties.$terrain

onMounted(() => {
    const terrain = document.getElementById("terrain-container")
    terrain.classList.remove("opacity-50")
});
</script>