<template>
    <Head title="Secure Passwords" />
    <div class="mx-auto mt-24 max-w-lg">
        <label for="data" class="block text-center text-2xl font-medium leading-6 text-gray-50"
            >Encrypt Sensitive Data</label
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
        <p class="mt-3 text-center text-sm leading-6 text-gray-100">
            Include as much information as you want to be encrypted. This data will be stored in
            <a href="https://www.openpgp.org/about/" target="_blank">OpenPGP</a> encrypted format. My site is open
            source, you can verify this code
            <a
                href="https://github.com/AlextheYounga/alextheyounger-v3/blob/master/resources/js/Pages/Passwords/PasswordEncrypt.vue"
                >here</a
            >.
        </p>
        <button
            @click="submit"
            type="submit"
            class="mx-auto mt-4 block rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
        >
            Encrypt
        </button>
        <div v-if="link" class="mt-12 text-center text-sm leading-6 text-gray-100">
            <p class="text-lg">Encrypted data has been stored securely.</p>
            <p class="py-4 text-gray-100">
                <span class="text-indigo-400">Instructions:</span> Send me the following link.
            </p>
            <p>
                <span class="text-indigo-400">Decryption link:</span> <span class="underline">{{ link }}</span>
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
import { createMessage, encrypt } from "openpgp";

export default {
    components: {
        Head,
    },
    data() {
        return {
            textInput: "",
            encryptedData: "",
            link: "",
            message: "",
            decryptionKey: this.generateRandomPassword(),
        };
    },
    methods: {
        copyUrl() {
            navigator.clipboard.writeText(this.link);
            alert("URL copied to clipboard");
        },
        generateRandomPassword() {
            const characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
            const length = 16; // You can adjust the length as needed
            let result = "";
            for (let i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() * characters.length));
            }
            return result;
        },
        generateLink(uuid) {
            return `${window.location.origin}/secure-passwords/decrypt/${uuid}?${this.decryptionKey}`;
        },
        async store() {
            axios
                .post("/secure-passwords/store", { encryptedData: this.encryptedData })
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
                const message = await createMessage({ text: this.textInput });
                const encrypted = await encrypt({
                    message,
                    passwords: [this.decryptionKey],
                    format: "armored",
                });
                this.encryptedData = encrypted;
                console.log(this.encryptedData);
                this.store();
            } catch (error) {
                console.error("Encryption failed:", error);
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
