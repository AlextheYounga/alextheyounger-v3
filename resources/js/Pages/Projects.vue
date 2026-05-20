```vue
<template>
    <Head title="Project Gallery" />
    <AnimatedButtonMenu />

    <div id="page-wrapper">
        <div id="projects" class="bg-transparent px-3 py-6 shadow shadow-sky-100 md:px-12 md:py-24">
            <div class="rounded-md px-3 py-12 md:border-2 md:border-sky-600 md:px-0">
                <div class="mx-auto max-w-3xl">
                    <h1 class="text-center text-4xl font-semibold text-white">Projects</h1>
                    <p class="mx-auto pb-4 text-center text-base leading-relaxed text-sky-300">
                        Here are some of my proudest projects, personal and professional.
                    </p>
                </div>
                <div
                    v-if="projects.length > 0"
                    id="projects-container"
                    class="mx-auto mt-12 grid max-w-lg gap-5 lg:max-w-6xl lg:grid-cols-3"
                >
                    <div
                        v-for="project in this.projects"
                        :key="project.id"
                        class="relative flex flex-col overflow-hidden rounded-lg border shadow-lg"
                    >
                        <div class="flex-shrink-0">
                            <img
                                v-if="project.external_image_link"
                                :src="project.external_image_link"
                                class="h-48 w-full object-cover"
                            />
                            <img
                                v-else
                                :src="projectImage(project)"
                                class="h-48 w-full object-cover"
                                :alt="imageAlt(project)"
                            />
                        </div>
                        <div class="flex flex-1 flex-col justify-between bg-sky-600 bg-opacity-25 p-6">
                            <div class="flex-1">
                                <p
                                    v-for="tech of project.content?.technology"
                                    :key="tech.name"
                                    class="framework-bubble mr-2 inline-flex rounded-full px-2 text-xs font-semibold leading-5"
                                    :style="technologyStyle(tech)"
                                >
                                    {{ tech.name }}
                                </p>

                                <div class="mt-2 block">
                                    <p class="text-xl font-semibold text-sky-50">{{ project.title }}</p>
                                    <p class="text-sm font-semibold text-sky-200">{{ project.scope }}</p>
                                    <p class="mt-3 text-base text-sky-100">{{ project.excerpt }}</p>
                                </div>
                            </div>
                            <div class="mt-6 flex items-center">
                                <button
                                    @click="
                                        showModal = true;
                                        projectSelected = project;
                                    "
                                    class="inline-flex items-center text-blue-500"
                                    target="_blank"
                                >
                                    View More
                                    <svg
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        class="ml-2 h-4 w-4"
                                        viewBox="0 0 24 24"
                                    >
                                        <path d="M5 12h14M12 5l7 7-7 7"></path>
                                    </svg>
                                </button>
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
    </div>

    <!-- Modal -->
    <Modal :show="showModal" @close="showModal = false">
        <div>
            <div class="flex-shrink-0">
                <img
                    v-if="projectSelected.external_image_link"
                    :src="projectSelected.external_image_link"
                    class="w-full object-cover"
                />
                <img
                    v-else
                    :src="projectImage(projectSelected)"
                    class="w-full object-cover"
                    :alt="imageAlt(projectSelected)"
                />
            </div>
            <div class="flex flex-1 flex-col justify-between bg-white p-6">
                <div class="flex-1">
                    <p
                        v-for="tech of projectSelected.content?.technology"
                        :key="tech.name"
                        class="framework-bubble mr-2 inline-flex rounded-full px-2 text-xs font-semibold leading-5"
                        :style="technologyStyle(tech)"
                    >
                        {{ tech.name }}
                    </p>

                    <div class="mt-2 block">
                        <p class="text-2xl font-semibold text-gray-900">{{ projectSelected.title }}</p>
                        <p class="text-sm font-semibold text-gray-400">{{ projectSelected.scope }}</p>

                        <div class="mt-2 flex items-center">
                            <a
                                :href="projectSelected.external_link"
                                class="inline-flex items-center text-red-500"
                                target="_blank"
                                >Visit Project
                                <svg
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    class="ml-2 h-4 w-4"
                                    viewBox="0 0 24 24"
                                >
                                    <path d="M5 12h14M12 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>

                        <p class="mt-6 text-base text-gray-600" v-for="bullet in projectSelected.content?.bullets">
                            - {{ bullet }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </Modal>
</template>

<script>
import Modal from "@/Components/Modal.vue";
import { Head } from "@inertiajs/vue3";
import AnimatedButtonMenu from "@/Components/AnimatedButtonMenu.vue";
import { renderStarfield } from "@/three/space";

const projectImages = import.meta.glob("../../images/projects/*.{jpg,jpeg,png,webp}", {
    eager: true,
    import: "default",
});

const resolveImage = (imageMap, name, folder) => {
    if (!name) {
        return "";
    }

    const candidates = [
        `../../images/${folder}/${name}.webp`,
        `../../images/${folder}/${name}.jpg`,
        `../../images/${folder}/${name}.jpeg`,
        `../../images/${folder}/${name}.png`,
    ];

    for (const candidate of candidates) {
        if (imageMap[candidate]) {
            return imageMap[candidate];
        }
    }

    return "";
};

function padZero(str, len) {
    len = len || 2;
    const zeros = new Array(len).join('0');

    return (zeros + str).slice(-len);
}

function invertColor(hex, bw) {
    if (hex.indexOf('#') === 0) {
        hex = hex.slice(1);
    }

    if (hex.length === 3) {
        hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
    }

    if (hex.length !== 6) {
        throw new Error('Invalid HEX color.');
    }

    let r = parseInt(hex.slice(0, 2), 16),
        g = parseInt(hex.slice(2, 4), 16),
        b = parseInt(hex.slice(4, 6), 16);

    if (bw) {
        return r * 0.299 + g * 0.587 + b * 0.114 > 186 ? '#000000' : '#FFFFFF';
    }

    r = (255 - r).toString(16);
    g = (255 - g).toString(16);
    b = (255 - b).toString(16);

    return '#' + padZero(r) + padZero(g) + padZero(b);
}

export default {
    components: {
        Head,
        AnimatedButtonMenu,
        Modal,
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
        };
    },
    methods: {
        imageAlt(project) {
            return `Alex Younger Project Gallery ${project.title} Cover Image`;
        },
        projectImage(project) {
            return resolveImage(projectImages, project?.properties?.image_name, "projects");
        },
        technologyStyle(technology) {
            const backgroundColor = technology.color || '#64748b';

            return {
                backgroundColor,
                color: invertColor(backgroundColor, true),
            };
        },
    },
    mounted() {
        renderStarfield();
    },
};
</script>

<style>
#projects-container img,
#projects-container picture {
    max-width: 1000px;
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
