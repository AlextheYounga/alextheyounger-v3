<template>

    <Head title="Home" />

    <AnimatedButtonMenu />

    <main id="page-wrapper" ref="page-wrapper" class="opacity-0 transition-opacity duration-500 pt-32">
        <div class="flex justify-between w-full">
            <div id="home-menu" class="w-1/3">
                <section id="hero" class="w-1/3 mx-auto sm:mt-0 mb-4 relative rounded">
                    <img src="/images/bridge-standing.jpg.webp" class="headshot flex mx-auto relative text-center border-sky border-2" alt="alex younger developer marketing about me" />
                </section>

                <section id="title" class="rounded relative mb-8">
                    <h1 class="text-5xl text-sky-100 text-center glow">Alex Younger</h1>
                    <p id="tagline" class="py-2 text-sky-200 text-center text-sm">
                        {{ $props.content?.homeTagline?.content ?? '' }}
                    </p>
                </section>

                <section class="menu-list">
                    <ul class="text-center">
                        <li v-for="item in homeItems" class="py-1 text-xl">
                            <template v-if="item.link">
                                <Link :href="route(item.link)" class="text-sky-100 hover:text-sky-200 uppercase">{{ item.name }}</Link>
                            </template>
                            <template v-else>
                                <p @click="item.action(item.id)" class="text-sky-100 hover:text-sky-200 uppercase cursor-pointer">{{ item.name }}</p>
                            </template>
                        </li>
                    </ul>
                </section>
            </div>

            <div id="hud" class="w-2/3">
                <section id="bio" v-if="this.selected == 'bio'" class="max-w-3xl mx-auto">
                    <div class="rounded-md p-12 border-2 border-sky-600 bg-transparent shadow shadow-sky-100">
                        <h2 class="text-sky-300 text-2xl pb-4 font-semibold">Bio</h2>
                        <div v-html="$props.content?.bio?.content ?? ''" class="text-sky-100"></div>
                    </div>
                </section>

                <section id="skills" v-if="this.selected == 'skills'" class="max-w-3xl mx-auto">
                    <div class="rounded-md p-12 border-2 border-sky-600 bg-transparent shadow shadow-sky-100">
                        <h3 class="text-sky-300 text-2xl pb-4 font-semibold">Skills</h3>
                        <div class="text-sky-100">
                            <LanguageBar />
                        </div>

                    </div>
                </section>
            </div>
        </div>
    </main>
</template>

<script>
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3'
import AnimatedButtonMenu from '@/Components/AnimatedButtonMenu.vue';
import LanguageBar from '@/Components/LanguageBar.vue';
import { renderStarfield } from '@/three/space';
// import Footer from '@/Components/Footer.vue';

// Will grab this during the mounted lifecycle hook
let pageWrapper = null;
const sections = ['bio', 'skills'];

async function reveal() {
    const delay = 1000
    setTimeout(() => {
        pageWrapper.classList.add('opacity-100');
    }, delay);
}

export default {
    components: {
        Head,
        Link,
        AnimatedButtonMenu,
        LanguageBar
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
    data() {
        return {
            homeItems: [
                { name: 'Bio', link: false, id: 'bio', action: this.openSection },
                { name: 'Skills', link: false, id: 'skills', action: this.openSection },
                { name: 'Reading List', link: 'pages.books' },
                { name: 'Projects', link: 'pages.projects' },
                { name: 'Explore Starfield', link: 'pages.starfield' }
            ],
            selected: 'default' // default is nothing
        }
    },
    methods: {
        openSection(sectionId) {
            this.$data.selected = sectionId;
        }
    },
    beforeCreate() {
        reveal()
    },
    mounted() {
        pageWrapper = document.getElementById('page-wrapper');
        renderStarfield();
    }
}
</script>

<style>
#page-wrapper {
    --glowy-text-shadow: 0 0 10px #fff, 0 0 20px #fff, 0 0 30px #0ea5e9, 0 0 40px #0ea5e9, 0 0 50px #0ea5e9, 0 0 60px #0ea5e9, 0 0 70px #0ea5e9;
}

#bio a {
    color: #38bdf8 !important;
    text-decoration: underline;
}

#hero img {
    border-radius: 130px;
    max-width: 175px;
}

.menu-list ul li:hover {
    text-shadow: var(--glowy-text-shadow);
}

.glow-shadow {
    box-shadow: var(--glowy-text-shadow);
}

.glow {
    text-shadow: var(--glowy-text-shadow);
}
</style>