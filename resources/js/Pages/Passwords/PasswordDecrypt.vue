<template>

	<Head title="Secure Passwords Decrypt" />

	<div class="max-w-lg mx-auto mt-24">
		<label for="data" class="block text-2xl text-center font-medium leading-6 text-gray-50">Decrypted Sensitive Data</label>
		<div class="mt-4 p-4 bg-gray-800 text-gray-50 rounded-md shadow-md">
			<p class="mt-2 break-words">{{ decryptedData }}</p>
		</div>
		<p class="mt-3 text-sm leading-6 text-center text-gray-100">This data has been destroyed from the database. Make sure to save it.</p>

		<button @click="copyText"
				class="mt-4 mx-auto block rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
			Copy Text
		</button>
		<p class="mt-4 text-sm leading-6 text-center text-gray-200">{{ message }}</p>
	</div>
</template>

<script>
import { Head } from '@inertiajs/vue3';
import { readMessage, decrypt } from 'openpgp';

export default {
	components: {
		Head
	},
	props: {
		encryptedData: {
			type: String,
			required: true
		}
	},
	data() {
		return {
			decryptedData: '',
			decryptionKey: window.location.search.split('?')[1],
			message: ''
		};
	},
	methods: {
		copyText() {
			navigator.clipboard.writeText(this.decryptedData);
			this.message = 'Text copied to clipboard';
		},
		async decryptData() {
			try {
				const message = await readMessage({
					armoredMessage: this.encryptedData // parse armored message
				});
				const { data: decrypted } = await decrypt({
					message,
					passwords: [this.decryptionKey],
					format: 'utf8'
				});

				this.decryptedData = decrypted;
			} catch (error) {
				console.error('Encryption failed:', error);
			}
		},
	},
	mounted() {
		console.log(this.encryptedData);
		this.decryptData();
	}
};
</script>
