<template>
	<div class="min-h-screen bg-gray-50 py-8">
		<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="rounded-lg overflow-hidden">
				<!-- Header -->
				<div class="px-6 py-4">
					<h1 class="text-5xl font-bold text-gray-900">{{ proposal.title }}</h1>
					<p class="mt-1 text-sm text-gray-500">Created on {{ formatDate(proposal.created_at) }}</p>
				</div>

				<!-- Client Info -->
				<div class="px-6 py-4">
					<h2 class="text-lg font-medium text-gray-900">Client Information</h2>
					<p class="mt-1 text-gray-600">{{ proposal.client }}</p>
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
				<div class="px-6 py-4" v-if="proposal.client_signature">
					<h2 class="text-2xl font-medium text-gray-900 mb-4">Client Agreement</h2>
					<div class="mt-4">
						<p class="text-sm text-gray-600">Signed on {{ formatDate(proposal.client_sign_date) }}</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';

document.body.classList.remove('bg-black');

const proposal = ref(usePage().props.proposal);

const {
	description,
	scope,
	techStack,
	disclaimer,
	paymentSchedule
} = proposal.value.content;

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
