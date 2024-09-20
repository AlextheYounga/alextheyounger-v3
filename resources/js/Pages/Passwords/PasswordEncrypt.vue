<template>

	<Head title="Secure Passwords" />
	<div class="max-w-lg mx-auto mt-24">
		<label for="data" class="block text-2xl text-center font-medium leading-6 text-gray-50">Encrypt Sensitive Data</label>
		<div class="mt-2 mx-auto">
			<textarea id="data" name="data" rows="3"
					  class="block w-full rounded-md border-0 py-1.5 bg-slate-800 text-gray-50 shadow-sm ring-1 ring-inset ring-gray-600 placeholder:text-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
					  v-model="textInput"></textarea>
		</div>
		<p class="mt-3 text-sm leading-6 text-center text-gray-100">
			Include as much information as you want to be encrypted. This data will be stored in <a href="https://www.openpgp.org/OpenPGP" target="_blank">OpenPGP</a> encrypted format.
			My site is open source, you can verify this code <a href="https://github.com/AlextheYounga/alextheyounger-v3">here</a>.
		</p>
		<button @click="submit" type="submit"
				class="mt-4 mx-auto block rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
			Encrypt
		</button>
		<p v-if="link" class="mt-4 text-sm leading-6 text-center text-gray-100">
			Decrypt link: <a :href="link" target="_blank">{{ link }}</a>
		</p>
	</div>
</template>

<script>
import { Head } from '@inertiajs/vue3';
import { createMessage, encrypt } from 'openpgp';

export default {
	components: {
		Head
	},
	data() {
		return {
			textInput: '',
			encryptedData: '',
			link: '',
			decryptionKey: this.generateRandomPassword(),
		};
	},
	methods: {
		generateRandomPassword() {
			const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
			const length = 16; // You can adjust the length as needed
			let result = '';
			for (let i = 0; i < length; i++) {
				result += characters.charAt(Math.floor(Math.random() * characters.length));
			}
			return result;
		},
		generateLink(uuid) {
			return `${window.location.origin}/secure-passwords/decrypt/${uuid}?${this.decryptionKey}`;
		},
		async store() {
			axios.post('/secure-passwords/store', { encryptedData: this.encryptedData })
				.then(response => {
					const uuid = response.data;
					this.link = this.generateLink(uuid);
					console.log('Decryption link:', this.link);
				})
				.catch(error => {
					console.error('Error storing encrypted data:', error);
				});
		},
		async submit() {
			try {
				const message = await createMessage({ text: this.textInput });
				const encrypted = await encrypt({
					message,
					passwords: [this.decryptionKey],
					format: 'armored'
				});
				this.encryptedData = encrypted;
				console.log(this.encryptedData);
				this.store();
			} catch (error) {
				console.error('Encryption failed:', error);
			}
		},
	},
};
</script>

<style scoped>
a {
	color: #6366f1 !important;
	text-decoration: underline;
}
</style>
