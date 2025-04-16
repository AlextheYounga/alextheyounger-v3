<template>

	<Head title="Proposal" />
	<div id="proposal" class="min-h-screen py-12">
		<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="rounded-lg overflow-hidden">
				<div class="px-6 space-y-6">
					<!-- Header -->
					<div>
						<h1 class="text-5xl font-bold text-gray-900">{{ proposal.title }}</h1>
						<p class="mt-1 text-sm text-gray-500">Created on {{ formatDate(proposal.created_at) }}</p>
						<p class="mt-3 text-sm text-gray-600"><b>Prepared for</b>: {{ proposal.client }}</p>
						<p class="mt-1 text-sm text-gray-600"><b>Prepared by</b>: Alex Younger</p>
					</div>

					<!-- Description -->
					<div v-if="description">
						<h2 class="text-2xl font-medium text-gray-900 mb-4">Project Description</h2>
						<div class="prose max-w-none" v-html="description"></div>
					</div>

					<!-- Scope -->
					<div v-if="scope">
						<h2 class="text-2xl font-medium text-gray-900 mb-4">Project Scope</h2>
						<div class="prose max-w-none" v-html="scope"></div>
					</div>

					<!-- Completion Date -->
					<div v-if="proposal.completion_date">
						<div class="flex items-center my-8">
							<h2 class="text-2xl font-medium text-gray-900 mr-4">Estimated Completion Date: </h2>
							<p class="text-lg text-gray-600">{{ formatDate(proposal.completion_date) }}</p>
						</div>
					</div>

					<!-- Tech Stack -->
					<div v-if="technology">
						<h2 class="text-2xl font-medium text-gray-900 mb-4">Technology Stack</h2>
						<div class="prose max-w-none" v-html="technology"></div>
					</div>

					<!-- Line Items -->
					<div v-if="proposal.line_items && Object.entries(proposal.line_items).length">
						<div class="my-12">
							<h2 class="text-2xl font-medium text-gray-900 mb-4">Line Items</h2>
							<div class="mt-4 overflow-x-auto">
								<table class="min-w-full divide-y divide-gray-200">
									<thead class="bg-gray-50">
										<tr>
											<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
											<th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
										</tr>
									</thead>
									<tbody class="bg-white divide-y divide-gray-200">
										<tr v-for="(item, index) in proposal.line_items" :key="index">
											<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ item.description }}</td>
											<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${{ formatPrice(item.price) }}</td>
										</tr>
										<tr class="font-bold">
											<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Total</td>
											<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${{ formatPrice(proposal.total) }}</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>

					<!-- Payment Schedule -->
					<div v-if="paymentSchedule && Object.entries(paymentSchedule).length">
						<div class="my-12">
							<h2 class="text-2xl font-medium text-gray-900 mb-4">Payment Schedule</h2>
							<div class="mt-4 overflow-x-auto">
								<table class="min-w-full divide-y divide-gray-200">
									<thead class="bg-gray-50">
										<tr>
											<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Milestone</th>
											<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
											<th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount Due</th>
											<th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
										</tr>
									</thead>
									<tbody class="bg-white divide-y divide-gray-200">
										<tr v-for="item in paymentSchedule" :key="item.date">
											<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ item?.milestone }}</td>
											<td class="px-6 py-4 text-sm text-gray-900">{{ item?.description }}</td>
											<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${{ formatPrice(item?.amount_due) }}</td>
											<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">{{ item?.date }}</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>

					<!-- Disclaimer -->
					<div v-if="disclaimer">
						<div class="mb-12">
							<h2 class="text-2xl font-medium text-gray-900 mb-4">Disclaimer</h2>
							<div class="prose max-w-none" v-html="disclaimer"></div>
						</div>
					</div>

					<!-- Client Agreement -->
					<div id="client-agreement" class="rounded border p-8 shadow">
						<div class="no-print" v-if="!proposal.client_sign_date">
							<h2 class="text-2xl font-medium text-gray-900 mb-4">Client Agreement</h2>
							<form @submit.prevent="submitAgreement" class="space-y-6">
								<div>
									<label class="flex items-center">
										<input type="checkbox" v-model="agreement" class="form-checkbox h-5 w-5 text-indigo-600">
										<span class="ml-2 text-gray-700">I agree to the terms and conditions outlined in this proposal</span>
									</label>
								</div>

								<div>
									<label class="block text-sm font-medium text-gray-700">Digital Signature</label>
									<div class="mt-1">
										<input type="text" v-model="signature" class="block border-0 rounded-md text-4xl w-full yesteryear" placeholder="Your Name">
									</div>
								</div>

								<div>
									<button type="submit" :disabled="!canSubmit"
											class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
										Sign and Agree
									</button>
								</div>
							</form>
						</div>


						<div v-if="proposal.client_sign_date">
							<h2 class="text-2xl font-medium text-gray-900 mb-4">Client Agreement</h2>
							<div class="mt-4 flex justify-between items-center">
								<p class="text-lg text-gray-600"><b class="pr-4">Signed by:</b> <span class="yesteryear signature text-5xl">{{ proposal.client_signature }}</span></p>
								<p class="text-lg text-gray-600 mt-2"><b class="pr-4">Signed on:</b> <span class="underline">{{ formatDate(proposal.client_sign_date) }}</span></p>
							</div>


							<div class="no-print">
								<button @click="printPage"
										class="mt-6 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-500 hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-400">
									<PrinterIcon class="h-5 w-5 mr-2" />
									Save Document
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script setup>
import { ref, computed, defineProps } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import { PrinterIcon } from '@heroicons/vue/24/outline'

document.body.classList.remove('bg-black');

const { proposal } = defineProps({
	proposal: Object
});
const agreement = ref(false);
const signature = ref('');

const {
	description,
	scope,
	technology,
	disclaimer,
	payment_schedule: paymentSchedule
} = proposal.content;

const canSubmit = computed(() => {
	return agreement.value && signature.value.trim().length > 0;
});

const submitAgreement = () => {
	router.post(`/proposals/${proposal.hash}/sign`, {
		signature: signature.value,
		agreement: agreement.value
	}, {
		preserveScroll: true,
		onSuccess: () => {
			// Update the local proposal data
			proposal.client_signature = signature.value;
			proposal.client_agreement = true;
			proposal.client_sign_date = new Date().toISOString();

			// Clear the form
			signature.value = '';
			agreement.value = false;
		}
	});
};

const printPage = () => {
	window.print();
}

const formatDate = (date) => {
	return new Date(date).toLocaleDateString('en-US', {
		year: 'numeric',
		month: 'long',
		day: 'numeric'
	});
};

const formatPrice = (price) => {
	return parseFloat(price).toFixed(2);
};
</script>

<style scoped>
.yesteryear {
	font-family: "Yesteryear", cursive;
	font-weight: 400;
	font-style: normal;
}

body {
	background: #f9fafb;
}

input:focus {
	outline: none !important;
	box-shadow: none !important;
}

@media print {

	/* Hide elements not needed on print */
	.no-print {
		display: none !important;
	}

	body {
		background: #fff !important;
	}

	#client-agreement {
		padding: 0 !important;
		border: none !important;
		box-shadow: none !important;
	}
}
</style>