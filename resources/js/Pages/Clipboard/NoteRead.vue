<template>
    <Head title="Clipboard Paste" />

    <div class="mx-auto mt-24 max-w-lg">
        <label for="data" class="block text-center text-2xl font-medium leading-6 text-gray-50">Clipboard Note</label>
        <div class="mt-4 rounded-md bg-gray-800 p-4 text-gray-50 shadow-md">
            <p class="mt-2 break-words">{{ note }}</p>
        </div>

        <button
            @click="copyText"
            class="mx-auto mt-4 block rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
        >
            Copy Text
        </button>
        <p class="mt-4 text-center text-sm leading-6 text-gray-200">{{ message }}</p>
    </div>
</template>

<script>
import { Head } from "@inertiajs/vue3";

export default {
    components: {
        Head,
    },
    props: {
        note: {
            type: String,
            required: true,
        },
        noteId: {
            type: String,
            required: true,
        },
    },
    data() {
        return {
            message: "",
        };
    },
    methods: {
        copyText() {
            navigator.clipboard.writeText(this.note);
            this.message = "Text copied to clipboard";
        },
    },
};
</script>
