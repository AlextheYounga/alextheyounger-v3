<template>
    <Head title="Clipboard" />
    <div class="mx-auto mt-24 max-w-lg">
        <label for="data" class="block text-center text-2xl font-medium leading-6 text-gray-50"
            >Save to Clipboard</label
        >
        <div class="mx-auto mt-2">
            <textarea
                id="data"
                name="data"
                rows="3"
                class="block w-full rounded-md border-0 bg-slate-800 py-1.5 text-gray-50 shadow-sm ring-1 ring-inset ring-gray-600 placeholder:text-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                v-model="textInput"
            ></textarea>
        </div>
        <button
            @click="submit"
            type="submit"
            class="mx-auto mt-4 block rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
        >
            Save Note
        </button>
        <div v-if="link" class="mt-12 text-center text-sm leading-6 text-gray-100">
            <p class="text-lg">
                Note has been stored under the ID: <span class="text-xl text-red-600">{{ this.noteId }}</span>
            </p>
            <p class="text-lg">
                This data is stored in plain text. Use my <a href="/secure-passwords">secure-passwords</a> route for
                sensitive data.
            </p>
            <p class="mt-6">
                <span>Note link: </span>
                <a :href="link">{{ link }}</a>
            </p>

            <button
                @click="copyUrl"
                type="submit"
                class="mx-auto mt-4 block rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
            >
                Copy Link
            </button>
        </div>
    </div>
</template>

<script>
import { Head } from "@inertiajs/vue3";

export default {
    components: {
        Head,
    },
    data() {
        return {
            textInput: "",
            link: "",
            message: "",
            noteId: this.generateRandomNoteId(),
        };
    },
    methods: {
        copyUrl() {
            navigator.clipboard.writeText(this.link);
            alert("URL copied to clipboard");
        },
        generateRandomNoteId() {
            const characters = "abcdefghijklmnopqrstuvwxyz";
            const length = 6; // You can adjust the length as needed
            let result = "";
            for (let i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() * characters.length));
            }
            return result;
        },
        generateLink(noteId) {
            return `${window.location.origin}/clipboard/${noteId}`;
        },
        async store() {
            axios
                .post("/clipboard/store", { noteId: this.noteId, note: this.textInput })
                .then((response) => {
                    const uuid = response.data;
                    this.link = this.generateLink(uuid);
                    console.log("Decryption link:", this.link);
                })
                .catch((error) => {
                    console.error("Error storing encrypted data:", error);
                });
        },
        async submit() {
            try {
                this.store();
            } catch (error) {
                console.error("Note creation failed", error);
            }
        },
    },
};
</script>

<style scoped>
a {
    color: #818cf8 !important;
    text-decoration: underline;
}
</style>
