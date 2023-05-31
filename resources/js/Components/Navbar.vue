<template>
    <!-- Desktop -->
    <div class="desktop-menu hidden z-50 sm:block fixed w-full bg-stone-100">
        <div class="container left-0 mx-auto px-5 right-0 top-0">
            <div class="flex justify-between w-full">
                <div class="w-1/3">
                    <Link href="/" class="text-burgandy font-semibold text-xl hover:text-red-600">Home</Link>
                </div>

                <div class="w-2/3 ml-auto flex justify-end">
                    <Link :href="route('pages.books')" class="text-burgandy font-semibold text-xl hover:text-red-600 pr-4">Reading List <span aria-hidden="true">&#x1F4DA;</span></Link>
                    <Link :href="route('pages.projects')" class="text-burgandy font-semibold text-xl hover:text-red-600 pr-4">Projects <span aria-hidden="true">&#x1F5A5;</span></Link>
                    <button @click="terrain.redraw" class="cursor-pointer text-burgandy font-semibold text-xl hover:text-red-600">Redraw Terrain <span aria-hidden="true">&#x1F3D4;</span></button>
                </div>
            </div>
        </div>
    </div>
    <!-- Mobile -->
    <nav class="mobile sm:hidden">
        <button @click="toggleMenu">
            <div class="mobile-nav-button absolute m-6 right-0 top-0 z-50">
                <div class="mobile-nav-button__line w-full relative bg-stone-800"></div>
                <div class="mobile-nav-button__line w-full relative bg-stone-800"></div>
                <div class="mobile-nav-button__line w-full relative bg-stone-800"></div>
            </div>
        </button>

        <div class="mobile-menu block max-w-md w-full h-screen top-0 absolute bg-transparent z-negative">
            <ul class="relative p-0 mx-auto text-center hidden">
                <li class="py-4">
                    <Link href="/" class="text-burgandy font-semibold text-xl w-42 hover:text-red-600">Home</Link>
                </li>
                <li class="py-4">
                    <Link :href="route('pages.books')" class="text-burgandy font-semibold text-xl hover:text-red-600">Reading List <span aria-hidden="true">&#x1F4DA;</span></Link>
                </li>
                <li class="py-4">
                    <Link :href="route('pages.projects')" class="text-burgandy font-semibold text-xl hover:text-red-600">Projects <span aria-hidden="true">&#x1F5A5;</span></Link>
                </li>
                <li class="py-4">
                    <button @click="terrain.redraw" class="text-burgandy font-semibold text-xl hover:text-red-600">Redraw Terrain <span aria-hidden="true">&#x1F3D4;</span></button>
                </li>
            </ul>
        </div>
    </nav>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { getCurrentInstance } from 'vue';

const terrain = getCurrentInstance().appContext.config.globalProperties.$terrain

function toggleMenu() {
    const pageContainer = document.getElementById('page-wrapper');

    const mobileMenu = document.querySelector('.mobile-menu');
    const footer = document.getElementById('footer');
    const menuLines = document.querySelectorAll('.mobile-nav-button__line');

    menuLines[0].classList.toggle('mobile-nav-button__line--1');
    menuLines[1].classList.toggle('mobile-nav-button__line--2');
    menuLines[2].classList.toggle('mobile-nav-button__line--3');

    mobileMenu.classList.toggle('mobile-menu--open');
    mobileMenu.classList.toggle('z-negative');
    mobileMenu.classList.toggle('z-40');

    pageContainer.classList.toggle('hidden')
    footer.classList.toggle('relative')
    footer.classList.toggle('absolute')
    footer.classList.toggle('bottom-0')
    mobileMenu.querySelector('ul').classList.toggle('hidden');
}

</script>

<style>
.z-negative {
    z-index: -1;
}

.mobile-nav-button {
    width: 28px;
    cursor: pointer;
}

.mobile-nav-button .mobile-nav-button__line {
    height: 2px;
    transition: 1s ease;
}

.mobile-nav-button .mobile-nav-button__line:nth-of-type(2) {
    margin: 0.4rem 0;
}

.mobile-nav-button .mobile-nav-button__line--1 {
    transform: rotate(45deg);
    top: 13px;
    position: absolute;
}

.mobile-nav-button .mobile-nav-button__line--2 {
    display: none;
}

.mobile-nav-button .mobile-nav-button__line--3 {
    transform: rotate(135deg);
    top: 13px;
    position: absolute;
}

.mobile-menu {
    transition: 0.6s ease;
    opacity: 0;
}

.mobile-menu ul {
    top: 50%;
    transform: translateY(-50%);
}

.mobile-menu ul li {
    list-style: none;
}

.mobile-menu ul li a {
    text-decoration: none;
    overflow: hidden;
}

.mobile-menu ul li a:after {
    content: '';
    background: black;
    width: 100%;
    height: 100%;
    position: absolute;
    right: -100%;
    top: 0;
    z-index: -1;
    transition: 0.4s ease;
}

.mobile-menu--open {
    right: 0;
    opacity: 1;
}

/* @media (max-width: 768px) {
    nav.desktop {
        display: none !important;
    }
}

@media (min-width: 767px) {
    nav.mobile {
        display: none !important;
    }
} */
</style>