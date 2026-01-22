<template>
    <Head title="Home" />

    <AnimatedButtonMenu />

    <main id="page-wrapper" ref="page-wrapper" class="opacity-0 transition-opacity duration-500">
        <!-- TODO: Is there a better way to do mobile/desktop -->
        <!-- Desktop View -->
        <div id="desktop" class="hidden w-full justify-between px-8 pt-32 md:flex">
            <div id="home-menu" class="w-1/3">
                <section id="hero" class="relative mx-auto mb-4 rounded">
                    <img
                        :src="heroImage"
                        class="headshot border-sky relative mx-auto flex border-2 text-center"
                        alt="alex younger developer marketing about me"
                    />
                </section>

                <section id="title" class="relative mb-8 rounded">
                    <h1 class="glow text-center text-5xl text-sky-100">Alex Younger</h1>
                    <p id="tagline" class="py-2 text-center text-sm text-sky-200">
                        {{ $props.content?.homeTagline?.content ?? "" }}
                    </p>
                </section>

                <section class="menu-list">
                    <ul class="text-center">
                        <li v-for="item in homeItems" class="py-1 text-xl">
                            <template v-if="item.link">
                                <Link :href="route(item.link)" class="uppercase text-sky-100 hover:text-sky-200">{{
                                    item.name
                                }}</Link>
                            </template>
                            <template v-else>
                                <p
                                    @click="item.action(item.id)"
                                    class="cursor-pointer uppercase text-sky-100 hover:text-sky-200"
                                >
                                    {{ item.name }}
                                </p>
                            </template>
                        </li>
                    </ul>
                </section>
            </div>

            <div id="hud" class="w-2/3">
                <section id="bio" v-if="this.selected == 'bio'" class="mx-auto max-w-3xl">
                    <div class="rounded-md border-2 border-sky-600 bg-transparent p-12 shadow shadow-sky-100">
                        <h2 class="pb-4 text-2xl font-semibold text-sky-300">Bio</h2>
                        <div v-html="$props.content?.bio?.content ?? ''" class="text-sky-100"></div>
                    </div>
                </section>

                <section id="about" v-if="this.selected == 'about'" class="mx-auto max-w-3xl">
                    <div
                        class="large-description rounded-md border-2 border-sky-600 bg-transparent p-12 shadow shadow-sky-100"
                    >
                        <h2 class="pb-4 text-2xl font-semibold text-sky-300">About</h2>
                        <div v-html="$props.content?.about?.content ?? ''" class="text-sky-100"></div>
                    </div>
                </section>

                <section id="skills" v-if="this.selected == 'skills'" class="mx-auto max-w-3xl">
                    <div class="rounded-md border-2 border-sky-600 bg-transparent p-12 shadow shadow-sky-100">
                        <h3 class="pb-4 text-2xl font-semibold text-sky-300">Skills</h3>
                        <div class="text-sky-100">
                            <LanguageBar />
                        </div>
                    </div>
                </section>

                <section id="contact" v-if="this.selected == 'contact'" class="mx-auto max-w-3xl">
                    <div class="rounded-md border-2 border-sky-600 bg-transparent p-12 shadow shadow-sky-100">
                        <h3 class="pb-4 text-2xl font-semibold text-sky-300">Contact</h3>
                        <div id="contact-description" v-html="$props.content?.contact?.content ?? ''"></div>

                        <div class="w-full pt-12">
                            <div
                                class="mx-auto flex w-1/2 rounded-md border border-sky-100 bg-sky-300 bg-opacity-20 px-2 py-4"
                            >
                                <a v-for="link in links" :href="link.url" class="w-16 no-underline" target="_blank">
                                    <img class="mx-auto" :src="link.icon" width="25" height="25" :alt="link.alt" />
                                </a>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <!-- End Desktop View -->

        <!-- Mobile View -->
        <div id="mobile" class="block w-full pt-12 md:hidden">
            <div class="mx-auto">
                <section id="hero" class="relative mx-auto mb-4 rounded">
                    <img
                        :src="heroImage"
                        class="headshot border-sky relative mx-auto flex border-2 text-center"
                        alt="alex younger developer marketing about me"
                    />
                </section>

                <section id="title" class="relative mb-8 rounded">
                    <h1 class="glow text-center text-5xl text-sky-100">Alex Younger</h1>
                    <p id="tagline" class="py-2 text-center text-sm text-sky-200">
                        {{ $props.content?.homeTagline?.content ?? "" }}
                    </p>
                </section>
            </div>

            <div id="home-menu" class="w-full">
                <section class="menu-list container mx-auto max-w-md px-8">
                    <ul class="flex flex-wrap justify-center">
                        <li v-for="item in homeItems" class="px-2 py-1 text-sm">
                            <template v-if="item.link">
                                <Link :href="route(item.link)" class="uppercase text-sky-100 hover:text-sky-200">{{
                                    item.name
                                }}</Link>
                            </template>
                            <template v-else>
                                <p
                                    @click="item.action(item.id)"
                                    class="cursor-pointer uppercase text-sky-100 hover:text-sky-200"
                                >
                                    {{ item.name }}
                                </p>
                            </template>
                        </li>
                    </ul>
                </section>
            </div>

            <div id="hud" class="container mx-auto mt-6 w-full max-w-md px-8">
                <section id="bio" v-if="this.selected == 'bio'" class="mx-auto max-w-3xl">
                    <div class="rounded-md border border-sky-600 bg-transparent p-3 shadow shadow-sky-100">
                        <h2 class="pb-4 text-xl font-semibold text-sky-300">Bio</h2>
                        <div v-html="$props.content?.bio?.content ?? ''" class="text-sm text-sky-100"></div>
                    </div>
                </section>

                <section id="about" v-if="this.selected == 'about'" class="mx-auto max-w-3xl">
                    <div class="rounded-md border border-sky-600 bg-transparent p-3 shadow shadow-sky-100">
                        <h2 class="pb-4 text-xl font-semibold text-sky-300">About</h2>
                        <div v-html="$props.content?.about?.content ?? ''" class="text-sm text-sky-100"></div>
                    </div>
                </section>

                <section id="skills" v-if="this.selected == 'skills'" class="mx-auto max-w-3xl">
                    <div class="rounded-md border border-sky-600 bg-transparent p-3 shadow shadow-sky-100">
                        <h3 class="pb-4 text-xl font-semibold text-sky-300">Skills</h3>
                        <div class="text-sm text-sky-100">
                            <LanguageBar />
                        </div>
                    </div>
                </section>

                <section id="contact" v-if="this.selected == 'contact'" class="mx-auto max-w-3xl">
                    <div class="rounded-md border border-sky-600 bg-transparent p-3 shadow shadow-sky-100">
                        <h3 class="pb-4 text-xl font-semibold text-sky-300">Contact</h3>
                        <div
                            id="contact-description"
                            class="text-sm"
                            v-html="$props.content?.contact?.content ?? ''"
                        ></div>

                        <div class="w-full pt-6">
                            <div
                                class="mx-auto flex w-full rounded-md border border-sky-100 bg-sky-300 bg-opacity-20 px-2 py-4"
                            >
                                <a v-for="link in links" :href="link.url" class="w-16 no-underline" target="_blank">
                                    <img class="mx-auto" :src="link.icon" width="25" height="25" :alt="link.alt" />
                                </a>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <!-- End Mobile View -->
    </main>
