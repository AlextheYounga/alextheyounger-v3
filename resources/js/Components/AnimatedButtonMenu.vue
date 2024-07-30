<template>
  <div class="fixed top-1 left-1 md:left-12 z-50">
    <button class="relative group border-sky-100 border-2">
      <!-- button container -->
      <div @click="clickHandler" class="relative z-20 flex items-center justify-center rounded-full w-[50px] h-[50px] transform transition-all bg-slate-700 ring-0 ring-gray-300 hover:ring-8 group-focus:ring-8 ring-opacity-30 duration-200 shadow-md">
        <div class="flex flex-col justify-between w-[20px] h-[20px] transform transition-all duration-300 group-focus:-rotate-[45deg] origin-center">
          <div class="bg-white h-[2px] w-1/2 rounded transform transition-all duration-300 group-focus:-rotate-90 group-focus:h-[1px] origin-right delay-75 group-focus:-translate-y-[1px]"></div>
          <div class="bg-white h-[1px] rounded"></div>
          <div class="m-0 bg-white h-[2px] w-1/2 rounded self-end transform transition-all duration-300 group-focus:-rotate-90 group-focus:h-[1px] origin-left delay-75 group-focus:translate-y-[1px]"></div>
        </div>
      </div>
      <!-- End button container -->

      <!-- Menu List -->
      <div class="absolute bg-white w-0 h-0 overflow-hidden rounded-md left-6 top-5 transition-all group-focus:h-[162px] group-focus:w-40 delay-150 duration-300 shadow-xl">
        <ul class="divide-y py-2 text-right text-sm text-sky-700">
          <li v-for="item in menuList" :key="item.name" class="flex justify-end hover:bg-gray-100">
            <Link :href="route(item.link)" class="px-4 py-2 w-full">
            {{ item.name }}
            </Link>
          </li>
        </ul>
      </div>
    </button>
  </div>
</template>


<script setup>
import { Link } from '@inertiajs/vue3';

const menuList = [
  { name: 'Home', link: "home" },
  { name: 'Reading List', link: "pages.books" },
  { name: 'Projects', link: "pages.projects" },
  { name: 'Explore Starfield', link: "pages.starfield" },
];

</script>

<style scoped>
  button {
    border-radius: 100px;
  }
</style>