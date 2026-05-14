<script setup>
defineProps({
    message: {
        type: String,
    },
});
</script>

<template>
    <div v-show="message">
        <p class="dark:text-red-400 text-sm text-red-600">
            {{ message }}
        </p>
    </div>
</template>
