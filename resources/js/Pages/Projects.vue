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
                <div v-if="projects.length > 0" v-for="project in this.projects" :key="project.id" class="flex flex-col overflow-hidden rounded-lg shadow-lg relative border">
                    <div class="flex-shrink-0">
                        <template v-if="project.external_image_link">
                            <img :src="project.external_image_link" class="h-48 w-full object-cover" />
                        </template>
                        <template v-else>
                            <picture>
                                <source :srcset="`/images/projects/${project.image_name}.webp`" type="image/webp">
                                <source :srcset="`/images/projects/${project.image_name}`" type="image/jpg">
                                <img :src="`/images/projects/${project.image_name}`" class="h-48 w-full object-cover" :alt="imageAlt(project)" />
                            </picture>
                        </template>
                    </div>
                    <div class="flex flex-1 flex-col justify-between bg-white p-6">
                        <div class="flex-1">
                            <p class="framework-bubble inline-flex rounded-full px-2 text-xs font-semibold leading-5" :data-framework="project.framework">
                                {{ project.framework }}
                            </p>
                            <a :href="project.project_link" class="mt-2 block">
                                <p class="text-xl font-semibold text-gray-900">{{ project.title }}</p>
                                <p class="mt-3 text-base text-gray-500">{{ project.excerpt || project.description }}</p>
                            </a>
                        </div>
                        <div class="mt-6 flex items-center">
                            <div class="ml-3">
                                <a :href="project.project_link" class="text-red-500 inline-flex items-center">Visit
                                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-2" viewBox="0 0 24 24">
                                        <path d="M5 12h14M12 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <!-- Render an empty state or message when there are no projects -->
                    <p>No projects available.</p>
                </div>
            </div>
        </div>
    </section>
    <Footer/>
</template>
  
<script>
import NavBar from '@/Components/Navbar.vue';
import { Head } from '@inertiajs/vue3';
import Footer from '@/Components/Footer.vue';
import { Link } from '@inertiajs/vue3';
import { generateProjectFrameworkColors } from '@/Components/ProjectColors.vue';

export default {
    components: {
        Link,
        Head,
        Footer,
        NavBar,
    },
    props: {
        projects: {
            type: Object,
            required: true,
        },
    },
    methods: {
        imageAlt(project) {
            return `Alex Younger Project Gallery ${project.title} Cover Image`
        },
        dampenBackground() {
            const terrain = document.getElementById("terrain-container")
            terrain.classList.add("opacity-50")
        }
    },
    mounted() {
        // drawTerrain()
        generateProjectFrameworkColors()
        this.dampenBackground()
    }
};
</script>
  
<style>
#projects-container img, #projects-container picture {
    max-width: 1000px;
}
</style>