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
                        Software Engineer, Data Scientist, Entrepreneur
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
                        <div class="text-sky-100">
                            <p>
                                Programmer writing in multiple languages, doing web development,
                                <a href="https://youtu.be/VTdS4-Tas6E?si=VuC2DKMPXHrL9r0v">drone programming</a>,
                                and AI LLM development. I love freedom, adrenaline, and working to improve the world
                                any way I can.
                            </p>
                        </div>
                    </div>
                </section>

                <section id="about" v-if="this.selected == 'about'" class="mx-auto max-w-3xl">
                    <div
                        class="large-description rounded-md border-2 border-sky-600 bg-transparent p-12 shadow shadow-sky-100"
                    >
                        <h2 class="pb-4 text-2xl font-semibold text-sky-300">About</h2>
                        <div class="text-sky-100">
                            <p>
                                Over the past three years, I've led the transformation of
                                <a href="https://www.learnarena.com/" target="_blank" rel="noopener noreferrer"
                                    >Learn Arena</a
                                >
                                into a scalable Next.js platform. It's a competitive learning platform where users can
                                get paid to learn. I also developed an AI course generator that creates 60+ hours of
                                interactive content from a single topic.
                            </p>

                            <p>
                                At
                                <a href="https://marketplacer.com/" target="_blank" rel="noopener noreferrer"
                                    >Marketplacer</a
                                >,
                                I developed core Shopify and Adobe Commerce API connectors for an enterprise Rails API
                                system, allowing enterprise companies to manage a fleet of eCommerce websites where
                                every action syncs back to a central dashboard.
                            </p>

                            <p>
                                I started at
                                <a href="https://www.izoox.com/" target="_blank" rel="noopener noreferrer">Izoox</a>,
                                building websites and infrastructure for clients like
                                <a href="https://www.bluehawaiian.com/en" target="_blank" rel="noopener noreferrer"
                                    >Blue Hawaiian Helicopters</a
                                >,
                                <a href="https://www.intelligentoffice.com/" target="_blank" rel="noopener noreferrer"
                                    >Intelligent Office</a
                                >,
                                and
                                <a href="https://www.rugdoctor.com/" target="_blank" rel="noopener noreferrer"
                                    >Rug Doctor</a
                                >.
                            </p>

                            <p>
                                View my <a href="/projects">projects</a> or check out my
                                <a href="https://resume.alexyounger.me" target="_blank" rel="noopener noreferrer"
                                    >resume</a
                                >.
                            </p>
                        </div>
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
                        <div id="contact-description">
                            <p>
                                Not a fan of copyright, take whatever you want. Here's the
                                <a
                                    href="https://github.com/AlextheYounga/alextheyounger-v3"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    >repo</a
                                >
                                for this site.
                                <a href="https://alextheyounger.me" target="_blank" rel="noopener noreferrer"
                                    >alextheyounger.me</a
                                >
                                by Alex Younger.
                            </p>
                            <p>
                                This site was built with PHP Laravel with Orchid, InertiaJS, Vue3, ThreeJS, and
                                TailwindCSS.
                            </p>
                        </div>

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
                        Software Engineer, Data Scientist, Entrepreneur
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
                        <div class="text-sm text-sky-100">
                            <p>
                                Programmer writing in multiple languages, doing web development,
                                <a href="https://youtu.be/VTdS4-Tas6E?si=VuC2DKMPXHrL9r0v">drone programming</a>,
                                and AI LLM development. I love freedom, adrenaline, and working to improve the world
                                any way I can.
                            </p>
                        </div>
                    </div>
                </section>

                <section id="about" v-if="this.selected == 'about'" class="mx-auto max-w-3xl">
                    <div class="rounded-md border border-sky-600 bg-transparent p-3 shadow shadow-sky-100">
                        <h2 class="pb-4 text-xl font-semibold text-sky-300">About</h2>
                        <div class="text-sm text-sky-100">
                            <p>
                                Over the past three years, I've led the transformation of
                                <a href="https://www.learnarena.com/" target="_blank" rel="noopener noreferrer"
                                    >Learn Arena</a
                                >
                                into a scalable Next.js platform. It's a competitive learning platform where users can
                                get paid to learn. I also developed an AI course generator that creates 60+ hours of
                                interactive content from a single topic.
                            </p>

                            <p>
                                At
                                <a href="https://marketplacer.com/" target="_blank" rel="noopener noreferrer"
                                    >Marketplacer</a
                                >,
                                I developed core Shopify and Adobe Commerce API connectors for an enterprise Rails API
                                system, allowing enterprise companies to manage a fleet of eCommerce websites where
                                every action syncs back to a central dashboard.
                            </p>

                            <p>
                                I started at
                                <a href="https://www.izoox.com/" target="_blank" rel="noopener noreferrer">Izoox</a>,
                                building websites and infrastructure for clients like
                                <a href="https://www.bluehawaiian.com/en" target="_blank" rel="noopener noreferrer"
                                    >Blue Hawaiian Helicopters</a
                                >,
                                <a href="https://www.intelligentoffice.com/" target="_blank" rel="noopener noreferrer"
                                    >Intelligent Office</a
                                >,
                                and
                                <a href="https://www.rugdoctor.com/" target="_blank" rel="noopener noreferrer"
                                    >Rug Doctor</a
                                >.
                            </p>

                            <p>
                                View my <a href="/projects">projects</a> or check out my
                                <a href="https://resume.alexyounger.me" target="_blank" rel="noopener noreferrer"
                                    >resume</a
                                >.
                            </p>
                        </div>
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
                        <div id="contact-description" class="text-sm">
                            <p>
                                Not a fan of copyright, take whatever you want. Here's the
                                <a
                                    href="https://github.com/AlextheYounga/alextheyounger-v3"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    >repo</a
                                >
                                for this site.
                                <a href="https://alextheyounger.me" target="_blank" rel="noopener noreferrer"
                                    >alextheyounger.me</a
                                >
                                by Alex Younger.
                            </p>
                            <p>
                                This site was built with PHP Laravel with Orchid, InertiaJS, Vue3, ThreeJS, and
                                TailwindCSS.
                            </p>
                        </div>

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
    data() {
        return {
            links,
            heroImage,
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
