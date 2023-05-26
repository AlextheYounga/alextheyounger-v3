<template>
    <footer class="relative py-4 text-center w-full bg-white">
        <div class="container mx-auto">
            <p class="block normal-font text-center text-sm">
                I don't believe in copyright, take whatever you want. <a href="https://alextheyounger.me" class="burgandy">alextheyounger.me</a> by Alex Younger. This site was built with Ruby on Rails and TailwindCSS.
            </p>
            <p class="block normal-font text-center text-sm">Fractal terrain generator javascript adapted from <a href="https://codepen.io/ge1doot/pen/ZPpWaR" rel="nofollow" target="_blank" class="text-burgandy">https://codepen.io/ge1doot/pen/ZPpWaR</a>.</p>
            <div class="flex justify-center mx-auto py-4 text-center w-1/2">
                <a v-for="link in links" :href="link.url" class="w-16 no-underline burgandy" :class="link.icon.iconName">
                    <img :src="link.icon" width="25" height="25" :alt="link.alt" />
                </a>
            </div>
        </div>
    </footer>
</template>
  
<script>

const twitterIcon = 'https://img.icons8.com/color/48/twitter--v1.png'
const linkedIcon = 'https://img.icons8.com/color/48/linkedin.png'
const githubIcon = 'https://img.icons8.com/color/48/github--v1.png'
const mediumIcon = 'https://img.icons8.com/color/48/medium--v2.png'
const emailIcon = 'https://img.icons8.com/emoji/48/e-mail.png'

const links = [
    { name: 'Twitter', url: 'https://github.com/AlextheYounga', icon: twitterIcon, alt: "twitter--v1" },
    { name: 'LinkedIn', url: 'https://www.linkedin.com/in/alexyounger/', icon: linkedIcon, alt: 'linkedin' },
    { name: 'Github', url: 'https://github.com/AlextheYounga', icon: githubIcon, alt: 'github' },
    { name: 'Medium', url: 'https://medium.com/@AlextheYounger', icon: mediumIcon, alt: 'medium--v2' },
    { name: 'Email', url: 'mailto:alex@alextheyounger.me', icon: emailIcon, alt: 'e-mail' }
    // { name: 'Codepen', url: 'https://codepen.io/alextheyounger/', icon: codepenIcon },
    // { name: 'Substack', url: 'https://apeswithnukes.substack.com/', icon: substackIcon },
];


export default {

    data() {
        return {
            links
        };
    },
};
</script>
  
<style>
/* Add your styles here */
</style>
  