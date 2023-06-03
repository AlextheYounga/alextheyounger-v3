<template>
    <!-- Desktop -->
    <div class="desktop-menu hidden z-50 sm:block fixed w-full bg-stone-100">
        <div class="container left-0 mx-auto px-5 right-0 top-0">
            <div class="flex justify-between w-full">
                <div class="w-1/3">
                    <Link href="/" class="text-burgandy font-semibold text-xl hover:text-red-600">Home</Link>
                </div>

                <div class="w-2/3 ml-auto flex justify-end">
                    <Link :href="route('pages.books')" class="text-burgandy font-semibold text-xl hover:text-red-600 pr-6">Reading List <span
                        aria-hidden="true">&#x1F4DA;</span></Link>
                    <Link :href="route('pages.projects')" class="text-burgandy font-semibold text-xl hover:text-red-600 pr-6">Projects <span
                        aria-hidden="true">&#x1F5A5;</span></Link>
                    <button @click="terrain.redraw" class="cursor-pointer text-burgandy font-semibold text-xl hover:text-red-600">Redraw Terrain <span
                            aria-hidden="true">&#x1F3D4;</span></button>
                </div>
            </div>
        </div>
    </div>
    <!-- Mobile -->
    <nav class="mobile sm:hidden">
        <div @click="toggleMenu" id="hamburger" class="hamburger absolute top-0 right-0 z-50 h-5">
            <a id="hamburger-click" class="main-nav-toggle" href="#main-nav"><i>Menu</i></a>
        </div>

        <div class="mobile-menu block max-w-md w-full h-screen absolute bg-transparent z-negative">
            <ul class="relative p-0 mx-auto text-center hidden">
                <li class="py-4">
                    <Link href="/" class="text-burgandy font-semibold text-xl w-42 hover:text-red-600">Home</Link>
                </li>
                <li class="py-4">
                    <Link :href="route('pages.books')" class="text-burgandy font-semibold text-xl hover:text-red-600">Reading List <span
                        aria-hidden="true">&#x1F4DA;</span></Link>
                </li>
                <li class="py-4">
                    <Link :href="route('pages.projects')" class="text-burgandy font-semibold text-xl hover:text-red-600">Projects <span
                        aria-hidden="true">&#x1F5A5;</span></Link>
                </li>
                <li class="py-4">
                    <button @click="terrain.redraw" class="text-burgandy font-semibold text-xl hover:text-red-600">Redraw Terrain <span
                            aria-hidden="true">&#x1F3D4;</span></button>
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
    const hamburger = document.getElementById('hamburger-click');

    mobileMenu.classList.toggle('mobile-menu--open');
    mobileMenu.classList.toggle('z-negative');
    mobileMenu.classList.toggle('z-40');

    hamburger.classList.toggle('active-menu');

    pageContainer.classList.toggle('hidden')
    footer.classList.toggle('hidden')
    mobileMenu.querySelector('ul').classList.toggle('hidden');
}

</script>

<style>
.z-negative {
    z-index: -1;
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

.hamburger a.main-nav-toggle {
  display: block;
  width: 30px;
  position: absolute;
  height: 16px;
  left: -50px;
  top: 30px;
}
.hamburger a.main-nav-toggle:after, .hamburger a.main-nav-toggle:before {
  content: '';
  position: absolute;
  top: 0;
  height: 0;
  border-bottom: 4px solid #bbb;
  width: 100%;
  left: 0;
  right: 0;
  transition: all ease-out 0.3s;
}
.hamburger a.main-nav-toggle:after {
  top: 100%;
}
.hamburger a.main-nav-toggle i {
  display: block;
  text-indent: 100%;
  overflow: hidden;
  white-space: nowrap;
  height: 4px;
  background-color: #bbb;
  width: 100%;
  position: absolute;
  top: 50%;
  transition: all ease-out 0.1s;
}
.hamburger a.main-nav-toggle.active-menu:after {
  transform: rotate(-45deg);
  transform-origin: center;
  top: 50%;
}
.hamburger a.main-nav-toggle.active-menu:before {
  transform: rotate(45deg);
  transform-origin: center;
  top: 50%;
}
.hamburger a.main-nav-toggle.active-menu i {
  opacity: 0;
}
</style>