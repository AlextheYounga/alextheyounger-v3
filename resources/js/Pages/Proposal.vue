<template>
	<Head title="Proposal" />
	<div class="min-h-screen bg-gray-50 py-8">
		<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="rounded-lg overflow-hidden">
				<!-- Header -->
				<div class="px-6 py-4">
					<h1 class="text-5xl font-bold text-gray-900">{{ proposal.title }}</h1>
					<p class="mt-1 text-sm text-gray-500">Created on {{ formatDate(proposal.created_at) }}</p>
				</div>

				<!-- Client Info -->
				<div class="px-6 pb-4">
					<p class="mt-1 text-gray-600"><b>Prepared for</b>: {{ proposal.client }}</p>
					<p class="mt-1 text-gray-600"><b>Prepared by</b>: Alex Younger</p>
				</div>

				<!-- Description -->
				<div class="px-6 py-4" v-if="description">
					<h2 class="text-2xl font-medium text-gray-900 mb-4">Project Description</h2>
					<div class="prose max-w-none" v-html="description"></div>
				</div>

				<!-- Scope -->
				<div class="px-6 py-4" v-if="scope">
					<h2 class="text-2xl font-medium text-gray-900 mb-4">Project Scope</h2>
					<div class="prose max-w-none" v-html="scope"></div>
				</div>

				<!-- Tech Stack -->
				<div class="px-6 py-4" v-if="techStack">
					<h2 class="text-2xl font-medium text-gray-900 mb-4">Technology Stack</h2>
					<div class="prose max-w-none" v-html="techStack"></div>
				</div>

				<!-- Line Items -->
				<div class="px-6 py-4" v-if="proposal.line_items && proposal.line_items.length">
					<h2 class="text-2xl font-medium text-gray-900 mb-4">Pricing</h2>
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

				<!-- Payment Schedule -->
				<div class="px-6 py-4" v-if="paymentSchedule && paymentSchedule.length">
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
								<tr v-for="(item, index) in paymentSchedule" :key="index">
									<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ item.milestone }}</td>
									<td class="px-6 py-4 text-sm text-gray-900">{{ item.description }}</td>
									<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${{ formatPrice(item.amount_due) }}</td>
									<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">{{ formatDate(item.date) }}</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

				<!-- Disclaimer -->
				<div class="px-6 py-4" v-if="disclaimer">
					<h2 class="text-2xl font-medium text-gray-900 mb-4">Disclaimer</h2>
					<div class="prose max-w-none" v-html="disclaimer"></div>
				</div>

				<!-- Client Agreement -->
				<div id="sign-input" class="px-6 py-4" v-if="!proposal.client_signature">
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
								<input type="text" v-model="signature" class="block border-0 rounded-md text-4xl w-full yesteryear" placeholder="Type your full name">
							</div>
						</div>

						<div>
							<button type="submit" :disabled="!canSubmit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
								Sign and Agree
							</button>
						</div>
					</form>
				</div>

				<div class="px-6 py-4" v-else>
					<h2 class="text-2xl font-medium text-gray-900 mb-4">Client Agreement</h2>
					<div class="mt-4">
						<p class="text-lg text-gray-600"><b class="pr-4">Signed by:</b> <span class="yesteryear signature text-5xl">{{ proposal.client_signature }}</span></p>
						<p class="text-lg text-gray-600 mt-2"><b class="pr-4">Signed on:</b> <span class="underline">{{ formatDate(proposal.client_sign_date) }}</span></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { usePage, Head, router } from '@inertiajs/vue3';

document.body.classList.remove('bg-black');

const proposal = ref(usePage().props.proposal);
const agreement = ref(false);
const signature = ref('');

const {
	description,
	scope,
	techStack,
	disclaimer,
	paymentSchedule
} = proposal.value.content;

const canSubmit = computed(() => {
	return agreement.value && signature.value.trim().length > 0;
});

const submitAgreement = () => {
	router.post(`/proposals/${proposal.value.hash}/sign`, {
		signature: signature.value,
		agreement: agreement.value
	}, {
		preserveScroll: true,
		onSuccess: () => {
			// Update the local proposal data
			proposal.value.client_signature = signature.value;
			proposal.value.client_agreement = true;
			proposal.value.client_sign_date = new Date().toISOString();
			
			// Clear the form
			signature.value = '';
			agreement.value = false;
		}
	});
};

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
</style>