<template>
    <Head title="Project Gallery" />
    <NavBar />
    <section id="page-wrapper" class="text-gray-600 body-font projects ">

        <div class="container px-5 py-12 mx-auto">
            <div class="bg-white flex flex-col mb-16 mx-auto py-2 rounded shadow-lg text-center sm:w-3/5 border">
                <h1 class="sm:text-3xl text-2xl font-medium fancy-font mb-4 text-gray-900">Project Gallery</h1>
                <p class="lg:w-2/3 mx-auto leading-relaxed text-base">Here are some of my proudest projects, personal and professional.</p>
            </div>
            <div id="projects-container" class="mx-auto mt-12 grid max-w-lg gap-5 lg:max-w-6xl lg:grid-cols-3">
                <div v-if="projects.length > 0" v-for="project in this.projects" :key="project.id"
                    class="flex flex-col overflow-hidden rounded-lg shadow-lg relative border">
                    <div class="flex-shrink-0">
                        <img v-if="project.external_image_link" :src="project.external_image_link" class="h-48 w-full object-cover" />
                        <img v-else :src="`/images/projects/${project.image_name}.webp`" class="h-48 w-full object-cover" :alt="imageAlt(project)" />
                    </div>
                    <div class="flex flex-1 flex-col justify-between bg-white p-6">
                        <div class="flex-1">
                            <p v-for="tech of project.techstack" :key="tech"
                                class="framework-bubble inline-flex rounded-full px-2 text-xs font-semibold leading-5 mr-2" :data-techstack="tech">
                                {{ tech }}
                            </p>

                            <div class="mt-2 block">
                                <p class="text-xl font-semibold text-gray-900">{{ project.title }}</p>
                                <p class="text-sm font-semibold text-gray-400">{{ project.scope }}</p>
                                <p class="mt-3 text-base text-gray-500">{{ project.excerpt }}</p>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center">
                            <button @click="showModal = true; projectSelected = project;" class="text-red-500 inline-flex items-center" target="_blank">
                                View More
                                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    class="w-4 h-4 ml-2" viewBox="0 0 24 24">
                                    <path d="M5 12h14M12 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <!-- Render an empty state or message when there are no projects -->
                    <p>No projects available.</p>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <Modal :show="showModal" @close="showModal=false">
            <div>
                <div class="flex-shrink-0">
                    <img v-if="projectSelected.external_image_link" :src="projectSelected.external_image_link" class="w-full object-cover" />
                    <img v-else :src="`/images/projects/${projectSelected.image_name}.webp`" class="w-full object-cover"
                        :alt="imageAlt(projectSelected)" />
                </div>
                <div class="flex flex-1 flex-col justify-between bg-white p-6">
                    <div class="flex-1">
                        <p v-for="tech of projectSelected.techstack" :key="tech"
                            class="framework-bubble inline-flex rounded-full px-2 text-xs font-semibold leading-5 mr-2" :data-techstack="tech">
                            {{ tech }}
                        </p>

                        <div class="mt-2 block">
                            <p class="text-xl font-semibold text-gray-900">{{ projectSelected.title }}</p>
                            <p class="text-sm font-semibold text-gray-400">{{ projectSelected.scope }}</p>
                            <p v-html="projectSelected.description" class="mt-3 text-base text-gray-500"></p>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center">
                        <a :href="projectSelected.external_link" class="text-red-500 inline-flex items-center" target="_blank">Visit Project
                            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-2"
                                viewBox="0 0 24 24">
                                <path d="M5 12h14M12 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </Modal>
    </section>
    <Footer />
</template>
  
<script>
import NavBar from '@/Components/Navbar.vue';
import Modal from '@/Components/Modal.vue';
import { Head } from '@inertiajs/vue3';
import Footer from '@/Components/Footer.vue';
import { generateTechColors } from '@/Components/ProjectColors.vue';
import { terrainLoaded } from '@/Components/Terrain.vue';
import { getCurrentInstance } from 'vue';

export default {
    components: {
        Head,
        Footer,
        NavBar,
        Modal
    },
    props: {
        projects: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            showModal: false,
            projectSelected: null,
        }
    },
    methods: {
        imageAlt(project) {
            return `Alex Younger Project Gallery ${project.title} Cover Image`
        },
        dampenBackground() {
            const terrainElement = document.getElementById("terrain-container")
            terrainElement.classList.add("opacity-50")
        },

    },
    mounted() {
        const terrain = getCurrentInstance().appContext.config.globalProperties.$terrain

        this.dampenBackground()
        if (!terrainLoaded) {
            terrain.draw()
        }

        generateTechColors()
    },
    updated() {
        generateTechColors()
    }
};
</script>
  
<style>
#projects-container img,
#projects-container picture {
    max-width: 1000px;
}
</style>