<template>
    <div class="bg-stone-50 w-full z-10 fixed top-0">
        <div class="w-1/4 ml-auto flex justify-around">
            <Link :href="route('books.index')" class="text-burgandy font-semibold text-xl">Reading List</Link>
            <Link :href="route('projects.index')" class="text-burgandy font-semibold text-xl">Projects</Link>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
</script>