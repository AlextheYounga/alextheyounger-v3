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

        $homeHtml = file_get_contents('storage/app/public/home.html');
        $aboutHtml = file_get_contents('storage/app/public/about.html');
        $contactHtml = file_get_contents('storage/app/public/contact.html');
        $languageBarHtml = file_get_contents('storage/app/public/language-bar.html');

        /**
         * Home
         */
        $homeTitle = [
            'view' => 'Home',
            'name' => 'Home Tagline',
            'key' => 'homeTagline',
            'html_id' => 'tagline',
            'content' => 'Software Engineer, Data Scientist, Entrepreneur',
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

        $bio['content'] = $homeHtml;

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
        $about['content'] = $aboutHtml;

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

        $languageBar['content'] = $languageBarHtml;

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

        $contact['content'] = $contactHtml;
        \App\Models\PageContent::create($contact);

        // --------- End Contact Description
    }
}
