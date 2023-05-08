<template>
    <div class="wrapper w-5/6 sm:w-3/5 mx-auto max-w-3xl">
        <section class="hero mt-16 w-1/2 sm:w-1/3 mx-auto sm:mt-0 mb-4 relative home-content js--fadeInLeft">
            <picture>
                <!-- <source srcset="<%= image_path("neonheadshot.jpg.webp") %>" type="image/webp">
                <source srcset="<%= image_path("neonheadshot.jpg") %>" type="image/jpg">
                <img src="<%= image_path("neonheadshot.jpg") %>" class="headshot flex sm:mt-10 mx-auto relative text-center" alt="alex younger developer marketing about me"/> -->
            </picture>
        </section>

        <section class="title mb-8 bg-white border shadow rounded relative home-content js--fadeInLeft">
            <h1 class="fancy-font text-5xl text-black text-center">Alex Younger</h1>
            <p class="fancy-font py-2 text-black text-center text-lg">Software Engineer, Data Scientist, Entrepreneur</p>
        </section>

        <!-- Skills -->
        <section class="bg-white border my-8 p-2 py-8 shadow relative rounded z-10 home-content js--fadeInLeft">
            <div class="language-stats">
                <h2 class="mb-3 text-3xl text-gray-900 fancy-font sm:px-8">Skills</h2>
                <!-- language bar -->
            </div>
        </section>

        <!-- Description -->
        <section class="home-description relative bg-white border mt-24 mb-8 p-8 home-content js--fadeInLeft shadow">
            <div class="absolute top-0 left-0">
                <picture>
                    <!-- <source srcset="<%= image_path("bridge-standing.jpg.webp") %>" type="image/webp" class="flex force-vertical-align mx-auto relative text-center w-full">
        <source srcset="<%= image_path("bridge-standing.jpg") %>" type="image/jpg">
        <img src="<%= image_path("bridge-standing.jpg") %>" class="flex force-vertical-align mx-auto relative text-center w-full" alt="alex younger developer marketing about me"/> -->
                </picture>
            </div>
            <p class="home-quote italic mt-28 text-sm w-full sm:mt-0 sm:text-right sm:pb-6 sm:ml-auto sm:w-2/3">"The man who grasps principles can successfully handle his own methods. The man who tries methods, ignoring principles is sure to have trouble" -Ralph Waldo Emerson</p>
            <div class="description leading-normal ml-auto normal-font mt-6">
                <p class="py-2">I currently work for <a class="text-blue-600 hover:text-blue-900" href="https://marketplacer.com/">Marketplacer</a>,
                    building connector APIs that integrate with major ecommerce platforms.</p>
                <p class="py-2">I've built and maintained websites for an extensive list of small and large companies including
                    <a class="text-blue-600 hover:text-blue-900" href="https://www.bluehawaiian.com/en">Blue Hawaiian Helicopters</a>,
                    <a class="text-blue-600 hover:text-blue-900" href="https://www.intelligentoffice.com/">Intelligent Office</a>, and
                    <a class="text-blue-600 hover:text-blue-900" href="https://www.intelligentoffice.com/">Rugdoctor</a>.
                </p>
                <p class="py-2">I'm building an awesome MVC framework atop Electron called <a class="text-blue-600 hover:text-blue-900" href="https://github.com/AlextheYounga/vultron-js">Vultron JS</a>.
                    I built that Vultron to build a personal budget/accounting desktop app called AmassWealth.io (Work in Progress).</p>

                <p>My <a class="text-blue-600 hover:text-blue-900" href="https://twitter.com/hazlittresearch">Twitter bot</a> automatically posts Congressional stock transactions so you can trade like the people who control the market. Follow me.</p>
                <p class="py-2">Check out my latest
                    <a href="<%= projects_path %>" class="text-blue-600 hover:text-blue-900">projects</a>.
                </p>
            </div>
        </section>

        <!-- Projects List -->
        <section class="projects-home p-2 sm:p-8 relative mb-8 bg-white border home-content js--fadeInLeft">
            <h3 class="text-3xl text-gray-900 mb-4 fancy-font mx-auto">Projects</h3>
            <div class="overflow-hidden bg-white border shadow sm:rounded-md">
                <ul role="list" id="projects-container" class="divide-y divide-gray-200 overflow-scroll">
                    <!-- foreach projects -->
                    <li>
                        <a href="<%= project.project_link %>" class="block hover:bg-gray-50">
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <p class="truncate text-lg font-medium text-blue-600 normal-font">
                                        <!-- project title -->
                                    </p>
                                    <div class="ml-2 flex flex-shrink-0">
                                        <p class="framework-bubble inline-flex rounded-full px-2 text-xs font-semibold leading-5" data-framework="<%= project.framework %>">
                                            <!-- project framework -->
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-2 sm:flex sm:justify-between">
                                    <div class="sm:flex">
                                        <p class="flex items-center text-sm text-gray-500 truncate">
                                            <!-- project excerpt -->
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                    <!-- end loop -->
                </ul>
            </div>
            <a href="<%= projects_path %>" class="bg-white border block border-blue-800 hover:bg-blue-900 hover:text-blue-100 mx-auto text-center no-underline normal-font py-3 rounded shadow-lg text-blue-900 w-48 mt-12">
                See More
            </a>
        </section>
    </div>
</template>

<script>
</script>