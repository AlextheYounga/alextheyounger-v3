<template>
    <div id="language-bar" class="mx-auto text-lg">
        <div class="bar relative mb-4 w-full">
            <template v-if="typeof languages === 'object'">
                <template v-for="language in languages">
                    <span
                        @mouseenter="highlightLanguage"
                        @mouseleave="highlightLanguage"
                        :style="{ width: language.width + '%', backgroundColor: language.color }"
                        :id="`bar-item-${language.properties?.slug}`"
                        class="bar-item"
                    >
                    </span>
                </template>
            </template>
        </div>
        <ul class="flex flex-wrap">
            <template v-if="typeof languages === 'object'">
                <template v-for="language in languages">
                    <li :ref="language.properties.slug" class="lang-item w-32 rounded-lg leading-6 sm:w-48">
                        <div class="item-wrapper mr-3 inline-flex flex-nowrap items-center text-sm no-underline">
                            <svg
                                :style="{ color: language.color }"
                                :class="['octicon', language.properties?.slug, 'octicon-dot-fill', 'mr-2']"
                                viewbox="0 0 16 16"
                                version="1.1"
                                width="16"
                                height="16"
                                aria-hidden="true"
                            >
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8z"></path>
                            </svg>
                            <span class="lang-name mr-1 text-xs font-bold text-white">{{ language.language }}</span>
                            <span class="percent text-gray-400">{{ language.width }}%</span>
                        </div>
                    </li>
                </template>
            </template>
        </ul>
        <div class="mt-6">
            <div id="language-description" class="block" v-html="this.descriptionContent"></div>
            <div class="stats w-full pt-3 text-sm">
                <p>
                    Repos Scanned:
                    <a class="font-semibold text-sky-400 hover:text-blue-600" :href="scriptUrl">{{
                        repoStats.count
                    }}</a>
                </p>
                <p>
                    Repos Compressed Size:
                    <a class="font-semibold text-sky-400 hover:text-blue-600" :href="scriptUrl"
                        >{{ repoStats.size }}{{ repoStats.scale }}</a
                    >
                </p>
            </div>
        </div>
    </div>
</template>

<script>
const scriptUrl = "https://github.com/AlextheYounga/repo-linguist-scanner";

const defaultDescriptionContent = `<p class="text-sm italic pb-1">These statistics are not random. 
	This was calculated using the <a class="text-sky-400 hover:text-blue-600 font-semibold" href="https://github.com/github-linguist/linguist">Github Linguist package</a> and accurately represent bytes of code.</p>
	<p class="text-sm italic">You can see how I did this <a class="text-sky-400 hover:text-blue-600 font-semibold" href="${scriptUrl}">here</a>. Many of these repos (but certainly not all) can be found on my <a class="text-sky-400 hover:text-blue-600 font-semibold" href="https://github.com/AlextheYounga">Github</a>.</p>`;

export default {
    data() {
        return {
            descriptionContent: defaultDescriptionContent,
            languages: [],
            scriptUrl,
            repoStats: {
                count: 0,
                size: 0,
                scale: "GB",
            },
        };
    },
    methods: {
        highlightLanguage(event) {
            const id = event.target.id;
            const slug = id.split("bar-item-")[1];
            const langItem = this.$refs[slug][0];
            langItem.classList.toggle("glowy");
        },
    },
    mounted() {
        // Ajax fetch data
        axios
            .get("/api/languages/stats")
            .then((response) => {
                this.languages = response.data.languages;
                this.repoStats = response.data.repoStats;
            })
            .catch((error) => {
                console.error("Error fetching data:", error);
            });
    },
};
</script>

<style>
#language-bar .bar {
    display: flex;
    height: 8px;
    overflow: hidden;
    background-color: #e1e4e8;
    border-radius: 6px;
}

.project-count {
    font-size: 10px;
    margin-left: -3px;
    padding: 0 4px 12px 0;
}

.glowy {
    /* background-color: #f1f8ff; */
    text-shadow:
        0 0 10px #fff,
        0 0 20px #fff,
        0 0 30px #0ea5e9,
        0 0 40px #0ea5e9,
        0 0 50px #0ea5e9,
        0 0 60px #0ea5e9,
        0 0 70px #0ea5e9;
}

#language-bar ul {
    list-style-type: none;
}

#language-bar ul .octicon {
    fill: currentColor;
}
</style>
