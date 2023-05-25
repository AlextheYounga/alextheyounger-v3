<template>
    <div id="language-bar" class="text-lg mx-auto pb-4 px-8">
        <div class="bar w-full mb-4">
            <template v-if="typeof language_stats === 'object'">
                <template v-for="(val, lang) in language_stats">
                    <span :style="{ width: val + '%' }" :class="['bar-item', slugifyLanguage(lang)]"></span>
                </template>
            </template>
        </div>
        <ul class="flex flex-wrap">
            <template v-if="typeof language_stats === 'object'">
                <template v-for="(val, lang) in language_stats">
                    <li class="lang-item w-48">
                        <div class="item-wrapper inline-flex flex-nowrap no-underline text-sm mr-3">
                            <svg :class="['octicon', slugifyLanguage(lang), 'octicon-dot-fill', 'mr-2']" viewbox="0 0 16 16" version="1.1" width="16" height="16" aria-hidden="true">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8z"></path>
                            </svg>
                            <span class="lang-name mr-1">{{ lang }}</span>
                            <span>{{ val }}%</span>
                        </div>
                    </li>
                </template>
            </template>
        </ul>
        <div class="italic mt-6">
            <small>
                These stats are not arbitrary, they're pulled directly from Github and represent real lines of code.
            </small>
        </div>
    </div>
</template>
  
<script>
export default {
    props: {
        language_stats: {
            type: Object,
            required: true,
        },
    },
    methods: {
        slugifyLanguage(string) {
            if (string.includes("+")) {
                return string.replace(/\+/g, "plus");
            }

            string = string.replace(/[^a-zA-Z0-9]+/g, "-");
            return string.toLowerCase();
        }
    },
};
</script>
  