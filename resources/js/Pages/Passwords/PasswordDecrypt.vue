<template>
    <Head title="Secure Passwords Decrypt" />

    <div class="mx-auto mt-24 max-w-lg">
        <div v-if="!confirm" class="mt-4 rounded-md bg-gray-800 p-4 text-gray-50 shadow-md">
            <p class="text-center text-xl">
                Are you sure you're ready to view the decrypted data? This data can be viewed only once. Clicking yes
                will destroy the data.
            </p>
            <div class="mt-4 flex justify-center space-x-4">
                <button
                    @click="decryptData"
                    class="rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600"
                >
                    Yes
                </button>
                <a
                    href="/secure-passwords"
                    class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600"
                >
                    No
                </a>
            </div>
        </div>
        <div v-else>
            <label for="data" class="block text-center text-2xl font-medium leading-6 text-gray-50"
                >Decrypted Sensitive Data</label
            >
            <div class="mt-4 rounded-md bg-gray-800 p-4 text-gray-50 shadow-md">
                <p class="mt-2 break-words">{{ decryptedData }}</p>
            </div>
            <p class="mt-3 text-center text-sm leading-6 text-gray-100">
                This data has been destroyed from the database. Make sure to save it.
            </p>

            <button
                @click="copyText"
                class="mx-auto mt-4 block rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
            >
                Copy Text
            </button>
            <p class="mt-4 text-center text-sm leading-6 text-gray-200">{{ message }}</p>
        </div>
    </div>
</template>

<script>
import { Head } from "@inertiajs/vue3";
import { readMessage, decrypt } from "openpgp";

export default {
    components: {
        Head,
    },
    props: {
        encryptedData: {
            type: String,
            required: true,
        },
    },
    data() {
        return {
            confirm: false,
            decryptedData: "",
            uuid: window.location.pathname.split("/").pop(),
            decryptionKey: window.location.search.split("?")[1],
            message: "",
        };
    },
    methods: {
        copyText() {
            navigator.clipboard.writeText(this.decryptedData);
            this.message = "Text copied to clipboard";
        },
        async decryptData() {
            try {
                const message = await readMessage({
                    armoredMessage: this.encryptedData, // parse armored message
                });
                const { data: decrypted } = await decrypt({
                    message,
                    passwords: [this.decryptionKey],
                    format: "utf8",
                });

                this.confirm = true;
                this.decryptedData = decrypted;
                this.destroy();
            } catch (error) {
                console.error("Encryption failed:", error);
            }
        },
        async destroy() {
            axios
                .get(`/secure-passwords/destroy/${this.uuid}`)
                .then((response) => {
                    console.log("Destroyed password:", response);
                })
                .catch((error) => {
                    console.error("Error destroying encrypted data:", error);
                });
        },
    },
};
</script>
