<template>
    <div class="bg-transparent">

        <Head title="Terrain Playgound" />
        <img class="mx-auto mt-12" width="120" height="120" src="/images/neuron-activation.png.webp" alt="neuron activation meme" />
        <button @click="terrain.redraw" class="bg-white mt-2 block border border-red-800 hover:bg-red-900 hover:text-red-100 mx-auto no-underline normal-font py-2 rounded shadow-lg text-red-900 text-center w-36">
            Redraw Terrain
        </button>
    </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import { getCurrentInstance } from 'vue';
import { terrainLoaded } from '@/Components/Terrain.vue';

const terrain = getCurrentInstance().appContext.config.globalProperties.$terrain

onMounted(() => {
    if (!terrainLoaded) {
        terrain.draw()
    }
    const terrainElement = document.getElementById("terrain-container")
    terrainElement.classList.remove("opacity-50")
});
</script>