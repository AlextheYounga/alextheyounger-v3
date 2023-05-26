<template>
    <Head title="Terrain Playgound" />
    <NavBar />
    <button @click="redraw" class="bg-white block border border-red-800 hover:bg-red-900 hover:text-red-100 mt-12 mx-auto no-underline normal-font py-2 rounded shadow-lg text-red-900 text-center w-36">
        Redraw Terrain
    </button>
    <Footer/>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import NavBar from '@/Components/Navbar.vue';
import Footer from '@/Components/Footer.vue';
import { onMounted } from 'vue';

// drawTerrain()

onMounted(() => {
    const terrain = document.getElementById("terrain-container")
    terrain.classList.remove("opacity-50")
});
</script>