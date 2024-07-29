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

                <section id="about" v-if="this.selected == 'about'" class="max-w-3xl mx-auto">
                    <div class="rounded-md p-12 border-2 border-sky-600 bg-transparent shadow shadow-sky-100">
                        <h2 class="text-sky-300 text-2xl pb-4 font-semibold">About</h2>
                        <div v-html="$props.content?.about?.content ?? ''" class="text-sky-100"></div>
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

                <section id="contact" v-if="this.selected == 'contact'" class="max-w-3xl mx-auto">
                    <div class="rounded-md p-12 border-2 border-sky-600 bg-transparent shadow shadow-sky-100">
                        <h3 class="text-sky-300 text-2xl pb-4 font-semibold">Contact</h3>
                        <div id="contact-description" v-html="$props.content?.contact?.content ?? ''"></div>

                        <div class="w-full pt-12">
                            <div class="w-1/2 flex py-4 bg-sky-300 bg-opacity-20 border border-sky-100 px-2 rounded-md mx-auto">
                                <a v-for="link in links" :href="link.url" class="w-10 sm:w-16 no-underline" target="_blank">
                                    <img class="mx-auto" :src="link.icon" width="25" height="25" :alt="link.alt" />
                                </a>
                            </div>
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

// Will grab this during the mounted lifecycle hook
let pageWrapper = null;

const twitterIcon = 'https://img.icons8.com/color/48/twitter--v1.png'
const linkedIcon = 'https://img.icons8.com/color/48/linkedin.png'
const githubIcon = 'https://img.icons8.com/color/48/github--v1.png'
const emailIcon = 'https://img.icons8.com/emoji/48/e-mail.png'
const resumeIcon = 'https://img.icons8.com/nolan/64/resume.png'

const links = [
    { name: 'Twitter', url: 'https://github.com/AlextheYounga', icon: twitterIcon, alt: "twitter--v1" },
    { name: 'LinkedIn', url: 'https://www.linkedin.com/in/alexyounger/', icon: linkedIcon, alt: 'linkedin' },
    { name: 'Github', url: 'https://github.com/AlextheYounga', icon: githubIcon, alt: 'github' },
    { name: 'Email', url: 'mailto:alex@alextheyounger.me', icon: emailIcon, alt: 'e-mail' },
    { name: 'Resume', url: 'https://docs.google.com/document/d/1xaebeC0PrJee5jfqY1wSgAbTAqwNHdstd-Zer0BVZww/edit?usp=sharing', icon: resumeIcon, alt: 'resume' },
];

async function reveal() {
    let delay = 1000
    const visited = localStorage.getItem('visited');
    if (visited) delay = 10 // Shorter delay for transition effect
    
    var object = {value: true, timestamp: new Date().getTime()}
    setTimeout(() => {
        localStorage.setItem('visited', JSON.stringify(object));
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
    },
    data() {
        return {
            links,
            homeItems: [
                { name: 'Bio', link: false, id: 'bio', action: this.openSection },
                { name: 'About', link: false, id: 'about', action: this.openSection },
                { name: 'Skills', link: false, id: 'skills', action: this.openSection },
                { name: 'Reading List', link: 'pages.books' },
                { name: 'Projects', link: 'pages.projects' },
                { name: 'Contact', link: false, id: 'contact', action: this.openSection },
                { name: 'Explore Starfield', link: 'pages.starfield' }
            ],
            selected: 'default' // default is nothing
        }
    },
    methods: {
        openSection(sectionId) {
            this.$data.selected = sectionId;
        },
    },
    mounted() {
        pageWrapper = document.getElementById('page-wrapper');
        renderStarfield();
        reveal()
    }
}
</script>

<style>
#page-wrapper {
    --glowy-text-shadow: 0 0 10px #fff, 0 0 20px #fff, 0 0 30px #0ea5e9, 0 0 40px #0ea5e9, 0 0 50px #0ea5e9, 0 0 60px #0ea5e9, 0 0 70px #0ea5e9;
}

#hud p {
    color: #e0f2fe;
    margin-bottom: 1rem;
}

#hud a {
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