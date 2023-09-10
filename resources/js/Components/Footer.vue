<template>
    <footer id="footer" class="relative py-4 text-center w-full bg-white">
        <div class="container mx-auto px-2">
            <div id="footer-description"></div>
            <div class="flex justify-center mx-auto py-4 text-center w-full sm:w-2/3">
                <a v-for="link in links" :href="link.url" class="w-10 sm:w-16 no-underline burgandy" target="_blank">
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
const emailIcon = 'https://img.icons8.com/emoji/48/e-mail.png'
const resumeIcon = 'https://img.icons8.com/nolan/64/resume.png'
// const codepenIcon = 'https://img.icons8.com/ios/50/codepen.png'
// const mediumIcon = 'https://img.icons8.com/color/48/medium--v2.png'
// const substackIcon = 'https://substackcdn.com/image/fetch/w_96,c_limit,f_auto,q_auto:good,fl_progressive:steep/https%3A%2F%2Fbucketeer-e05bbc84-baa3-437e-9518-adb32be77984.s3.amazonaws.com%2Fpublic%2Fimages%2Fba81cfff-7bc5-4aef-866e-864d0942c42d_1000x1000.png'

const links = [
    { name: 'Twitter', url: 'https://github.com/AlextheYounga', icon: twitterIcon, alt: "twitter--v1" },
    { name: 'LinkedIn', url: 'https://www.linkedin.com/in/alexyounger/', icon: linkedIcon, alt: 'linkedin' },
    { name: 'Github', url: 'https://github.com/AlextheYounga', icon: githubIcon, alt: 'github' },
    { name: 'Email', url: 'mailto:alex@alextheyounger.me', icon: emailIcon, alt: 'e-mail' },
    { name: 'Resume', url: 'https://docs.google.com/document/d/1xaebeC0PrJee5jfqY1wSgAbTAqwNHdstd-Zer0BVZww/edit?usp=sharing', icon: resumeIcon, alt: 'resume' },
    // { name: 'Codepen', url: 'https://codepen.io/alextheyounger/', icon: codepenIcon, alt: 'codepen' },
    // { name: 'Medium', url: 'https://medium.com/@AlextheYounger', icon: mediumIcon, alt: 'medium--v2' },
    // { name: 'Substack', url: 'https://apeswithnukes.substack.com/', icon: substackIcon, alt: 'substack' },
];

const defaultFooterCopy = `Not a fan of copyright, take whatever you want. Here's the <a class="text-burgandy" href="https://github.com/AlextheYounga/alextheyounger-v3">repo</a> for this site. <a href="https://alextheyounger.me" class="text-burgandy">alextheyounger.me</a> by Alex Younger.`

export default {
    data() {
        return {
            links
        };
    },
    mounted() {
        // Ajax fetch data
        axios.get('/footer/setup')
            .then(response => {
                const footerContent = response.data?.footer;
                const footerCopy = footerContent ?? defaultFooterCopy;

                document.getElementById(footerContent.html_id).innerHTML = footerCopy.content;
            })
            .catch(error => {
                console.error('Error fetching data:', error);
                this.threads = [];
            });
    }
};
</script>