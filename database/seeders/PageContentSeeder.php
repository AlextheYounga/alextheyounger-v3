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
<p>Programmer writing in multiple languages, doing web development, <a href="https://youtu.be/VTdS4-Tas6E?si=VuC2DKMPXHrL9r0v">drone programming</a>, and AI LLM development. I love freedom, adrenaline, and working to improve the world any way I can.</p>
HTML;

        \App\Models\PageContent::create($bio);
        //------------- End Home Bio


        /**
        * About Description
        */
        $about = [
            'view' => 'Home',
            'name' => 'About',
            'key' => 'about',
            'html_id' => 'about',
        ];
        $about['content'] = <<<HTML
<p>For the past 2 years, I have poured my soul into <a href="https://www.learnarena.com/" target="_blank">Learn Arena</a>. Learn Arena is the first-of-its-kind competitive learning platform, where you can get paid to learn! Imagine, you put money
    into a pot, (the cost of the course), and after completing the course, you can compete with other users in a final challenge to test your knowledge. The highest scores can make their money back and even profit! I spearheaded its transformation
    from a hobbyist React and Firebase app (avoid at all costs) to a scalable NextJS application with a SQL database. While at LearnArena, I built one of the world's first AI Course Creators, Learn Infinite, which can take any topic and generate 60
    hours of course material, complete with practice questions and even code editor questions on programming courses.
</p>
<p>I worked at <a target="_blank" href="https://marketplacer.com/">Marketplacer</a> for a little over a year, and I enjoyed my time there. I was surrounded by very smart people and was able to quickly advance my skills. Marketplacer allows
    enterprises to manage a fleet of eCommerce websites from a unified dashboard, incorporating major platforms like Shopify, Adobe Commerce,
    Salesforce, and BigCommerce. Here I built critical Shopify and Adobe Commerce connectors, enabling seamless data flow through an extensive Rails API system.
</p>

<p>I learned the ropes of programming working at <a href="https://www.izoox.com/">Izoox</a>, a web hosting and web development company.
    I love this company and its owner, my first real mentor, who is also the founder of <a href="https://beardsorcery.com/">Beard Sorcery</a>. Along with highly competitive web hosting prices, this company is like a house flipper for websites.
    Here I built and maintained websites for an extensive list of small and large companies including
    <a href="https://www.bluehawaiian.com/en" target="_blank">Blue Hawaiian Helicopters</a>,
    <a href="https://www.intelligentoffice.com/" target="_blank">Intelligent Office</a>, and
    <a href="https://www.rugdoctor.com/" target="_blank">Rugdoctor</a>.
</p>

<p>Check out my latest <a :href="/projects">projects</a></p>
<p>Or my <a href="https://docs.google.com/document/d/1xaebeC0PrJee5jfqY1wSgAbTAqwNHdstd-Zer0BVZww/edit?usp=sharing" target="_blank">Resume</a>.</p>
HTML;

        \App\Models\PageContent::create($about);
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
<p class="italic pb-1">These statistics are not random. They were calculated using the <a href="https://github.com/github-linguist/linguist">Github Linguist package</a> and accurately represent the number of bytes of code.</p>
<p class="italic">You can see how I did this <a href="https://github.com/AlextheYounga/alextheyounger-v3/blob/master/app/Http/Services/GithubLinguistService.php">here</a>. You can even see the list of <a href="https://github.com/AlextheYounga/alextheyounger-v3/blob/master/storage/data/repositories.json">repositories</a> I scanned from my machine to generate these statistics. I either legally own or have made substantial contributions to these projects. Most of them (but not all) can be found on my <a href="https://github.com/AlextheYounga">Github</a>.</p>
HTML;

        \App\Models\PageContent::create($languageBar);
        // --------- End Language Description



        /**
        * Contact
        */
        $contact = [
            'view' => 'Home',
            'name' => 'Contact Description',
            'key' => 'contact',
            'html_id' => 'contact-description',
        ];

        $contact['content'] = <<<HTML
<p>Not a fan of copyright, take whatever you want. Here's the <a href="https://github.com/AlextheYounga/alextheyounger-v3">repo</a> for this site. <a href="https://alextheyounger.me">alextheyounger.me</a> by Alex Younger.</p>
<p>This site was built with PHP Laravel with Orchid, InertiaJS, Vue3, ThreeJS, and TailwindCSS.</p>
HTML;
        \App\Models\PageContent::create($contact);

        // --------- End Contact Description

    }
}
