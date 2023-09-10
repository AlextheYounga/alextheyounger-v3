<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PageContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        \App\Models\PageContent::truncate();

        /**
        * Home
        */
        $homeTitle = [
            'view' => 'Home',
            'name' => 'Home Tagline',
            'key' => 'homeTagline',
            'html_id' => 'tagline',
            'content' => 'Software Engineer, Data Scientist, Entrepreneur'
        ];

        \App\Models\PageContent::create($homeTitle);
        //------------- End Home Title

        /**
        * Home Bio
        */
        $bio = [
            'view' => 'Home',
            'name' => 'Home Bio',
            'key' => 'bio',
            'html_id' => 'home-bio',
        ];

        $bio['content'] = <<<HTML
<h2 class="mb-3 text-3xl text-gray-700">Bio</h2>
<p class="py-2 text-gray-700">I am a professional software engineer writing in multiple languages, doing web development, drone programming, and AI. I love freedom, adrenaline, and working to improve the world any way I can.</p>
HTML;

        \App\Models\PageContent::create($bio);
        //------------- End Home Bio

        /**
        * Home Quote
        */
        $homeQuote = [
            'view' => 'Home',
            'name' => 'Home Quote',
            'key' => 'quote',
            'html_id' => 'home-quote',
            'content' => 'Software Engineer, Data Scientist, Entrepreneur'
        ];

        $homeQuote['content'] = <<<HTML
<p class="italic text-md">"The man who grasps principles can successfully handle his own methods. The man who tries methods, ignoring principles is sure to have trouble"</p>
<p class="italic pt-4"> -Ralph Waldo Emerson</p>
HTML;

        \App\Models\PageContent::create($homeQuote);
        //------------- End Home Quote


        /**
        * About Description
        */
        $aboutDescription = [
            'view' => 'Home',
            'name' => 'About Description',
            'key' => 'aboutDescription',
            'html_id' => 'home-description',
        ];
        $aboutDescription['content'] = <<<HTML
<h2 class="mb-3 text-3xl text-gray-700">About Me</h2>
<div class="description leading-normal ml-auto">
<p class="py-2 text-gray-700">I recently left <a class="text-burgandy hover:text-red-600 font-semibold" target="_blank" href="https://marketplacer.com/">Marketplacer</a>, which allows enterprises to manage a fleet of eCommerce websites from a unified dashboard, incorporating major platforms like Shopify, Adobe Commerce, Salesforce, and BigCommerce. Here I built critical Shopify and Adobe Commerce connectors, enabling seamless data flow through an extensive Rails API system.
</p>

<p class="py-2 text-gray-700">I've built and maintained websites for an extensive list of small and large companies including
<a class="text-burgandy hover:text-red-600 font-semibold" href="https://www.bluehawaiian.com/en" target="_blank">Blue Hawaiian Helicopters</a>,
<a class="text-burgandy hover:text-red-600 font-semibold" href="https://www.intelligentoffice.com/" target="_blank">Intelligent Office</a>, and
<a class="text-burgandy hover:text-red-600 font-semibold" href="https://www.rugdoctor.com/" target="_blank">Rugdoctor</a>.
</p>

<p class="py-2 text-gray-700">I'm the Software Architect as well as a consultant at the startup <a class="text-burgandy hover:text-red-600 font-semibold" href="https://www.learnarena.com/" target="_blank">Learn Arena</a>. As a founding developer for Learn Arena, a groundbreaking competitive learning platform, I spearheaded its transformation from a hobbyist React and Firebase app to a scalable NextJS application. Learn Arena is the first-of-its-kind competitive learning platform, where students with the greatest academic achievement have the potential to win back the costs of the course, or even make a profit.</p>

<p class="py-2 text-gray-700">I recently launched a unique application that enables LLM models to autonomously interact, simulating intriguing AI-generated conversations. called <a class="text-burgandy hover:text-red-600 font-semibold" href="https://www.gptconversations.app/" target="_blank"> GPT Conversations</a>. Currently it's set up to continuously talk to itself on "phenomena lacking clear explanations but solvable using all human knowledge."
</p>
<p class="py-2 text-gray-700">Check out my latest
<Link class="text-burgandy hover:text-red-600 font-semibold" :href="route('pages.projects')">projects</Link>
</p>
<p>Or my <a class="text-burgandy hover:text-red-600 font-semibold" href="https://docs.google.com/document/d/1xaebeC0PrJee5jfqY1wSgAbTAqwNHdstd-Zer0BVZww/edit?usp=sharing" target="_blank">Resume</a>.</p>
</div>
HTML;

        \App\Models\PageContent::create($aboutDescription);
        //--------- End About Description


        /**
        * Language Bar
        */
        $languageBar = [
            'view' => 'LanguageBar',
            'name' => 'Language Description',
            'key' => 'languageDescription',
            'html_id' => 'language-description',
        ];

        $languageBar['content'] = <<<HTML
<p class="text-sm italic pb-1">These statistics are not random. They were calculated using the <a class="text-burgandy hover:text-red-600 font-semibold" href="https://github.com/github-linguist/linguist">Github Linguist package</a> and accurately represent the number of bytes of code.</p>
<p class="text-sm italic">You can see how I did this <a class="text-burgandy hover:text-red-600 font-semibold" href="https://github.com/AlextheYounga/alextheyounger-v3/blob/master/app/Http/Services/GithubLinguistService.php">here</a>. You can even see the list of <a class="text-burgandy hover:text-red-600 font-semibold" href="https://github.com/AlextheYounga/alextheyounger-v3/blob/master/storage/data/repositories.json">repositories</a> I scanned from my machine to generate these statistics. I either legally own or have made substantial contributions to these projects. Most of them (but not all) can be found on my <a class="text-burgandy hover:text-red-600 font-semibold" href="https://github.com/AlextheYounga">Github</a>.</p>
HTML;

        \App\Models\PageContent::create($languageBar);
        // --------- End Language Description



        /**
        * Footer
        */
        $footer = [
            'view' => 'Footer',
            'name' => 'Footer Description',
            'key' => 'footer',
            'html_id' => 'footer-description',
        ];

        $footer['content'] = <<<HTML
<p class="block normal-font text-center text-sm">Not a fan of copyright, take whatever you want. Here's the <a class="text-burgandy" href="https://github.com/AlextheYounga/alextheyounger-v3">repo</a> for this site. <a href="https://alextheyounger.me" class="text-burgandy">alextheyounger.me</a> by Alex Younger.</p>
<p class="block normal-font text-center text-sm mt-2">This site was built with PHP Laravel with Orchid, InertiaJS, Vue3, and TailwindCSS.</p>
HTML;
        \App\Models\PageContent::create($footer);

        // --------- End Footer

    }
}
