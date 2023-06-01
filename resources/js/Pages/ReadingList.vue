<template>
    <Head title="Reading List" />
    <NavBar />
    <div id="page-wrapper">
        <h1 class="sm:text-3xl text-2xl font-medium mb-4 pt-16 sm:pt-24 text-gray-900 text-center">
            My Favorite Books & Audio
        </h1>
        <div class="container mx-auto">
            <div class="category-menu">
                <ul class="flex flex-wrap w-full justify-center p-0">
                    <li class="text-center p-2 border-r border-gray-300">
                        <button id="showall" @click="showAll" class="normal-font text-sm text-purple-900 hover:text-purple-300">Show All</button>
                    </li>
                    <li v-for="(category, index) in categories" :key="index" class="text-center p-2" :class="{ 'border-r border-gray-300': index !== categories.length - 1 }">
                        <button @click="selectCategory(category.selector)" :class="category.selector + ' normal-font text-sm text-purple-900 hover:text-purple-300'">
                            {{ category.name }}
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <div class="container mx-auto">
            <div class="covers flex flex-wrap my-8 w-full">
                <template v-for="book in this.books" :key="book.id">
                    <div :class="book.selector + ' cover lg:w-1/4 md:w-1/3 sm:w-1/2 text-center my-12 relative duration-500 transition-transform'">
                        <a :href="book.external_link" target="_blank" rel="nofollow" class="no-underline">
                            <img v-if="book.external_image_link" :src="book.external_image_link" class="mb-3 mx-auto shadow" :alt="imageAlt(book)">
                            <img v-else :src="`/images/books/${book.image_name}.webp`" class="mb-3 mx-auto shadow" :alt="imageAlt(book)">
                        </a>
                        <div class="book-description bg-purple-100 w-4/5 p-4 rounded-lg mx-auto">
                            <p class="fancy-font text-md text-purple-900">{{ book.title }}</p>
                            <p class="normal-font text-sm text-purple-900 py-2">{{ book.subtitle }}</p>
                            <p class="normal-font text-sm text-purple-900">by {{ book.author }}</p>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
    <Footer />
</template>

<script>
import { Head } from '@inertiajs/vue3';
import Footer from '@/Components/Footer.vue';
import { Link } from '@inertiajs/vue3'
import NavBar from '@/Components/Navbar.vue';
import { terrainLoaded } from '@/Components/Terrain.vue';
import { getCurrentInstance } from 'vue';
import '@/jquery.min.js'

export default {
    components: {
        NavBar,
        Head,
        Footer,
        Link
    },
    props: {
        books: {
            type: Object,
            required: true,
        },
        categories: {
            type: Object,
            required: true,
        },
    },
    methods: {
        category(book) {
            return book.categories[0];
        },
        imageAlt(book) {
            return `Alex Younger Reading List ${book.title} Cover Image`
        },
        selectCategory(selector) {
            // Only using Jquery because I don't know how to replicate their cool
            // showHide() animations in Javascript without writing a shit ton of code.
            var get_current = $('.covers .' + selector);

            $('.cover')
                .not(get_current)
                .hide(500);
            get_current.show(500);
        },
        showAll() {
            $('.cover').show(500);
        },
        dampenBackground() {
            const terrain = document.getElementById("terrain-container")
            terrain.classList.add("opacity-50")
        }
    },
    mounted() {
        const terrain = getCurrentInstance().appContext.config.globalProperties.$terrain

        this.dampenBackground()
        if (!terrainLoaded) {
            terrain.draw()
        }
    }
}
</script>

<style scoped>
.category-menu ul li {
    list-style: none;
}

.category-menu ul li button {
    text-decoration: none;
}

.category-menu ul li button:focus {
    outline: 1px solid #fff;
    outline-offset: 5px;
}

.covers .edit-book-link {
    top: 0;
    right: 55px;
}

.covers img {
    border: 3px inset lightgrey;
    border-radius: 4px;
    max-width: 200px;
}

@media (max-width: 576px) {
    .covers .category-menu {
        width: 40%;
    }

    .covers .cover {
        width: 50%;
    }

    .covers img {
        width: 8rem;
    }

    .covers .book-description {
        width: 83.333%;
    }
}
</style>