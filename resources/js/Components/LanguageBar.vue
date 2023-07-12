<template>
    <div id="language-bar" class="text-lg mx-auto pb-4 px-8">
        <div class="bar w-full mb-4">
            <template v-if="typeof languages === 'object'">
                <template v-for="language in languages">
                    <span @mouseenter="displayHint" @mouseleave="displayHint" :style="{ width: (language.width + '%'), backgroundColor: language.color }" :id="`bar-item-${language.properties?.slug}`" class="bar-item">
                        <p :id="`hint-${language.properties?.slug}`" class="bg-gray-300 opacity-50 invisible absolute top-16 text-black rounded text-xs z-20">
                            {{ `${language.language}: ${language.width}%` }}
                        </p>
                    </span>

                </template>
            </template>
        </div>
        <ul class="flex flex-wrap">
            <template v-if="typeof languages === 'object'">
                <template v-for="language in languages">
                    <li class="lang-item w-48">
                        <div class="item-wrapper inline-flex flex-nowrap no-underline text-sm mr-3">
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
            <div class="stats text-sm">
                <p>Projects Scanned: <a class="text-burgandy hover:text-red-600 font-semibold" href="https://github.com/AlextheYounga/alextheyounger-v3/blob/master/storage/data/repositories.json">{{ repoStats.count }}</a></p>
                <p>Projects Total Size: <a class="text-burgandy hover:text-red-600 font-semibold" href="https://github.com/AlextheYounga/alextheyounger-v3/blob/master/storage/data/repositories.json">{{ repoStats.size }}GB</a></p>
            </div>
            <h3 class="mt-6">What am I looking at?</h3>
            <p class="text-sm italic">These stats are not arbitrary. They're calculated using the <a class="text-burgandy hover:text-red-600 font-semibold" href="https://github.com/github-linguist/linguist">Github Linguist package</a> and represent real
                bytes of code.</p>
            <p class="text-sm italic">You can see how I did this <a class="text-burgandy hover:text-red-600 font-semibold" href="https://github.com/AlextheYounga/alextheyounger-v3/blob/master/app/Http/Services/GithubLinguistService.php">here</a>.
                You can even see all the <a class="text-burgandy hover:text-red-600 font-semibold" href="https://github.com/AlextheYounga/alextheyounger-v3/blob/master/storage/data/repositories.json">repos</a> I scanned from my machine to create this, all
                of which I either have legal ownership of or significantly contributed to the project, and many (but not all) can be found on my <a class="text-burgandy hover:text-red-600 font-semibold" href="https://github.com/AlextheYounga">Github</a>.</p>
        </div>
    </div>
</template>
  
<script>
export default {
    props: {
        languages: {
            type: Object,
            required: true,
        },
        repoStats: {
            type: Object,
            required: true,
        },
    },
    methods: {
        displayHint(event) {
            const id = event.target.id;
            const slug = id.split('-')[2];
            const hint = document.getElementById(`hint-${slug}`);
            hint.classList.toggle('invisible');
        },
    },

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

.language-stats #language-bar ul {
    list-style-type: none;
}

.language-stats #language-bar ul .octicon {
    fill: currentColor;
}
</style>