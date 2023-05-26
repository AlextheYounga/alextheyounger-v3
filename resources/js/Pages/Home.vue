<template>
    <Head title="Welcome" />
    <NavBar />
    <div id="page-wrapper" class="opacity-0 transition-opacity duration-500 w-11/12 sm:w-3/5 mx-auto max-w-3xl pt-12">
        <section class="hero mt-16 w-1/2 sm:w-1/3 mx-auto sm:mt-0 mb-4 relative ">
            <picture>
                <source srcset="/images/bridge-standing.jpg.webp" type="image/webp">
                <source srcset="/images/bridge-standing.jpg" type="image/jpg">
                <img src="/images/bridge-standing.jpg" class="headshot flex mx-auto relative text-center" alt="alex younger developer marketing about me" />
            </picture>
            <!-- <img :src="bridgeStanding" class="headshot flex mx-auto relative text-center" alt="alex younger developer marketing about me" /> -->
        </section>

        <section class="title mb-8 bg-neutral-50 shadow rounded relative">
            <h1 class="text-5xl text-gray-700 text-center">Alex Younger</h1>
            <p class="py-2 text-gray-700 text-center text-lg">Software Engineer, Data Scientist, Entrepreneur</p>
        </section>

        <!-- Skills -->
        <section class="bg-neutral-50 my-8 p-2 py-8 shadow relative rounded z-10 text-gray-700">
            <div class="language-stats">
                <h2 class="mb-3 text-3xl text-gray-700 sm:px-8">Skills</h2>
                <LanguageBar :languages="languages" />
            </div>
        </section>

        <!-- Description -->
        <section class="home-description relative bg-neutral-50 my-8 mb-8 p-8 shadow">
            <div class="image-quote sm:flex justify-between">
                <picture>
                    <source srcset="/images/frozen-lake.jpg.webp" type="image/webp">
                    <source srcset="/images/frozen-lake.jpg" type="image/jpg">
                    <img src="/images/frozen-lake.jpg" class="border-neutral-200 border-8 flex mx-auto relative text-center w-full" alt="alex younger developer marketing about me" />
                </picture>
                <!-- <img :src="frozenLake" class="border-neutral-200 border-8 flex mx-auto relative text-center w-full" alt="alex younger developer marketing about me" /> -->
                <p class="italic text-gray-700 text-sm w-full mt-4 sm:mt-0 sm:text-right sm:ml-auto sm:w-2/3 sm:pt-16">"The man who grasps principles can successfully handle his own methods. The man who tries methods, ignoring principles is sure to have trouble" -Ralph Waldo Emerson</p>
            </div>

            <div class="description leading-normal ml-auto">
                <p class="py-2 text-gray-700">I currently work for <a class="text-blue-600 hover:text-blue-800" href="https://marketplacer.com/">Marketplacer</a>,
                    building connector APIs that integrate with major ecommerce platforms.</p>
                <p class="py-2 text-gray-700">I've built and maintained websites for an extensive list of small and large companies including
                    <a class="text-blue-600 hover:text-blue-800" href="https://www.bluehawaiian.com/en">Blue Hawaiian Helicopters</a>,
                    <a class="text-blue-600 hover:text-blue-800" href="https://www.intelligentoffice.com/">Intelligent Office</a>, and
                    <a class="text-blue-600 hover:text-blue-800" href="https://www.intelligentoffice.com/">Rugdoctor</a>.
                </p>
                <p class="py-2 text-gray-700">I'm building an awesome MVC framework atop Electron called <a class="text-blue-600 hover:text-blue-800" href="https://github.com/AlextheYounga/vultron-js">Vultron JS</a>.
                    I built that Vultron to build a personal budget/accounting desktop app called AmassWealth.io (Work in Progress).</p>

                <p class="text-gray-700">My <a class="text-blue-600 hover:text-blue-800" href="https://twitter.com/hazlittresearch">Twitter bot</a> automatically posts Congressional stock transactions so you can trade like the people who control the market. Follow me.</p>
                <p class="py-2 text-gray-700">Check out my latest
                    <Link class="text-blue-600 hover:text-blue-800" :href="route('pages.projects')">projects</Link>
                </p>
            </div>
        </section>

        <!-- Projects List -->
        <section class="projects-home p-2 sm:p-8 relative mb-8 bg-neutral-50">
            <h3 class="text-3xl text-gray-700 mb-4 mx-auto">Projects</h3>
            <div class="overflow-hidden shadow sm:rounded-md">
                <ul role="list" id="projects-container" class="divide-y divide-gray-500 overflow-scroll">
                    <li v-for="project of projects" :key="project.id">
                        <a :href="project.project_link" class="block">
                            <div class="bg-neutral-200 px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <p class="truncate text-sm sm:text-lg font-medium text-blue-600">
                                        {{ project.title }}
                                    </p>
                                    <div class="ml-2 flex flex-shrink-0">
                                        <p class="text-gray-700 framework-bubble inline-flex rounded-full px-2 text-xs font-semibold leading-5" :data-framework="project.framework">
                                            {{ project.framework }}
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-2 sm:flex sm:justify-between">
                                    <div class="sm:flex">
                                        <p class="flex items-center text-sm text-gray-400 truncate">
                                            {{ project.excerpt }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
            <Link class="bg-white block border border-blue-800 hover:bg-blue-900 hover:text-blue-100 mt-12 mx-auto no-underline normal-font py-2 rounded shadow-lg text-blue-900 text-center w-36" :href="route('pages.projects')">
            See More
            </Link>
        </section>
    </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3'
import LanguageBar from '@/Components/LanguageBar.vue';
import NavBar from '@/Components/Navbar.vue';
import { terrainLoaded } from '@/Components/Terrain.vue';
import { generateProjectFrameworkColors } from '@/Components/ProjectColors.vue';
import { onMounted } from 'vue';

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
    waitForTerrain()
    generateProjectFrameworkColors()
});

</script>

<style scoped>
.hero img,
.hero picture {
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