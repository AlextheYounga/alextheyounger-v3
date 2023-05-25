<template>
    <Head title="Reading List" />
    <NavBar />
    <h1 class="sm:text-3xl text-2xl font-medium mb-4 text-gray-900 text-center">
        My Favorite Books & Audio
    </h1>
    <div class="container mx-auto">
        <div class="category-menu">
            <ul class="flex flex-wrap w-full justify-center p-0">
                <li class="text-center p-2 border-r border-gray-300">
                    <button id="showall" class="normal-font text-sm text-purple-900 hover:text-purple-300">Show All</button>
                </li>
                <li v-for="(category, index) in categories" :key="index" class="text-center p-2" :class="{ 'border-r border-gray-300': index !== categories.length - 1 }">
                    <button :id="category.properties['html_selector']" class="normal-font text-sm text-purple-900 hover:text-purple-300">{{ category.name }}</button>
                </li>
            </ul>
        </div>
    </div>

    <div class="container mx-auto">
        <div class="covers flex flex-wrap my-8 w-full">
            <div v-for="book in books" :key="book.id" v-if="book.covers.attached || book.image_address" :class="book.category.html_selector + ' cover lg:w-1/4 md:w-1/3 sm:w-1/2 text-center my-12 relative'">
                <p class="absolute left-16 top-0">{{ book.position }}</p>
                <a :href="book.book_link" target="_blank" rel="nofollow" class="no-underline">
                    <picture>
                        <source :srcset="book.covers.attached ? book.webp_attachment : `${imagePath(book.image_address)}.webp`" type="image/webp">
                        <source :srcset="book.covers.attached ? book.jpeg_attachment : image_path(book.image_address)" type="image/jpg">
                        <img :src="book.covers.attached ? book.jpeg_attachment : image_path(book.image_address)" class="mb-3 mx-auto shadow" :alt="book.image_alt">
                    </picture>
                </a>
                <div class="book-description bg-purple-100 w-4/5 p-4 rounded-lg mx-auto">
                    <p class="fancy-font text-md text-purple-900">{{ book.title }}</p>
                    <p class="normal-font text-sm text-purple-900 py-2">{{ book.subtitle }}</p>
                    <p class="normal-font text-sm text-purple-900">by {{ book.author }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3'
import NavBar from '@/Components/Navbar.vue';
import { draw } from '@/Components/Terrain.vue';
import { onMounted } from 'vue';

defineProps({
    books: {
        type: Object,
        required: true,
    },
    categories: {
        type: Object,
        required: true,
    },
});

onMounted(() => {
    draw()
});

</script>