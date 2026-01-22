<template>
    <Head title="Reading List" />

    <AnimatedButtonMenu />

    <div id="page-wrapper">
        <div class="bg-transparent px-3 py-6 shadow shadow-sky-100 md:px-12 md:py-24">
            <div class="rounded-md px-3 py-12 md:border-2 md:border-sky-600 md:px-0">
                <div class="mx-auto max-w-3xl">
                    <h1 class="text-center text-4xl font-semibold text-white">My Favorite Books & Audio</h1>
                    <p class="mx-auto pb-4 text-center text-base leading-relaxed text-sky-300">
                        Books that have made an impact on my life
                    </p>
                </div>
                <div class="category-menu mx-auto">
                    <ul class="flex w-full flex-wrap justify-center p-0 md:justify-center">
                        <li class="p-2 text-center md:border-r md:border-gray-300">
                            <button id="showall" @click="showAll" class="border-0 text-sm">
                                <span
                                    class="inline-flex items-center rounded-md bg-blue-400/10 px-2 py-1 text-xs font-medium text-sky-50 ring-1 ring-inset ring-blue-400/30 hover:text-sky-300"
                                >
                                    Show All ({{ books.length }})
                                </span>
                            </button>
                        </li>
                        <li
                            v-for="(category, index) in categories"
                            :key="index"
                            class="p-2 text-center"
                            :class="{ 'md:border-r md:border-blue-300': index !== categories.length - 1 }"
                        >
                            <button @click="selectCategory(category.selector)" :class="category.selector">
                                <span
                                    class="inline-flex items-center rounded-md bg-blue-400/10 px-2 py-1 text-xs font-medium text-sky-50 ring-1 ring-inset ring-blue-400/30 hover:text-sky-300"
                                    >{{ category.name }}</span
                                >
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="container mx-auto">
                    <div class="covers flex w-full flex-wrap">
                        <template v-for="book in this.books" :key="book.id">
                            <div
                                :class="
                                    book.selector +
                                    ' cover relative my-12 text-center transition-transform duration-500 sm:w-1/2 md:w-1/3 lg:w-1/4'
                                "
                            >
                                <a :href="book.external_link" target="_blank" rel="nofollow" class="no-underline">
                                    <img
                                        v-if="book.external_image_link"
                                        :src="book.external_image_link"
                                        class="mx-auto mb-3 shadow"
                                        :alt="imageAlt(book)"
                                    />
                                    <img
                                        v-else
                                        :src="bookImage(book)"
                                        class="mx-auto mb-3 shadow"
                                        :alt="imageAlt(book)"
                                    />
                                </a>
                                <div
                                    class="book-description mx-auto w-4/5 rounded-lg border border-sky-100 bg-sky-800 bg-opacity-25 p-4 shadow shadow-sky-50"
                                >
                                    <p class="fancy-font text-md font-semibold text-sky-50">{{ book.title }}</p>
                                    <p class="py-2 text-sm text-sky-200">{{ book.subtitle }}</p>
                                    <p class="text-sm font-semibold text-sky-100">by {{ book.author }}</p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Head } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";
import AnimatedButtonMenu from "@/Components/AnimatedButtonMenu.vue";
import { renderStarfield } from "@/three/space";
import "@/jquery.min.js"; // I have been using jQuery to load this since like 2016. I'm not going to stop now.

const bookImages = import.meta.glob("../images/books/*.{jpg,jpeg,png,webp}", {
    eager: true,
    import: "default",
});

const resolveImage = (imageMap, name, folder) => {
    if (!name) {
        return "";
    }

    const candidates = [
        `../images/${folder}/${name}.webp`,
        `../images/${folder}/${name}.jpg`,
        `../images/${folder}/${name}.jpeg`,
        `../images/${folder}/${name}.png`,
    ];

    for (const candidate of candidates) {
        if (imageMap[candidate]) {
            return imageMap[candidate];
        }
    }

    return "";
};

export default {
    components: {
        AnimatedButtonMenu,
        Head,
        Link,
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
            return `Alex Younger Reading List ${book.title} Cover Image`;
        },
        bookImage(book) {
            return resolveImage(bookImages, book?.properties?.image_name, "books");
        },
        selectCategory(selector) {
            // Only using Jquery because I don't know how to replicate their cool
            // showHide() animations in Javascript without writing a shit ton of code.
            var get_current = $(".covers ." + selector);

            $(".cover").not(get_current).hide(500);
            get_current.show(500);
        },
        showAll() {
            $(".cover").show(500);
        },
    },
    mounted() {
        renderStarfield();
    },
};
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

h1 {
    text-shadow:
        0 0 10px #fff,
        0 0 20px #fff,
        0 0 30px #0ea5e9,
        0 0 40px #0ea5e9,
        0 0 50px #0ea5e9,
        0 0 60px #0ea5e9,
        0 0 70px #0ea5e9;
}
</style>
