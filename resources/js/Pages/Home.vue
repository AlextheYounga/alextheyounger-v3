<template>

    <Head title="Home" />

    <AnimatedButtonMenu />

    <main id="page-wrapper" class="opacity-0 transition-opacity duration-500 w-1/3 pt-32">
        <section class="hero w-1/3 mx-auto sm:mt-0 mb-4 relative rounded">
            <img src="/images/bridge-standing.jpg.webp" class="headshot flex mx-auto relative text-center border-sky border-2" alt="alex younger developer marketing about me" />
        </section>

        <section class="title rounded relative mb-8">
            <h1 class="text-5xl text-sky-100 text-center glow">Alex Younger</h1>
            <p id="tagline" class="py-2 text-sky-200 text-center text-sm"></p>
        </section>

        <section class="home-menu">
            <ul class="text-center">
                <li v-for="item in homeItems" class="py-1 text-xl">
                    <template v-if="item.link">
                        <Link :href="route(item.link)" class="text-sky-100 hover:text-sky-200 uppercase">{{ item.name }}</Link>
                    </template>
                    <template v-else>
                        <p @click="item.action" class="text-sky-100 hover:text-sky-200 uppercase cursor-pointer">{{ item.name }}</p>
                    </template>
                </li>
            </ul>
        </section>
    </main>
</template>

<script>
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3'
import AnimatedButtonMenu from '@/Components/AnimatedButtonMenu.vue';
// import Footer from '@/Components/Footer.vue';
// import LanguageBar from '@/Components/LanguageBar.vue';
// import { generateTechColors } from '@/Components/ProjectColors.vue';
import { renderStarfield } from '@/three/space';

const delay = (ms) => new Promise((res) => setTimeout(res, ms));

async function reveal() {
    await delay(1000);
    const wrapper = document.getElementById('page-wrapper');
    wrapper.classList.add('opacity-100')
}

// Write content from db; there's probably a better way to do this
function writeContent(content) {
    for (const key in content) {
        const contentItem = content[key]
        if (contentItem && contentItem.html_id) {
            const element = document.getElementById(contentItem.html_id)
            if (!element) continue
            element.innerHTML = contentItem.content
        }
    }
}

const homeItems = [
    { name: 'Bio', link: false, action: 'openBio' },
    { name: 'Skills', link: false, action: 'openSkills' },
    { name: 'Reading List', link: 'pages.books' },
    { name: 'Projects', link: 'pages.projects' },
    { name: 'Explore Starfield', link: 'pages.starfield' }
];

export default {
    components: {
        Head,
        Link,
        AnimatedButtonMenu
    },
    props: {
        content: {
            type: Object,
            required: true,
        },
        projects: {
            type: Object,
            required: true,
        },
    },
    setup() {
        return {
            homeItems,
        }
    },
    mounted() {
        renderStarfield();
        writeContent(this.$props.content);
        reveal();
    }
}
</script>

<style scoped>
.hero img {
    border-radius: 130px;
    max-width: 175px;
}

.home-menu ul li:hover {
    text-shadow: 0 0 10px #fff, 0 0 20px #fff, 0 0 30px #0ea5e9, 0 0 40px #0ea5e9, 0 0 50px #0ea5e9, 0 0 60px #0ea5e9, 0 0 70px #0ea5e9;
}

.glow {
    text-shadow: 0 0 10px #fff, 0 0 20px #fff, 0 0 30px #0ea5e9, 0 0 40px #0ea5e9, 0 0 50px #0ea5e9, 0 0 60px #0ea5e9, 0 0 70px #0ea5e9;
}
</style>