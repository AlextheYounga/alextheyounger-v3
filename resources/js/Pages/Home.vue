<template>
    <Head title="Welcome" />
    <NavBar />
    <div id="page-wrapper" class="opacity-0 transition-opacity duration-500 w-11/12 sm:w-3/5 mx-auto max-w-3xl pt-12">
        <section class="hero mt-16 w-1/2 sm:w-1/3 mx-auto sm:mt-0 mb-4 relative rounded">
            <img src="/images/bridge-standing.jpg.webp" class="headshot flex mx-auto relative text-center border-burgandy border-2"
                alt="alex younger developer marketing about me" />
        </section>

        <section class="title mb-8 bg-neutral-50 shadow rounded relative">
            <h1 class="text-5xl text-gray-700 text-center">Alex Younger</h1>
            <p class="py-2 text-gray-700 text-center text-lg">Software Engineer, Data Scientist,
                Entrepreneur</p>
        </section>

        <!-- Skills -->
        <section class="bg-neutral-50 my-8 p-2 py-8 shadow relative rounded z-10 text-gray-700">
            <div class="language-stats">
                <h2 class="mb-3 text-3xl text-gray-700 sm:px-8">Skills</h2>
                <LanguageBar :languages="languages" />
            </div>
        </section>

        <!-- Quote Box -->
        <section class="home-quote relative bg-neutral-50 my-8 mb-8 p-8 shadow rounded">
            <div class="image-quote sm:flex justify-between">
                <img src="/images/frozen-lake.jpg.webp" class="border-burgandy border-2 flex mx-auto relative text-center w-full"
                    alt="alex younger developer marketing about me" />
                <div class="text-gray-700 text-sm w-full mt-4 sm:mt-0 sm:text-right sm:ml-auto sm:pt-16 sm:w-2/3 pt-2">
                    <p class="italic text-md">"The man who grasps principles can successfully handle his own
                        methods. The man who tries methods, ignoring principles is sure to have trouble"
                    </p>
                    <p class="italic pt-4"> -Ralph Waldo Emerson</p>
                </div>
            </div>
            <div class="flex justify-between mx-auto text-center w-full sm:w-5/6 py-8">
                <a v-for="link in links" :href="link.url" target="_blank" class="w-8 sm:w-12 no-underline burgandy text-gray-600">
                    <img :src="link.icon" class="w-16 hover:shadow p-1 rounded" :alt="link.alt" />
                    <small>{{ link.hint }}</small>
                </a>
            </div>
            <div class="text-center">
                <p class="text-sm text-gray-700 pb-2">Pssst! Are you bored? Wanna play with the terrain generator?
                </p>
                <Link
                    class="block mx-auto text-sm bg-red-900 text-white border border-red-800 hover:bg-white hover:text-burgandy no-underline p-1 rounded shadow-lg text-center w-16"
                    href="/terrain">play</Link>
            </div>
        </section>

        <!-- Description -->
        <section class="home-description relative bg-neutral-50 my-8 mb-8 p-8 shadow rounded">
            <h2 class="mb-3 text-3xl text-gray-700">About Me</h2>
            <div class="description leading-normal ml-auto">
                <p class="py-2 text-gray-700">I recently left <a class="text-burgandy hover:text-red-600 font-semibold" target="_blank"
                        href="https://marketplacer.com/">Marketplacer</a>, which allows enterprises
                    to manage a fleet of eCommerce websites from a unified dashboard, incorporating major platforms like Shopify, Adobe Commerce,
                    Salesforce, and BigCommerce. Here I built critical Shopify and Adobe Commerce connectors, enabling seamless data flow through an
                    extensive Rails API system.
                </p>

                <p class="py-2 text-gray-700">I've built and maintained websites for an extensive list of small and large companies including
                    <a class="text-burgandy hover:text-red-600 font-semibold" href="https://www.bluehawaiian.com/en" target="_blank">Blue Hawaiian
                        Helicopters</a>,
                    <a class="text-burgandy hover:text-red-600 font-semibold" href="https://www.intelligentoffice.com/" target="_blank">Intelligent
                        Office</a>, and
                    <a class="text-burgandy hover:text-red-600 font-semibold" href="https://www.rugdoctor.com/" target="_blank">Rugdoctor</a>.
                </p>

                <p class="py-2 text-gray-700">I'm the Software Architect as well as a consultant at the startup <a
                        class="text-burgandy hover:text-red-600 font-semibold" href="https://www.learnarena.com/" target="_blank">Learn Arena</a>.
                    As a founding developer for Learn Arena, a groundbreaking competitive learning platform, I spearheaded its transformation from a
                    hobbyist React and Firebase app to a scalable NextJS application. Learn Arena is the first-of-its-kind competitive learning platform,
                    where students with the greatest academic achievement have the potential to win back the costs of the course, or even make a profit.
                </p>

                <p class="py-2 text-gray-700">I recently launched a unique application that enables LLM models to autonomously interact, simulating
                    intriguing AI-generated conversations. called <a class="text-burgandy hover:text-red-600 font-semibold"
                        href="https://www.gptconversations.app/" target="_blank">
                        GPT Conversations</a>. Currently it's set up to continuously talk to itself on "phenomena lacking clear explanations but solvable
                    using all human knowledge."
                </p>
                <p class="py-2 text-gray-700">Check out my latest
                    <Link class="text-burgandy hover:text-red-600 font-semibold" :href="route('pages.projects')">projects</Link>
                </p>
                <p>Or my <a class="text-burgandy hover:text-red-600 font-semibold"
                        href="https://docs.google.com/document/d/1xaebeC0PrJee5jfqY1wSgAbTAqwNHdstd-Zer0BVZww/edit?usp=sharing" target="_blank">Resume</a>.</p>
            </div>
        </section>

        <!-- Projects List -->
        <section class="projects-home sm:p-8 relative mb-8 bg-neutral-50 rounded">
            <div class="flex justify-between mb-4 p-2 sm:p-0">
                <h3 class="text-left w-1/2 text-3xl text-gray-700">Projects</h3>
                <Link
                    class="bg-red-900 text-white border border-red-800 hover:bg-white hover:text-burgandy no-underline p-1 rounded shadow-lg text-center w-24"
                    :href="route('pages.projects')">
                See All
                </Link>
            </div>

            <div class="overflow-hidden shadow sm:rounded-md">
                <ul role="list" id="projects-container" class="divide-y divide-gray-400 overflow-scroll">
                    <li v-for="project of projects" :key="project.id">
                        <a :href="project.project_link" class="block">
                            <div class="bg-stone-100 px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <p class="truncate text-sm sm:text-lg font-medium text-burgandy">
                                        {{ project.title }}
                                    </p>
                                    <div class="ml-2 flex flex-shrink-0">
                                        <p v-for="tech of project.techstack" :key="tech"
                                            class="framework-bubble inline-flex rounded-full px-2 text-xs font-semibold leading-5 mr-2"
                                            :data-techstack="tech">
                                            {{ tech }}
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-2 sm:flex sm:justify-between">
                                    <div class="sm:flex">
                                        <p class="flex items-center text-xs text-gray-600 truncate">
                                            {{ project.excerpt }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </section>
    </div>
    <Footer />
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import Footer from '@/Components/Footer.vue';
import { Link } from '@inertiajs/vue3'
import LanguageBar from '@/Components/LanguageBar.vue';
import NavBar from '@/Components/Navbar.vue';
import { generateTechColors } from '@/Components/ProjectColors.vue';
import { terrainLoaded } from '@/Components/Terrain.vue';
import { onMounted } from 'vue';
import { getCurrentInstance } from 'vue';

defineProps({
    languages: {
        type: Object,
        required: true,
    },
    projects: {
        type: Object,
        required: true,
    },
});

const twitterIcon = 'https://img.icons8.com/color/48/twitter--v1.png'
const linkedIcon = 'https://img.icons8.com/color/48/linkedin.png'
const githubIcon = 'https://img.icons8.com/color/48/github--v1.png'
const emailIcon = 'https://img.icons8.com/emoji/48/e-mail.png'
const resumeIcon = 'https://img.icons8.com/nolan/64/resume.png'

const links = [
    { name: 'Twitter', url: 'https://github.com/AlextheYounga', icon: twitterIcon, alt: "twitter--v1", hint: 'Twitter' },
    { name: 'LinkedIn', url: 'https://www.linkedin.com/in/alexyounger/', icon: linkedIcon, alt: 'linkedin', hint: 'LinkedIn' },
    { name: 'Github', url: 'https://github.com/AlextheYounga', icon: githubIcon, alt: 'github', hint: 'Github' },
    { name: 'Email', url: 'mailto:alex@alextheyounger.me', icon: emailIcon, alt: 'e-mail', hint: 'Email' },
    { name: 'Resume', url: 'https://docs.google.com/document/d/1xaebeC0PrJee5jfqY1wSgAbTAqwNHdstd-Zer0BVZww/edit?usp=sharing', icon: resumeIcon, alt: 'resume', hint: 'Resume' },
];

const terrain = getCurrentInstance().appContext.config.globalProperties.$terrain

function reveal() {
    const wrapper = document.getElementById('page-wrapper');
    wrapper.classList.add('opacity-100')
}

function terrainFinished() {
    reveal()
}

function waitForTerrain() {
    if (typeof terrainLoaded === "undefined" || !terrainLoaded) {
        setTimeout(waitForTerrain, 400);
        return
    }

    terrainFinished()
}

onMounted(() => {
    if (!terrainLoaded) {
        terrain.draw()
        waitForTerrain()
    } else {
        terrainFinished()
    }
    generateTechColors()
});

</script>

<style scoped>
.hero img {
    border-radius: 130px;
}

.image-quote img {
    border-radius: 100px;
    max-width: 200px;
    padding: 0;
    margin: 0;
}

.language-stats #language-bar .bar {
    display: flex;
    height: 8px;
    overflow: hidden;
    background-color: #e1e4e8;
    border-radius: 6px;
}

.language-stats #language-bar ul {
    list-style-type: none;
}

.language-stats #language-bar ul .octicon {
    fill: currentColor;
}

.projects-home ul {
    height: 60vh;
}
</style>