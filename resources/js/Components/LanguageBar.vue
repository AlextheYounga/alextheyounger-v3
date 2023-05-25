<template>
    <div id="language-bar" class="text-lg mx-auto pb-4 px-8">
        <div class="bar w-full mb-4">
            <template v-if="typeof languages === 'object'">
                <template v-for="(val, lang) in languages">
                    <span :style="{ width: val + '%' }" :class="['bar-item', slugifyLanguage(lang)]"></span>
                </template>
            </template>
        </div>
        <ul class="flex flex-wrap">
            <template v-if="typeof languages === 'object'">
                <template v-for="(val, lang) in languages">
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
        <div class="italic mt-6 text-sm">
            <small>
                These stats are not arbitrary, they're pulled directly from Github and represent real lines of code.
            </small>
        </div>
    </div>
</template>
  
<script>
import languageColors from "../../data/language-colors.json";

export default {
    props: {
        languages: {
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
        },
        stringParameterize(str1) {
            if (str1.includes("+")) {
                return str1.trim().toLowerCase().replace(/[^a-zA-Z0-9 -]/, "").replace(/\s/g, "-").replace("+", "plus");
            }
            return str1.trim().toLowerCase().replace(/[^a-zA-Z0-9 -]/, "").replace(/\s/g, "-");
        },
        generateLanguageBarColors() {
            var langStats = document.getElementById("language-bar");
            if (typeof (langStats) != 'undefined' && langStats != null) {
                for (var _i = 0, _Object$entries = Object.entries(languageColors); _i < _Object$entries.length; _i++) {
                    var _Object$entries$_i = _Object$entries[_i];
                    let lang = _Object$entries$_i[0];
                    let color = _Object$entries$_i[1];
                    var barItems = document.querySelectorAll(".bar-item." + this.stringParameterize(lang));
                    var octicons = document.querySelectorAll(".octicon." + this.stringParameterize(lang));

                    if (typeof barItems != 'undefined' && barItems != null) {
                        for (var i = 0, length = barItems.length; i < length; i++) {
                            barItems[i].style.backgroundColor = color;
                        }
                    }

                    if (typeof octicons != 'undefined' && octicons != null) {
                        for (var i = 0; i < octicons.length; i++) {
                            octicons[i].style.color = color;
                        }
                    }
                }
            }
        }
    },
    mounted() {
        this.generateLanguageBarColors();
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

.language-stats #language-bar ul {
    list-style-type: none;
}

.language-stats #language-bar ul .octicon {
    fill: currentColor;
}
</style>