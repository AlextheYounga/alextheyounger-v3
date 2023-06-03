<template>
    <div class="bg-transparent">

        <Head title="Terrain Playgound" />
        <Navbar />
        <div class="absolute bottom-5 left-5 sm:top-10 sm:bottom-0 sm:left-10">
            <div class="neuron-activation text-sm sm:text-base text-gray-700 font-extrabold font-sans -mb-4 sm:-mb-6 tracking-tight">Neuron activation</div>
            <img class="w-20 h-20 sm:w-28 sm:h-28" src="/images/monkey-brain.png.webp" alt="neuron activation meme" />
            <button @click="terrain.redraw"
                class="bg-white mt-2 block border border-red-800 hover:bg-red-900 hover:text-red-100 no-underline normal-font rounded shadow-lg text-red-900 text-center p-1 sm:w-36">
                Redraw Terrain
            </button>
        </div>

    </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';
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