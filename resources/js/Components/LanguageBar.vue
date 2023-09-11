<template>
    <div id="language-bar" class="text-lg mx-auto pb-4 px-8">
        <div class="bar w-full mb-4">
            <template v-if="typeof languages === 'object'">
                <template v-for="language in languages">
                    <span @mouseenter="displayHint" @mouseleave="displayHint" :style="{ width: (language.width + '%'), backgroundColor: language.color }" :id="`bar-item-${language.properties?.slug}`" class="bar-item">
                        <p :id="`hint-${language.properties?.slug}`" class="bg-gray-300 opacity-70 invisible absolute top-16 text-black rounded text-xs z-20">
                            {{ `${language.language}: ${language.width}%` }}
                        </p>
                    </span>
                </template>
            </template>
        </div>
        <ul class="flex flex-wrap">
            <template v-if="typeof languages === 'object'">
                <template v-for="language in languages">
                    <li class="lang-item w-32 sm:w-48">
                        <div class="item-wrapper inline-flex flex-nowrap no-underline text-sm mr-3 items-center">
                            <svg :style="{ color: language.color }" :class="['octicon', language.properties?.slug, 'octicon-dot-fill', 'mr-2']" viewbox="0 0 16 16" version="1.1" width="16" height="16" aria-hidden="true">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8z"></path>
                            </svg>
                            <span class="lang-name mr-1">{{ language.language }}</span>
                            <span>{{ language.width }}%</span>
                        </div>
                    </li>
                </template>
            </template>
        </ul>
        <div class="mt-6">
            <div class="w-full flex-col-reverse flex md:flex-row justify-between items-end">
                <div class="w-full md:w-1/2">
                    <button @click="openDescription" class="mt-6 flex">
                        <span class="text-burgandy">
                            <QuestionMarkCircleIcon class="w-6 h-6 mr-1" />
                        </span>
                        <span class="text-burgandy">What am I looking at?</span>
                    </button>
                </div>
                <div class="w-full md:w-1/2 stats text-sm md:text-right">
                    <p>Projects Scanned: <a class="text-burgandy hover:text-red-600 font-semibold" href="https://github.com/AlextheYounga/alextheyounger-v3/blob/master/storage/data/repositories.json">{{ repoStats.count }}</a></p>
                    <p>Projects Total Size: <a class="text-burgandy hover:text-red-600 font-semibold" href="https://github.com/AlextheYounga/alextheyounger-v3/blob/master/storage/data/repositories.json">{{ repoStats.size }}GB</a></p>
                </div>
            </div>

            <div id="language-description" :class="[descriptionOpen ? 'block' : 'hidden', 'pt-3']"></div>
        </div>
    </div>
</template>
  
<script>
import {
    QuestionMarkCircleIcon
} from '@heroicons/vue/24/outline';

const defaultDescriptionContent = `<p class="text-sm italic pb-1">These statistics are not random. They were calculated using the <a class="text-burgandy hover:text-red-600 font-semibold" href="https://github.com/github-linguist/linguist">Github Linguist package</a> and
                    accurately
                    represent the number of bytes of code.</p>
                <p class="text-sm italic">You can see how I did this <a class="text-burgandy hover:text-red-600 font-semibold" href="https://github.com/AlextheYounga/alextheyounger-v3/blob/master/app/Http/Services/GithubLinguistService.php">here</a>.
                    You can even see the list of <a class="text-burgandy hover:text-red-600 font-semibold" href="https://github.com/AlextheYounga/alextheyounger-v3/blob/master/storage/data/repositories.json">repositories</a> I scanned from my machine to
                    generate these statistics. I either legally own or have made substantial contributions to these projects. Most of them (but not all) can be found on my <a class="text-burgandy hover:text-red-600 font-semibold"
                        href="https://github.com/AlextheYounga">Github</a>.
                </p>`

export default {
    components: {
        QuestionMarkCircleIcon,
    },
    data() {
        return {
            descriptionOpen: false,
            descriptionContent: defaultDescriptionContent,
            languages: [],
            repoStats: {
                count: 0,
                size: 0,
            }
        }
    },
    methods: {
        openDescription() {
            console.log(this.$data.descriptionOpen)
            this.$data.descriptionOpen = !this.$data.descriptionOpen;
        },
        displayHint(event) {
            const id = event.target.id;
            const slug = id.split('-')[2];
            const hint = document.getElementById(`hint-${slug}`);
            hint.classList.toggle('invisible');
        },

    },
    mounted() {
        // Ajax fetch data
        axios.get('/languages/setup')
            .then(response => {
                this.languages = response.data.languages;
                this.repoStats = response.data.repoStats;

                const descriptionContent = response.data.content.languageDescription
                document.getElementById(descriptionContent.html_id).innerHTML = descriptionContent.content;
            })
            .catch(error => {
                console.error('Error fetching data:', error);
                this.threads = [];
            });
    }

};
</script>
  
<style scoped>
.language-stats #language-bar .bar {
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

.language-stats #language-bar ul {
    list-style-type: none;
}

.language-stats #language-bar ul .octicon {
    fill: currentColor;
}
</style>