</template>

<script>
import { Head } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";
import AnimatedButtonMenu from "@/Components/AnimatedButtonMenu.vue";
import LanguageBar from "@/Components/LanguageBar.vue";
import { renderStarfield } from "@/three/space";

// Will grab this during the mounted lifecycle hook
let pageWrapper = null;
const heroImage = new URL("../../images/bridge-standing-smaller.jpg.webp", import.meta.url).href;
const twitterIcon = "https://img.icons8.com/color/48/twitter--v1.png";
const linkedIcon = "https://img.icons8.com/color/48/linkedin.png";
const githubIcon = "https://img.icons8.com/color/48/github--v1.png";
const emailIcon = "https://img.icons8.com/emoji/48/e-mail.png";
const resumeIcon = "https://img.icons8.com/nolan/64/resume.png";

const links = [
    { name: "Twitter", url: "https://github.com/AlextheYounga", icon: twitterIcon, alt: "twitter--v1" },
    { name: "LinkedIn", url: "https://www.linkedin.com/in/alexyounger/", icon: linkedIcon, alt: "linkedin" },
    { name: "Github", url: "https://github.com/AlextheYounga", icon: githubIcon, alt: "github" },
    { name: "Email", url: "mailto:alex@alextheyounger.me", icon: emailIcon, alt: "e-mail" },
    { name: "Resume", url: "https://resume.alexyounger.me", icon: resumeIcon, alt: "resume" },
];

async function reveal() {
    let delay = 1000;
    const visited = localStorage.getItem("visited");
    if (visited) delay = 10; // Shorter delay for transition effect

    var object = { value: true, timestamp: new Date().getTime() };
    setTimeout(() => {
        localStorage.setItem("visited", JSON.stringify(object));
        pageWrapper.classList.add("opacity-100");
    }, delay);
}

export default {
    components: {
        Head,
        Link,
        AnimatedButtonMenu,
        LanguageBar,
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
                { name: "Bio", link: false, id: "bio", action: this.openSection },
                { name: "About", link: false, id: "about", action: this.openSection },
                { name: "Skills", link: false, id: "skills", action: this.openSection },
                { name: "Contact", link: false, id: "contact", action: this.openSection },
                { name: "Reading List", link: "pages.books" },
                { name: "Projects", link: "pages.projects" },
                { name: "Explore Starfield", link: "pages.starfield" },
            ],
            selected: "default", // default is nothing
        };
    },
    data() {
        return {
            heroImage,
        };
    },
    methods: {
        openSection(sectionId) {
            this.$data.selected = sectionId;
        },
    },
    mounted() {
        pageWrapper = document.getElementById("page-wrapper");
        renderStarfield();
        reveal();
    },
};
</script>

<style>
#page-wrapper {
    --glowy-text-shadow:
        0 0 10px #fff, 0 0 20px #fff, 0 0 30px #0ea5e9, 0 0 40px #0ea5e9, 0 0 50px #0ea5e9, 0 0 60px #0ea5e9,
        0 0 70px #0ea5e9;
}

#desktop #hud section > div:first-child {
    max-height: 65vh;
    overflow-y: scroll;
    scrollbar-width: none;
}

#mobile #hud section > div:first-child {
    max-height: 45vh;
    overflow-y: scroll;
    scrollbar-width: none;
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
